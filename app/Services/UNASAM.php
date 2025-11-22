<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UNASAM
{
    // guardar la base url de la api
    protected string $baseUrl;

    public function __construct()
    {
        // cargar la url desde config/services.php
        $this->baseUrl = config('services.unasam.base_url');
    }

    /*
     * esta funcion recibe un dni y consulta la api de unasam
     * si el dni existe devuelve los datos
     * si el dni no existe devuelve null
     * si hay error tambien devuelve null
     */
    public function obtenerDNI(string $dni): ?array
    {
        // validamos que el dni sea 8 numeros
        if (!preg_match('/^[0-9]{8}$/', $dni)) {
            return null;
        }

        try {

            // hacemos la peticion http a la api
            // timeout de 10s para que no se cuelgue el sistema si la api esta lenta
            $response = Http::timeout(10)->get($this->baseUrl . $dni);

            // si la respuesta vino bien (codigo 200)
            if ($response->successful()) {

                // convertimos el json en array php
                $data = $response->json();

                // si la api respondio vacio significa que el dni no existe
                if (empty($data)) {
                    return null;
                }

                // devolvemos la data del alumno
                return $data;
            }

            // si no fue 200 devolvemos null para no romper nada
            return null;

        } catch (\Exception $e) {

            // si algo explota (api caida, sin internet, etc)
            Log::error("api unasam error: ".$e->getMessage());

            return null;
        }
    }
}