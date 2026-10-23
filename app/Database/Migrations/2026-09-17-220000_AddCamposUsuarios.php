<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCamposUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'after' => 'email'],
            'telefono' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'password'],
            'dni'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false, 'after' => 'telefono'],
            'rol'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'cliente', 'after' => 'dni'],
        ]);
        $this->forge->addUniqueKey('dni');
        $this->forge->addUniqueKey('email');
        $this->forge->processIndexes('usuarios');
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', ['password', 'telefono', 'dni', 'rol']);
    }
}
