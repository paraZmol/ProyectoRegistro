<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Escuela;
use App\Models\Facultad;
use App\Services\UNASAM;

class UNASAMHelpers
{
    protected UNASAM $api;

    public function __construct(UNASAM $api)
    {
        $this->api = $api;
    }

    // funcion para generar siglas agarrando la primera letra de cada palabra
    private function generarSigla(string $nombre): string
    {
        // separamos las palabras
        $palabras = explode(' ', $nombre);

        // palabras que no aportan inicial
        $excluir = ['de', 'del', 'la', 'las', 'los', 'e', 'y'];

        $sigla = '';

        foreach ($palabras as $p) {
            $p = strtolower($p);

            if (!in_array($p, $excluir)) {
                // agarramos la primera letra en mayus
                $sigla .= strtoupper($p[0]);
            }
        }

        return $sigla;
    }

    // este metodo recibe un dni
    public function obtenerCrearEstudiante(string $dni): ?Estudiante
    {
        // verificar si ya existe localmente
        $local = Estudiante::where('carnet', $dni)->first();
        if ($local) {
            return $local;
        }

        // si no existe, consultamos a la api
        $data = $this->api->obtenerDNI($dni);

        // si la api no devuelve nada, simplemente regresamos null
        if (!$data) {
            return null;
        }

        /*
            el json de la api nos da estos datos:

            alumno:
                apellido_paterno
                apellido_materno
                nombres
                dni

            escuela:
                nombre

            facultad:
                nombre
        */

        $apellidos = trim(
            $data['alumno']['apellido_paterno'] . ' ' .
            $data['alumno']['apellido_materno']
        );

        // nombres raw
        $nombreFacultadReal = $data['facultad']['nombre'];
        $nombreEscuela = $data['escuela']['nombre'];

        // para facultad
        $nombreFacultad = "Facultad de " . $nombreFacultadReal;

        // generamos siglas correctas
        $siglaFacultad = $this->generarSigla($nombreFacultad);
        $siglaEscuela = $this->generarSigla($nombreEscuela);

        // crear o buscar facultad
        $facultad = Facultad::firstOrCreate(
            ['facultad' => $nombreFacultadReal],
            [
                'sigla' => $siglaFacultad
            ]
        );

        // crear o buscar escuela
        $escuela = Escuela::firstOrCreate(
            ['escuela' => $nombreEscuela],
            [
                'sigla' => $siglaEscuela,
                'facultad_id' => $facultad->id
            ]
        );

        // crear estudiante en bd
        $nuevo = Estudiante::create([
            'carnet' => $dni,
            'apellidos' => $apellidos,
            'nombres' => $data['alumno']['nombres'],
            'escuela_id' => $escuela->id,
        ]);

        return $nuevo;
    }
}