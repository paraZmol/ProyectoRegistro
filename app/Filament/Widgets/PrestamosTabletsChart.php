<?php

namespace App\Filament\Widgets;

use App\Models\Prestamo;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class PrestamosTabletsChart extends ChartWidget
{
    protected ?string $heading = 'Préstamos de Tablets (Por Mes)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full'; // ocupar toda la fila
    protected bool $isCollapsible = true; // ocultar mostrar

    protected function getData(): array
    {
        // obtener datos de tablets de este año
        $data = Prestamo::query()
            ->whereHas('item', fn (Builder $query) => $query->where('tipo', 'Tablet'))
            ->whereYear('momento_prestamo', date('Y')) // año actual
            ->selectRaw('MONTH(momento_prestamo) as month, count(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // 0 para los meses vacios
        $counts = [];
        for ($i = 1; $i <= 12; $i++) {
            $counts[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tablets Prestadas',
                    'data' => $counts,
                    'borderColor' => '#36A2EB', // azul
                    'backgroundColor' => '#36A2EB20', // azul transparente
                    'fill' => true,
                ],
            ],
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // valores enteros
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1, // saltos de 1 en 1
                        'precision' => 0, // sin decimales
                    ],
                ],
            ],
        ];
    }
}
