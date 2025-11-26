<?php

namespace App\Exports;

use App\Models\Prestamo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PrestamosExport implements FromView, WithEvents
{
    protected $prestamos;

    public function __construct()
    {
        $this->prestamos = Prestamo::with([
            'estudiante.escuela.facultad',
            'item.tablet',
            'item.tesis'
        ])
        ->orderBy('momento_prestamo', 'desc')
        ->get();
    }

    public function view(): View
    {
        return view('exports.prestamosExcel', [
            'prestamos' => $this->prestamos
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $totalRows = count($this->prestamos) + 1;

                // encabezado
                $header = "A1:H1";
                $sheet->getStyle($header)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3B82F6']
                    ]
                ]);

                // auto filter por encabezado
                $sheet->setAutoFilter($header);

                // borde de tabla
                $fullRange = "A1:H{$totalRows}";
                $sheet->getStyle($fullRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // filas intercalado - tipo zebra
                for ($i = 2; $i <= $totalRows; $i++) {
                    if ($i % 2 === 0) {
                        $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F3F4F6']
                            ]
                        ]);
                    }
                }

                // encabezado estatico
                $sheet->freezePane('A2');

                // autoajuste de columnas
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
