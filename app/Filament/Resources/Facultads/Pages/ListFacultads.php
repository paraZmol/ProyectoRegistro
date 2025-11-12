<?php

namespace App\Filament\Resources\Facultads\Pages;

use App\Filament\Resources\Facultads\FacultadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacultads extends ListRecords
{
    protected static string $resource = FacultadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
