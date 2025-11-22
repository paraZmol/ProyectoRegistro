<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Escuela;
use App\Models\Facultad;
use Illuminate\Support\Facades\Log;

/**
 * este servicio se encarga de toda la magia de buscar un estudiante
 * primero intenta buscar en la bd
 * si no existe, llama a la api unasam
 * si la api lo devuelve, crea escuela, facultad y estudiante
 * si algo falla devuelve null
 */
class EstudianteFinder
{
    protected $unasam;

    public function __construct(UNASAM $unasam)
    {
        $this->unasam = $unasam;
    }

    /**
     * busca un estudiante por dni (o carnet, que es lo mismo)
     * si no existe lo crea usando la api
     */
    public function buscarOCrearPorDni(string $dni): ?Estudiante
    {
        // primero buscamos en bd
        $est = Estudiante::where('carnet', $dni)->first();

        if ($st = $est) {
            // si existe ya no hacemos nada
            return $st;
        }

        // si no existe, usamos la api unasam
        $data = $this->unasam->obtenerDNI($dni);

        if (!$data) {
            // si la api murio o no encontro nada
            return null;
        }

        // datos basicos del estudiante
        $apellidos = $data['apellidos'] ?? null;
        $nombres = $data['nombres'] ?? null;
        $nombreEscuela = $data['escuela'] ?? null;
        $nombreFacultad = $data['facultad'] ?? null;

        if (!$apellidos || !$nombres || !$nombreEscuela || !$nombreFacultad) {
            // si falta algun dato importante mejor no crear nada
            Log::warning("datos incompletos de la api para dni {$dni}");
            return null;
        }

        // ===========================
        // FACULTAD
        // ===========================

        // buscar facultad por su nombre completo
        $facultad = Facultad::where('nombre', $nombreFacultad)->first();

        if (!$facultad) {
            // si no existe la creamos
            $sigla = $this->generarSigla($nombreFacultad); // primera letra de cada palabra
            $facultad = Facultad::create([
                'nombre' => $nombreFacultad,
                'sigla' => $sigla,
            ]);
        }

        // ===========================
        // ESCUELA
        // ===========================

        // buscar escuela por nombre
        $escuela = Escuela::where('nombre', $nombreEscuela)->first();

        if (!$escuela) {
            // si no existe la creamos
            $sigla = $this->generarSigla($nombreEscuela); // primera letra de cada palabra
            $escuela = Escuela::create([
                'nombre' => $nombreEscuela,
                'sigla' => $sigla,
                'facultad_id' => $facultad->id,
            ]);
        }

        // ===========================
        // ESTUDIANTE
        // ===========================

        $estudiante = Estudiante::create([
            'carnet' => $dni,      // el carnet es igual al dni
            'apellidos' => $apellidos,
            'nombres' => $nombres,
            'escuela_id' => $escuela->id,
        ]);

        return $estudiante;
    }

    /**
     * genera la sigla tomando la primera letra de cada palabra
     * ejemplo: "Facultad de Ciencias" -> "FC"
     */
    private function generarSigla(string $nombre): string
    {
        // dividir palabras
        $palabras = explode(' ', trim($nombre));

        // tomar la primera letra de cada palabra y poner en mayuscula
        $sigla = '';
        foreach ($palabras as $p) {
            if (strlen($p) > 0) {
                $sigla .= strtoupper($p[0]);
            }
        }
        return $sigla;
    }
}