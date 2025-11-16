<?php

namespace App\Filament\Resources\Facultad\Pages;

use App\Filament\Resources\Facultad\FacultadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacultad extends ListRecords
{
    protected static string $resource = FacultadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
