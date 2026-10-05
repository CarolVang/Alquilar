<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndicesHerramientasAlquileres extends Migration
{
    public function up()
    {
        $this->forge->addKey('estado', false, false, 'idx_estado');
        $this->forge->processIndexes('herramientas');

        $this->forge->addKey('fecha_fin', false, false, 'idx_fecha_fin');
        $this->forge->processIndexes('alquileres');
    }

    public function down()
    {
        $this->forge->dropKey('herramientas', 'idx_estado');
        $this->forge->dropKey('alquileres', 'idx_fecha_fin');
    }
}
