<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Crear cuenta</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --teal-900:#0B3D3C; --teal-700:#12524F; --teal-600:#1C6B63; --teal-100:#E4EDE9; --teal-50:#F2F6F4;
    --amber-500:#F2A93B; --ink:#16241F; --muted:#5F6E68; --line:#E4E7E3; --danger:#C1503D; --danger-bg:#FBEAE6;
  }
  *{box-sizing:border-box;}
  body{ margin:0; font-family:'Inter',sans-serif; color:var(--ink); background:var(--teal-50); }
  h1,h2{ font-family:'Space Grotesk',sans-serif; }

  .wrap{ display:flex; min-height:100vh; }
  .side{
    flex:1; background:linear-gradient(150deg,var(--teal-900),var(--teal-600));
    color:#fff; padding:50px; display:flex; flex-direction:column; justify-content:space-between; position:relative; overflow:hidden;
  }
  .side::after{ content:""; position:absolute; right:-80px; bottom:-80px; width:260px; height:260px; border:50px solid rgba(255,255,255,.06); border-radius:50%; }
  .logo{ font-size:22px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .side h2{ font-size:26px; line-height:1.25; max-width:340px; margin-top:40px; }
  .side p{ color:#CFE0DB; font-size:13.5px; max-width:320px; }
  .stat-pill{ display:inline-flex; gap:8px; align-items:center; background:rgba(255,255,255,.1); padding:8px 14px; border-radius:20px; font-size:12px; }

  .form-col{ flex:1; padding:48px; display:flex; flex-direction:column; justify-content:center; max-width:460px; margin:0 auto; }
  .role-toggle{ display:flex; background:var(--teal-50); border-radius:9px; padding:4px; margin-bottom:20px; }
  .role-toggle div{ flex:1; text-align:center; padding:9px; border-radius:6px; font-size:13px; font-weight:700; color:var(--muted); cursor:pointer; }
  .role-toggle div.on{ background:#fff; color:var(--teal-900); box-shadow:0 2px 6px rgba(0,0,0,.08); }

  .form-row{ display:flex; gap:12px; }
  .field-group{ flex:1; margin-bottom:14px; }
  .field-label{ font-size:12px; font-weight:600; color:var(--muted); margin:0 0 6px; display:block; }
  .field-input{
    width:100%; border:1.5px solid var(--line); border-radius:8px; padding:11px 14px; font-size:13.5px;
    color:var(--ink); font-family:inherit;
  }
  .field-input:focus{ outline:none; border-color:var(--teal-600); }
  .field-error{ font-size:11px; color:var(--danger); margin-top:5px; display:none; }
  .field-group.has-error .field-input{ border-color:var(--danger); background:var(--danger-bg); }
  .field-group.has-error .field-error{ display:block; }

  .terms-row{ display:flex; align-items:flex-start; gap:8px; margin:6px 0 4px; font-size:11.5px; color:var(--muted); }
  .terms-row input{ margin-top:2px; }

  .btn{
    margin-top:18px; width:100%; background:var(--teal-900); color:#fff; border:none; padding:13px;
    border-radius:8px; font-weight:700; font-size:14px; cursor:pointer; font-family:inherit;
  }
  .btn:hover{ background:var(--teal-700); }
  .switch-line{ text-align:center; font-size:12.5px; color:var(--muted); margin-top:16px; }
  .switch-line a{ color:var(--teal-700); font-weight:700; text-decoration:none; }

  .mobile-logo{ display:none; }

  @media (max-width: 760px){
    .side{ display:none; }
    .form-col{ padding:36px 22px; max-width:100%; justify-content:flex-start; }
    .form-row{ flex-direction:column; gap:0; }
    .mobile-logo{
      display:block; text-align:center; font-size:20px; font-weight:700; margin-bottom:20px;
    }
    .mobile-logo span{ color:var(--amber-500); }
  }
</style>
</head>
<body>

<div class="wrap">
  <div class="side">
    <div>
      <div class="logo">Alquil<span>-Ar</span></div>
      <h2>Sumate a la red y empezá a alquilar o a publicar.</h2>
      <p>Creá tu cuenta gratis en menos de un minuto.</p>
    </div>
    <div class="stat-pill">🔒 Tus datos siempre protegidos</div>
  </div>

  <div class="form-col">
    <div class="mobile-logo">Alquil<span>-Ar</span></div>
    <h1 style="font-size:21px; margin:0 0 4px;">Creá tu cuenta</h1>
    <p style="color:var(--muted); font-size:13px; margin:0 0 18px;">Elegí cómo vas a usar Alquil-Ar</p>

    <div class="role-toggle">
      <div class="on" id="tab-cliente" onclick="setRole('cliente')">Soy Cliente</div>
      <div id="tab-retador" onclick="setRole('retador')">Soy Retador</div>
    </div>

    <form id="regForm" method="post" action="/registro">
      <input type="hidden" name="rol" id="rolInput" value="cliente">

      <div class="field-group" id="group-nombre">
        <label class="field-label">Nombre completo</label>
        <input class="field-input" type="text" name="nombre" placeholder="Ej: Sofía Duarte" required minlength="2">
        <div class="field-error">Ingresá tu nombre completo.</div>
      </div>

      <div class="field-group" id="group-email">
        <label class="field-label">Email</label>
        <input class="field-input" type="email" name="email" placeholder="tu@email.com" required>
        <div class="field-error">Ingresá un email válido.</div>
      </div>

      <div class="form-row">
        <div class="field-group" id="group-telefono">
          <label class="field-label">Teléfono</label>
          <input class="field-input" type="tel" name="telefono" placeholder="+54 9 11 ...">
          <div class="field-error">Ingresá un teléfono válido.</div>
        </div>
        <div class="field-group" id="group-dni">
          <label class="field-label">DNI</label>
          <input class="field-input" type="text" name="dni" placeholder="00.000.000" required>
          <div class="field-error">Ingresá tu DNI.</div>
        </div>
      </div>

      <div class="form-row">
        <div class="field-group" id="group-password">
          <label class="field-label">Contraseña</label>
          <input class="field-input" type="password" name="password" placeholder="••••••••••" required minlength="6">
          <div class="field-error">Mínimo 6 caracteres.</div>
        </div>
        <div class="field-group" id="group-password2">
          <label class="field-label">Confirmar contraseña</label>
          <input class="field-input" type="password" name="password2" placeholder="••••••••••" required minlength="6">
          <div class="field-error">Las contraseñas no coinciden.</div>
        </div>
      </div>

      <div class="terms-row">
        <input type="checkbox" id="terms" required>
        <label for="terms">Acepto los Términos y Condiciones y la Política de Privacidad de Alquil-Ar.</label>
      </div>

      <button type="submit" class="btn">Crear cuenta</button>
    </form>

    <div class="switch-line">¿Ya tenés cuenta? <a href="/login">Iniciar sesión</a></div>
  </div>
</div>

<script>
  function setRole(role){
    document.getElementById('tab-cliente').classList.toggle('on', role === 'cliente');
    document.getElementById('tab-retador').classList.toggle('on', role === 'retador');
    document.getElementById('rolInput').value = role;
  }

  // Validación visual básica (front-end). La validación real va en el Controller/Model.
  document.getElementById('regForm').addEventListener('submit', function(e){
    let valid = true;
    const f = this;
    ['nombre','email','dni','password'].forEach(name => {
      const group = document.getElementById('group-' + name);
      group.classList.remove('has-error');
      if(!f[name].value.trim()){
        group.classList.add('has-error');
        valid = false;
      }
    });
    if(f.email.value && !f.email.value.includes('@')){
      document.getElementById('group-email').classList.add('has-error');
      valid = false;
    }
    document.getElementById('group-password2').classList.remove('has-error');
    if(f.password.value !== f.password2.value || !f.password2.value){
      document.getElementById('group-password2').classList.add('has-error');
      valid = false;
    }
    if(!valid) e.preventDefault();
  });
</script>

</body>
</html>
