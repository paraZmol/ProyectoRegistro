<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Escuela;
use App\Models\Facultad;
use Illuminate\Support\Facades\Log;

class EstudianteFinder
{
    protected $unasam;

    public function __construct(UNASAM $unasam)
    {
        $this->unasam = $unasam;
    }

    /**
     * busca un estudiante por DNI/carnet.
     * si no existe, consulta API UNASAM y lo crea junto con escuela/facultad.
     */
    public function buscarOCrearPorDni(string $dni): ?Estudiante
    {
        // buscar estudiante localmente
        $est = Estudiante::where('carnet', $dni)->first();
        if ($est) {
            return $est;
        }

        // consultar API UNASAM
        $data = $this->unasam->obtenerDNI($dni);
        if (!$data) {
            return null;
        }

        // validar datos obligatorios
        if (
            empty($data['alumno']['apellido_paterno']) ||
            empty($data['alumno']['apellido_materno']) ||
            empty($data['alumno']['nombres']) ||
            empty($data['escuela']['nombre']) ||
            empty($data['facultad']['nombre'])
        ) {
            Log::warning("API UNASAM devolvió datos incompletos para DNI {$dni}");
            return null;
        }

        // datos del alumno
        $apellidos = trim(
            $data['alumno']['apellido_paterno'] . ' ' .
            $data['alumno']['apellido_materno']
        );

        $nombres = $data['alumno']['nombres'];
        $nombreEscuela = trim($data['escuela']['nombre']);
        $nombreFacultadOriginal = trim($data['facultad']['nombre']);

        // Facultad de X
        $nombreFacultad = "Facultad de " . $nombreFacultadOriginal;

        // crear o buscar facultad
        $facultad = Facultad::firstOrCreate(
            ['facultad' => $nombreFacultad],
            ['sigla' => $this->generarSigla($nombreFacultad)]
        );

        // crear o buscar escuela
        $escuela = Escuela::firstOrCreate(
            ['escuela' => $nombreEscuela],
            [
                'sigla' => $this->generarSigla($nombreEscuela),
                'facultad_id' => $facultad->id
            ]
        );

        // crear estudiante
        return Estudiante::create([
            'carnet'      => $dni,
            'apellidos'   => $apellidos,
            'nombres'     => $nombres,
            'escuela_id'  => $escuela->id,
        ]);
    }

    // generar siglas
    private function generarSigla(string $nombre): string
    {
        $palabras = explode(' ', strtolower($nombre));

        $excluir = ['de', 'del', 'la', 'las', 'los', 'e', 'y'];

        $sigla = '';
        foreach ($palabras as $p) {
            if (!in_array($p, $excluir) && strlen($p) > 0) {
                $sigla .= strtoupper($p[0]);
            }
        }

        return $sigla;
    }
}