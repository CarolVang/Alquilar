<?php

namespace App\Controllers;

class Catalogo extends BaseController
{
    public function index()
    {
        return view('catalogo', [], ['debug' => false]);
    }
}
