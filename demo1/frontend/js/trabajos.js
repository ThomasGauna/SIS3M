window.__trabajoShowInit = window.__trabajoShowInit || false;
window.__trabajoSaving = false;
const API_TRAB = '/demo5/demo1/backend/modules/api/trabajos';

async function postForm(url, fields){
  const fd = new FormData();
  for (const [k,v] of Object.entries(fields)){
    if (v !== undefined && v !== null) fd.append(k, String(v));
  }
  const res = await fetch(url, { method:'POST', body: fd });
  const raw = await res.text();
  console.debug('POST →', url, 'status:', res.status, 'raw:', raw);

  let data;
  try { data = JSON.parse(raw); }
  catch {
    throw new Error(`El servidor no devolvió JSON válido (HTTP ${res.status}). Respuesta: ${raw.slice(0,160)}…`);
  }
  const ok = data?.ok === true || data?.status === 'ok' || data?.success === true;
  if (!ok) throw new Error(data?.error || data?.message || 'Operación fallida');
  return data;
}

/*async function postForm(url, fields){
  const fd = new FormData();
  for (const [k,v] of Object.entries(fields)){
    if (v !== undefined && v !== null) fd.append(k, String(v));
  }
  const r = await fetch(url, { method:'POST', body: fd });
  const data = await r.json().catch(()=>null);
  if (!data || data.ok !== true){
    throw new Error(data?.error || 'Error desconocido');
  }
  return data;
}*/

function el(id){ return document.getElementById(id); }

window.initTrabajosList = async function initTrabajosList(){
  const q          = el('q');
  const fEstado    = el('f_estado');
  const tbody      = el('tbody');
  const pager      = el('pager');
  const btnBuscar  = el('btnBuscar');
  const btnNuevo   = el('btnNuevo');

  const urlq = new URLSearchParams(location.search);
  const preCliente = urlq.get('cliente_id')?.trim();

  let page = 1, perPage = 10;

  btnBuscar?.addEventListener('click', () => { page = 1; load(); });
  q?.addEventListener('keydown', (e)=>{ if (e.key === 'Enter') { page = 1; load(); }});
  fEstado?.addEventListener('change', ()=>{ page = 1; load(); });
  btnNuevo?.addEventListener('click', ()=> location.href = './trabajos_show.html');

  if (preCliente) addClienteChip(preCliente);

  async function load(){
    const params = new URLSearchParams();
    const qv = q?.value?.trim() || '';
    const ev = fEstado?.value?.trim() || '';

    if (qv) params.set('q', qv);
    if (ev) params.set('estado', ev);
    if (preCliente) params.set('cliente_id', preCliente);
    params.set('page', String(page));
    params.set('per_page', String(perPage));

    const url = `${API_TRAB}/list.php?` + params.toString();
    let data;
    try{
      const res = await fetch(url);
      if (!res.ok) { tbody.innerHTML = `<tr><td colspan="6">Error ${res.status}</td></tr>`; return; }
      data = await res.json();
    }catch(err){
      console.error(err);
      tbody.innerHTML = `<tr><td colspan="6">Error de red</td></tr>`;
      return;
    }
    if (!data.ok) { tbody.innerHTML = `<tr><td colspan="6">${data.error||'Error'}</td></tr>`; return; }

    renderRows(data.items || []);
    renderPager(data.total || 0, data.page || 1, data.per_page || perPage);
  }

  function toLocalDateTime(s){
    if (!s) return '-';
    const iso = s.includes('T') ? s : s.replace(' ', 'T');
    const d = new Date(iso);
    return isNaN(d) ? s : d.toLocaleString();
  }

  function renderRows(items){
    if (!items.length){
      tbody.innerHTML = `<tr><td colspan="6" class="muted">Sin trabajos</td></tr>`;
      return;
    }
    tbody.innerHTML = items.map(it => {
      const fecha = toLocalDateTime(it.fecha_alta);
      return `<tr data-id="${it.id}" class="row-link">
        <td><a href="./trabajos_show.html?id=${it.id}">#${it.id}</a></td>
        <td>${fecha}</td>
        <td><a href="./trabajos_show.html?id=${it.id}">${escapeHtml(it.titulo || '')}</a></td>
        <td>${escapeHtml(it.cliente || '')}</td>
        <td><span class="badge">${it.prioridad}</span></td>
        <td><span class="badge">${it.estado}</span></td>
      </tr>`;
    }).join('');

    tbody.onclick = (e)=>{
      if (e.target.closest('a')) return;
      const tr = e.target.closest('tr.row-link');
      if (!tr) return;
      const id = tr.getAttribute('data-id');
      if (id) location.href = `./trabajos_show.html?id=${id}`;
    };
  }

  function renderPager(total, curPage, per){
    const pages = Math.max(1, Math.ceil(total / per));
    if (pages <= 1){ pager.innerHTML = ''; return; }
    let html = `<div class="actions">`;
    html += `<button class="btn" ${curPage<=1?'disabled':''} data-act="prev">«</button>`;
    html += `<span class="muted">Página ${curPage} / ${pages}</span>`;
    html += `<button class="btn" ${curPage>=pages?'disabled':''} data-act="next">»</button>`;
    html += `</div>`;
    pager.innerHTML = html;
    pager.querySelectorAll('button[data-act]').forEach(b=>{
      b.addEventListener('click', ()=>{
        const act = b.getAttribute('data-act');
        if (act==='prev' && page>1){ page--; load(); }
        if (act==='next' && page<pages){ page++; load(); }
      });
    });
  }

  function escapeHtml(s){
    return s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]));
  }

  function addClienteChip(id){
    const bar = document.querySelector('.toolbar, .bar');
    if (!bar) return;
    if (document.getElementById('chipCliente')) return;
    const chip = document.createElement('span');
    chip.id = 'chipCliente';
    chip.className = 'badge';
    chip.style.marginLeft = '.5rem';
    chip.textContent = `Cliente #${id}`;
    bar.appendChild(chip);
  }

  load();
};

async function fetchJSONFirst(paths){
  for (const p of paths){
    try{
      const res = await fetch(p, { cache: 'no-store' });
      if (!res.ok) continue;
      const j = await res.json();
      return { data: j, url: p };
    }catch{ /* seguimos probando */ }
  }
  throw new Error('No encontré un endpoint válido para opciones');
}

function getClientesArray(payload){
  if (Array.isArray(payload)) return payload;
  if (payload.clientes) return payload.clientes;
  if (payload.items) return payload.items;
  if (payload.data) return payload.data;
  return [];
}
function getUbicacionesArray(payload){
  if (Array.isArray(payload)) return payload;
  if (payload.ubicaciones) return payload.ubicaciones;
  if (payload.items) return payload.items;
  if (payload.data) return payload.data;
  return [];
}

window.initTrabajoShow = function initTrabajoShow(){
  if (window.__trabajoShowInit) return;
  window.__trabajoShowInit = true;
  function setSelectValue(sel, value, label){
  if (!sel) return;
  if (value == null || value === '') { sel.value = ''; return; }
  const val = String(value);
  sel.value = val;
  if (sel.value !== val){
    const opt = document.createElement('option');
    opt.value = val;
    opt.textContent = label || `#${val}`;
    sel.appendChild(opt);
    sel.value = val;
  }
}

  const $ = (id)=>document.getElementById(id);
  function setValue(id, val){
    const el = $(id);
    if (!el){ console.warn('Falta #'+id); return; }
    el.value = (val ?? '');
  }
  const msg = (t,err=false)=>{ const m=$('msg'); if(!m)return; m.textContent=t||''; m.style.color = err?'#b00020':'#666'; };

  const qs = new URLSearchParams(location.search);
  const editingId = qs.get('id');
  const presetCliente = qs.get('cliente_id');

  let item = null;
  (async ()=>{
    const pOpts = Promise.allSettled([cargarClientes(), cargarUbicaciones()]);
    const pItem = (async ()=>{
      if (editingId) { await loadTrabajo(editingId); }
      else if (presetCliente) { setSelectValue($('#cliente_id'), presetCliente); }
    })();

    await pItem;
    await pOpts;

    if (item){
      setSelectValue($('#cliente_id'),  item.cliente_id,  item.cliente_nombre);
      setSelectValue($('#ubicacion_id'), item.ubicacion_id, item.ubicacion_nombre);
    }
  })();

  async function cargarClientes(){
    try{
      let res = await fetch('../../backend/modules/clientes/options.php', { cache:'no-store' });
      if (!res.ok) return;
      const data = await res.json();
      const rows = data.clientes || data.items || data.data || [];
      const sel = document.getElementById('cliente_id'); if (!sel) return;
      sel.innerHTML = '<option value="">— seleccionar —</option>';
      rows.forEach(c=>{
        const label = [c.nombre, c.apellido].filter(Boolean).join(' ') || c.razon_social || c.nombre || `#${c.id}`;
        const opt = document.createElement('option');
        opt.value = c.id; opt.textContent = label;
        sel.appendChild(opt);
      });
    }catch(e){ console.warn('Clientes options:', e); }
  }

  async function cargarUbicaciones(){
    try{
      let res = await fetch('../../backend/modules/ubicaciones/read.php', { cache:'no-store' });
      if (!res.ok) return;
      const data = await res.json();
      const rows = data.ubicaciones || data.items || data.data || [];
      const sel = document.getElementById('ubicacion_id'); if (!sel) return;
      sel.innerHTML = '<option value="">— sin ubicación —</option>';
      rows.forEach(u=>{
        const opt = document.createElement('option');
        opt.value = u.id; opt.textContent = u.nombre || u.descripcion || `#${u.id}`;
        sel.appendChild(opt);
      });
    }catch(e){ console.warn('Ubicaciones options:', e); }
  }

  function lockForm(){
    document.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
    document.getElementById('btnGuardar')?.setAttribute('disabled','disabled');
  }

  async function loadTrabajo(id){
    msg('Cargando…');
    const res = await fetch(`${API_TRAB}/read.php?id=${encodeURIComponent(id)}`);
    if (!res.ok){ msg(`Error ${res.status}`, true); return; }

    let data;
    try { data = await res.json(); }
    catch { msg('Respuesta inválida del servidor', true); return; }

    if (!data || !(data.ok === true || data.status === 'ok' || data.success === true)){
      msg(data?.error || 'No se pudo cargar', true);
      return;
    }
    const t = data.item || data.data || data;

    item = t;

    setValue('trabajoId', t.id);
    setSelectValue(document.getElementById('cliente_id'),   t.cliente_id,   t.cliente_nombre);
    setValue('titulo', t.titulo || '');
    setValue('descripcion_ini', t.descripcion_ini || '');
    setValue('prioridad', t.prioridad || 'media');
    setSelectValue(document.getElementById('ubicacion_id'), t.ubicacion_id, t.ubicacion_nombre);

    if (t.estado === 'finalizado' || t.estado === 'cancelado'){
      document.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
      $('#btnGuardar')?.setAttribute('disabled','disabled');
    }
    msg('Listo');
  }

  function payloadFromForm(){
    const ubicEl     = document.getElementById('ubicacion_id');
    const clienteEl  = document.getElementById('cliente_id');
    const tituloEl   = document.getElementById('titulo');
    const descrEl    = document.getElementById('descripcion_ini');
    const prioridadEl= document.getElementById('prioridad');

    const ubic = ubicEl?.value || '';
    return {
      cliente_id: +(clienteEl?.value || 0) || null,
      titulo: (tituloEl?.value || '').trim(),
      descripcion_ini: (descrEl?.value || '').trim(),
      prioridad: prioridadEl?.value || 'media',
      ubicacion_id: ubic ? +ubic : null
    };
  }

  const form = document.getElementById('formTrabajo');

  document.getElementById('btnGuardar')?.addEventListener('click', () => {
    form?.requestSubmit();
  });

  form?.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    ev.stopPropagation();
    if (window.__trabajoSaving) return;
    window.__trabajoSaving = true;

    msg('');
    const id          = document.getElementById('trabajoId')?.value || '';
    const cliente_id  = document.getElementById('cliente_id')?.value || '';
    const titulo      = document.getElementById('titulo')?.value.trim() || '';
    const descripcion = document.getElementById('descripcion_ini')?.value.trim() || '';
    const prioridad   = document.getElementById('prioridad')?.value || 'media';
    const ubicacion   = document.getElementById('ubicacion_id')?.value || '';
    const estadoEl    = document.getElementById('estado'); // opcional

    if (!cliente_id || !titulo){
      msg('Cliente y título son obligatorios', true);
      window.__trabajoSaving = false;
      return;
    }

    const fields = { cliente_id, titulo, descripcion_ini: descripcion, prioridad };
    if (ubicacion) fields.ubicacion_id = ubicacion;
    if (id) { 
      fields.id = id; 
      if (estadoEl) fields.estado = estadoEl.value;
    }

    const url = id ? `${API_TRAB}/update.php` : `${API_TRAB}/create.php`;

    try {
      const data = await postForm(url, fields);
      msg(data.message);
      if (!id && data.data?.id){
        location.replace(`trabajos_show.html?id=${data.data.id}`);
      }
    } catch (e){
      msg(e.message, true);
    } finally {
      window.__trabajoSaving = false;
    }
  });


  document.getElementById('btnCerrar')?.addEventListener('click', async ()=>{
    msg('');
    const id = document.getElementById('trabajoId')?.value || '';
    if (!id){ msg('Primero guardá el trabajo', true); return; }

    try {
      const data = await postForm(`${API_TRAB}/close.php`, { id });
      lockForm();
      msg(data.message);
    } catch (e){
      msg(e.message, true);
    }
  });

  function getParam(name){
    const url = new URL(location.href);
    return url.searchParams.get(name);
  }

};

function msg(text, isError=false){
  const el = document.getElementById('msg');
  if (!el) return;
  el.textContent = text;
  el.style.color = isError ? 'crimson' : 'green';
}


document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('formTrabajo')) {
    window.initTrabajoShow?.();
  }
});