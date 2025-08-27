// ===== Config =====
const BASE = '../../backend/modules';
const API_VEHICULOS = `${BASE}/vehiculos/options.php`;
const API_USUARIOS  = `${BASE}/usuarios/options.php`;
const API_SALIDA    = `${BASE}/vehiculo_usos/salida.php`;
const API_REGRESO   = `${BASE}/vehiculo_usos/regreso.php`;
const API_ACTIVOS   = `${BASE}/vehiculo_usos/list_activos.php`;

const $ = (s) => document.querySelector(s);

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

async function loadOptions(selectEl, url, placeholder='-- seleccionar --') {
  const list = await fetchJSON(url);
  selectEl.innerHTML =
    `<option value="">${placeholder}</option>` +
    list.map(r => `<option value="${r.id}">${r.nombre ?? r.text ?? r.patente ?? r.id}</option>`).join('');
}

async function loadOptionsUsuarios(selectEl, url, placeholder='-- usuario --') {
  const list = await fetchJSON(url);
  selectEl.innerHTML =
    `<option value="">${placeholder}</option>` +
    list.map(u => `<option value="${u.id}" data-dni="${u.dni_legajo ?? ''}">${u.nombre}</option>`).join('');
}

async function loadActivos() {
  const tbody = $('#tbodyActivos');
  const usos = await fetchJSON(API_ACTIVOS);

  tbody.innerHTML = (usos.length ? usos.map(u => `
    <tr>
      <td>${u.id}</td>
      <td>${u.patente ?? ''}</td>
      <td>${u.descripcion ?? ''}</td>
      <td>${u.usuario_salida ?? ''}</td>
      <td>${u.fecha_salida ? new Date(u.fecha_salida).toLocaleString() : ''}</td>
      <td>${u.destino ?? ''}</td>
      <td>${u.motivo ?? ''}</td>
    </tr>`).join('') : `<tr><td colspan="7" style="text-align:center">Sin usos activos</td></tr>`);

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

function firmaPad(canvas, hiddenInput, clearBtn) {
  if (!canvas || !hiddenInput) return null;
  const ctx = canvas.getContext('2d');
  let drawing = false, last = null;

  const dpr = window.devicePixelRatio || 1;
  const w = canvas.width, h = canvas.height;
  canvas.width = w * dpr; canvas.height = h * dpr;
  canvas.style.width = w + 'px'; canvas.style.height = h + 'px';
  ctx.scale(dpr, dpr); ctx.lineWidth = 2; ctx.lineCap = 'round';

  const getXY = (e) => {
    const r = canvas.getBoundingClientRect();
    const t = (e.touches && e.touches[0]) || e;
    return { x: (t.clientX - r.left), y: (t.clientY - r.top) };
  };
  const start = (e) => { drawing = true; last = getXY(e); e.preventDefault(); };
  const move  = (e) => {
    if (!drawing) return;
    const p = getXY(e);
    ctx.beginPath(); ctx.moveTo(last.x, last.y); ctx.lineTo(p.x, p.y); ctx.stroke();
    last = p; e.preventDefault();
  };
  const end = () => { drawing = false; last = null; };

  canvas.addEventListener('mousedown', start);
  canvas.addEventListener('mousemove', move);
  canvas.addEventListener('mouseup', end);
  canvas.addEventListener('mouseleave', end);
  canvas.addEventListener('touchstart', start, {passive:false});
  canvas.addEventListener('touchmove',  move,  {passive:false});
  canvas.addEventListener('touchend',   end);

  clearBtn?.addEventListener('click', () => {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    hiddenInput.value = '';
  });

  return {
    capture() { hiddenInput.value = canvas.toDataURL('image/png'); },
    hasData() { return !!hiddenInput.value; }
  };
}

(async function init() {
  try {
    await loadOptions($('#vehiculo_id'), API_VEHICULOS, '-- vehículo --');
    await loadOptionsUsuarios($('#usuario_id_salida'),  API_USUARIOS, '-- usuario --');
    await loadOptionsUsuarios($('#usuario_id_regreso'), API_USUARIOS, '-- usuario --');

    $('#usuario_id_salida')?.addEventListener('change', (e) => {
      const opt = e.target.selectedOptions[0];
      $('#dni_salida').value = opt?.dataset?.dni || '';
    });
    $('#usuario_id_regreso')?.addEventListener('change', (e) => {
      const opt = e.target.selectedOptions[0];
      $('#dni_regreso').value = opt?.dataset?.dni || '';
    });

    $('#vehiculo_id')?.addEventListener('change', async (e) => {
      const vid = e.target.value || '';
      const url = vid ? `${API_USUARIOS}?vehiculo_id=${encodeURIComponent(vid)}` : API_USUARIOS;
      await Promise.all([
        loadOptionsUsuarios($('#usuario_id_salida'),  url, '-- usuario --'),
        loadOptionsUsuarios($('#usuario_id_regreso'), url, '-- usuario --'),
      ]).catch(err => msg(err.message, true));
      $('#dni_salida').value = '';
      $('#dni_regreso').value = '';
    });

    await loadActivos();

    const padSalida  = firmaPad($('#canvasSalida'),  $('#firma_salida_png'),  $('#btnLimpiarSalida'));
    const padRegreso = firmaPad($('#canvasRegreso'), $('#firma_regreso_png'), $('#btnLimpiarRegreso'));

    $('#formSalida')?.addEventListener('submit', async (e) => {
      e.preventDefault();
      padSalida?.capture();
      if (!$('#firma_salida_png').value) {
        return msg('Falta firmar la salida.', true);
      }
      try {
        await fetchJSON(API_SALIDA, { method:'POST', body:new FormData(e.currentTarget) });
        msg('Salida registrada');
        e.currentTarget.reset();
        $('#dni_salida').value = '';
        await loadActivos();
      } catch (err) {
        msg(err.message || 'No se pudo registrar la salida', true);
      }
    });

    $('#formRegreso')?.addEventListener('submit', async (e) => {
      e.preventDefault();
      padRegreso?.capture();
      if (!$('#firma_regreso_png').value) {
        return msg('Falta firmar el regreso.', true);
      }
      try {
        await fetchJSON(API_REGRESO, { method:'POST', body:new FormData(e.currentTarget) });
        msg('Regreso registrado');
        e.currentTarget.reset();
        $('#dni_regreso').value = '';
        await loadActivos();
      } catch (err) {
        msg(err.message || 'No se pudo registrar el regreso', true);
      }
    });

    $('#btnRefrescar')?.addEventListener('click', () =>
      loadActivos().catch(err => msg(err.message, true))
    );

  } catch (err) {
    msg(err.message || 'Error inicializando pantalla', true);
  }
})();
