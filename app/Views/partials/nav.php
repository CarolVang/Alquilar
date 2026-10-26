<?php
/**
 * Nav compartida (base: feature/frontend-luana), adaptada a las rutas reales
 * del proyecto y a la sesion real (isLoggedIn / rol / nombre) en vez de los
 * datos simulados por localStorage que tenia la version original.
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
  .ar-nav{ display:flex; align-items:center; gap:18px; padding:14px 28px; background:#0B3D3C; color:#fff; font-family:'Inter',sans-serif; }
  .ar-nav .logo{ font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; margin-right:10px; }
  .ar-nav .logo span{ color:#F2A93B; }
  .ar-nav a{ color:#CFE0DB; text-decoration:none; font-size:13.5px; font-weight:600; }
  .ar-nav a.on{ color:#fff; }
  .ar-nav .spacer{ flex:1; }
  .ar-nav .user{ font-size:13px; color:#CFE0DB; margin-right:6px; }
  .ar-nav .btn-amber{ background:#F2A93B; color:#0B3D3C; border:none; padding:8px 14px; border-radius:7px; font-size:12.5px; font-weight:700; text-decoration:none; }
  .ar-nav .btn-ghost{ color:#fff; border:1px solid rgba(255,255,255,.35); padding:7px 13px; border-radius:7px; font-size:12.5px; font-weight:700; }
</style>
<nav class="ar-nav">
  <a href="<?= site_url('/') ?>" class="logo">Alquil<span>-Ar</span></a>
  <?php foreach ($links as [$slug, $texto]) : ?>
    <a href="<?= site_url($slug) ?>" class="<?= $activo === $slug ? 'on' : '' ?>"><?= esc($texto) ?></a>
  <?php endforeach ?>
  <div class="spacer"></div>
  <?php if ($logueado) : ?>
    <span class="user">Hola, <?= esc(session('nombre')) ?></span>
    <a href="<?= site_url('logout') ?>" class="btn-ghost">Salir</a>
  <?php else : ?>
    <a href="<?= site_url('login') ?>" class="btn-ghost">Iniciar sesión</a>
    <a href="<?= site_url('registro') ?>" class="btn-amber">Crear cuenta</a>
  <?php endif ?>
</nav>
