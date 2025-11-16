<?php

namespace App\Filament\Resources\Tesis\Pages;

use App\Filament\Resources\Tesis\TesisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTesis extends ListRecords
{
    protected static string $resource = TesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
