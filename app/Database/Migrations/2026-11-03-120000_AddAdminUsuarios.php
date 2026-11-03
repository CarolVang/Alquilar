<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminUsuarios extends Migration
{
    public function up()
    {
        // Panel de administrador: un admin puede suspender cuentas (no pueden
        // iniciar sesion) y verificar empresas (la insignia de empresa
        // verificada aparece recien cuando un admin la aprueba). El rol
        // 'admin' no necesita columna nueva: usa la columna rol que ya existe.
        $this->forge->addColumn('usuarios', [
            'suspendido' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 0, 'after' => 'cuit'],
            'verificado' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 0, 'after' => 'suspendido'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', ['suspendido', 'verificado']);
    }
}
