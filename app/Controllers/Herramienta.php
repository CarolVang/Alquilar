<?php

namespace App\Controllers;

class Herramienta extends BaseController
{
    public function testDisponibles()
    {
        $model = new \App\Models\HerramientaModel();
        $resultado = $model->getCatalogoOrdenado();

        dd($resultado);
    }
}