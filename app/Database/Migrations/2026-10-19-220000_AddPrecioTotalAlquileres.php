<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrecioTotalAlquileres extends Migration
{
    public function up()
    {
        $this->forge->addColumn('alquileres', [
            'precio_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'after' => 'estado'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('alquileres', 'precio_total');
    }
}
