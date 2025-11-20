<?php

namespace App\Filament\Resources\Prestamos\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Prestamo;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use App\Filament\Resources\Prestamos\PrestamoResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ListPrestamos extends ListRecords
{
    protected static string $resource = PrestamoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
            // crear el boton de imprimr
            Actions\Action::make('imprimir')
                ->label('Imprimir')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn ($livewire) => route('prestamos.imprimir', [
                    'filtros' => $livewire->tableFilters
                ]))
                ->openUrlInNewTab(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        try {
            // /todoo en una saola llamada
            $query->with(['estudiante', 'item.tablet', 'item.tesis']);

        } catch (\Exception $e) {
            Log::info('¡¡¡ERROR EN EAGER LOADING!!! ' . $e->getMessage());
        }
        return $query;
    }
}
