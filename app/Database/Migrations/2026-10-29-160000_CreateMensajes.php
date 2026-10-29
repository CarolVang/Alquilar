<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMensajes extends Migration
{
    public function up()
    {
        // Chat simple, atado a un alquiler puntual (no es mensajeria libre
        // entre cualquier par de usuarios): cada conversacion es "la del
        // alquiler #X", con acceso solo para el cliente de ese alquiler y el
        // dueño de la herramienta. Eso da control de acceso gratis (ya existe
        // la relacion) sin inventar un sistema de contactos/conversaciones.
        $this->forge->addField([
            'id_mensaje'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_alquiler' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_usuario'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'mensaje'     => ['type' => 'VARCHAR', 'constraint' => 500],
            'creado_en'   => ['type' => 'DATETIME'],
        ]);
        $this->forge->addPrimaryKey('id_mensaje');
        $this->forge->addForeignKey('id_alquiler', 'alquileres', 'id_alquiler', '', 'CASCADE');
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', '', 'CASCADE');
        $this->forge->addKey('id_alquiler');
        $this->forge->createTable('mensajes');
    }

    public function down()
    {
        $this->forge->dropTable('mensajes');
    }
}
