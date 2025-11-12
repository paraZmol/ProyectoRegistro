<?php

namespace App\Filament\Resources\Teses\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

class TesesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('autor')
                    ->searchable()
                    ->sortable(),
                // revisando filament, parace que puede ser  text y badge una propiedad
                // https://filamentphp.com/docs/4.x/tables/columns/text#displaying-as-a-badge
                BadgeColumn::make('item.estado_disponibilidad')
                    ->label('Estado')
                    ->sortable()
                    ->colors([
                        'success' => 'Disponible',
                        'warning' => 'Prestado',
                        'danger' => 'Mantenimiento',
                    ]),
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