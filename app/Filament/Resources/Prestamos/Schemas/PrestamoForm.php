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

// Servicios
use App\Services\EstudianteFinder;

class PrestamoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Registrar Nuevo Prestamo')
                    ->schema([

                        // dni - carnet
                        TextInput::make('dni')
                            ->label('DNI / Carnet del Estudiante')
                            ->numeric()
                            ->length(8)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {

                                if (!$state || strlen($state) !== 8) {
                                    return;
                                }

                                // llamada al EstudianteFinder
                                $finder = app(EstudianteFinder::class);
                                $est = $finder->buscarOCrearPorDni($state);

                                if ($est) {
                                    // autoseleccionar estudiante
                                    $set('estudiante_id', $est->id);
                                }
                            }),

                        // select estudiante
                        Select::make('estudiante_id')
                            ->label('Estudiante')
                            ->relationship('estudiante', 'apellidos')
                            ->getOptionLabelFromRecordUsing(fn (Estudiante $record) =>
                                "{$record->apellidos}, {$record->nombres} ({$record->carnet})"
                            )
                            ->searchable(['apellidos', 'nombres', 'carnet'])
                            ->preload()
                            ->required(),

                        // fecha y hora del prestamo
                        DateTimePicker::make('momento_prestamo')
                            ->label('Fecha y Hora del Prestamo')
                            ->default(now())
                            ->required(),

                        // item a prestar
                        Select::make('item_id')
                            ->label('Item')
                            ->relationship(
                                name: 'item',
                                titleAttribute: 'id',
                                modifyQueryUsing: function (Builder $query) {

                                    $query->where('estado_disponibilidad', 'Disponible');

                                    $user = Auth::user();
                                    /** @var \App\Models\User $user */

                                    if ($user) {
                                        if ($user->hasRole('Encargado de Tablet')) {
                                            $query->where('tipo', 'Tablet');
                                        } elseif ($user->hasRole('Encargado de Tesis')) {
                                            $query->where('tipo', 'Tesis');
                                        }
                                    }

                                    return $query;
                                }
                            )
                            ->getOptionLabelFromRecordUsing(function (Item $record) {

                                $record->loadMissing(trim($record->tipo) === 'Tablet' ? 'tablet' : 'tesis');

                                if (trim($record->tipo) === 'Tablet') {
                                    if (!$record->tablet) { return "ERROR Tablet Huerfano (ID {$record->id})"; }
                                    return "TABLET: {$record->tablet->marca} {$record->tablet->modelo} (Cod: {$record->tablet->codigo})";
                                }

                                if (trim($record->tipo) === 'Tesis') {
                                    if (!$record->tesis) { return "ERROR Tesis Huerfana (ID {$record->id})"; }
                                    return "TESIS: {$record->tesis->titulo}";
                                }

                                return "Item #{$record->id}";
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->rules([

                                function (Get $get) {

                                    return function (string $attribute, $value, \Closure $fail) use ($get) {

                                        $estudianteId = $get('estudiante_id');
                                        $item = Item::find($value);

                                        if (!$estudianteId || !$item) {
                                            return;
                                        }

                                        // validacion de prestamos activos del mismo tipo
                                        $prestamoActivo = Prestamo::where('estudiante_id', $estudianteId)
                                            ->whereNull('momento_entrega')
                                            ->whereHas('item', function ($q) use ($item) {
                                                $q->where('tipo', $item->tipo);
                                            })
                                            ->exists();

                                        if ($prestamoActivo) {
                                            $fail("El estudiante ya tiene un prestamo activo de una {$item->tipo}.");
                                        }

                                        // validacion segun roles
                                        $user = Auth::user();
                                        /** @var \App\Models\User $user */

                                        if ($user) {
                                            if ($user->hasRole('Encargado de Tablet') && strtolower(trim($item->tipo)) !== 'tablet') {
                                                $fail('No tienes permiso para prestar este tipo de item.');
                                            }
                                            if ($user->hasRole('Encargado de Tesis') && strtolower(trim($item->tipo)) !== 'tesis') {
                                                $fail('No tienes permiso para prestar este tipo de item.');
                                            }
                                        }

                                    };
                                }
                            ]),

                        // actividad tablet
                        Select::make('actividad_tablet')
                            ->label('Actividad a realizar con la Tablet')
                            ->options([
                                'Lectura de libro digital' => 'Lectura de libro digital',
                                'Uso de BD para investigacion' => 'Uso de BD para investigacion',
                                'Trabajo universitario' => 'Trabajo universitario',
                                'Otro' => 'Otro',
                            ])
                            ->required()
                            ->live()
                            ->visible(function (Get $get) {
                                $item = Item::find($get('item_id'));
                                return $item && strtolower(trim($item->tipo)) === 'tablet';
                            }),

                        TextInput::make('actividad_tablet_otro')
                            ->label('Especifique la actividad')
                            ->required()
                            ->visible(fn (Get $get) => $get('actividad_tablet') === 'Otro'),

                        // info tesis
                        Placeholder::make('info_tesis')
                            ->content('Este prestamo es una Tesis, no se requiere actividad.')
                            ->visible(function (Get $get) {
                                $item = Item::find($get('item_id'));
                                return $item && strtolower(trim($item->tipo)) === 'tesis';
                            }),

                    ])
                    ->columns(2),

            ])
            ->columns(1);
    }
}
