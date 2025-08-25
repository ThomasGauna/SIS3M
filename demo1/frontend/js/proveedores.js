// Ajustá base según tu estructura relativa: desde /frontend/html/ a /backend/modules/proveedores/
const API_BASE = "../../backend/modules/proveedores/";

const form = document.getElementById("formProveedor");
const inputId = document.getElementById("proveedorId");
const msg = document.getElementById("msg");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");

const q = document.getElementById("q");
const btnBuscar = document.getElementById("btnBuscar");
const btnLimpiar = document.getElementById("btnLimpiar");
const tbody = document.getElementById("tbody");

const f = (id) => document.getElementById(id);
const campos = [
  "nombre","cuit","telefono","email","direccion","localidad","provincia","pais",
  "contacto_nombre","contacto_telefono","contacto_email","observaciones","estado","fecha_alta"
];

function setMsg(text, isError=false){ msg.textContent=text||""; msg.style.color=isError?"#b00020":"#666"; }
function toFormData(obj){ const fd=new FormData(); Object.entries(obj).forEach(([k,v])=>fd.append(k, v ?? "")); return fd; }
function getFormValues(){
  const obj = {};
  campos.forEach(c => obj[c] = f(c)?.value?.trim() ?? "");
  return obj;
}
function setFormValues(p){
  campos.forEach(c => { if (f(c)) f(c).value = p[c] ?? ""; });
}

form?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const id = inputId.value.trim();
  const values = getFormValues();

  if (!values.nombre) { setMsg("El nombre es obligatorio.", true); return; }

  const isUpdate = !!id;
  const url = API_BASE + (isUpdate ? "update.php" : "create.php");
  const body = toFormData(isUpdate ? { id, ...values } : values);

  try{
    const res = await fetch(url, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status === "success"){ resetForm(); await loadProveedores(); }
  }catch(err){ console.error(err); setMsg("Error al guardar el proveedor.", true); }
});

btnCancelar?.addEventListener("click", resetForm);

function resetForm(){
  inputId.value = "";
  setFormValues({
    nombre:"", cuit:"", telefono:"", email:"", direccion:"", localidad:"", provincia:"",
    pais:"Argentina", contacto_nombre:"", contacto_telefono:"", contacto_email:"",
    observaciones:"", estado:"activo", fecha_alta:""
  });
  btnGuardar.textContent = "Guardar";
  btnCancelar.classList.add("hidden");
  form.reset();
}

// READ + búsqueda
async function loadProveedores(term=""){
  try{
    const url = term ? `${API_BASE}read.php?q=${encodeURIComponent(term)}` : `${API_BASE}read.php`;
    const res = await fetch(url);
    const data = await res.json();
    if (data.status !== "success"){ setMsg("No se pudieron cargar los proveedores.", true); return; }
    renderTable(data.proveedores || []);
  }catch(err){ console.error(err); setMsg("Error al cargar proveedores.", true); }
}

function renderTable(rows){
  tbody.innerHTML = "";
  if (!rows.length){
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 7; td.textContent = "Sin resultados"; td.className = "muted";
    tr.appendChild(td); tbody.appendChild(tr); return;
  }

  rows.forEach(p=>{
    const tr = document.createElement("tr");

    const tdId = document.createElement("td"); tdId.textContent = p.id;
    const tdNom = document.createElement("td"); tdNom.textContent = p.nombre;
    const tdCuit = document.createElement("td"); tdCuit.textContent = p.cuit ?? "";
    const tdCont = document.createElement("td"); tdCont.textContent = p.telefono ?? "";
    const tdMail = document.createElement("td"); tdMail.textContent = p.email ?? "";
    const tdEst = document.createElement("td"); tdEst.textContent = p.estado ?? "";

    const tdAcc = document.createElement("td"); tdAcc.className = "actions";
    const bEdit = document.createElement("button"); bEdit.type="button"; bEdit.textContent = "Editar";
    bEdit.addEventListener("click", ()=> startEdit(p.id));
    const bDel = document.createElement("button"); bDel.type="button"; bDel.textContent = "Eliminar";
    bDel.addEventListener("click", ()=> deleteProveedor(p.id));

    tdAcc.append(bEdit, bDel);
    tr.append(tdId, tdNom, tdCuit, tdCont, tdMail, tdEst, tdAcc);
    tbody.appendChild(tr);
  });
}

async function startEdit(id){
  try {
    const res = await fetch(`${API_BASE}show.php?id=${encodeURIComponent(id)}`);
    const data = await res.json();

    if (data.status !== "success" || !data.proveedor) {
      setMsg("Proveedor no encontrado.", true);
      return;
    }
    const p = data.proveedor;

    inputId.value = p.id;
    setFormValues({
      nombre: p.nombre ?? "",
      cuit: p.cuit ?? "",
      telefono: p.telefono ?? "",
      email: p.email ?? "",
      direccion: p.direccion ?? "",
      localidad: p.localidad ?? "",
      provincia: p.provincia ?? "",
      pais: p.pais ?? "Argentina",
      contacto_nombre: p.contacto_nombre ?? "",
      contacto_telefono: p.contacto_telefono ?? "",
      contacto_email: p.contacto_email ?? "",
      observaciones: p.observaciones ?? "",
      estado: p.estado ?? "activo",
      fecha_alta: p.fecha_alta ?? ""
    });

    btnGuardar.textContent = "Actualizar";
    btnCancelar.classList.remove("hidden");
    window.scrollTo({ top: 0, behavior: "smooth" });
  } catch (err) {
    console.error(err);
    setMsg("Error al cargar proveedor.", true);
  }
}

// DELETE
async function deleteProveedor(id){
  if (!confirm("¿Seguro que querés eliminar este proveedor?")) return;
  try{
    const body = new URLSearchParams({ id: String(id) });
    const res = await fetch(API_BASE + "delete.php", { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status === "success"){ await loadProveedores(q.value.trim()); }
  }catch(err){ console.error(err); setMsg("Error al eliminar el proveedor.", true); }
}

btnBuscar?.addEventListener("click", ()=> loadProveedores(q.value.trim()));
btnLimpiar?.addEventListener("click", ()=> { q.value=""; loadProveedores(); });
q?.addEventListener("keydown", (e)=>{ if (e.key==="Enter"){ e.preventDefault(); loadProveedores(q.value.trim()); } });

document.addEventListener("DOMContentLoaded", ()=>{
  const hoy = new Date().toISOString().slice(0,10);
  const fa = document.getElementById("fecha_alta");
  if (fa && !fa.value) fa.value = hoy;

  loadProveedores();
});
