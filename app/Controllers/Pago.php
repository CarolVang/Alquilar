<?php

namespace App\Controllers;

class Pago extends BaseController
{
    public function index()
    {
        return view('pago', [], ['debug' => false]);
    }
}
