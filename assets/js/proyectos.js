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

    // Colección estática de proyectos adicionales de la comunidad alzatista
    const proyectosAdicionales = [
        {
            id: 7,
            titulo: 'Red de mentorías alzatistas',
            descripcion: 'Programa de orientación profesional liderado por egresados para estudiantes de últimos grados.',
            autor: 'Santiago Emitola',
            esPropio: true
        },
        {
            id: 8,
            titulo: 'Plataforma de empleo joven',
            descripcion: 'Iniciativa para conectar talento técnico alzatista con oportunidades laborales tempranas.',
            autor: 'Diego Castro',
            esPropio: false
        },
        {
            id: 9,
            titulo: 'Semillero de robótica educativa',
            descripcion: 'Espacio formativo para promover el pensamiento computacional y proyectos de automatización.',
            autor: 'Camila Morales',
            esPropio: false
        }
    ];

    let itemsCargados = false;
    let isProcessing = false;

    /**
     * Renderiza una tarjeta de proyecto estática idéntica a las 6 iniciales,
     * con enlace stretched-link a detalle y acción editar si es propio.
     */
    const crearTarjetaEstatica = (item) => {
        const article = document.createElement('article');
        article.className = 'proyecto-card project-card';
        article.setAttribute('data-href', `index.php?page=detalle-proyecto&id=${item.id}`);
        article.setAttribute('role', 'link');
        article.setAttribute('tabindex', '0');
        article.setAttribute('aria-label', `Ver detalle del proyecto ${item.titulo}`);

        const botonEditarHtml = item.esPropio ? `
            <a href="index.php?page=editar-proyecto&id=${item.id}" class="btn-editar-card" title="Editar proyecto ${item.titulo}" onclick="event.stopPropagation();">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <span>Editar</span>
            </a>
        ` : '';

        const covers = ['assets/img/proyecto-cover-1.svg', 'assets/img/proyecto-cover-2.svg', 'assets/img/proyecto-cover-3.svg'];
        const coverSrc = covers[(item.id || 1) % covers.length];

        article.innerHTML = `
            <div class="proyecto-cover-wrapper">
                <img src="${coverSrc}" alt="${item.titulo}" class="proyecto-cover-img">
            </div>
            <div class="proyecto-info">
                <h2 class="proyecto-title project-title">
                    <a href="index.php?page=detalle-proyecto&id=${item.id}" class="proyecto-title-link project-link stretched-link">
                        ${item.titulo}
                    </a>
                </h2>
                <p class="proyecto-description">${item.descripcion}</p>
                <div class="proyecto-footer-row">
                    <div class="proyecto-author">Por ${item.autor}</div>
                    ${botonEditarHtml}
                </div>
            </div>
        `;

        return article;
    };

    /**
     * Manejador de clic: brinda retroalimentación visual al usuario e inserta elementos
     */
    if (btnVerMas && proyectosGrid) {
        btnVerMas.addEventListener('click', (e) => {
            e.preventDefault();

            if (isProcessing) return;

            if (itemsCargados) {
                btnVerMas.style.display = 'none';
                return;
            }

            isProcessing = true;

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
    }

    // =========================================================================
    // 3. NAVEGACIÓN Y ACCESIBILIDAD EN TARJETAS DE PROYECTO
    // =========================================================================
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.proyecto-card');
        if (!card) return;

        // Si el clic fue en el botón de edición o dentro de él, detener para no ir a detalle
        if (e.target.closest('.btn-editar-card')) {
            return;
        }

        // Navegación nativa ante cualquier clic en la tarjeta si no fue en un enlace explícito
        if (!e.target.closest('a')) {
            const href = card.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        }
    });

    // Accesibilidad mediante teclado en tarjetas (Enter activa el enlace a detalle)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const card = document.activeElement;
            if (card && card.classList.contains('proyecto-card')) {
                const link = card.querySelector('.proyecto-title-link');
                if (link) {
                    link.click();
                }
            }
        }
    });
});
