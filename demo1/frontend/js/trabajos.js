// API base para Trabajos (desde /frontend/html/*.html)
const API_TRAB = '../../backend/modules/api/trabajos';

function el(id){ return document.getElementById(id); }

// =========== Trabajos: LISTADO ==========
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

    // Delegación: click en fila completa (excepto si clickean un <a>)
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

  // carga inicial
  load();
};

// helper: probar múltiples rutas y quedarnos con la primera que responda OK
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

// normalizadores por si cambian las claves
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

// =========== Trabajo: ALTA / EDICIÓN ==========
window.initTrabajoShow = function initTrabajoShow(){
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
  const API = '../../backend/modules/api/trabajos';
  const msg = (t,err=false)=>{ const m=$('msg'); if(!m)return; m.textContent=t||''; m.style.color = err?'#b00020':'#666'; };

  const qs = new URLSearchParams(location.search);
  const editingId = qs.get('id');
  const presetCliente = qs.get('cliente_id');

  // carga selects
  let item = null; // guardamos el trabajo cargado
  (async ()=>{
    const pOpts = Promise.allSettled([cargarClientes(), cargarUbicaciones()]);
    const pItem = (async ()=>{
      if (editingId) { await loadTrabajo(editingId); }
      else if (presetCliente) { setSelectValue($('#cliente_id'), presetCliente); }
    })();

    await pItem;
    await pOpts;

    // si el detalle llegó antes que las opciones, re-seleccionamos ahora
    if (item){
      setSelectValue($('#cliente_id'),  item.cliente_id,  item.cliente_nombre);
      setSelectValue($('#ubicacion_id'), item.ubicacion_id, item.ubicacion_nombre);
    }
  })();

  async function cargarClientes(){
    try{
      let res = await fetch('../../backend/modules/clientes/options.php', { cache:'no-store' });
      if (!res.ok) return;                         // si querés, poné un fallback acá
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
      if (!res.ok) return;                         // fallback opcional si tenés otra ruta
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
    const res = await fetch(`${API}/read.php?id=${encodeURIComponent(id)}`, { cache:'no-store' });
    if (!res.ok){ msg(`Error ${res.status}`, true); return; }

    let data;
    try { data = await res.json(); }
    catch { msg('Respuesta inválida del servidor', true); return; }

    if (!data.ok || !data.item){ msg(data.error||'No se pudo cargar', true); return; }

    const t = data.item;
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
    const ubic = $('#ubicacion_id').value;
    return {
      cliente_id: +$('#cliente_id').value || null,
      titulo: ($('#titulo').value||'').trim(),
      descripcion_ini: ($('#descripcion_ini').value||'').trim(),
      prioridad: $('#prioridad').value || 'media',
      ubicacion_id: ubic ? +ubic : null
    };
  }

  document.getElementById('btnGuardar')?.addEventListener('click', async ()=>{
    msg('');
    const body = payloadFromForm();
    if (!body.cliente_id || !body.titulo){ msg('Cliente y título son obligatorios', true); return; }

    const isEdit = !!document.getElementById('trabajoId').value;
    const url = isEdit ? `${API}/update.php` : `${API}/create.php`;
    const res = await fetch(url, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(isEdit ? { id:+document.getElementById('trabajoId').value, ...body } : body)
    });
    const data = await res.json();
    if (!data.ok){ msg(data.error||'No se pudo guardar', true); return; }

    if (!isEdit){
      location.href = `./trabajos_show.html?id=${data.id}`;
    }else{
      msg('Guardado');
    }
  });

  document.getElementById('btnCerrar')?.addEventListener('click', async ()=>{
    msg('');
    const id = +(document.getElementById('trabajoId').value||0);
    if (!id){ msg('Primero guardá el trabajo', true); return; }
    const res = await fetch(`${API}/close.php`, {
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ id })
    });
    const data = await res.json();
    if (!data.ok){ msg(data.error||'No se pudo cerrar', true); return; }
    lockForm();
    msg('Trabajo finalizado');
  });

  document.getElementById('btnVolver')?.addEventListener('click',(e)=>{
    e.preventDefault();
    if (document.referrer) history.back();
    else location.href = './trabajos_list.html';
  });
};