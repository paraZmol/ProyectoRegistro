<?php

namespace App\Filament\Resources\Prestamos\Pages;

use App\Filament\Resources\Prestamos\PrestamoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrestamo extends EditRecord
{
    protected static string $resource = PrestamoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
