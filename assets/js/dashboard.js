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
 * Gestión interactiva del botón "Conectar" en Detalle de Egresado (Conectado a MySQL)
 * Envía la solicitud real a la base de datos vía api/conectar-egresado.php
 */
window.conectarConEgresado = async function (btn, targetId) {
    if (!btn || btn.disabled) return;

    const id = targetId || btn.getAttribute('data-id');
    const csrfToken = document.getElementById('csrfTokenEgresados')?.value || '';
    const originalHtml = btn.innerHTML;
    const textSpan = btn.querySelector('.btn-text');

    btn.disabled = true;
    if (textSpan) textSpan.textContent = 'Enviando...';

    // Toast helper
    const toast = document.getElementById('toastNotificacionEgresados');
    const toastMsg = document.getElementById('toastMensajeEgresados');
    const mostrarToastLocal = (msg) => {
        if (toast && toastMsg) {
            toastMsg.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3500);
        }
    };

    if (!id) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        mostrarToastLocal('ID de egresado no disponible.');
        return;
    }

    try {
        const formData = new URLSearchParams();
        formData.append('target_id', id);
        formData.append('csrf_token', csrfToken);

        const resp = await fetch('api/conectar-egresado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData.toString()
        });

        const data = await resp.json();

        if (!resp.ok || !data.success) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            mostrarToastLocal(data.error || 'Error al conectar.');
            return;
        }

        if (data.estado === 'pendiente') {
            btn.className = 'btn btn-action-main btn-conectar btn-card-pending';
            btn.disabled = true;
            btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span class="btn-text">Solicitud enviada</span>`;
            mostrarToastLocal(data.mensaje || '¡Solicitud de conexión enviada!');
        } else if (data.estado === 'aceptada') {
            btn.outerHTML = `<a href="index.php?page=mensajes" class="btn btn-primary btn-action-main" id="btnEnviarMensaje">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span class="btn-text">Enviar mensaje</span>
            </a>`;
            mostrarToastLocal(data.mensaje || '¡Conexión establecida!');
        }

    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        mostrarToastLocal('Error de conexión al procesar la solicitud.');
    }
};

