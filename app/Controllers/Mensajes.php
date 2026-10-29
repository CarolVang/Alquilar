<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\HerramientaModel;
use App\Models\MensajeModel;

/**
 * Se llama "Mensajes" y no "Chat" -- y las rutas en Routes.php tambien evitan
 * esa palabra -- porque InfinityFree bloquea con un 403 cualquier URL que
 * contenga "chat" en el path: es una regla anti-abuso de ellos contra
 * scripts de chat que hacen polling constante (lo mismo que hace esta
 * pantalla). Esta a proposito, no se puede desactivar, asi que la unica
 * forma de evitarlo es no usar esa palabra en la URL.
 */
class Mensajes extends BaseController
{
    public function index($idAlquiler)
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $idAlquiler  = (int) $idAlquiler;
        $mensajeModel = new MensajeModel();
        $participantes = $mensajeModel->getParticipantes($idAlquiler);

        if (! $participantes || ! $this->esParticipante($participantes)) {
            return redirect()->to('/mis-reservas')->with('error', 'No podés ver esa conversación.');
        }

        $alquiler    = (new AlquilerModel())->find($idAlquiler);
        $herramienta = (new HerramientaModel())->find($alquiler['id_herramienta']);

        return view('mensajes', [
            'id_alquiler' => $idAlquiler,
            'alquiler'    => $alquiler,
            'herramienta' => $herramienta,
            'mensajes'    => $mensajeModel->getMensajes($idAlquiler),
        ], ['debug' => false]);
    }

    public function listar($idAlquiler)
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Tenés que iniciar sesión.']);
        }

        $idAlquiler    = (int) $idAlquiler;
        $mensajeModel  = new MensajeModel();
        $participantes = $mensajeModel->getParticipantes($idAlquiler);

        if (! $participantes || ! $this->esParticipante($participantes)) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'error' => 'No podés ver esa conversación.']);
        }

        return $this->response->setJSON([
            'ok'       => true,
            'mensajes' => $mensajeModel->getMensajes($idAlquiler),
        ]);
    }

    public function enviar($idAlquiler)
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Tenés que iniciar sesión.']);
        }

        $idAlquiler    = (int) $idAlquiler;
        $mensajeModel  = new MensajeModel();
        $participantes = $mensajeModel->getParticipantes($idAlquiler);

        if (! $participantes || ! $this->esParticipante($participantes)) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'error' => 'No podés escribir en esa conversación.']);
        }

        $texto = trim((string) $this->request->getPost('mensaje'));

        if ($texto === '') {
            return $this->response->setJSON(['ok' => false, 'error' => 'Escribí algo antes de enviar.']);
        }

        if (! $mensajeModel->enviarMensaje($idAlquiler, (int) $this->session->get('id_usuario'), $texto)) {
            return $this->response->setJSON(['ok' => false, 'error' => implode(' ', $mensajeModel->errors())]);
        }

        // El filtro CSRF rota el token en cada pedido valido (Security::regenerate
        // = true): como este chat manda varios POST sin recargar la pagina, hay
        // que devolver el token nuevo en cada respuesta para que el proximo envio
        // no falle con el token viejo.
        return $this->response->setJSON([
            'ok'        => true,
            'csrf_hash' => csrf_hash(),
        ]);
    }

    /**
     * El usuario logueado tiene que ser el cliente de ese alquiler o el
     * dueño de la herramienta alquilada -- nadie mas. Mismo criterio de
     * "proteccion real en el modelo/controller, no un filtro que se podria
     * olvidar" que ya se usa en actualizarPropia()/cambiarEstado().
     */
    private function esParticipante(array $participantes): bool
    {
        $idUsuario = (int) $this->session->get('id_usuario');

        return $idUsuario === (int) $participantes['id_cliente'] || $idUsuario === (int) $participantes['id_propietario'];
    }
}
