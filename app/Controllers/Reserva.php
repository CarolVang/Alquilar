<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\HerramientaModel;
use App\Models\UsuarioModel;

class Reserva extends BaseController
{
    public function confirmacion()
    {
        $idHerramienta = (int) $this->request->getGet('id_herramienta');
        $fechaInicio    = $this->request->getGet('fecha_inicio');
        $fechaFin       = $this->request->getGet('fecha_fin');

        $herramienta = (new HerramientaModel())->find($idHerramienta);

        if (! $herramienta || ! $fechaInicio || ! $fechaFin) {
            return redirect()->to('/catalogo');
        }

        $propietario = (new UsuarioModel())->find($herramienta['id_usuario']);

        $inicio     = new \DateTime($fechaInicio);
        $fin        = new \DateTime($fechaFin);
        $diasInicio = new \DateTime($inicio->format('Y-m-d'));
        $diasFin    = new \DateTime($fin->format('Y-m-d'));
        $dias       = (int) $diasInicio->diff($diasFin)->days + 1;

        // Formula provisoria (dias x precio + 10% servicio), igual a la que
        // Luana ya tenia en el JS de detalle.php/confirmacion.php. La semana 7
        // del plan asigna el calculo automatico al Modelo, a cargo de Luz;
        // cuando ese metodo exista en HerramientaModel/AlquilerModel, esto se
        // reemplaza por esa llamada en vez de recalcularlo aca.
        $subtotal = $dias * (float) $herramienta['precio'];
        $servicio = round($subtotal * 0.1, 2);
        $total    = $subtotal + $servicio;

        return view('confirmacion', [
            'herramienta'   => $herramienta,
            'propietario'   => $propietario,
            'id_herramienta'=> $idHerramienta,
            'fecha_inicio'  => $fechaInicio,
            'fecha_fin'     => $fechaFin,
            'dias'          => $dias,
            'subtotal'      => $subtotal,
            'servicio'      => $servicio,
            'total'         => $total,
        ], ['debug' => false]);
    }

    public function confirmar()
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON([
                'exito'   => false,
                'mensaje' => 'Tenés que iniciar sesión para reservar.',
            ]);
        }

        $idHerramienta = (int) $this->request->getPost('id_herramienta');
        $fechaInicio   = $this->request->getPost('fecha_inicio');
        $fechaFin      = $this->request->getPost('fecha_fin');

        $resultado = (new AlquilerModel())->crearReservaConValidacion(
            $idHerramienta,
            $this->session->get('id_usuario'),
            $fechaInicio,
            $fechaFin
        );

        return $this->response->setJSON($resultado);
    }
}
