<?php
$mesesEs = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
$hoy = new DateTime('now');
$anio = (int) $hoy->format('Y');
$mes = (int) $hoy->format('n');
$diasEnMes = (int) $hoy->format('t');
$primerDiaSemana = ((int) (new DateTime("$anio-$mes-01"))->format('N')) - 1; // 0 = lunes
$nombreMes = $mesesEs[$mes] . ' ' . $anio;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Detalle</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --teal-900:#0B3D3C; --teal-700:#12524F; --teal-600:#1C6B63; --teal-100:#E4EDE9; --teal-50:#F2F6F4;
    --amber-500:#F2A93B; --ink:#16241F; --muted:#5F6E68; --line:#E4E7E3; --card:#fff;
    --danger:#C1503D; --danger-bg:#FBEAE6; --success:#2F8F5B; --success-bg:#E4F4EB; --pending:#B96F16; --pending-bg:#FCEFDA;
  }
  *{box-sizing:border-box;}
  body{ margin:0; font-family:'Inter',sans-serif; color:var(--ink); background:var(--teal-50); }
  h1,h2,h3{ font-family:'Space Grotesk',sans-serif; }

  .nav{ display:flex; align-items:center; gap:20px; padding:16px 40px; background:var(--teal-900); color:#fff; }
  .logo{ font-size:20px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .back{ color:#CFE0DB; text-decoration:none; font-size:13px; flex:1; }
  .avatar-chip{ width:32px; height:32px; border-radius:50%; background:var(--amber-500); color:var(--teal-900); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12.5px; }

  .detail-wrap{ display:grid; grid-template-columns:1.15fr 0.85fr; gap:32px; max-width:1180px; margin:0 auto; padding:30px 24px 60px; }

  .gallery-main{ height:260px; background:var(--teal-100); border-radius:12px; display:flex; align-items:center; justify-content:center; color:var(--teal-700); margin-bottom:10px; }
  .gallery-main svg{ width:56px; height:56px; }
  .gallery-thumbs{ display:flex; gap:8px; margin-bottom:22px; }
  .gallery-thumbs div{ width:56px; height:56px; background:var(--teal-50); border:1px solid var(--line); border-radius:8px; }

  .badge-verified{ display:inline-flex; align-items:center; gap:5px; background:#fff; border:1px solid var(--line); border-radius:14px; padding:4px 10px; font-size:10.5px; font-weight:700; color:var(--teal-700); margin-bottom:10px; }
  .detail-info h1{ font-size:22px; margin:0 0 6px; }
  .rating{ font-size:12.5px; color:var(--muted); margin-bottom:14px; }
  .rating b{ color:var(--ink); }
  .owner-row{ display:flex; align-items:center; gap:10px; margin:14px 0 18px; font-size:12.5px; color:var(--muted); }
  .owner-avatar{ width:34px; height:34px; border-radius:50%; background:var(--teal-600); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; }
  .desc{ font-size:13px; color:var(--muted); line-height:1.6; margin-bottom:18px; }
  .spec-grid{ display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:8px; }
  .spec{ background:var(--card); border:1px solid var(--line); border-radius:8px; padding:10px 12px; font-size:11.5px; }
  .spec b{ display:block; font-size:13px; color:var(--teal-900); margin-top:2px; }

  /* ===== Panel de reserva ===== */
  .booking-card{ background:var(--card); border:1px solid var(--line); border-radius:14px; padding:22px 22px 24px; align-self:start; position:sticky; top:20px; }
  .booking-card h3{ font-size:15px; margin:0 0 4px; }
  .price-line{ font-family:'Space Grotesk',sans-serif; font-size:20px; font-weight:700; color:var(--teal-900); margin-bottom:16px; }
  .price-line span{ font-size:11.5px; font-weight:500; color:var(--muted); font-family:'Inter',sans-serif; }

  .avail-legend{ display:flex; gap:14px; font-size:10.5px; color:var(--muted); margin:10px 0 6px; }
  .avail-legend span{ display:inline-flex; align-items:center; gap:5px; }
  .avail-legend i{ width:9px; height:9px; border-radius:2px; display:inline-block; }

  .cal-grid{ display:grid; grid-template-columns:repeat(7,1fr); gap:4px; margin-bottom:16px; }
  .cal-grid .dname{ font-size:9.5px; font-weight:700; color:var(--muted); text-align:center; padding-bottom:2px; }
  .cal-day{ aspect-ratio:1; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:10.5px; font-weight:600; border:1px solid var(--line); cursor:pointer; background:#fff; }
  .cal-day.free:hover{ border-color:var(--teal-600); }
  .cal-day.booked{ background:var(--danger-bg); color:var(--danger); border-color:transparent; cursor:not-allowed; }
  .cal-day.off{ visibility:hidden; }
  .cal-day.selected{ background:var(--teal-900); color:#fff; border-color:var(--teal-900); }

  .field-label{ font-size:11.5px; font-weight:600; color:var(--muted); margin:0 0 5px; display:block; }
  .field-group{ margin-bottom:13px; }
  .form-row{ display:flex; gap:10px; }
  .field-input{
    width:100%; border:1.5px solid var(--line); border-radius:8px; padding:10px 12px; font-size:13px;
    color:var(--ink); font-family:inherit;
  }
  .field-input:focus{ outline:none; border-color:var(--teal-600); }
  .field-error{ font-size:10.5px; color:var(--danger); margin-top:4px; display:none; }
  .field-group.has-error .field-input{ border-color:var(--danger); background:var(--danger-bg); }
  .field-group.has-error .field-error{ display:block; }

  .summary-box{ background:var(--teal-50); border-radius:9px; padding:12px 14px; margin:14px 0; font-size:12px; display:none; }
  .summary-box.on{ display:block; }
  .summary-row{ display:flex; justify-content:space-between; padding:3px 0; color:var(--muted); }
  .summary-row.total{ border-top:1px solid var(--line); margin-top:5px; padding-top:8px; font-weight:700; color:var(--ink); font-size:13.5px; }

  .reservar-btn{
    width:100%; background:var(--amber-500); color:var(--teal-900); border:none; padding:13px;
    border-radius:8px; font-weight:700; font-size:14px; cursor:pointer; font-family:inherit;
  }
  .reservar-btn:hover{ background:#e0981f; }

  .toast{
    display:none; align-items:center; gap:8px; background:var(--danger-bg); color:var(--danger); border-radius:8px;
    padding:10px 12px; font-size:12px; margin-top:12px;
  }
  .toast.on{ display:flex; }

  @media (max-width: 860px){
    .detail-wrap{ grid-template-columns:1fr; }
    .booking-card{ position:static; }
  }
</style>
</head>
<body>

<div class="nav">
  <div class="logo">Alquil<span>-Ar</span></div>
  <a href="/catalogo" class="back">← Volver al catálogo</a>
</div>

<div class="detail-wrap">

  <div class="detail-info">
    <div class="gallery-main">
      <?php if (! empty($herramienta['foto_url'])) : ?>
        <img src="<?= esc($herramienta['foto_url']) ?>" alt="<?= esc($herramienta['nombre']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
      <?php else : ?>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg>
      <?php endif ?>
    </div>

    <h1><?= esc($herramienta['nombre']) ?></h1>
    <div class="owner-row">
      <div class="owner-avatar"><?= esc(strtoupper(substr($propietario['nombre'] ?? '?', 0, 2))) ?></div>
      Publicado por <b style="color:var(--ink)"><?= esc($propietario['nombre'] ?? 'Usuario') ?></b>
    </div>

    <p class="desc"><?= esc($herramienta['descripcion'] ?? '') ?></p>
  </div>

  <div class="booking-card">
    <h3>Elegí cuándo alquilarlo</h3>
    <div class="price-line">$<?= number_format((float) $herramienta['precio'], 0, ',', '.') ?> <span>/día</span></div>

    <div class="avail-legend">
      <span><i style="background:#fff; border:1px solid var(--line);"></i>Disponible</span>
      <span><i style="background:var(--danger-bg); border:1px solid var(--danger);"></i>Ocupado</span>
      <span><i style="background:var(--teal-900);"></i>Seleccionado</span>
    </div>
    <div style="font-size:11.5px; font-weight:600; margin-bottom:6px;"><?= esc($nombreMes) ?></div>
    <div class="cal-grid" id="calGrid"></div>

    <div class="form-row">
      <div class="field-group" id="group-fechaInicio">
        <label class="field-label">Fecha de inicio</label>
        <input class="field-input" type="text" id="fechaInicio" placeholder="Elegí un día" readonly>
        <div class="field-error">Elegí una fecha de inicio.</div>
      </div>
      <div class="field-group" id="group-fechaFin">
        <label class="field-label">Fecha de fin</label>
        <input class="field-input" type="text" id="fechaFin" placeholder="Elegí un día" readonly>
        <div class="field-error">La fecha de fin debe ser posterior a la de inicio.</div>
      </div>
    </div>

    <div class="form-row">
      <div class="field-group" id="group-horaRetiro">
        <label class="field-label">Hora de retiro</label>
        <select class="field-input" id="horaRetiro">
          <option value="">Elegí un horario</option>
          <option>08:00</option><option>09:00</option><option>10:00</option><option>14:00</option><option>16:00</option>
        </select>
        <div class="field-error">Elegí una hora de retiro.</div>
      </div>
      <div class="field-group" id="group-horaDevolucion">
        <label class="field-label">Hora de devolución</label>
        <select class="field-input" id="horaDevolucion">
          <option value="">Elegí un horario</option>
          <option>08:00</option><option>09:00</option><option>10:00</option><option>14:00</option><option>16:00</option><option>18:00</option>
        </select>
        <div class="field-error">Elegí una hora de devolución.</div>
      </div>
    </div>

    <div class="summary-box" id="summaryBox">
      <div class="summary-row"><span id="summaryDias"></span><span id="summarySubtotal"></span></div>
      <div class="summary-row"><span>Tarifa de servicio</span><span id="summaryServicio"></span></div>
      <div class="summary-row total"><span>Total</span><span id="summaryTotal"></span></div>
    </div>

    <button class="reservar-btn" id="btnReservar">Reservar</button>
    <div class="toast" id="toastError">⚠️ Revisá los datos marcados antes de continuar.</div>
  </div>

</div>

<script>
  const PRECIO_DIA = <?= (float) $herramienta['precio'] ?>;
  const ANIO = <?= $anio ?>;
  const MES = <?= $mes ?>; // 1-12
  const DIAS_MES = <?= $diasEnMes ?>;
  const PRIMER_DIA_SEMANA = <?= $primerDiaSemana ?>; // 0 = lunes

  // Períodos realmente ocupados (reservas confirmadas), traídos por el Controller
  // desde AlquilerModel::getPeriodosOcupados() (Luz).
  const PERIODOS_OCUPADOS = <?= json_encode($periodos_ocupados) ?>;

  function fechaDelDia(d){
    const mm = String(MES).padStart(2, '0');
    const dd = String(d).padStart(2, '0');
    return `${ANIO}-${mm}-${dd}`;
  }

  function diaOcupado(d){
    const fecha = fechaDelDia(d);
    return PERIODOS_OCUPADOS.some(p => fecha >= p.fecha_inicio.slice(0, 10) && fecha <= p.fecha_fin.slice(0, 10));
  }

  let seleccion = { inicio: null, fin: null };

  function buildCalendar(){
    const dnames = ['L','M','M','J','V','S','D'];
    let html = dnames.map(d => `<div class="dname">${d}</div>`).join('');
    for(let i=0; i<PRIMER_DIA_SEMANA; i++){ html += `<div class="cal-day off"></div>`; }
    for(let d=1; d<=DIAS_MES; d++){
      const ocupado = diaOcupado(d);
      html += `<div class="cal-day ${ocupado ? 'booked' : 'free'}" data-day="${d}" onclick="${ocupado ? '' : `pickDay(${d})`}">${d}</div>`;
    }
    document.getElementById('calGrid').innerHTML = html;
  }

  function pickDay(d){
    if(!seleccion.inicio || (seleccion.inicio && seleccion.fin)){
      // arrancar selección nueva
      seleccion = { inicio: d, fin: null };
    } else if(d > seleccion.inicio){
      // ¿el rango cruza algún día ocupado?
      let cruzaOcupado = false;
      for(let o = seleccion.inicio + 1; o < d; o++){
        if(diaOcupado(o)){ cruzaOcupado = true; break; }
      }
      if(cruzaOcupado){
        seleccion = { inicio: d, fin: null };
      } else {
        seleccion.fin = d;
      }
    } else {
      seleccion = { inicio: d, fin: null };
    }
    renderSeleccion();
  }

  function renderSeleccion(){
    document.querySelectorAll('.cal-day.free, .cal-day.selected').forEach(el => {
      const d = parseInt(el.dataset.day);
      el.classList.remove('selected');
      el.classList.add('free');
      if(seleccion.inicio && seleccion.fin && d >= seleccion.inicio && d <= seleccion.fin){
        el.classList.add('selected'); el.classList.remove('free');
      } else if(seleccion.inicio && !seleccion.fin && d === seleccion.inicio){
        el.classList.add('selected'); el.classList.remove('free');
      }
    });
    document.getElementById('fechaInicio').value = seleccion.inicio ? `${seleccion.inicio} de <?= esc($nombreMes) ?>` : '';
    document.getElementById('fechaFin').value = seleccion.fin ? `${seleccion.fin} de <?= esc($nombreMes) ?>` : '';
    updateSummary();
  }

  function updateSummary(){
    const box = document.getElementById('summaryBox');
    if(seleccion.inicio && seleccion.fin){
      const dias = (seleccion.fin - seleccion.inicio) + 1;
      const subtotal = dias * PRECIO_DIA;
      const servicio = Math.round(subtotal * 0.1);
      document.getElementById('summaryDias').textContent = `$${PRECIO_DIA.toLocaleString('es-AR')} × ${dias} ${dias === 1 ? 'día' : 'días'}`;
      document.getElementById('summarySubtotal').textContent = `$${subtotal.toLocaleString('es-AR')}`;
      document.getElementById('summaryServicio').textContent = `$${servicio.toLocaleString('es-AR')}`;
      document.getElementById('summaryTotal').textContent = `$${(subtotal + servicio).toLocaleString('es-AR')}`;
      box.classList.add('on');
    } else {
      box.classList.remove('on');
    }
  }

  document.getElementById('btnReservar').addEventListener('click', function(){
    let valid = true;
    document.getElementById('toastError').classList.remove('on');

    ['fechaInicio','fechaFin','horaRetiro','horaDevolucion'].forEach(id => {
      const group = document.getElementById('group-' + id);
      const val = document.getElementById(id).value;
      group.classList.remove('has-error');
      if(!val){ group.classList.add('has-error'); valid = false; }
    });

    const hRetiro = document.getElementById('horaRetiro').value;
    const hDevol = document.getElementById('horaDevolucion').value;
    if(seleccion.inicio && seleccion.fin && seleccion.inicio === seleccion.fin && hRetiro && hDevol && hDevol <= hRetiro){
      document.getElementById('group-horaDevolucion').classList.add('has-error');
      valid = false;
    }

    if(!valid){
      document.getElementById('toastError').classList.add('on');
      return;
    }

    const fechaInicio = `${fechaDelDia(seleccion.inicio)} ${hRetiro}:00`;
    const fechaFin = `${fechaDelDia(seleccion.fin)} ${hDevol}:00`;

    const params = new URLSearchParams({
      id_herramienta: <?= (int) $herramienta['id_herramienta'] ?>,
      fecha_inicio: fechaInicio,
      fecha_fin: fechaFin,
    });
    window.location.href = '/confirmacion?' + params.toString();
  });

  buildCalendar();
</script>

</body>
</html>
