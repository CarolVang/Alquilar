<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoriaHerramientas extends Migration
{
    public function up()
    {
        // El formulario de publicar.php ya pedia "Categoria" como obligatorio
        // desde que lo armo Luana, pero el Controller nunca la guardaba: se
        // perdia en silencio. Se agrega como columna real con los mismos 6
        // valores que el <select> siempre ofrecio. Nullable porque las
        // herramientas publicadas antes de este cambio no tienen ninguna.
        $this->forge->addColumn('herramientas', [
            'categoria' => [
                'type'       => 'ENUM',
                'constraint' => ['Herramientas', 'Construcción', 'Recreación', 'Jardín', 'Eventos', 'Vehículos'],
                'null'       => true,
                'after'      => 'nombre',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('herramientas', 'categoria');
    }
}
