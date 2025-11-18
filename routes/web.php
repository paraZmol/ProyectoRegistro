<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrestamoPdfController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/prestamos/{prestamo}/boleta', [PrestamoPdfController::class, 'imprimir'])
    ->name('prestamos.boleta');
