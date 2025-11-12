<?php

namespace App\Filament\Resources\Escuelas\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;


class EscuelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('escuela')
                    ->label('Nombre de la Escuela')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('facultad.facultad')
                    ->label('Facultad')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sigla')
                    ->label('Sigla')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(true),
            ])
            ->filters([
                //
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