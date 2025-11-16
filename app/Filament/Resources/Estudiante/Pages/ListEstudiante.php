<?php

namespace App\Filament\Resources\Estudiante\Pages;

use App\Filament\Resources\Estudiante\EstudianteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstudiante extends ListRecords
{
    protected static string $resource = EstudianteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
