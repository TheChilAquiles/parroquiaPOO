

  <!-- Layout principal: sidebar + contenido (siempre pegado a la izquierda) -->
  <div class="flex">

    <!-- Sidebar -->
    <aside class="sticky top-0 h-screen w-16 border-r border-slate-200/70 bg-white/80 backdrop-blur-sm">
      <div class="flex h-full flex-col items-center gap-3 p-2">
        <button class="group mt-2 inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
          <svg class="h-5 w-5 transition group-hover:rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <button class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-rose-500 text-white shadow-md transition hover:-translate-y-0.5 hover:bg-rose-600 hover:shadow-lg" title="Agregar">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
          </svg>
        </button>

        <div class="mt-2 flex flex-col items-center gap-3">
          <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-900">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" d="M7 7h10v10H7z"/>
            </svg>
          </button>
          <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-900">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="4"/>
            </svg>
          </button>
          <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:text-slate-900">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" d="M4 12h16M12 4v16"/>
            </svg>
          </button>
        </div>

        <div class="mt-auto mb-3 text-[10px] font-medium text-slate-400">v1.0</div>
      </div>
    </aside>

    <!-- Contenido (pegado a la izquierda, full width) -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
      <!-- Card cabecera -->
      <section class="rounded-2xl bg-white/80 p-4 sm:p-6 shadow-[0_10px_40px_-10px_rgba(84,63,157,0.20)] ring-1 ring-slate-200 backdrop-blur-sm">
        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
          <!-- logo -->
          <div class="grid h-20 w-20 place-items-center rounded-xl bg-[#1877f2] shadow-inner">
            <span class="text-4xl font-black text-white">f</span>
          </div>

          <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
              <div class="min-w-0">
                <h2 class="text-lg font-bold tracking-tight text-slate-900">FACEBOOK</h2>
                <p class="text-xs text-slate-500">Publicación enlazada</p>
                <a href="https://www.facebook.com/francisco.deasis.79274"
                   class="mt-1 block truncate text-sm text-slate-700 underline-offset-2 hover:text-slate-900 hover:underline">
                   https://www.facebook.com/francisco.deasis.79274
                </a>
              </div>

              <!-- acciones bonitas -->
              <div class="flex items-center gap-2">
                <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 2a2 2 0 0 0-2 2v18l8-4 8 4V4a2 2 0 0 0-2-2H6z"/></svg>
                  Guardar
                </button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-black/90 hover:shadow-md">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M8 12h8"/>
                  </svg>
                  Compartir
                </button>
                <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md" title="Más">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Sección Lista -->
      <section class="mt-6 rounded-2xl bg-white/80 p-4 sm:p-6 shadow-[0_10px_40px_-10px_rgba(20,43,99,0.15)] ring-1 ring-slate-200 backdrop-blur-sm">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
          <h3 class="text-sm font-bold tracking-wider text-slate-800">DIRECCIÓN Y NÚMERO DE TELÉFONO</h3>
          <div class="flex items-center gap-2">
            <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 shadow-sm transition hover:bg-slate-50">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 20l9-7-9-7-9 7 9 7z"/></svg>
              Exportar
            </button>
            <a href="#" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white shadow-sm transition hover:bg-black/90">
              Ver todo
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Item Dirección -->
        <a href="#" class="group flex flex-col gap-3 rounded-xl p-3 transition hover:bg-slate-50 sm:flex-row sm:items-center">
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
            <p class="mt-0.5 line-clamp-1 text-[13px] text-slate-600">Calle 86 a sur #81-23</p>
            <p class="mt-1 flex items-center gap-1.5 text-[12px] text-slate-500">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M12 8v4l3 3M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z"/>
              </svg>
              Today • ~ 23 min
            </p>
          </div>
          <div class="flex items-center gap-2 sm:self-stretch">
            <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M12 20l9-7-9-7-9 7 9 7z"/>
              </svg>
              Mapa
            </button>
            <button class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-black/90">
              Detalles
              <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
        </a>

        <div class="my-2 h-px bg-slate-100"></div>

        <!-- Item Teléfono -->
        <a href="tel:6014023526" class="group flex flex-col gap-3 rounded-xl p-3 transition hover:bg-slate-50 sm:flex-row sm:items-center">
          <div class="grid h-28 w-full place-items-center rounded-lg bg-slate-100 ring-1 ring-slate-200 sm:h-16 sm:w-28">
            <svg class="h-7 w-7 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.09 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.3 1.77.54 2.6a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.83.24 1.7.42 2.6.54A2 2 0 0 1 22 16.92z"/>
            </svg>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-2">
              <p class="text-sm font-semibold text-slate-900">TELÉFONO</p>
              <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-700 ring-1 ring-sky-100">Llamar</span>
            </div>
            <p class="mt-0.5 text-[13px] text-slate-600">6014023526</p>
            <p class="mt-1 text-[12px] text-slate-500">L - V 8:00 – 17:00</p>
          </div>
          <div class="flex items-center gap-2 sm:self-stretch">
            <button class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
              Copiar
            </button>
            <button class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-black/90">
              Llamar
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.09 4.18 2 2 0 0 1 4.11 2h3"/>
              </svg>
            </button>
          </div>
        </a>
      </section>
    </main>
  </div>
