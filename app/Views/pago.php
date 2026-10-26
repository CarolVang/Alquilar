<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Pago</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>

:root{--teal-900:#0B3D3C;--teal-700:#12524F;--teal-600:#1C6B63;--teal-100:#E4EDE9;--teal-50:#F2F6F4;--amber-500:#F2A93B;--ink:#16241F;--muted:#5F6E68;--line:#E4E7E3;--card:#fff;--danger:#C1503D;--danger-bg:#FBEAE6;--success:#2F8F5B;--success-bg:#E4F4EB;--pending:#B96F16;--pending-bg:#FCEFDA}
*{box-sizing:border-box}
body{margin:0;font-family:'Inter',sans-serif;color:var(--ink);background:var(--teal-50)}
h1,h2,h3{font-family:'Space Grotesk',sans-serif}
.wrap{max-width:1000px;margin:0 auto;padding:28px 24px 60px}
.card{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:20px 22px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;border-radius:8px;padding:12px 20px;font:700 14px 'Inter',sans-serif;cursor:pointer;text-decoration:none}
.btn-primary{background:var(--amber-500);color:var(--teal-900)}.btn-primary:hover{background:#e0981f}
.btn-dark{background:var(--teal-900);color:#fff}
.btn-ghost{background:#fff;color:var(--teal-900);border:1.5px solid var(--line)}
.btn-block{width:100%}
.btn[disabled]{opacity:.6;cursor:default}
.page-title{font-size:22px;margin:0 0 4px}
.page-sub{color:var(--muted);font-size:13px;margin:0 0 20px}
.back-link{display:inline-block;color:var(--teal-600);font-size:13px;font-weight:600;text-decoration:none;margin-bottom:14px}
.view{display:none}.view.on{display:block}
.dev-toolbar{gap:8px;margin-bottom:20px;padding:8px 10px;background:#fff3d6;border:1px dashed #d9a441;border-radius:8px;font-size:11.5px;color:#7a5a12;flex-wrap:wrap;align-items:center}
.dev-toolbar button{border:1px solid #d9a441;background:#fff;border-radius:6px;padding:5px 10px;font-size:11px;cursor:pointer}
.spinner{width:18px;height:18px;border:2.5px solid rgba(11,61,60,.2);border-top-color:var(--teal-900);border-radius:50%;animation:spin .7s linear infinite;display:inline-block}
@keyframes spin{to{transform:rotate(360deg)}}
.skeleton{background:linear-gradient(90deg,#e6e9e6 25%,#f0f2f0 37%,#e6e9e6 63%);background-size:400% 100%;animation:sk 1.3s ease-in-out infinite;border-radius:6px}
@keyframes sk{0%{background-position:100% 50%}100%{background-position:0 50%}}
.state{text-align:center;padding:48px 20px;border-radius:14px}
.state svg{width:42px;height:42px;margin-bottom:12px}
.state h2{font-size:17px;margin:0 0 6px}
.state p{font-size:13px;margin:0 0 18px}
.state.empty{background:#fff;border:1px dashed var(--line)}.state.empty svg{color:var(--teal-700)}.state.empty p{color:var(--muted)}
.state.error{background:var(--danger-bg)}.state.error svg,.state.error h2{color:var(--danger)}.state.error p{color:#8a3d2e}
.state.ok{background:#fff;border:1px solid var(--line)}.state.ok .circle{background:var(--success-bg);color:var(--success)}
.circle{width:62px;height:62px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.circle svg{width:30px;height:30px;margin:0}
.pill{display:inline-block;font-size:10.5px;font-weight:700;padding:4px 11px;border-radius:20px;white-space:nowrap}
.pill.ok{background:var(--success-bg);color:var(--success)}.pill.warn{background:var(--pending-bg);color:var(--pending)}
.pill.gray{background:var(--teal-50);color:var(--muted);border:1px solid var(--line)}.pill.bad{background:var(--danger-bg);color:var(--danger)}
.tabs{display:flex;gap:4px;border-bottom:1px solid var(--line);margin-bottom:16px;overflow-x:auto}
.tab{padding:10px 16px;font-size:13px;font-weight:600;color:var(--muted);border:none;background:none;border-bottom:2px solid transparent;cursor:pointer;white-space:nowrap;font-family:inherit}
.tab.on{color:var(--teal-900);border-color:var(--teal-900)}
.tab small{background:var(--teal-100);border-radius:10px;padding:1px 7px;margin-left:5px;font-size:10.5px}
.rows{display:flex;flex-direction:column;gap:12px}
.row{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px}
.row .ic{width:46px;height:46px;border-radius:10px;background:var(--teal-100);display:flex;align-items:center;justify-content:center;color:var(--teal-700);flex-shrink:0}
.row .ic svg{width:22px;height:22px}
.row .main{flex:1;min-width:0}
.row h3{margin:0 0 3px;font-size:14px}
.row .meta{font-size:12px;color:var(--muted);line-height:1.5}
.row .side{text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:8px}
.row .total{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:15px;color:var(--teal-900)}
.mini{font-size:11.5px;font-weight:700;border-radius:6px;padding:7px 12px;border:none;cursor:pointer;font-family:inherit}
.mini.dark{background:var(--teal-900);color:#fff}.mini.line{background:#fff;color:var(--danger);border:1px solid var(--line)}
.sum-row{display:flex;justify-content:space-between;gap:12px;padding:6px 0;font-size:13px;color:var(--muted)}
.sum-row span:last-child{color:var(--ink);font-weight:600;white-space:nowrap}
.sum-row.total{border-top:1px solid var(--line);margin-top:6px;padding-top:12px;font-size:15px;color:var(--ink);font-weight:700}
.sum-note{font-size:11px;color:var(--muted);margin:-2px 0 4px}
@media (max-width:640px){
  .wrap{padding:20px 14px 48px}
  .row{flex-wrap:wrap}
  .row .side{flex-direction:row;align-items:center;justify-content:space-between;width:100%;text-align:left}
}

.dev-toolbar{display:flex}
.pay-grid{display:grid;grid-template-columns:1.15fr .85fr;gap:22px;align-items:start}
.field{margin-bottom:14px}
.field label{display:block;font-size:11.5px;font-weight:600;color:var(--muted);margin-bottom:5px}
.field input{width:100%;border:1.5px solid var(--line);border-radius:8px;padding:11px 12px;font:14px 'Inter',sans-serif;color:var(--ink)}
.field input:focus{outline:none;border-color:var(--teal-600)}
.ferr{display:none;color:var(--danger);font-size:11px;margin-top:4px}
.field.bad input{border-color:var(--danger);background:var(--danger-bg)}
.field.bad .ferr{display:block}
.two{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.secure{font-size:11.5px;color:var(--muted);text-align:center;margin:12px 0 0}
.tool-line{display:flex;gap:12px;align-items:center;margin-bottom:10px}
.tool-line .ic{width:46px;height:46px;border-radius:10px;background:var(--teal-100);display:flex;align-items:center;justify-content:center;color:var(--teal-700);flex-shrink:0}
.tool-line .ic svg{width:22px;height:22px}
.tool-line b{display:block;font-size:14px}.tool-line small{color:var(--muted);font-size:11.5px}
.confirm-card{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 18px;margin:0 0 20px;text-align:left}
.confirm-row{display:flex;justify-content:space-between;gap:12px;padding:7px 0;font-size:12.5px;border-bottom:1px dashed var(--line)}
.confirm-row:last-child{border-bottom:none}
.actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
@media (max-width:760px){.pay-grid{grid-template-columns:1fr}#resumen{order:-1}}

/* ===== Semana 10 ===== */
body:not(.demo) .dev-toolbar{display:none}   /* la barra de prueba solo se ve agregando ?demo a la dirección */
@media (max-width:420px){.two{grid-template-columns:1fr;gap:0}.card{padding:16px}.actions .btn{width:100%}}
</style>
</head>
<body>
<script>if(/[?&]demo\b/.test(location.search)) document.body.classList.add('demo');</script>

<?= view('partials/nav', ['activo' => '']) ?>

<?php if (! session('isLoggedIn')) : ?>
<!-- Sin sesión no se puede pagar. BACKEND: el Controller también tiene que exigir sesión. -->
<div class="wrap" style="max-width:900px">
  <div class="state empty">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
    <h2>Iniciá sesión para pagar tu reserva</h2>
    <p>Tus fechas quedan guardadas. Cuando ingreses, volvé al resumen para seguir.</p>
    <a href="<?= site_url('login') ?>" class="btn btn-primary">Iniciar sesión</a>
  </div>
</div>
<?php else : ?>
<div class="wrap" style="max-width:900px">
  <a href="<?= site_url('confirmacion') ?>" class="back-link" id="backTop">← Volver al resumen</a>

  <!-- Barra solo para esta demo: simula los estados sin backend -->
  <div class="dev-toolbar">
    🔧 Vista previa de estados:
    <button onclick="demo('form')">Formulario</button>
    <button onclick="demo('proc')">Procesando</button>
    <button onclick="demo('ok')">Éxito</button>
    <button onclick="demo('rech')">Rechazado</button>
    <button onclick="demo('err')">Error</button>
    <button onclick="demo('vacio')">Sin reserva</button>
    <span style="flex-basis:100%">Tarjetas de prueba: <b>4242 4242 4242 4242</b> (aprobada) · <b>4000 0000 0000 0002</b> (rechazada) · vencimiento a futuro y CVV de 3 dígitos.</span>
  </div>

  <!-- FORMULARIO -->
  <div class="view on" id="view-form">
    <h1 class="page-title">Pagá tu reserva</h1>
    <p class="page-sub">Último paso: completá los datos de tu tarjeta para confirmar el alquiler.</p>

    <div class="pay-grid">
      <form class="card" id="payForm" novalidate>
        <h3 style="margin:0 0 14px;font-size:15px">Datos de la tarjeta</h3>
        <div class="field" id="f-titular">
          <label for="titular">Nombre del titular</label>
          <input id="titular" autocomplete="cc-name" placeholder="Como figura en la tarjeta">
          <div class="ferr">Ingresá el nombre del titular.</div>
        </div>
        <div class="field" id="f-numero">
          <label for="numero">Número de tarjeta</label>
          <input id="numero" inputmode="numeric" autocomplete="cc-number" maxlength="23" placeholder="0000 0000 0000 0000">
          <div class="ferr">Ingresá un número de tarjeta válido.</div>
        </div>
        <div class="two">
          <div class="field" id="f-venc">
            <label for="venc">Vencimiento</label>
            <input id="venc" inputmode="numeric" autocomplete="cc-exp" maxlength="5" placeholder="MM/AA">
            <div class="ferr">Fecha inválida o vencida.</div>
          </div>
          <div class="field" id="f-cvv">
            <label for="cvv">Código de seguridad</label>
            <input id="cvv" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="123">
            <div class="ferr">Ingresá 3 o 4 dígitos.</div>
          </div>
        </div>
        <button class="btn btn-primary btn-block" id="btnPagar" type="submit">Pagar</button>
        <p class="secure">🔒 Pago simulado: no se procesa ninguna tarjeta real ni se guardan tus datos.</p>
      </form>

      <aside class="card" id="resumen"></aside>
    </div>
  </div>

  <!-- PROCESANDO -->
  <div class="view" id="view-proc">
    <div class="state ok"><span class="spinner" style="width:36px;height:36px;border-width:4px;margin-bottom:16px"></span>
      <h2>Procesando tu pago...</h2><p style="color:var(--muted)">No cierres ni recargues esta página.</p></div>
  </div>

  <!-- ÉXITO -->
  <div class="view" id="view-ok">
    <div class="state ok">
      <div class="circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg></div>
      <h2>¡Pago exitoso! Tu reserva está confirmada</h2>
      <p id="okTxt" style="color:var(--muted)"></p>
      <div class="confirm-card" id="okCard"></div>
      <div class="actions"><a href="<?= site_url('mis-reservas') ?>" class="btn btn-primary">Ver mis reservas</a><a href="<?= site_url('catalogo') ?>" class="btn btn-ghost">Seguir buscando</a></div>
    </div>
  </div>

  <!-- RECHAZADO -->
  <div class="view" id="view-rech">
    <div class="state error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      <h2>Tu pago fue rechazado</h2>
      <p>El banco no aprobó la operación (por ejemplo, fondos insuficientes). No se te cobró nada. Probá con otra tarjeta.</p>
      <div class="actions"><button class="btn btn-dark" type="button" onclick="demo('form')">Probar con otra tarjeta</button><a href="<?= site_url('confirmacion') ?>" class="btn btn-ghost">Volver al resumen</a></div>
    </div>
  </div>

  <!-- ERROR -->
  <div class="view" id="view-err">
    <div class="state error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      <h2>No pudimos procesar el pago</h2>
      <p id="errTxt">Hubo un problema de conexión. No se te cobró nada. Intentá de nuevo.</p>
      <div class="actions"><button class="btn btn-dark" type="button" onclick="demo('form')">Reintentar</button></div>
    </div>
  </div>

  <!-- SIN RESERVA -->
  <div class="view" id="view-vacio">
    <div class="state empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg>
      <h2>No hay una reserva para pagar</h2>
      <p>Elegí una herramienta y un período, y confirmá el resumen para llegar hasta acá.</p>
      <a href="<?= site_url('catalogo') ?>" class="btn btn-primary">Ir al catálogo</a>
    </div>
  </div>

</div>

<script>

/* ===== Utilidades compartidas de Alquil-Ar (misma copia en cada pantalla) =====
   Los datos de TOOLS y las reservas de ejemplo los va a reemplazar el Backend. */
var AR = (function(){
  var SERVICIO = 0.10, JORNADA_HS = 8, MAX_DIAS = 30;
  var ICONS = {
    drill:'<path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.1-3.1a5 5 0 01-6.7 6.7L5 22l-2-2 9.1-9.1a5 5 0 016.7-6.7l-3.1 3.1z"/>',
    scaffold:'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/>',
    boat:'<path d="M2 18c2 2 4 2 6 0s4-2 6 0 4 2 6 0M4 15l14-8-2 8"/>',
    mower:'<circle cx="12" cy="15" r="5"/><path d="M12 10V4M8 4h8"/>',
    tent:'<path d="M3 20L12 4l9 16M8 20l4-8 4 8"/>',
    grinder:'<rect x="4" y="10" width="12" height="6" rx="1"/><path d="M16 12h4v2h-4"/>'
  };
  var TOOLS = [
    {id:1,icon:'drill',name:'Taladro percutor Bosch',cat:'Herramientas',price:1200,rating:4.8,count:120,verificado:true,owner:'Martín I.',cap:'Mandril de 13 mm',det:'Con maletín y set de mechas',desc:'Taladro percutor de 750 W, ideal para hormigón, ladrillo y madera. Incluye maletín y set de mechas.',ocupados:[4,8,15,22]},
    {id:2,icon:'scaffold',name:'Andamio 3 cuerpos',cat:'Construcción',price:3500,rating:4.9,count:54,verificado:true,owner:'Martín I.',cap:'150 kg por nivel',det:'3 cuerpos (6 m de altura)',desc:'Andamio tubular de 3 cuerpos. Ideal para pintura, mantenimiento de fachada o refacciones de altura media. Incluye ruedas con freno y base niveladora.',ocupados:[5,9,14,20,26]},
    {id:3,icon:'boat',name:'Bote a remo 2 plazas',cat:'Recreación',price:2800,rating:4.7,count:38,verificado:true,owner:'Lucía P.',cap:'2 personas',det:'Incluye remos y chalecos',desc:'Bote a remo para dos personas, perfecto para pasear por la laguna. Incluye remos y chalecos salvavidas.',ocupados:[6,7,13,21]},
    {id:4,icon:'mower',name:'Cortadora de césped',cat:'Jardín',price:1800,rating:4.6,count:92,verificado:false,owner:'Diego M.',cap:'Corte de 46 cm',det:'Motor a nafta',desc:'Cortadora de césped a nafta con bolsa recolectora. Se entrega con el tanque lleno.',ocupados:[3,10,17,24]},
    {id:5,icon:'tent',name:'Carpa para eventos 6x6',cat:'Eventos',price:5200,rating:5.0,count:21,verificado:true,owner:'Ana L.',cap:'Hasta 30 personas',det:'Incluye parantes y lona',desc:'Carpa de 6x6 metros para cumpleaños, ferias y eventos al aire libre. Incluye estructura, lona y estacas.',ocupados:[11,12,18,25,27]},
    {id:6,icon:'grinder',name:'Amoladora angular',cat:'Herramientas',price:900,rating:4.8,count:140,verificado:false,owner:'Diego M.',cap:'Disco de 115 mm',det:'Incluye 3 discos',desc:'Amoladora angular de 850 W con protector y empuñadura lateral. Incluye 3 discos de corte.',ocupados:[2,9,16,23,30]}
  ];
  function pad(n){ return (n<10?'0':'')+n; }
  function iso(d){ return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate()); }
  function parseISO(s){ var p=String(s||'').split('-'); if(p.length!==3) return new Date(NaN); return new Date(+p[0],+p[1]-1,+p[2]); }
  function hoyISO(){ return iso(new Date()); }
  function addDays(n){ var t=new Date(); return iso(new Date(t.getFullYear(),t.getMonth(),t.getDate()+n)); }
  function horas(h){ var p=String(h||'').split(':'); if(p.length<2||isNaN(+p[0])||isNaN(+p[1])) return NaN; return +p[0]+(+p[1])/60; }
  function fmt(n){ return '$'+Math.round(n).toLocaleString('es-AR'); }
  function fechaCorta(s){ return parseISO(s).toLocaleDateString('es-AR',{weekday:'short',day:'numeric',month:'short'}); }
  function tool(id){ for(var i=0;i<TOOLS.length;i++){ if(TOOLS[i].id===id) return TOOLS[i]; } return null; }
  function icon(n){ return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">'+(ICONS[n]||ICONS.drill)+'</svg>'; }
  function ocupado(t, s){ return t.ocupados.indexOf(parseISO(s).getDate())>-1; }
  function rangoOcupado(t, a, b){
    var d=parseISO(a), fin=parseISO(b);
    while(d<=fin){ if(ocupado(t, iso(d))) return true; d=new Date(d.getFullYear(),d.getMonth(),d.getDate()+1); }
    return false;
  }
  /* Regla de precio (la misma tiene que implementar el Backend):
     - 1 día: se cobra por hora = precio del día / 8 hs x horas, con tope del precio del día.
     - 2 días o más: precio del día x cantidad de días.
     - Tarifa de servicio: 10 % del subtotal. Todo redondeado al peso. Máximo 30 días. */
  function calcularPrecio(o){
    function err(m, campo, incompleto){ return {ok:false, error:m, campo:campo||null, incompleto:!!incompleto}; }
    if(!o || !(o.precioDia>0)) return err('No pudimos leer el precio de la herramienta.');
    if(!o.inicio) return err('Elegí la fecha de inicio.','inicio',true);
    if(!o.fin) return err('Elegí la fecha de fin (o tocá otra vez el mismo día).','fin',true);
    var a=parseISO(o.inicio), b=parseISO(o.fin);
    if(isNaN(a)||isNaN(b)) return err('Las fechas elegidas no son válidas.','inicio');
    if(b<a) return err('La fecha de fin no puede ser anterior a la de inicio.','fin');
    var dias=Math.round((b-a)/86400000)+1;
    if(dias>MAX_DIAS) return err('El alquiler máximo es de '+MAX_DIAS+' días.','fin');
    var r={ok:true,dias:dias,precioDia:o.precioDia};
    if(dias===1){
      if(!o.retiro||!o.devolucion) return err('Elegí la hora de retiro y la de devolución.','retiro',true);
      var h=horas(o.devolucion)-horas(o.retiro);
      if(isNaN(h)) return err('Los horarios elegidos no son válidos.','retiro');
      if(h<=0) return err('La hora de devolución tiene que ser posterior a la de retiro.','devolucion');
      r.tipo='horas'; r.cantidad=h; r.tarifa=o.precioDia/JORNADA_HS;
      var bruto=Math.round(r.tarifa*h);
      r.tope=bruto>o.precioDia; r.subtotal=Math.min(bruto,o.precioDia);
    } else {
      r.tipo='dias'; r.cantidad=dias; r.tarifa=o.precioDia; r.tope=false; r.subtotal=o.precioDia*dias;
    }
    r.servicio=Math.round(r.subtotal*SERVICIO);
    r.total=r.subtotal+r.servicio;
    return r;
  }
  function formula(r){ /* texto de la fórmula para mostrar en pantalla */
    if(r.tipo==='dias') return fmt(r.tarifa)+' por día × '+r.cantidad+(r.cantidad===1?' día':' días');
    var hs=(Math.round(r.cantidad*10)/10).toString().replace('.',',');
    return fmt(r.tarifa)+' por hora × '+hs+(r.cantidad===1?' hora':' horas');
  }
  function ls(k,v){
    try{
      if(v===undefined){ var x=localStorage.getItem(k); return x?JSON.parse(x):null; }
      if(v===null) localStorage.removeItem(k); else localStorage.setItem(k,JSON.stringify(v));
      return true;
    }catch(e){ return v===undefined?null:false; }
  }
  function rol(){ try{ return localStorage.getItem('alquilar_rol'); }catch(e){ return null; } }
  function sembrar(tipo){
    var items = tipo==='reservas' ? [
      {id:'A-10452',toolId:1,owner:'Martín I.',inicio:addDays(3),fin:addDays(3),retiro:'09:00',devolucion:'13:00',estado:'confirmada'},
      {id:'A-10417',toolId:3,owner:'Lucía P.',inicio:addDays(-1),fin:addDays(1),retiro:'10:00',devolucion:'18:00',estado:'retirada'},
      {id:'A-10211',toolId:4,owner:'Diego M.',inicio:addDays(-20),fin:addDays(-20),retiro:'09:00',devolucion:'17:00',estado:'devuelta'},
      {id:'A-10105',toolId:6,owner:'Diego M.',inicio:addDays(-35),fin:addDays(-34),retiro:'09:00',devolucion:'18:00',estado:'devuelta'},
      {id:'A-10190',toolId:5,owner:'Ana L.',inicio:addDays(10),fin:addDays(10),retiro:'08:00',devolucion:'20:00',estado:'cancelada'}
    ] : [
      {id:'A-10452',toolId:2,cliente:'Sofía D.',tel:'+54 9 11 2345-6789',inicio:addDays(2),fin:addDays(3),retiro:'09:00',devolucion:'18:00',estado:'confirmada'},
      {id:'A-10388',toolId:1,cliente:'Camila R.',tel:'+54 9 11 5555-0101',inicio:addDays(-1),fin:addDays(-1),retiro:'09:00',devolucion:'13:00',estado:'retirada'},
      {id:'A-10301',toolId:2,cliente:'Diego M.',tel:'+54 9 11 4444-0202',inicio:addDays(-12),fin:addDays(-10),retiro:'08:00',devolucion:'18:00',estado:'devuelta'},
      {id:'A-10277',toolId:1,cliente:'Ana L.',tel:'+54 9 11 3333-0303',inicio:addDays(6),fin:addDays(6),retiro:'14:00',devolucion:'18:00',estado:'cancelada'}
    ];
    return items.map(function(x){
      var t=tool(x.toolId); x.tool=t.name; x.icon=t.icon; x.precioDia=t.price;
      var c=calcularPrecio({precioDia:t.price,inicio:x.inicio,fin:x.fin,retiro:x.retiro,devolucion:x.devolucion});
      x.subtotal=c.subtotal; x.servicio=c.servicio; x.total=c.total; return x;
    });
  }
  function lista(tipo){
    var k = tipo==='reservas' ? 'alquilar_reservas' : 'alquilar_alquileres';
    var l = ls(k);
    if(!l){ l = sembrar(tipo); ls(k,l); }
    return l;
  }
  function guardar(tipo,l){ return ls(tipo==='reservas'?'alquilar_reservas':'alquilar_alquileres', l); }
  var ESTADOS = {confirmada:['Confirmada','ok'],retirada:['En uso','warn'],devuelta:['Devuelta','gray'],cancelada:['Cancelada','bad']};
  return {TOOLS:TOOLS,tool:tool,icon:icon,iso:iso,parseISO:parseISO,hoyISO:hoyISO,fmt:fmt,fechaCorta:fechaCorta,
          ocupado:ocupado,rangoOcupado:rangoOcupado,calcularPrecio:calcularPrecio,formula:formula,
          ls:ls,rol:rol,lista:lista,guardar:guardar,ESTADOS:ESTADOS,SERVICIO:SERVICIO,JORNADA_HS:JORNADA_HS,MAX_DIAS:MAX_DIAS};
})();

(function(){
  function $(i){ return document.getElementById(i); }
  window.demo = function(n){
    document.querySelectorAll('.view').forEach(function(v){ v.classList.remove('on'); });
    $('view-' + n).classList.add('on');
    if(n === 'ok' && !$('okCard').innerHTML) pintarExito(ejemplo());
    if(n === 'form'){ $('btnPagar').disabled = false; }
  };

  var data = AR.ls('alquilar_reserva');
  var tool = data ? AR.tool(data.toolId) : null;
  var r = null;
  if(tool){
    r = AR.calcularPrecio({precioDia: tool.price, inicio: data.inicio, fin: data.fin, retiro: data.retiro, devolucion: data.devolucion});
    if(!r.ok || r.total !== data.total){ r = null; }
  }
  if(!tool || !r){ $('backTop').href = <?= json_encode(site_url('catalogo')) ?>; $('backTop').textContent = '← Volver al catálogo'; demo('vacio'); } else {
    $('btnPagar').textContent = 'Pagar ' + AR.fmt(r.total);
    $('resumen').innerHTML =
      '<h3 style="margin:0 0 12px;font-size:15px">Resumen de tu reserva</h3>' +
      '<div class="tool-line"><div class="ic">' + AR.icon(tool.icon) + '</div><div><b>' + tool.name + '</b><small>Publicado por ' + tool.owner + '</small></div></div>' +
      '<div class="sum-row"><span>Fechas</span><span>' + (data.inicio === data.fin ? AR.fechaCorta(data.inicio) : AR.fechaCorta(data.inicio) + ' al ' + AR.fechaCorta(data.fin)) + '</span></div>' +
      '<div class="sum-row"><span>Retiro / devolución</span><span>' + data.retiro + ' / ' + data.devolucion + '</span></div>' +
      '<div class="sum-row" style="margin-top:8px"><span>' + AR.formula(r) + '</span><span>' + AR.fmt(r.subtotal) + '</span></div>' +
      '<div class="sum-row"><span>Tarifa de servicio (' + Math.round(AR.SERVICIO * 100) + '%)</span><span>' + AR.fmt(r.servicio) + '</span></div>' +
      '<div class="sum-row total"><span>Total a pagar</span><span>' + AR.fmt(r.total) + '</span></div>';
  }

  // ---- formato de los campos
  $('numero').addEventListener('input', function(){
    var d = this.value.replace(/\D/g, '').slice(0, 19);
    this.value = d.replace(/(.{4})/g, '$1 ').trim();
  });
  $('venc').addEventListener('input', function(){
    var d = this.value.replace(/\D/g, '').slice(0, 4);
    this.value = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
  });
  $('cvv').addEventListener('input', function(){ this.value = this.value.replace(/\D/g, '').slice(0, 4); });
  ['titular','numero','venc','cvv'].forEach(function(i){ $(i).addEventListener('input', function(){ $('f-' + i).classList.remove('bad'); }); });

  // ---- validaciones
  function luhn(n){ var s = 0, dbl = false; for(var i = n.length - 1; i >= 0; i--){ var x = +n.charAt(i); if(dbl){ x *= 2; if(x > 9) x -= 9; } s += x; dbl = !dbl; } return n.length >= 13 && s % 10 === 0; }
  function vencOk(v){
    var m = /^(\d{2})\/(\d{2})$/.exec(v); if(!m) return false;
    var mes = +m[1], anio = 2000 + (+m[2]); if(mes < 1 || mes > 12) return false;
    return new Date(anio, mes, 1) > new Date();      // vale hasta el último día del mes indicado
  }
  function validar(){
    var num = $('numero').value.replace(/\s/g, '');
    var chk = {titular: $('titular').value.trim().length >= 3, numero: luhn(num), venc: vencOk($('venc').value), cvv: /^\d{3,4}$/.test($('cvv').value)};
    var ok = true;
    Object.keys(chk).forEach(function(k){ $('f-' + k).classList.toggle('bad', !chk[k]); if(!chk[k]) ok = false; });
    return ok ? num : null;
  }

  function ejemplo(){
    return {id: 'A-10999', tool: 'Andamio 3 cuerpos', owner: 'Martín I.', inicio: AR.hoyISO(), fin: AR.hoyISO(), retiro: '09:00', devolucion: '13:00', total: 1925};
  }
  function pintarExito(rec){
    $('okTxt').textContent = rec.owner + ' va a coordinar el retiro con vos. Te enviamos los detalles a tu email.';
    $('okCard').innerHTML =
      '<div class="confirm-row"><span>Herramienta</span><b>' + rec.tool + '</b></div>' +
      '<div class="confirm-row"><span>Fechas</span><b>' + (rec.inicio === rec.fin ? AR.fechaCorta(rec.inicio) : AR.fechaCorta(rec.inicio) + ' al ' + AR.fechaCorta(rec.fin)) + '</b></div>' +
      '<div class="confirm-row"><span>Horarios</span><b>' + rec.retiro + ' a ' + rec.devolucion + '</b></div>' +
      '<div class="confirm-row"><span>Total pagado</span><b>' + AR.fmt(rec.total) + '</b></div>' +
      '<div class="confirm-row"><span>N° de reserva</span><b>#' + rec.id + '</b></div>';
  }

  var pagando = false;
  $('payForm').addEventListener('submit', function(e){
    e.preventDefault();
    if(pagando || !tool || !r) return;
    var num = validar(); if(!num) return;
    pagando = true; $('btnPagar').disabled = true; demo('proc');
    setTimeout(function(){          // acá el Backend procesa el pago y responde aprobado / rechazado
      pagando = false;
      var rechazada = (num === '4000000000000002');
      ['numero','venc','cvv'].forEach(function(i){ $(i).value = ''; });   // nunca se guardan datos de tarjeta
      if(rechazada){ demo('rech'); return; }
      if(navigator.onLine === false){ $('errTxt').textContent = 'No hay conexión a internet. No se te cobró nada. Intentá de nuevo.'; demo('err'); return; }
      var rec = {id: 'A-' + (10000 + Math.floor(Math.random() * 89999)), toolId: tool.id, tool: tool.name, icon: tool.icon, owner: tool.owner,
                 precioDia: tool.price, inicio: data.inicio, fin: data.fin, retiro: data.retiro, devolucion: data.devolucion,
                 subtotal: r.subtotal, servicio: r.servicio, total: r.total, estado: 'confirmada'};
      var lista = AR.lista('reservas'); lista.unshift(rec);
      if(!AR.guardar('reservas', lista)){ $('errTxt').textContent = 'El pago se aprobó pero no pudimos guardar tu reserva en este navegador. Avisanos así la confirmamos a mano.'; demo('err'); return; }
      AR.ls('alquilar_reserva', null);          // evita pagar dos veces la misma reserva
      pintarExito(rec); demo('ok');
    }, 1800);
  });
})();
</script>
<?php endif ?>

</body>
</html>
