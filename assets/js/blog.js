/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Script: Control Interactivo del Módulo Unificado de Blog
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Elementos del DOM
    const tabButtons = document.querySelectorAll('.blog-tab-btn, .blog-tab-pill');
    const cards = document.querySelectorAll('.blog-card');
    const searchInput = document.getElementById('blogSearchInput');
    const searchClearBtn = document.getElementById('blogSearchClear');
    const categorySelect = document.getElementById('blogCategorySelect');
    const sortSelect = document.getElementById('blogSortSelect');
    const emptyState = document.getElementById('blogEmptyState');
    const resetFiltersBtn = document.getElementById('btnResetFilters');
    const btnCreateMenu = document.getElementById('btnBlogCreate');
    const dropdownMenu = document.getElementById('blogCreateDropdown');
    const dropdownWrapper = document.getElementById('blogCreateWrapper');
    const blogGrid = document.getElementById('blogGrid') || document.getElementById('blogFeedGrid');

    // 2. Estado de filtros
    let currentFilterType = 'todos';
    let currentSearchQuery = '';
    let currentCategory = 'todas';
    let currentSort = 'recientes';

    // 3. Inicialización desde URL (?tipo=noticias / proyectos / oportunidades)
    const urlParams = new URLSearchParams(window.location.search);
    const initialTipo = urlParams.get('tipo');
    if (initialTipo && ['noticias', 'proyectos', 'oportunidades'].includes(initialTipo.toLowerCase())) {
        currentFilterType = initialTipo.toLowerCase();
        tabButtons.forEach(btn => {
            if (btn.getAttribute('data-filter') === currentFilterType) {
                btn.classList.add('is-active');
                btn.setAttribute('aria-selected', 'true');
            } else {
                btn.classList.remove('is-active');
                btn.setAttribute('aria-selected', 'false');
            }
        });
    }

    // 4. Manejo del Menú Desplegable de Publicación
    if (btnCreateMenu && dropdownMenu) {
        btnCreateMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdownMenu.classList.toggle('is-active');
            if (dropdownWrapper) {
                dropdownWrapper.classList.toggle('is-open', isOpen);
            }
            btnCreateMenu.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Cerrar al hacer clic fuera del menú
        document.addEventListener('click', (e) => {
            if (!dropdownMenu.contains(e.target) && e.target !== btnCreateMenu && !btnCreateMenu.contains(e.target)) {
                dropdownMenu.classList.remove('is-active');
                if (dropdownWrapper) {
                    dropdownWrapper.classList.remove('is-open');
                }
                btnCreateMenu.setAttribute('aria-expanded', 'false');
            }
        });

        // Cerrar al pulsar Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && dropdownMenu.classList.contains('is-active')) {
                dropdownMenu.classList.remove('is-active');
                if (dropdownWrapper) {
                    dropdownWrapper.classList.remove('is-open');
                }
                btnCreateMenu.setAttribute('aria-expanded', 'false');
                btnCreateMenu.focus();
            }
        });

        // Navegación inmediata y fluida al hacer clic en cualquier opción
        dropdownMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                dropdownMenu.classList.remove('is-active');
                if (dropdownWrapper) {
                    dropdownWrapper.classList.remove('is-open');
                }
                btnCreateMenu.setAttribute('aria-expanded', 'false');
                const href = link.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            });
        });
    }

    // 5. Función de filtrado unificado
    function aplicarFiltros() {
        let visibleCount = 0;
        const query = currentSearchQuery.toLowerCase().trim();

        cards.forEach(card => {
            const cardType = (card.getAttribute('data-tipo') || '').toLowerCase();
            const cardCat = (card.getAttribute('data-categoria') || '').toLowerCase();
            const cardTitle = (card.getAttribute('data-titulo') || '').toLowerCase();
            const cardDesc = (card.getAttribute('data-descripcion') || '').toLowerCase();
            const cardAuthor = (card.getAttribute('data-autor') || '').toLowerCase();
            const cardEmpresa = (card.getAttribute('data-empresa') || '').toLowerCase();

            // Validación por Tipo (Tab)
            const matchType = (currentFilterType === 'todos' || cardType === currentFilterType);

            // Validación por Categoría
            const matchCategory = (currentCategory === 'todas' || cardCat === currentCategory.toLowerCase());

            // Validación por Búsqueda de Texto
            const matchSearch = query === '' || 
                cardTitle.includes(query) || 
                cardDesc.includes(query) || 
                cardAuthor.includes(query) || 
                cardEmpresa.includes(query);

            if (matchType && matchCategory && matchSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Mostrar / Ocultar estado vacío
        if (emptyState) {
            emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    // 6. Ordenamiento de tarjetas
    function aplicarOrden() {
        if (!blogGrid) return;
        const cardsArray = Array.from(cards);

        cardsArray.sort((a, b) => {
            const timeA = parseInt(a.getAttribute('data-timestamp') || '0', 10);
            const timeB = parseInt(b.getAttribute('data-timestamp') || '0', 10);
            return currentSort === 'recientes' ? (timeB - timeA) : (timeA - timeB);
        });

        cardsArray.forEach(card => blogGrid.appendChild(card));
    }

    // 7. Eventos de Tabs (Botones de filtro temático)
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => {
                b.classList.remove('is-active');
                b.setAttribute('aria-selected', 'false');
            });

            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');
            currentFilterType = (btn.getAttribute('data-filter') || 'todos').toLowerCase();

            // Actualizar URL sin recargar la página
            const newUrl = new URL(window.location);
            if (currentFilterType === 'todos') {
                newUrl.searchParams.delete('tipo');
            } else {
                newUrl.searchParams.set('tipo', currentFilterType);
            }
            window.history.replaceState({}, '', newUrl);

            aplicarFiltros();
        });
    });

    // 8. Eventos del Buscador
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearchQuery = e.target.value;
            if (searchClearBtn) {
                searchClearBtn.style.display = currentSearchQuery.length > 0 ? 'block' : 'none';
            }
            aplicarFiltros();
        });
    }

    if (searchClearBtn && searchInput) {
        searchClearBtn.addEventListener('click', () => {
            searchInput.value = '';
            currentSearchQuery = '';
            searchClearBtn.style.display = 'none';
            searchInput.focus();
            aplicarFiltros();
        });
    }

    // 9. Eventos de Selección de Categoría
    if (categorySelect) {
        categorySelect.addEventListener('change', (e) => {
            currentCategory = e.target.value;
            aplicarFiltros();
        });
    }

    // 10. Eventos de Orden
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            currentSort = e.target.value;
            aplicarOrden();
        });
    }

    // 11. Botón de Restablecer Filtros
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', () => {
            currentFilterType = 'todos';
            currentSearchQuery = '';
            currentCategory = 'todas';
            currentSort = 'recientes';

            tabButtons.forEach(b => {
                const isTodos = (b.getAttribute('data-filter') === 'todos');
                b.classList.toggle('is-active', isTodos);
                b.setAttribute('aria-selected', isTodos ? 'true' : 'false');
            });

            if (searchInput) {
                searchInput.value = '';
                if (searchClearBtn) searchClearBtn.style.display = 'none';
            }
            if (categorySelect) categorySelect.value = 'todas';
            if (sortSelect) sortSelect.value = 'recientes';

            const newUrl = new URL(window.location);
            newUrl.searchParams.delete('tipo');
            window.history.replaceState({}, '', newUrl);

            aplicarOrden();
            aplicarFiltros();
        });
    }

    // Ejecutar filtro inicial
    aplicarFiltros();
});
