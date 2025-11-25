const API_BASE   = '../../backend/modules/vehiculos';
const API_CREATE = `${API_BASE}/create.php`;
const API_UPDATE = `${API_BASE}/update.php`;
const API_LIST   = `${API_BASE}/list.php`;

const $  = (s) => document.querySelector(s);
const $$ = (s) => Array.from(document.querySelectorAll(s));

function msg(text, isError = false) {
  const el = $('#msg');
  if (!el) return;
  el.textContent = text;
  el.style.color = isError ? 'crimson' : 'green';
}

async function fetchJSON(url, opts) {
  const res = await fetch(url, opts);
  const raw = await res.text();
  let data;
  try { data = JSON.parse(raw); }
  catch { throw new Error(`Respuesta no-JSON (${res.status}): ${raw.slice(0,160)}`); }
  if (data?.ok === false && data?.error) throw new Error(data.error);
  return data;
}

async function postForm(url, formEl) {
  const fd = new FormData(formEl);
  return fetchJSON(url, { method: 'POST', body: fd });
}

function formToJSON() {
  return {
    id:          $('#vehiculoId').value.trim(),
    patente:     $('#patente').value.trim(),
    descripcion: $('#descripcion').value.trim(),
    marca:       $('#marca').value.trim(),
    modelo:      $('#modelo').value.trim(),
    anio:        $('#anio').value.trim(),
    estado:      $('#estado').value,
  };
}

function fillForm(row) {
  $('#vehiculoId').value   = row.id ?? '';
  $('#patente').value      = row.patente ?? '';
  $('#descripcion').value  = row.descripcion ?? '';
  $('#marca').value        = row.marca ?? '';
  $('#modelo').value       = row.modelo ?? '';
  $('#anio').value         = row.anio ?? '';
  $('#estado').value       = row.estado ?? 'activo';
  $('#btnCancelar')?.classList.remove('hidden');
  $('#patente')?.focus();
}

function resetForm() {
  $('#formVehiculo').reset();
  $('#vehiculoId').value = '';
  $('#btnCancelar')?.classList.add('hidden');
}

function renderRows(rows = []) {
  const tbody = $('#tbody');
  if (!tbody) return;

  if (!rows.length) {
    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center">Sin resultados</td></tr>`;
    return;
  }

  tbody.innerHTML = rows.map(r => `
    <tr data-id="${r.id}">
      <td>${r.id}</td>
      <td>${r.patente ?? ''}</td>
      <td>${r.descripcion ?? ''}</td>
      <td>${r.marca ?? ''}</td>
      <td>${r.modelo ?? ''}</td>
      <td>${r.anio ?? ''}</td>
      <td>${r.estado ?? ''}</td>
      <td>
        <button class="btn btn-edit" data-action="edit">Editar</button>
        <!-- Si después querés borrar:
        <button class="btn btn-danger" data-action="del">Borrar</button>
        -->
      </td>
    </tr>
  `).join('');
}

async function loadList() {
  const q = $('#q')?.value?.trim() ?? '';
  const url = q ? `${API_LIST}?q=${encodeURIComponent(q)}` : API_LIST;
  const data = await fetchJSON(url);

  const rows = Array.isArray(data) ? data : (data.items || data.data || []);
  renderRows(rows);
}

function wireEvents() {
  $('#formVehiculo')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = $('#vehiculoId').value.trim();

    const { patente, descripcion } = formToJSON();
    if (!patente || !descripcion) {
      msg('Patente y descripción son obligatorias', true);
      return;
    }

    try {
      if (id) {
        await postForm(API_UPDATE, e.currentTarget);
        msg('Vehículo actualizado');
      } else {
        await postForm(API_CREATE, e.currentTarget);
        msg('Vehículo creado');
      }
      resetForm();
      await loadList();
    } catch (err) {
      msg(err.message || 'No se pudo guardar', true);
    }
  });

  $('#btnCancelar')?.addEventListener('click', () => {
    resetForm();
    msg('Edición cancelada');
  });

  $('#btnBuscar')?.addEventListener('click', (e) => {
    e.preventDefault();
    loadList().catch(err => msg(err.message, true));
  });

  $('#btnLimpiar')?.addEventListener('click', (e) => {
    e.preventDefault();
    $('#q').value = '';
    loadList().catch(err => msg(err.message, true));
  });

  let t;
  $('#q')?.addEventListener('input', () => {
    clearTimeout(t);
    t = setTimeout(() => loadList().catch(err => msg(err.message, true)), 250);
  });

  $('#tbody')?.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const tr = btn.closest('tr');
    if (!tr) return;

    const id = tr.dataset.id;
    if (btn.dataset.action === 'edit') {
      const tds = tr.querySelectorAll('td');
      const row = {
        id,
        patente:     tds[1]?.textContent?.trim(),
        descripcion: tds[2]?.textContent?.trim(),
        marca:       tds[3]?.textContent?.trim(),
        modelo:      tds[4]?.textContent?.trim(),
        anio:        tds[5]?.textContent?.trim(),
        estado:      tds[6]?.textContent?.trim() || 'activo',
      };
      fillForm(row);
      msg(`Editando #${id}`);
    }
  });
}

(async function init() {
  try {
    await loadList();
    wireEvents();
  } catch (err) {
    msg(err.message || 'Error inicializando pantalla', true);
  }
})();
