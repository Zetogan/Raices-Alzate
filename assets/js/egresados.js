/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Módulo de Egresados (pages/egresados.php)
 * Lógica Vanilla JS: Navegación, Filtros en tiempo real, Conexiones y Modal de Detalle
 * Versión: 0.1.1
 */

document.addEventListener('DOMContentLoaded', () => {
    // =========================================================================
    // 1. LÓGICA DE SIDEBAR COLAPSABLE (ESCRITORIO)
    // =========================================================================
    const sidebar = document.getElementById('dashboardSidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const menuToggle = document.getElementById('dashboardMenuToggle');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (sidebarToggleBtn && sidebar) {
        const updateToggleState = (isCollapsed) => {
            sidebarToggleBtn.setAttribute('aria-label', isCollapsed ? 'Expandir barra lateral' : 'Contraer barra lateral');
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

    // =========================================================================
    // 2. LÓGICA DE MENÚ DRAWER (MÓVIL <= 768px)
    // =========================================================================
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
    // 3. TOAST NOTIFICATION HELPER
    // =========================================================================
    const toast = document.getElementById('toastNotificacionEgresados');
    const toastMsg = document.getElementById('toastMensajeEgresados');
    let toastTimer = null;

    const mostrarToast = (mensaje) => {
        if (!toast || !toastMsg) return;
        toastMsg.textContent = mensaje;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    };

    // =========================================================================
    // 4. BÚSQUEDA Y FILTRADO EN TIEMPO REAL
    // =========================================================================
    const searchInput = document.getElementById('inputBuscarEgresado');
    const selectPromo = document.getElementById('selectPromocion');
    const selectEnfasis = document.getElementById('selectEnfasis');
    const selectCiudad = document.getElementById('selectCiudad');
    const btnReset = document.getElementById('btnResetFiltros');
    const btnResetVacios = document.getElementById('btnRestablecerVacios');
    const cards = Array.from(document.querySelectorAll('.egresado-card'));
    const counterEl = document.getElementById('contadorResultados');
    const emptyState = document.getElementById('emptyStateEgresados');
    const chipsContainer = document.getElementById('contenedorChipsFiltros');
    const tabBtns = document.querySelectorAll('.egresados-tab-btn');

    let currentCategory = 'todos';

    const aplicarFiltros = () => {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const promoVal = selectPromo ? selectPromo.value : '';
        const enfasisVal = selectEnfasis ? selectEnfasis.value : '';
        const ciudadVal = selectCiudad ? selectCiudad.value : '';

        let visibles = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name')?.toLowerCase() || '';
            const promo = card.getAttribute('data-promo') || '';
            const enfasis = card.getAttribute('data-enfasis') || '';
            const ciudad = card.getAttribute('data-ciudad') || '';
            const cargo = card.getAttribute('data-cargo')?.toLowerCase() || '';
            const empresa = card.getAttribute('data-empresa')?.toLowerCase() || '';
            const destacado = card.getAttribute('data-destacado') === 'true';
            const isConnected = card.querySelector('.btn-card-connect')?.classList.contains('connected');

            // 1. Filtro de Categoría rápida (Tabs)
            let matchCategory = true;
            if (currentCategory === '2021') {
                matchCategory = (promo === '2021');
            } else if (currentCategory === 'destacados') {
                matchCategory = destacado;
            } else if (currentCategory === 'conectados') {
                matchCategory = isConnected;
            }

            // 2. Filtro de Texto
            let matchText = true;
            if (query) {
                matchText = name.includes(query) || cargo.includes(query) || empresa.includes(query) || card.textContent.toLowerCase().includes(query);
            }

            // 3. Filtros Dropdowns
            let matchPromo = !promoVal || (promo === promoVal);
            let matchEnfasis = !enfasisVal || (enfasis === enfasisVal);
            let matchCiudad = !ciudadVal || (ciudad === ciudadVal);

            if (matchCategory && matchText && matchPromo && matchEnfasis && matchCiudad) {
                card.style.display = 'flex';
                visibles++;
            } else {
                card.style.display = 'none';
            }
        });

        // Actualizar contador
        if (counterEl) counterEl.textContent = visibles;

        // Estado vacío
        if (emptyState) {
            if (visibles === 0) {
                emptyState.classList.add('is-visible');
            } else {
                emptyState.classList.remove('is-visible');
            }
        }

        // Renderizar chips activos
        actualizarChips(query, promoVal, enfasisVal, ciudadVal);
    };

    const actualizarChips = (query, promo, enfasis, ciudad) => {
        if (!chipsContainer) return;
        chipsContainer.innerHTML = '';

        if (query) {
            crearChip(`Búsqueda: "${query}"`, () => {
                if (searchInput) searchInput.value = '';
                aplicarFiltros();
            });
        }
        if (promo) {
            crearChip(`Promoción ${promo}`, () => {
                if (selectPromo) selectPromo.value = '';
                aplicarFiltros();
            });
        }
        if (enfasis && selectEnfasis) {
            const texto = selectEnfasis.options[selectEnfasis.selectedIndex].text;
            crearChip(texto, () => {
                selectEnfasis.value = '';
                aplicarFiltros();
            });
        }
        if (ciudad && selectCiudad) {
            const texto = selectCiudad.options[selectCiudad.selectedIndex].text;
            crearChip(texto, () => {
                selectCiudad.value = '';
                aplicarFiltros();
            });
        }
    };

    const crearChip = (texto, onRemove) => {
        const chip = document.createElement('span');
        chip.className = 'filter-chip';
        chip.innerHTML = `${texto} <button type="button" class="filter-chip-remove" aria-label="Eliminar filtro">&times;</button>`;
        chip.querySelector('.filter-chip-remove').addEventListener('click', onRemove);
        chipsContainer.appendChild(chip);
    };

    const resetearFiltros = () => {
        if (searchInput) searchInput.value = '';
        if (selectPromo) selectPromo.value = '';
        if (selectEnfasis) selectEnfasis.value = '';
        if (selectCiudad) selectCiudad.value = '';
        currentCategory = 'todos';
        tabBtns.forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-category') === 'todos');
        });
        aplicarFiltros();
        mostrarToast('Filtros restablecidos.');
    };

    if (searchInput) searchInput.addEventListener('input', aplicarFiltros);
    if (selectPromo) selectPromo.addEventListener('change', aplicarFiltros);
    if (selectEnfasis) selectEnfasis.addEventListener('change', aplicarFiltros);
    if (selectCiudad) selectCiudad.addEventListener('change', aplicarFiltros);
    if (btnReset) btnReset.addEventListener('click', resetearFiltros);
    if (btnResetVacios) btnResetVacios.addEventListener('click', resetearFiltros);

    // Tabs rápidas
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentCategory = btn.getAttribute('data-category');
            aplicarFiltros();
        });
    });

    // =========================================================================
    // 5. BOTÓN CONECTAR / SOLICITUD DE CONTACTO EN TARJETAS
    // =========================================================================
    const btnConectarList = document.querySelectorAll('.btn-conectar-egresado');
    let conectadosCount = 1;

    btnConectarList.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const egresadoName = btn.getAttribute('data-name') || 'egresado';

            if (btn.classList.contains('connected')) {
                btn.classList.remove('connected');
                btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg> Conectar`;
                conectadosCount = Math.max(0, conectadosCount - 1);
                mostrarToast(`Has cancelado la conexión con ${egresadoName}.`);
            } else {
                btn.classList.add('connected');
                btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Conectado`;
                conectadosCount++;
                mostrarToast(`¡Solicitud enviada a ${egresadoName}! Ya están en contacto.`);
            }

            const badgeConectados = document.getElementById('badgeConectados');
            if (badgeConectados) badgeConectados.textContent = conectadosCount;
        });
    });

    // =========================================================================
    // 6. MODAL ACCESIBLE DE DETALLE DE EGRESADO (VER PERFIL)
    // =========================================================================
    const modalDetalle = document.getElementById('modalDetalleEgresado');
    const btnCerrarModal = document.getElementById('btnCerrarModalEgresado');
    const btnCerrarFooter = document.getElementById('btnCerrarModalFooter');
    const btnVerPerfilList = document.querySelectorAll('.btn-ver-perfil');
    const btnModalConectar = document.getElementById('btnModalConectar');

    let currentModalEgresado = '';

    const abrirDetalle = (card) => {
        if (!modalDetalle || !card) return;

        const name = card.getAttribute('data-name') || 'Egresado';
        const promo = card.getAttribute('data-promo') || '2021';
        const enfasis = card.querySelector('.card-emphasis')?.textContent || 'Bachiller Alzatista';
        const cargo = card.getAttribute('data-cargo') || 'Profesional';
        const empresa = card.getAttribute('data-empresa') || 'Institucional';
        const ciudad = card.querySelector('.card-location')?.textContent.trim() || 'Medellín, Colombia';
        const avatarInitial = name.charAt(0).toUpperCase();

        currentModalEgresado = name;

        const elName = document.getElementById('modalEgresadoName');
        const elEmphasis = document.getElementById('modalEgresadoEmphasis');
        const elPromo = document.getElementById('modalEgresadoPromo');
        const elCiudad = document.getElementById('modalEgresadoCiudad');
        const elCargo = document.getElementById('modalEgresadoCargo');
        const elEmpresa = document.getElementById('modalEgresadoEmpresa');
        const elAvatar = document.getElementById('modalEgresadoAvatar');

        if (elName) elName.textContent = name;
        if (elEmphasis) elEmphasis.textContent = enfasis;
        if (elPromo) elPromo.textContent = `Promoción ${promo}`;
        if (elCiudad) elCiudad.textContent = ciudad;
        if (elCargo) elCargo.textContent = cargo;
        if (elEmpresa) elEmpresa.textContent = empresa;
        if (elAvatar) elAvatar.textContent = avatarInitial;

        // Actualizar estado del botón en el modal según estado de conexión de la tarjeta
        const cardConnectBtn = card.querySelector('.btn-card-connect');
        const isConnected = cardConnectBtn && cardConnectBtn.classList.contains('connected');

        if (btnModalConectar) {
            if (isConnected) {
                btnModalConectar.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <span>Conectado</span>`;
                btnModalConectar.classList.add('btn-outline-secondary');
                btnModalConectar.classList.remove('btn-primary');
            } else {
                btnModalConectar.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg> <span>Conectar con egresado</span>`;
                btnModalConectar.classList.add('btn-primary');
                btnModalConectar.classList.remove('btn-outline-secondary');
            }
        }

        modalDetalle.classList.add('is-active');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';
    };

    const cerrarDetalle = () => {
        if (!modalDetalle) return;
        modalDetalle.classList.remove('is-active');
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    };

    btnVerPerfilList.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const card = btn.closest('.egresado-card');
            abrirDetalle(card);
        });
    });

    if (btnCerrarModal) btnCerrarModal.addEventListener('click', cerrarDetalle);
    if (btnCerrarFooter) btnCerrarFooter.addEventListener('click', cerrarDetalle);

    if (modalDetalle) {
        modalDetalle.addEventListener('click', (e) => {
            if (e.target === modalDetalle) cerrarDetalle();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalDetalle.classList.contains('is-active')) {
                cerrarDetalle();
            }
        });
    }

    if (btnModalConectar) {
        btnModalConectar.addEventListener('click', () => {
            // Alternar estado de conexión
            const isCurrentlyConnected = btnModalConectar.classList.contains('btn-outline-secondary');
            
            if (isCurrentlyConnected) {
                btnModalConectar.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg> <span>Conectar con egresado</span>`;
                btnModalConectar.classList.add('btn-primary');
                btnModalConectar.classList.remove('btn-outline-secondary');
                
                // Actualizar tarjeta correspondiente
                cards.forEach(card => {
                    if (card.getAttribute('data-name') === currentModalEgresado) {
                        const btn = card.querySelector('.btn-card-connect');
                        if (btn && btn.classList.contains('connected')) {
                            btn.classList.remove('connected');
                            btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg> Conectar`;
                            conectadosCount = Math.max(0, conectadosCount - 1);
                        }
                    }
                });
                mostrarToast(`Has cancelado la conexión con ${currentModalEgresado}.`);
            } else {
                btnModalConectar.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <span>Conectado</span>`;
                btnModalConectar.classList.add('btn-outline-secondary');
                btnModalConectar.classList.remove('btn-primary');

                // Actualizar tarjeta correspondiente
                cards.forEach(card => {
                    if (card.getAttribute('data-name') === currentModalEgresado) {
                        const btn = card.querySelector('.btn-card-connect');
                        if (btn && !btn.classList.contains('connected')) {
                            btn.classList.add('connected');
                            btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Conectado`;
                            conectadosCount++;
                        }
                    }
                });
                mostrarToast(`¡Conexión establecida con ${currentModalEgresado}!`);
            }

            const badgeConectados = document.getElementById('badgeConectados');
            if (badgeConectados) badgeConectados.textContent = conectadosCount;

            setTimeout(cerrarDetalle, 400);
        });
    }
});
