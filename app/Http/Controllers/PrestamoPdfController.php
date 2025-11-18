<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrestamoPdfController extends Controller
{
    public function imprimir(Prestamo $prestamo)
    {
        // datos relacionados ale prestamo
        $prestamo->load(['estudiante.escuela', 'item.tablet', 'item.tesis']);

        //crear el pdf
        $pdf = Pdf::loadView('pdf.boleta', ['prestamo'=> $prestamo]);

        // tamaño de papael en A4
        $pdf->setPaper('a4', 'portrait');

        // para abrir una nueva pestaña con pdf
        return $pdf->stream("boleta-prestamo-{$prestamo->id}.pdf");
    }
}
