<?php

namespace App\Filament\Resources\Tablets\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

class TabletsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('marca')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('modelo')
                    ->searchable(),

                /* el badge se usa para relacionar
                *el estado del item con la tablet,
                *ya que tablet no tiene estado de disponibilidad*/
                BadgeColumn::make('item.estado_disponibilidad')
                    ->label('Estado')
                    ->sortable()
                    ->colors([
                        'success' => 'Disponible',
                        'warning' => 'Prestado',
                        'danger' => 'Mantenimiento',
                    ]),

                TextColumn::make('estado_fisico')
                    ->label('Estado Físico')
                    ->toggleable(true)
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('item_id')
                    ->label('Filtrar por Estado')
                    ->relationship('item', 'estado_disponibilidad')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}