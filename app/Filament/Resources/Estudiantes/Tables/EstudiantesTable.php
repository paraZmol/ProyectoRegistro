<?php

namespace App\Filament\Resources\Estudiantes\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

class EstudiantesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('carnet')
                    ->label('Carnet/DNI')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('apellidos')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nombres')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('escuela.escuela')
                    ->label('Escuela')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('escuela.facultad.facultad')
                    ->label('Facultad')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(true),
            ])
            ->filters([
                SelectFilter::make('escuela_id')
                    ->label('Filtrar por Escuela')
                    ->relationship('escuela', 'escuela') // (relacion, columna_a_mostrar)
                    ->searchable()
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
