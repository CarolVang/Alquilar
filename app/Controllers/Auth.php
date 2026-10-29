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

        $rol = $this->request->getPost('rol') === 'comerciante' ? 'comerciante' : 'particular';

        $razonSocial = null;
        $cuit        = null;

        if ($rol === 'comerciante') {
            $razonSocial = trim((string) $this->request->getPost('razon_social'));
            $cuit        = trim((string) $this->request->getPost('cuit'));

            if ($razonSocial === '') {
                return redirect()->back()->withInput()->with('error', 'Ingresá la razón social de la empresa.');
            }

            if (! $this->cuitValido($cuit)) {
                return redirect()->back()->withInput()->with('error', 'El CUIT ingresado no es válido. Revisá los 11 números.');
            }
        }

        $usuarioModel = new UsuarioModel();

        $data = [
            'nombre'       => $this->request->getPost('nombre'),
            'email'        => $this->request->getPost('email'),
            'password'     => $password,
            'telefono'     => $this->request->getPost('telefono'),
            'dni'          => $this->request->getPost('dni'),
            'rol'          => $rol,
            'razon_social' => $razonSocial,
            'cuit'         => $cuit,
        ];

        if (! $usuarioModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $usuarioModel->errors()));
        }

        return redirect()->to('/login')->with('mensaje', 'Cuenta creada con éxito. Iniciá sesión.');
    }

    /**
     * Valida el digito verificador del CUIT con el algoritmo oficial de AFIP.
     * Esto NO confirma que el CUIT le pertenezca de verdad a quien se
     * registra (eso requeriria consultar contra AFIP, fuera de alcance acá):
     * solo garantiza que los 11 numeros son matematicamente consistentes
     * entre si, como cualquier CUIT real.
     */
    private function cuitValido(string $cuit): bool
    {
        $cuit = preg_replace('/\D/', '', $cuit);

        if (strlen($cuit) !== 11) {
            return false;
        }

        $digitos  = array_map('intval', str_split($cuit));
        $pesos    = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $suma     = 0;

        for ($i = 0; $i < 10; $i++) {
            $suma += $digitos[$i] * $pesos[$i];
        }

        $verificador = 11 - ($suma % 11);

        if ($verificador === 11) {
            $verificador = 0;
        }

        if ($verificador === 10) {
            return false;
        }

        return $verificador === $digitos[10];
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
