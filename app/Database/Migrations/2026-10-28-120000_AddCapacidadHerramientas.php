<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCapacidadHerramientas extends Migration
{
    public function up()
    {
        // Mismo caso que "categoria": el campo "Capacidad / detalle tecnico"
        // existe en publicar.php desde siempre, pero el Controller nunca lo
        // guardaba. Texto libre (ej: "hasta 13mm", "150kg por nivel"), por
        // eso VARCHAR y no ENUM. Opcional, asi que nullable.
        $this->forge->addColumn('herramientas', [
            'capacidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'precio_hora',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('herramientas', 'capacidad');
    }
}
