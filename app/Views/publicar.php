<?php
// $herramienta llega solo desde Herramientas::editar(); si no, es el form de alta.
$herramienta = $herramienta ?? null;
$esEdicion   = $herramienta !== null;
$urlSubmit   = $esEdicion ? site_url('mis-herramientas/editar/' . (int) $herramienta['id_herramienta']) : site_url('publicar-herramienta');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — <?= $esEdicion ? 'Editar herramienta' : 'Publicar herramienta' ?></title>
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

  .back-row{ padding:16px 24px 0; }
  .back{ color:var(--teal-600); text-decoration:none; font-size:13px; font-weight:600; }

  .wrap{ max-width:620px; margin:0 auto; padding:32px 20px 60px; }
  .card{ background:#fff; border:1px solid var(--line); border-radius:14px; padding:26px 28px; }

  .upload-box{
    border:1.5px dashed #C8D2CC; border-radius:12px; padding:26px; text-align:center; color:var(--teal-700);
    margin-bottom:20px; cursor:pointer; position:relative;
  }
  .upload-box svg{ width:30px; height:30px; margin-bottom:8px; }
  .upload-box .u1{ font-size:13px; font-weight:600; }
  .upload-box .u2{ font-size:11px; color:var(--muted); margin-top:3px; }
  .upload-box input[type="file"]{ position:absolute; inset:0; opacity:0; cursor:pointer; z-index:1; }
  .upload-box.has-preview{ padding:0; border-style:solid; overflow:hidden; }
  .upload-box.has-preview .placeholder{ display:none; }
  .preview-wrap{ display:none; position:relative; }
  .upload-box.has-preview .preview-wrap{ display:block; }
  .preview-wrap img{ width:100%; max-height:220px; object-fit:cover; display:block; }
  .preview-wrap .quitar-foto{
    position:absolute; top:8px; right:8px; background:rgba(11,61,60,.85); color:#fff; border:none;
    border-radius:50%; width:26px; height:26px; font-size:14px; cursor:pointer; z-index:2;
  }

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

  @media (max-width: 560px){
    .wrap{ padding:20px 14px 48px; }
    .card{ padding:20px 18px; }
    .form-row{ flex-direction:column; gap:0; }
  }
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => 'publicar']) ?>
<div class="back-row">
  <a href="<?= site_url('perfil') ?>" class="back">← Volver a mi perfil</a>
</div>

<div class="wrap">
  <h1 style="font-size:20px; margin:0 0 4px;"><?= $esEdicion ? 'Editar herramienta' : 'Publicar nueva herramienta' ?></h1>
  <p style="color:var(--muted); font-size:13px; margin:0 0 18px;"><?= $esEdicion ? 'Actualizá los datos de tu herramienta.' : 'Completá los datos para que otros usuarios puedan encontrarla y alquilarla.' ?></p>

  <div class="card">

    <!-- Banner de error (se muestra vía JS si el backend responde con error) -->
    <div class="banner error" id="bannerError">⚠️ No pudimos <?= $esEdicion ? 'guardar los cambios' : 'publicar la herramienta' ?>. Revisá los datos e intentá de nuevo.</div>

    <!-- Formulario -->
    <form id="publicarForm" data-url="<?= $urlSubmit ?>">
      <?= csrf_field() ?>
      <div class="upload-box<?= $esEdicion && ! empty($herramienta['foto_url']) ? ' has-preview' : '' ?>" id="uploadBox">
        <input type="file" name="foto" id="inputFoto" accept="image/jpeg,image/png,image/webp">
        <div class="placeholder">
          <svg class="stroke" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto; display:block;">
            <path d="M12 16V4M12 4l-4 4M12 4l4 4M4 16v3a2 2 0 002 2h12a2 2 0 002-2v-3"/>
          </svg>
          <div class="u1">Hacé clic para subir una foto</div>
          <div class="u2">JPG, PNG o WEBP · hasta 4 MB (opcional)</div>
        </div>
        <div class="preview-wrap" id="previewWrap">
          <img id="previewImg" alt="Vista previa de la foto" <?= $esEdicion && ! empty($herramienta['foto_url']) ? 'src="' . esc($herramienta['foto_url']) . '"' : '' ?>>
          <button type="button" class="quitar-foto" id="btnQuitarFoto" title="Quitar foto">✕</button>
        </div>
      </div>
      <?php if ($esEdicion) : ?>
        <p style="font-size:11px; color:var(--muted); margin:-14px 0 16px;">Si no elegís una foto nueva, se mantiene la actual. Tocar la ✕ no la borra todavía, solo descarta la selección.</p>
      <?php endif ?>

      <div class="field-group" id="group-nombre">
        <label class="field-label">Nombre de la herramienta</label>
        <input class="field-input" type="text" name="nombre" placeholder="Ej: Taladro percutor Bosch" value="<?= esc($herramienta['nombre'] ?? '') ?>" required>
        <div class="field-error">Ingresá un nombre.</div>
      </div>

      <div class="form-row">
        <div class="field-group" id="group-categoria">
          <label class="field-label">Categoría</label>
          <select class="field-input" name="categoria" required>
            <option value="">Elegí una categoría</option>
            <?php foreach (['Herramientas', 'Construcción', 'Recreación', 'Jardín', 'Eventos', 'Vehículos'] as $cat) : ?>
              <option <?= ($herramienta['categoria'] ?? '') === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
            <?php endforeach ?>
          </select>
          <div class="field-error">Elegí una categoría.</div>
        </div>
        <div class="field-group" id="group-precio_dia">
          <label class="field-label">Precio por día</label>
          <input class="field-input" type="number" name="precio_dia" placeholder="$ 1.200" min="1" value="<?= esc($herramienta['precio_dia'] ?? '') ?>" required>
          <div class="field-error">Ingresá un precio válido.</div>
        </div>
      </div>

      <div class="field-group" id="group-precio-hora">
        <label class="field-label">Precio por hora (opcional)</label>
        <input class="field-input" type="number" name="precio_hora" placeholder="$ 200" min="1" value="<?= esc($herramienta['precio_hora'] ?? '') ?>">
      </div>

      <div class="field-group" id="group-capacidad">
        <label class="field-label">Capacidad / detalle técnico</label>
        <input class="field-input" type="text" name="capacidad" placeholder="Ej: hasta 13mm, 150kg por nivel, etc.">
      </div>

      <div class="field-group" id="group-descripcion">
        <label class="field-label">Descripción</label>
        <textarea class="field-input" name="descripcion" placeholder="Contá el estado, qué incluye, condiciones de uso..." required><?= esc($herramienta['descripcion'] ?? '') ?></textarea>
        <div class="field-error">Agregá una descripción.</div>
      </div>

      <button type="submit" class="btn" id="btnPublicar">
        <span class="spinner" id="spinner"></span>
        <span id="btnText"><?= $esEdicion ? 'Guardar cambios' : 'Publicar herramienta' ?></span>
      </button>
    </form>

    <!-- Vista de éxito (oculta hasta que se simule/reciba la confirmación) -->
    <div class="success-view" id="successView">
      <div class="check">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>
      </div>
      <h2><?= $esEdicion ? '¡Cambios guardados!' : '¡Herramienta publicada!' ?></h2>
      <p><?= $esEdicion ? 'Los cambios ya están visibles en el catálogo.' : 'Ya está visible para que otros usuarios la encuentren y la alquilen.' ?></p>
      <a href="<?= site_url('mis-herramientas') ?>" class="btn">Ver mis herramientas</a>
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
  // Preview de la foto elegida, antes de mandarla al servidor.
  const uploadBox = document.getElementById('uploadBox');
  const inputFoto = document.getElementById('inputFoto');
  const previewImg = document.getElementById('previewImg');

  inputFoto.addEventListener('change', function () {
    const archivo = inputFoto.files[0];
    if (! archivo) {
      uploadBox.classList.remove('has-preview');
      return;
    }
    const lector = new FileReader();
    lector.onload = function (e) {
      previewImg.src = e.target.result;
      uploadBox.classList.add('has-preview');
    };
    lector.readAsDataURL(archivo);
  });

  document.getElementById('btnQuitarFoto').addEventListener('click', function () {
    inputFoto.value = '';
    uploadBox.classList.remove('has-preview');
  });

  const form = document.getElementById('publicarForm');
  const btn = document.getElementById('btnPublicar');
  const spinner = document.getElementById('spinner');
  const btnText = document.getElementById('btnText');
  const bannerError = document.getElementById('bannerError');
  const successView = document.getElementById('successView');

  const textoBotonOriginal = btnText.textContent;
  const textoCargando = <?= json_encode($esEdicion ? 'Guardando...' : 'Publicando...') ?>;

  form.addEventListener('submit', function(e){
    e.preventDefault();

    // Validación visual básica
    let valid = true;
    ['nombre','categoria','precio_dia','descripcion'].forEach(name => {
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
    btnText.textContent = textoCargando;

    fetch(form.dataset.url, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(res => res.json())
      .then(data => {
        spinner.style.display = 'none';
        btn.disabled = false;
        btnText.textContent = textoBotonOriginal;

        if (data.ok) {
          form.style.display = 'none';
          successView.style.display = 'block';
        } else {
          bannerError.textContent = '⚠️ ' + (data.error || bannerError.textContent);
          bannerError.style.display = 'flex';
        }
      })
      .catch(() => {
        spinner.style.display = 'none';
        btn.disabled = false;
        btnText.textContent = textoBotonOriginal;
        bannerError.textContent = '⚠️ No pudimos conectar con el servidor. Intentá de nuevo.';
        bannerError.style.display = 'flex';
      });
  });
</script>

</body>
</html>
