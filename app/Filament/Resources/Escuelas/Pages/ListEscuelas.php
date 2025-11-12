<?php

namespace App\Filament\Resources\Escuelas\Pages;

use App\Filament\Resources\Escuelas\EscuelaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEscuelas extends ListRecords
{
    protected static string $resource = EscuelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
