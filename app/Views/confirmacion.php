<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Confirmar reserva</title>
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

  .nav{ display:flex; align-items:center; gap:14px; padding:16px 40px; background:var(--teal-900); color:#fff; }
  .logo{ font-size:18px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .back{ color:#CFE0DB; text-decoration:none; font-size:13px; flex:1; }

  .wrap{ max-width:560px; margin:0 auto; padding:34px 20px 60px; }

  /* Dev toolbar solo para esta demo */
  .dev-toolbar{
    display:flex; gap:8px; margin-bottom:20px; padding:8px 10px; background:#fff3d6; border:1px dashed #d9a441;
    border-radius:8px; font-size:11px; color:#7a5a12; flex-wrap:wrap; align-items:center;
  }
  .dev-toolbar button{ border:1px solid #d9a441; background:#fff; border-radius:6px; padding:5px 9px; font-size:10.5px; cursor:pointer; }

  .view{ display:none; }
  .view.on{ display:block; }

  /* ===== Vista: Resumen ===== */
  h1.title{ font-size:20px; margin:0 0 18px; }
  .card{ background:var(--card); border:1px solid var(--line); border-radius:14px; padding:20px 22px; margin-bottom:16px; }
  .tool-row{ display:flex; gap:14px; align-items:center; margin-bottom:6px; }
  .tool-media{ width:64px; height:64px; border-radius:10px; background:var(--teal-100); display:flex; align-items:center; justify-content:center; color:var(--teal-700); flex-shrink:0; }
  .tool-media svg{ width:28px; height:28px; }
  .tool-name{ font-size:15px; font-weight:700; margin:0 0 3px; }
  .tool-owner{ font-size:11.5px; color:var(--muted); }

  .detail-rows{ margin-top:16px; }
  .detail-row{ display:flex; justify-content:space-between; padding:9px 0; border-top:1px solid var(--line); font-size:13px; }
  .detail-row span:first-child{ color:var(--muted); }
  .detail-row span:last-child{ font-weight:600; }

  .summary-box{ background:var(--teal-50); border-radius:10px; padding:14px 16px; margin-top:4px; font-size:12.5px; }
  .summary-row{ display:flex; justify-content:space-between; padding:4px 0; color:var(--muted); }
  .summary-row.total{ border-top:1px solid var(--line); margin-top:6px; padding-top:10px; font-weight:700; color:var(--ink); font-size:15px; }

  .btn{ width:100%; border:none; padding:13px; border-radius:8px; font-weight:700; font-size:14px; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:10px; }
  .btn-confirm{ background:var(--amber-500); color:var(--teal-900); margin-bottom:10px; }
  .btn-confirm:hover{ background:#e0981f; }
  .btn-back{ background:#fff; color:var(--teal-900); border:1.5px solid var(--line); }

  .spinner{ width:16px; height:16px; border:2px solid rgba(11,61,60,.25); border-top-color:var(--teal-900); border-radius:50%; animation:spin .7s linear infinite; display:none; }
  @keyframes spin{ to{ transform:rotate(360deg); } }

  /* ===== Vista: Éxito ===== */
  .success-view{ text-align:center; padding:30px 10px; }
  .check-circle{ width:60px; height:60px; border-radius:50%; background:var(--success-bg); color:var(--success); display:flex; align-items:center; justify-content:center; margin:0 auto 18px; }
  .check-circle svg{ width:30px; height:30px; }
  .success-view h2{ font-size:19px; margin:0 0 6px; }
  .success-view p{ color:var(--muted); font-size:13px; margin:0 0 20px; }
  .confirm-card{ background:var(--card); border:1px solid var(--line); border-radius:12px; padding:16px 18px; margin-bottom:20px; text-align:left; }
  .confirm-row{ display:flex; justify-content:space-between; padding:6px 0; font-size:12.5px; border-bottom:1px dashed var(--line); }
  .confirm-row:last-child{ border-bottom:none; }

  /* ===== Vista: Error ===== */
  .error-view{ text-align:center; padding:30px 10px; }
  .error-circle{ width:60px; height:60px; border-radius:50%; background:var(--danger-bg); color:var(--danger); display:flex; align-items:center; justify-content:center; margin:0 auto 18px; }
  .error-circle svg{ width:30px; height:30px; }
  .error-view h2{ font-size:19px; margin:0 0 6px; color:var(--danger); }
  .error-view p{ color:#8a3d2e; font-size:13px; margin:0 0 22px; }
</style>
</head>
<body>

<div class="nav">
  <div class="logo">Alquil<span>-Ar</span></div>
  <a href="/detalle" class="back">← Volver / modificar</a>
</div>

<div class="wrap">

  <!-- Barra solo para esta demo: simula los estados sin backend -->
  <div class="dev-toolbar">
    🔧 Vista previa de estados:
    <button onclick="showView('resumen')">Resumen</button>
    <button onclick="showView('exito')">Éxito</button>
    <button onclick="showView('error')">Error</button>
  </div>

  <!-- ESTADO: Resumen (antes de confirmar) -->
  <div class="view on" id="view-resumen">
    <h1 class="title">Confirmá tu reserva</h1>

    <div class="card">
      <div class="tool-row">
        <div class="tool-media"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg></div>
        <div>
          <div class="tool-name">Andamio 3 cuerpos</div>
          <div class="tool-owner">Publicado por Martín I.</div>
        </div>
      </div>

      <div class="detail-rows">
        <div class="detail-row"><span>Fecha de inicio</span><span>Jue 3 de septiembre</span></div>
        <div class="detail-row"><span>Fecha de fin</span><span>Vie 4 de septiembre</span></div>
        <div class="detail-row"><span>Hora de retiro</span><span>09:00</span></div>
        <div class="detail-row"><span>Hora de devolución</span><span>18:00</span></div>
      </div>
    </div>

    <div class="card">
      <h3 style="font-size:13.5px; margin:0 0 10px;">Precio estimado</h3>
      <div class="summary-box">
        <div class="summary-row"><span>$3.500 × 2 días</span><span>$7.000</span></div>
        <div class="summary-row"><span>Tarifa de servicio</span><span>$700</span></div>
        <div class="summary-row total"><span>Total</span><span>$7.700</span></div>
      </div>
    </div>

    <button class="btn btn-confirm" id="btnConfirmar">
      <span class="spinner" id="spinner"></span>
      <span id="btnText">Confirmar reserva</span>
    </button>
    <a href="/detalle" class="btn btn-back" style="text-decoration:none;">Volver y modificar</a>
  </div>

  <!-- ESTADO: Éxito -->
  <div class="view" id="view-exito">
    <div class="success-view">
      <div class="check-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>
      </div>
      <h2>¡Reserva confirmada!</h2>
      <p>Martín va a coordinar el retiro con vos. Te enviamos los detalles a tu email.</p>
      <div class="confirm-card">
        <div class="confirm-row"><span>Herramienta</span><b>Andamio 3 cuerpos</b></div>
        <div class="confirm-row"><span>Fechas</span><b>3 al 4 de sept.</b></div>
        <div class="confirm-row"><span>Total pagado</span><b>$7.700</b></div>
        <div class="confirm-row"><span>N° de reserva</span><b>#A-10452</b></div>
      </div>
      <a href="/mis-reservas" class="btn btn-confirm" style="text-decoration:none; display:flex;">Ver mis reservas</a>
    </div>
  </div>

  <!-- ESTADO: Error -->
  <div class="view" id="view-error">
    <div class="error-view">
      <div class="error-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      </div>
      <h2>No pudimos confirmar tu reserva</h2>
      <p>Puede que esas fechas se hayan ocupado justo ahora, o hubo un problema de conexión. Probá de nuevo.</p>
      <button class="btn btn-confirm" onclick="showView('resumen')">Volver a intentar</button>
    </div>
  </div>

</div>

<script>
  function showView(name){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('on'));
    document.getElementById('view-' + name).classList.add('on');
  }

  document.getElementById('btnConfirmar').addEventListener('click', function(){
    const btn = this;
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');

    btn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Confirmando...';

    // Simulación de respuesta del servidor (acá se conecta el envío real al Backend).
    setTimeout(function(){
      spinner.style.display = 'none';
      btn.disabled = false;
      btnText.textContent = 'Confirmar reserva';
      showView('exito'); // por defecto esta demo muestra éxito; cambiar a showView('error') para ver ese caso
    }, 1200);
  });
</script>

</body>
</html>
