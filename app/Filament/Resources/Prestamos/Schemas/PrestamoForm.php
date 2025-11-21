<?php

namespace App\Filament\Resources\Prestamos\Schemas;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Placeholder;
use App\Models\Estudiante;
use App\Models\Item;
use App\Models\Prestamo;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrestamoForm
{
    //use CreateRecord\Concerns\HasWizard;
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Registrar Nuevo Préstamo')
                    ->schema([

                        // estudiante
                        Select::make('estudiante_id')
                            ->label('Estudiante')
                            ->relationship('estudiante', 'apellidos')
                            ->getOptionLabelFromRecordUsing(fn (Estudiante $record) => "{$record->apellidos}, {$record->nombres} ({$record->carnet})")
                            ->searchable(['apellidos', 'nombres', 'carnet'])
                            ->preload()
                            ->required(),

                        // fecha
                        DateTimePicker::make('momento_prestamo')
                            ->label('Fecha y Hora del Préstamo')
                            ->default(now())
                            ->required(),

                        //item
                        Select::make('item_id')
                            ->label('Ítem')
                            ->relationship(
                                name: 'item',
                                titleAttribute: 'id',
                                modifyQueryUsing: function (Builder $query) {
                                    // Mostrar solo items disponibles
                                    $query->where('estado_disponibilidad', 'Disponible');

                                    // FILTRADO POR ROL:
                                    //$user = auth()->user();
                                    $user = Auth::user();
                                    /** @var \App\Models\User $user */ // <--- AGREGA ESTA LÍNEA MÁGICA -- investigar la linea magica
                                    if ($user) {
                                        if ($user->hasRole('Encargado de Tablet')) {
                                            // solo Tablets
                                            $query->where('tipo', 'Tablet');
                                        } elseif ($user->hasRole('Encargado de Tesis')) {
                                            // solo Tesis
                                            $query->where('tipo', 'Tesis');
                                        }
                                        // admin / super_admin no se filtran aquí
                                    }

                                    return $query;
                                }

                            )
                            ->getOptionLabelFromRecordUsing(function (Item $record) {
                                $record->loadMissing(trim($record->tipo) === 'Tablet' ? 'tablet' : 'tesis');
                                if (trim($record->tipo) === 'Tablet') {
                                    if (!$record->tablet) { return "¡ERROR! Item Tablet huérfano (ID: {$record->id})"; }
                                    return "TABLET: {$record->tablet->marca} {$record->tablet->modelo} (Cod: {$record->tablet->codigo})";
                                }
                                if (trim($record->tipo) === 'Tesis') {
                                    if (!$record->tesis) { return "¡ERROR! Item Tesis huérfano (ID: {$record->id})"; }
                                    return "TESIS: {$record->tesis->titulo}";
                                }
                                return "Item #{$record->id}";
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            // reglas
                            ->rules([
                                // principal
                                function (Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {

                                        // obtener los datos
                                        $estudianteId = $get('estudiante_id');
                                        $item = Item::find($value); // item a prestar

                                        if (!$estudianteId || !$item) {
                                            return; // si no hay item o estudiante no se valida
                                        }

                                        // verifiar si hay algun prstmao activo
                                        $prestamoActivo = Prestamo::where('estudiante_id', $estudianteId)
                                            ->whereNull('momento_entrega') // que este activo
                                            ->whereHas('item', function ($query) use ($item) {
                                                // del mismo tipo (Tablet o Tesis)
                                                $query->where('tipo', $item->tipo);
                                            })
                                            ->exists(); //para consultar si hay algun prestamo

                                        // si hay algun prestamos rechazamos la validacion con un mensaje
                                        if ($prestamoActivo) {
                                            $fail("El estudiante ya tiene un préstamo activo de una {$item->tipo}.");
                                        }

                                        // NUEVA validación: comprobar que el rol del usuario coincide con el tipo del item
                                        //$user = auth()->user();
                                        $user = Auth::user();
                                        /** @var \App\Models\User $user */ // <--- AGREGA ESTA LÍNEA MÁGICA -- investigar la linea magica
                                        if ($user) {
                                            if ($user->hasRole('Encargado de Tablet') && strtolower(trim($item->tipo)) !== 'tablet') {
                                                $fail('No tienes permiso para prestar ese tipo de ítem.');
                                            }
                                            if ($user->hasRole('Encargado de Tesis') && strtolower(trim($item->tipo)) !== 'tesis') {
                                                $fail('No tienes permiso para prestar ese tipo de ítem.');
                                            }
                                        }
                                    };
                                },
                            ]),
                        // --------------
                        // desplegable de actividad par atablte
                        Select::make('actividad_tablet')
                            ->label('Actividad a realizar con la Tablet')
                            ->options([
                                'Lectura de libro digital' => 'Lectura de libro digital',
                                'Uso de BD para investigación' => 'Uso de BD para investigación',
                                'Trabajo universitario' => 'Trabajo universitario',
                                'Otro' => 'Otro',
                            ])
                            ->required()
                            ->live() // en caso de que sea otro
                            // para ver si se selecciona una tablet o no
                            ->visible(function (Get $get) {
                                // buscar si que tipo de item se seleccion
                                $item = Item::find($get('item_id'));
                                // para comprar si la tablet existe o no y filtrar en minusculas
                                return $item && strtolower(trim($item->tipo)) === 'tablet';
                            }),

                        //para el caso de otro en tablet
                        TextInput::make('actividad_tablet_otro')
                            ->label('Especifique la actividad')
                            ->required()
                            ->visible(fn (Get $get) => $get('actividad_tablet') === 'Otro'),

                        // en caso de ser tesis
                        Placeholder::make('info_tesis')
                            ->content('Este préstamo es una Tesis, no se requiere actividad.')
                            // para ver si es una tesis
                            ->visible(function (Get $get) {
                                // busca el item en el formulario
                                $item = Item::find($get('item_id'));
                                // para comprbar si el item existe o no, filtrando en minusculas
                                return $item && strtolower(trim($item->tipo)) === 'tesis';
                            }),
                    ])
                    ->columns(2),
            ])
            ->columns(1);
    }
}
