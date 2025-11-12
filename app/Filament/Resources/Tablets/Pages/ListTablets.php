<?php

namespace App\Filament\Resources\Tablets\Pages;

use App\Filament\Resources\Tablets\TabletResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTablets extends ListRecords
{
    protected static string $resource = TabletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
