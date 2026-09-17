<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Publicar herramienta</title>
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
  h1,h2{ font-family:'Space Grotesk',sans-serif; }

  .topbar{ display:flex; align-items:center; gap:14px; padding:16px 24px; background:var(--teal-900); color:#fff; }
  .logo{ font-size:18px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .back{ color:#CFE0DB; text-decoration:none; font-size:13px; }

  .wrap{ max-width:620px; margin:0 auto; padding:32px 20px 60px; }
  .card{ background:#fff; border:1px solid var(--line); border-radius:14px; padding:26px 28px; }

  .upload-box{
    border:1.5px dashed #C8D2CC; border-radius:12px; padding:26px; text-align:center; color:var(--teal-700);
    margin-bottom:20px; cursor:pointer;
  }
  .upload-box svg{ width:30px; height:30px; margin-bottom:8px; }
  .upload-box .u1{ font-size:13px; font-weight:600; }
  .upload-box .u2{ font-size:11px; color:var(--muted); margin-top:3px; }
  .thumbs-row{ display:flex; gap:8px; margin-top:12px; justify-content:center; }
  .thumbs-row div{ width:52px; height:52px; border-radius:8px; background:var(--teal-100); }

  .form-row{ display:flex; gap:14px; }
  .field-group{ flex:1; margin-bottom:16px; }
  .field-label{ font-size:12px; font-weight:600; color:var(--muted); margin:0 0 6px; display:block; }
  .field-input, textarea.field-input{
    width:100%; border:1.5px solid var(--line); border-radius:8px; padding:11px 14px; font-size:13.5px;
    color:var(--ink); font-family:inherit;
  }
  textarea.field-input{ resize:vertical; min-height:70px; }
  .field-input:focus{ outline:none; border-color:var(--teal-600); }
  .field-error{ font-size:11px; color:var(--danger); margin-top:5px; display:none; }
  .field-group.has-error .field-input{ border-color:var(--danger); background:var(--danger-bg); }
  .field-group.has-error .field-error{ display:block; }

  .btn{
    margin-top:6px; width:100%; background:var(--teal-900); color:#fff; border:none; padding:13px;
    border-radius:8px; font-weight:700; font-size:14px; cursor:pointer; font-family:inherit;
    display:flex; align-items:center; justify-content:center; gap:10px;
  }
  .btn:hover{ background:var(--teal-700); }
  .btn:disabled{ opacity:.75; cursor:default; }

  .spinner{
    width:16px; height:16px; border:2px solid rgba(255,255,255,.35); border-top-color:#fff;
    border-radius:50%; animation:spin .7s linear infinite; display:none;
  }
  @keyframes spin{ to{ transform:rotate(360deg); } }

  .banner{ display:none; border-radius:10px; padding:12px 14px; font-size:13px; margin-bottom:18px; align-items:center; gap:10px; }
  .banner.error{ background:var(--danger-bg); color:var(--danger); }
  .banner.success{ background:var(--success-bg); color:var(--success); }

  .success-view{ display:none; text-align:center; padding:20px 0; }
  .success-view .check{
    width:56px; height:56px; border-radius:50%; background:var(--success-bg); color:var(--success);
    display:flex; align-items:center; justify-content:center; margin:0 auto 16px;
  }
  .success-view .check svg{ width:28px; height:28px; }
  .success-view h2{ font-size:18px; margin:0 0 6px; }
  .success-view p{ color:var(--muted); font-size:13px; margin:0 0 20px; }
  .success-view a.btn{ display:inline-flex; text-decoration:none; width:auto; padding:12px 24px; }
</style>
</head>
<body>

<div class="topbar">
  <div class="logo">Alquil<span>-Ar</span></div>
  <a href="/panel" class="back">← Volver al panel</a>
</div>

<div class="wrap">
  <h1 style="font-size:20px; margin:0 0 4px;">Publicar nueva herramienta</h1>
  <p style="color:var(--muted); font-size:13px; margin:0 0 18px;">Completá los datos para que otros usuarios puedan encontrarla y alquilarla.</p>

  <div class="card">

    <!-- Banner de error (se muestra vía JS si el backend responde con error) -->
    <div class="banner error" id="bannerError">⚠️ No pudimos publicar la herramienta. Revisá los datos e intentá de nuevo.</div>

    <!-- Formulario -->
    <form id="publicarForm">
      <div class="upload-box" id="uploadBox">
        <svg class="stroke" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto; display:block;">
          <path d="M12 16V4M12 4l-4 4M12 4l4 4M4 16v3a2 2 0 002 2h12a2 2 0 002-2v-3"/>
        </svg>
        <div class="u1">Arrastrá tus fotos acá</div>
        <div class="u2">o hacé clic para subir (hasta 6 fotos)</div>
        <div class="thumbs-row"><div></div><div></div><div></div></div>
      </div>

      <div class="field-group" id="group-nombre">
        <label class="field-label">Nombre de la herramienta</label>
        <input class="field-input" type="text" name="nombre" placeholder="Ej: Taladro percutor Bosch" required>
        <div class="field-error">Ingresá un nombre.</div>
      </div>

      <div class="form-row">
        <div class="field-group" id="group-categoria">
          <label class="field-label">Categoría</label>
          <select class="field-input" name="categoria" required>
            <option value="">Elegí una categoría</option>
            <option>Herramientas</option>
            <option>Construcción</option>
            <option>Recreación</option>
            <option>Jardín</option>
            <option>Eventos</option>
            <option>Vehículos</option>
          </select>
          <div class="field-error">Elegí una categoría.</div>
        </div>
        <div class="field-group" id="group-precio">
          <label class="field-label">Precio por día</label>
          <input class="field-input" type="number" name="precio" placeholder="$ 1.200" min="1" required>
          <div class="field-error">Ingresá un precio válido.</div>
        </div>
      </div>

      <div class="field-group" id="group-capacidad">
        <label class="field-label">Capacidad / detalle técnico</label>
        <input class="field-input" type="text" name="capacidad" placeholder="Ej: hasta 13mm, 150kg por nivel, etc.">
      </div>

      <div class="field-group" id="group-descripcion">
        <label class="field-label">Descripción</label>
        <textarea class="field-input" name="descripcion" placeholder="Contá el estado, qué incluye, condiciones de uso..." required></textarea>
        <div class="field-error">Agregá una descripción.</div>
      </div>

      <button type="submit" class="btn" id="btnPublicar">
        <span class="spinner" id="spinner"></span>
        <span id="btnText">Publicar herramienta</span>
      </button>
    </form>

    <!-- Vista de éxito (oculta hasta que se simule/reciba la confirmación) -->
    <div class="success-view" id="successView">
      <div class="check">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>
      </div>
      <h2>¡Herramienta publicada!</h2>
      <p>Ya está visible para que otros usuarios la encuentren y la alquilen.</p>
      <a href="/mis-herramientas" class="btn">Ver mis herramientas</a>
    </div>

  </div>
</div>

<script>
  // -----------------------------------------------------------------
  // Esto es una SIMULACIÓN visual de los 3 estados (cargando/error/éxito),
  // para mostrar el diseño mientras no está conectado el Backend real.
  // Cuando Rodolfo conecte esto a CodeIgniter, este bloque se reemplaza
  // por el fetch/submit real al Controller, y se muestra el banner de
  // error o el successView según la respuesta del servidor.
  // -----------------------------------------------------------------
  const form = document.getElementById('publicarForm');
  const btn = document.getElementById('btnPublicar');
  const spinner = document.getElementById('spinner');
  const btnText = document.getElementById('btnText');
  const bannerError = document.getElementById('bannerError');
  const successView = document.getElementById('successView');

  form.addEventListener('submit', function(e){
    e.preventDefault();

    // Validación visual básica
    let valid = true;
    ['nombre','categoria','precio','descripcion'].forEach(name => {
      const group = document.getElementById('group-' + name);
      group.classList.remove('has-error');
      if(!form[name].value.trim()){
        group.classList.add('has-error');
        valid = false;
      }
    });
    bannerError.style.display = 'none';
    if(!valid) return;

    // Estado: cargando
    btn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Publicando...';

    // Simulación de respuesta del servidor (reemplazar por el submit real)
    setTimeout(function(){
      spinner.style.display = 'none';
      btn.disabled = false;
      btnText.textContent = 'Publicar herramienta';

      // Para ver el ESTADO DE ÉXITO (comportamiento por defecto de esta demo):
      form.style.display = 'none';
      successView.style.display = 'block';

      // Para ver el ESTADO DE ERROR en su lugar, comentá las 2 líneas de
      // arriba y descomentá esta: bannerError.style.display = 'flex';
    }, 1200);
  });
</script>

</body>
</html>
