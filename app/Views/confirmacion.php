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

.nf-lite a{color:var(--teal-600);font-weight:600}
.btn-confirm,.btn-back{text-decoration:none}

.summary-box{display:block}
.summary-box.on{display:block}

/* ===== Semana 10 ===== */
body:not(.demo) .dev-toolbar{display:none}   /* la barra de prueba solo se ve agregando ?demo a la dirección */
@media (max-width:640px){.nav{padding:14px 16px}.wrap{padding:24px 16px 48px}.card{padding:16px}}
</style>
</head>
<body>
<script>if(/[?&]demo\b/.test(location.search)) document.body.classList.add('demo');</script>

<div class="nav">
  <div class="logo">Alquil<span>-Ar</span></div>
  <a href="detalle" class="back" id="backLink">← Volver / modificar</a>
</div>

<?php if (! session('isLoggedIn')) : ?>
<!-- Sin sesión no se muestra la reserva. BACKEND: el Controller también tiene que exigir sesión. -->
<div class="wrap">
  <div class="error-view" style="padding:40px 10px;">
    <h2 style="font-size:19px;margin:0 0 6px;color:var(--ink);">Iniciá sesión para confirmar tu reserva</h2>
    <p style="color:var(--muted);font-size:13px;margin:0 0 20px;">Tus fechas quedan guardadas. Cuando ingreses, volvé a esta página para seguir.</p>
    <a href="login" class="btn btn-confirm">Iniciar sesión</a>
  </div>
</div>
<?php else : ?>
<div class="wrap">

  <!-- Barra solo para esta demo: simula los estados sin backend -->
  <div class="dev-toolbar">
    🔧 Vista previa de estados:
    <button onclick="showView('resumen')">Resumen</button>
    <button onclick="showView('error')">Error</button>
    <button onclick="showView('vacio')">Sin reserva</button>
  </div>

  <!-- ESTADO: Resumen (antes de confirmar) -->
  <div class="view on" id="view-resumen">
    <h1 class="title">Confirmá tu reserva</h1>

    <div class="card">
      <div class="tool-row">
        <div class="tool-media" id="cIcon"></div>
        <div>
          <div class="tool-name" id="cName">—</div>
          <div class="tool-owner" id="cOwner"></div>
        </div>
      </div>
      <div class="detail-rows">
        <div class="detail-row"><span>Fecha de inicio</span><span id="cInicio">—</span></div>
        <div class="detail-row"><span>Fecha de fin</span><span id="cFin">—</span></div>
        <div class="detail-row"><span>Hora de retiro</span><span id="cRetiro">—</span></div>
        <div class="detail-row"><span>Hora de devolución</span><span id="cDevolucion">—</span></div>
      </div>
    </div>

    <div class="card">
      <h3 style="font-size:13.5px; margin:0 0 10px;">Precio estimado</h3>
      <div class="summary-box" id="priceBox"></div>
    </div>

    <button class="btn btn-confirm" id="btnConfirmar" type="button">
      <span class="spinner" id="spinner"></span>
      <span id="btnText">Confirmar y pagar</span>
    </button>
    <a href="detalle" class="btn btn-back" id="btnVolver">Volver y modificar</a>
  </div>

  <!-- ESTADO: Sin reserva en curso -->
  <div class="view" id="view-vacio">
    <div class="error-view" style="padding:40px 10px;">
      <h2 style="font-size:19px;margin:0 0 6px;">No hay una reserva en curso</h2>
      <p style="color:var(--muted);font-size:13px;margin:0 0 20px;">Elegí una herramienta y un período para ver el resumen acá.</p>
      <a href="catalogo" class="btn btn-confirm">Ir al catálogo</a>
    </div>
  </div>

  <!-- ESTADO: Error -->
  <div class="view" id="view-error">
    <div class="error-view">
      <div class="error-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
      </div>
      <h2>No pudimos confirmar tu reserva</h2>
      <p id="errMsg">Hubo un problema. Probá de nuevo.</p>
      <a href="detalle" class="btn btn-confirm" id="btnErrVolver">Volver y elegir otro período</a>
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
  window.showView = function(n){
    document.querySelectorAll('.view').forEach(function(v){ v.classList.remove('on'); });
    $('view-' + n).classList.add('on');
  };
  function falla(msg){ $('errMsg').textContent = msg; showView('error'); }

  var data = AR.ls('alquilar_reserva');
  var tool = data ? AR.tool(data.toolId) : null;
  if(!data || !tool){ showView('vacio'); return; }

  var back = 'detalle?id=' + tool.id;
  $('backLink').href = back; $('btnVolver').href = back; $('btnErrVolver').href = back;
  $('cIcon').innerHTML = AR.icon(tool.icon);
  $('cName').textContent = tool.name;
  $('cOwner').textContent = 'Publicado por ' + tool.owner;
  $('cInicio').textContent = AR.fechaCorta(data.inicio);
  $('cFin').textContent = AR.fechaCorta(data.fin);
  $('cRetiro').textContent = data.retiro;
  $('cDevolucion').textContent = data.devolucion;

  // Se vuelve a calcular el precio con la misma función que usó el detalle: si no coincide, se avisa.
  var r = AR.calcularPrecio({precioDia: tool.price, inicio: data.inicio, fin: data.fin, retiro: data.retiro, devolucion: data.devolucion});
  if(!r.ok || r.total !== data.total){
    falla('No pudimos verificar el precio de tu reserva. Volvé a elegir tus fechas.'); return;
  }
  var html = '<div class="summary-row"><span>' + AR.formula(r) + '</span><span>' + AR.fmt(r.subtotal) + '</span></div>' +
             '<div class="summary-row"><span>Tarifa de servicio (' + Math.round(AR.SERVICIO * 100) + '%)</span><span>' + AR.fmt(r.servicio) + '</span></div>' +
             '<div class="summary-row total"><span>Total</span><span>' + AR.fmt(r.total) + '</span></div>';
  var box = $('priceBox'); box.className = 'summary-box on'; box.innerHTML = html;

  $('btnConfirmar').addEventListener('click', function(){
    var b = this; b.disabled = true; $('spinner').style.display = 'inline-block'; $('btnText').textContent = 'Verificando disponibilidad...';
    setTimeout(function(){   // acá el Backend va a confirmar que las fechas siguen libres
      if(AR.rangoOcupado(tool, data.inicio, data.fin)){
        b.disabled = false; $('spinner').style.display = 'none'; $('btnText').textContent = 'Confirmar y pagar';
        falla('Esas fechas ya no están disponibles. Volvé a elegir otro período.'); return;
      }
      location.href = 'pago';
    }, 800);
  });
})();
</script>
<?php endif ?>

</body>
</html>
