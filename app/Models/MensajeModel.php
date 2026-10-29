<?php

namespace App\Models;

use CodeIgniter\Model;

class MensajeModel extends Model
{
    protected $table            = 'mensajes';
    protected $primaryKey       = 'id_mensaje';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_alquiler', 'id_usuario', 'mensaje', 'creado_en'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts        = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id_alquiler' => 'required|is_natural_no_zero',
        'id_usuario'  => 'required|is_natural_no_zero',
        'mensaje'     => 'required|max_length[500]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Trae a los dos usuarios del alquiler (cliente y propietario de la
     * herramienta) para poder chequear que quien pide ver/mandar mensajes
     * sea de verdad una de esas dos personas, antes de tocar la tabla
     * mensajes. Devuelve null si el alquiler no existe.
     */
    public function getParticipantes(int $idAlquiler): ?array
    {
        $fila = $this->db->table('alquileres')
            ->select('alquileres.id_usuario AS id_cliente, herramientas.id_usuario AS id_propietario')
            ->join('herramientas', 'herramientas.id_herramienta = alquileres.id_herramienta')
            ->where('alquileres.id_alquiler', $idAlquiler)
            ->get()
            ->getRowArray();

        return $fila;
    }

    /**
     * Mensajes de un alquiler, mas viejo primero (orden de lectura natural
     * de un chat), con el nombre de quien mando cada uno.
     */
    public function getMensajes(int $idAlquiler): array
    {
        return $this->select('mensajes.*, usuarios.nombre AS emisor_nombre')
                    ->join('usuarios', 'usuarios.id_usuario = mensajes.id_usuario')
                    ->where('mensajes.id_alquiler', $idAlquiler)
                    ->orderBy('mensajes.id_mensaje', 'ASC')
                    ->findAll();
    }

    public function enviarMensaje(int $idAlquiler, int $idUsuario, string $texto): bool
    {
        return (bool) $this->insert([
            'id_alquiler' => $idAlquiler,
            'id_usuario'  => $idUsuario,
            'mensaje'     => $texto,
            'creado_en'   => date('Y-m-d H:i:s'),
        ]);
    }
}
