<?php

namespace App\Models;

use CodeIgniter\Model;

class AlquilerModel extends Model
{
    protected $table            = 'alquileres';
    protected $primaryKey       = 'id_alquiler';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_herramienta', 'id_usuario', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_herramienta' => 'required|is_natural_no_zero',
        'id_usuario'      => 'required|is_natural_no_zero',
        'fecha_inicio'    => 'required|valid_date[Y-m-d H:i:s]',
        'fecha_fin'       => 'required|valid_date[Y-m-d H:i:s]',
        'estado'          => 'required|in_list[confirmada,retirada,devuelta,cancelada]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function haySolapamiento(int $idHerramienta, string $fechaInicio, string $fechaFin, ?int $idAlquilerExcluir = null, int $margenHoras = 2): bool
    {
        $fechaFinConMargen = (new \DateTime($fechaFin))
            ->modify("+{$margenHoras} hours")
            ->format('Y-m-d H:i:s');

        $builder = $this->where('id_herramienta', $idHerramienta)
                         ->whereIn('estado', ['confirmada'])
                         ->where('fecha_inicio <', $fechaFinConMargen)
                         ->where("DATE_ADD(fecha_fin, INTERVAL {$margenHoras} HOUR) >", $fechaInicio);

        if ($idAlquilerExcluir !== null) {
            $builder->where('id_alquiler !=', $idAlquilerExcluir);
        }

        return $builder->countAllResults() > 0;
    }
    
    public function getPeriodosOcupados(int $idHerramienta): array
    {
        return $this->select('fecha_inicio, fecha_fin')
                    ->where('id_herramienta', $idHerramienta)
                    ->where('estado', 'confirmada')
                    ->findAll();
    }

    public function crearReservaConValidacion(int $idHerramienta, int $idUsuario, string $fechaInicio, string $fechaFin): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $db->query(
            'SELECT id_alquiler FROM alquileres WHERE id_herramienta = ? AND estado = "confirmada" FOR UPDATE',
            [$idHerramienta]
        );

        if ($this->haySolapamiento($idHerramienta, $fechaInicio, $fechaFin)) {
            $db->transRollback();
            return ['exito' => false, 'mensaje' => 'Esas fechas ya no estan disponibles para esta herramienta.'];
        }

        $idNuevo = $this->insert([
            'id_herramienta' => $idHerramienta,
            'id_usuario'     => $idUsuario,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
            'estado'         => 'confirmada',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['exito' => false, 'mensaje' => 'No se pudo confirmar la reserva, intenta de nuevo.'];
        }

        return ['exito' => true, 'id_alquiler' => $idNuevo];
    }
}
