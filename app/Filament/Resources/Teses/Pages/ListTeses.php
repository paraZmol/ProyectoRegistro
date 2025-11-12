<?php

namespace App\Filament\Resources\Teses\Pages;

use App\Filament\Resources\Teses\TesisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeses extends ListRecords
{
    protected static string $resource = TesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
