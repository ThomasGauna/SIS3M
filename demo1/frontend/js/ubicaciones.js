const API = "../../backend/modules/ubicaciones/";

const form = document.getElementById("formUbi");
const inputId = document.getElementById("ubiId");
const msg = document.getElementById("msg");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");
const tbody = document.getElementById("tbody");
const q = document.getElementById("q");

const f = (id)=>document.getElementById(id);
const campos = ["nombre","descripcion","direccion","localidad","provincia","pais","estado","fecha_alta"];

function setMsg(t, err=false){ msg.textContent=t||""; msg.style.color=err?"#b00020":"#666"; }
function toFD(o){ const fd=new FormData(); Object.entries(o).forEach(([k,v])=>fd.append(k, v ?? "")); return fd; }
function getValues(){ const o={}; campos.forEach(c=>o[c]=f(c)?.value?.trim() ?? ""); return o; }
function setValues(p){ campos.forEach(c=>{ if(f(c)) f(c).value = p[c] ?? ""; }); }

form?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const id = inputId.value.trim();
  const v = getValues();
  if (!v.nombre){ setMsg("El nombre es obligatorio.", true); return; }

  const isUpdate = !!id;
  const url = API + (isUpdate ? "update.php" : "create.php");
  const body = toFD(isUpdate ? { id, ...v } : v);

  try{
    const res = await fetch(url, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ resetForm(); await loadUbicaciones(); }
  }catch(err){ console.error(err); setMsg("Error al guardar.", true); }
});

btnCancelar?.addEventListener("click", resetForm);

function resetForm(){
  inputId.value=""; setValues({
    nombre:"", descripcion:"", direccion:"", localidad:"", provincia:"", pais:"Argentina", estado:"activo", fecha_alta:""
  });
  const hoy = new Date().toISOString().slice(0,10);
  if (!f("fecha_alta").value) f("fecha_alta").value = hoy;

  btnGuardar.textContent="Guardar";
  btnCancelar.classList.add("hidden");
  form.reset();
}

document.getElementById("btnBuscar")?.addEventListener("click", ()=> loadUbicaciones(q.value.trim()));
document.getElementById("btnLimpiar")?.addEventListener("click", ()=> { q.value=""; loadUbicaciones(); });
q?.addEventListener("keydown", (e)=>{ if(e.key==="Enter"){ e.preventDefault(); loadUbicaciones(q.value.trim()); } });

async function loadUbicaciones(term=""){
  try{
    const url = term ? `${API}read.php?q=${encodeURIComponent(term)}` : `${API}read.php`;
    const res = await fetch(url); const data = await res.json();
    if (data.status!=="success"){ setMsg("No se pudieron cargar las ubicaciones.", true); return; }
    renderTable(data.ubicaciones || []);
  }catch(err){ console.error(err); setMsg("Error al cargar ubicaciones.", true); }
}

function renderTable(rows){
  tbody.innerHTML = "";
  if (!rows.length){
    const tr=document.createElement("tr"); const td=document.createElement("td");
    td.colSpan=8; td.textContent="Sin resultados"; td.className="muted"; tr.appendChild(td); tbody.appendChild(tr); return;
  }
  rows.forEach(u=>{
    const tr=document.createElement("tr");
    tr.innerHTML = `
      <td>${u.id}</td>
      <td>${u.nombre ?? ""}</td>
      <td>${u.direccion ?? ""}</td>
      <td>${u.localidad ?? ""}</td>
      <td>${u.provincia ?? ""}</td>
      <td>${u.pais ?? ""}</td>
      <td>${u.estado ?? ""}</td>
      <td class="actions"></td>`;
    const tdA = tr.querySelector(".actions");
    const bE = document.createElement("button"); bE.type="button"; bE.textContent="Editar";
    bE.addEventListener("click", ()=> startEdit(u.id));
    const bD = document.createElement("button"); bD.type="button"; bD.textContent="Eliminar";
    bD.addEventListener("click", ()=> deleteUbi(u.id));
    tdA.append(bE,bD);
    tbody.appendChild(tr);
  });
}

async function startEdit(id){
  try{
    const res = await fetch(`${API}show.php?id=${encodeURIComponent(id)}`);
    const data = await res.json();
    if (data.status!=="success" || !data.ubicacion){ setMsg("Ubicación no encontrada.", true); return; }
    const u = data.ubicacion;

    inputId.value = u.id;
    setValues({
      nombre: u.nombre ?? "",
      descripcion: u.descripcion ?? "",
      direccion: u.direccion ?? "",
      localidad: u.localidad ?? "",
      provincia: u.provincia ?? "",
      pais: u.pais ?? "Argentina",
      estado: u.estado ?? "activo",
      fecha_alta: u.fecha_alta ?? ""
    });

    btnGuardar.textContent="Actualizar";
    btnCancelar.classList.remove("hidden");
    window.scrollTo({ top: 0, behavior: "smooth" });
  }catch(err){ console.error(err); setMsg("Error al cargar ubicación.", true); }
}

async function deleteUbi(id){
  if (!confirm("¿Seguro que querés eliminar esta ubicación?")) return;
  try{
    const body = new URLSearchParams({ id:String(id) });
    const res = await fetch(`${API}delete.php`, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ await loadUbicaciones(q.value.trim()); }
  }catch(err){ console.error(err); setMsg("Error al eliminar ubicación.", true); }
}

document.addEventListener("DOMContentLoaded", ()=>{
  const hoy = new Date().toISOString().slice(0,10);
  if (!f("fecha_alta").value) f("fecha_alta").value = hoy;
  loadUbicaciones();
});
