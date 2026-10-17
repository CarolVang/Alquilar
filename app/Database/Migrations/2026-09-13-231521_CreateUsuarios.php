<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
    $this->forge->addField([
    'id_usuario' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
    'nombre'     => ['type' => 'VARCHAR', 'constraint' => 100],
    'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
]);
$this->forge->addPrimaryKey('id_usuario');
$this->forge->createTable('usuarios');
    }

    public function down()
    {
    $this->forge->dropTable('usuarios');
    }
}
