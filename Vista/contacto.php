  <div class="flex">
    <!-- Sidebar -->
    <aside class="sticky top-0 h-screen w-16 border-r border-slate-200/70 bg-white/80 backdrop-blur-sm">
      <div class="flex h-full flex-col items-center gap-3 p-2">
        <button class="group mt-2 inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" title="Menú">
          <svg class="h-5 w-5 transition group-hover:rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <button class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500 text-white shadow-md transition hover:-translate-y-0.5 hover:bg-rose-600 hover:shadow-lg" title="Agregar">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
        </button>
        <div class="mt-2 flex flex-col items-center gap-3">
          <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-900" title="Panel">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M7 7h10v10H7z"/></svg>
          </button>
          <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-900" title="Estado">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>
          </button>
          <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-900" title="Crear">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 12h16M12 4v16"/></svg>
          </button>
        </div>
        <div class="mt-auto mb-3 text-[10px] font-medium text-slate-400">v1.2</div>
      </div>
    </aside>

    <!-- Contenido -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
      <!-- Cabecera -->
      <section class="rounded-2xl bg-white/80 p-4 sm:p-6 shadow-[0_10px_40px_-10px_rgba(84,63,157,0.20)] ring-1 ring-slate-200 backdrop-blur-sm">
        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
          <div class="grid h-20 w-20 place-items-center rounded-xl bg-[#1877f2] shadow-inner">
            <span class="text-4xl font-black text-white">f</span>
          </div>

          <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
              <div class="min-w-0">
                <h2 class="text-lg font-bold tracking-tight text-slate-900">FACEBOOK</h2>
                <p class="text-xs text-slate-500">Publicación enlazada</p>
                <a id="fbLink"
                   href="https://www.facebook.com/francisco.deasis.79274"
                   class="mt-1 block truncate text-sm text-slate-700 underline-offset-2 hover:text-slate-900 hover:underline">
                   https://www.facebook.com/francisco.deasis.79274
                </a>
              </div>

              <div class="flex items-center gap-2">
                <!-- Guardar -->
                <button id="btnSave" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md">
                  <svg id="iconSave" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 2a2 2 0 0 0-2 2v18l8-4 8 4V4a2 2 0 0 0-2-2H6z"/></svg>
                  <span id="saveText">Guardar</span>
                </button>
                <!-- Compartir -->
                <button id="btnShare" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-black/90 hover:shadow-md">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M8 12h8"/></svg>
                  Compartir
                </button>
                <!-- Más (decorativo) -->
                <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md" title="Más">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Lista -->
      <section class="mt-6 rounded-2xl bg-white/80 p-4 sm:p-6 shadow-[0_10px_40px_-10px_rgba(20,43,99,0.15)] ring-1 ring-slate-200 backdrop-blur-sm">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
          <h3 class="text-sm font-bold tracking-wider text-slate-800">DIRECCIÓN Y NÚMERO DE TELÉFONO</h3>
          <div class="flex items-center gap-2">
            <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50" title="Descargar listado (decorativo)">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 20l9-7-9-7-9 7 9 7z"/></svg>
              Exportar
            </button>
            <a href="#todo" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white shadow-sm transition hover:bg-black/90">
              Ver todo
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>

        <!-- Item Dirección -->
        <div class="group flex flex-col gap-3 rounded-xl p-3 transition hover:bg-slate-50 sm:flex-row sm:items-center">
          <img src="https://images.unsplash.com/photo-1501183638710-841dd1904471?q=80&w=256&auto=format&fit=crop"
               alt="Bosa San José"
               class="h-28 w-full rounded-lg object-cover ring-1 ring-slate-200 sm:h-16 sm:w-28"/>
          <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-2">
              <p class="text-sm font-semibold text-slate-900">BOSA SAN JOSE</p>
              <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-100">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Activo
              </span>
            </div>
            <p id="direccion" class="mt-0.5 line-clamp-1 text-[13px] text-slate-600">Calle 86 a sur #81-23, Bogotá</p>
            <p class="mt-1 flex items-center gap-1.5 text-[12px] text-slate-500">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 8v4l3 3M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z"/></svg>
              Today • ~ 23 min
            </p>
          </div>
          <div class="flex items-center gap-2 sm:self-stretch">
            <a id="btnMapa" href="#" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 20l9-7-9-7-9 7 9 7z"/></svg>
              Mapa
            </a>
            <button id="btnDetalles"
              class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-black/90">
              Ver detalles
              <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>
        </div>

        <div class="my-2 h-px bg-slate-100"></div>

        <!-- Item Teléfono -->
        <div class="group flex flex-col gap-3 rounded-xl p-3 transition hover:bg-slate-50 sm:flex-row sm:items-center">
          <div class="grid h-28 w-full place-items-center rounded-lg bg-slate-100 ring-1 ring-slate-200 sm:h-16 sm:w-28">
            <svg class="h-7 w-7 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.09 4.18 2 2 0 0 1 4.11 2h3"/>
            </svg>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-2">
              <p class="text-sm font-semibold text-slate-900">TELÉFONO</p>
              <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-700 ring-1 ring-sky-100">Llamar</span>
            </div>
            <p id="telefono" class="mt-0.5 text-[13px] text-slate-600">6014023526</p>
            <p class="mt-1 text-[12px] text-slate-500">L - V 8:00 – 17:00</p>
          </div>
          <div class="flex items-center gap-2 sm:self-stretch">
            <button id="btnCopiar" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
              Copiar
            </button>
            <a id="btnLlamar" href="tel:6014023526"
               class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-black/90">
              Llamar
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6"/></svg>
            </a>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- Modal Detalles -->
  <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
    <div class="relative mx-4 w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200">
      <div class="mb-3 flex items-start justify-between gap-4">
        <h4 class="text-base font-semibold text-slate-900">Detalle de la ubicación</h4>
        <button id="closeModal" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
      </div>
      <p class="text-sm leading-relaxed text-slate-600">
        Sede parroquial Bosa San José. Atención presencial en horarios de oficina.
        Estacionamiento cercano y acceso a transporte público. Para trámites se
        recomienda llevar documento de identidad.
      </p>
      <div class="mt-5 flex items-center gap-2">
        <a id="modalMaps" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-black/90">
          Abrir en Maps
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
        </a>
        <button id="closeModal2" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50">
          Cerrar
        </button>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div id="toast" class="pointer-events-none fixed bottom-4 right-4 z-50 hidden">
    <div class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-lg">Copiado al portapapeles</div>
  </div>

<script>
  const $ = (id) => document.getElementById(id);

  // Estado "Guardar"
  const btnSave = $('btnSave');
  const saveText = $('saveText');
  const iconSave = $('iconSave');
  let saved = false;
  btnSave.addEventListener('click', () => {
    saved = !saved;
    btnSave.classList.toggle('bg-emerald-50', saved);
    btnSave.classList.toggle('border-emerald-200', saved);
    btnSave.classList.toggle('text-emerald-700', saved);
    iconSave.setAttribute('fill', saved ? '#059669' : 'currentColor');
    saveText.textContent = saved ? 'Guardado' : 'Guardar';
  });

  // Compartir (con fallback a copiar)
  $('btnShare').addEventListener('click', async () => {
    const url = $('fbLink').href;
    try {
      if (navigator.share) {
        await navigator.share({ title: 'Parroquia – Facebook', text: 'Mira este enlace', url });
      } else {
        await navigator.clipboard.writeText(url);
        toast('Enlace copiado');
      }
    } catch { /* cancelado */ }
  });

  // Construir URL de Maps
  const direccion = $('direccion').innerText;
  const mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(direccion);
  $('btnMapa').href = mapsUrl;
  $('modalMaps').href = mapsUrl;

  // Teléfono
  const tel = $('telefono').innerText.replace(/\s/g,'');
  $('btnLlamar').href = 'tel:' + tel;

  // Copiar teléfono
  $('btnCopiar').addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(tel);
      toast('Teléfono copiado');
    } catch {
      toast('No se pudo copiar');
    }
  });

  // Modal
  const modal = $('modal');
  const openModal = () => modal.classList.remove('hidden');
  const closeModal = () => modal.classList.add('hidden');
  $('btnDetalles').addEventListener('click', openModal);
  $('closeModal').addEventListener('click', closeModal);
  $('closeModal2').addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

  // Toast
  function toast(msg){
    const t = $('toast');
    t.querySelector('div').textContent = msg;
    t.classList.remove('hidden');
    clearTimeout(toast._t);
    toast._t = setTimeout(()=>t.classList.add('hidden'), 1600);
  }
</script>

