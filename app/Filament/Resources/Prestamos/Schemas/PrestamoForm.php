<?php

namespace App\Filament\Resources\Prestamos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PrestamoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('momento_prestamo')
                    ->required(),
                DateTimePicker::make('momento_entrega'),
                Select::make('estudiante_id')
                    ->relationship('estudiante', 'id')
                    ->required(),
                Select::make('item_id')
                    ->relationship('item', 'id')
                    ->required(),
            ]);
    }
}
