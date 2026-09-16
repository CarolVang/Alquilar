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
</style>
</head>
<body>

<div class="topbar">
  <div class="logo">Alquil<span>-Ar</span></div>
  <a href="/publicar-herramienta" class="btn-amber">+ Publicar nueva</a>
</div>

<div class="wrap">
  <h1>Mis herramientas</h1>

  <!-- Barra solo para esta demo: simula los distintos estados de la pantalla -->
  <div class="dev-toolbar">
    🔧 Vista previa de estados (para QA / diseño):
    <button onclick="showView('cargando')">Cargando</button>
    <button onclick="showView('listado')">Con herramientas</button>
    <button onclick="showView('vacio')">Vacío</button>
    <button onclick="showView('error')">Error</button>
  </div>

  <!-- ESTADO: cargando -->
  <div class="view grid-view" id="view-cargando">
    <div class="skeleton-card">
      <div class="tool-media skeleton" style="height:100px;"></div>
      <div class="tool-body">
        <div class="skeleton" style="height:12px; width:70%; border-radius:4px; margin-bottom:8px;"></div>
        <div class="skeleton" style="height:10px; width:40%; border-radius:4px; margin-bottom:14px;"></div>
        <div class="skeleton" style="height:28px; width:100%; border-radius:6px;"></div>
      </div>
    </div>
    <div class="skeleton-card">
      <div class="tool-media skeleton" style="height:100px;"></div>
      <div class="tool-body">
        <div class="skeleton" style="height:12px; width:70%; border-radius:4px; margin-bottom:8px;"></div>
        <div class="skeleton" style="height:10px; width:40%; border-radius:4px; margin-bottom:14px;"></div>
        <div class="skeleton" style="height:28px; width:100%; border-radius:6px;"></div>
      </div>
    </div>
    <div class="skeleton-card">
      <div class="tool-media skeleton" style="height:100px;"></div>
      <div class="tool-body">
        <div class="skeleton" style="height:12px; width:70%; border-radius:4px; margin-bottom:8px;"></div>
        <div class="skeleton" style="height:10px; width:40%; border-radius:4px; margin-bottom:14px;"></div>
        <div class="skeleton" style="height:28px; width:100%; border-radius:6px;"></div>
      </div>
    </div>
  </div>

  <!-- ESTADO: con herramientas publicadas -->
  <div class="view grid-view" id="view-listado">
    <div class="tool-card">
      <div class="tool-media"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.1-3.1a5 5 0 01-6.7 6.7L5 22l-2-2 9.1-9.1a5 5 0 016.7-6.7l-3.1 3.1z"/></svg></div>
      <div class="tool-body">
        <h3>Taladro percutor Bosch</h3>
        <div class="tool-status">● Activo · $1.200/día</div>
        <div class="tool-actions"><button class="btn-edit">Editar</button><button class="btn-pause">Pausar</button></div>
      </div>
    </div>
    <div class="tool-card">
      <div class="tool-media"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg></div>
      <div class="tool-body">
        <h3>Andamio 3 cuerpos</h3>
        <div class="tool-status">● Activo · $3.500/día</div>
        <div class="tool-actions"><button class="btn-edit">Editar</button><button class="btn-pause">Pausar</button></div>
      </div>
    </div>
    <div class="tool-card">
      <div class="tool-media"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="12" height="6" rx="1"/><path d="M16 12h4v2h-4"/></svg></div>
      <div class="tool-body">
        <h3>Amoladora angular</h3>
        <div class="tool-status paused">⏸ Pausado · $900/día</div>
        <div class="tool-actions"><button class="btn-edit">Editar</button><button class="btn-pause">Reactivar</button></div>
      </div>
    </div>
    <a href="/publicar-herramienta" class="add-card">
      <div style="font-size:24px;">+</div>Publicar nueva
    </a>
  </div>

  <!-- ESTADO: vacío (todavía no publicó nada) -->
  <div class="view" id="view-vacio">
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg>
      <h3>Todavía no publicaste ninguna herramienta</h3>
      <p>Empezá a generar un ingreso extra publicando algo que tengas y no uses siempre.</p>
      <a href="/publicar-herramienta" class="btn-amber" style="padding:11px 22px;">Publicar mi primera herramienta</a>
    </div>
  </div>

  <!-- ESTADO: error (no se pudo cargar) -->
  <div class="view" id="view-error">
    <div class="error-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      <h3>No pudimos cargar tus herramientas</h3>
      <p>Hubo un problema de conexión. Intentá de nuevo en unos segundos.</p>
      <button onclick="showView('listado')">Reintentar</button>
    </div>
  </div>

</div>

<script>
  // Esta función solo existe para esta demo visual (probar los 4 estados
  // sin backend). Cuando Rodolfo conecte esto, el estado real va a venir
  // de la respuesta del servidor: mientras espera la respuesta se muestra
  // 'cargando'; si falla, 'error'; si no hay datos, 'vacio'; si hay datos,
  // 'listado'. Este bloque de JS y la barra amarilla de arriba se borran
  // en ese momento.
  function showView(name){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('on'));
    document.getElementById('view-' + name).classList.add('on');
  }
  showView('listado'); // estado inicial por defecto de esta demo
</script>

</body>
</html>
