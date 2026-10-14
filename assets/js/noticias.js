/**
 * Raíces Alzate - Módulo de Noticias
 * Especificación Figma: 07 - Noticias - Desktop (y adaptación Mobile)
 * Funcionalidad: Interacción 100% en el cliente para el botón "Ver más noticias" y navegación responsive
 * Versión: 0.1.1
 */

document.addEventListener('DOMContentLoaded', () => {
    // =========================================================================
    // 1. CONTROL DE NAVEGACIÓN Y MENÚ RESPONSIVE (DRAWER MÓVIL Y SIDEBAR)
    // =========================================================================
    const sidebar = document.getElementById('dashboardSidebar');
    const menuToggle = document.getElementById('dashboardMenuToggle');
    const backdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

    // 1.1 Colapso de barra lateral en Desktop
    if (sidebarToggleBtn && sidebar) {
        const updateToggleState = (isCollapsed) => {
            sidebarToggleBtn.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
            sidebarToggleBtn.setAttribute('title', isCollapsed ? 'Expandir menú' : 'Contraer menú');
        };

        try {
            const savedCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
            if (savedCollapsed && window.innerWidth > 768) {
                sidebar.classList.add('is-collapsed', 'collapsed');
                document.documentElement.classList.add('sidebar-is-collapsed');
                updateToggleState(true);
            }
        } catch (err) {}

        sidebarToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const isCurrentlyCollapsed = sidebar.classList.contains('is-collapsed');
            const nextCollapsed = !isCurrentlyCollapsed;

            sidebar.classList.toggle('is-collapsed', nextCollapsed);
            sidebar.classList.toggle('collapsed', nextCollapsed);
            document.documentElement.classList.toggle('sidebar-is-collapsed', nextCollapsed);

            try {
                localStorage.setItem('sidebar_collapsed', nextCollapsed ? 'true' : 'false');
            } catch (err) {}

            updateToggleState(nextCollapsed);
        });
    }

    // 1.2 Menú Drawer en dispositivos móviles
    if (menuToggle && sidebar && backdrop) {
        const toggleMobileSidebar = (open) => {
            const isOpen = open !== undefined ? open : !sidebar.classList.contains('is-open');
            sidebar.classList.toggle('is-open', isOpen);
            backdrop.classList.toggle('is-visible', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('sidebar-drawer-active', isOpen);
        };

        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMobileSidebar();
        });

        backdrop.addEventListener('click', () => {
            toggleMobileSidebar(false);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                toggleMobileSidebar(false);
                menuToggle.focus();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && sidebar.classList.contains('is-open')) {
                toggleMobileSidebar(false);
            }
        });
    }

    // =========================================================================
    // 2. INTERACCIÓN PURAMENTE LOCAL: "VER MÁS NOTICIAS" (CERO APIS / AJAX)
    // =========================================================================
    const btnVerMas = document.getElementById('btnVerMasNoticias');
    const noticiasGrid = document.getElementById('noticiasGrid');
    const statusContainer = document.getElementById('noticiasStatusContainer');

    if (!btnVerMas || !noticiasGrid) return;

    // Colección estática de noticias adicionales de la comunidad alzatista
    const noticiasAdicionales = [
        {
            titulo: 'Jornada de actualización profesional',
            descripcion: 'Talleres y conferencias para fortalecer las competencias de nuestros egresados en el sector productivo.',
            fecha: '5 de agosto de 2026'
        },
        {
            titulo: 'Convocatoria para proyectos de innovación',
            descripcion: 'Postula tus iniciativas para recibir acompañamiento y visibilidad en los canales institucionales.',
            fecha: '28 de julio de 2026'
        },
        {
            titulo: 'Reconocimiento a egresados destacados',
            descripcion: 'Celebramos las trayectorias de exalumnos que generan impacto positivo en la región y el país.',
            fecha: '18 de julio de 2026'
        }
    ];

    let itemsCargados = false;
    let isProcessing = false;

    /**
     * Renderiza una tarjeta de noticia estática idéntica a las 6 iniciales
     */
    const crearTarjetaEstatica = (item) => {
        const article = document.createElement('article');
        article.className = 'noticia-card';

        article.innerHTML = `
            <div class="noticia-cover-wrapper">
                <div class="noticia-cover-placeholder"></div>
            </div>
            <div class="noticia-info">
                <h2 class="noticia-title">${item.titulo}</h2>
                <p class="noticia-description">${item.descripcion}</p>
                <div class="noticia-date">${item.fecha}</div>
            </div>
        `;

        return article;
    };

    /**
     * Manejador de clic: brinda retroalimentación visual al usuario e inserta elementos
     */
    btnVerMas.addEventListener('click', (e) => {
        e.preventDefault();

        if (isProcessing) return;

        if (itemsCargados) {
            btnVerMas.style.display = 'none';
            return;
        }

        isProcessing = true;

        // Feedback visual inmediato en el botón (spinner + texto "Cargando...")
        btnVerMas.disabled = true;
        btnVerMas.innerHTML = '<span class="btn-spinner" aria-hidden="true"></span> Cargando...';

        // Simulación sutil de fluidez en interfaz (350ms)
        setTimeout(() => {
            const fragment = document.createDocumentFragment();

            noticiasAdicionales.forEach((noticia) => {
                const card = crearTarjetaEstatica(noticia);
                fragment.appendChild(card);
            });

            noticiasGrid.appendChild(fragment);
            itemsCargados = true;
            isProcessing = false;

            // Ocultar el botón y mostrar mensaje amigable de finalización
            btnVerMas.style.display = 'none';

            if (statusContainer) {
                const finMensaje = document.createElement('p');
                finMensaje.className = 'noticias-fin-mensaje';
                finMensaje.textContent = 'Has visualizado todas las noticias disponibles.';
                statusContainer.appendChild(finMensaje);
            }
        }, 350);
    });
});
