const PROJ = "/demo5/demo1";
const BASE = `${location.origin}${PROJ}`;

const API     = `${BASE}/backend/modules/clientes/`;
const API_DIR = `${BASE}/backend/modules/clientes/cliente_direcciones/`;
const API_CON = `${BASE}/backend/modules/clientes/cliente_contactos/`;

const form = document.getElementById("formCliente");
const inputId = document.getElementById("clienteId");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");
const msg = document.getElementById("msg");
const tbody = document.getElementById("tbody");
const q = document.getElementById("q");

const f = (id)=>document.getElementById(id);
const campos = [
  "tipo","nombre","apellido","estado","documento_tipo","documento_nro",
  "email","telefono","notas",
  "dir_etiqueta","dir_direccion","dir_localidad","dir_provincia","dir_pais","dir_cp",
  "cont_nombre","cont_cargo","cont_email","cont_telefono"
];

function setMsg(t, err=false){ msg.textContent=t||""; msg.style.color = err ? "#b00020" : "#666"; }
function toFD(o){ const fd=new FormData(); Object.entries(o).forEach(([k,v])=>fd.append(k, v ?? "")); return fd; }
function getValues(){ const o={}; campos.forEach(c=>o[c]=f(c)?.value?.trim() ?? ""); return o; }
function setValues(p){ campos.forEach(c=>{ if(f(c)) f(c).value = p[c] ?? ""; }); }

form?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const id = inputId.value.trim();
  const v = getValues();
  if (!v.nombre) { setMsg("El nombre es obligatorio.", true); return; }

  const isUpdate = !!id;
  const url = API + (isUpdate ? "update.php" : "create.php");
  const body = toFD(isUpdate ? { id, ...v } : v);

  try{
    const res = await fetch(url, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status === "success") { resetForm(); await loadClientes(); }
  }catch(err){ console.error(err); setMsg("Error al guardar cliente.", true); }
});

btnCancelar?.addEventListener("click", resetForm);

function resetForm(){
  inputId.value = "";
  setValues({
    tipo:"persona", nombre:"", apellido:"", estado:"activo",
    documento_tipo:"", documento_nro:"",
    email:"", telefono:"", notas:"",
    dir_etiqueta:"", dir_direccion:"", dir_localidad:"", dir_provincia:"", dir_pais:"Argentina", dir_cp:"",
    cont_nombre:"", cont_cargo:"", cont_email:"", cont_telefono:""
  });

  const hidDirCli = document.getElementById("dir_cliente_id");
  const hidConCli = document.getElementById("cont_cliente_id");
  if (hidDirCli) hidDirCli.value = "";
  if (hidConCli) hidConCli.value = "";

  const dirPrincipalSel = document.getElementById("dir_principal");
  if (dirPrincipalSel) dirPrincipalSel.value = "0";
  const dirPrincipalChk = document.getElementById("dir_principal_chk");
  if (dirPrincipalChk) dirPrincipalChk.checked = false;

  const tbodyDir = document.querySelector("#tblDirecciones tbody");
  const tbodyCon = document.querySelector("#tblContactos tbody");
  if (tbodyDir) tbodyDir.innerHTML = "";
  if (tbodyCon) tbodyCon.innerHTML = "";

  if (typeof toggleSubpaneles === "function") toggleSubpaneles(false);

  btnGuardar.textContent = "Guardar";
  btnCancelar.classList.add("hidden");

  document.getElementById("panelDirecciones").classList.add("hidden");
  document.getElementById("panelContactos").classList.add("hidden");
  document.getElementById("dir_cliente_id").value = "";
  document.getElementById("cont_cliente_id").value = "";
  document.getElementById("tbodyDir").innerHTML = "";
  document.getElementById("tbodyCont").innerHTML = "";
}

async function loadClientes(term=""){
  try{
    const url = term ? `${API}read.php?q=${encodeURIComponent(term)}` : `${API}read.php`;
    const res = await fetch(url); const data = await res.json();
    if (data.status!=="success"){ setMsg("No se pudieron cargar los clientes.", true); return; }
    renderTable(data.clientes || []);
  }catch(err){ console.error(err); setMsg("Error al cargar clientes.", true); }
}

function renderTable(rows){
  tbody.innerHTML="";
  if (!rows.length){
    const tr=document.createElement("tr"), td=document.createElement("td");
    td.colSpan=8; td.textContent="Sin resultados"; td.className="muted";
    tr.appendChild(td); tbody.appendChild(tr); return;
  }
  rows.forEach(c=>{
    const tr=document.createElement("tr");
    tr.innerHTML = `
      <td>${c.id}</td>
      <td>${[c.nombre, c.apellido || ""].join(" ").trim()}</td>
      <td>${c.tipo}</td>
      <td>${[c.documento_tipo || "", c.documento_nro || ""].filter(Boolean).join(" ")}</td>
      <td>${c.email || ""}</td>
      <td>${c.telefono || ""}</td>
      <td>${c.fecha_alta || ""}</td>
      <td class="actions"></td>`;
    const tdAcc = tr.querySelector(".actions");

    const bEdit=document.createElement("button"); bEdit.type="button"; bEdit.textContent="Editar";
    bEdit.addEventListener("click", ()=> startEdit(c.id));
    const bDel=document.createElement("button"); bDel.type="button"; bDel.textContent="Eliminar";
    bDel.addEventListener("click", ()=> deleteCliente(c.id));

    tdAcc.append(bEdit,bDel);
    tbody.appendChild(tr);
  });
}

async function startEdit(id){
  try{
    const res  = await fetch(`${API}show.php?id=${encodeURIComponent(id)}`);
    const data = await res.json();
    if (data.status!=="success" || !data.cliente){
      setMsg("Cliente no encontrado.", true); return;
    }

    const c   = data.cliente;
    const dir = data.direccion_principal || {};
    const cont= data.contacto_principal  || {};

    inputId.value = c.id;
    setValues({
      tipo: c.tipo || "persona",
      nombre: c.nombre || "",
      apellido: c.apellido || "",
      estado: c.estado || "activo",
      documento_tipo: c.documento_tipo || "",
      documento_nro: c.documento_nro || "",
      email: c.email || "",
      telefono: c.telefono || "",
      notas: c.notas || "",
      dir_etiqueta: dir.etiqueta || "",
      dir_direccion: dir.direccion || "",
      dir_localidad: dir.localidad || "",
      dir_provincia: dir.provincia || "",
      dir_pais: dir.pais || "Argentina",
      dir_cp: dir.cp || "",
      cont_nombre: cont.nombre || "",
      cont_cargo: cont.cargo || "",
      cont_email: cont.email || "",
      cont_telefono: cont.telefono || ""
    });

    toggleSubpaneles(true);

    document.getElementById("dir_cliente_id").value  = String(Number(c.id));
    document.getElementById("cont_cliente_id").value = String(Number(c.id));

    await loadDirecciones(c.id);
    await loadContactos(c.id);

    btnGuardar.textContent = "Actualizar";
    btnCancelar.classList.remove("hidden");
    window.scrollTo({ top: 0, behavior: "smooth" });

    toggleSubpaneles(true);

    document.getElementById("dir_cliente_id").value  = String(Number(c.id));
    document.getElementById("cont_cliente_id").value = String(Number(c.id));

    await Promise.all([
      loadDirecciones(c.id),
      loadContactos(c.id)
    ]);
  }catch(err){
    console.error(err);
    setMsg("Error al cargar cliente.", true);
  }
}

async function deleteCliente(id){
  if (!confirm("¿Seguro que querés eliminar este cliente?")) return;
  try{
    const body = new URLSearchParams({ id: String(id) });
    const res = await fetch(`${API}delete.php`, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ await loadClientes(q.value.trim()); }
  }catch(err){ console.error(err); setMsg("Error al eliminar cliente.", true); }
}

document.getElementById("btnBuscar")?.addEventListener("click", ()=> loadClientes(q.value.trim()));
document.getElementById("btnLimpiar")?.addEventListener("click", ()=> { q.value=""; loadClientes(); });
q?.addEventListener("keydown", (e)=>{ if(e.key==="Enter"){ e.preventDefault(); loadClientes(q.value.trim()); } });

document.addEventListener("DOMContentLoaded", ()=> loadClientes());

// ====== SUBPANELES: toggle según cliente seleccionado ======
function toggleSubpaneles(visible) {
  document.getElementById("panelDirecciones").classList.toggle("hidden", !visible);
  document.getElementById("panelContactos").classList.toggle("hidden", !visible);
}

async function afterClienteLoaded(idCliente) {
  toggleSubpaneles(true);
  document.getElementById("dir_cliente_id").value  = idCliente;
  document.getElementById("cont_cliente_id").value = idCliente;
  await Promise.all([ loadDirecciones(idCliente), loadContactos(idCliente) ]);
}


// ====== DIRECCIONES ======

const tbodyDir = document.getElementById("tbodyDir");
const fdir = (id)=> document.querySelector(`#formDir [id="${id}"]`) || document.getElementById(id);

async function loadDirecciones(clienteId){
  try{
    const id = String(Number((clienteId ?? "").toString().trim()));
    if (!id || id === "0"){ setMsg("Cliente sin ID para direcciones.", true); return; }

    const url = `${API_DIR}read.php?cliente_id=${encodeURIComponent(id)}`;
    const res = await fetch(url, { headers:{ "Accept":"application/json" } });
    const txt = await res.text();
    console.debug("DIR LOAD DEBUG:", { url, status: res.status, body: txt.slice(0,200) });

    if (!res.ok){ setMsg(`HTTP ${res.status} cargando direcciones`, true); return; }

    let data; try { data = JSON.parse(txt); }
    catch { console.error("HTML recibido (dir):", txt.slice(0,200)); setMsg("El backend devolvió HTML en direcciones.", true); return; }

    if (data.status !== "success"){ setMsg(data.message || "No se pudieron cargar direcciones", true); return; }
    renderDirecciones(data.direcciones || []);
  }catch(e){ console.error(e); setMsg("Error cargando direcciones", true); }
}

function renderDirecciones(rows){
  tbodyDir.innerHTML = "";
  if (!rows.length){
    tbodyDir.innerHTML = `<tr><td colspan="8" class="muted">Sin direcciones</td></tr>`;
    return;
  }
  rows.forEach(d=>{
    const isPri = Number(d.es_principal) === 1;
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${d.etiqueta ?? ""}</td>
      <td>${d.direccion ?? ""}</td>
      <td>${d.localidad ?? ""}</td>
      <td>${d.provincia ?? ""}</td>
      <td>${d.pais ?? ""}</td>
      <td>${d.cp ?? ""}</td>
      <td>${isPri ? "Sí" : "No"}</td>
      <td class="actions"></td>`;
    const tdA = tr.querySelector(".actions");

    const bE = document.createElement("button"); bE.type="button";
    bE.textContent="Editar";
    bE.addEventListener("click", ()=> startEditDireccion(d));

    const bD = document.createElement("button"); bD.type="button";
    bD.textContent="Eliminar";
    bD.addEventListener("click", ()=> deleteDireccion(d.id));

    tdA.append(bE, bD);
    tbodyDir.appendChild(tr);
  });
}

function startEditDireccion(d){
  const isPri = Number(d.es_principal) === 1;

  fdir("sdir_id").value        = d.id;
  fdir("sdir_etiqueta").value  = d.etiqueta ?? "";
  fdir("sdir_direccion").value = d.direccion ?? "";
  fdir("sdir_localidad").value = d.localidad ?? "";
  fdir("sdir_provincia").value = d.provincia ?? "";
  fdir("sdir_pais").value      = d.pais ?? "Argentina";
  fdir("sdir_cp").value        = d.cp ?? "";

  // ojo: IDs del subpanel
  const selPri = fdir("sdir_principal");
  if (selPri) selPri.value = isPri ? "1" : "0";

  const chkPri = fdir("sdir_principal_chk");
  if (chkPri) chkPri.checked = isPri;

  document.getElementById("btnDirCancelar")?.classList.remove("hidden");
}

document.getElementById("btnDirCancelar")?.addEventListener("click", ()=>{
  fdir("sdir_id").value = "";
  document.getElementById("formDir").reset();
  const selPri = fdir("sdir_principal");      if (selPri) selPri.value = "0";
  const chkPri = fdir("sdir_principal_chk");  if (chkPri) chkPri.checked = false;
  document.getElementById("btnDirCancelar")?.classList.add("hidden");
});

document.getElementById("formDir")?.addEventListener("submit", async (e)=>{
  e.preventDefault();

  const cliente_id = fdir("dir_cliente_id").value;
  if (!cliente_id){ setMsg("Falta cliente_id.", true); return; }

  let esPrincipal = "0";
  const selPri = fdir("sdir_principal");      if (selPri) esPrincipal = selPri.value === "1" ? "1" : "0";
  const chkPri = fdir("sdir_principal_chk");  if (chkPri) esPrincipal = chkPri.checked ? "1" : "0";

  const payload = {
    id: fdir("sdir_id").value,
    cliente_id,
    etiqueta:  (fdir("sdir_etiqueta").value  || "").trim(),
    direccion: (fdir("sdir_direccion").value || "").trim(),
    localidad: (fdir("sdir_localidad").value || "").trim(),
    provincia: (fdir("sdir_provincia").value || "").trim(),
    pais:      (fdir("sdir_pais").value      || "").trim(),
    cp:        (fdir("sdir_cp").value        || "").trim(),
    es_principal: esPrincipal
  };
  if (!payload.direccion){ setMsg("La dirección es obligatoria.", true); return; }

  const url  = API_DIR + (payload.id ? "update.php" : "create.php");
  const body = new FormData(); Object.entries(payload).forEach(([k,v])=> body.append(k, v));

  try{
    const res  = await fetch(url, { method:"POST", body });
    const data = await res.json().catch(()=> ({}));
    if (data.status === "success"){
      await loadDirecciones(cliente_id);
      fdir("sdir_id").value = "";
      document.getElementById("formDir").reset();
      if (selPri) selPri.value = "0";
      if (chkPri) chkPri.checked = false;
      document.getElementById("btnDirCancelar")?.classList.add("hidden");
      setMsg(data.message || "Dirección guardada.");
    } else {
      setMsg(data.message || "Error guardando dirección.", true);
    }
  }catch(e){ console.error(e); setMsg("Error guardando dirección.", true); }
});

async function deleteDireccion(id){
  if (!confirm("¿Eliminar dirección?")) return;
  const cliente_id = fdir("dir_cliente_id").value;
  try{
    const body = new URLSearchParams({ id:String(id) });
    const res = await fetch(`${API_DIR}delete.php`, { method:"POST", body });
    const data = await res.json().catch(()=> ({}));
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ await loadDirecciones(cliente_id); }
  }catch(e){ console.error(e); setMsg("Error eliminando dirección.", true); }
}


// ====== CONTACTOS======

const tbodyCont = document.getElementById("tbodyCont");
const fcont = (id)=> document.querySelector(`#formCont [id="${id}"]`) || document.getElementById(id);

async function loadContactos(clienteId){
  try{
    const id = String(Number((clienteId ?? "").toString().trim()));
    if (!id || id === "0"){ setMsg("Cliente sin ID para contactos.", true); return; }

    const url = `${API_CON}read.php?cliente_id=${encodeURIComponent(id)}`;
    const res = await fetch(url, { headers:{ "Accept":"application/json" } });
    const txt = await res.text();
    if (!res.ok){ setMsg(`HTTP ${res.status} cargando contactos`, true); return; }

    let data; 
    try { data = JSON.parse(txt); }
    catch { console.error("HTML recibido (cont):", txt.slice(0,200)); setMsg("El backend devolvió HTML en contactos.", true); return; }

    if (data.status!=="success"){ setMsg(data.message || "No se pudieron cargar contactos", true); return; }
    renderContactos(data.contactos || []);
  }catch(e){ console.error(e); setMsg("Error cargando contactos", true); }
}

function renderContactos(rows){
  tbodyCont.innerHTML = "";
  if (!rows.length){
    tbodyCont.innerHTML = `<tr><td colspan="6" class="muted">Sin contactos</td></tr>`;
    return;
  }
  rows.forEach(c=>{
    const tr=document.createElement("tr");
    tr.innerHTML = `
      <td>${c.nombre ?? ""}</td>
      <td>${c.cargo ?? ""}</td>
      <td>${c.email ?? ""}</td>
      <td>${c.telefono ?? ""}</td>
      <td>${c.es_principal ? "Sí" : "No"}</td>
      <td class="actions"></td>`;
    const tdA = tr.querySelector(".actions");
    const bE=document.createElement("button"); bE.textContent="Editar";   bE.addEventListener("click", ()=> startEditContacto(c));
    const bD=document.createElement("button"); bD.textContent="Eliminar"; bD.addEventListener("click", ()=> deleteContacto(c.id));
    tdA.append(bE,bD);
    tbodyCont.appendChild(tr);
  });
}

function startEditContacto(c){
  fcont("scont_id").value        = c.id;
  fcont("scont_nombre").value    = c.nombre ?? "";
  fcont("scont_cargo").value     = c.cargo ?? "";
  fcont("scont_email").value     = c.email ?? "";
  fcont("scont_telefono").value  = c.telefono ?? "";
  const selPri = fcont("scont_principal");      if (selPri) selPri.value = c.es_principal ? "1" : "0";
  const chkPri = fcont("scont_principal_chk");  if (chkPri) chkPri.checked = !!c.es_principal;
  document.getElementById("btnContCancelar").classList.remove("hidden");
}

document.getElementById("btnContCancelar")?.addEventListener("click", ()=>{
  fcont("scont_id").value = "";
  document.getElementById("formCont").reset();
  const selPri = fcont("scont_principal");      if (selPri) selPri.value = "0";
  const chkPri = fcont("scont_principal_chk");  if (chkPri) chkPri.checked = false;
  document.getElementById("btnContCancelar").classList.add("hidden");
});

document.getElementById("formCont")?.addEventListener("submit", async (e)=>{
  e.preventDefault();
  const cliente_id = fcont("cont_cliente_id").value; // oculto del subpanel
  if (!cliente_id){ setMsg("Falta cliente_id.", true); return; }

  let esPrincipal = "0";
  const selPri = fcont("scont_principal");      if (selPri) esPrincipal = selPri.value === "1" ? "1" : "0";
  const chkPri = fcont("scont_principal_chk");  if (chkPri) esPrincipal = chkPri.checked ? "1" : "0";

  const payload = {
    id: fcont("scont_id").value,
    cliente_id,
    nombre:   (fcont("scont_nombre").value   || "").trim(),
    cargo:    (fcont("scont_cargo").value    || "").trim(),
    email:    (fcont("scont_email").value    || "").trim(),
    telefono: (fcont("scont_telefono").value || "").trim(),
    es_principal: esPrincipal
  };
  if (!payload.nombre){ setMsg("El nombre del contacto es obligatorio.", true); return; }

  const url  = API_CON + (payload.id ? "update.php" : "create.php");
  const body = new FormData(); Object.entries(payload).forEach(([k,v])=> body.append(k, v));

  try{
    const res  = await fetch(url, { method:"POST", body });
    const data = await res.json().catch(()=> ({}));
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){
      fcont("scont_id").value = "";
      document.getElementById("formCont").reset();
      if (selPri) selPri.value = "0";
      if (chkPri) chkPri.checked = false;
      await loadContactos(cliente_id);
      document.getElementById("btnContCancelar").classList.add("hidden");
    }
  }catch(e){ console.error(e); setMsg("Error guardando contacto.", true); }
});

async function deleteContacto(id){
  if (!confirm("¿Eliminar contacto?")) return;
  const cliente_id = fcont("cont_cliente_id").value;
  try{
    const body = new URLSearchParams({ id:String(id) });
    const res = await fetch(`${API_CON}delete.php`, { method:"POST", body });
    const data = await res.json().catch(()=> ({}));
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ await loadContactos(cliente_id); }
  }catch(e){ console.error(e); setMsg("Error eliminando contacto.", true); }
}