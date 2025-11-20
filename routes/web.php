<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrestamoPdfController;

Route::get('/', function () {
    return view('welcome');
});
// prestamo individual
Route::get('/prestamos/{prestamo}/boleta', [PrestamoPdfController::class, 'imprimir'])
    ->name('prestamos.boleta');
// prestamo en lista
Route::get('/prestamos/imprimir', [PrestamoPdfController::class, 'imprimirListado'])
    ->name('prestamos.imprimir');