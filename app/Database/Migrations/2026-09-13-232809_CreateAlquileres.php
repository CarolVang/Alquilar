<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlquileres extends Migration
{
    public function up()
    {
    $this->forge->addField([
    'id_alquiler'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
    'id_herramienta'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
    'id_usuario'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
    'fecha_inicio'    => ['type' => 'DATETIME'],
    'fecha_fin'       => ['type' => 'DATETIME'],
    'estado'          => ['type' => 'VARCHAR', 'constraint' => 30],
]);
$this->forge->addPrimaryKey('id_alquiler');
$this->forge->addForeignKey('id_herramienta', 'herramientas', 'id_herramienta', 'CASCADE', 'CASCADE');
$this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
$this->forge->createTable('alquileres');
    }

    public function down()
    {
    $this->forge->dropTable('alquileres');
    }
}
