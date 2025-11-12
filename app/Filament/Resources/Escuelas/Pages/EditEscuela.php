<?php

namespace App\Filament\Resources\Escuelas\Pages;

use App\Filament\Resources\Escuelas\EscuelaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEscuela extends EditRecord
{
    protected static string $resource = EscuelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
