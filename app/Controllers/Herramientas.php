<?php

namespace App\Controllers;

class Herramientas extends BaseController
{
    public function publicar()
    {
        return view('publicar');
    }

    public function mis()
    {
        return view('mis_herramientas');
    }
}
