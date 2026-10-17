/**
 * Raíces Alzate - Módulo de Detalle de Proyecto (detalle-proyecto.js)
 * Especificación Figma: 10 - Detalle de proyecto - Desktop (y adaptación Mobile)
 * Funcionalidades:
 *   - Control de Sidebar y Drawer móvil compatible con la arquitectura del Dashboard
 *   - Gestión de estado persistente del Sidebar en Desktop (localStorage)
 *   - Accesibilidad mediante teclado y navegación segura
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
            e.preventDefault();
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
    // 2. ACCESIBILIDAD Y ENLACES EXTERNOS
    // =========================================================================
    const btnVisitar = document.getElementById('btnVisitarProyecto');
    if (btnVisitar) {
        btnVisitar.addEventListener('click', () => {
            // Asegurar que el destino externo se abra limpiamente
            btnVisitar.setAttribute('rel', 'noopener noreferrer');
        });
    }
});
