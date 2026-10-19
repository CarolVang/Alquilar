<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\HerramientaModel;
use App\Models\UsuarioModel;

class Herramientas extends BaseController
{
    public function publicar()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('publicar');
    }

    public function crear()
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok'    => false,
                'error' => 'Tenés que iniciar sesión para publicar una herramienta.',
            ]);
        }

        $herramientaModel = new HerramientaModel();

        $data = [
            'id_usuario'  => $this->session->get('id_usuario'),
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio'      => $this->request->getPost('precio'),
            'estado'      => 'disponible',
        ];

        if (! $herramientaModel->insert($data)) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => implode(' ', $herramientaModel->errors()),
            ]);
        }

        return $this->response->setJSON(['ok' => true]);
    }

    public function mis()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $herramientas = (new HerramientaModel())
            ->where('id_usuario', $this->session->get('id_usuario'))
            ->findAll();

        return view('mis_herramientas', ['herramientas' => $herramientas]);
    }

    public function detalle($id)
    {
        $herramienta = (new HerramientaModel())->find($id);

        if (! $herramienta) {
            return redirect()->to('/catalogo')->with('error', 'Esa herramienta no existe o ya no está disponible.');
        }

        $propietario = (new UsuarioModel())->find($herramienta['id_usuario']);
        $periodosOcupados = (new AlquilerModel())->getPeriodosOcupados((int) $id);

        return view('detalle', [
            'herramienta'       => $herramienta,
            'propietario'       => $propietario,
            'periodos_ocupados' => $periodosOcupados,
        ], ['debug' => false]);
    }
}
