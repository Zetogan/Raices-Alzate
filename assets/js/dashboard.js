/**
 * Raíces Alzate - JavaScript del Panel Privado (Dashboard, Perfil, Detalle de Egresado)
 * Manejo de interacciones del sidebar, menú móvil responsive y colapso de navegación
 * Versión: 0.1.1
 */

(function () {
    'use strict';

    function initDashboardSidebar() {
        const sidebar = document.getElementById('dashboardSidebar') || document.querySelector('.dashboard-sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn') || document.querySelector('.sidebar-toggle-btn');
        const menuToggle = document.getElementById('dashboardMenuToggle') || 
                           document.getElementById('menuToggle') || 
                           document.querySelector('.dashboard-menu-toggle') || 
                           document.querySelector('.mobile-menu-toggle') || 
                           document.querySelector('.btn-menu-hamburguesa') ||
                           document.querySelector('.menu-toggle');
        const backdrop = document.getElementById('sidebarBackdrop') || document.querySelector('.sidebar-backdrop');

        // =========================================================================
        // 1. COLAPSO Y EXPANSIÓN EN ESCRITORIO (CHEVRON / TOGGLE BTN)
        // =========================================================================
        if (sidebar && sidebarToggleBtn) {
            const updateToggleState = (collapsed) => {
                if (collapsed) {
                    sidebarToggleBtn.setAttribute('aria-label', 'Expandir barra lateral');
                    sidebarToggleBtn.setAttribute('title', 'Expandir menú');
                } else {
                    sidebarToggleBtn.setAttribute('aria-label', 'Contraer barra lateral');
                    sidebarToggleBtn.setAttribute('title', 'Contraer menú');
                }
            };

            // Restaurar estado persistido desde localStorage (solo en pantallas de escritorio)
            try {
                const savedCollapsed = localStorage.getItem('sidebar_collapsed');
                if (savedCollapsed === 'true' && window.innerWidth > 768) {
                    sidebar.classList.add('is-collapsed', 'collapsed');
                    document.documentElement.classList.add('sidebar-is-collapsed');
                    updateToggleState(true);
                } else if (savedCollapsed === 'false') {
                    sidebar.classList.remove('is-collapsed', 'collapsed');
                    document.documentElement.classList.remove('sidebar-is-collapsed');
                    updateToggleState(false);
                } else if (document.documentElement.classList.contains('sidebar-is-collapsed')) {
                    sidebar.classList.add('is-collapsed', 'collapsed');
                    updateToggleState(true);
                }
            } catch (err) {
                console.warn('Acceso a localStorage restringido:', err);
            }

            // Evento click del botón chevron
            sidebarToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const isCurrentlyCollapsed = sidebar.classList.contains('is-collapsed') || sidebar.classList.contains('collapsed');
                const nextCollapsed = !isCurrentlyCollapsed;

                sidebar.classList.toggle('is-collapsed', nextCollapsed);
                sidebar.classList.toggle('collapsed', nextCollapsed);
                document.documentElement.classList.toggle('sidebar-is-collapsed', nextCollapsed);

                try {
                    localStorage.setItem('sidebar_collapsed', nextCollapsed ? 'true' : 'false');
                } catch (err) {
                    console.warn('No se pudo guardar la preferencia en localStorage:', err);
                }

                updateToggleState(nextCollapsed);
            });
        }

        // =========================================================================
        // 2. APERTURA Y CIERRE DEL MENÚ LATERAL EN MÓVIL (DRAWER RESPONSIVE)
        // =========================================================================
        if (menuToggle && sidebar) {
            const toggleMobileSidebar = (forceOpen) => {
                const currentlyOpen = sidebar.classList.contains('is-open') || sidebar.classList.contains('active');
                const nextOpen = forceOpen !== undefined ? forceOpen : !currentlyOpen;

                sidebar.classList.toggle('is-open', nextOpen);
                sidebar.classList.toggle('active', nextOpen);

                if (backdrop) {
                    backdrop.classList.toggle('is-visible', nextOpen);
                    backdrop.classList.toggle('active', nextOpen);
                }

                menuToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
                document.body.classList.toggle('sidebar-drawer-active', nextOpen);
            };

            menuToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleMobileSidebar();
            });

            if (backdrop) {
                backdrop.addEventListener('click', () => {
                    toggleMobileSidebar(false);
                });
            }

            // Cerrar menú con la tecla Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && (sidebar.classList.contains('is-open') || sidebar.classList.contains('active'))) {
                    toggleMobileSidebar(false);
                    menuToggle.focus();
                }
            });

            // Cerrar drawer móvil si la pantalla se redimensiona a escritorio (> 768px)
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768 && (sidebar.classList.contains('is-open') || sidebar.classList.contains('active'))) {
                    toggleMobileSidebar(false);
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardSidebar);
    } else {
        initDashboardSidebar();
    }
})();

/**
 * Gestión interactiva del botón "Conectar" en Detalle de Egresado
 * Deshabilita el botón, actualiza el estado a solicitud enviada, muestra icono de verificación y activa el toast
 */
window.conectarConEgresado = function (btn) {
    if (!btn || btn.disabled) return;

    btn.disabled = true;
    btn.classList.add('solicitud-enviada');

    const textSpan = btn.querySelector('.btn-text');
    if (textSpan) {
        textSpan.textContent = 'Solicitud enviada';
    } else {
        btn.textContent = 'Solicitud enviada';
    }

    const svg = btn.querySelector('svg');
    if (svg) {
        svg.innerHTML = '<polyline points="20 6 9 17 4 12"></polyline>';
        svg.setAttribute('stroke-width', '2.5');
    }

    // Feedback visual mediante Toast Flotante
    const toast = document.getElementById('toastNotificacionEgresados');
    const toastMsg = document.getElementById('toastMensajeEgresados');
    if (toast && toastMsg) {
        const nombreEl = document.querySelector('.profile-user-name');
        const nombre = nombreEl ? nombreEl.textContent.trim() : 'el egresado';
        toastMsg.textContent = `¡Solicitud enviada a ${nombre}! Ya están en contacto.`;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    }
};

