<?php

namespace App\Filament\Resources\Teses\Pages;

use App\Filament\Resources\Teses\TesisResource;
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
