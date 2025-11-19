<?php

namespace App\Filament\Resources\Prestamos\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\TextSize;
use App\Models\Prestamo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PrestamosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            //ordenar por defecto de lo mas reciente al mas primero ---
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('estado') // columna virtual
                    ->label('Estado')
                    ->getStateUsing(function ($record): string {
                        if (is_null($record->momento_entrega)) {
                            return 'Prestado';
                        }
                        return 'Devuelto';
                    })
                    ->badge()
                    ->colors([
                        'success' => 'Devuelto',
                        'warning' => 'Prestado',
                    ])
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('momento_entrega', $direction)),

                TextColumn::make('estudiante.apellidos')
                    ->label('Estudiante')
                    ->formatStateUsing(fn ($record): string =>
                        $record->estudiante->apellidos . ', ' . $record->estudiante->nombres)
                    ->searchable(['apellidos', 'nombres'])
                    ->sortable(),

                TextColumn::make('estudiante.carnet')
                    ->label('Carnet')
                    ->searchable(),

                TextColumn::make('item.tipo')
                    ->label('Tipo de Item')
                    ->sortable(),

                TextColumn::make('momento_prestamo')
                    ->label('Fecha de Préstamo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('momento_entrega')
                    ->label('Fecha de Devolución')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // filtro para ver prestamos activos
                Filter::make('prestamos_activos')
                    ->label('Mostrar solo préstamos activos')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereNull('momento_entrega')
                    )
                    ->default(),

                //filtro por estudiante
                SelectFilter::make('estudiante_id')
                    ->label('Filtrar por Estudiante')
                    ->relationship('estudiante', 'apellidos')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->apellidos . ', ' . $record->nombres)
                    ->searchable()
                    ->preload(),
            ])
            ->recordUrl(null) //desactiva el clik por fila

            ->recordActions([ // bton de acciones

                ViewAction::make('ver')
                    ->label('Ver Detalles')
                    ->modalHeading('Detalles del Préstamo')
                    ->modalSubmitAction(false) // hide al botomn guardar
                    ->modalCancelActionLabel('Cerrar') // camniar cancelar por cerrar

                    ->infolist([
                            Section::make('Información del Estudiante')
                                ->columns(2)
                                ->schema([
                                    TextEntry::make('estudiante.nombres')
                                        ->label('Nombres'),
                                    TextEntry::make('estudiante.apellidos')
                                        ->label('Apellidos'),
                                    TextEntry::make('estudiante.carnet')
                                        ->label('Carnet'),
                                    TextEntry::make('estudiante.escuela.escuela')
                                        ->label('Escuela'),
                                ]),
                            Section::make('Información del Préstamo')
                                ->columns(2)
                                ->schema([
                                    TextEntry::make('item.tipo')
                                        ->label('Tipo de Item'),
                                    // caso tesis
                                    TextEntry::make('item.tesis.titulo')
                                        ->label('Título')
                                        ->visible(fn ($record) => trim($record->item->tipo) === 'Tesis'),
                                    TextEntry::make('item.tesis.autor')
                                        ->label('Autor')
                                        ->visible(fn ($record) => trim($record->item->tipo) === 'Tesis'),

                                    // caso tablet
                                    TextEntry::make('item_detalle_tablet') // campo virtual
                                        ->label('Detalle del Ítem')
                                        ->getStateUsing(function ($record) {
                                            if (!$record->item->tablet) { return null; }
                                            return "{$record->item->tablet->marca} {$record->item->tablet->modelo}";
                                        })
                                        ->visible(fn ($record) => trim($record->item->tipo) === 'Tablet'),
                                    TextEntry::make('item.tablet.codigo')
                                        ->label('Código')
                                        ->visible(fn ($record) => trim($record->item->tipo) === 'Tablet'),
                                    // devolucion y prestamo
                                    TextEntry::make('momento_prestamo')
                                        ->label('Fecha de Préstamo')
                                        ->dateTime('d/m/Y H:i'),
                                    TextEntry::make('momento_entrega')
                                        ->label('Fecha de Devolución')
                                        ->getStateUsing(function ($record) {
                                            $state = $record->momento_entrega;
                                            if ($state === null) {
                                                return 'Aún no devuelto';
                                            }
                                            return \Carbon\Carbon::parse($state)->format('d/m/Y H:i');
                                        })
                                        ->size(TextSize::Large)// tamaño de letra
                                        ->color(fn ($state): string => $state === 'Aún no devuelto' ? 'danger' : 'success'),
                                    ]),
                                    // botones de guardar e imprimir
                                    Section::make('Acciones')
                                        ->schema([
                                            Actions::make([
                                                // registrar devolucion
                                                Action::make('devolver')
                                                    ->label('Registrar Devolución')
                                                    ->icon('heroicon-o-check-circle')
                                                    ->color('success')
                                                    ->requiresConfirmation()
                                                    ->modalHeading('Confirmar Devolución')
                                                    ->action(function ($record) {
                                                        $prestamo = Prestamo::find($record->id);
                                                        $prestamo->momento_entrega = Carbon::now();
                                                        $prestamo->save();

                                                        $item = $prestamo->item;
                                                        $item->estado_disponibilidad = 'Disponible';
                                                        $item->save();

                                                        Notification::make()
                                                            ->title('Devolución Registrada')
                                                            ->success()
                                                            ->send();
                                                    })
                                                    ->after(function ($record, $livewire) {
                                                        // Cerrar el modal del ViewAction
                                                        $livewire->dispatch('close-modal', id: 'view-action');
                                                    })
                                                    ->visible(fn ($record) => is_null($record->momento_entrega))
                                                    /*->close()*/,

                                                // imprimir boleta
                                                Action::make('imprimir')
                                                    ->label('Imprimir Boleta')
                                                    ->icon('heroicon-o-printer')
                                                    ->color('gray')
                                                    // enlace al balde de imprimir pdf
                                                    ->url(fn (Prestamo $record) => route('prestamos.boleta', $record))
                                                    ->openUrlInNewTab(),
                                            ])->fullWidth(), // poara que los botones se vean bien
                                        ])
                                    // fin botones
                 ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}