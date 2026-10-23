<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagos extends Migration
{
    public function up()
    {
    $this->forge->addField([
    'id_pago'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
    'id_alquiler' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
    'monto'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
    'metodo'      => ['type' => 'VARCHAR', 'constraint' => 50],
    'estado'      => ['type' => 'VARCHAR', 'constraint' => 30],
]);
$this->forge->addPrimaryKey('id_pago');
$this->forge->addForeignKey('id_alquiler', 'alquileres', 'id_alquiler', 'CASCADE', 'CASCADE');
$this->forge->createTable('pagos');
    }

    public function down()
    {
    $this->forge->dropTable('pagos');
    }
}
