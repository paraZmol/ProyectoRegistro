<?php

namespace App\Filament\Resources\Tablets\Pages;

use App\Filament\Resources\Tablets\TabletResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTablet extends EditRecord
{
    protected static string $resource = TabletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
