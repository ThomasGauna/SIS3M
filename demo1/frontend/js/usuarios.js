const BASE = '../../backend/modules';
const API = {
  list:   `${BASE}/usuarios/list.php`,
  create: `${BASE}/usuarios/create.php`,
  update: `${BASE}/usuarios/update.php`,
  roles:  `${BASE}/roles/options.php`,
};

const $  = s => document.querySelector(s);

function msg(text, isError=false){
  const el = $('#msg');
  if (!el) return;
  el.textContent = text;
  el.style.color = isError ? 'crimson' : 'green';
}

async function fetchJSON(url, opts){
  const res = await fetch(url, opts);
  const raw = await res.text();
  let data;
  try { data = JSON.parse(raw); }
  catch { throw new Error(`Respuesta no-JSON (${res.status}): ${raw.slice(0,160)}`); }
  if (data?.ok === false && data?.error) throw new Error(data.error);
  return data;
}

async function loadRoles(){
  const roles = await fetchJSON(API.roles);
  $('#role_id').innerHTML =
    '<option value="">-- rol --</option>' +
    roles.map(r => `<option value="${r.id}">${r.nombre}</option>`).join('');
}

function renderRows(rows){
  const tbody = $('#tbody');
  if (!rows.length){
    tbody.innerHTML = `<tr><td colspan="6" style="text-align:center">Sin resultados</td></tr>`;
    return;
    }
  tbody.innerHTML = rows.map(r => `
    <tr data-id="${r.id}">
      <td>${r.id}</td>
      <td>${r.nombre ?? ''}</td>
      <td>${r.email ?? ''}</td>
      <td>${r.rol_nombre ?? ''}</td>
      <td>${r.estado ?? ''}</td>
      <td>
        <button class="btn" data-action="edit">Editar</button>
      </td>
    </tr>
  `).join('');
}

async function loadList(){
  const q = $('#q')?.value?.trim() ?? '';
  const url = q ? `${API.list}?q=${encodeURIComponent(q)}` : API.list;
  const data = await fetchJSON(url);
  const rows = Array.isArray(data) ? data : (data.items || data.data || []);
  renderRows(rows);
}

function fillForm(row){
  $('#usuarioId').value = row.id ?? '';
  $('#nombre').value    = row.nombre ?? '';
  $('#email').value     = row.email ?? '';
  $('#role_id').value   = row.role_id ?? '';
  $('#estado').value    = row.estado ?? 'activo';
  $('#btnCancelar').classList.remove('hidden');
}

function resetForm(){
  $('#formUsuario').reset();
  $('#usuarioId').value = '';
  $('#btnCancelar').classList.add('hidden');
}

function wire(){
  $('#formUsuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = $('#usuarioId').value.trim();
    const nombre = $('#nombre').value.trim();
    const roleId = $('#role_id').value.trim();
    if (!nombre || !roleId) return msg('Nombre y rol son obligatorios', true);

    const url = id ? API.update : API.create;
    const btn = e.currentTarget.querySelector('button[type="submit"]');
    btn?.setAttribute('disabled','');

    try {
      await fetchJSON(url, { method:'POST', body:new FormData(e.currentTarget) });
      msg(id ? 'Usuario actualizado' : 'Usuario creado');
      resetForm();
      await loadList();
    } catch (err) {
      msg(err.message || 'No se pudo guardar', true);
    } finally {
      btn?.removeAttribute('disabled');
    }
  });

  $('#btnBuscar')?.addEventListener('click', (e) => { e.preventDefault(); loadList().catch(err => msg(err.message, true)); });
  $('#btnLimpiar')?.addEventListener('click', (e) => { e.preventDefault(); $('#q').value=''; loadList().catch(err => msg(err.message, true)); });
  let t;
  $('#q')?.addEventListener('input', () => { clearTimeout(t); t=setTimeout(()=>loadList().catch(err=>msg(err.message,true)), 250); });

  $('#tbody')?.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action="edit"]');
    if (!btn) return;
    const tr = btn.closest('tr'); if (!tr) return;
    const tds = tr.querySelectorAll('td');
    const row = {
      id: tr.dataset.id,
      nombre: tds[1]?.textContent?.trim(),
      email: tds[2]?.textContent?.trim(),
      rol_nombre: tds[3]?.textContent?.trim(),
      estado: tds[4]?.textContent?.trim(),
    };
    const opt = Array.from($('#role_id').options).find(o => o.textContent === row.rol_nombre);
    row.role_id = opt ? opt.value : '';
    fillForm(row);
    msg(`Editando #${row.id}`);
  });

  $('#btnCancelar').addEventListener('click', () => { resetForm(); msg('Edición cancelada'); });
}

(async function init(){
  try {
    await loadRoles();
    await loadList();
    wire();
  } catch (err) {
    msg(err.message || 'Error inicializando Usuarios', true);
  }
})();
