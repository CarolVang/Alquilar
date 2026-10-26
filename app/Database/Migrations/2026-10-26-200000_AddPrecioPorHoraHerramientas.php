<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrecioPorHoraHerramientas extends Migration
{
    public function up()
    {
        // Decision de producto de Carol (PM): una herramienta se puede alquilar
        // por dia y/o por hora. 'precio' pasa a ser el precio por dia
        // (mantiene el significado que ya tenia en todo el sistema), y se suma
        // precio_hora como opcional (un retador puede no ofrecer esa modalidad).
        $this->forge->modifyColumn('herramientas', [
            'precio' => [
                'name'       => 'precio_dia',
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
        ]);

        $this->forge->addColumn('herramientas', [
            'precio_hora' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true, 'after' => 'precio_dia'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('herramientas', 'precio_hora');

        $this->forge->modifyColumn('herramientas', [
            'precio_dia' => [
                'name'       => 'precio',
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
        ]);
    }
}
