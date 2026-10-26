<?php

namespace App\Controllers;

use App\Models\AlquilerModel;
use App\Models\PagoModel;

class Pago extends BaseController
{
    /**
     * Recibo de pago de una reserva ya confirmada.
     *
     * No hay pasarela de pago real en el proyecto (PagoModel.metodo solo
     * admite 'simulado'): el pago se registra automaticamente al confirmar
     * la reserva (ver AlquilerModel::crearReservaConValidacion()), asi que
     * esta pantalla ya no simula una tarjeta, solo muestra el comprobante
     * real que quedo guardado.
     */
    public function index($idAlquiler)
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $alquiler = (new AlquilerModel())->getReservaDeCliente((int) $idAlquiler, (int) $this->session->get('id_usuario'));

        if ($alquiler === null) {
            return redirect()->to('/mis-reservas');
        }

        $pago = (new PagoModel())->where('id_alquiler', $alquiler['id_alquiler'])->first();

        return view('pago', [
            'alquiler' => $alquiler,
            'pago'     => $pago,
        ], ['debug' => false]);
    }
}
