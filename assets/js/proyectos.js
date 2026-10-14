/**
 * Raíces Alzate - Módulo de Proyectos
 * Especificación Figma: 06 - Proyectos - Desktop (y adaptación Mobile)
 * Funcionalidad: Interacción 100% en el cliente para el botón "Ver más proyectos" y navegación responsive
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
    // 2. INTERACCIÓN PURAMENTE LOCAL: "VER MÁS PROYECTOS" (CERO APIS / AJAX)
    // =========================================================================
    const btnVerMas = document.getElementById('btnVerMasProyectos');
    const proyectosGrid = document.getElementById('proyectosGrid');
    const statusContainer = document.getElementById('proyectosStatusContainer');

    if (!btnVerMas || !proyectosGrid) return;

    // Colección estática de proyectos adicionales de la comunidad alzatista
    const proyectosAdicionales = [
        {
            titulo: 'Red de mentorías alzatistas',
            descripcion: 'Programa de orientación profesional liderado por egresados para estudiantes de últimos grados.',
            autor: 'Por Santiago Emitola'
        },
        {
            titulo: 'Plataforma de empleo joven',
            descripcion: 'Iniciativa para conectar talento técnico alzatista con oportunidades laborales tempranas.',
            autor: 'Por Diego Castro'
        },
        {
            titulo: 'Semillero de robótica educativa',
            descripcion: 'Espacio formativo para promover el pensamiento computacional y proyectos de automatización.',
            autor: 'Por Camila Morales'
        }
    ];

    let itemsCargados = false;
    let isProcessing = false;

    /**
     * Renderiza una tarjeta de proyecto estática idéntica a las 6 iniciales
     */
    const crearTarjetaEstatica = (item) => {
        const article = document.createElement('article');
        article.className = 'proyecto-card';

        article.innerHTML = `
            <div class="proyecto-cover-wrapper">
                <div class="proyecto-cover-placeholder"></div>
            </div>
            <div class="proyecto-info">
                <h2 class="proyecto-title">${item.titulo}</h2>
                <p class="proyecto-description">${item.descripcion}</p>
                <div class="proyecto-author">${item.autor}</div>
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
        const textoOriginal = btnVerMas.innerHTML;

        // Feedback visual inmediato en el botón (spinner + texto)
        btnVerMas.disabled = true;
        btnVerMas.innerHTML = '<span class="btn-spinner" aria-hidden="true"></span> Cargando...';

        // Simulación sutil de fluidez en interfaz (350ms)
        setTimeout(() => {
            const fragment = document.createDocumentFragment();

            proyectosAdicionales.forEach((proyecto) => {
                const card = crearTarjetaEstatica(proyecto);
                fragment.appendChild(card);
            });

            proyectosGrid.appendChild(fragment);
            itemsCargados = true;
            isProcessing = false;

            // Ocultar el botón y mostrar mensaje amigable de finalización
            btnVerMas.style.display = 'none';

            if (statusContainer) {
                const finMensaje = document.createElement('p');
                finMensaje.className = 'proyectos-fin-mensaje';
                finMensaje.textContent = 'Has visualizado todos los proyectos disponibles.';
                statusContainer.appendChild(finMensaje);
            }
        }, 350);
    });
});
