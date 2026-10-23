<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCamposHerramientas extends Migration
{
    public function up()
    {
    $this->forge->addColumn('herramientas', [
    'descripcion' => ['type' => 'TEXT', 'null' => true, 'after' => 'nombre'],
    'estado'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'disponible', 'after' => 'precio'],
    'foto_url'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'estado'],
]);
    }

    public function down()
    {
    $this->forge->dropColumn('herramientas', ['descripcion', 'estado', 'foto_url']);
    }
}
