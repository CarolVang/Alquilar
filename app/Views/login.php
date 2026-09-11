<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Ingresar</title>
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

  .login-wrap{ display:flex; min-height:100vh; }
  .login-side{
    flex:1; background:linear-gradient(150deg,var(--teal-900),var(--teal-600));
    color:#fff; padding:50px; display:flex; flex-direction:column; justify-content:space-between; position:relative; overflow:hidden;
  }
  .login-side::after{ content:""; position:absolute; right:-80px; bottom:-80px; width:260px; height:260px; border:50px solid rgba(255,255,255,.06); border-radius:50%; }
  .logo{ font-size:22px; font-weight:700; }
  .logo span{ color:var(--amber-500); }
  .login-side h2{ font-size:26px; line-height:1.25; max-width:340px; margin-top:40px; }
  .login-side p{ color:#CFE0DB; font-size:13.5px; max-width:320px; }
  .stat-pill{ display:inline-flex; gap:8px; align-items:center; background:rgba(255,255,255,.1); padding:8px 14px; border-radius:20px; font-size:12px; margin-top:10px; width:fit-content; }

  .login-form{ flex:1; padding:56px; display:flex; flex-direction:column; justify-content:center; max-width:440px; margin:0 auto; }
  .role-toggle{ display:flex; background:var(--teal-50); border-radius:9px; padding:4px; margin-bottom:22px; }
  .role-toggle div{ flex:1; text-align:center; padding:9px; border-radius:6px; font-size:13px; font-weight:700; color:var(--muted); cursor:pointer; }
  .role-toggle div.on{ background:#fff; color:var(--teal-900); box-shadow:0 2px 6px rgba(0,0,0,.08); }
  .field-label{ font-size:12px; font-weight:600; color:var(--muted); margin:14px 0 6px; display:block; }
  .field-input{
    width:100%; border:1.5px solid var(--line); border-radius:8px; padding:11px 14px; font-size:13.5px;
    color:var(--ink); font-family:inherit;
  }
  .field-input:focus{ outline:none; border-color:var(--teal-600); }
  .field-error{ font-size:11px; color:var(--danger); margin-top:5px; display:none; }
  .field-group.has-error .field-input{ border-color:var(--danger); background:var(--danger-bg); }
  .field-group.has-error .field-error{ display:block; }

  .login-btn{
    margin-top:24px; width:100%; background:var(--teal-900); color:#fff; border:none; padding:13px;
    border-radius:8px; font-weight:700; font-size:14px; cursor:pointer; font-family:inherit;
  }
  .login-btn:hover{ background:var(--teal-700); }
  .forgot{ text-align:right; font-size:11.5px; color:var(--teal-600); margin-top:8px; font-weight:600; text-decoration:none; display:block; }
  .divider-or{ display:flex; align-items:center; gap:10px; margin:20px 0; color:var(--muted); font-size:11.5px; }
  .divider-or::before,.divider-or::after{ content:""; flex:1; height:1px; background:var(--line); }
  .social-row{ display:flex; gap:10px; }
  .social-btn{
    flex:1; border:1.5px solid var(--line); border-radius:8px; padding:10px; text-align:center;
    font-size:13px; font-weight:600; color:var(--ink); background:#fff; cursor:pointer; font-family:inherit;
  }
  .switch-line{ text-align:center; font-size:12.5px; color:var(--muted); margin-top:18px; }
  .switch-line a{ color:var(--teal-700); font-weight:700; text-decoration:none; }

  .mobile-logo{ display:none; }

  @media (max-width: 760px){
    .login-side{ display:none; }
    .login-form{ padding:40px 24px; max-width:100%; justify-content:flex-start; }
    .mobile-logo{
      display:block; text-align:center; font-size:20px; font-weight:700; margin-bottom:24px;
    }
    .mobile-logo span{ color:var(--amber-500); }
  }
</style>
</head>
<body>

<div class="login-wrap">
  <div class="login-side">
    <div>
      <div class="logo">Alquil<span>-Ar</span></div>
      <h2>Todo lo que necesitás, cuando lo necesitás.</h2>
      <p>Sumate a la red comunitaria de alquiler de herramientas y equipos.</p>
    </div>
    <div>
      <div class="stat-pill">⭐ 4.8 calificación promedio</div><br>
      <div class="stat-pill" style="margin-top:8px;">🔒 Pagos protegidos</div>
    </div>
  </div>

  <div class="login-form">
    <div class="mobile-logo">Alquil<span>-Ar</span></div>
    <h1 style="font-size:22px; margin:0 0 4px;">Bienvenido/a de vuelta</h1>
    <p style="color:var(--muted); font-size:13px; margin:0 0 20px;">Ingresá a tu cuenta para continuar</p>

    <div class="role-toggle">
      <div class="on" id="tab-cliente" onclick="setRole('cliente')">Soy Cliente</div>
      <div id="tab-retador" onclick="setRole('retador')">Soy Retador</div>
    </div>

    <form id="loginForm" method="post" action="/login">
      <input type="hidden" name="rol" id="rolInput" value="cliente">

      <label class="field-label">Email</label>
      <div class="field-group" id="group-email">
        <input class="field-input" type="email" name="email" placeholder="tu@email.com" required>
        <div class="field-error">Ingresá un email válido.</div>
      </div>

      <label class="field-label">Contraseña</label>
      <div class="field-group" id="group-password">
        <input class="field-input" type="password" name="password" placeholder="••••••••••" required minlength="6">
        <div class="field-error">La contraseña debe tener al menos 6 caracteres.</div>
      </div>

      <a href="/olvide-password" class="forgot">¿Olvidaste tu contraseña?</a>

      <button type="submit" class="login-btn">Ingresar</button>
    </form>

    <div class="divider-or">o continuá con</div>
    <div class="social-row">
      <button class="social-btn">Google</button>
      <button class="social-btn">Facebook</button>
    </div>

    <div class="switch-line">¿No tenés cuenta? <a href="/registro">Registrate gratis</a></div>
  </div>
</div>

<script>
  function setRole(role){
    document.getElementById('tab-cliente').classList.toggle('on', role === 'cliente');
    document.getElementById('tab-retador').classList.toggle('on', role === 'retador');
    document.getElementById('rolInput').value = role;
  }

  // Validación visual básica (front-end). La validación real va en el Controller/Model.
  document.getElementById('loginForm').addEventListener('submit', function(e){
    let valid = true;
    const email = this.email;
    const pass = this.password;

    document.getElementById('group-email').classList.remove('has-error');
    document.getElementById('group-password').classList.remove('has-error');

    if(!email.value || !email.value.includes('@')){
      document.getElementById('group-email').classList.add('has-error');
      valid = false;
    }
    if(!pass.value || pass.value.length < 6){
      document.getElementById('group-password').classList.add('has-error');
      valid = false;
    }
    if(!valid) e.preventDefault();
  });
</script>

</body>
</html>
