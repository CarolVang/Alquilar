<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Catálogo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --teal-900:#0B3D3C; --teal-700:#12524F; --teal-600:#1C6B63; --teal-100:#E4EDE9; --teal-50:#F2F6F4;
    --amber-500:#F2A93B; --ink:#16241F; --muted:#5F6E68; --line:#E4E7E3; --card:#fff;
    --danger:#C1503D; --danger-bg:#FBEAE6; --success:#2F8F5B; --success-bg:#E4F4EB;
  }
  *{box-sizing:border-box;}
  body{ margin:0; font-family:'Inter',sans-serif; color:var(--ink); background:var(--teal-50); }
  h1,h2,h3{ font-family:'Space Grotesk',sans-serif; }

  .nav{ display:flex; align-items:center; gap:28px; padding:16px 40px; background:var(--teal-900); color:#fff; }
  .logo{ font-size:20px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .nav-links{ display:flex; gap:24px; font-size:14px; color:#CFE0DB; flex:1; }
  .nav-links a{ color:inherit; text-decoration:none; }
  .nav-links a.on{ color:#fff; font-weight:700; }
  .avatar-chip{ width:32px; height:32px; border-radius:50%; background:var(--amber-500); color:var(--teal-900); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12.5px; }

  .search-panel{ background:var(--teal-900); padding:0 40px 26px; }
  .search-bar{ display:flex; background:#fff; border-radius:10px; padding:6px; max-width:620px; }
  .search-bar input{ border:none; outline:none; flex:1; padding:10px 14px; font-size:13.5px; font-family:inherit; color:var(--ink); }
  .search-bar button{ background:var(--amber-500); border:none; color:var(--teal-900); font-weight:700; padding:0 20px; border-radius:7px; font-size:13.5px; cursor:pointer; }

  .filters{ display:flex; gap:10px; padding:16px 40px; background:#fff; border-bottom:1px solid var(--line); flex-wrap:wrap; align-items:center; }
  .filters select, .filters .chip{
    border:1.5px solid var(--line); border-radius:20px; padding:8px 14px; font-size:12.5px; font-family:inherit;
    color:var(--ink); background:#fff; cursor:pointer;
  }
  .filters .chip.active{ background:var(--teal-900); color:#fff; border-color:var(--teal-900); }
  .filters .results-count{ margin-left:auto; font-size:12.5px; color:var(--muted); }

  .wrap{ max-width:1180px; margin:0 auto; padding:28px 40px 60px; }

  /* Dev toolbar solo para esta demo (probar estados sin backend) */
  .dev-toolbar{
    display:flex; gap:8px; margin-bottom:22px; padding:8px 10px; background:#fff3d6; border:1px dashed #d9a441;
    border-radius:8px; font-size:11.5px; color:#7a5a12; flex-wrap:wrap; align-items:center;
  }
  .dev-toolbar button{ border:1px solid #d9a441; background:#fff; border-radius:6px; padding:5px 10px; font-size:11px; cursor:pointer; }

  .grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
  .p-card{ background:var(--card); border-radius:12px; overflow:hidden; border:1px solid var(--line); }
  .p-media{ height:112px; background:var(--teal-100); display:flex; align-items:center; justify-content:center; color:var(--teal-700); position:relative; }
  .p-media svg{ width:38px; height:38px; }
  .badge-verified{ position:absolute; top:9px; left:9px; background:#fff; border-radius:14px; padding:3px 8px 3px 6px; font-size:9.5px; font-weight:700; color:var(--teal-700); box-shadow:0 2px 6px rgba(0,0,0,.12); }
  .p-body{ padding:13px 15px 15px; }
  .p-body h3{ font-size:13.5px; margin:0 0 4px; font-weight:600; }
  .p-cat{ font-size:10.5px; color:var(--teal-600); font-weight:600; margin-bottom:4px; }
  .rating{ font-size:11px; color:var(--muted); margin-bottom:9px; }
  .rating b{ color:var(--ink); }
  .price-row{ display:flex; justify-content:space-between; align-items:center; }
  .price{ font-family:'Space Grotesk',sans-serif; font-size:16px; font-weight:700; color:var(--teal-900); }
  .price span{ font-size:10.5px; font-weight:500; color:var(--muted); }
  .rent-btn{ background:var(--teal-900); color:#fff; border:none; padding:7px 13px; border-radius:7px; font-size:11.5px; font-weight:700; cursor:pointer; }

  /* Estado: cargando */
  .skeleton{ background:linear-gradient(90deg, #e6e9e6 25%, #f0f2f0 37%, #e6e9e6 63%); background-size:400% 100%; animation:sk 1.3s ease-in-out infinite; }
  @keyframes sk{ 0%{background-position:100% 50%;} 100%{background-position:0 50%;} }

  /* Estado: sin resultados */
  .empty-state{ text-align:center; padding:60px 20px; background:#fff; border:1px dashed var(--line); border-radius:14px; }
  .empty-state svg{ width:42px; height:42px; color:var(--teal-700); margin-bottom:14px; }
  .empty-state h3{ font-size:16px; margin:0 0 6px; }
  .empty-state p{ color:var(--muted); font-size:13px; margin:0 0 16px; }
  .empty-state button{ background:var(--teal-900); color:#fff; border:none; padding:10px 20px; border-radius:8px; font-weight:700; font-size:12.5px; cursor:pointer; }

  /* Estado: error */
  .error-state{ text-align:center; padding:50px 20px; background:var(--danger-bg); border-radius:14px; }
  .error-state svg{ width:38px; height:38px; color:var(--danger); margin-bottom:12px; }
  .error-state h3{ font-size:15px; margin:0 0 6px; color:var(--danger); }
  .error-state p{ color:#8a3d2e; font-size:13px; margin:0 0 18px; }
  .error-state button{ background:#fff; border:1px solid var(--danger); color:var(--danger); font-weight:700; font-size:12.5px; padding:9px 18px; border-radius:8px; cursor:pointer; }

  .view{ display:none; }
  .view.on{ display:block; }
  .view.on.grid-view{ display:grid; }
</style>
</head>
<body>

<div class="nav">
  <div class="logo">Alquil<span>-Ar</span></div>
  <div class="nav-links"><a class="on">Catálogo</a><a>Cómo funciona</a></div>
  <div class="avatar-chip">SD</div>
</div>

<div class="search-panel">
  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="¿Qué querés alquilar? Ej: taladro, andamio, bote...">
    <button onclick="applyFilters()">Buscar</button>
  </div>
</div>

<div class="filters">
  <select id="filterPrecio" onchange="applyFilters()">
    <option value="">Cualquier precio</option>
    <option value="1000">Hasta $1.000/día</option>
    <option value="2500">Hasta $2.500/día</option>
    <option value="99999">Más de $2.500/día</option>
  </select>
  <div class="results-count" id="resultsCount"></div>
</div>

<div class="wrap">

  <!-- ESTADO: cargando -->
  <div class="view grid-view" id="view-cargando">
    <div class="p-card"><div class="p-media skeleton" style="height:112px;"></div><div class="p-body">
      <div class="skeleton" style="height:12px; width:70%; border-radius:4px; margin-bottom:8px;"></div>
      <div class="skeleton" style="height:10px; width:40%; border-radius:4px; margin-bottom:14px;"></div>
      <div class="skeleton" style="height:26px; width:100%; border-radius:6px;"></div>
    </div></div>
    <div class="p-card"><div class="p-media skeleton" style="height:112px;"></div><div class="p-body">
      <div class="skeleton" style="height:12px; width:70%; border-radius:4px; margin-bottom:8px;"></div>
      <div class="skeleton" style="height:10px; width:40%; border-radius:4px; margin-bottom:14px;"></div>
      <div class="skeleton" style="height:26px; width:100%; border-radius:6px;"></div>
    </div></div>
    <div class="p-card"><div class="p-media skeleton" style="height:112px;"></div><div class="p-body">
      <div class="skeleton" style="height:12px; width:70%; border-radius:4px; margin-bottom:8px;"></div>
      <div class="skeleton" style="height:10px; width:40%; border-radius:4px; margin-bottom:14px;"></div>
      <div class="skeleton" style="height:26px; width:100%; border-radius:6px;"></div>
    </div></div>
  </div>

  <!-- ESTADO: con resultados -->
  <div class="view grid-view" id="view-resultados">
    <div class="grid" id="resultsGrid" style="display:contents;"></div>
  </div>

  <!-- ESTADO: sin resultados -->
  <div class="view" id="view-vacio">
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
      <h3>No encontramos herramientas con esa búsqueda</h3>
      <p>Probá con otra palabra o sacá algunos filtros.</p>
      <button onclick="clearFilters()">Limpiar filtros</button>
    </div>
  </div>

  <!-- ESTADO: error -->
  <div class="view" id="view-error">
    <div class="error-state">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto;"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      <h3>No pudimos cargar el catálogo</h3>
      <p>Hubo un problema de conexión. Intentá de nuevo en unos segundos.</p>
      <button onclick="showView('resultados')">Reintentar</button>
    </div>
  </div>

</div>

<script>
  const ICON_GENERICO = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="12" height="6" rx="1"/><path d="M16 12h4v2h-4"/></svg>';

  const ESTADOS = {
    disponible: 'Disponible',
    alquilada: 'Alquilada',
    no_disponible: 'No disponible',
  };

  // Datos reales, provistos por el Controller (Catalogo::index -> HerramientaModel::getCatalogoOrdenado)
  const TOOLS = <?= json_encode(array_map(static fn ($h) => [
      'name'   => $h['nombre'],
      'price'  => (float) $h['precio'],
      'estado' => $h['estado'],
      'foto'   => $h['foto_url'],
  ], $herramientas)) ?>;

  function renderCards(list){
    const grid = document.getElementById('resultsGrid');
    grid.innerHTML = list.map(t => `
      <div class="p-card">
        <div class="p-media">${t.foto ? `<img src="${t.foto}" alt="${t.name}" style="width:100%;height:100%;object-fit:cover;">` : ICON_GENERICO}</div>
        <div class="p-body">
          <div class="p-cat">${ESTADOS[t.estado] || t.estado}</div>
          <h3>${t.name}</h3>
          <div class="price-row">
            <div class="price">$${t.price.toLocaleString('es-AR')}<span> /día</span></div>
            <button class="rent-btn" ${t.estado !== 'disponible' ? 'disabled style="opacity:.5;cursor:not-allowed;"' : ''}>${t.estado === 'disponible' ? 'Alquilar' : 'No disponible'}</button>
          </div>
        </div>
      </div>`).join('');
  }

  function applyFilters(){
    const q = document.getElementById('searchInput').value.trim().toLowerCase();
    const maxPrecio = document.getElementById('filterPrecio').value;

    let list = TOOLS.filter(t => {
      if(q && !t.name.toLowerCase().includes(q)) return false;
      if(maxPrecio === '1000' && t.price > 1000) return false;
      if(maxPrecio === '2500' && t.price > 2500) return false;
      if(maxPrecio === '99999' && t.price <= 2500) return false;
      return true;
    });

    document.getElementById('resultsCount').textContent = list.length + (list.length === 1 ? ' resultado' : ' resultados');

    if(list.length === 0){
      showView('vacio');
    } else {
      renderCards(list);
      showView('resultados');
    }
  }

  function clearFilters(){
    document.getElementById('searchInput').value = '';
    document.getElementById('filterPrecio').value = '';
    applyFilters();
  }

  function showView(name){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('on'));
    document.getElementById('view-' + name).classList.add('on');
  }

  // Estado inicial
  applyFilters();
</script>

</body>
</html>
