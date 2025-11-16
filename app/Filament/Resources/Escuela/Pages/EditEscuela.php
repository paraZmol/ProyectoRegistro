<?php

namespace App\Filament\Resources\Escuela\Pages;

use App\Filament\Resources\Escuela\EscuelaResource;
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
