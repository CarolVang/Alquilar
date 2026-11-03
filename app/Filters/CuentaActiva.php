<?php

namespace App\Filters;

use App\Models\UsuarioModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Corre antes de cada pedido (filtro global, ver Filters.php). La sesion
 * guarda el rol al iniciar sesion y lo recuerda 2 horas: sin este filtro, si
 * un admin suspende una cuenta o le saca el rol de admin a alguien, esa
 * persona seguiria adentro con los permisos viejos hasta que venza la sesion.
 * Aca se vuelve a mirar la base en cada pedido (una consulta chica por id).
 */
class CuentaActiva implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');

        if (! $session->get('isLoggedIn')) {
            return null;
        }

        $usuario = (new UsuarioModel())
            ->select('id_usuario, nombre, rol, suspendido')
            ->find((int) $session->get('id_usuario'));

        if ($usuario === null || (int) $usuario['suspendido'] === 1) {
            // Se sacan solo los datos de la sesion iniciada (no se destruye
            // la sesion entera) para que el mensaje de abajo llegue al login.
            $session->remove(['id_usuario', 'nombre', 'rol', 'isLoggedIn']);

            return redirect()->to('/login')->with('error', 'Tu cuenta está suspendida. Comunicate con un administrador.');
        }

        $session->set(['rol' => $usuario['rol'], 'nombre' => $usuario['nombre']]);

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
