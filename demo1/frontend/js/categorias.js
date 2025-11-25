const API_BASE = "../../backend/modules/categorias/";

const form = document.getElementById("formCategoria");
const inputNombre = document.getElementById("nombre");
const inputDescripcion = document.getElementById("descripcion");
const inputId = document.getElementById("categoriaId");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");
const msg = document.getElementById("msg");

const q = document.getElementById("q");
const btnBuscar = document.getElementById("btnBuscar");
const btnLimpiar = document.getElementById("btnLimpiar");
const tbody = document.getElementById("tbody");

function setMsg(text, isError=false){ msg.textContent=text||""; msg.style.color=isError?"#b00020":"#666"; }
function toFormData(obj){ const fd=new FormData(); Object.entries(obj).forEach(([k,v])=>fd.append(k,v)); return fd; }

form?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const id = inputId.value.trim();
  const nombre = inputNombre.value.trim();
  const descripcion = inputDescripcion.value.trim();

  if(!nombre){ setMsg("El nombre es obligatorio.", true); return; }

  const isUpdate = !!id;
  const url = API_BASE + (isUpdate ? "update.php" : "create.php");
  const body = toFormData(isUpdate ? {id, nombre, descripcion} : {nombre, descripcion});

  try{
    const res = await fetch(url, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if(data.status==="success"){ resetForm(); await loadCategorias(); }
  }catch(err){ console.error(err); setMsg("Error al guardar la categoría.", true); }
});

btnCancelar?.addEventListener("click", resetForm);

function resetForm(){
  inputId.value=""; inputNombre.value=""; inputDescripcion.value="";
  btnGuardar.textContent="Guardar"; btnCancelar.classList.add("hidden"); form.reset();
}

async function loadCategorias(term=""){
  try{
    const url = term ? `${API_BASE}read.php?q=${encodeURIComponent(term)}` : `${API_BASE}read.php`;
    const res = await fetch(url); const data = await res.json();
    if(data.status!=="success"){ setMsg("No se pudieron cargar las categorías.", true); return; }
    renderTable(data.categorias||[]);
  }catch(err){ console.error(err); setMsg("Error al cargar categorías.", true); }
}

function renderTable(categorias){
  tbody.innerHTML="";
  if(!categorias.length){
    const tr=document.createElement("tr"); const td=document.createElement("td");
    td.colSpan=4; td.textContent="Sin resultados"; td.className="muted"; tr.appendChild(td); tbody.appendChild(tr); return;
  }
  categorias.forEach(c=>{
    const tr=document.createElement("tr");

    const tdId=document.createElement("td"); tdId.textContent=c.id;
    const tdNombre=document.createElement("td"); tdNombre.textContent=c.nombre;
    const tdDesc=document.createElement("td"); tdDesc.textContent=c.descripcion ?? "";

    const tdAcc=document.createElement("td"); tdAcc.className="actions";
    const btnEdit=document.createElement("button"); btnEdit.type="button"; btnEdit.textContent="Editar";
    btnEdit.addEventListener("click", ()=> startEdit(c));
    const btnDel=document.createElement("button"); btnDel.type="button"; btnDel.textContent="Eliminar";
    btnDel.addEventListener("click", ()=> deleteCategoria(c.id));

    tdAcc.append(btnEdit, btnDel);
    tr.append(tdId, tdNombre, tdDesc, tdAcc);
    tbody.appendChild(tr);
  });
}

function startEdit(cat){
  inputId.value = cat.id;
  inputNombre.value = cat.nombre;
  inputDescripcion.value = cat.descripcion ?? "";
  btnGuardar.textContent = "Actualizar";
  btnCancelar.classList.remove("hidden");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

async function deleteCategoria(id){
  if(!confirm("¿Seguro que querés eliminar esta categoría?")) return;
  try{
    const body = new URLSearchParams({ id: String(id) });
    const res = await fetch(API_BASE + "delete.php", { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if(data.status==="success"){ await loadCategorias(q.value.trim()); }
  }catch(err){ console.error(err); setMsg("Error al eliminar la categoría.", true); }
}

btnBuscar?.addEventListener("click", ()=> loadCategorias(q.value.trim()));
btnLimpiar?.addEventListener("click", ()=> { q.value=""; loadCategorias(); });
q?.addEventListener("keydown", (e)=>{ if(e.key==="Enter"){ e.preventDefault(); loadCategorias(q.value.trim()); } });

document.addEventListener("DOMContentLoaded", ()=> loadCategorias());
