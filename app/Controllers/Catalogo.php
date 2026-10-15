<?php

namespace App\Controllers;

use App\Models\HerramientaModel;

class Catalogo extends BaseController
{
    public function index()
    {
        $herramientas = (new HerramientaModel())->getCatalogoOrdenado();

        return view('catalogo', ['herramientas' => $herramientas], ['debug' => false]);
    }
}
