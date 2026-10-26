<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Mis herramientas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --teal-900:#0B3D3C; --teal-700:#12524F; --teal-600:#1C6B63; --teal-100:#E4EDE9; --teal-50:#F2F6F4;
    --amber-500:#F2A93B; --ink:#16241F; --muted:#5F6E68; --line:#E4E7E3;
    --danger:#C1503D; --danger-bg:#FBEAE6; --success:#2F8F5B; --success-bg:#E4F4EB;
  }
  *{box-sizing:border-box;}
  body{ margin:0; font-family:'Inter',sans-serif; color:var(--ink); background:var(--teal-50); }
  h1,h2,h3{ font-family:'Space Grotesk',sans-serif; }

  .topbar{ display:flex; align-items:center; justify-content:space-between; padding:16px 24px; background:var(--teal-900); color:#fff; }
  .logo{ font-size:18px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .btn-amber{ background:var(--amber-500); color:var(--teal-900); border:none; padding:9px 16px; border-radius:7px; font-size:12.5px; font-weight:700; cursor:pointer; text-decoration:none; }
  .logo a{ color:inherit; text-decoration:none; }
  .top-links{ display:flex; gap:6px; flex:1; margin-left:22px; }
  .top-links a{ color:#CFE0DB; text-decoration:none; font-size:13.5px; padding:7px 12px; border-radius:7px; }
  .top-links a.on{ color:#fff; background:rgba(255,255,255,.12); font-weight:600; }
  .top-right{ display:flex; align-items:center; gap:14px; }
  .top-right .salir{ color:#CFE0DB; text-decoration:none; font-size:13px; }
  .soon-note{ display:none; font-size:12px; color:var(--muted); background:#fff; border:1px solid var(--line); border-radius:8px; padding:10px 14px; margin-bottom:16px; }
  .soon-note.on{ display:block; }

  .wrap{ max-width:920px; margin:0 auto; padding:28px 20px 60px; }
  .wrap h1{ font-size:20px; margin:0 0 18px; }

  /* Dev toolbar para probar los distintos estados (borrar cuando esté conectado el backend real) */
  .dev-toolbar{
    display:flex; gap:8px; margin-bottom:22px; padding:8px 10px; background:#fff3d6; border:1px dashed #d9a441;
    border-radius:8px; font-size:11.5px; color:#7a5a12; flex-wrap:wrap; align-items:center;
  }
  .dev-toolbar button{
    border:1px solid #d9a441; background:#fff; border-radius:6px; padding:5px 10px; font-size:11px; cursor:pointer;
  }

  .grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
  .tool-card{ background:#fff; border:1px solid var(--line); border-radius:12px; overflow:hidden; }
  .tool-media{ height:100px; background:var(--teal-100); display:flex; align-items:center; justify-content:center; color:var(--teal-700); }
  .tool-media svg{ width:34px; height:34px; }
  .tool-body{ padding:12px 14px 14px; }
  .tool-body h3{ font-size:13.5px; margin:0 0 4px; }
  .tool-status{ font-size:11px; color:var(--success); margin-bottom:10px; }
  .tool-status.paused{ color:var(--muted); }
  .tool-actions{ display:flex; gap:8px; }
  .tool-actions button{
    flex:1; font-size:11px; font-weight:700; border-radius:6px; padding:7px; cursor:pointer;
  }
  .btn-edit{ background:var(--teal-900); color:#fff; border:none; }
  .btn-pause{ background:#fff; color:var(--muted); border:1px solid var(--line); }

  .add-card{
    border:1.5px dashed #C8D2CC; border-radius:12px; display:flex; flex-direction:column; align-items:center;
    justify-content:center; gap:6px; color:var(--teal-700); font-size:13px; font-weight:600; text-decoration:none;
    min-height:170px;
  }

  /* Estado: cargando (skeleton) */
  .skeleton{ background:linear-gradient(90deg, #e6e9e6 25%, #f0f2f0 37%, #e6e9e6 63%); background-size:400% 100%; animation:sk 1.3s ease-in-out infinite; }
  @keyframes sk{ 0%{background-position:100% 50%;} 100%{background-position:0 50%;} }
  .skeleton-card{ background:#fff; border:1px solid var(--line); border-radius:12px; overflow:hidden; }
  .skeleton-card .tool-media{ background:transparent; }

  /* Estado: vacío */
  .empty-state{ text-align:center; padding:60px 20px; background:#fff; border:1px dashed var(--line); border-radius:14px; }
  .empty-state svg{ width:44px; height:44px; color:var(--teal-700); margin-bottom:14px; }
  .empty-state h3{ font-size:16px; margin:0 0 6px; }
  .empty-state p{ color:var(--muted); font-size:13px; margin:0 0 18px; }

  /* Estado: error */
  .error-state{ text-align:center; padding:50px 20px; background:var(--danger-bg); border-radius:14px; }
  .error-state svg{ width:38px; height:38px; color:var(--danger); margin-bottom:12px; }
  .error-state h3{ font-size:15px; margin:0 0 6px; color:var(--danger); }
  .error-state p{ color:#8a3d2e; font-size:13px; margin:0 0 18px; }
  .error-state button{
    background:#fff; border:1px solid var(--danger); color:var(--danger); font-weight:700; font-size:12.5px;
    padding:9px 18px; border-radius:8px; cursor:pointer;
  }

  .view{ display:none; }
  .view.on{ display:block; }
  .view.on.grid-view{ display:grid; }

  /* Semana 10: responsive */
  @media (max-width: 820px){ .grid{ grid-template-columns:repeat(2,1fr); } }
  @media (max-width: 560px){
    .topbar{ padding:12px 16px; flex-wrap:wrap; gap:10px; }
    .top-links{ order:3; flex-basis:100%; margin-left:0; overflow-x:auto; }
    .wrap{ padding:20px 16px 48px; }
    .grid{ grid-template-columns:1fr; }
  }
</style>
</head>
<body>

<div class="topbar">
  <div class="logo"><a href="mis-herramientas">Alquil<span>-Ar</span></a></div>
  <div class="top-links"><a href="mis-herramientas" class="on">Mis herramientas</a><a href="mis-alquileres">Mis alquileres</a></div>
  <div class="top-right">
    <a href="publicar-herramienta" class="btn-amber">+ Publicar nueva</a>
    <a href="logout" class="salir">Salir</a>
  </div>
</div>

<div class="wrap">
  <h1>Mis herramientas</h1>

  <div class="soon-note" id="soonNote">Editar y pausar una publicación todavía no está disponible. Muy pronto vas a poder hacerlo desde acá.</div>

  <!-- ESTADO: con herramientas publicadas -->
  <div class="view grid-view grid" id="view-listado">
    <?php foreach ($herramientas as $h) : ?>
    <div class="tool-card">
      <div class="tool-media">
        <?php if (! empty($h['foto_url'])) : ?>
          <img src="<?= esc($h['foto_url']) ?>" alt="<?= esc($h['nombre']) ?>" style="width:100%;height:100%;object-fit:cover;">
        <?php else : ?>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="12" height="6" rx="1"/><path d="M16 12h4v2h-4"/></svg>
        <?php endif ?>
      </div>
      <div class="tool-body">
        <h3><?= esc($h['nombre']) ?></h3>
        <div class="tool-status<?= $h['estado'] !== 'disponible' ? ' paused' : '' ?>">
          <?= $h['estado'] === 'disponible' ? '●' : '⏸' ?> <?= esc(['disponible' => 'Disponible', 'alquilada' => 'Alquilada', 'no_disponible' => 'No disponible'][$h['estado']] ?? ucfirst($h['estado'])) ?> · $<?= number_format((float) $h['precio'], 0, ',', '.') ?>/día
        </div>
        <!-- BACKEND: faltan las rutas para editar y pausar una herramienta. -->
        <div class="tool-actions"><button class="btn-edit" type="button" onclick="proximamente()">Editar</button><button class="btn-pause" type="button" onclick="proximamente()">Pausar</button></div>
      </div>
    </div>
    <?php endforeach ?>
    <a href="publicar-herramienta" class="add-card">
      <div style="font-size:24px;">+</div>Publicar nueva
    </a>
  </div>

  <!-- ESTADO: vacío (todavía no publicó nada) -->
  <div class="view" id="view-vacio">
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg>
      <h3>Todavía no publicaste ninguna herramienta</h3>
      <p>Empezá a generar un ingreso extra publicando algo que tengas y no uses siempre.</p>
      <a href="publicar-herramienta" class="btn-amber" style="padding:11px 22px;">Publicar mi primera herramienta</a>
    </div>
  </div>

  <!-- ESTADO: error (no se pudo cargar) -->
  <div class="view" id="view-error">
    <div class="error-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      <h3>No pudimos cargar tus herramientas</h3>
      <p>Hubo un problema de conexión. Intentá de nuevo en unos segundos.</p>
      <button onclick="location.reload()">Reintentar</button>
    </div>
  </div>

</div>

<script>
  function showView(name){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('on'));
    document.getElementById('view-' + name).classList.add('on');
  }
  function proximamente(){
    document.getElementById('soonNote').classList.add('on');
  }
  showView('<?= empty($herramientas) ? 'vacio' : 'listado' ?>');
</script>

</body>
</html>
