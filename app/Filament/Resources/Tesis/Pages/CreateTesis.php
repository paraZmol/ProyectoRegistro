<?php

namespace App\Filament\Resources\Tesis\Pages;

use App\Filament\Resources\Tesis\TesisResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;

class CreateTesis extends CreateRecord
{
    protected static string $resource = TesisResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $item = Item::create([
            'tipo' => 'Tesis',
            'estado_disponibilidad' => 'Disponible',
        ]);

        $data['item_id'] = $item->id;

        return $data;
    }
}
