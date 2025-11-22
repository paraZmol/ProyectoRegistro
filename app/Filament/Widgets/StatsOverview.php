<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\Prestamo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    // protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $stats = [];

        // logica de tablets
        // visible para tablet o admin
        if ($user->hasRole(['super_admin', 'Administrador', 'Encargado de Tablet'])) {

            // calculos para contar
            $totalTablets = Item::where('tipo', 'Tablet')->count();
            $tabletsDisp = Item::where('tipo', 'Tablet')->where('estado_disponibilidad', 'Disponible')->count();

            $prestamosTablets = Prestamo::whereNull('momento_entrega')
                ->whereHas('item', fn (Builder $query) => $query->where('tipo', 'Tablet'))
                ->count();

            // agregar tarjetas al array
            $stats[] = Stat::make('Préstamos Activos (Tablets)', $prestamosTablets)
                ->description('Tablets sin devolver')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning');

            $stats[] = Stat::make('Inventario Tablets', "{$tabletsDisp} de {$totalTablets}")
                ->description('Disponibles / Total')
                ->descriptionIcon('heroicon-m-device-tablet')
                ->color($tabletsDisp > 0 ? 'success' : 'danger');
        }

        // logica para tesis
        // visible para tesis o admin
        if ($user->hasRole(['super_admin', 'Administrador', 'Encargado de Tesis'])) {

            // calculos
            $totalTesis = Item::where('tipo', 'Tesis')->count();
            $tesisDisp = Item::where('tipo', 'Tesis')->where('estado_disponibilidad', 'Disponible')->count();

            $prestamosTesis = Prestamo::whereNull('momento_entrega')
                ->whereHas('item', fn (Builder $query) => $query->where('tipo', 'Tesis'))
                ->count();

            // agregar tarjetas al array
            $stats[] = Stat::make('Préstamos Activos (Tesis)', $prestamosTesis)
                ->description('Tesis sin devolver')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning');

            $stats[] = Stat::make('Inventario Tesis', "{$tesisDisp} de {$totalTesis}")
                ->description('Disponibles / Total')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary');
        }

        return $stats;
    }

    // modificar espacios
    protected function getColumns(): int
    {
        return 2; // 2 tarjetas por fila
    }
}