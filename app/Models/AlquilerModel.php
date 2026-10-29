<?php

namespace App\Models;

use CodeIgniter\Model;

class AlquilerModel extends Model
{
    protected $table            = 'alquileres';
    protected $primaryKey       = 'id_alquiler';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_herramienta', 'id_usuario', 'fecha_inicio', 'fecha_fin', 'estado', 'precio_total', 'unidad_precio'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_herramienta' => 'required|is_natural_no_zero',
        'id_usuario'      => 'required|is_natural_no_zero',
        'fecha_inicio'    => 'required|valid_date[Y-m-d H:i:s]',
        'fecha_fin'       => 'required|valid_date[Y-m-d H:i:s]',
        'estado'          => 'required|in_list[confirmada,retirada,devuelta,cancelada]',
        'precio_total'    => 'required|decimal|greater_than[0]',
        'unidad_precio'   => 'required|in_list[dia,hora]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function haySolapamiento(int $idHerramienta, string $fechaInicio, string $fechaFin, ?int $idAlquilerExcluir = null, int $margenHoras = 2): bool
    {
        $fechaFinConMargen = (new \DateTime($fechaFin))
            ->modify("+{$margenHoras} hours")
            ->format('Y-m-d H:i:s');

        $builder = $this->where('id_herramienta', $idHerramienta)
                         ->whereIn('estado', ['confirmada'])
                         ->where('fecha_inicio <', $fechaFinConMargen)
                         ->where("DATE_ADD(fecha_fin, INTERVAL {$margenHoras} HOUR) >", $fechaInicio);

        if ($idAlquilerExcluir !== null) {
            $builder->where('id_alquiler !=', $idAlquilerExcluir);
        }

        return $builder->countAllResults() > 0;
    }
    
    public function getPeriodosOcupados(int $idHerramienta): array
    {
        return $this->select('fecha_inicio, fecha_fin')
                    ->where('id_herramienta', $idHerramienta)
                    ->where('estado', 'confirmada')
                    ->findAll();
    }

    /**
     * Para el catalogo: de que herramientas (entre las dadas) hay una reserva
     * confirmada que las ocupa justo ahora, y hasta cuando. Una sola consulta
     * para toda la grilla en vez de una por tarjeta.
     *
     * @param list<int> $idsHerramienta
     * @return array<int, string> id_herramienta => fecha_fin de la reserva que la ocupa ahora
     */
    public function getOcupacionActual(array $idsHerramienta): array
    {
        if ($idsHerramienta === []) {
            return [];
        }

        $filas = $this->select('id_herramienta, MAX(fecha_fin) AS libre_desde')
                       ->whereIn('id_herramienta', $idsHerramienta)
                       ->where('estado', 'confirmada')
                       ->where('fecha_inicio <=', date('Y-m-d H:i:s'))
                       ->where('fecha_fin >=', date('Y-m-d H:i:s'))
                       ->groupBy('id_herramienta')
                       ->findAll();

        $mapa = [];
        foreach ($filas as $fila) {
            $mapa[(int) $fila['id_herramienta']] = $fila['libre_desde'];
        }

        return $mapa;
    }

    /**
     * Reservas hechas POR un cliente (lo que alquiló de otros).
     */
    public function getReservasCliente(int $idUsuario): array
    {
        return $this->select('alquileres.*, herramientas.nombre AS herramienta_nombre, herramientas.foto_url, usuarios.nombre AS propietario_nombre, usuarios.rol AS propietario_rol, usuarios.telefono AS propietario_telefono')
                    ->join('herramientas', 'herramientas.id_herramienta = alquileres.id_herramienta')
                    ->join('usuarios', 'usuarios.id_usuario = herramientas.id_usuario')
                    ->where('alquileres.id_usuario', $idUsuario)
                    ->orderBy('alquileres.fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Alquileres recibidos SOBRE las herramientas de un retador (lo que le alquilaron a otros).
     */
    public function getAlquileresPropietario(int $idUsuario): array
    {
        return $this->select('alquileres.*, herramientas.nombre AS herramienta_nombre, herramientas.foto_url, usuarios.nombre AS cliente_nombre, usuarios.telefono AS cliente_telefono, usuarios.rol AS cliente_rol')
                    ->join('herramientas', 'herramientas.id_herramienta = alquileres.id_herramienta')
                    ->join('usuarios', 'usuarios.id_usuario = alquileres.id_usuario')
                    ->where('herramientas.id_usuario', $idUsuario)
                    ->orderBy('alquileres.fecha_inicio', 'DESC')
                    ->findAll();
    }

    /**
     * Cancela una reserva, solo si todavia esta 'confirmada' y le pertenece al cliente.
     */
    public function cancelarReserva(int $idAlquiler, int $idUsuario): bool
    {
        $filas = $this->where('id_alquiler', $idAlquiler)
                      ->where('id_usuario', $idUsuario)
                      ->where('estado', 'confirmada')
                      ->set(['estado' => 'cancelada'])
                      ->update();

        return $filas && $this->db->affectedRows() > 0;
    }

    /**
     * Avanza el estado de un alquiler (confirmada -> retirada -> devuelta),
     * solo si lo pide el propietario de la herramienta.
     */
    public function avanzarEstado(int $idAlquiler, int $idUsuarioPropietario, string $nuevoEstado): bool
    {
        $siguiente = ['confirmada' => 'retirada', 'retirada' => 'devuelta'];

        $alquiler = $this->select('alquileres.id_alquiler, alquileres.estado, herramientas.id_usuario AS id_propietario')
                          ->join('herramientas', 'herramientas.id_herramienta = alquileres.id_herramienta')
                          ->where('alquileres.id_alquiler', $idAlquiler)
                          ->get()
                          ->getRowArray();

        if ($alquiler === null || (int) $alquiler['id_propietario'] !== $idUsuarioPropietario) {
            return false;
        }

        if (($siguiente[$alquiler['estado']] ?? null) !== $nuevoEstado) {
            return false;
        }

        $filas = $this->update($idAlquiler, ['estado' => $nuevoEstado]);

        return (bool) $filas;
    }

    /**
     * Un alquiler puntual con sus datos de herramienta y propietario,
     * solo si pertenece al cliente dado (para la pantalla de recibo de pago).
     */
    public function getReservaDeCliente(int $idAlquiler, int $idUsuario): ?array
    {
        return $this->select('alquileres.*, herramientas.nombre AS herramienta_nombre, herramientas.foto_url, usuarios.nombre AS propietario_nombre')
                    ->join('herramientas', 'herramientas.id_herramienta = alquileres.id_herramienta')
                    ->join('usuarios', 'usuarios.id_usuario = herramientas.id_usuario')
                    ->where('alquileres.id_alquiler', $idAlquiler)
                    ->where('alquileres.id_usuario', $idUsuario)
                    ->get()
                    ->getRowArray();
    }

    public function crearReservaConValidacion(int $idHerramienta, int $idUsuario, string $fechaInicio, string $fechaFin, float $precioTotal, string $unidadPrecio): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $db->query(
            'SELECT id_alquiler FROM alquileres WHERE id_herramienta = ? AND estado = "confirmada" FOR UPDATE',
            [$idHerramienta]
        );

        if ($this->haySolapamiento($idHerramienta, $fechaInicio, $fechaFin)) {
            $db->transRollback();
            return ['exito' => false, 'mensaje' => 'Esas fechas ya no estan disponibles para esta herramienta.'];
        }

        $idNuevo = $this->insert([
            'id_herramienta' => $idHerramienta,
            'id_usuario'     => $idUsuario,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
            'estado'         => 'confirmada',
            'precio_total'   => $precioTotal,
            'unidad_precio'  => $unidadPrecio,
        ]);

        // No existe pasarela de pago real todavia (PagoModel.metodo solo
        // admite 'simulado'), asi que confirmar la reserva simula el cobro
        // del total en el mismo momento y deja el comprobante en pagos.
        (new PagoModel())->insert([
            'id_alquiler' => $idNuevo,
            'monto'       => $precioTotal,
            'metodo'      => 'simulado',
            'estado'      => 'pagado',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['exito' => false, 'mensaje' => 'No se pudo confirmar la reserva, intenta de nuevo.'];
        }

        return ['exito' => true, 'id_alquiler' => $idNuevo];
    }

    /**
     * Calcula el precio de una reserva en la unidad elegida (dia u hora).
     * Decision de producto de Carol (PM): las herramientas se pueden alquilar
     * por dia o por hora. Basado en el calcularPrecio() que escribio Luz en
     * feature/backend (que asumia precio siempre por hora); se extiende para
     * soportar ambas unidades contra precio_dia/precio_hora, y se suma la
     * tarifa de servicio del 10% que ya se usaba en el resto del sistema.
     */
    public function calcularPrecio(int $idHerramienta, string $unidadPrecio, string $fechaInicio, string $fechaFin): array
    {
        $herramienta = $this->db->table('herramientas')
                                 ->select('precio_dia, precio_hora')
                                 ->where('id_herramienta', $idHerramienta)
                                 ->get()
                                 ->getRowArray();

        if ($herramienta === null) {
            return ['exito' => false, 'mensaje' => 'La herramienta no existe.'];
        }

        $inicio = new \DateTime($fechaInicio);
        $fin    = new \DateTime($fechaFin);

        if ($fin <= $inicio) {
            return ['exito' => false, 'mensaje' => 'La fecha de fin debe ser posterior a la de inicio.'];
        }

        if ($unidadPrecio === 'hora') {
            if ($herramienta['precio_hora'] === null) {
                return ['exito' => false, 'mensaje' => 'Esta herramienta no se puede alquilar por hora.'];
            }

            $precioUnidad = (float) $herramienta['precio_hora'];
            $cantidad     = round(($fin->getTimestamp() - $inicio->getTimestamp()) / 3600, 2);
        } else {
            $precioUnidad = (float) $herramienta['precio_dia'];

            $diasInicio = new \DateTime($inicio->format('Y-m-d'));
            $diasFin    = new \DateTime($fin->format('Y-m-d'));
            $cantidad   = (int) $diasInicio->diff($diasFin)->days + 1;
        }

        $subtotal = round($precioUnidad * $cantidad, 2);
        $servicio = round($subtotal * 0.1, 2);
        $total    = $subtotal + $servicio;

        return [
            'exito'         => true,
            'unidad_precio' => $unidadPrecio,
            'precio_unidad' => $precioUnidad,
            'cantidad'      => $cantidad,
            'subtotal'      => $subtotal,
            'servicio'      => $servicio,
            'total'         => $total,
        ];
    }
}
