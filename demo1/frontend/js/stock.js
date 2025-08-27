const API_BASE = "../../backend/modules/stock/";
const $ = (id)=>document.getElementById(id);
const setMsg = (t,err=false)=>{ $("msg").textContent=t||""; $("msg").style.color = err?"#b00020":"#666"; };

async function cargarProductosSelect(selectId){
  const sel = $(selectId);
  if (!sel) return;
  try{
    const res = await fetch("../../backend/modules/productos/options.php");
    const data = await res.json();
    if (data.status !== "success") return;

    const isKardex = (selectId === "k_prod");
    sel.innerHTML = isKardex
      ? '<option value="">-- Todos --</option>'
      : '<option value="">-- Selecciona un producto --</option>';

    (data.productos || []).forEach(p=>{
      const label = [p.nombre, p.sku ? `(${p.sku})` : null, (p.stock_actual!=null)?`— stock: ${p.stock_actual}`:null]
        .filter(Boolean).join(' ');
      const opt = document.createElement("option");
      opt.value = p.id;
      opt.textContent = label;
      sel.appendChild(opt);
    });
  }catch(e){ console.error("No se pudieron cargar productos", e); }
}

async function cargarUbicacionesSelect(selectId){
  const sel = $(selectId);
  if (!sel) return;
  try{
    let res = await fetch("../../backend/modules/ubicaciones/read.php");
    if (!res.ok) {
      res = await fetch("../../backend/ubicaciones/read.php");
    }
    const data = await res.json();
    if (data.status !== "success") return;

    const rows = data.ubicaciones || data.unidades || [];
    sel.innerHTML = '<option value="">-- Selecciona --</option>';
    rows.forEach(u=>{
      const opt = document.createElement("option");
      opt.value = u.id;
      opt.textContent = u.nombre;
      sel.appendChild(opt);
    });
  }catch(e){
    console.error("No se pudieron cargar ubicaciones", e);
  }
}

let selTipo, grpOrigen, grpDestino, selOrigen, selDestino;

function ensureNodes(){
  selTipo   = selTipo   || $("tipo");
  grpOrigen = grpOrigen || $("grpOrigen");
  grpDestino= grpDestino|| $("grpDestino");
  selOrigen = selOrigen || $("ubic_origen_id");
  selDestino= selDestino|| $("ubic_destino_id");
  return selTipo && grpOrigen && grpDestino && selOrigen && selDestino;
}

function togglePorTipo() {
  if (!ensureNodes()) return;
  const esEntrada = (selTipo.value === "entrada");

  grpDestino.style.display = esEntrada ? "" : "none";
  selDestino.required = esEntrada;
  if (!esEntrada) selDestino.value = "";

  grpOrigen.style.display = esEntrada ? "none" : "";
  selOrigen.required = !esEntrada;
  if (esEntrada) selOrigen.value = "";
}

$("formMov")?.addEventListener("submit", async (e)=>{
  e.preventDefault();
  setMsg("");

  const tipo = $("tipo").value;
  const body = new FormData();
  body.append("tipo", tipo);
  body.append("producto_id", $("producto_id").value);
  body.append("cantidad", $("cantidad").value);
  body.append("origen", $("origen").value || "manual");

  if (tipo === "entrada") {
    if (!selDestino.value) { setMsg("Elegí ubicación destino.", true); return; }
    body.append("ubic_destino_id", selDestino.value);
  } else {
    if (!selOrigen.value) { setMsg("Elegí ubicación origen.", true); return; }
    body.append("ubic_origen_id", selOrigen.value);
  }

  const refTipo = $("ref_tipo").value.trim();
  const refId   = $("ref_id").value.trim();
  const notas   = $("notas").value.trim();
  if (refTipo) body.append("ref_tipo", refTipo);
  if (refId)   body.append("ref_id", refId);
  if (notas)   body.append("notas", notas);

  try{
    const res = await fetch(API_BASE+"create_movimiento.php",{method:"POST",body});
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
  }catch(err){ console.error(err); setMsg("Error registrando movimiento", true); }
});

$("formTrans")?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const body = new FormData();
  body.append("producto_id", $("t_producto_id").value);
  body.append("cantidad", $("t_cantidad").value);
  body.append("ubic_origen_id", $("t_origen").value);
  body.append("ubic_destino_id", $("t_destino").value);
  try{
    const res = await fetch(API_BASE+"transferir.php",{method:"POST",body});
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
  }catch(err){ console.error(err); setMsg("Error transfiriendo", true); }
});

$("k_go")?.addEventListener("click", loadKardex);
async function loadKardex(){
  setMsg("");
  const params = new URLSearchParams();
  if ($("k_prod").value) params.append("producto_id", $("k_prod").value);
  if ($("k_q").value)    params.append("q", $("k_q").value);
  try{
    const res = await fetch(API_BASE+"read.php?"+params.toString());
    const data = await res.json();
    if (data.status!=="success") { setMsg("No se pudo listar movimientos", true); return; }
    const tb = $("tbK"); tb.innerHTML="";
    const rows = data.movimientos || [];
    if (!rows.length){ tb.innerHTML = `<tr><td colspan="8" class="muted">Sin resultados</td></tr>`; return; }
    rows.forEach(m=>{
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${m.id}</td>
        <td>${m.created_at ?? ""}</td>
        <td>${m.producto ?? ""}</td>
        <td>${m.tipo}</td>
        <td>${m.cantidad}</td>
        <td>${m.origen ?? ""}</td>
        <td>${[m.ref_tipo||"", m.ref_id||""].filter(Boolean).join(" ")}</td>
        <td>${m.notas ?? ""}</td>`;
      tb.appendChild(tr);
    });
  }catch(err){ console.error(err); setMsg("Error cargando movimientos", true); }
}

$("formInvOpen")?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const body = new FormData();
  body.append("titulo", $("inv_titulo").value);
  if ($("inv_ubi").value) body.append("ubicacion_id", $("inv_ubi").value);
  try{
    const res = await fetch(API_BASE+"inventario_create.php",{method:"POST",body});
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ $("inv_id").value = data.id; }
  }catch(err){ console.error(err); setMsg("Error abriendo inventario", true); }
});

$("inv_close")?.addEventListener("click", async ()=>{
  setMsg("");
  const invId = $("inv_id").value;
  if (!invId){ setMsg("Falta Inventario ID", true); return; }
  let arr=[];
  try{ arr = JSON.parse($("inv_items").value || "[]"); }catch(e){ setMsg("Items inválidos (JSON)", true); return; }
  const body = new FormData();
  body.append("inventario_id", invId);
  body.append("items", JSON.stringify(arr));
  try{
    const res = await fetch(API_BASE+"inventario_cerrar.php",{method:"POST",body});
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
  }catch(err){ console.error(err); setMsg("Error cerrando inventario", true); }
});

 document.addEventListener("DOMContentLoaded", async ()=>{
   await Promise.all([
     cargarProductosSelect("producto_id"),
     cargarProductosSelect("t_producto_id"),
     cargarProductosSelect("k_prod"),
     cargarUbicacionesSelect("ubic_origen_id"),
     cargarUbicacionesSelect("ubic_destino_id"),
     cargarUbicacionesSelect("t_origen"),
     cargarUbicacionesSelect("t_destino"),
     cargarUbicacionesSelect("inv_ubi"),
   ]);
   togglePorTipo();
   $("tipo")?.addEventListener("change", togglePorTipo);
   await loadKardex();
 });

$("k_go")?.addEventListener("click", loadKardex);
$("k_prod")?.addEventListener("change", loadKardex);
$("k_q")?.addEventListener("keydown", (e)=>{ if(e.key==="Enter") loadKardex(); });
