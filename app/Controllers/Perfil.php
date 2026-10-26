<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    public function index()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $usuario = (new UsuarioModel())->find($this->session->get('id_usuario'));

        return view('perfil', ['usuario' => $usuario], ['debug' => false]);
    }
}
