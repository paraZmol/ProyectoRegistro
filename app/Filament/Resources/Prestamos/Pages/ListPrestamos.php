<?php

namespace App\Filament\Resources\Prestamos\Pages;

use App\Filament\Resources\Prestamos\PrestamoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrestamos extends ListRecords
{
    protected static string $resource = PrestamoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
