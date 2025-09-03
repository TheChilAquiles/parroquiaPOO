<div class="max-w-7xl mx-auto p-6">
  <!-- Header -->
  <header class="flex items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
      <div class="h-12 w-12 rounded-lg brand flex items-center justify-center shadow-lg">
        <span class="text-xl font-bold">PS</span>
      </div>
      <div>
        <h1 class="text-2xl font-bold">Panel de reportes</h1>
        <p class="text-sm text-slate-500">Todos los registros están centralizados — filtra, exporta o revisa detalles.</p>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button id="exportCsv" class="inline-flex items-center gap-2 rounded-lg bg-[#1877f2] px-4 py-2 text-white shadow hover:brightness-95">
        Exportar CSV
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
        </svg>
      </button>
      <button id="newReport" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 hover:shadow">Nuevo</button>
    </div>
  </header>

  <!-- Controls -->
  <section class="bg-white rounded-2xl p-4 shadow-sm mb-6 ring-1 ring-slate-100">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <div class="sm:col-span-2 flex gap-2">
        <input id="search" type="text" placeholder="Buscar por título, descripción, id..." class="flex-1 rounded-lg border p-2 focus:ring-2 focus:ring-[#cfa3ff]" />
        <select id="stateFilter" class="rounded-lg border p-2">
          <option value="">Estado (todos)</option>
          <option value="pendiente">Pendiente</option>
          <option value="completado">Completado</option>
          <option value="cancelado">Cancelado</option>
        </select>
      </div>

      <div class="flex gap-2 items-center">
        <label class="text-xs text-slate-500 mr-2">Desde</label>
        <input id="dateFrom" type="date" class="rounded-lg border p-2" />
      </div>

      <div class="flex gap-2 items-center">
        <label class="text-xs text-slate-500 mr-2">Hasta</label>
        <input id="dateTo" type="date" class="rounded-lg border p-2" />
      </div>
    </div>
  </section>

  <!-- Tabla -->
  <section class="bg-white rounded-2xl p-4 shadow-sm ring-1 ring-slate-100">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y">
        <thead class="bg-[linear-gradient(90deg,#f8f4ff,#fbfbff)]">
          <tr>
            <th class="p-3 text-left text-sm font-semibold">ID <button data-col="id" class="sort-btn text-xs ml-2 text-slate-400">↕</button></th>
            <th class="p-3 text-left text-sm font-semibold">Título <button data-col="titulo" class="sort-btn text-xs ml-2 text-slate-400">↕</button></th>
            <th class="p-3 text-left text-sm font-semibold">Descripción</th>
            <th class="p-3 text-left text-sm font-semibold">Fecha <button data-col="fecha" class="sort-btn text-xs ml-2 text-slate-400">↕</button></th>
            <th class="p-3 text-left text-sm font-semibold">Categoría <button data-col="categoria" class="sort-btn text-xs ml-2 text-slate-400">↕</button></th>
            <th class="p-3 text-left text-sm font-semibold">Pago (ID)</th>
            <th class="p-3 text-center text-sm font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody id="tableBody" class="divide-y"></tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between">
      <div>
        <label class="text-sm">Mostrar</label>
        <select id="perPage" class="ml-2 rounded-lg border p-1">
          <option>5</option>
          <option selected>10</option>
          <option>25</option>
        </select>
        <span class="ml-2 text-sm text-slate-500" id="rangeInfo"></span>
      </div>

      <div class="flex items-center gap-2">
        <button id="prevPage" class="rounded-lg px-3 py-1 border">Anterior</button>
        <div id="pageNumbers" class="flex items-center gap-2"></div>
        <button id="nextPage" class="rounded-lg px-3 py-1 border">Siguiente</button>
      </div>
    </div>
  </section>
</div>

<!-- Modal detalle -->
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center">
  <div class="absolute inset-0 bg-black/40"></div>
  <div class="relative w-[90%] max-w-2xl bg-white rounded-2xl p-6 shadow-2xl ring-1 ring-slate-200">
    <div class="flex items-start justify-between">
      <h3 class="text-lg font-semibold">Detalle del registro</h3>
      <button id="closeModal" class="text-slate-500 hover:text-slate-800">Cerrar ✕</button>
    </div>
    <div id="modalContent" class="mt-4 space-y-3 text-sm text-slate-700"></div>
  </div>
</div>

<script>
/* ========== Estado UI / variables ========== */
let data = [];
let sortField = null, sortDir = 1;
let currentPage = 1, perPage = 10;

/* ========== Helpers ========== */
const $ = id => document.getElementById(id);

function applyFiltersAndRender(){
  const q = $('search').value.trim().toLowerCase();
  const state = $('stateFilter').value;
  const from = $('dateFrom').value;
  const to = $('dateTo').value;

  let filtered = data.filter(r => {
    if(state && r.categoria !== state) return false;
    if(q){
      const hay = (r.titulo + ' ' + r.descripcion + ' ' + r.id).toLowerCase();
      if(!hay.includes(q)) return false;
    }
    if(from && r.fecha < from) return false;
    if(to && r.fecha > to) return false;
    return true;
  });

  if(sortField){
    filtered.sort((a,b)=> {
      let A = a[sortField], B = b[sortField];
      if(sortField === 'fecha') { A = new Date(A); B = new Date(B); }
      if(A < B) return -1 * sortDir;
      if(A > B) return 1 * sortDir;
      return 0;
    });
  }

  data = filtered;
  currentPage = 1;
  renderTable();
}

/* ========== Render tabla + paginación ========== */
function renderTable(){
  const tbody = $('tableBody');
  tbody.innerHTML = '';

  perPage = Number($('perPage').value);
  const total = data.length;
  const pages = Math.max(1, Math.ceil(total / perPage));
  currentPage = Math.min(currentPage, pages);
  const start = (currentPage-1)*perPage;
  const end = start + perPage;
  const pageSlice = data.slice(start,end);

  pageSlice.forEach(row => {
    const tr = document.createElement('tr');
    tr.className = 'hover:bg-slate-50';
    tr.innerHTML = `
      <td class="p-3 text-sm">${row.id}</td>
      <td class="p-3 text-sm font-medium">${row.titulo}</td>
      <td class="p-3 text-sm text-slate-600">${row.descripcion}</td>
      <td class="p-3 text-sm">${row.fecha}</td>
      <td class="p-3 text-sm">${row.categoria}</td>
      <td class="p-3 text-sm">${row.id_pagos}</td>
      <td class="p-3 text-sm text-center">
        <button data-id="${row.id}" class="viewBtn inline-flex items-center gap-2 rounded-lg bg-[#6d0ba9] px-3 py-1 text-white text-xs">Ver</button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  $('rangeInfo').textContent = `${Math.min(total, start+1)} - ${Math.min(total, end)} de ${total}`;
  const pageNumbers = $('pageNumbers');
  pageNumbers.innerHTML = '';
  for(let p=1;p<=pages;p++){
    const btn = document.createElement('button');
    btn.textContent = p;
    btn.className = `px-3 py-1 rounded ${p===currentPage ? 'bg-[#6d0ba9] text-white' : 'border'}`;
    btn.onclick = ()=> { currentPage = p; renderTable(); };
    pageNumbers.appendChild(btn);
  }

  $('prevPage').disabled = currentPage === 1;
  $('nextPage').disabled = currentPage === pages;

  document.querySelectorAll('.viewBtn').forEach(b=>{
    b.addEventListener('click', ()=> openModal(Number(b.dataset.id)));
  });
}

/* ========== Modal ========== */
function openModal(id){
  const rec = data.find(x=>x.id===id);
  if(!rec) return;
  $('modalContent').innerHTML = `
    <div class="grid grid-cols-1 gap-3">
      <div><strong>ID:</strong> ${rec.id}</div>
      <div><strong>Título:</strong> ${rec.titulo}</div>
      <div><strong>Descripción:</strong> ${rec.descripcion}</div>
      <div><strong>Fecha:</strong> ${rec.fecha}</div>
      <div><strong>Categoría:</strong> ${rec.categoria}</div>
      <div><strong>ID Pago:</strong> ${rec.id_pagos}</div>
    </div>
  `;
  $('modal').classList.remove('hidden');
}
function closeModal(){ $('modal').classList.add('hidden'); }

/* ========== Export CSV ========== */
function exportCsv(){
  const header = ['id','titulo','descripcion','fecha','categoria','id_pagos'];
  const rows = data.map(r => header.map(k => `"${String(r[k]).replace(/"/g,'""')}"`).join(','));
  const csv = [header.join(','), ...rows].join('\n');
  const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'reportes.csv';
  a.click();
  URL.revokeObjectURL(url);
}

/* ========== Cargar datos reales ========== */
async function cargarDatos(){
  try {
    const res = await fetch('controlador/reportes.php?accion=listar');
    const json = await res.json();
    data = json;
    renderTable();
  } catch (e) {
    console.error("Error cargando datos:", e);
  }
}

/* ========== Eventos ========== */
document.addEventListener('DOMContentLoaded', () => {
  cargarDatos();

  $('search').addEventListener('input', debounce(()=>applyFiltersAndRender(), 300));
  $('stateFilter').addEventListener('change', applyFiltersAndRender);
  $('dateFrom').addEventListener('change', applyFiltersAndRender);
  $('dateTo').addEventListener('change', applyFiltersAndRender);
  $('perPage').addEventListener('change', ()=> { currentPage = 1; renderTable(); });

  $('prevPage').addEventListener('click', ()=> { currentPage--; renderTable(); });
  $('nextPage').addEventListener('click', ()=> { currentPage++; renderTable(); });

  document.querySelectorAll('.sort-btn').forEach(b=>{
    b.addEventListener('click', ()=> {
      const col = b.dataset.col;
      if(sortField === col) sortDir = -sortDir; else { sortField = col; sortDir = 1; }
      applyFiltersAndRender();
    });
  });

  $('closeModal').addEventListener('click', closeModal);
  $('modal').addEventListener('click', (e)=> { if(e.target === $('modal')) closeModal(); });

  $('exportCsv').addEventListener('click', exportCsv);

  $('newReport').addEventListener('click', ()=> alert('Crear nuevo registro — integrar con backend.'));
});

/* ========== Utilidad: debounce ========== */
function debounce(fn, ms=200){ let t; return (...a)=>{ clearTimeout(t); t = setTimeout(()=>fn(...a), ms); }; }
</script>
