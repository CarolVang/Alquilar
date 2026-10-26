<?php
/**
 * Base visual: feature/frontend-luana. Esa version simulaba todo el flujo
 * de pago en el navegador (tarjeta con validacion Luhn, setTimeout, estado
 * guardado en localStorage) porque todavia no existia ninguna conexion con
 * el backend. En este proyecto no hay pasarela de pago real (PagoModel
 * solo admite metodo='simulado'): el pago se simula y queda registrado
 * automaticamente al confirmar la reserva, asi que esta pantalla ya no
 * pide tarjeta, solo muestra el comprobante real guardado en la tabla pagos.
 */
$diasEs  = ['Mon' => 'lun', 'Tue' => 'mar', 'Wed' => 'mié', 'Thu' => 'jue', 'Fri' => 'vie', 'Sat' => 'sáb', 'Sun' => 'dom'];
$mesesEs = [1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'];

function fechaLargaEs(string $fecha, array $diasEs, array $mesesEs): string
{
    $dt = new DateTime($fecha);

    return $diasEs[$dt->format('D')] . ' ' . (int) $dt->format('j') . ' de ' . $mesesEs[(int) $dt->format('n')];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Comprobante de pago</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{--teal-900:#0B3D3C;--teal-700:#12524F;--teal-100:#E4EDE9;--teal-50:#F2F6F4;--amber-500:#F2A93B;--ink:#16241F;--muted:#5F6E68;--line:#E4E7E3;--card:#fff;--success:#2F8F5B;--success-bg:#E4F4EB;--pending:#B96F16;--pending-bg:#FCEFDA;--danger:#C1503D;--danger-bg:#FBEAE6}
  *{box-sizing:border-box}
  body{margin:0;font-family:'Inter',sans-serif;color:var(--ink);background:var(--teal-50)}
  h1,h2,h3{font-family:'Space Grotesk',sans-serif}
  .wrap{max-width:520px;margin:0 auto;padding:34px 20px 60px}
  .card{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:22px 24px;margin-bottom:16px}
  .check-circle{width:56px;height:56px;border-radius:50%;background:var(--success-bg);color:var(--success);display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
  .check-circle svg{width:28px;height:28px}
  .center{text-align:center}
  .pill{display:inline-block;font-size:11px;font-weight:700;padding:4px 11px;border-radius:20px}
  .pill.ok{background:var(--success-bg);color:var(--success)}
  .pill.warn{background:var(--pending-bg);color:var(--pending)}
  .pill.bad{background:var(--danger-bg);color:var(--danger)}
  .row{display:flex;justify-content:space-between;padding:8px 0;border-top:1px dashed var(--line);font-size:13px}
  .row:first-child{border-top:none}
  .row span:first-child{color:var(--muted)}
  .row span:last-child{font-weight:600}
  .btn{display:inline-flex;width:100%;align-items:center;justify-content:center;border:none;border-radius:8px;padding:12px;font:700 14px 'Inter',sans-serif;cursor:pointer;text-decoration:none;background:var(--teal-900);color:#fff}
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => '']) ?>

<div class="wrap">
  <div class="card center">
    <div class="check-circle">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>
    </div>
    <h1 style="font-size:18px;margin:0 0 4px;">Comprobante de pago</h1>
    <p style="color:var(--muted);font-size:13px;margin:0;">Reserva #<?= (int) $alquiler['id_alquiler'] ?> — <?= esc($alquiler['herramienta_nombre']) ?></p>
  </div>

  <div class="card">
    <div class="row"><span>Herramienta</span><span><?= esc($alquiler['herramienta_nombre']) ?></span></div>
    <div class="row"><span>Publicada por</span><span><?= esc($alquiler['propietario_nombre']) ?></span></div>
    <div class="row"><span>Fechas</span><span><?= esc(fechaLargaEs($alquiler['fecha_inicio'], $diasEs, $mesesEs)) ?> al <?= esc(fechaLargaEs($alquiler['fecha_fin'], $diasEs, $mesesEs)) ?></span></div>
    <div class="row"><span>Unidad</span><span><?= $alquiler['unidad_precio'] === 'hora' ? 'Por hora' : 'Por día' ?></span></div>
    <div class="row"><span>Total pagado</span><span>$<?= number_format((float) $alquiler['precio_total'], 0, ',', '.') ?></span></div>
  </div>

  <?php if ($pago) : ?>
    <?php $colorEstado = ['pagado' => 'ok', 'pendiente' => 'warn', 'rechazado' => 'bad'][$pago['estado']] ?? 'warn'; ?>
    <div class="card">
      <div class="row"><span>Estado del pago</span><span><span class="pill <?= $colorEstado ?>"><?= esc(ucfirst($pago['estado'])) ?></span></span></div>
      <div class="row"><span>Método</span><span>Simulado</span></div>
      <div class="row"><span>Fecha de la operación</span><span><?= esc((new DateTime($pago['fecha_operacion']))->format('d/m/Y H:i')) ?></span></div>
    </div>
  <?php endif ?>

  <a href="<?= site_url('mis-reservas') ?>" class="btn">Volver a mis reservas</a>
</div>

</body>
</html>
