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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Unique;
//use Filament\Forms\Get;

class PrestamoForm
{
    //use CreateRecord\Concerns\HasWizard;
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // 2. NO hay 'Wizard', solo una Sección
                Section::make('Registrar Nuevo Préstamo')
                    ->schema([

                        // --- Campo Estudiante ---
                        Select::make('estudiante_id')
                            ->label('Estudiante')
                            ->relationship('estudiante', 'apellidos')
                            ->getOptionLabelFromRecordUsing(fn (Estudiante $record) => "{$record->apellidos}, {$record->nombres} ({$record->carnet})")
                            ->searchable(['apellidos', 'nombres', 'carnet'])
                            ->preload()
                            ->required(),

                        // --- Campo Fecha ---
                        DateTimePicker::make('momento_prestamo')
                            ->label('Fecha y Hora del Préstamo')
                            ->default(now())
                            ->required(),

                        // --- Campo Ítem ---
                        Select::make('item_id')
                            ->label('Ítem')
                            ->relationship(
                                name: 'item',
                                titleAttribute: 'id',
                                modifyQueryUsing: fn (Builder $query) =>
                                    $query->where('estado_disponibilidad', 'Disponible')
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
                                // La regla principal
                                function (Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {

                                        // A. Obtener los datos
                                        $estudianteId = $get('estudiante_id');
                                        $item = Item::find($value); // El ítem que se quiere prestar

                                        if (!$estudianteId || !$item) {
                                            return; // Si no hay estudiante o ítem, no validar
                                        }

                                        // B. Buscar si ya existe un préstamo ACTIVO
                                        $prestamoActivo = Prestamo::where('estudiante_id', $estudianteId)
                                            ->whereNull('momento_entrega') // <-- Que esté activo
                                            ->whereHas('item', function ($query) use ($item) {
                                                // Del MISMO TIPO (Tablet o Tesis)
                                                $query->where('tipo', $item->tipo);
                                            })
                                            ->exists(); // <-- Solo preguntar si existe

                                        // C. Si existe, fallar la validación (RF-18 y RF-19)
                                        if ($prestamoActivo) {
                                            $fail("El estudiante ya tiene un préstamo activo de una {$item->tipo}.");
                                        }
                                    };
                                },
                            ]),
                        // --------------
                        // 2. El desplegable de Actividad (para Tablets)
                        Select::make('actividad_tablet')
                            ->label('Actividad a realizar con la Tablet')
                            ->options([
                                'Lectura de libro digital' => 'Lectura de libro digital',
                                'Uso de BD para investigación' => 'Uso de BD para investigación',
                                'Trabajo universitario' => 'Trabajo universitario',
                                'Otro' => 'Otro',
                            ])
                            ->required()
                            ->live() // Necesario para mostrar/ocultar el campo 'Otro'
                            ->visible(function (Get $get) {
                                $item = Item::find($get('item_id'));
                                // Comparamos el tipo de la DB (normalizado) con 'Tablet'
                                return $item && strtolower(trim($item->tipo)) === 'tablet';
                            }),

                        // 3. El campo de texto "Otro" (solo si se selecciona 'Otro' en 'actividad_tablet')
                        TextInput::make('actividad_tablet_otro')
                            ->label('Especifique la actividad')
                            ->required()
                            ->visible(fn (Get $get) => $get('actividad_tablet') === 'Otro'),

                        // 4. Mensaje para Tesis (solo si se selecciona una Tesis)
                        Placeholder::make('info_tesis')
                            ->content('Este préstamo es una Tesis, no se requiere actividad.')
                            ->visible(function (Get $get) {
                                $item = Item::find($get('item_id'));
                                // Comparamos el tipo de la DB (normalizado) con 'Tesis'
                                return $item && strtolower(trim($item->tipo)) === 'tesis';
                            }),

                        // --- FIN: LÓGICA CONDICIONAL DE ACTIVIDAD ---
                    ])
                    ->columns(2), // 4. Poner los campos en 2 columnas
            ])
            ->columns(1);
    }
}
