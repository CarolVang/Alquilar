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
        $unidadPrecio  = $this->request->getGet('unidad_precio') === 'hora' ? 'hora' : 'dia';
        $fechaInicio   = $this->request->getGet('fecha_inicio');
        $fechaFin      = $this->request->getGet('fecha_fin');

        $herramienta = (new HerramientaModel())->find($idHerramienta);

        if (! $herramienta || ! $fechaInicio || ! $fechaFin) {
            return redirect()->to('/catalogo');
        }

        $precio = (new AlquilerModel())->calcularPrecio($idHerramienta, $unidadPrecio, $fechaInicio, $fechaFin);

        if (! $precio['exito']) {
            return redirect()->to('/detalle/' . $idHerramienta)->with('error', $precio['mensaje']);
        }

        $propietario = (new UsuarioModel())->find($herramienta['id_usuario']);

        return view('confirmacion', [
            'herramienta'    => $herramienta,
            'propietario'    => $propietario,
            'id_herramienta' => $idHerramienta,
            'unidad_precio'  => $unidadPrecio,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
            'cantidad'       => $precio['cantidad'],
            'subtotal'       => $precio['subtotal'],
            'servicio'       => $precio['servicio'],
            'total'          => $precio['total'],
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
        $unidadPrecio  = $this->request->getPost('unidad_precio') === 'hora' ? 'hora' : 'dia';
        $fechaInicio   = $this->request->getPost('fecha_inicio');
        $fechaFin      = $this->request->getPost('fecha_fin');

        $alquilerModel = new AlquilerModel();

        // El precio se recalcula acá con el precio actual de la herramienta,
        // nunca se confía en un total mandado por el navegador.
        $precio = $alquilerModel->calcularPrecio($idHerramienta, $unidadPrecio, $fechaInicio, $fechaFin);

        if (! $precio['exito']) {
            return $this->response->setJSON(['exito' => false, 'mensaje' => $precio['mensaje']]);
        }

        $resultado = $alquilerModel->crearReservaConValidacion(
            $idHerramienta,
            $this->session->get('id_usuario'),
            $fechaInicio,
            $fechaFin,
            $precio['total'],
            $unidadPrecio
        );

        return $this->response->setJSON($resultado);
    }
}
