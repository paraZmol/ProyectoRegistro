<?php

namespace App\Filament\Resources\Escuela\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
class EscuelaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('escuela')
                    ->label('Nombre de la Escuela')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sigla')
                    ->label('Sigla')
                    ->required()
                    ->maxLength(45),

                Select::make('facultad_id')
                    ->label('Facultad a la que pertenece')
                    ->relationship(name: 'facultad', titleAttribute: 'facultad') // (Relación, Columna a mostrar)
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
