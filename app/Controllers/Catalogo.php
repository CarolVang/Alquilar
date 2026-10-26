<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\HerramientaModel;

class Catalogo extends BaseController
{
    public function index()
    {
        $herramientas = (new HerramientaModel())->getCatalogoOrdenado();

        $ids      = array_map(static fn ($h) => (int) $h['id_herramienta'], $herramientas);
        $ocupadas = (new AlquilerModel())->getOcupacionActual($ids);

        return view('catalogo', [
            'herramientas' => $herramientas,
            'ocupadas'     => $ocupadas,
        ], ['debug' => false]);
    }
}
