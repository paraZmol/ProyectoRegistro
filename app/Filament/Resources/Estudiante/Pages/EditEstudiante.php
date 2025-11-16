<?php

namespace App\Filament\Resources\Estudiante\Pages;

use App\Filament\Resources\Estudiante\EstudianteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEstudiante extends EditRecord
{
    protected static string $resource = EstudianteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
