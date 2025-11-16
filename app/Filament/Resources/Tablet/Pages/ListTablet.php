<?php

namespace App\Filament\Resources\Tablet\Pages;

use App\Filament\Resources\Tablet\TabletResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTablet extends ListRecords
{
    protected static string $resource = TabletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
