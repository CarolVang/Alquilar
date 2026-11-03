<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Corre antes de cualquier ruta del grupo /admin (ver Routes.php). Un filtro
 * es mas seguro que un if en cada metodo del controlador: no hay forma de
 * olvidarse de protegerlo, porque CodeIgniter lo aplica a todo el grupo.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');

        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if ($session->get('rol') !== 'admin') {
            return redirect()->to('/');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
