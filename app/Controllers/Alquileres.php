<?php

namespace App\Controllers;

use App\Models\AlquilerModel;

class Alquileres extends BaseController
{
    public function misReservas()
    {
        if (! $this->session->get('isLoggedIn')) {
            return view('mis_reservas', ['reservas' => []], ['debug' => false]);
        }

        $reservas = (new AlquilerModel())->getReservasCliente((int) $this->session->get('id_usuario'));

        return view('mis_reservas', ['reservas' => $reservas], ['debug' => false]);
    }

    public function misAlquileres()
    {
        if (! $this->session->get('isLoggedIn')) {
            return view('mis_alquileres', ['alquileres' => []], ['debug' => false]);
        }

        $alquileres = (new AlquilerModel())->getAlquileresPropietario((int) $this->session->get('id_usuario'));

        return view('mis_alquileres', ['alquileres' => $alquileres], ['debug' => false]);
    }

    public function cancelar($idAlquiler)
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['exito' => false, 'mensaje' => 'Tenés que iniciar sesión.']);
        }

        $ok = (new AlquilerModel())->cancelarReserva((int) $idAlquiler, (int) $this->session->get('id_usuario'));

        if (! $ok) {
            return $this->response->setJSON(['exito' => false, 'mensaje' => 'No se pudo cancelar la reserva.']);
        }

        return $this->response->setJSON(['exito' => true]);
    }

    public function avanzarEstado($idAlquiler)
    {
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['exito' => false, 'mensaje' => 'Tenés que iniciar sesión.']);
        }

        $nuevoEstado = (string) $this->request->getPost('estado');

        $ok = (new AlquilerModel())->avanzarEstado((int) $idAlquiler, (int) $this->session->get('id_usuario'), $nuevoEstado);

        if (! $ok) {
            return $this->response->setJSON(['exito' => false, 'mensaje' => 'No se pudo actualizar el alquiler.']);
        }

        return $this->response->setJSON(['exito' => true]);
    }
}
