// ================================
// PARROQUIA SAN FRANCISCO DE ASÍS
// JavaScript para página de información
// ================================

/**
 * DATOS COMPLETOS DE LOS MINISTERIOS
 * Información detallada inventada para cada ministerio
 */
const ministryData = {
    'legion': {
        title: 'Legión de María',
        subtitle: 'Apostolado Mariano - "Regina Sine Labe"',
        image: 'https://i.pinimg.com/originals/90/c9/30/90c930833a634c3ee26925ebdded25cf.png',
        description: 'Somos 35 legionarios comprometidos que trabajamos bajo la dirección espiritual de la Santísima Virgen María. Nuestro presidium "Regina Sine Labe" fue fundado en 2005 y se dedica especialmente a la visita domiciliaria, evangelización de familias alejadas de la fe, y el acompañamiento a enfermos. Cada semana realizamos obras de misericordia espiritual y corporal, llevando la luz del Evangelio a los rincones más necesitados de nuestra comunidad.',
        schedule: 'Reuniones todos los sábados de 4:00 a 5:30 PM en el Salón San Francisco. Incluye: Oración inicial, lectura espiritual, asignación de trabajos apostólicos, y bendición final. Los domingos realizamos visitas familiares de 3:00 a 6:00 PM.',
        participation: 'Abierto a católicos practicantes mayores de 16 años. Requisitos: Primera Comunión y Confirmación, compromiso semanal mínimo 3 horas, participación en retiro anual. Formación inicial de 3 meses con manual oficial de la Legión.',
        achievements: 'En 2023: Visitamos 450 familias, acompañamos 28 enfermos terminales, organizamos 12 charlas de evangelización, y logramos el retorno de 67 personas a la práctica sacramental.'
    },
    'monaguillos': {
        title: 'Monaguillos San Francisco',
        subtitle: 'Servidores del Altar - "Pequeños Franciscanos"',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMc6kqE_oMj4Rpwazy6dHq8iLnjb_7hLPcXg&s',
        description: 'Nuestro grupo "Pequeños Franciscanos" está compuesto por 18 niños y jóvenes (8-17 años) que sirven al altar con devoción y alegría. Más que asistentes litúrgicos, son verdaderos discípulos que aprenden el valor del servicio, la responsabilidad y la oración. Participan activamente en misas dominicales, celebraciones especiales, procesiones y adoración eucarística. Su formación incluye liturgia, espiritualidad franciscana y desarrollo personal.',
        schedule: 'Ensayos y formación: Viernes de 4:00 a 5:30 PM. Servicio litúrgico: Turnos rotativos en todas las misas (incluye misas de difuntos y celebraciones especiales). Retiro mensual: Primer sábado de cada mes.',
        participation: 'Niños y jóvenes de 8 a 17 años. Proceso de ingreso: Entrevista familiar, curso básico de liturgia (8 sesiones), período de prueba de 2 meses. Se proporciona alba, cíngulo y manual de ceremonias. Compromiso mínimo: 1 año.',
        achievements: 'Reconocimiento diocesano 2022 como "Mejor Grupo de Monaguillos". Participación en Jornada Mundial de la Juventud (3 monaguillos). Proyecto solidario: Recaudación de $800,000 para familias vulnerables.'
    },
    'lectores': {
        title: 'Ministerio de la Palabra',
        subtitle: 'Proclamadores Certificados - "Voz de Cristo"',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0cLzgMrZVQLhFurSO7MqGfUeBGaPWEt6eqA&s',
        description: 'Somos 25 lectores certificados por la Arquidiócesis que proclamamos la Palabra de Dios con preparación técnica y espiritual profunda. No solo leemos, sino que transmitimos el mensaje divino con claridad, reverencia y convicción. Nuestro ministerio incluye las lecturas bíblicas, salmos responsoriales, oración de los fieles, y ocasionalmente homilías para laicos. Participamos en la preparación litúrgica semanal y en la formación bíblica permanente.',
        schedule: 'Formación semanal: Viernes de 4:30 a 6:00 PM (estudio bíblico y técnicas de proclamación). Ensayos especiales antes de celebraciones importantes. Retiro anual de 3 días para profundización espiritual y técnica.',
        participation: 'Adultos y jóvenes mayores de 16 años con excelente dicción y comprensión lectora. Proceso: Curso intensivo de 6 meses (Sagrada Escritura, hermenéutica, técnica vocal, liturgia). Certificación diocesana obligatoria. Renovación cada 3 años.',
        achievements: 'Diploma de excelencia diocesano 2023. Implementación de sistema Braille para personas con discapacidad visual. Traducción simultánea en lengua de señas en misas principales. Programa radial semanal "La Palabra Viva".'
    },
    'coro': {
        title: 'Coro "Voces de Asís"',
        subtitle: 'Ministerio de Música Sacra - 20 años de historia',
        image: '',
        description: 'Fundado en 2003, nuestro coro "Voces de Asís" es reconocido a nivel diocesano por su calidad artística y compromiso litúrgico. Conformado por 22 coristas de diversas edades y nuestro director musical Maestro Fernando Castillo (Conservatorio Nacional), interpretamos repertorio gregoriano, polifónico, contemporáneo y latinoamericano. Más que música, ofrecemos oración cantada que eleva el espíritu y facilita el encuentro con Dios.',
        schedule: 'Ensayos generales: Jueves de 7:00 a 9:00 PM en el templo (acústica natural). Ensayos por cuerdas: Martes y miércoles según cronograma. Participación en todas las misas dominicales, festividades litúrgicas y conciertos de música sacra.',
        participation: 'Prueba de aptitud vocal obligatoria (no se requiere experiencia previa). Formación musical básica incluida: solfeo, respiración, técnica vocal, historia de la música sacra. Compromiso: Mínimo 80% de asistencia a ensayos y presentaciones.',
        achievements: 'Grabación de CD "Cantos Franciscanos" (2022). Participación en Festival Diocesano de Música Sacra (3 años consecutivos). Concierto benéfico anual recauda en promedio $2 millones. Intercambio musical con coros de Medellín y Cali.'
    },
    'catequistas': {
        title: 'Escuela de Catequistas "San Francisco"',
        subtitle: 'Formadores en la Fe - Pedagogía Franciscana',
        image: '',
        description: 'Nuestro equipo de 15 catequistas especializados atiende a 180 niños, 45 jóvenes y 30 adultos en proceso de formación sacramental. Utilizamos metodología franciscana centrada en la experiencia, el testimonio personal y la pedagogía del amor. Contamos con aulas dotadas de material audiovisual, biblioteca infantil, y recursos digitales innovadores. La catequesis no es solo preparación sacramental, sino acompañamiento integral en el crecimiento humano y cristiano.',
        schedule: 'Catequesis infantil (Primera Comunión): Domingos 9:00-10:30 AM. Catequesis juvenil (Confirmación): Domingos 4:00-5:30 PM. Catequesis de adultos: Miércoles 7:00-8:30 PM. Formación permanente de catequistas: Sábados 2:00-4:00 PM mensual.',
        participation: 'Catequistas: Adultos con formación cristiana sólida, curso de pedagogía religiosa (120 horas), certificación arquidiocesana. Padres de familia: Participación obligatoria en 4 talleres anuales. Apoyo psicopedagógico disponible.',
        achievements: 'Programa "Catequesis Digital" premiado nacionalmente. Tasa de perseverancia sacramental: 92%. Proyecto "Familias Catequizadoras" involucra 85 hogares. Material didáctico propio editado y distribuido en 15 parroquias vecinas.'
    },
    'social': {
        title: 'Pastoral Social "Manos Franciscanas"',
        subtitle: 'Opción Preferencial por los Pobres - Caridad Organizada',
        image: '',
        description: 'Inspirados en el carisma franciscano, nuestros 40 voluntarios trabajan incansablemente con las familias más vulnerables del sector. "Manos Franciscanas" no solo asiste necesidades materiales, sino que promueve desarrollo humano integral, dignidad personal y construcción de tejido social. Operamos con criterios de sostenibilidad, transparencia y acompañamiento personalizado. Somos reconocidos por la Alcaldía como entidad de desarrollo social comunitario.',
        schedule: 'Reuniones de coordinación: Sábados 2:00-4:00 PM. Visitas domiciliarias: Miércoles y sábados mañana. Distribución de mercados: Primer sábado de cada mes. Talleres formativos: Jueves 6:00-8:00 PM (alfabetización, oficios, emprendimiento).',
        participation: 'Voluntarios mayores de 18 años con sensibilidad social. Formación inicial: Doctrina Social de la Iglesia, trabajo comunitario, primeros auxilios básicos. Disponibilidad mínima: 4 horas semanales. Evaluación y acompañamiento psicosocial.',
        achievements: 'Atención permanente a 120 familias. Centro de Desarrollo Infantil (50 niños en desnutrición). Microcréditos para 25 emprendimientos familiares. Huerta comunitaria produce 200 kg de alimentos mensuales. Convenio con SENA para capacitación técnica.'
    },
    'juvenil': {
        title: 'Pastoral Juvenil "Francisco Digital"',
        subtitle: 'Jóvenes del Siglo XXI - Fe y Tecnología',
        image: '',
        description: 'Somos 45 jóvenes entre 14 y 28 años que vivimos la fe franciscana en clave contemporánea. "Francisco Digital" combina espiritualidad tradicional con herramientas tecnológicas innovadoras: misas transmitidas en vivo, aplicaciones de oración, podcast juvenil "FeConecta", y voluntariado virtual. Organizamos retiros, conciertos, obras teatrales, competencias deportivas y proyectos de impacto social. Nuestro lema: "Antiguos en la fe, modernos en el método".',
        schedule: 'Encuentros generales: Sábados 6:00-8:30 PM (dinámicas, formación, oración). Células de crecimiento: Miércoles por grupos etarios. Retiros mensuales: Segundo fin de semana completo. Actividades especiales: Según calendario litúrgico y necesidades pastorales.',
        participation: 'Jóvenes de 14 a 28 años sin requisitos previos. Proceso gradual: Integración social, formación básica, compromiso pastoral, liderazgo. Acompañamiento personalizado por asesores especializados en pastoral juvenil. Certificación en competencias pastorales.',
        achievements: 'Canal de YouTube "Francisco Digital": 15,000 suscriptores. App móvil "OraConnect" descargada 8,000 veces. Misión juvenil en Venezuela (12 jóvenes, 2022). Festival "Rock por la Vida" convoca 2,000 personas anualmente. Escuela de liderazgo juvenil reconocida diocesanamente.'
    },
    'musica': {
        title: 'Ministerio Musical Instrumental',
        subtitle: 'Músicos al Servicio de la Liturgia - Salmistas Modernos',
        image: '',
        description: 'Nuestro ministerio cuenta con 12 músicos profesionales y semiprofesionales que embellecen la liturgia con instrumentos diversos: piano, guitarra, violín, flauta, batería, bajo, teclados y percusión menor. Más allá de acompañar cantos, creamos atmósferas de oración, facilitamos la contemplación y enriquecen la experiencia litúrgica. Colaboramos estrechamente con el coro "Voces de Asís" y participamos en grandes celebraciones diocesanas.',
        schedule: 'Ensayos técnicos: Miércoles 7:30-9:30 PM (repertorio y arreglos). Ensayos conjuntos con coro: Primer jueves de mes. Participación litúrgica: Misas dominicales de 11 AM y 6 PM, festividades especiales. Conciertos: Trimestrales con fines benéficos.',
        participation: 'Músicos con formación básica en su instrumento (audición requerida). Capacidad de lectura musical deseable pero no indispensable. Disponibilidad para ensayos y compromisos litúrgicos. Formación permanente en música sacra y espiritualidad litúrgica.',
        achievements: 'Arreglos musicales originales para 50 cantos litúrgicos. Participación en ordenaciones sacerdotales (2019-2023). Escuela de música parroquial: 35 estudiantes en formación. Colaboración con conservatorio local para becas estudiantiles.'
    },
    'oracion': {
        title: 'Grupo de Oración "Corazones en Oración"',
        subtitle: 'Renovación Carismática - Pentecostés Permanente',
        image: '',
        description: 'Desde 2008, "Corazones en Oración" es el corazón carismático de nuestra parroquia. Somos 60 miembros regulares que experimentamos el poder transformador del Espíritu Santo a través de la oración espontánea, los carismas, la adoración profunda y la sanación integral. No somos un grupo cerrado, sino una comunidad abierta que busca renovar la Iglesia desde dentro, llevando frescura espiritual y fervor evangelizador a toda la comunidad parroquial.',
        schedule: 'Reuniones principales: Martes 7:00-9:30 PM con adoración, alabanza, oración por sanación, testimonios y formación bíblica. Vigilias mensuales: Primer viernes de cada mes, 9 PM - 6 AM. Retiros trimestrales de fin de semana en casa de oración campestre.',
        participation: 'Católicos bautizados y confirmados con deseo sincero de renovación espiritual. Seminario de Vida en el Espíritu (7 semanas) como preparación. Compromiso gradual sin presiones. Acompañamiento espiritual personalizado disponible. Formación continua en carismas y discernimiento.',
        achievements: 'Encuentro Carismático Diocesano 2023: 1,200 participantes. Ministerio de sanación: 847 testimonios documentados de sanación (física, emocional, espiritual). Programa radial "Soplo de Vida" en emisora católica. Escuela de oración con 8 niveles formativos.'
    }
};

/**
 * DATOS DE ARQUITECTURA DEL TEMPLO
 * Información detallada de los espacios sagrados
 */
const architectureData = {
    'altar': {
        title: 'Altar Mayor',
        icon: 'church',
        description: 'Nuestro altar mayor es una obra de arte labrada en mármol de Carrara importado especialmente desde Italia en 2010. Diseñado por el arquitecto Francisco Restrepo siguiendo cánones franciscanos, representa la mesa del banquete celestial donde Cristo se hace presente diariamente en la Eucaristía.',
        specs: 'Dimensiones: 3.50m x 1.20m x 1.05m • Material: Mármol de Carrara con incrustaciones de bronce • Peso: 2.8 toneladas • Relieves: Escenas franciscanas labradas a mano • Consagración: 15 de octubre de 2010 por Mons. Daniel Caro • Reliquia: Fragmento del sepulcro de San Francisco de Asís'
    },
    'baptistery': {
        title: 'Baptisterio San Juan Bautista',
        icon: 'water_drop',
        description: 'Lugar donde nacemos a la vida cristiana, nuestro baptisterio es una joya arquitectónica que simboliza el sepulcro místico donde morimos al pecado y resucitamos como hijos de Dios. La pila bautismal, tallada en piedra volcánica de origen colombiano, tiene capacidad para bautismo por inmersión.',
        specs: 'Pila bautismal: Piedra volcánica de 1.80m de diámetro • Capacidad: 400 litros de agua bendita • Sistema de filtrado automático • Mosaicos: Representación del Jordán en técnica bizantina • Vitrales: 12 paneles con escenas bautismales • Promedio anual: 145 bautizos celebrados'
    },
    'virgin': {
        title: 'Altar Mariano',
        icon: 'favorite',
        description: 'Dedicado a Nuestra Señora de los Ángeles, patrona franciscana, este altar lateral es un remanso de paz donde los fieles acuden para pedir la intercesión maternal de María. La imagen titular fue traída desde Asís en 1998 y ha sido testigo de innumerables milagros y favores concedidos.',
        specs: 'Imagen principal: Nuestra Señora de los Ángeles (réplica oficial de Asís) • Altura del altar: 2.10m • Material: Madera de cedro con pan de oro • Exvotos documentados: 1,247 testimonios • Rosario perpetuo: 24 horas diarias • Novenas especiales: Cada 2 de agosto'
    },
    'chapel': {
        title: 'Capilla de Adoración Perpetua',
        icon: 'auto_stories',
        description: 'Inaugurada en 2018, nuestra capilla de adoración eucarística funciona las 24 horas con turnos organizados de fieles adoradores. El ambiente recogido, la iluminación tenue y el silencio sagrado crean el espacio perfecto para el encuentro íntimo con Jesús Sacramentado.',
        specs: 'Capacidad: 15 adoradores simultáneos • Custodia: Plata y oro, donada por familia Hernández • Turnos diarios: 48 períodos de 30 minutos • Adoradores registrados: 120 personas • Sistema de seguridad: Cámaras 24/7 • Milagro eucarístico documentado: 3 de mayo de 2021'
    }
};

/**
 * CLASE PRINCIPAL PARA MANEJAR LA PÁGINA
 */
class ParroquiaInformacion {
    constructor() {
        this.initializeScrollAnimations();
        this.initializeFilters();
        this.setupEventListeners();
        this.initializeCounters();
        this.loadSavedPreferences();
    }

    /**
     * ANIMACIONES DE SCROLL
     */
    initializeScrollAnimations() {
        // Configuración del Intersection Observer para animaciones
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate', 'revealed');
                    
                    // Animación especial para contadores
                    if (entry.target.classList.contains('counter-number')) {
                        this.animateCounter(entry.target);
                    }
                }
            });
        }, observerOptions);

        // Observar elementos con animaciones
        const animatedElements = document.querySelectorAll('.timeline-item, .scroll-reveal, .counter-number');
        animatedElements.forEach(element => observer.observe(element));
    }

    /**
     * ANIMACIÓN DE CONTADORES ESTADÍSTICOS
     */
    animateCounter(element) {
        const finalValue = parseInt(element.textContent.replace(/\D/g, ''));
        const duration = 2000;
        const increment = finalValue / (duration / 16);
        let currentValue = 0;

        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                element.textContent = element.textContent.replace(/\d+/, finalValue);
                clearInterval(timer);
            } else {
                element.textContent = element.textContent.replace(/\d+/, Math.floor(currentValue));
            }
        }, 16);
    }

    /**
     * SISTEMA DE FILTROS PARA MINISTERIOS
     */
    initializeFilters() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const ministryItems = document.querySelectorAll('.ministry-item');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Actualizar botón activo
                filterButtons.forEach(b => {
                    b.classList.remove('active', 'bg-[#D0B8A8]', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700');
                });
                
                btn.classList.add('active', 'bg-[#D0B8A8]', 'text-white');
                btn.classList.remove('bg-gray-200', 'text-gray-700');

                const filter = btn.getAttribute('data-filter');
                this.filterMinistries(filter, ministryItems);
                
                // Guardar preferencia
                localStorage.setItem('parroquiaFilter', filter);
            });
        });
    }

    /**
     * FILTRAR MINISTERIOS CON ANIMACIONES
     */
    filterMinistries(filter, items) {
        items.forEach(item => {
            const category = item.getAttribute('data-category');
            const shouldShow = filter === 'all' || category === filter;

            if (shouldShow) {
                item.style.display = 'block';
                item.classList.remove('fade-out');
                item.classList.add('fade-in');
                
                // Animación de entrada retardada
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 100);
            } else {
                item.classList.add('fade-out');
                item.classList.remove('fade-in');
                
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }

    /**
     * CONFIGURAR TODOS LOS EVENT LISTENERS
     */
    setupEventListeners() {
        // Scroll suave para navegación
        document.addEventListener('click', (e) => {
            if (e.target.hasAttribute('onclick') && e.target.getAttribute('onclick').includes('scrollToSection')) {
                e.preventDefault();
                const sectionId = e.target.getAttribute('onclick').match(/'([^']+)'/)[1];
                this.scrollToSection(sectionId);
            }
        });

        // Cerrar modales con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal('ministry-modal');
                this.closeModal('architecture-modal');
            }
        });

        // Cerrar modales clickeando fuera
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                this.closeModal(e.target.id);
            }
        });

        // Efecto parallax mejorado
        window.addEventListener('scroll', this.handleParallax.bind(this));
        
        // Responsive handling
        window.addEventListener('resize', this.handleResize.bind(this));
    }

    /**
     * SCROLL SUAVE A SECCIÓN ESPECÍFICA
     */
    scrollToSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            // Actualizar URL sin recargar página
            history.replaceState(null, null, `#${sectionId}`);
        }
    }

    /**
     * EFECTO PARALLAX OPTIMIZADO
     */
    handleParallax() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.parallax-bg');
        
        parallaxElements.forEach(element => {
            const speed = 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });

        // Efecto para elementos flotantes
        const floatingElements = document.querySelectorAll('.floating-animation');
        floatingElements.forEach((element, index) => {
            const speed = 0.1 + (index * 0.05);
            const yPos = scrolled * speed;
            element.style.transform += ` translateY(${yPos}px)`;
        });
    }

    /**
     * MANEJO RESPONSIVE
     */
    handleResize() {
        // Ajustar parallax en móviles
        const isMobile = window.innerWidth < 768;
        const parallaxElements = document.querySelectorAll('.parallax-bg');
        
        parallaxElements.forEach(element => {
            if (isMobile) {
                element.style.backgroundAttachment = 'scroll';
            } else {
                element.style.backgroundAttachment = 'fixed';
            }
        });
    }

    /**
     * INICIALIZAR CONTADORES ESTADÍSTICOS
     */
    initializeCounters() {
        const counters = [
            { element: document.querySelector('[data-counter="families"]'), target: 800, suffix: '+' },
            { element: document.querySelector('[data-counter="ministries"]'), target: 12, suffix: '' },
            { element: document.querySelector('[data-counter="masses"]'), target: 2500, suffix: '' },
            { element: document.querySelector('[data-counter="sacraments"]'), target: 450, suffix: '' }
        ];

        // Los contadores se activan con Intersection Observer
        this.countersData = counters;
    }

    /**
     * CARGAR PREFERENCIAS GUARDADAS
     */
    loadSavedPreferences() {
        const savedFilter = localStorage.getItem('parroquiaFilter');
        if (savedFilter && savedFilter !== 'all') {
            const filterBtn = document.querySelector(`[data-filter="${savedFilter}"]`);
            if (filterBtn) {
                filterBtn.click();
            }
        }

        // Cargar tema si existe
        const savedTheme = localStorage.getItem('parroquiaTheme');
        if (savedTheme) {
            document.body.classList.add(`theme-${savedTheme}`);
        }
    }

    /**
     * ABRIR MODAL DE MINISTERIO
     */
    openMinistryModal(ministry) {
        const data = ministryData[ministry];
        if (!data) return;

        // Poblar contenido del modal
        document.getElementById('modal-title').textContent = data.title;
        document.getElementById('modal-subtitle').textContent = data.subtitle;
        document.getElementById('modal-description').textContent = data.description;
        document.getElementById('modal-schedule').textContent = data.schedule;
        document.getElementById('modal-participation').textContent = data.participation;
        document.getElementById('modal-achievements').textContent = data.achievements;

        // Manejar imagen
        const modalImage = document.getElementById('modal-image');
        if (data.image) {
            modalImage.src = data.image;
            modalImage.style.display = 'block';
        } else {
            modalImage.style.display = 'none';
        }

        // Mostrar modal con animación
        const modal = document.getElementById('ministry-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Animar entrada
        setTimeout(() => {
            modal.querySelector('.bg-white').classList.add('modal-enter');
        }, 50);

        // Tracking para analíticas
        this.trackModalOpen('ministry', ministry);
    }

    /**
     * ABRIR MODAL DE ARQUITECTURA
     */
    openArchitectureModal(architecture) {
        const data = architectureData[architecture];
        if (!data) return;

        // Poblar contenido
        document.getElementById('arch-modal-title').textContent = data.title;
        document.getElementById('arch-modal-icon').textContent = data.icon;
        document.getElementById('arch-modal-description').textContent = data.description;
        document.getElementById('arch-modal-specs').textContent = data.specs;

        // Mostrar modal
        const modal = document.getElementById('architecture-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Animar entrada
        setTimeout(() => {
            modal.querySelector('.bg-white').classList.add('modal-enter');
        }, 50);

        this.trackModalOpen('architecture', architecture);
    }

    /**
     * CERRAR MODAL
     */
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Animar salida
        modal.querySelector('.bg-white').classList.add('modal-exit');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.querySelector('.bg-white').classList.remove('modal-enter', 'modal-exit');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    /**
     * TRACKING DE EVENTOS (para analíticas futuras)
     */
    trackModalOpen(type, item) {
        // Aquí se puede integrar Google Analytics o similar
        if (typeof gtag !== 'undefined') {
            gtag('event', 'modal_open', {
                event_category: 'engagement',
                event_label: `${type}_${item}`,
                value: 1
            });
        }
    }

    /**
     * FUNCIONES DE UTILIDAD
     */
    
    // Detectar dispositivo móvil
    isMobile() {
        return window.innerWidth < 768;
    }

    // Generar número aleatorio para efectos
    random(min, max) {
        return Math.random() * (max - min) + min;
    }

    // Formatear números con separadores
    formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Validar email (para futuro formulario de inscripciones)
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
}

/**
 * FUNCIONES GLOBALES PARA COMPATIBILIDAD
 * (Llamadas desde el HTML)
 */

function scrollToSection(sectionId) {
    window.parroquiaApp.scrollToSection(sectionId);
}

function openMinistryModal(ministry) {
    window.parroquiaApp.openMinistryModal(ministry);
}

function openArchitectureModal(architecture) {
    window.parroquiaApp.openArchitectureModal(architecture);
}

function closeModal(modalId) {
    window.parroquiaApp.closeModal(modalId);
}

/**
 * EFECTOS ESPECIALES ADICIONALES
 */

// Efecto de escritura para títulos
function typewriterEffect(element, text, speed = 100) {
    let i = 0;
    const timer = setInterval(() => {
        element.textContent += text.charAt(i);
        i++;
        if (i > text.length - 1) {
            clearInterval(timer);
        }
    }, speed);
}

// Efecto de lluvia de pétalos para eventos especiales
function createPetalEffect() {
    const petals = ['🌸', '🌺', '🌻', '🌹', '🌷'];
    
    for (let i = 0; i < 50; i++) {
        const petal = document.createElement('div');
        petal.textContent = petals[Math.floor(Math.random() * petals.length)];
        petal.style.position = 'fixed';
        petal.style.top = '-50px';
        petal.style.left = Math.random() * window.innerWidth + 'px';
        petal.style.fontSize = Math.random() * 20 + 10 + 'px';
        petal.style.zIndex = '9999';
        petal.style.pointerEvents = 'none';
        petal.style.animation = `fall ${Math.random() * 3 + 2}s linear forwards`;
        
        document.body.appendChild(petal);
        
        setTimeout(() => {
            petal.remove();
        }, 5000);
    }
}

// CSS para animación de pétalos
const petalCSS = `
@keyframes fall {
    0% {
        transform: translateY(-100px) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
    }
}
`;

// Inyectar CSS de pétalos
const style = document.createElement('style');
style.textContent = petalCSS;
document.head.appendChild(style);

/**
 * INICIALIZACIÓN CUANDO EL DOM ESTÁ LISTO
 */
document.addEventListener('DOMContentLoaded', function() {
    // Crear instancia global de la aplicación
    window.parroquiaApp = new ParroquiaInformacion();
    
    // Mostrar mensaje de bienvenida en consola
    console.log(`
    🏛️ Parroquia San Francisco de Asís
    📅 Información actualizada: ${new Date().toLocaleDateString()}
    ⚡ Sistema cargado correctamente
    🙏 ¡Bienvenido a nuestra comunidad digital!
    `);
    
    // Easter egg: Efecto especial en días franciscanos
    const today = new Date();
    const franciscanDays = [
        { month: 10, day: 4 }, // San Francisco de Asís
        { month: 8, day: 11 }, // Santa Clara de Asís
        { month: 1, day: 30 }  // San Martín de Porres
    ];
    
    const isSpecialDay = franciscanDays.some(date => 
        today.getMonth() + 1 === date.month && today.getDate() === date.day
    );
    
    if (isSpecialDay) {
        setTimeout(createPetalEffect, 2000);
        console.log('🌸 ¡Feliz día franciscano! 🌸');
    }
    
    // Registro de Service Worker para PWA (futuro)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/parroquia-sw.js')
            .then(registration => console.log('SW registrado'))
            .catch(error => console.log('SW no pudo registrarse'));
    }
});

/**
 * MANEJO DE ERRORES GLOBAL
 */
window.addEventListener('error', function(e) {
    console.error('Error en la aplicación:', e.error);
    
    // Aquí se podría enviar el error a un servicio de logging
    if (typeof gtag !== 'undefined') {
        gtag('event', 'exception', {
            description: e.error.toString(),
            fatal: false
        });
    }
});

/**
 * EXPORTAR PARA MÓDULOS ES6 (si se necesita)
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { 
        ParroquiaInformacion, 
        ministryData, 
        architectureData 
    };
}