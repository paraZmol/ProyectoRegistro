<?php

namespace App\Filament\Resources\Escuela\Pages;

use App\Filament\Resources\Escuela\EscuelaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEscuela extends ListRecords
{
    protected static string $resource = EscuelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
