const BASE = '../../backend/modules';
const API_VEHICULOS  = `${BASE}/vehiculos/options.php`;
const API_USUARIOS   = `${BASE}/usuarios/options.php`;
const API_SALIDA     = `${BASE}/vehiculo_usos/salida.php`;
const API_REGRESO    = `${BASE}/vehiculo_usos/regreso.php`;
const API_ACTIVOS    = `${BASE}/vehiculo_usos/list_activos.php`;

const $ = (s) => document.querySelector(s);

function msg(text, isError = false) {
  const el = document.getElementById('msg');
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

async function loadOptions(selectEl, url, placeholder = '-- seleccionar --') {
  const list = await fetchJSON(url);
  selectEl.innerHTML =
    `<option value="">${placeholder}</option>` +
    list.map(r => `<option value="${r.id}">${r.nombre ?? r.text ?? r.patente ?? r.id}</option>`).join('');
}

async function loadActivos() {
  const tbody = $('#tbodyActivos');
  const usos = await fetchJSON(API_ACTIVOS);

  tbody.innerHTML = usos.map(u => `
    <tr>
      <td>${u.id}</td>
      <td>${u.patente ?? ''}</td>
      <td>${u.descripcion ?? ''}</td>
      <td>${u.usuario_salida ?? ''}</td>
      <td>${u.fecha_salida ? new Date(u.fecha_salida).toLocaleString() : ''}</td>
      <td>${u.destino ?? ''}</td>
      <td>${u.motivo ?? ''}</td>
    </tr>
  `).join('') || `<tr><td colspan="7" style="text-align:center">Sin usos activos</td></tr>`;

  const selUso = $('#uso_id');
  selUso.innerHTML = '<option value="">-- seleccionar --</option>' +
    usos.map(u => {
      const label = [
        u.patente || `Vehículo ${u.vehiculo_id}`,
        u.usuario_salida ? `• ${u.usuario_salida}` : '',
        u.fecha_salida ? `• ${new Date(u.fecha_salida).toLocaleString()}` : ''
      ].join(' ');
      return `<option value="${u.id}">${label}</option>`;
    }).join('');
}

async function init() {
  await Promise.all([
    loadOptions($('#vehiculo_id'), API_VEHICULOS, '-- vehículo --'),
    loadOptions($('#usuario_id_salida'), API_USUARIOS, '-- usuario --'),
    loadOptions($('#usuario_id_regreso'), API_USUARIOS, '-- usuario --'),
  ]);

  await loadActivos();

  $('#formSalida').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.currentTarget);
    try {
      await fetchJSON(API_SALIDA, { method: 'POST', body: fd });
      msg('Salida registrada');
      e.currentTarget.reset();
      await loadActivos();
    } catch (err) {
      msg(err.message || 'No se pudo registrar la salida', true);
    }
  });

  $('#formRegreso').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.currentTarget);
    try {
      await fetchJSON(API_REGRESO, { method: 'POST', body: fd });
      msg('Regreso registrado');
      e.currentTarget.reset();
      await loadActivos();
    } catch (err) {
      msg(err.message || 'No se pudo registrar el regreso', true);
    }
  });

  $('#btnRefrescar')?.addEventListener('click', () => loadActivos().catch(err => msg(err.message, true)));
}

init().catch(err => msg(err.message, true));
