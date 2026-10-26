<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFechaOperacionPagos extends Migration
{
    public function up()
    {
        // La propuesta del proyecto define "fecha de operacion" como parte de
        // Pagos, pero la migracion original nunca la agrego.
        $this->forge->addColumn('pagos', [
            'fecha_operacion' => ['type' => 'DATETIME', 'null' => false, 'after' => 'estado'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pagos', 'fecha_operacion');
    }
}
