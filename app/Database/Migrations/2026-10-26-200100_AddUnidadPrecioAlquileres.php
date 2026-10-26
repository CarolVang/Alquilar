<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnidadPrecioAlquileres extends Migration
{
    public function up()
    {
        $this->forge->addColumn('alquileres', [
            'unidad_precio' => ['type' => 'ENUM', 'constraint' => ['dia', 'hora'], 'null' => false, 'default' => 'dia', 'after' => 'precio_total'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('alquileres', 'unidad_precio');
    }
}
