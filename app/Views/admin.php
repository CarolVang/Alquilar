<?php
$r = $resumen;
$activos = ($r['alquileres_estado']['confirmada'] ?? 0) + ($r['alquileres_estado']['retirada'] ?? 0);
$totalAlquileres = array_sum($r['alquileres_estado']);

$etiquetaHerramienta = ['disponible' => ['Disponible', 'ok'], 'no_disponible' => ['Pausada', 'gray'], 'bloqueada' => ['Bloqueada', 'bad'], 'alquilada' => ['Alquilada', 'warn']];
$etiquetaAlquiler    = ['confirmada' => ['Confirmada', 'ok'], 'retirada' => ['En uso', 'warn'], 'devuelta' => ['Devuelta', 'gray'], 'cancelada' => ['Cancelada', 'bad']];
$etiquetaRol         = ['admin' => 'Admin', 'comerciante' => 'Empresa', 'particular' => 'Particular'];

function fechaAdmin(string $fecha): string
{
    return (new DateTime($fecha))->format('d/m/Y H:i');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Panel de administración</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--teal-900:#0B3D3C;--teal-700:#12524F;--teal-600:#1C6B63;--teal-100:#E4EDE9;--teal-50:#F2F6F4;--amber-500:#F2A93B;--ink:#16241F;--muted:#5F6E68;--line:#E4E7E3;--card:#fff;--danger:#C1503D;--danger-bg:#FBEAE6;--success:#2F8F5B;--success-bg:#E4F4EB;--pending:#B96F16;--pending-bg:#FCEFDA}
*{box-sizing:border-box}
body{margin:0;font-family:'Inter',sans-serif;color:var(--ink);background:var(--teal-50)}
h1,h2,h3{font-family:'Space Grotesk',sans-serif}
.wrap{max-width:1100px;margin:0 auto;padding:28px 24px 60px}
.page-title{font-size:22px;margin:0 0 4px}
.page-sub{color:var(--muted);font-size:13px;margin:0 0 20px}
.aviso{border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:16px}
.aviso.ok{background:var(--success-bg);color:var(--success)}
.aviso.bad{background:var(--danger-bg);color:var(--danger)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px}
.stat{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 16px}
.stat .l{font-size:12px;color:var(--muted);margin-bottom:4px}
.stat .v{font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:var(--teal-900)}
.stat .d{font-size:11.5px;color:var(--muted);margin-top:3px}
.stat.alerta{border-color:var(--amber-500);background:#FFFBF2}
.tabs{display:flex;gap:4px;border-bottom:1px solid var(--line);margin-bottom:16px;overflow-x:auto}
.tab{padding:10px 16px;font-size:13px;font-weight:600;color:var(--muted);border:none;background:none;border-bottom:2px solid transparent;cursor:pointer;white-space:nowrap;font-family:inherit}
.tab.on{color:var(--teal-900);border-color:var(--teal-900)}
.tab small{background:var(--teal-100);border-radius:10px;padding:1px 7px;margin-left:5px;font-size:10.5px}
.panel{display:none}.panel.on{display:block}
.tabla-wrap{background:#fff;border:1px solid var(--line);border-radius:12px;overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:12.5px}
th{text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);padding:11px 12px;border-bottom:1px solid var(--line);white-space:nowrap}
td{padding:10px 12px;border-bottom:1px solid var(--line);vertical-align:middle}
tr:last-child td{border-bottom:none}
td .sub{display:block;font-size:11px;color:var(--muted)}
.pill{display:inline-block;font-size:10.5px;font-weight:700;padding:3px 10px;border-radius:20px;white-space:nowrap}
.pill.ok{background:var(--success-bg);color:var(--success)}.pill.warn{background:var(--pending-bg);color:var(--pending)}
.pill.gray{background:var(--teal-50);color:var(--muted);border:1px solid var(--line)}.pill.bad{background:var(--danger-bg);color:var(--danger)}
.acciones{display:flex;gap:6px;flex-wrap:wrap}
.acciones form{margin:0}
.mini{font-size:11.5px;font-weight:700;border-radius:6px;padding:6px 11px;border:1px solid var(--line);background:#fff;color:var(--teal-900);cursor:pointer;font-family:inherit;white-space:nowrap;text-decoration:none;display:inline-block}
.mini.peligro{color:var(--danger)}
.mini.primario{background:var(--teal-900);color:#fff;border-color:var(--teal-900)}
.vacio{padding:30px;text-align:center;color:var(--muted);font-size:13px}
@media (max-width:860px){.stats{grid-template-columns:repeat(2,1fr)}}
@media (max-width:640px){.wrap{padding:20px 14px 48px}}
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => 'admin']) ?>

<div class="wrap">
  <h1 class="page-title">Panel de administración</h1>
  <p class="page-sub">Controlá cuentas, empresas y publicaciones, y mirá todos los alquileres del sitio.</p>

  <?php if (session()->getFlashdata('mensaje')) : ?>
    <div class="aviso ok"><?= esc(session()->getFlashdata('mensaje')) ?></div>
  <?php endif ?>
  <?php if (session()->getFlashdata('error')) : ?>
    <div class="aviso bad"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif ?>

  <div class="stats">
    <div class="stat"><div class="l">Usuarios</div><div class="v"><?= (int) $r['usuarios'] ?></div><div class="d"><?= (int) $r['suspendidos'] ?> suspendido<?= (int) $r['suspendidos'] === 1 ? '' : 's' ?></div></div>
    <div class="stat<?= $r['empresas_pendientes'] > 0 ? ' alerta' : '' ?>"><div class="l">Empresas por verificar</div><div class="v"><?= (int) $r['empresas_pendientes'] ?></div><div class="d">esperan revisión</div></div>
    <div class="stat"><div class="l">Publicaciones</div><div class="v"><?= (int) $r['herramientas'] ?></div><div class="d"><?= (int) $r['disponibles'] ?> disponibles · <?= (int) $r['pausadas'] ?> pausadas · <?= (int) $r['bloqueadas'] ?> bloqueadas</div></div>
    <div class="stat"><div class="l">Alquileres</div><div class="v"><?= (int) $totalAlquileres ?></div><div class="d"><?= (int) $activos ?> activos · facturado $<?= number_format((float) $r['facturado'], 0, ',', '.') ?></div></div>
  </div>

  <div class="tabs">
    <button class="tab on" type="button" data-tab="usuarios">Usuarios<small><?= count($usuarios) ?></small></button>
    <button class="tab" type="button" data-tab="publicaciones">Publicaciones<small><?= count($herramientas) ?></small></button>
    <button class="tab" type="button" data-tab="alquileres">Alquileres<small><?= count($alquileres) ?></small></button>
  </div>

  <!-- USUARIOS -->
  <div class="panel on" id="panel-usuarios">
    <div class="tabla-wrap">
      <?php if (empty($usuarios)) : ?>
        <div class="vacio">No hay usuarios registrados.</div>
      <?php else : ?>
      <table>
        <tr><th>Usuario</th><th>Tipo</th><th>Empresa</th><th>Estado</th><th>Acciones</th></tr>
        <?php foreach ($usuarios as $u) :
            $esEmpresa  = $u['rol'] === 'comerciante';
            $suspendido = (int) $u['suspendido'] === 1;
            $verificado = (int) $u['verificado'] === 1;
        ?>
          <tr>
            <td><b><?= esc($u['nombre']) ?></b><span class="sub"><?= esc($u['email']) ?> · DNI <?= esc($u['dni']) ?></span></td>
            <td><?= esc($etiquetaRol[$u['rol']] ?? 'Particular') ?></td>
            <td>
              <?php if ($esEmpresa) : ?>
                <?= esc($u['razon_social'] ?? '') ?><span class="sub">CUIT <?= esc($u['cuit'] ?? '—') ?></span>
                <span class="pill <?= $verificado ? 'ok' : 'warn' ?>"><?= $verificado ? 'Verificada' : 'Pendiente' ?></span>
              <?php else : ?>
                <span class="sub">—</span>
              <?php endif ?>
            </td>
            <td><span class="pill <?= $suspendido ? 'bad' : 'ok' ?>"><?= $suspendido ? 'Suspendida' : 'Activa' ?></span></td>
            <td>
              <div class="acciones">
                <?php if ($esEmpresa) : ?>
                  <form method="post" action="<?= site_url('admin/usuarios/' . (int) $u['id_usuario'] . '/verificar') ?>">
                    <?= csrf_field() ?>
                    <button class="mini<?= $verificado ? '' : ' primario' ?>" type="submit"><?= $verificado ? 'Quitar verificación' : 'Verificar empresa' ?></button>
                  </form>
                <?php endif ?>
                <?php if ($u['rol'] !== 'admin') : ?>
                  <form method="post" action="<?= site_url('admin/usuarios/' . (int) $u['id_usuario'] . '/suspender') ?>">
                    <?= csrf_field() ?>
                    <button class="mini<?= $suspendido ? '' : ' peligro' ?>" type="submit"><?= $suspendido ? 'Reactivar' : 'Suspender' ?></button>
                  </form>
                <?php endif ?>
              </div>
            </td>
          </tr>
        <?php endforeach ?>
      </table>
      <?php endif ?>
    </div>
  </div>

  <!-- PUBLICACIONES -->
  <div class="panel" id="panel-publicaciones">
    <div class="tabla-wrap">
      <?php if (empty($herramientas)) : ?>
        <div class="vacio">No hay publicaciones.</div>
      <?php else : ?>
      <table>
        <tr><th>Publicación</th><th>Dueño</th><th>Precio por día</th><th>Estado</th><th>Acciones</th></tr>
        <?php foreach ($herramientas as $h) :
            [$texto, $color] = $etiquetaHerramienta[$h['estado']] ?? [$h['estado'], 'gray'];
            $bloqueada = $h['estado'] === 'bloqueada';
        ?>
          <tr>
            <td><b><?= esc($h['nombre']) ?></b><span class="sub"><?= esc($h['categoria'] ?? 'Sin categoría') ?> · N° <?= (int) $h['id_herramienta'] ?></span></td>
            <td><?= esc($h['duenio']) ?></td>
            <td>$<?= number_format((float) $h['precio_dia'], 0, ',', '.') ?></td>
            <td><span class="pill <?= $color ?>"><?= esc($texto) ?></span></td>
            <td>
              <div class="acciones">
                <a class="mini" href="<?= site_url('detalle/' . (int) $h['id_herramienta']) ?>">Ver</a>
                <form method="post" action="<?= site_url('admin/herramientas/' . (int) $h['id_herramienta'] . '/bloquear') ?>">
                  <?= csrf_field() ?>
                  <button class="mini<?= $bloqueada ? '' : ' peligro' ?>" type="submit"><?= $bloqueada ? 'Desbloquear' : 'Bloquear' ?></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach ?>
      </table>
      <?php endif ?>
    </div>
  </div>

  <!-- ALQUILERES -->
  <div class="panel" id="panel-alquileres">
    <div class="tabla-wrap">
      <?php if (empty($alquileres)) : ?>
        <div class="vacio">Todavía no hay alquileres.</div>
      <?php else : ?>
      <table>
        <tr><th>N°</th><th>Herramienta</th><th>Cliente → Dueño</th><th>Retiro / devolución</th><th>Total</th><th>Estado</th></tr>
        <?php foreach ($alquileres as $a) :
            [$texto, $color] = $etiquetaAlquiler[$a['estado']] ?? [$a['estado'], 'gray'];
        ?>
          <tr>
            <td>#<?= (int) $a['id_alquiler'] ?></td>
            <td><?= esc($a['herramienta']) ?><span class="sub">por <?= $a['unidad_precio'] === 'hora' ? 'hora' : 'día' ?></span></td>
            <td><?= esc($a['cliente']) ?> → <?= esc($a['duenio']) ?></td>
            <td><?= esc(fechaAdmin($a['fecha_inicio'])) ?><span class="sub"><?= esc(fechaAdmin($a['fecha_fin'])) ?></span></td>
            <td>$<?= number_format((float) $a['precio_total'], 0, ',', '.') ?></td>
            <td><span class="pill <?= $color ?>"><?= esc($texto) ?></span></td>
          </tr>
        <?php endforeach ?>
      </table>
      <?php endif ?>
    </div>
  </div>
</div>

<script>
function abrirPestana(nombre) {
  document.querySelectorAll('.tab').forEach(function (b) { b.classList.toggle('on', b.dataset.tab === nombre); });
  document.querySelectorAll('.panel').forEach(function (p) { p.classList.toggle('on', p.id === 'panel-' + nombre); });
}
document.querySelectorAll('.tab').forEach(function (b) {
  b.addEventListener('click', function () { abrirPestana(b.dataset.tab); history.replaceState(null, '', '#' + b.dataset.tab); });
});
// Despues de una accion, el servidor redirige a /admin#publicaciones (por ejemplo):
// se vuelve a abrir la misma pestaña en la que estaba el admin.
// "hashchange" cubre el caso en que solo cambia el # (el navegador no recarga).
function abrirDesdeUrl() {
  var desdeUrl = location.hash.replace('#', '');
  if (['usuarios', 'publicaciones', 'alquileres'].indexOf(desdeUrl) !== -1) { abrirPestana(desdeUrl); }
}
abrirDesdeUrl();
window.addEventListener('hashchange', abrirDesdeUrl);
</script>

</body>
</html>
