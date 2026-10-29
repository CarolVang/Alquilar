<?php
/**
 * Base visual: feature/frontend-luana (Semana 9/10). Igual que mis_reservas.php:
 * se cambia el motor simulado por localStorage por los alquileres reales que
 * manda el controller (AlquilerModel::getAlquileresPropietario).
 */
$diasEs  = ['Mon' => 'lun', 'Tue' => 'mar', 'Wed' => 'mié', 'Thu' => 'jue', 'Fri' => 'vie', 'Sat' => 'sáb', 'Sun' => 'dom'];
$mesesEs = [1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic'];

function fechaCortaEs2(string $fecha, array $diasEs, array $mesesEs): string
{
    $dt = new DateTime($fecha);

    return $diasEs[$dt->format('D')] . ' ' . (int) $dt->format('j') . ' ' . $mesesEs[(int) $dt->format('n')] . ' ' . $dt->format('H:i');
}

$grupos = ['todos' => [], 'retirar' => [], 'uso' => [], 'fin' => [], 'canc' => []];
foreach ($alquileres as $a) {
    $grupos['todos'][] = $a;
    if ($a['estado'] === 'confirmada') {
        $grupos['retirar'][] = $a;
    } elseif ($a['estado'] === 'retirada') {
        $grupos['uso'][] = $a;
    } elseif ($a['estado'] === 'devuelta') {
        $grupos['fin'][] = $a;
    } elseif ($a['estado'] === 'cancelada') {
        $grupos['canc'][] = $a;
    }
}

$pills = [
    'confirmada' => ['Confirmada', 'ok'],
    'retirada'   => ['En uso', 'warn'],
    'devuelta'   => ['Devuelta', 'gray'],
    'cancelada'  => ['Cancelada', 'bad'],
];
$siguiente = ['confirmada' => ['retirada', 'Marcar como retirada'], 'retirada' => ['devuelta', 'Marcar como devuelta']];

$activos    = count($grupos['retirar']) + count($grupos['uso']);
$porRetirar = count($grupos['retirar']);
$ingresos   = 0.0;
foreach ($alquileres as $a) {
    if ($a['estado'] !== 'cancelada') {
        $ingresos += (float) $a['precio_total'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Mis alquileres</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--teal-900:#0B3D3C;--teal-700:#12524F;--teal-600:#1C6B63;--teal-100:#E4EDE9;--teal-50:#F2F6F4;--amber-500:#F2A93B;--ink:#16241F;--muted:#5F6E68;--line:#E4E7E3;--card:#fff;--danger:#C1503D;--danger-bg:#FBEAE6;--success:#2F8F5B;--success-bg:#E4F4EB;--pending:#B96F16;--pending-bg:#FCEFDA}
*{box-sizing:border-box}
body{margin:0;font-family:'Inter',sans-serif;color:var(--ink);background:var(--teal-50)}
h1,h2,h3{font-family:'Space Grotesk',sans-serif}
.wrap{max-width:1000px;margin:0 auto;padding:28px 24px 60px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;border-radius:8px;padding:12px 20px;font:700 14px 'Inter',sans-serif;cursor:pointer;text-decoration:none}
.btn-primary{background:var(--amber-500);color:var(--teal-900)}
.page-title{font-size:22px;margin:0 0 4px}
.page-sub{color:var(--muted);font-size:13px;margin:0 0 20px}
.state{text-align:center;padding:48px 20px;border-radius:14px}
.state svg{width:42px;height:42px;margin-bottom:12px}
.state h2{font-size:17px;margin:0 0 6px}
.state p{font-size:13px;margin:0 0 18px}
.state.empty{background:#fff;border:1px dashed var(--line)}.state.empty svg{color:var(--teal-700)}.state.empty p{color:var(--muted)}
.pill{display:inline-block;font-size:10.5px;font-weight:700;padding:4px 11px;border-radius:20px;white-space:nowrap}
.pill.ok{background:var(--success-bg);color:var(--success)}.pill.warn{background:var(--pending-bg);color:var(--pending)}
.pill.gray{background:var(--teal-50);color:var(--muted);border:1px solid var(--line)}.pill.bad{background:var(--danger-bg);color:var(--danger)}
.tabs{display:flex;gap:4px;border-bottom:1px solid var(--line);margin-bottom:16px;overflow-x:auto}
.tab{padding:10px 16px;font-size:13px;font-weight:600;color:var(--muted);border:none;background:none;border-bottom:2px solid transparent;cursor:pointer;white-space:nowrap;font-family:inherit}
.tab.on{color:var(--teal-900);border-color:var(--teal-900)}
.tab small{background:var(--teal-100);border-radius:10px;padding:1px 7px;margin-left:5px;font-size:10.5px}
.rows{display:flex;flex-direction:column;gap:12px}
.rows.hidden{display:none}
.row{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px}
.row .ic{width:46px;height:46px;border-radius:10px;background:var(--teal-100);display:flex;align-items:center;justify-content:center;color:var(--teal-700);flex-shrink:0;overflow:hidden}
.row .ic img{width:100%;height:100%;object-fit:cover}
.row .ic svg{width:22px;height:22px}
.row .main{flex:1;min-width:0}
.row h3{margin:0 0 3px;font-size:14px}
.row .meta{font-size:12px;color:var(--muted);line-height:1.5}
.row .side{text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:8px}
.row .total{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:15px;color:var(--teal-900)}
.mini{font-size:11.5px;font-weight:700;border-radius:6px;padding:7px 12px;border:none;cursor:pointer;font-family:inherit}
.mini.dark{background:var(--teal-900);color:#fff}
.priv{font-size:11px;color:var(--muted);font-style:italic}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.stat{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 16px}
.stat .l{font-size:12px;color:var(--muted);margin-bottom:4px}
.stat .v{font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:var(--teal-900)}
.toast-ar{position:fixed;left:50%;bottom:24px;transform:translateX(-50%);background:var(--teal-900);color:#fff;padding:11px 18px;border-radius:9px;font-size:13px;opacity:0;pointer-events:none;transition:opacity .2s;z-index:5;max-width:90vw;text-align:center}
.toast-ar.on{opacity:1}.toast-ar.bad{background:var(--danger)}
@media (max-width:640px){.wrap{padding:20px 14px 48px}.stats{grid-template-columns:1fr}.row{flex-wrap:wrap}.row .side{flex-direction:row;align-items:center;justify-content:space-between;width:100%;text-align:left}}
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => 'mis-alquileres']) ?>

<?php if (! session('isLoggedIn')) : ?>
<div class="wrap">
  <div class="state empty">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
    <h2>Iniciá sesión para ver tus alquileres</h2>
    <p>Los datos de tus clientes son privados: solo los ves vos con tu cuenta.</p>
    <a href="<?= site_url('login') ?>" class="btn btn-primary">Iniciar sesión</a>
  </div>
</div>
<?php else : ?>
<div class="wrap">
  <h1 class="page-title">Mis alquileres</h1>
  <p class="page-sub">Quién alquiló tus herramientas, cuándo y en qué estado está cada alquiler.</p>

  <div class="stats">
    <div class="stat"><div class="l">Alquileres activos</div><div class="v"><?= $activos ?></div></div>
    <div class="stat"><div class="l">Por retirar</div><div class="v"><?= $porRetirar ?></div></div>
    <div class="stat"><div class="l">Ingresos (sin tarifa de servicio)</div><div class="v">$<?= number_format($ingresos, 0, ',', '.') ?></div></div>
  </div>

  <div class="tabs">
    <button class="tab on" type="button" data-tab="todos">Todos<small><?= count($grupos['todos']) ?></small></button>
    <button class="tab" type="button" data-tab="retirar">Por retirar<small><?= count($grupos['retirar']) ?></small></button>
    <button class="tab" type="button" data-tab="uso">En uso<small><?= count($grupos['uso']) ?></small></button>
    <button class="tab" type="button" data-tab="fin">Finalizados<small><?= count($grupos['fin']) ?></small></button>
    <button class="tab" type="button" data-tab="canc">Cancelados<small><?= count($grupos['canc']) ?></small></button>
  </div>

  <?php
  $titulosVacio = [
      'todos'   => ['Todavía no te alquilaron nada', 'Cuando alguien alquile una de tus herramientas, lo vas a ver acá.'],
      'retirar' => ['No tenés alquileres por retirar', 'Los alquileres confirmados que todavía no se retiraron aparecen acá.'],
      'uso'     => ['No tenés herramientas en uso', 'Las herramientas que ya retiraron y todavía no devolvieron aparecen acá.'],
      'fin'     => ['Todavía no hay alquileres finalizados', 'Cuando te devuelvan una herramienta, el alquiler queda acá.'],
      'canc'    => ['No hay alquileres cancelados', 'Si un cliente cancela una reserva, la vas a ver acá.'],
  ];
  ?>
  <?php foreach ($grupos as $clave => $items) : ?>
    <div class="rows<?= $clave === 'todos' ? '' : ' hidden' ?>" data-grupo="<?= $clave ?>">
      <?php if (empty($items)) : ?>
        <div class="state empty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg>
          <h2><?= esc($titulosVacio[$clave][0]) ?></h2>
          <p><?= esc($titulosVacio[$clave][1]) ?></p>
          <?php if ($clave === 'todos') : ?>
            <a href="<?= site_url('publicar-herramienta') ?>" class="btn btn-primary">Publicar una herramienta</a>
          <?php endif ?>
        </div>
      <?php else : ?>
        <?php foreach ($items as $a) :
            [$texto, $color] = $pills[$a['estado']] ?? [$a['estado'], 'gray'];
            $activo  = $a['estado'] === 'confirmada' || $a['estado'] === 'retirada';
            $accion  = $siguiente[$a['estado']] ?? null;
        ?>
          <div class="row">
            <div class="ic">
              <?php if (! empty($a['foto_url'])) : ?>
                <img src="<?= esc($a['foto_url']) ?>" alt="">
              <?php else : ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="12" height="6" rx="1"/><path d="M16 12h4v2h-4"/></svg>
              <?php endif ?>
            </div>
            <div class="main">
              <h3><?= esc($a['herramienta_nombre']) ?></h3>
              <div class="meta">
                Cliente: <b><?= esc($a['cliente_nombre']) ?></b><?php if (($a['cliente_rol'] ?? '') === 'comerciante') : ?> <span title="Empresa" style="display:inline-flex;align-items:center;justify-content:center;width:13px;height:13px;background:var(--teal-600);color:#fff;border-radius:50%;font-size:8.5px;">✓</span><?php endif ?> · N° <b>#<?= (int) $a['id_alquiler'] ?></b><br>
                <?= $activo ? 'Tel. ' . esc($a['cliente_telefono'] ?? '—') : '<span class="priv">Contacto disponible solo mientras el alquiler está activo</span>' ?><br>
                <?= esc(fechaCortaEs2($a['fecha_inicio'], $diasEs, $mesesEs)) ?> al <?= esc(fechaCortaEs2($a['fecha_fin'], $diasEs, $mesesEs)) ?>
                · por <?= $a['unidad_precio'] === 'hora' ? 'hora' : 'día' ?>
              </div>
            </div>
            <div class="side">
              <span class="pill <?= $color ?>"><?= esc($texto) ?></span>
              <div class="total">$<?= number_format((float) $a['precio_total'], 0, ',', '.') ?></div>
              <div class="meta" style="font-size:10.5px">tu ingreso (sin tarifa de servicio)</div>
              <a class="mini dark" href="<?= site_url('mensajes/' . (int) $a['id_alquiler']) ?>">Chat</a>
              <?php if ($accion) : ?>
                <button class="mini dark" type="button" data-id="<?= (int) $a['id_alquiler'] ?>" data-estado="<?= $accion[0] ?>"><?= esc($accion[1]) ?></button>
              <?php endif ?>
            </div>
          </div>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</div>
<div class="toast-ar" id="toast"></div>

<script>
const CSRF_TOKEN_NAME = <?= json_encode(csrf_token()) ?>;
const CSRF_HASH = <?= json_encode(csrf_hash()) ?>;

document.querySelectorAll('.tab').forEach(function (btn) {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.tab').forEach(function (b) { b.classList.remove('on'); });
    btn.classList.add('on');
    var grupo = btn.getAttribute('data-tab');
    document.querySelectorAll('.rows').forEach(function (r) {
      r.classList.toggle('hidden', r.getAttribute('data-grupo') !== grupo);
    });
  });
});

var toastT = null;
function aviso(msg, malo) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast-ar on' + (malo ? ' bad' : '');
  clearTimeout(toastT);
  toastT = setTimeout(function () { t.className = 'toast-ar'; }, 2800);
}

document.addEventListener('click', function (e) {
  var btn = e.target.closest('[data-id]');
  if (! btn) return;

  fetch('<?= site_url('alquileres/avanzar-estado') ?>/' + btn.getAttribute('data-id'), {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
    body: new URLSearchParams({ estado: btn.getAttribute('data-estado'), [CSRF_TOKEN_NAME]: CSRF_HASH }),
  })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.exito) {
        aviso('Alquiler actualizado.');
        location.reload();
      } else {
        aviso(data.mensaje || 'No pudimos actualizar el alquiler.', true);
      }
    })
    .catch(function () { aviso('Hubo un problema de conexión. Intentá de nuevo.', true); });
});
</script>
<?php endif ?>

</body>
</html>
