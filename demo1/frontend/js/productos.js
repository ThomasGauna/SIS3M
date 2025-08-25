const API = "../../backend/modules/productos/";
const API_CAT = "../../backend/modules/categorias/read.php";
const API_MAR = "../../backend/modules/marcas/read.php";
const API_PRO = "../../backend/modules/proveedores/read.php";
const API_UNI = "../../backend/modules/unidades_medida/read.php";
const API_UBI = "../../backend/modules/ubicaciones/read.php"; // asegurate de tenerlo

const form = document.getElementById("formProducto");
const inputId = document.getElementById("productoId");
const msg = document.getElementById("msg");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");
const tbody = document.getElementById("tbody");

const f = (id) => document.getElementById(id);
const campos = [
  "nombre","sku","estado","fecha_alta","categoria_id","marca_id","proveedor_id","unidad_id",
  "ubicacion_id","costo_unitario","stock_actual","stock_minimo","descripcion"
];

function setMsg(t, err=false){ msg.textContent=t||""; msg.style.color=err?"#b00020":"#666"; }
function toFD(o){ const fd=new FormData(); Object.entries(o).forEach(([k,v])=>fd.append(k, v ?? "")); return fd; }
function getValues(){
  const o={}; campos.forEach(c=>o[c]=f(c)?.value?.trim() ?? ""); return o;
}
function setValues(p){
  campos.forEach(c => { if (f(c)) f(c).value = p[c] ?? ""; });
}

async function loadOptions() {
  const loaders = [
    [API_CAT, "categoria_id", (it)=>({v:it.id, t:it.nombre}) ],
    [API_MAR, "marca_id",     (it)=>({v:it.id, t:it.nombre}) ],
    [API_PRO, "proveedor_id", (it)=>({v:it.id, t:it.nombre}) ],
    [API_UNI, "unidad_id",    (it)=>({v:it.id, t:`${it.nombre} (${it.abreviatura})`}) ],
    [API_UBI, "ubicacion_id", (it)=>({v:it.id, t:it.nombre}) ],
  ];
  for (const [url, selId, map] of loaders) {
    try {
      const res = await fetch(url); const data = await res.json();
      const sel = f(selId); if (!sel) continue;
      const arr = data.categorias || data.marcas || data.proveedores || data.unidades || data.ubicaciones || [];
      sel.innerHTML = '<option value=""></option>' + arr.map(it => {
        const {v,t} = map(it); return `<option value="${v}">${t}</option>`;
      }).join('');
    } catch (e) { console.warn("No se pudo cargar", selId, e); }
  }
}

form?.addEventListener("submit", async (e)=>{
  e.preventDefault(); setMsg("");
  const id = inputId.value.trim();
  const v = getValues();

  if (!v.nombre || !v.categoria_id || !v.unidad_id) {
    setMsg("Nombre, Categoría y Unidad son obligatorios.", true); return;
  }

  const isUpdate = !!id;
  const url = API + (isUpdate ? "update.php" : "create.php");
  const body = toFD(isUpdate ? {id, ...v} : v);

  try{
    const res = await fetch(url, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status === "success") { resetForm(); await loadProductos(); }
  } catch (err) { console.error(err); setMsg("Error al guardar.", true); }
});

btnCancelar?.addEventListener("click", resetForm);

function resetForm(){
  inputId.value = "";
  setValues({
    nombre:"", sku:"", estado:"activo", fecha_alta:"",
    categoria_id:"", marca_id:"", proveedor_id:"", unidad_id:"",
    ubicacion_id:"", costo_unitario:"", stock_actual:"0", stock_minimo:"0", descripcion:""
  });
  // fecha por defecto hoy
  const hoy = new Date().toISOString().slice(0,10);
  if (!f("fecha_alta").value) f("fecha_alta").value = hoy;

  btnGuardar.textContent = "Guardar";
  btnCancelar.classList.add("hidden");
  form.reset();
}

const q = document.getElementById("q");
document.getElementById("btnBuscar")?.addEventListener("click", ()=> loadProductos(q.value.trim()));
document.getElementById("btnLimpiar")?.addEventListener("click", ()=> { q.value=""; loadProductos(); });
q?.addEventListener("keydown", (e)=>{ if(e.key==="Enter"){ e.preventDefault(); loadProductos(q.value.trim()); } });

async function loadProductos(term=""){
  try{
    const url = term ? `${API}read.php?q=${encodeURIComponent(term)}` : `${API}read.php`;
    const res = await fetch(url); const data = await res.json();
    if (data.status!=="success"){ setMsg("No se pudieron cargar los productos.", true); return; }
    renderTable(data.productos || []);
  }catch(err){ console.error(err); setMsg("Error al cargar productos.", true); }
}

function renderTable(rows){
  tbody.innerHTML = "";
  if (!rows.length){
    const tr=document.createElement("tr"); const td=document.createElement("td");
    td.colSpan=9; td.textContent="Sin resultados"; td.className="muted"; tr.appendChild(td); tbody.appendChild(tr); return;
  }
  rows.forEach(p=>{
    const tr=document.createElement("tr");
    tr.innerHTML = `
      <td>${p.id}</td>
      <td>${p.nombre ?? ""}</td>
      <td>${p.sku ?? ""}</td>
      <td>${p.categoria ?? ""}</td>
      <td>${p.marca ?? ""}</td>
      <td>${p.unidad ?? ""}</td>
      <td>${p.stock_actual ?? 0}${p.unidad ? ' '+p.unidad : ''}</td>
      <td>${p.costo_unitario ?? ""}</td>
      <td class="actions"></td>`;
    const tdAcc = tr.querySelector(".actions");

    const bEdit = document.createElement("button"); bEdit.type="button"; bEdit.textContent="Editar";
    bEdit.addEventListener("click", ()=> startEdit(p.id));
    const bDel = document.createElement("button"); bDel.type="button"; bDel.textContent="Eliminar";
    bDel.addEventListener("click", ()=> deleteProducto(p.id));

    tdAcc.append(bEdit, bDel);
    tbody.appendChild(tr);
  });
}

async function startEdit(id){
  try{
    const res = await fetch(`${API}show.php?id=${encodeURIComponent(id)}`);
    const data = await res.json();
    if (data.status!=="success" || !data.producto){ setMsg("Producto no encontrado.", true); return; }
    const p = data.producto;

    inputId.value = p.id;
    setValues({
      nombre: p.nombre ?? "",
      sku: p.sku ?? "",
      estado: p.estado ?? "activo",
      fecha_alta: p.fecha_alta ?? "",
      categoria_id: p.categoria_id ?? "",
      marca_id: p.marca_id ?? "",
      proveedor_id: p.proveedor_id ?? "",
      unidad_id: p.unidad_id ?? "",
      ubicacion_id: p.ubicacion_id ?? "",
      costo_unitario: p.costo_unitario ?? "",
      stock_actual: p.stock_actual ?? "0",
      stock_minimo: p.stock_minimo ?? "0",
      descripcion: p.descripcion ?? ""
    });

    btnGuardar.textContent = "Actualizar";
    btnCancelar.classList.remove("hidden");
    window.scrollTo({ top: 0, behavior: "smooth" });
  }catch(err){ console.error(err); setMsg("Error al cargar producto.", true); }
}

async function deleteProducto(id){
  if (!confirm("¿Seguro que querés eliminar este producto?")) return;
  try{
    const body = new URLSearchParams({ id: String(id) });
    const res = await fetch(`${API}delete.php`, { method:"POST", body });
    const data = await res.json();
    setMsg(data.message, data.status!=="success");
    if (data.status==="success"){ await loadProductos(q.value.trim()); }
  }catch(err){ console.error(err); setMsg("Error al eliminar producto.", true); }
}

// init
document.addEventListener("DOMContentLoaded", async ()=>{
  await loadOptions();
  // fecha por defecto
  const hoy = new Date().toISOString().slice(0,10);
  const fa = f("fecha_alta"); if (fa && !fa.value) fa.value = hoy;
  loadProductos();
});
