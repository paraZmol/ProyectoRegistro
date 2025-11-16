<?php

namespace App\Filament\Resources\Facultad\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class FacultadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('facultad')
                    ->label('Nombre de la Facultad')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sigla')
                    ->label('Sigla (Ej. FISI)')
                    ->required()
                    ->maxLength(45),
            ]);
    }
}
