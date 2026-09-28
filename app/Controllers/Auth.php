<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('login', [], ['debug' => false]);
    }

    public function registro()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('registro', [], ['debug' => false]);
    }

    public function attemptRegistro()
    {
        $password  = (string) $this->request->getPost('password');
        $password2 = (string) $this->request->getPost('password2');

        if ($password !== $password2) {
            return redirect()->back()->withInput()->with('error', 'Las contraseñas no coinciden.');
        }

        $usuarioModel = new UsuarioModel();

        $data = [
            'nombre'   => $this->request->getPost('nombre'),
            'email'    => $this->request->getPost('email'),
            'password' => $password,
            'telefono' => $this->request->getPost('telefono'),
            'dni'      => $this->request->getPost('dni'),
            'rol'      => $this->request->getPost('rol') ?: 'cliente',
        ];

        if (! $usuarioModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $usuarioModel->errors()));
        }

        return redirect()->to('/login')->with('mensaje', 'Cuenta creada con éxito. Iniciá sesión.');
    }

    public function attemptLogin()
    {
        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $usuario = (new UsuarioModel())->where('email', $email)->first();

        if (! $usuario || ! password_verify($password, $usuario['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email o contraseña incorrectos.');
        }

        $this->session->set([
            'id_usuario' => $usuario['id_usuario'],
            'nombre'     => $usuario['nombre'],
            'rol'        => $usuario['rol'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/');
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to('/login');
    }
}
