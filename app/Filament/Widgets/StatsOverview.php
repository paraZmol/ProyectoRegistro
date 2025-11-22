<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\Prestamo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends BaseWidget
{
    // protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // calculos de prestamos
        // tablets
        $prestamosTablets = Prestamo::whereNull('momento_entrega')
            ->whereHas('item', fn (Builder $query) => $query->where('tipo', 'Tablet'))
            ->count();

        // tesis
        $prestamosTesis = Prestamo::whereNull('momento_entrega')
            ->whereHas('item', fn (Builder $query) => $query->where('tipo', 'Tesis'))
            ->count();


        // calculo de inventario
        // tablet
        $totalTablets = Item::where('tipo', 'Tablet')->count();
        $tabletsDisp = Item::where('tipo', 'Tablet')->where('estado_disponibilidad', 'Disponible')->count();
        // tesis
        $totalTesis = Item::where('tipo', 'Tesis')->count();
        $tesisDisp = Item::where('tipo', 'Tesis')->where('estado_disponibilidad', 'Disponible')->count();


        return [
            // tarjetas de pendientes
            // tablet
            Stat::make('Préstamos Activos (Tablets)', $prestamosTablets)
                ->description('Tablets sin devolver')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'), // amarillo para pendiente

            // tesis
            Stat::make('Préstamos Activos (Tesis)', $prestamosTesis)
                ->description('Tesis sin devolver')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            // tarjetas de inventario
            // tablets
            Stat::make('Inventario Tablets', "{$tabletsDisp} de {$totalTablets}")
                ->description('Disponibles / Total')
                ->descriptionIcon('heroicon-m-device-tablet')
                ->color($tabletsDisp > 0 ? 'success' : 'danger'),

            // tesis
            Stat::make('Inventario Tesis', "{$tesisDisp} de {$totalTesis}")
                ->description('Disponibles / Total')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),
        ];
    }

    // modificar espacios
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2; // 2 tarjetas por fila
    }
}
