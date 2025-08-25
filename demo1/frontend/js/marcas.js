// Ajustá este base según tu estructura: desde /frontend/html/ a /backend/modules/marcas/
const API_BASE = "../../backend/modules/marcas/";

const form = document.getElementById("formMarca");
const inputNombre = document.getElementById("nombre");
const inputId = document.getElementById("marcaId");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");
const msg = document.getElementById("msg");

const q = document.getElementById("q");
const btnBuscar = document.getElementById("btnBuscar");
const btnLimpiar = document.getElementById("btnLimpiar");

const tbody = document.getElementById("tbody");

function setMsg(text, isError = false) {
  msg.textContent = text || "";
  msg.style.color = isError ? "#b00020" : "#666";
}

function toFormData(obj) {
  const fd = new FormData();
  Object.entries(obj).forEach(([k, v]) => fd.append(k, v));
  return fd;
}

// ===== CREATE / UPDATE =====
form?.addEventListener("submit", async (e) => {
  e.preventDefault();
  setMsg("");

  const id = inputId.value.trim();
  const nombre = inputNombre.value.trim();

  if (!nombre) {
    setMsg("El nombre es obligatorio.", true);
    return;
  }

  try {
    const isUpdate = !!id;
    const url = API_BASE + (isUpdate ? "update.php" : "create.php");
    const body = toFormData(isUpdate ? { id, nombre } : { nombre });

    const res = await fetch(url, { method: "POST", body });
    const data = await res.json();

    setMsg(data.message, data.status !== "success");

    if (data.status === "success") {
      resetForm();
      await loadMarcas();
    }
  } catch (err) {
    console.error(err);
    setMsg("Error al guardar la marca.", true);
  }
});

btnCancelar?.addEventListener("click", () => resetForm());

function resetForm() {
  inputId.value = "";
  inputNombre.value = "";
  btnGuardar.textContent = "Guardar";
  btnCancelar.classList.add("hidden");
  form.reset();
}

// ===== READ (con búsqueda) =====
async function loadMarcas(term = "") {
  try {
    const url = term ? `${API_BASE}read.php?q=${encodeURIComponent(term)}` : `${API_BASE}read.php`;
    const res = await fetch(url);
    const data = await res.json();

    if (data.status !== "success") {
      setMsg("No se pudieron cargar las marcas.", true);
      return;
    }
    renderTable(data.marcas || []);
  } catch (err) {
    console.error(err);
    setMsg("Error al cargar marcas.", true);
  }
}

function renderTable(marcas) {
  tbody.innerHTML = "";
  if (!marcas.length) {
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 3;
    td.textContent = "Sin resultados";
    td.className = "muted";
    tr.appendChild(td);
    tbody.appendChild(tr);
    return;
  }

  marcas.forEach((m) => {
    const tr = document.createElement("tr");

    const tdId = document.createElement("td");
    tdId.textContent = m.id;

    const tdNombre = document.createElement("td");
    tdNombre.textContent = m.nombre;

    const tdAcc = document.createElement("td");
    tdAcc.className = "actions";

    const btnEdit = document.createElement("button");
    btnEdit.type = "button";
    btnEdit.textContent = "Editar";
    btnEdit.addEventListener("click", () => startEdit(m));

    const btnDel = document.createElement("button");
    btnDel.type = "button";
    btnDel.textContent = "Eliminar";
    btnDel.addEventListener("click", () => deleteMarca(m.id));

    tdAcc.append(btnEdit, btnDel);
    tr.append(tdId, tdNombre, tdAcc);
    tbody.appendChild(tr);
  });
}

// ===== START EDIT =====
function startEdit(marca) {
  inputId.value = marca.id;
  inputNombre.value = marca.nombre;
  btnGuardar.textContent = "Actualizar";
  btnCancelar.classList.remove("hidden");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

// ===== DELETE =====
async function deleteMarca(id) {
  if (!confirm("¿Seguro que querés eliminar esta marca?")) return;
  try {
    const body = new URLSearchParams({ id: String(id) });
    const res = await fetch(API_BASE + "delete.php", { method: "POST", body });
    const data = await res.json();
    setMsg(data.message, data.status !== "success");
    if (data.status === "success") {
      await loadMarcas(q.value.trim());
    }
  } catch (err) {
    console.error(err);
    setMsg("Error al eliminar la marca.", true);
  }
}

// ===== BUSCAR =====
btnBuscar?.addEventListener("click", async () => {
  await loadMarcas(q.value.trim());
});
btnLimpiar?.addEventListener("click", async () => {
  q.value = "";
  await loadMarcas();
});
q?.addEventListener("keydown", async (e) => {
  if (e.key === "Enter") {
    e.preventDefault();
    await loadMarcas(q.value.trim());
  }
});

// Init
document.addEventListener("DOMContentLoaded", () => {
  loadMarcas();
});
