<?php

namespace App\Exports;

use App\Models\Prestamo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PrestamosExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $prestamos = Prestamo::query()
            ->with([
                'estudiante.escuela.facultad',
                'item.tablet',
                'item.tesis'
            ])
            ->orderBy('momento_prestamo', 'asc')
            ->get();

        return view('exports.prestamosExcel', [
            'prestamos' => $prestamos
        ]);
    }
}
