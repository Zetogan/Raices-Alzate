/**
 * Raíces Alzate - Módulo de Oportunidades
 * Funcionalidad: Interacción en el cliente para navegación responsive y botón "Ver más oportunidades"
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
    // 2. INTERACCIÓN PURAMENTE LOCAL: "VER MÁS OPORTUNIDADES" (CERO APIS / AJAX)
    // =========================================================================
    const btnVerMas = document.getElementById('btnVerMasOportunidades');
    const oportunidadesGrid = document.getElementById('oportunidadesGrid');
    const statusContainer = document.getElementById('oportunidadesStatusContainer');

    if (!oportunidadesGrid) return;

    // Delegación de clic accesible en todas las tarjetas
    oportunidadesGrid.addEventListener('click', (e) => {
        const card = e.target.closest('.oportunidad-card');
        if (!card) return;

        const href = card.getAttribute('href') || card.getAttribute('data-href');
        if (href && card.tagName.toLowerCase() !== 'a') {
            window.location.href = href;
        }
    });

    // Accesibilidad por teclado (Enter / Espacio)
    oportunidadesGrid.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            const card = e.target.closest('.oportunidad-card');
            if (card && card.tagName.toLowerCase() !== 'a') {
                const href = card.getAttribute('href') || card.getAttribute('data-href');
                if (href) {
                    e.preventDefault();
                    window.location.href = href;
                }
            }
        }
    });

    if (!btnVerMas) return;

    // Banco estático adicional en memoria cliente
    const oportunidadesAdicionales = [
        {
            id: 7,
            titulo: 'Curso Certificado en Ciberseguridad Básica',
            empresa: 'Mintic & Alianza Educativa',
            categoria: 'Capacitación',
            modalidad: 'Virtual',
            ubicacion: 'Online',
            fecha: '5 de septiembre de 2026',
            descripcion: 'Formación especializada en seguridad digital y buenas prácticas de protección de la información en organizaciones.'
        },
        {
            id: 8,
            titulo: 'Pasantía en Producción Audiovisual y Redes',
            empresa: 'Medios Digitales de Antioquia',
            categoria: 'Pasantía',
            modalidad: 'Presencial',
            ubicacion: 'Medellín',
            fecha: '28 de agosto de 2026',
            descripcion: 'Apoyo en grabación, edición de video y gestión de contenido para canales institucionales de difusión regional.'
        },
        {
            id: 9,
            titulo: 'Beca de Idiomas: Inglés para el Empleo',
            empresa: 'Centro Colombo Americano & IE GAA',
            categoria: 'Beca',
            modalidad: 'Híbrido',
            ubicacion: 'Medellín',
            fecha: '15 de agosto de 2026',
            descripcion: 'Programa de inmersión en inglés conversacional y técnico para egresados con interés en vacantes bilingües.'
        }
    ];

    let adicionalesCargadas = false;

    btnVerMas.addEventListener('click', () => {
        if (adicionalesCargadas) return;

        btnVerMas.disabled = true;
        const textoOriginal = btnVerMas.textContent;
        btnVerMas.textContent = 'Cargando más oportunidades...';

        setTimeout(() => {
            const fragment = document.createDocumentFragment();

            oportunidadesAdicionales.forEach((item) => {
                const card = document.createElement('a');
                card.href = `index.php?page=detalle-oportunidad&id=${item.id}`;
                card.className = 'oportunidad-card';
                card.setAttribute('data-href', `index.php?page=detalle-oportunidad&id=${item.id}`);
                card.setAttribute('aria-label', `Ver detalle de ${item.titulo}`);

                card.innerHTML = `
                    <div class="oportunidad-cover-wrapper">
                        <div class="oportunidad-cover-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="oportunidad-info">
                        <div class="oportunidad-badges-row">
                            <span class="oportunidad-categoria-badge">${item.categoria}</span>
                            <span class="oportunidad-modalidad-badge">${item.modalidad}</span>
                        </div>
                        <h2 class="oportunidad-title">${item.titulo}</h2>
                        <div class="oportunidad-empresa">${item.empresa}</div>
                        <p class="oportunidad-description">${item.descripcion}</p>
                        <div class="oportunidad-footer-meta">
                            <span class="oportunidad-date">Publicado: ${item.fecha}</span>
                            <span class="oportunidad-location">${item.ubicacion}</span>
                        </div>
                    </div>
                `;

                fragment.appendChild(card);
            });

            oportunidadesGrid.appendChild(fragment);
            adicionalesCargadas = true;

            btnVerMas.style.display = 'none';

            if (statusContainer) {
                const avisoFin = document.createElement('p');
                avisoFin.className = 'oportunidades-end-msg';
                avisoFin.style.fontFamily = "'Inter', sans-serif";
                avisoFin.style.fontSize = "13px";
                avisoFin.style.color = "#757575";
                avisoFin.style.margin = "0";
                avisoFin.textContent = 'Has llegado al final del catálogo de oportunidades disponibles.';
                statusContainer.appendChild(avisoFin);
            }
        }, 200);
    });
});
