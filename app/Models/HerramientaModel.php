<?php

namespace App\Models;

use CodeIgniter\Model;

class HerramientaModel extends Model
{
    protected $table             = 'herramientas';
    protected $primaryKey       = 'id_herramienta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_usuario', 'nombre', 'descripcion', 'precio', 'estado', 'foto_url'];

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
    'id_usuario' => 'required|is_natural_no_zero',
    'nombre'     => 'required|min_length[3]|max_length[100]',
    'descripcion'=> 'permit_empty|max_length[500]',
    'precio'     => 'required|decimal|greater_than[0]',
    'estado'     => 'permit_empty|in_list[disponible,alquilada,no_disponible]',
    'foto_url'   => 'permit_empty|valid_url_strict',
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
}
