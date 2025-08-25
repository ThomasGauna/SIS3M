// ===== helpers DOM =====
const $  = (s) => document.querySelector(s);
const $$ = (s) => Array.from(document.querySelectorAll(s));

// ===== base de rutas =====
// Estás en /.../frontend/html/historial_tecnico.html  -> recorto hasta /.../
const ROOT     = location.pathname.replace(/\/frontend\/html\/.*/, '/');
const API_ROOT = `${location.origin}${ROOT}`;
const TAPI     = `${API_ROOT}backend/modules/api/tickets`;

// ===== fetch robusto =====
async function fetchJSON(url, opts = {}) {
  const r = await fetch(url, opts);
  const raw = await r.text();
  if (!r.ok) {
    console.error("HTTP", r.status, url, raw);
    throw new Error(`HTTP ${r.status} ${url}`);
  }
  try { return JSON.parse(raw); }
  catch {
    console.error("No es JSON. Respuesta cruda:", raw);
    throw new Error("Respuesta no JSON del servidor");
  }
}

// ===== estado =====
const state = {
  q: '',
  estado: '',
  limit: 25,
  offset: 0,
  total: 0,
};

// ===== elementos del HTML (tus IDs) =====
const elQ      = $('#t_q');
const elEstado = $('#t_estado');
const elBuscar = $('#t_buscar');
const elTbody  = $('#tbTickets');

// ===== utils =====
const esc = (s) => (s ?? '').toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
function fmtFechaISO(y_m_d) {
  if (!y_m_d) return '';
  const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(y_m_d);
  if (m) return `${m[3]}/${m[2]}/${m[1]}`;
  const d = new Date(y_m_d);
  return isNaN(d) ? y_m_d : d.toLocaleString();
}

// ===== render =====
function renderRows(rows) {
  if (!elTbody) return;
  elTbody.innerHTML = rows.map(r => {
    const cliente = [r.cliente_nombre, r.cliente_apellido].filter(Boolean).join(' ');
    const equipo  = r.producto_nombre || '';
    const asignado = r.asignado_id ?? '';
    return `
      <tr data-id="${r.id}">
        <td style="text-align:right">${r.id}</td>
        <td>${fmtFechaISO(r.fecha_ingreso)}</td>
        <td>${esc(cliente)}</td>
        <td>${esc(equipo)}</td>
        <td>${esc(r.numero_serie ?? '')}</td>
        <td>${esc(r.tipo_trabajo ?? '')}</td>
        <td>${esc(r.estado ?? '')}</td>
        <td>${esc(asignado)}</td>
        <td style="white-space:nowrap">
          <button class="btn btn--sm ver" data-id="${r.id}">Ver</button>
          <a class="btn btn--sm" href="${ROOT}frontend/html/ticket.html?id=${encodeURIComponent(r.id)}">Editar</a>
        </td>
      </tr>
    `;
  }).join('');
}

// ===== carga =====
async function cargar() {
  const url = new URL(`${TAPI}/list.php`);
  if (state.q)      url.searchParams.set('q', state.q);
  if (state.estado) url.searchParams.set('estado', state.estado);
  url.searchParams.set('limit',  state.limit);
  url.searchParams.set('offset', state.offset);

  const res = await fetchJSON(url.toString());
  if (res.status !== 'ok' || !Array.isArray(res.data)) {
    console.error('Payload inesperado:', res);
    throw new Error('No se pudo listar tickets');
  }
  state.total = res.total ?? res.data.length;
  renderRows(res.data);
}

// ===== eventos =====
function wire() {
  if (elBuscar) elBuscar.addEventListener('click', async () => {
    state.q = (elQ?.value || '').trim();
    state.estado = (elEstado?.value || '').trim();
    state.offset = 0;
    try { await cargar(); } catch (e) { alert(e.message); }
  });

  if (elTbody) elTbody.addEventListener('click', (ev) => {
    const btn = ev.target.closest('.ver');
    if (!btn) return;
    const id = btn.dataset.id;
    window.open(`${TAPI}/show.php?id=${encodeURIComponent(id)}`, '_blank');
  });

  // enter en el input = buscar
  if (elQ) elQ.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') elBuscar?.click();
  });
}

// ===== init =====
(async function init(){
  wire();
  try { await cargar(); } catch (e) { console.error(e); alert(e.message); }
})();
