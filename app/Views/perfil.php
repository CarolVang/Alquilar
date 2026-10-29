<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Mi perfil</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{--teal-900:#0B3D3C;--teal-700:#12524F;--teal-100:#E4EDE9;--teal-50:#F2F6F4;--amber-500:#F2A93B;--ink:#16241F;--muted:#5F6E68;--line:#E4E7E3;--card:#fff;--danger:#C1503D}
  *{box-sizing:border-box}
  body{margin:0;font-family:'Inter',sans-serif;color:var(--ink);background:var(--teal-50)}
  h1,h2,h3{font-family:'Space Grotesk',sans-serif}
  .wrap{max-width:560px;margin:0 auto;padding:30px 20px 60px}
  .head-card{display:flex;align-items:center;gap:16px;background:var(--card);border:1px solid var(--line);border-radius:16px;padding:20px 22px;margin-bottom:18px}
  .avatar{width:58px;height:58px;border-radius:50%;background:var(--amber-500);color:var(--teal-900);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;flex-shrink:0}
  .head-card h1{font-size:18px;margin:0 0 3px}
  .head-card p{margin:0;font-size:12.5px;color:var(--muted)}
  .link-card{display:flex;align-items:center;justify-content:space-between;background:var(--card);border:1px solid var(--line);border-radius:14px;padding:16px 18px;text-decoration:none;color:var(--teal-900);font-weight:700;font-size:13.5px;margin-bottom:10px}
  .link-card:hover{background:var(--teal-100)}
  .link-card .sub{display:block;margin-top:2px;font-size:11.5px;color:var(--muted);font-weight:500}
  .logout-btn{width:100%;margin-top:14px;background:#fff;border:1.5px solid var(--line);color:var(--danger);font-weight:700;font-size:13.5px;padding:12px;border-radius:10px;cursor:pointer;font-family:inherit}
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => 'perfil']) ?>

<div class="wrap">
  <div class="head-card">
    <div class="avatar"><?= esc(mb_strtoupper(mb_substr(trim((string) $usuario['nombre']), 0, 2))) ?></div>
    <div>
      <h1>
        <?= esc($usuario['nombre']) ?>
        <?php if ($usuario['rol'] === 'comerciante') : ?>
          <span title="Empresa" style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;background:var(--teal-600);color:#fff;border-radius:50%;font-size:10px;vertical-align:middle;">✓</span>
        <?php endif ?>
      </h1>
      <p><?= esc($usuario['email']) ?></p>
      <?php if ($usuario['rol'] === 'comerciante' && ! empty($usuario['razon_social'])) : ?>
        <p><?= esc($usuario['razon_social']) ?></p>
      <?php endif ?>
    </div>
  </div>

  <a href="<?= site_url('mis-herramientas') ?>" class="link-card">
    <span>Mis herramientas<span class="sub">Lo que publicaste para alquilar</span></span>
    <span aria-hidden="true">→</span>
  </a>

  <a href="<?= site_url('mis-alquileres') ?>" class="link-card">
    <span>Mis alquileres<span class="sub"><?= $por_retirar > 0 ? "Tenés {$por_retirar} por retirar" : 'Quién alquiló tus herramientas' ?></span></span>
    <span aria-hidden="true">→</span>
  </a>

  <a href="<?= site_url('mis-reservas') ?>" class="link-card">
    <span>Mis reservas<span class="sub">Lo que alquilaste vos</span></span>
    <span aria-hidden="true">→</span>
  </a>

  <form action="<?= site_url('logout') ?>" method="get">
    <button type="submit" class="logout-btn">Cerrar sesión</button>
  </form>
</div>

</body>
</html>
