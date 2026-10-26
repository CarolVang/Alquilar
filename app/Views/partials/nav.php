<?php
/**
 * Semana 11: menú de navegación único para todas las pantallas internas.
 * Uso: <?= view('partials/nav', ['activo' => 'catalogo']) ?>
 * $activo: catalogo | mis-reservas | mis-herramientas | mis-alquileres | '' (ninguno marcado)
 *
 * Los links dependen del rol guardado en la sesión:
 *  - cliente: Catálogo y Mis reservas.
 *  - retador: Catálogo, Mis herramientas, Mis alquileres y "+ Publicar".
 *  - sin rol conocido: se muestran todos.
 */
$activo   = $activo ?? '';
$logueado = (bool) session('isLoggedIn');
$rol      = (string) session('rol');

$links = [['catalogo', 'Catálogo']];
if ($logueado && $rol !== 'retador') {
    $links[] = ['mis-reservas', 'Mis reservas'];
}
if ($logueado && $rol !== 'cliente') {
    $links[] = ['mis-herramientas', 'Mis herramientas'];
    $links[] = ['mis-alquileres', 'Mis alquileres'];
}
?>
<style>
  .app-nav{ display:flex; align-items:center; gap:22px; padding:14px 40px; background:#0B3D3C; color:#fff; font-family:'Inter',sans-serif; }
  .app-nav .app-logo{ font-family:'Space Grotesk',sans-serif; font-size:20px; font-weight:700; color:#fff; text-decoration:none; white-space:nowrap; }
  .app-nav .app-logo span{ color:#F2A93B; }
  .app-nav .app-links{ display:flex; gap:6px; flex:1; min-width:0; }
  .app-nav .app-links a{ color:#CFE0DB; text-decoration:none; font-size:13.5px; padding:7px 12px; border-radius:7px; white-space:nowrap; }
  .app-nav .app-links a:hover{ color:#fff; }
  .app-nav .app-links a.on{ color:#fff; background:rgba(255,255,255,.12); font-weight:600; }
  .app-nav .app-user{ display:flex; align-items:center; gap:14px; font-size:13px; }
  .app-nav .app-user a{ color:#CFE0DB; text-decoration:none; white-space:nowrap; }
  .app-nav .app-user a:hover{ color:#fff; }
  .app-nav .app-user a.app-amber{ background:#F2A93B; color:#0B3D3C; font-weight:700; font-size:12.5px; padding:8px 14px; border-radius:7px; }
  .app-nav .app-avatar{ width:32px; height:32px; border-radius:50%; background:#F2A93B; color:#0B3D3C; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12.5px; flex-shrink:0; }
  @media (max-width:640px){
    .app-nav{ padding:12px 16px; gap:10px; flex-wrap:wrap; }
    .app-nav .app-user{ margin-left:auto; gap:10px; }
    .app-nav .app-links{ order:3; flex-basis:100%; overflow-x:auto; margin:0 -6px; }
    .app-nav .app-links a{ padding:6px 10px; font-size:13px; }
  }
</style>
<nav class="app-nav">
  <a class="app-logo" href="<?= site_url('catalogo') ?>">Alquil<span>-Ar</span></a>
  <div class="app-links">
    <?php foreach ($links as [$ruta, $texto]) : ?>
      <a href="<?= site_url($ruta) ?>"<?= $activo === $ruta ? ' class="on"' : '' ?>><?= $texto ?></a>
    <?php endforeach ?>
  </div>
  <div class="app-user">
    <?php if ($logueado) : ?>
      <?php if ($rol !== 'cliente') : ?>
        <a class="app-amber" href="<?= site_url('publicar-herramienta') ?>">+ Publicar</a>
      <?php endif ?>
      <a href="<?= site_url('logout') ?>">Salir</a>
      <div class="app-avatar" title="<?= esc(session('nombre')) ?>"><?= esc(mb_strtoupper(mb_substr((string) session('nombre'), 0, 1))) ?></div>
    <?php else : ?>
      <a class="app-amber" href="<?= site_url('login') ?>">Ingresar</a>
    <?php endif ?>
  </div>
</nav>
