<?php

namespace App\Filament\Resources\Teses\Pages;

use App\Filament\Resources\Teses\TesisResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTesis extends EditRecord
{
    protected static string $resource = TesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
