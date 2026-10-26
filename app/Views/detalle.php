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

  .back-link{ display:inline-block; color:var(--teal-600); font-size:13px; font-weight:600; text-decoration:none; }
  .back-row{ max-width:1180px; margin:0 auto; padding:22px 24px 0; }

  .detail-wrap{ display:grid; grid-template-columns:minmax(0,1.15fr) minmax(0,0.85fr); gap:32px; max-width:1180px; margin:0 auto; padding:16px 24px 60px; }

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
  .price-line span:not(#dPrice){ font-size:11.5px; font-weight:500; color:var(--muted); font-family:'Inter',sans-serif; }

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

/* ===== Semana 7: calendario real + precio claro ===== */
.cal-head{display:flex;align-items:center;justify-content:space-between;margin:2px 0 8px}
.cal-head b{font-size:12.5px}
.cal-head button{border:1px solid var(--line);background:#fff;border-radius:6px;width:28px;height:28px;cursor:pointer;font-size:14px}
.cal-head button:disabled{opacity:.35;cursor:default}
.cal-day.past{color:#c4c9c4;background:#f6f7f6;cursor:not-allowed}
.cal-day.inrange{background:var(--teal-100);border-color:transparent}
.range-hint{font-size:11.5px;color:var(--muted);margin:-6px 0 14px;min-height:16px}
.range-hint.warn{color:var(--danger);font-weight:600}
.summary-box{display:block}
.summary-box.hint{background:var(--teal-50);color:var(--muted);font-size:12px}
.summary-box.err{background:var(--danger-bg);color:var(--danger);font-size:12px;font-weight:600}
.summary-note{font-size:11px;color:var(--muted);margin:-1px 0 4px}
.how{font-size:12px;color:var(--muted);margin:0 0 14px}
.how summary{cursor:pointer;color:var(--teal-600);font-weight:600}
.how ul{margin:8px 0 0;padding-left:18px;line-height:1.6}
.nf{max-width:480px;margin:70px auto;text-align:center;padding:0 20px}
.nf h2{font-size:18px}.nf p{color:var(--muted);font-size:13px}
.nf a{display:inline-block;margin-top:10px;background:var(--teal-900);color:#fff;padding:11px 20px;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none}
@media (max-width:560px){.form-row{flex-direction:column;gap:0}}

/* ===== Semana 10: responsive ===== */
@media (max-width:640px){
  .back-row{padding:16px 16px 0}
  .detail-wrap{padding:12px 16px 48px;gap:22px}
  .gallery-main{height:200px}
  .booking-card{padding:18px 16px 20px}
}
</style>
</head>
<body>

<?= view('partials/nav', ['activo' => 'catalogo']) ?>

<div class="back-row"><a href="<?= site_url('catalogo') ?>" class="back-link">← Volver al catálogo</a></div>

<div class="nf" id="notFound" style="display:none">
  <h2>No encontramos esa herramienta</h2>
  <p>Puede que ya no esté publicada o que el link esté incompleto.</p>
  <a href="<?= site_url('catalogo') ?>">Volver al catálogo</a>
</div>

<div class="detail-wrap" id="detalleWrap">

  <div class="detail-info">
    <div class="gallery-main" id="dGallery"></div>
    <div class="gallery-thumbs"><div></div><div></div><div></div><div></div></div>

    <div class="badge-verified" id="dBadge"></div>
    <h1 id="dName"></h1>
    <div class="rating" id="dRating"></div>
    <div class="owner-row"><div class="owner-avatar" id="dOwnerAv"></div><span>Publicado por <b id="dOwner" style="color:var(--ink)"></b> · Responde en ~1h</span></div>

    <p class="desc" id="dDesc"></p>

    <div class="spec-grid">
      <div class="spec">Categoría<b id="dCat"></b></div>
      <div class="spec">Capacidad<b id="dCap"></b></div>
      <div class="spec">Detalle<b id="dDet"></b></div>
      <div class="spec">Ubicación<b>A 2,3 km tuyo</b></div>
    </div>
  </div>

  <div class="booking-card">
    <h3>Elegí cuándo alquilarlo</h3>
    <div class="price-line"><span id="dPrice"></span> <span>/día</span></div>

    <div class="avail-legend">
      <span><i style="background:#fff; border:1px solid var(--line);"></i>Disponible</span>
      <span><i style="background:var(--danger-bg); border:1px solid var(--danger);"></i>Ocupado</span>
      <span><i style="background:var(--teal-900);"></i>Seleccionado</span>
    </div>
    <div class="cal-head"><button type="button" id="prevM" aria-label="Mes anterior">‹</button><b id="monthLabel"></b><button type="button" id="nextM" aria-label="Mes siguiente">›</button></div>
    <div class="cal-grid" id="calGrid"></div>
    <div class="range-hint" id="rangeHint"></div>

    <div class="form-row">
      <div class="field-group" id="group-fechaInicio">
        <label class="field-label">Fecha de inicio</label>
        <input class="field-input" type="text" id="fechaInicio" placeholder="Elegí un día" readonly>
        <div class="field-error">Elegí una fecha de inicio.</div>
      </div>
      <div class="field-group" id="group-fechaFin">
        <label class="field-label">Fecha de fin</label>
        <input class="field-input" type="text" id="fechaFin" placeholder="Elegí un día" readonly>
        <div class="field-error">Elegí una fecha de fin.</div>
      </div>
    </div>

    <div class="form-row">
      <div class="field-group" id="group-horaRetiro">
        <label class="field-label">Hora de retiro</label>
        <select class="field-input" id="horaRetiro"><option value="">Elegí un horario</option></select>
        <div class="field-error">Elegí una hora de retiro.</div>
      </div>
      <div class="field-group" id="group-horaDevolucion">
        <label class="field-label">Hora de devolución</label>
        <select class="field-input" id="horaDevolucion"><option value="">Elegí un horario</option></select>
        <div class="field-error">La devolución tiene que ser después del retiro.</div>
      </div>
    </div>

    <div class="summary-box" id="summaryBox" aria-live="polite"></div>

    <details class="how">
      <summary>¿Cómo se calcula el precio?</summary>
      <ul>
        <li>Si alquilás <b>un solo día</b>, pagás por hora: el precio del día ÷ 8 horas × las horas que elegiste, sin pasar nunca el precio del día completo.</li>
        <li>Si alquilás <b>dos días o más</b>, pagás el precio del día × la cantidad de días.</li>
        <li>Se suma una <b>tarifa de servicio del 10%</b> sobre ese subtotal.</li>
        <li>Los montos se redondean al peso. El máximo es de 30 días.</li>
      </ul>
    </details>

    <button class="reservar-btn" id="btnReservar" type="button">Reservar</button>
    <div class="toast" id="toastError"></div>
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
  var q = new URLSearchParams(location.search);
  var id = q.has('id') ? parseInt(q.get('id'), 10) : 2;   // sin id se muestra el andamio (como en la demo anterior)
  var tool = AR.tool(id);
  if(!tool){ $('detalleWrap').style.display = 'none'; $('notFound').style.display = 'block'; return; }

  // ---- datos de la herramienta
  document.title = 'Alquil-Ar — ' + tool.name;
  $('dGallery').innerHTML = AR.icon(tool.icon);
  $('dBadge').textContent = tool.verificado ? '✓ Retador verificado' : 'Retador sin verificar';
  $('dName').textContent = tool.name;
  var est = ''; for(var s = 0; s < 5; s++){ est += s < Math.round(tool.rating) ? '★' : '☆'; }
  $('dRating').innerHTML = est + ' <b>' + tool.rating.toFixed(1) + '</b> · ' + tool.count + ' alquileres';
  $('dOwner').textContent = tool.owner;
  $('dOwnerAv').textContent = tool.owner.split(' ').map(function(w){ return w.charAt(0); }).join('');
  $('dDesc').textContent = tool.desc;
  $('dCat').textContent = tool.cat; $('dCap').textContent = tool.cap; $('dDet').textContent = tool.det;
  $('dPrice').textContent = AR.fmt(tool.price);

  // ---- horarios
  var opts = ''; for(var h = 8; h <= 20; h++){ var t = (h < 10 ? '0' : '') + h + ':00'; opts += '<option>' + t + '</option>'; }
  $('horaRetiro').insertAdjacentHTML('beforeend', opts);
  $('horaDevolucion').insertAdjacentHTML('beforeend', opts);

  // ---- estado de la selección (si vuelve desde "modificar", se recupera)
  var hoy = AR.hoyISO(), ahora = new Date();
  var min = {y: ahora.getFullYear(), m: ahora.getMonth()};
  var cur = {y: min.y, m: min.m};
  var sel = {inicio: null, fin: null};
  var aviso = '';
  var prev = AR.ls('alquilar_reserva');
  if(prev && prev.toolId === tool.id && prev.inicio >= hoy){
    sel = {inicio: prev.inicio, fin: prev.fin};
    $('horaRetiro').value = prev.retiro; $('horaDevolucion').value = prev.devolucion;
    var pd = AR.parseISO(prev.inicio); cur = {y: pd.getFullYear(), m: pd.getMonth()};
  }

  function mark(idc, bad){ $('group-' + idc).classList.toggle('has-error', !!bad); }

  function renderCal(){
    var y = cur.y, m = cur.m, first = new Date(y, m, 1), off = (first.getDay() + 6) % 7, n = new Date(y, m + 1, 0).getDate();
    var h = ['L','M','M','J','V','S','D'].map(function(d){ return '<div class="dname">' + d + '</div>'; }).join('');
    for(var i = 0; i < off; i++) h += '<div class="cal-day off"></div>';
    for(var d = 1; d <= n; d++){
      var s = AR.iso(new Date(y, m, d)), cls = 'cal-day', attr = '';
      if(s < hoy) cls += ' past';
      else if(tool.ocupados.indexOf(d) > -1) cls += ' booked';
      else { cls += ' free'; attr = ' data-iso="' + s + '"'; }
      if(sel.inicio && (s === sel.inicio || s === sel.fin)) cls += ' selected';
      else if(sel.inicio && sel.fin && s > sel.inicio && s < sel.fin) cls += ' inrange';
      h += '<div class="' + cls + '"' + attr + '>' + d + '</div>';
    }
    $('calGrid').innerHTML = h;
    var mes = first.toLocaleDateString('es-AR', {month: 'long', year: 'numeric'}).replace(' de ', ' '); $('monthLabel').textContent = mes.charAt(0).toUpperCase() + mes.slice(1);
    $('prevM').disabled = (cur.y === min.y && cur.m === min.m);
  }

  function calcular(){
    return AR.calcularPrecio({precioDia: tool.price, inicio: sel.inicio, fin: sel.fin,
                              retiro: $('horaRetiro').value, devolucion: $('horaDevolucion').value});
  }

  function renderResumen(){
    var box = $('summaryBox');
    if(!sel.inicio){ box.className = 'summary-box hint'; box.innerHTML = 'Elegí las fechas y los horarios para ver el precio.'; return; }
    var r = calcular();
    if(!r.ok){ box.className = 'summary-box ' + (r.incompleto ? 'hint' : 'err'); box.textContent = r.error; return; }
    var html = '<div class="summary-row"><span>' + AR.formula(r) + '</span><span>' + AR.fmt(r.subtotal) + '</span></div>';
    if(r.tipo === 'horas'){
      html += '<div class="summary-note">' + AR.fmt(tool.price) + ' por día ÷ ' + AR.JORNADA_HS + ' hs = ' + AR.fmt(r.tarifa) + ' por hora' +
              (r.tope ? '. Tope: no se cobra más que un día completo.' : '.') + '</div>';
    }
    html += '<div class="summary-row"><span>Tarifa de servicio (' + Math.round(AR.SERVICIO * 100) + '%)</span><span>' + AR.fmt(r.servicio) + '</span></div>' +
            '<div class="summary-row total"><span>Total</span><span>' + AR.fmt(r.total) + '</span></div>';
    box.className = 'summary-box on'; box.innerHTML = html;
  }

  function render(){
    renderCal();
    $('fechaInicio').value = sel.inicio ? AR.fechaCorta(sel.inicio) : '';
    $('fechaFin').value = sel.fin ? AR.fechaCorta(sel.fin) : '';
    var hint = $('rangeHint');
    hint.className = 'range-hint';
    if(aviso){ hint.textContent = aviso; hint.className = 'range-hint warn'; aviso = ''; }
    else if(!sel.inicio) hint.textContent = 'Tocá un día libre para empezar.';
    else if(!sel.fin) hint.textContent = 'Ahora tocá el día de fin. Para alquilar solo por horas, tocá otra vez el mismo día.';
    else { var c = calcular(); hint.textContent = c.ok ? 'Período elegido: ' + c.dias + (c.dias === 1 ? ' día.' : ' días.') : ''; }
    renderResumen();
  }

  $('calGrid').addEventListener('click', function(e){
    var el = e.target.closest('[data-iso]'); if(!el) return;
    var s = el.getAttribute('data-iso');
    $('toastError').classList.remove('on'); mark('fechaInicio', false); mark('fechaFin', false);
    if(!sel.inicio || sel.fin){ sel = {inicio: s, fin: null}; }
    else if(s >= sel.inicio){
      if(AR.rangoOcupado(tool, sel.inicio, s)){ aviso = 'Ese período incluye días ocupados. Elegí otro rango.'; sel = {inicio: s, fin: null}; }
      else sel.fin = s;
    } else { sel = {inicio: s, fin: null}; }
    render();
  });
  $('prevM').addEventListener('click', function(){ if(cur.m === 0){ cur.y--; cur.m = 11; } else cur.m--; renderCal(); $('prevM').disabled = (cur.y === min.y && cur.m === min.m); });
  $('nextM').addEventListener('click', function(){ if(cur.m === 11){ cur.y++; cur.m = 0; } else cur.m++; renderCal(); });
  ['horaRetiro','horaDevolucion'].forEach(function(i){
    $(i).addEventListener('change', function(){ mark(i, false); $('toastError').classList.remove('on'); renderResumen(); });
  });

  function toast(msg){ var t = $('toastError'); t.textContent = '⚠️ ' + msg; t.classList.add('on'); }

  $('btnReservar').addEventListener('click', function(){
    $('toastError').classList.remove('on');
    var f = {inicio: sel.inicio, fin: sel.fin, retiro: $('horaRetiro').value, devolucion: $('horaDevolucion').value};
    mark('fechaInicio', !f.inicio); mark('fechaFin', !f.fin); mark('horaRetiro', !f.retiro); mark('horaDevolucion', !f.devolucion);
    if(!f.inicio || !f.fin || !f.retiro || !f.devolucion){ toast('Completá los campos marcados antes de reservar.'); return; }
    var r = calcular();
    if(!r.ok){ if(r.campo === 'devolucion') mark('horaDevolucion', true); toast(r.error); return; }
    if(AR.rangoOcupado(tool, f.inicio, f.fin)){ toast('Ese período incluye días ocupados. Elegí otro.'); return; }
    var ok = AR.ls('alquilar_reserva', {toolId: tool.id, inicio: f.inicio, fin: f.fin, retiro: f.retiro, devolucion: f.devolucion, total: r.total});
    if(!ok){ toast('No pudimos guardar tu selección. Revisá que el navegador permita guardar datos del sitio.'); return; }
    location.href = <?= json_encode(site_url('confirmacion')) ?>;
  });

  render();
})();
</script>

</body>
</html>
