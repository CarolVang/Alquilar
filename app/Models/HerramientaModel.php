<?php

namespace App\Models;

use CodeIgniter\Model;

class HerramientaModel extends Model
{
    protected $table             = 'herramientas';
    protected $primaryKey        = 'id_herramienta';
    protected $useAutoIncrement  = true;
    protected $returnType        = 'array';
    protected $useSoftDeletes    = false;
    protected $protectFields     = true;
    protected $allowedFields     = ['id_usuario', 'nombre', 'descripcion', 'precio_dia', 'precio_hora', 'estado', 'foto_url'];

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
    protected $validationRules = [
        'id_usuario'  => 'required|is_natural_no_zero',
        'nombre'      => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[500]',
        'precio_dia'  => 'required|decimal|greater_than[0]',
        'precio_hora' => 'permit_empty|decimal|greater_than[0]',
        'estado'      => 'permit_empty|in_list[disponible,alquilada,no_disponible]',
        // La URL la genera el servidor al mover el archivo subido (Herramientas::crear()),
        // nunca viene como texto libre del usuario, por eso no exige valid_url_strict.
        'foto_url'    => 'permit_empty|max_length[255]',
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

    public function getDisponibles()
    {
        return $this->where('estado', 'disponible')->findAll();
    }

    public function getCatalogoOrdenado()
    {
        return $this->select('herramientas.*, usuarios.nombre AS propietario_nombre')
                    ->join('usuarios', 'usuarios.id_usuario = herramientas.id_usuario')
                    ->orderBy(
                        "FIELD(herramientas.estado, 'disponible', 'no_disponible', 'alquilada')",
                        '',
                        false
                    )
                    ->orderBy('herramientas.nombre', 'ASC')
                    ->findAll();
    }
}
