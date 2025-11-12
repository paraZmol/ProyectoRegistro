<?php

namespace App\Filament\Resources\Prestamos\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PrestamosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // --- ordenar por defecto de lo mas reciente al mas primero ---
            ->defaultSort('created_at', 'desc')
            ->columns([
                // --- columna de estado (activo/devuelto) ---
                BadgeColumn::make('momento_entrega')
                    ->label('Estado')
                    // Formatear el valor antes de mostrarlo
                    ->formatStateUsing(fn ($state): string =>
                        $state ? 'Devuelto' : 'Prestado'
                    )
                    ->colors([
                        'success' => 'Devuelto',
                        'warning' => 'Prestado',
                    ])
                    ->sortable(),

                // --- datos del estudiante ---
                TextColumn::make('estudiante.apellidos')
                    ->label('Apellidos del Estudiante')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('estudiante.nombres')
                    ->label('Nombres del Estudiante')
                    ->searchable(),
                TextColumn::make('estudiante.carnet')
                    ->label('Carnet')
                    ->searchable(),

                // --- datos del item - tipo ---
                TextColumn::make('item.tipo')
                    ->label('Tipo de Ítem')
                    ->sortable(),

                // --- fechas ---
                TextColumn::make('momento_prestamo')
                    ->label('Fecha de Préstamo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('momento_entrega')
                    ->label('Fecha de Devolución')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(true), // Oculto por defecto
            ])
            ->filters([
                // --- filtro para ver solo prestamos activos ---
                Filter::make('prestamos_activos')
                    ->label('Mostrar solo préstamos activos')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereNull('momento_entrega')
                    )
                    ->default(),

                // --- filtro por estudiante ---
                SelectFilter::make('estudiante_id')
                    ->label('Filtrar por Estudiante')
                    ->relationship('estudiante', 'apellidos')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([ // Acciones por fila
                // Dejaremos esto vacío por ahora,
                // la "Devolución" será una acción especial
            ])
            ->toolbarActions([ // Acciones de la barra
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
