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
        $precio      = $this->calcularPrecio((float) $herramienta['precio'], $fechaInicio, $fechaFin);

        return view('confirmacion', [
            'herramienta'    => $herramienta,
            'propietario'    => $propietario,
            'id_herramienta' => $idHerramienta,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
            'dias'           => $precio['dias'],
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
        $fechaInicio   = $this->request->getPost('fecha_inicio');
        $fechaFin      = $this->request->getPost('fecha_fin');

        $herramienta = (new HerramientaModel())->find($idHerramienta);

        if (! $herramienta) {
            return $this->response->setJSON([
                'exito'   => false,
                'mensaje' => 'Esa herramienta ya no existe.',
            ]);
        }

        // El precio se recalcula acá con el precio actual de la herramienta,
        // nunca se confía en un total que mande el navegador.
        $precio = $this->calcularPrecio((float) $herramienta['precio'], $fechaInicio, $fechaFin);

        $resultado = (new AlquilerModel())->crearReservaConValidacion(
            $idHerramienta,
            $this->session->get('id_usuario'),
            $fechaInicio,
            $fechaFin,
            $precio['total']
        );

        return $this->response->setJSON($resultado);
    }

    /**
     * Formula provisoria (dias x precio + 10% servicio), igual a la que
     * Luana ya tenia en el JS de detalle.php/confirmacion.php. La semana 7
     * del plan asigna el calculo automatico al Modelo, a cargo de Luz;
     * cuando ese metodo exista en HerramientaModel/AlquilerModel, esto se
     * reemplaza por esa llamada en vez de recalcularlo aca.
     */
    private function calcularPrecio(float $precioDia, string $fechaInicio, string $fechaFin): array
    {
        $diasInicio = new \DateTime((new \DateTime($fechaInicio))->format('Y-m-d'));
        $diasFin    = new \DateTime((new \DateTime($fechaFin))->format('Y-m-d'));
        $dias       = (int) $diasInicio->diff($diasFin)->days + 1;

        $subtotal = $dias * $precioDia;
        $servicio = round($subtotal * 0.1, 2);
        $total    = $subtotal + $servicio;

        return ['dias' => $dias, 'subtotal' => $subtotal, 'servicio' => $servicio, 'total' => $total];
    }
}
