<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        return view('login', [], ['debug' => false]);
    }

    public function registro()
    {
        return view('registro', [], ['debug' => false]);
    }
}
