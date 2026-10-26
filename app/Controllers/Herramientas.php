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

        $resultadoFoto = $this->procesarFotoSubida();

        if ($resultadoFoto['error'] !== null) {
            return $this->response->setJSON(['ok' => false, 'error' => $resultadoFoto['error']]);
        }

        $herramientaModel = new HerramientaModel();

        $data = [
            'id_usuario'  => $this->session->get('id_usuario'),
            'nombre'      => $this->request->getPost('nombre'),
            'categoria'   => $this->request->getPost('categoria'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_dia'  => $this->request->getPost('precio_dia'),
            'precio_hora' => $this->request->getPost('precio_hora') ?: null,
            'estado'      => 'disponible',
            'foto_url'    => $resultadoFoto['url'],
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

    public function editar($id)
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $herramienta = (new HerramientaModel())->find($id);

        if (! $herramienta || (int) $herramienta['id_usuario'] !== (int) $this->session->get('id_usuario')) {
            return redirect()->to('/mis-herramientas')->with('error', 'Esa herramienta no existe o no te pertenece.');
        }

        return view('publicar', ['herramienta' => $herramienta], ['debug' => false]);
    }

    public function actualizar($id)
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok'    => false,
                'error' => 'Tenés que iniciar sesión.',
            ]);
        }

        $herramientaModel = new HerramientaModel();
        $herramienta       = $herramientaModel->find($id);

        if (! $herramienta || (int) $herramienta['id_usuario'] !== (int) $this->session->get('id_usuario')) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Esa herramienta no existe o no te pertenece.']);
        }

        $resultadoFoto = $this->procesarFotoSubida();

        if ($resultadoFoto['error'] !== null) {
            return $this->response->setJSON(['ok' => false, 'error' => $resultadoFoto['error']]);
        }

        $data = [
            'nombre'      => $this->request->getPost('nombre'),
            'categoria'   => $this->request->getPost('categoria'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio_dia'  => $this->request->getPost('precio_dia'),
            'precio_hora' => $this->request->getPost('precio_hora') ?: null,
        ];

        // Solo se toca foto_url si de verdad se subio una foto nueva;
        // si no, la herramienta se queda con la que ya tenia.
        if ($resultadoFoto['url'] !== null) {
            $data['foto_url'] = $resultadoFoto['url'];
        }

        if (! $herramientaModel->actualizarPropia((int) $id, (int) $this->session->get('id_usuario'), $data)) {
            return $this->response->setJSON([
                'ok'    => false,
                'error' => implode(' ', $herramientaModel->errors()),
            ]);
        }

        // La foto vieja se borra recien despues de que el UPDATE confirmo bien,
        // para no perderla si algo fallaba a mitad de camino.
        if ($resultadoFoto['url'] !== null && ! empty($herramienta['foto_url'])) {
            $this->borrarFotoLocal($herramienta['foto_url']);
        }

        return $this->response->setJSON(['ok' => true]);
    }

    public function cambiarEstado($id)
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Tenés que iniciar sesión.']);
        }

        $nuevoEstado = (string) $this->request->getPost('estado');

        $ok = (new HerramientaModel())->cambiarEstado((int) $id, (int) $this->session->get('id_usuario'), $nuevoEstado);

        if (! $ok) {
            return $this->response->setJSON(['ok' => false, 'error' => 'No se pudo actualizar el estado.']);
        }

        return $this->response->setJSON(['ok' => true]);
    }

    /**
     * Valida (si se mando una) y mueve la foto subida a public/uploads/herramientas.
     * Usado por crear() y actualizar() para no duplicar la misma logica.
     *
     * @return array{url: string|null, error: string|null}
     */
    private function procesarFotoSubida(): array
    {
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
            return ['url' => null, 'error' => implode(' ', $this->validator->getErrors())];
        }

        $foto = $this->request->getFile('foto');

        if ($foto === null || ! $foto->isValid() || $foto->hasMoved()) {
            return ['url' => null, 'error' => null];
        }

        $nombreArchivo = $foto->getRandomName();
        $foto->move(FCPATH . 'uploads/herramientas', $nombreArchivo);

        return ['url' => base_url('uploads/herramientas/' . $nombreArchivo), 'error' => null];
    }

    /**
     * Borra del disco la foto anterior de una herramienta, solo si vive
     * adentro de public/uploads/herramientas (nunca un path fuera de ahi).
     */
    private function borrarFotoLocal(string $fotoUrl): void
    {
        $nombreArchivo = basename($fotoUrl);
        $ruta          = FCPATH . 'uploads/herramientas/' . $nombreArchivo;

        if (dirname($ruta) === FCPATH . 'uploads/herramientas' && is_file($ruta)) {
            unlink($ruta);
        }
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
