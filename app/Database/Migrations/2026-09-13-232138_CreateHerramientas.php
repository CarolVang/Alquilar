<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHerramientas extends Migration
{
    public function up()
    {
    $this->forge->addField([
    'id_herramienta' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
    'id_usuario'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
    'nombre'         => ['type' => 'VARCHAR', 'constraint' => 100],
    'precio'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
]);
$this->forge->addPrimaryKey('id_herramienta');
$this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
$this->forge->createTable('herramientas');
    }

    public function down()
    {
    $this->forge->dropTable('herramientas');
    }
}
