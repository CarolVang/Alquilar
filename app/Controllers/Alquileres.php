<?php

namespace App\Controllers;

class Alquileres extends BaseController
{
    public function misReservas()
    {
        return view('mis_reservas', [], ['debug' => false]);
    }

    public function misAlquileres()
    {
        return view('mis_alquileres', [], ['debug' => false]);
    }
}
