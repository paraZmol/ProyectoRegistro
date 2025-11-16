<?php

namespace App\Filament\Resources\Estudiante\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class EstudianteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Información Personal')
                    ->schema([
                        TextInput::make('apellidos')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('nombres')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Información Académica')
                    ->schema([
                        TextInput::make('carnet')
                            ->label('Carnet (o DNI)')
                            ->required()
                            ->maxLength(8)
                            ->unique(ignoreRecord: true),

                        Select::make('escuela_id')
                            ->label('Escuela Profesional')
                            ->relationship(name: 'escuela', titleAttribute: 'escuela') // (Relación, Columna)
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
