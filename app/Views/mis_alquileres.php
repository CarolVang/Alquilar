<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alquil-Ar — Mis alquileres</title>
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
a.mini{text-decoration:none;display:inline-block}
.mini.dark{background:var(--teal-900);color:#fff}
.rows .row .meta b{color:var(--ink)}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.stat{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px 16px}
.stat .l{font-size:12px;color:var(--muted);margin-bottom:4px}
.stat .v{font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:var(--teal-900)}
.toast-ar{position:fixed;left:50%;bottom:24px;transform:translateX(-50%);background:var(--teal-900);color:#fff;padding:11px 18px;border-radius:9px;font-size:13px;opacity:0;pointer-events:none;transition:opacity .2s;z-index:5;max-width:90vw;text-align:center}
.toast-ar.on{opacity:1}.toast-ar.bad{background:var(--danger)}
.priv{font-size:11px;color:var(--muted);font-style:italic}
@media (max-width:640px){.stats{grid-template-columns:1fr}}

/* ===== Semana 10 ===== */
body:not(.demo) .dev-toolbar{display:none}   /* la barra de prueba solo se ve agregando ?demo a la dirección */
@media (max-width:640px){.row .side{flex-wrap:wrap;gap:8px}}
</style>
</head>
<body>
<script>if(/[?&]demo\b/.test(location.search)) document.body.classList.add('demo');</script>

<?= view('partials/nav', ['activo' => 'mis-alquileres']) ?>

<?php if (! session('isLoggedIn')) : ?>
<!-- Los datos de los clientes (nombre, teléfono) son privados: sin sesión no se muestran. BACKEND: el Controller también tiene que exigir sesión. -->
<div class="wrap">
  <div class="state empty">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
    <h2>Iniciá sesión para ver tus alquileres</h2>
    <p>Los datos de tus clientes son privados: solo los ves vos con tu cuenta.</p>
    <a href="<?= site_url('login') ?>" class="btn btn-primary">Iniciar sesión</a>
  </div>
</div>
<?php else : ?>
<div class="wrap">

  <!-- Barra solo para esta demo: simula los estados sin backend -->
  <div class="dev-toolbar">
    🔧 Vista previa de estados:
    <button onclick="vista('cargando')">Cargando</button>
    <button onclick="vista('datos')">Con datos</button>
    <button onclick="vista('vacio')">Vacío</button>
    <button onclick="vista('error')">Error</button>
  </div>

  <h1 class="page-title">Mis alquileres</h1>
  <p class="page-sub">Quién alquiló tus herramientas, cuándo y en qué estado está cada alquiler.</p>

  <div class="stats" id="stats"></div>
  <div class="tabs" id="tabs"></div>
  <div id="contenido" aria-live="polite"></div>
</div>
<div class="toast-ar" id="toast"></div>

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

var URL_DETALLE = <?= json_encode(site_url('detalle')) ?>;
function $(i){ return document.getElementById(i); }
function esc(s){ return String(s == null ? '' : s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
function fechas(r){ return r.inicio === r.fin ? AR.fechaCorta(r.inicio) : AR.fechaCorta(r.inicio) + ' al ' + AR.fechaCorta(r.fin); }
function iconoDe(r){ var t = AR.tool(r.toolId); return AR.icon(r.icon || (t ? t.icon : 'drill')); }
function pill(estado){ var e = AR.ESTADOS[estado] || [estado, 'gray']; return '<span class="pill ' + e[1] + '">' + esc(e[0]) + '</span>'; }
var toastT = null;
function aviso(msg, malo){ var t = $('toast'); t.textContent = msg; t.className = 'toast-ar on' + (malo ? ' bad' : ''); clearTimeout(toastT); toastT = setTimeout(function(){ t.className = 'toast-ar'; }, 2800); }
var SKEL = '<div class="rows">' + [1,2,3].map(function(){ return '<div class="row"><div class="ic skeleton"></div><div class="main"><div class="skeleton" style="height:13px;width:55%;margin-bottom:8px"></div><div class="skeleton" style="height:10px;width:35%"></div></div></div>'; }).join('') + '</div>';
function vacioHTML(titulo, texto, cta){ return '<div class="state empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v6M16 4v6"/></svg><h2>' + titulo + '</h2><p>' + texto + '</p>' + (cta ? '<a href="' + cta[0] + '" class="btn btn-primary">' + cta[1] + '</a>' : '') + '</div>'; }
function errorHTML(titulo){ return '<div class="state error"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg><h2>' + titulo + '</h2><p>Hubo un problema de conexión. Intentá de nuevo en unos segundos.</p><button class="btn btn-dark" type="button" onclick="vista(\'datos\')">Reintentar</button></div>'; }

(function(){
  var TABS = [{k:'todos', t:'Todos', e:null}, {k:'retirar', t:'Por retirar', e:['confirmada']}, {k:'uso', t:'En uso', e:['retirada']},
              {k:'fin', t:'Finalizados', e:['devuelta']}, {k:'canc', t:'Cancelados', e:['cancelada']}];
  var VACIO = {
    todos: ['Todavía no te alquilaron nada', 'Cuando alguien alquile una de tus herramientas, lo vas a ver acá.', [<?= json_encode(site_url('publicar-herramienta')) ?>, 'Publicar una herramienta']],
    retirar: ['No tenés alquileres por retirar', 'Los alquileres confirmados que todavía no se retiraron aparecen acá.', null],
    uso: ['No tenés herramientas en uso', 'Las herramientas que ya retiraron y todavía no devolvieron aparecen acá.', null],
    fin: ['Todavía no hay alquileres finalizados', 'Cuando te devuelvan una herramienta, el alquiler queda acá.', null],
    canc: ['No hay alquileres cancelados', 'Si un cliente cancela una reserva, la vas a ver acá.', null]
  };
  var SIGUIENTE = {confirmada: ['retirada', 'Marcar como retirada'], retirada: ['devuelta', 'Marcar como devuelta']};   // única secuencia válida
  var estado = 'datos', tab = 'todos', vacio = false;

  // BACKEND: acá hay que pedirle al servidor los alquileres de las herramientas del usuario que inició sesión.
  function datos(){ return vacio ? [] : AR.lista('alquileres'); }

  window.vista = function(v){ vacio = (v === 'vacio'); estado = (v === 'cargando' || v === 'error') ? v : 'datos'; render(); };

  function fila(r){
    var activo = (r.estado === 'confirmada' || r.estado === 'retirada');
    var contacto = activo ? 'Tel. ' + esc(r.tel) : '<span class="priv">Contacto disponible solo mientras el alquiler está activo</span>';
    var accion = SIGUIENTE[r.estado] ? '<button class="mini dark" type="button" data-id="' + esc(r.id) + '">' + SIGUIENTE[r.estado][1] + '</button>' : '';
    return '<div class="row"><div class="ic">' + iconoDe(r) + '</div><div class="main"><h3>' + esc(r.tool) + '</h3>' +
      '<div class="meta">Cliente: <b>' + esc(r.cliente) + '</b> · N° <b>#' + esc(r.id) + '</b><br>' + contacto + '<br>' + fechas(r) + ' · ' + esc(r.retiro) + ' a ' + esc(r.devolucion) + '</div></div>' +
      '<div class="side">' + pill(r.estado) + '<div class="total">' + AR.fmt(r.subtotal) + '</div><div class="meta" style="font-size:10.5px">tu ingreso (sin tarifa de servicio)</div><div>' + accion + '</div></div></div>';
  }

  function render(){
    var box = $('contenido'), tabsEl = $('tabs'), stats = $('stats');
    if(estado === 'cargando'){ stats.innerHTML = ''; tabsEl.innerHTML = ''; box.innerHTML = SKEL; return; }
    if(estado === 'error'){ stats.innerHTML = ''; tabsEl.innerHTML = ''; box.innerHTML = errorHTML('No pudimos cargar tus alquileres'); return; }
    var l = datos();
    var activos = l.filter(function(r){ return r.estado === 'confirmada' || r.estado === 'retirada'; }).length;
    var porRetirar = l.filter(function(r){ return r.estado === 'confirmada'; }).length;
    var ingresos = l.filter(function(r){ return r.estado !== 'cancelada'; }).reduce(function(s, r){ return s + r.subtotal; }, 0);
    stats.innerHTML = '<div class="stat"><div class="l">Alquileres activos</div><div class="v">' + activos + '</div></div>' +
                      '<div class="stat"><div class="l">Por retirar</div><div class="v">' + porRetirar + '</div></div>' +
                      '<div class="stat"><div class="l">Ingresos (sin tarifa de servicio)</div><div class="v">' + AR.fmt(ingresos) + '</div></div>';
    tabsEl.innerHTML = TABS.map(function(t){
      var n = t.e ? l.filter(function(r){ return t.e.indexOf(r.estado) > -1; }).length : l.length;
      return '<button class="tab' + (t.k === tab ? ' on' : '') + '" type="button" data-tab="' + t.k + '">' + t.t + '<small>' + n + '</small></button>';
    }).join('');
    var cur = TABS.filter(function(t){ return t.k === tab; })[0];
    var items = cur.e ? l.filter(function(r){ return cur.e.indexOf(r.estado) > -1; }) : l;
    if(!items.length){ var v = VACIO[tab]; box.innerHTML = vacioHTML(v[0], v[1], v[2]); return; }
    box.innerHTML = '<div class="rows">' + items.map(fila).join('') + '</div>';
  }

  $('tabs').addEventListener('click', function(e){ var b = e.target.closest('[data-tab]'); if(!b) return; tab = b.getAttribute('data-tab'); render(); });
  $('contenido').addEventListener('click', function(e){
    var b = e.target.closest('[data-id]'); if(!b) return;
    var id = b.getAttribute('data-id'), l = AR.lista('alquileres'), cambio = false;
    l.forEach(function(r){ if(r.id === id && SIGUIENTE[r.estado]){ r.estado = SIGUIENTE[r.estado][0]; cambio = true; } });   // BACKEND: validar la transición
    if(!cambio) return;
    if(!AR.guardar('alquileres', l)){ aviso('No pudimos actualizar el alquiler. Intentá de nuevo.', true); return; }
    aviso('Alquiler actualizado.'); render();
  });

  render();
})();
</script>
<?php endif ?>

</body>
</html>
