<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    public function index()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $idUsuario = (int) $this->session->get('id_usuario');
        $usuario   = (new UsuarioModel())->find($idUsuario);

        return view('perfil', [
            'usuario'     => $usuario,
            'por_retirar' => (new AlquilerModel())->contarPorRetirar($idUsuario),
        ], ['debug' => false]);
    }
}
