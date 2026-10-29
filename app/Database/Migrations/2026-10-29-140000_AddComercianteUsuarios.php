<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddComercianteUsuarios extends Migration
{
    public function up()
    {
        // Cliente/Retador se fusionan: "rol" ya no distinguia nada de verdad
        // (ningun Controller chequeaba session('rol') antes de dejar publicar
        // o alquilar), asi que mantener los dos valores era una distincion
        // falsa. Se reutiliza la misma columna para "particular"/"comerciante",
        // que si tiene una diferencia real: el comerciante carga CUIT y razon
        // social, y eso habilita la insignia de empresa en el perfil.
        $this->forge->addColumn('usuarios', [
            'razon_social' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'rol',
            ],
            'cuit' => [
                'type'       => 'VARCHAR',
                'constraint' => 13,
                'null'       => true,
                'after'      => 'razon_social',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', ['razon_social', 'cuit']);
    }
}
