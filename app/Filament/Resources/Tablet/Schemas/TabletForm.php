<?php

namespace App\Filament\Resources\Tablet\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class TabletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Datos de la Tablet')
                    ->schema([
                        TextInput::make('codigo')
                            ->label('Código de Barras')
                            ->required()
                            ->maxLength(15)// recierca cambiar, creo que son 9?
                            ->unique(ignoreRecord: true),

                        TextInput::make('marca')
                            ->required()
                            ->maxLength(80),

                        TextInput::make('modelo')
                            ->required()
                            ->maxLength(80),

                        TextInput::make('color')
                            ->required()
                            ->maxLength(45),

                        Textarea::make('estado_fisico')
                            ->label('Estado Físico (Observaciones)')
                            ->required()
                            ->maxLength(45)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
