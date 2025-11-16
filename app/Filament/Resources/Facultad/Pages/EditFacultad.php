<?php

namespace App\Filament\Resources\Facultad\Pages;

use App\Filament\Resources\Facultad\FacultadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFacultad extends EditRecord
{
    protected static string $resource = FacultadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
