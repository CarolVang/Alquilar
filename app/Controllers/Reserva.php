<?php

namespace App\Controllers;

class Reserva extends BaseController
{
    public function confirmacion()
    {
        return view('confirmacion', [], ['debug' => false]);
    }
}
