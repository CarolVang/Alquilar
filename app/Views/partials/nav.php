<?php
/**
 * Nav compartida. Navegar el catalogo es publico; "Publicar" y "Mis reservas"
 * quedan siempre visibles pero sus controllers redirigen a /login si no hay
 * sesion (no hay nada que ocultar, la accion es la que esta protegida).
 * No se filtra por rol: ni Herramientas::crear() ni Reserva::confirmar()
 * distinguen cliente/retador, asi que mostrar u ocultar links segun rol
 * era una distincion que la app nunca aplicaba de verdad.
 */
$activo   = $activo ?? '';
$logueado = (bool) session('isLoggedIn');

function navIniciales(string $nombre): string
{
    return mb_strtoupper(mb_substr(trim($nombre), 0, 2));
}
?>
<style>
  .ar-nav{ display:flex; align-items:center; gap:22px; padding:0 28px; height:60px; background:#0B3D3C; color:#fff; font-family:'Inter',sans-serif; }
  .ar-nav .logo{ font-family:'Space Grotesk',sans-serif; font-size:19px; font-weight:700; text-decoration:none; color:#fff; }
  .ar-nav .logo span{ color:#F2A93B; }
  .ar-nav .links{ display:flex; gap:20px; flex:1; }
  .ar-nav a.link{ color:#CFE0DB; text-decoration:none; font-size:13.5px; font-weight:600; display:flex; align-items:center; gap:6px; }
  .ar-nav a.link.on{ color:#fff; }
  .ar-nav a.link:hover{ color:#fff; }
  .ar-nav .perfil-chip{ display:flex; align-items:center; gap:8px; color:#fff; text-decoration:none; font-size:13px; font-weight:600; }
  .ar-nav .avatar{ width:30px; height:30px; border-radius:50%; background:#F2A93B; color:#0B3D3C; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; flex-shrink:0; }
  .ar-nav .avatar.anon{ background:rgba(255,255,255,.15); color:#fff; }

  @media (max-width: 640px){
    .ar-nav{ gap:10px; padding:0 14px; }
    .ar-nav .logo{ font-size:16px; }
    .ar-nav .links{ gap:12px; }
    .ar-nav a.link{ font-size:12px; }
    /* El texto ("Hola, Nombre" / "Ingresar") es un nodo de texto suelto en el
       <a>, no tiene su propio elemento: achicar el font-size del padre a 0 lo
       vuelve invisible sin tocar el avatar (que define su propio font-size). */
    .ar-nav .perfil-chip{ font-size:0; gap:0; }
  }
  @media (max-width: 420px){
    .ar-nav .links a.link:first-child{ display:none; }
  }
</style>
<nav class="ar-nav">
  <a href="<?= site_url('/') ?>" class="logo">Alquil<span>-Ar</span></a>
  <div class="links">
    <a href="<?= site_url('/') ?>" class="link<?= $activo === 'catalogo' ? ' on' : '' ?>">Catálogo</a>
    <a href="<?= site_url('publicar-herramienta') ?>" class="link<?= $activo === 'publicar' ? ' on' : '' ?>">Publicar</a>
    <a href="<?= site_url('mis-reservas') ?>" class="link<?= $activo === 'mis-reservas' ? ' on' : '' ?>">Mis reservas</a>
  </div>
  <a href="<?= $logueado ? site_url('perfil') : site_url('login') ?>" class="perfil-chip">
    <?php if ($logueado) : ?>
      <span class="avatar"><?= esc(navIniciales((string) session('nombre'))) ?></span>
      <?= esc(session('nombre')) ?>
    <?php else : ?>
      <span class="avatar anon">?</span>
      Ingresar
    <?php endif ?>
  </a>
</nav>
