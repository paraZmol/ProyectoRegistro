<?php

namespace App\Filament\Resources\Facultads\Pages;

use App\Filament\Resources\Facultads\FacultadResource;
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
