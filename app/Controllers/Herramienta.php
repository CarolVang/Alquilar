<?php

namespace App\Controllers;

class Herramienta extends BaseController
{
    public function detalle()
    {
        return view('detalle', [], ['debug' => false]);
    }
}
