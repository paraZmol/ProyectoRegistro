<?php

namespace App\Filament\Resources\Prestamos\Pages;

// 1. IMPORTACIONES NECESARIAS
use App\Filament\Resources\Prestamos\PrestamoResource;
use Filament\Resources\Pages\CreateRecord;
//use Filament\Forms\Components\Wizard; // El componente principal del Asistente
use CreateRecord\Concerns\HasWizard; // El Trait para que funcione
//use Filament\Forms\Components\Step; // Para cada paso
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
//use Filament\Forms\Get; // Para lógica reactiva/condicional
use App\Models\Item;
use App\Models\Estudiante;
use Carbon\Carbon; // Para la fecha y hora
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Utilities\Get;

class CreatePrestamo extends CreateRecord
{
    // 2. USAR EL TRAIT DE WIZARD
    //use HasWizard;

    protected static string $resource = PrestamoResource::class;


    // 3. DEFINIR LOS PASOS DEL FORMULARIO
    protected function getSteps(): array
    {
        return [
            Wizard::make([
            Step::make('Estudiante')
                ->description('Seleccione al estudiante')
                ->schema([
                    Select::make('estudiante_id')
                        ->label('Estudiante')
                        ->relationship('estudiante', 'apellidos')
                        // Usamos una función para mostrar Apellidos, Nombres
                        ->getOptionLabelFromRecordUsing(fn (Estudiante $record) => "{$record->apellidos}, {$record->nombres} ({$record->carnet})")
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(), // Importante para que otros campos reaccionen
                ]),

            Step::make('Ítem')
                ->description('Seleccione el ítem a prestar')
                ->schema([
                    Select::make('item_id')
                        ->label('Ítem')
                        // 1. Filtrar: Mostrar solo ítems "Disponibles"
                        ->relationship(
                            name: 'item',
                            titleAttribute: 'id', // Temporalmente
                            modifyQueryUsing: fn (Builder $query) =>
                                $query->where('estado_disponibilidad', 'Disponible')
                        )
                        // 2. Lógica para mostrar un nombre útil (Tipo + Detalle)
                        ->getOptionLabelFromRecordUsing(function (Item $record) {
                            // Cargar la relación específica (tablet o tesis)
                            $record->loadMissing($record->tipo === 'Tablet' ? 'tablet' : 'tesis');

                            if ($record->tipo === 'Tablet') {
                                return "TABLET: {$record->tablet->marca} {$record->tablet->modelo} (Cod: {$record->tablet->codigo})";
                            }
                            if ($record->tipo === 'Tesis') {
                                return "TESIS: {$record->tesis->titulo}";
                            }
                            return "Item #{$record->id}";
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(), // Reactivo para el siguiente paso
                ]),

            Step::make('Actividad')
                ->description('Detalles adicionales (si aplica)')
                ->schema([
                    // --- 4. CAMPO CONDICIONAL (REQUISITO NUEVO) ---
                    Select::make('actividad_tablet')
                        ->label('Actividad a realizar con la Tablet')
                        ->options([
                            'Lectura de libro digital' => 'Lectura de libro digital',
                            'Uso de BD para investigación' => 'Uso de BD para investigación',
                            'Trabajo universitario' => 'Trabajo universitario',
                            'Otro' => 'Otro',
                        ])
                        ->required()
                        ->live() // Reactivo para mostrar el campo "Otro"
                        // Visible solo si el item_id seleccionado es una Tablet
                        ->visible(function (Get $get) {
                            $item = Item::find($get('item_id'));
                            return $item && $item->tipo === 'Tablet';
                        }),

                    TextInput::make('actividad_tablet_otro')
                        ->label('Especifique la actividad')
                        ->required()
                        // Visible solo si el Select anterior es 'Otro'
                        ->visible(fn (Get $get) => $get('actividad_tablet') === 'Otro'),

                    // Un placeholder para Tesis
                    Placeholder::make('info_tesis')
                        ->content('Este préstamo es una Tesis, no se requiere actividad.')
                        ->visible(function (Get $get) {
                            $item = Item::find($get('item_id'));
                            return $item && $item->tipo === 'Tesis';
                        }),
                ]),
            ]),
        ];
    }

    // --- 5. LÓGICA DE CREACIÓN (ANTES DE GUARDAR) ---
    // (RF-13: Registrar fecha y hora)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Establecer el momento exacto del préstamo
        $data['momento_prestamo'] = Carbon::now();

        return $data;
    }

    // --- 6. LÓGICA DE CREACIÓN (DESPUÉS DE GUARDAR) ---
    // (RF-14: Actualizar estado del Item)
    protected function afterCreate(): void
    {
        // Obtener el registro que acabamos de crear
        $prestamo = $this->getRecord();

        // Actualizar el Item relacionado
        $item = $prestamo->item;
        $item->estado_disponibilidad = 'Prestado';
        $item->save();
    }
}