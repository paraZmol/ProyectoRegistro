<?php

namespace App\Filament\Resources\Tesis\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class TesisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Datos de la Tesis')
                    ->schema([
                        TextInput::make('titulo')
                            ->required()
                            ->maxLength(300),

                        TextInput::make('autor')
                            ->required()
                            ->maxLength(255), 
                    ]),
            ]);
    }
}