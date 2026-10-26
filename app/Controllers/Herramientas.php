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

        // La foto es opcional, pero si se manda una, se valida de verdad server-side
        // (tipo real del archivo vía finfo, no la extensión del nombre, y tamaño maximo)
        // antes de tocar el disco. Nunca se confia en el nombre original del archivo.
        //
        // Importante: estas reglas de archivo NO van con permit_empty. Cada una
        // (max_size/is_image/mime_in/ext_in) ya resuelve el caso "no se mando ningun
        // archivo" por su cuenta (devuelven true si el error es UPLOAD_ERR_NO_FILE),
        // leyendo el archivo directo de $request->getFile(), no del valor que les
        // llega. Agregar permit_empty rompe esto: ese campo evalua "vacio" contra
        // request->getVar(), que para multipart/form-data nunca trae los archivos,
        // asi que permit_empty se activaba siempre y se salteaba toda la validacion
        // (una foto que no era imagen, o mas grande que el limite de PHP, se
        // publicaba igual, sin la foto, sin avisar del error).
        $reglasFoto = ['foto' => 'max_size[foto,4096]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|ext_in[foto,jpg,jpeg,png,webp]'];

        if (! $this->validate($reglasFoto)) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => implode(' ', $this->validator->getErrors()),
            ]);
        }

        $fotoUrl = null;
        $foto    = $this->request->getFile('foto');

        if ($foto !== null && $foto->isValid() && ! $foto->hasMoved()) {
            $nombreArchivo = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/herramientas', $nombreArchivo);
            $fotoUrl = base_url('uploads/herramientas/' . $nombreArchivo);
        }

        $herramientaModel = new HerramientaModel();

        $data = [
            'id_usuario'  => $this->session->get('id_usuario'),
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_dia'  => $this->request->getPost('precio_dia'),
            'precio_hora' => $this->request->getPost('precio_hora') ?: null,
            'estado'      => 'disponible',
            'foto_url'    => $fotoUrl,
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
