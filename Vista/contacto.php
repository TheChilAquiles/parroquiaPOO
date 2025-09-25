<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facebook Dashboard - Professional</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
  </style>
</head>
<body class="bg-slate-50">
  <div class="flex min-h-screen">
    <!-- Professional Sidebar -->
    <aside class="sticky top-0 h-screen w-20 border-r border-slate-200 bg-white shadow-sm">
      <div class="flex h-full flex-col items-center p-4">
        <!-- Logo Area -->
        <div class="mb-8 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-900 shadow-sm">
          <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </div>
        
        <!-- Navigation -->
        <nav class="flex flex-col space-y-4">
          <button class="group flex h-12 w-12 items-center justify-center rounded-lg bg-rose-500 text-white shadow-md transition-all duration-200 hover:bg-rose-600 hover:shadow-lg hover:scale-105" title="Agregar">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
            </svg>
          </button>
          
          <button class="group flex h-12 w-12 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300" title="Panel">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7"/>
              <rect x="14" y="3" width="7" height="7"/>
              <rect x="14" y="14" width="7" height="7"/>
              <rect x="3" y="14" width="7" height="7"/>
            </svg>
          </button>
          
          <button class="group flex h-12 w-12 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300" title="Estado">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="3"/>
              <path d="M12 1v6m0 6v6M7.05 7.05l4.95 4.95m0 0l4.95 4.95M1 12h6m6 0h6"/>
            </svg>
          </button>
          
          <button class="group flex h-12 w-12 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300" title="Crear">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <path d="M9 9h6v6H9z"/>
            </svg>
          </button>
        </nav>
        
        <!-- Version -->
        <div class="mt-auto text-xs font-medium text-slate-400 tracking-wide">v1.2</div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <!-- Header Section -->
      <section class="mb-8 rounded-xl bg-white p-8 shadow-sm border border-slate-200">
        <div class="flex items-start justify-between">
          <div class="flex items-start space-x-6">
            <!-- Facebook Icon -->
            <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-[#1877f2] shadow-sm">
              <span class="text-2xl font-black text-white">f</span>
            </div>
            
            <!-- Content Info -->
            <div class="flex-1">
              <div class="mb-2 flex items-center space-x-3">
                <h1 class="text-xl font-semibold text-slate-900 tracking-tight">Facebook</h1>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                  Publicación enlazada
                </span>
              </div>
              
              <a id="fbLink" 
                 href="https://www.facebook.com/francisco.deasis.79274"
                 class="block text-sm text-slate-600 hover:text-slate-900 transition-colors duration-200 underline-offset-2 hover:underline max-w-md truncate">
                https://www.facebook.com/francisco.deasis.79274
              </a>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center space-x-3">
            <button id="btnSave" 
                    class="inline-flex items-center space-x-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:border-slate-400">
              <svg id="iconSave" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6 2a2 2 0 0 0-2 2v18l8-4 8 4V4a2 2 0 0 0-2-2H6z"/>
              </svg>
              <span id="saveText">Guardar</span>
            </button>
            
            <button id="btnShare" 
                    class="inline-flex items-center space-x-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
              </svg>
              <span>Compartir</span>
            </button>
            
            <button class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:border-slate-400" title="Más opciones">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="1.5"/>
                <circle cx="6" cy="12" r="1.5"/>
                <circle cx="18" cy="12" r="1.5"/>
              </svg>
            </button>
          </div>
        </div>
      </section>

      <!-- Contact Information Section -->
      <section class="rounded-xl bg-white shadow-sm border border-slate-200 overflow-hidden">
        <!-- Section Header -->
        <div class="border-b border-slate-200 bg-slate-50 px-8 py-5">
          <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">
              Dirección y Número de Teléfono
            </h2>
            <div class="flex items-center space-x-3">
              <button class="inline-flex items-center space-x-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50" title="Exportar datos">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="7,10 12,15 17,10"/>
                  <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                <span>Exportar</span>
              </button>
              <a href="#todo" 
                 class="inline-flex items-center space-x-2 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800">
                <span>Ver todo</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9,18 15,12 9,6"/>
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="p-8 space-y-6">
          <!-- Address Item -->
          <div class="group flex items-start space-x-6 rounded-lg p-4 transition-all duration-200 hover:bg-slate-50">
            <img src="https://images.unsplash.com/photo-1501183638710-841dd1904471?q=80&w=256&auto=format&fit=crop"
                 alt="Bosa San José"
                 class="h-16 w-24 rounded-lg object-cover shadow-sm"/>
            
            <div class="min-w-0 flex-1">
              <div class="flex items-start justify-between">
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Bosa San José</h3>
                  <p id="direccion" class="mt-1 text-sm text-slate-600">Calle 86 a sur #81-23, Bogotá</p>
                  <div class="mt-2 flex items-center space-x-4 text-xs text-slate-500">
                    <span class="flex items-center space-x-1">
                      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                      </svg>
                      <span>Hoy • ~23 min</span>
                    </span>
                  </div>
                </div>
                
                <span class="inline-flex items-center space-x-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">
                  <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                  <span>Activo</span>
                </span>
              </div>
            </div>
            
            <div class="flex items-center space-x-3">
              <a id="btnMapa" 
                 href="#" 
                 target="_blank"
                 class="inline-flex items-center space-x-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                <span>Mapa</span>
              </a>
              <button id="btnDetalles"
                      class="inline-flex items-center space-x-2 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800">
                <span>Ver detalles</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9,18 15,12 9,6"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Divider -->
          <div class="border-t border-slate-200"></div>

          <!-- Phone Item -->
          <div class="group flex items-start space-x-6 rounded-lg p-4 transition-all duration-200 hover:bg-slate-50">
            <div class="flex h-16 w-24 items-center justify-center rounded-lg bg-slate-100 shadow-sm">
              <svg class="h-6 w-6 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
              </svg>
            </div>
            
            <div class="min-w-0 flex-1">
              <div class="flex items-start justify-between">
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Teléfono</h3>
                  <p id="telefono" class="mt-1 text-sm font-mono text-slate-700">601 402 3526</p>
                  <p class="mt-2 text-xs text-slate-500">Lunes - Viernes • 8:00 - 17:00</p>
                </div>
                
                <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-medium text-sky-700 border border-sky-200">
                  Disponible para llamar
                </span>
              </div>
            </div>
            
            <div class="flex items-center space-x-3">
              <button id="btnCopiar" 
                      class="inline-flex items-center space-x-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                <span>Copiar</span>
              </button>
              <a id="btnLlamar" 
                 href="tel:6014023526"
                 class="inline-flex items-center space-x-2 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <span>Llamar</span>
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- Professional Modal -->
  <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-md rounded-xl bg-white shadow-2xl border border-slate-200">
      <!-- Modal Header -->
      <div class="flex items-center justify-between border-b border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900">Detalle de la ubicación</h3>
        <button id="closeModal" 
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 transition-colors hover:bg-slate-50">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6L6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>
      
      <!-- Modal Content -->
      <div class="p-6">
        <p class="text-sm leading-relaxed text-slate-600">
          Sede parroquial Bosa San José. Atención presencial en horarios de oficina.
          Estacionamiento cercano y acceso a transporte público. Para trámites se
          recomienda llevar documento de identidad.
        </p>
      </div>
      
      <!-- Modal Footer -->
      <div class="flex items-center justify-end space-x-3 border-t border-slate-200 p-6">
        <button id="closeModal2" 
                class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
          Cerrar
        </button>
        <a id="modalMaps" 
           target="_blank" 
           class="inline-flex items-center space-x-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
          <span>Abrir en Maps</span>
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            <polyline points="15,3 21,3 21,9"/>
            <line x1="10" y1="14" x2="21" y2="3"/>
          </svg>
        </a>
      </div>
    </div>
  </div>

  <!-- Professional Toast -->
  <div id="toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden">
    <div class="rounded-lg bg-slate-900 px-4 py-3 text-sm font-medium text-white shadow-lg border border-slate-700">
      <span class="toast-message">Copiado al portapapeles</span>
    </div>
  </div>

  <script>
    const $ = (id) => document.getElementById(id);

    // Professional Save Button
    const btnSave = $('btnSave');
    const saveText = $('saveText');
    const iconSave = $('iconSave');
    let saved = false;
    
    btnSave.addEventListener('click', () => {
      saved = !saved;
      
      if (saved) {
        btnSave.classList.remove('border-slate-300', 'bg-white', 'text-slate-700');
        btnSave.classList.add('border-emerald-300', 'bg-emerald-50', 'text-emerald-700');
        iconSave.setAttribute('fill', '#059669');
        saveText.textContent = 'Guardado';
      } else {
        btnSave.classList.add('border-slate-300', 'bg-white', 'text-slate-700');
        btnSave.classList.remove('border-emerald-300', 'bg-emerald-50', 'text-emerald-700');
        iconSave.setAttribute('fill', 'currentColor');
        saveText.textContent = 'Guardar';
      }
    });

    // Professional Share
    $('btnShare').addEventListener('click', async () => {
      const url = $('fbLink').href;
      try {
        if (navigator.share) {
          await navigator.share({ 
            title: 'Parroquia – Facebook', 
            text: 'Mira este enlace', 
            url 
          });
        } else {
          await navigator.clipboard.writeText(url);
          showToast('Enlace copiado al portapapeles');
        }
      } catch (error) {
        console.log('Share cancelled or failed');
      }
    });

    // Maps URLs
    const direccion = $('direccion').innerText;
    const mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(direccion);
    $('btnMapa').href = mapsUrl;
    $('modalMaps').href = mapsUrl;

    // Phone functionality
    const tel = $('telefono').innerText.replace(/\s/g, '');
    $('btnLlamar').href = 'tel:' + tel;

    // Copy phone number
    $('btnCopiar').addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(tel);
        showToast('Teléfono copiado al portapapeles');
      } catch (error) {
        showToast('No se pudo copiar el teléfono');
      }
    });

    // Professional Modal
    const modal = $('modal');
    const openModal = () => {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    };
    
    const closeModal = () => {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    };
    
    $('btnDetalles').addEventListener('click', openModal);
    $('closeModal').addEventListener('click', closeModal);
    $('closeModal2').addEventListener('click', closeModal);
    
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });

    // Professional Toast
    function showToast(message) {
      const toast = $('toast');
      const messageElement = toast.querySelector('.toast-message');
      messageElement.textContent = message;
      
      toast.classList.remove('hidden');
      
      clearTimeout(showToast._timeout);
      showToast._timeout = setTimeout(() => {
        toast.classList.add('hidden');
      }, 3000);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
        closeModal();
      }
    });
  </script>
</body>
</html>