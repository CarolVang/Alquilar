<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\HerramientaModel;
use App\Models\UsuarioModel;

/**
 * Panel de administrador. No chequea el rol en cada metodo porque todas sus
 * rutas estan en el grupo /admin, protegido por AdminFilter (ver Routes.php):
 * a este controlador solo llega un admin con sesion iniciada.
 */
class Admin extends BaseController
{
    public function index()
    {
        $usuarios     = (new UsuarioModel())->getParaAdmin();
        $herramientas = (new HerramientaModel())->getParaAdmin();
        $alquilerModel = new AlquilerModel();

        $porEstadoHerramienta = array_count_values(array_column($herramientas, 'estado'));

        return view('admin', [
            'usuarios'     => $usuarios,
            'herramientas' => $herramientas,
            'alquileres'   => $alquilerModel->getParaAdmin(),
            'resumen'      => [
                'usuarios'             => count($usuarios),
                'suspendidos'          => count(array_filter($usuarios, static fn ($u) => (int) $u['suspendido'] === 1)),
                'empresas_pendientes'  => count(array_filter($usuarios, static fn ($u) => $u['rol'] === 'comerciante' && (int) $u['verificado'] === 0)),
                'herramientas'         => count($herramientas),
                'disponibles'          => $porEstadoHerramienta['disponible'] ?? 0,
                'pausadas'             => $porEstadoHerramienta['no_disponible'] ?? 0,
                'bloqueadas'           => $porEstadoHerramienta['bloqueada'] ?? 0,
                'alquileres_estado'    => $alquilerModel->contarPorEstado(),
                'facturado'            => $alquilerModel->totalFacturado(),
            ],
        ], ['debug' => false]);
    }

    public function suspender($idUsuario)
    {
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->find((int) $idUsuario);

        if ($usuario === null) {
            return redirect()->to('/admin#usuarios')->with('error', 'Ese usuario no existe.');
        }

        // Ni a uno mismo ni a otro admin: asi nadie puede dejar el sitio sin
        // administradores por error, ni sacar a otro admin.
        if ($usuario['rol'] === 'admin') {
            return redirect()->to('/admin#usuarios')->with('error', 'No se puede suspender a un administrador.');
        }

        $suspender = (int) $usuario['suspendido'] === 0;
        $usuarioModel->update((int) $idUsuario, ['suspendido' => $suspender ? 1 : 0]);

        return redirect()->to('/admin#usuarios')->with('mensaje', ($suspender ? 'Cuenta suspendida: ' : 'Cuenta reactivada: ') . $usuario['nombre']);
    }

    public function verificar($idUsuario)
    {
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->find((int) $idUsuario);

        if ($usuario === null || $usuario['rol'] !== 'comerciante') {
            return redirect()->to('/admin#usuarios')->with('error', 'Solo se pueden verificar cuentas de empresa.');
        }

        $verificar = (int) $usuario['verificado'] === 0;
        $usuarioModel->update((int) $idUsuario, ['verificado' => $verificar ? 1 : 0]);

        return redirect()->to('/admin#usuarios')->with('mensaje', ($verificar ? 'Empresa verificada: ' : 'Se quitó la verificación de: ') . ($usuario['razon_social'] ?: $usuario['nombre']));
    }

    public function bloquear($idHerramienta)
    {
        $herramientaModel = new HerramientaModel();
        $herramienta      = $herramientaModel->find((int) $idHerramienta);

        if ($herramienta === null) {
            return redirect()->to('/admin#publicaciones')->with('error', 'Esa publicación no existe.');
        }

        $bloquear = $herramienta['estado'] !== 'bloqueada';
        $herramientaModel->cambiarBloqueo((int) $idHerramienta, $bloquear);

        return redirect()->to('/admin#publicaciones')->with('mensaje', ($bloquear ? 'Publicación bloqueada: ' : 'Publicación desbloqueada: ') . $herramienta['nombre']);
    }
}
