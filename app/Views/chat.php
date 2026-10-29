<?php
$miId = (int) session('id_usuario');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Chat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--teal-900:#0B3D3C;--teal-700:#12524F;--teal-600:#1C6B63;--teal-100:#E4EDE9;--teal-50:#F2F6F4;--amber-500:#F2A93B;--ink:#16241F;--muted:#5F6E68;--line:#E4E7E3;--card:#fff;--danger:#C1503D;--warn-bg:#FCEFDA;--warn:#B96F16}
*{box-sizing:border-box}
body{margin:0;font-family:'Inter',sans-serif;color:var(--ink);background:var(--teal-50)}
h1,h2{font-family:'Space Grotesk',sans-serif}
.wrap{max-width:620px;margin:0 auto;padding:20px 16px 20px;display:flex;flex-direction:column;height:calc(100vh - 60px)}
.back{color:var(--teal-600);text-decoration:none;font-size:13px;font-weight:600;display:inline-block;margin-bottom:10px}
.head{font-size:16px;margin:0 0 2px}
.sub{color:var(--muted);font-size:12.5px;margin:0 0 12px}
.warn{background:var(--warn-bg);color:var(--warn);border-radius:10px;padding:10px 14px;font-size:12px;margin-bottom:14px;line-height:1.5}
.panel{flex:1;background:#fff;border:1px solid var(--line);border-radius:14px;display:flex;flex-direction:column;overflow:hidden;min-height:0}
.lista{flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px}
.msg{max-width:75%;padding:9px 13px;border-radius:12px;font-size:13.5px;line-height:1.4;word-wrap:break-word}
.msg .autor{font-size:10.5px;font-weight:700;opacity:.7;display:block;margin-bottom:2px}
.msg.mio{align-self:flex-end;background:var(--teal-900);color:#fff;border-bottom-right-radius:3px}
.msg.otro{align-self:flex-start;background:var(--teal-50);color:var(--ink);border-bottom-left-radius:3px}
.vacio{margin:auto;text-align:center;color:var(--muted);font-size:13px;padding:30px}
.form-envio{display:flex;gap:8px;padding:12px;border-top:1px solid var(--line)}
.form-envio input{flex:1;border:1.5px solid var(--line);border-radius:20px;padding:10px 16px;font-size:13.5px;font-family:inherit}
.form-envio input:focus{outline:none;border-color:var(--teal-600)}
.form-envio button{background:var(--teal-900);color:#fff;border:none;border-radius:20px;padding:0 20px;font-weight:700;font-size:13px;cursor:pointer;font-family:inherit}
.form-envio button:disabled{opacity:.6;cursor:default}
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => 'chat']) ?>

<div class="wrap">
  <a href="<?= site_url('mis-reservas') ?>" class="back">← Volver</a>
  <h1 class="head"><?= esc($herramienta['nombre'] ?? 'Herramienta') ?></h1>
  <p class="sub">Alquiler #<?= (int) $id_alquiler ?></p>

  <div class="warn">⚠️ Por tu seguridad: no compartas acá tu DNI, domicilio exacto u otros datos personales que no necesites para coordinar el alquiler.</div>

  <div class="panel">
    <div class="lista" id="lista">
      <?php if (empty($mensajes)) : ?>
        <div class="vacio" id="vacio">Todavía no hay mensajes. Escribí algo para coordinar el retiro.</div>
      <?php endif ?>
      <?php foreach ($mensajes as $m) : ?>
        <div class="msg <?= (int) $m['id_usuario'] === $miId ? 'mio' : 'otro' ?>">
          <?php if ((int) $m['id_usuario'] !== $miId) : ?>
            <span class="autor"><?= esc($m['emisor_nombre']) ?></span>
          <?php endif ?>
          <?= esc($m['mensaje']) ?>
        </div>
      <?php endforeach ?>
    </div>
    <form class="form-envio" id="formEnvio">
      <input type="text" id="inputMensaje" placeholder="Escribí un mensaje..." maxlength="500" autocomplete="off">
      <button type="submit" id="btnEnviar">Enviar</button>
    </form>
  </div>
</div>

<script>
const ID_ALQUILER = <?= (int) $id_alquiler ?>;
const MI_ID = <?= $miId ?>;
const CSRF_TOKEN_NAME = <?= json_encode(csrf_token()) ?>;
let CSRF_HASH = <?= json_encode(csrf_hash()) ?>;

const lista = document.getElementById('lista');
const form = document.getElementById('formEnvio');
const input = document.getElementById('inputMensaje');
const btn = document.getElementById('btnEnviar');

let ultimaCantidad = <?= count($mensajes) ?>;

function escapeHtml(texto) {
  const d = document.createElement('div');
  d.textContent = texto;
  return d.innerHTML;
}

function pintar(mensajes) {
  const vacio = document.getElementById('vacio');
  if (vacio) vacio.remove();

  lista.innerHTML = mensajes.map(function (m) {
    const esMio = parseInt(m.id_usuario, 10) === MI_ID;
    const autor = esMio ? '' : '<span class="autor">' + escapeHtml(m.emisor_nombre) + '</span>';
    return '<div class="msg ' + (esMio ? 'mio' : 'otro') + '">' + autor + escapeHtml(m.mensaje) + '</div>';
  }).join('');

  lista.scrollTop = lista.scrollHeight;
}

lista.scrollTop = lista.scrollHeight;

async function consultar() {
  try {
    const res = await fetch('<?= site_url('chat/' . (int) $id_alquiler . '/mensajes') ?>');
    const data = await res.json();
    if (data.ok && data.mensajes.length !== ultimaCantidad) {
      ultimaCantidad = data.mensajes.length;
      pintar(data.mensajes);
    }
  } catch (e) {
    // Sin red por un momento: el proximo intervalo lo reintenta solo.
  }
}

setInterval(consultar, 4000);

form.addEventListener('submit', async function (e) {
  e.preventDefault();
  const texto = input.value.trim();
  if (! texto) return;

  btn.disabled = true;

  try {
    const res = await fetch('<?= site_url('chat/' . (int) $id_alquiler . '/enviar') ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
      body: new URLSearchParams({ mensaje: texto, [CSRF_TOKEN_NAME]: CSRF_HASH }),
    });
    const data = await res.json();

    if (data.ok) {
      if (data.csrf_hash) CSRF_HASH = data.csrf_hash;
      input.value = '';
      await consultar();
    } else {
      alert(data.error || 'No se pudo enviar el mensaje.');
    }
  } catch (e) {
    alert('Hubo un problema de conexión. Intentá de nuevo.');
  } finally {
    btn.disabled = false;
    input.focus();
  }
});
</script>

</body>
</html>
