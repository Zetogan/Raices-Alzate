<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Directorio de Egresados (05 - Egresados - Desktop y adaptación Mobile)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

if (!function_exists('egresadosAssetCssUrl')) {
    function egresadosAssetCssUrl($filename) {
        $fullPath = __DIR__ . '/../assets/css/' . $filename;
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        $version = defined('APP_VERSION') ? APP_VERSION : '0.1.5';
        return 'assets/css/' . $filename . '?v=' . $version . '&amp;t=' . $time;
    }
}

// Datos de sesión del usuario autenticado
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

$topbarTitle = 'Egresados';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ('Egresados | ' . APP_NAME)); ?></title>

    <!-- Favicon e Iconografía Institucional Oficial -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">

    <!-- Tipografía Inter (Preconexión y Google Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Hojas de estilo modulares -->
    <link rel="stylesheet" href="<?php echo egresadosAssetCssUrl('fonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo egresadosAssetCssUrl('main.css'); ?>">
    <link rel="stylesheet" href="<?php echo egresadosAssetCssUrl('dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php echo egresadosAssetCssUrl('egresados.css'); ?>">
    <link rel="stylesheet" href="<?php echo egresadosAssetCssUrl('loader.css'); ?>">

    <!-- Prevención de FOUC para sidebar colapsado según preferencia en localStorage -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="dashboard-body-page egresados-body-page">
    <!-- Componente de precarga global (Page Loader con Spinner y Logotipo) -->
    <?php require_once __DIR__ . '/../includes/loader.php'; ?>

    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="dashboard-layout" id="dashboardLayout">
        <!-- 1. Barra Lateral de Navegación Privada (Sidebar con ítem Egresados activo) -->
        <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- 2. Área Principal de Contenido -->
        <div class="dashboard-main-area">
            <!-- Barra Superior (Topbar) -->
            <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

            <!-- Contenido Central del Directorio de Egresados -->
            <main class="dashboard-content egresados-content-wrapper" id="egresadosMainContent" tabindex="-1">
                
                <!-- A. Miga de pan / Breadcrumb -->
                <nav class="egresados-breadcrumb-nav" aria-label="Ruta de navegación">
                    <ol class="egresados-breadcrumb-list">
                        <li class="egresados-breadcrumb-item">
                            <a href="dashboard.php" class="egresados-breadcrumb-link">Dashboard</a>
                        </li>
                        <li class="egresados-breadcrumb-item">
                            <span class="egresados-breadcrumb-sep" aria-hidden="true">/</span>
                            <span class="egresados-breadcrumb-current" aria-current="page">Egresados</span>
                        </li>
                    </ol>
                </nav>

                <!-- B. Cabecera Hero del Directorio -->
                <header class="egresados-hero-header">
                    <div class="egresados-header-text">
                        <h1 class="egresados-title">
                            Directorio de Egresados
                            <span class="egresados-title-badge">Comunidad Alzatista</span>
                        </h1>
                        <p class="egresados-subtitle">
                            Conéctate con exalumnos de la Institución Educativa Gilberto Alzate Avendaño, amplía tu red profesional y encuentra colegas por año de promoción, especialidad o ciudad.
                        </p>
                    </div>

                    <div class="egresados-hero-stats">
                        <div class="hero-stat-badge">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span><strong>+1,250</strong> Egresados</span>
                        </div>

                        <div class="hero-stat-badge hero-stat-badge-blue">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                            <span><strong>42</strong> Promociones</span>
                        </div>
                    </div>
                </header>

                <!-- C. Barra de Búsqueda y Filtros en Tiempo Real -->
                <section class="egresados-filter-card" aria-label="Búsqueda y filtros del directorio">
                    <div class="filter-controls-row">
                        <!-- Campo de búsqueda libre -->
                        <div class="filter-search-box">
                            <span class="filter-search-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </span>
                            <input 
                                type="search" 
                                class="filter-search-input" 
                                id="inputBuscarEgresado" 
                                placeholder="Buscar por nombre, cargo, empresa o especialidad..."
                                aria-label="Buscar egresados"
                            >
                        </div>

                        <!-- Filtro por Promoción -->
                        <div class="filter-select-wrap">
                            <select class="filter-select" id="selectPromocion" aria-label="Filtrar por año de promoción">
                                <option value="">Todas las promociones</option>
                                <option value="2024">Promoción 2024</option>
                                <option value="2023">Promoción 2023</option>
                                <option value="2022">Promoción 2022</option>
                                <option value="2021">Promoción 2021</option>
                                <option value="2020">Promoción 2020</option>
                                <option value="2019">Promoción 2019</option>
                                <option value="2018">Promoción 2018</option>
                            </select>
                            <span class="filter-select-icon" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </div>

                        <!-- Filtro por Énfasis -->
                        <div class="filter-select-wrap">
                            <select class="filter-select" id="selectEnfasis" aria-label="Filtrar por énfasis institucional">
                                <option value="">Todos los énfasis</option>
                                <option value="sistemas">Sistemas e Informática</option>
                                <option value="comercial">Gestión Comercial</option>
                                <option value="academico">Bachillerato Académico</option>
                            </select>
                            <span class="filter-select-icon" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </div>

                        <!-- Filtro por Ciudad -->
                        <div class="filter-select-wrap">
                            <select class="filter-select" id="selectCiudad" aria-label="Filtrar por ciudad de residencia">
                                <option value="">Todas las ciudades</option>
                                <option value="medellin">Medellín</option>
                                <option value="bogota">Bogotá</option>
                                <option value="cali">Cali</option>
                                <option value="exterior">Exterior / Remoto</option>
                            </select>
                            <span class="filter-select-icon" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </div>

                        <!-- Botón para limpiar filtros -->
                        <button type="button" class="btn-filter-reset" id="btnResetFiltros" title="Restablecer todos los filtros">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2.5 2v6h6M21.5 22v-6h-6"></path>
                                <path d="M22 11.5A10 10 0 0 0 3.2 7.2L2.5 8M2 12.5a10 10 0 0 0 18.8 4.3l.7.7"></path>
                            </svg>
                            Limpiar
                        </button>
                    </div>

                    <!-- Resumen de resultados y chips activos -->
                    <div class="filter-summary-row">
                        <span class="results-counter-text">
                            Mostrando <strong id="contadorResultados">8</strong> egresados encontrados
                        </span>
                        <div class="active-filter-chips" id="contenedorChipsFiltros"></div>
                    </div>
                </section>

                <!-- D. Pestañas de Vista Rápida -->
                <nav class="egresados-tabs-container" aria-label="Filtrado rápido de egresados">
                    <button type="button" class="egresados-tab-btn active" data-category="todos">
                        Todos los egresados
                        <span class="tab-badge" id="badgeTotalEgresados">8</span>
                    </button>
                    <button type="button" class="egresados-tab-btn" data-category="2021">
                        Mi promoción (2021)
                        <span class="tab-badge">4</span>
                    </button>
                    <button type="button" class="egresados-tab-btn" data-category="destacados">
                        Destacados
                        <span class="tab-badge">3</span>
                    </button>
                    <button type="button" class="egresados-tab-btn" data-category="conectados">
                        Conectados
                        <span class="tab-badge" id="badgeConectados">1</span>
                    </button>
                </nav>

                <!-- E. Grid de Tarjetas de Egresados -->
                <section class="egresados-cards-grid" id="egresadosCardsGrid" aria-label="Listado de egresados alzatistas">
                    
                    <!-- Tarjeta 1: Valentina Ríos Gómez -->
                    <article class="egresado-card" 
                        data-name="Valentina Ríos Gómez" 
                        data-promo="2021" 
                        data-enfasis="sistemas" 
                        data-ciudad="medellin"
                        data-cargo="Ingeniera de Software"
                        data-empresa="Mercado Libre"
                        data-destacado="true"
                    >
                        <div class="card-top-banner">
                            <span class="card-promo-badge">Promoción 2021</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar egresado-avatar-blue" aria-hidden="true">V</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Valentina Ríos Gómez</h2>
                            <p class="card-emphasis">Técnico en Sistemas e Informática</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Ingeniera de Software en Mercado Libre
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">PHP</span>
                                <span class="card-skill-pill">React</span>
                                <span class="card-skill-pill">MySQL</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="1">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Valentina Ríos">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 2: Carlos Andrés Morales -->
                    <article class="egresado-card" 
                        data-name="Carlos Andrés Morales" 
                        data-promo="2020" 
                        data-enfasis="comercial" 
                        data-ciudad="medellin"
                        data-cargo="Coordinador Financiero"
                        data-empresa="Bancolombia"
                        data-destacado="false"
                    >
                        <div class="card-top-banner card-top-banner-blue">
                            <span class="card-promo-badge card-promo-badge-blue">Promoción 2020</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar egresado-avatar-teal" aria-hidden="true">C</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Carlos Andrés Morales</h2>
                            <p class="card-emphasis">Técnico en Gestión Comercial y Contable</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Coordinador Financiero en Bancolombia
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">Finanzas</span>
                                <span class="card-skill-pill">Excel Avanzado</span>
                                <span class="card-skill-pill">Liderazgo</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="2">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Carlos Morales">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 3: Camila Zuluaga Henao -->
                    <article class="egresado-card" 
                        data-name="Camila Zuluaga Henao" 
                        data-promo="2021" 
                        data-enfasis="academico" 
                        data-ciudad="medellin"
                        data-cargo="Diseñadora UI/UX"
                        data-empresa="Globant"
                        data-destacado="true"
                    >
                        <div class="card-top-banner">
                            <span class="card-promo-badge">Promoción 2021</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar egresado-avatar-amber" aria-hidden="true">C</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Camila Zuluaga Henao</h2>
                            <p class="card-emphasis">Bachillerato Académico</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Diseñadora de Producto UI/UX en Globant
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">Figma</span>
                                <span class="card-skill-pill">Design Systems</span>
                                <span class="card-skill-pill">UX Research</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="3">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Camila Zuluaga">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 4: Mateo Henao Castro -->
                    <article class="egresado-card" 
                        data-name="Mateo Henao Castro" 
                        data-promo="2019" 
                        data-enfasis="sistemas" 
                        data-ciudad="medellin"
                        data-cargo="Líder Técnico Cloud"
                        data-empresa="Ruta N"
                        data-destacado="true"
                    >
                        <div class="card-top-banner card-top-banner-blue">
                            <span class="card-promo-badge card-promo-badge-blue">Promoción 2019</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar" aria-hidden="true">M</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Mateo Henao Castro</h2>
                            <p class="card-emphasis">Técnico en Sistemas e Informática</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Líder Técnico Cloud en Ruta N
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">Python</span>
                                <span class="card-skill-pill">AWS</span>
                                <span class="card-skill-pill">Docker</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="4">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Mateo Henao">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 5: Laura Restrepo Silva -->
                    <article class="egresado-card" 
                        data-name="Laura Restrepo Silva" 
                        data-promo="2022" 
                        data-enfasis="academico" 
                        data-ciudad="medellin"
                        data-cargo="Médica Interna"
                        data-empresa="Hospital San Vicente"
                        data-destacado="false"
                    >
                        <div class="card-top-banner">
                            <span class="card-promo-badge">Promoción 2022</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar egresado-avatar-teal" aria-hidden="true">L</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Laura Restrepo Silva</h2>
                            <p class="card-emphasis">Bachillerato Académico con Honores</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Médica Interna - Univ. de Antioquia
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">Salud Pública</span>
                                <span class="card-skill-pill">Bioética</span>
                                <span class="card-skill-pill">Investigación</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="5">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Laura Restrepo">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 6: Alejandro Quintero -->
                    <article class="egresado-card" 
                        data-name="Alejandro Quintero" 
                        data-promo="2021" 
                        data-enfasis="comercial" 
                        data-ciudad="bogota"
                        data-cargo="Especialista en Marketing"
                        data-empresa="Cementos Argos"
                        data-destacado="false"
                    >
                        <div class="card-top-banner card-top-banner-blue">
                            <span class="card-promo-badge card-promo-badge-blue">Promoción 2021</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar egresado-avatar-blue" aria-hidden="true">A</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Alejandro Quintero</h2>
                            <p class="card-emphasis">Técnico en Gestión Comercial</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Especialista Marketing en Cementos Argos
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Bogotá, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">Estrategia Digital</span>
                                <span class="card-skill-pill">Analítica</span>
                                <span class="card-skill-pill">Negociación</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="6">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Alejandro Quintero">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 7: Daniela Cardona Pineda -->
                    <article class="egresado-card" 
                        data-name="Daniela Cardona Pineda" 
                        data-promo="2018" 
                        data-enfasis="sistemas" 
                        data-ciudad="medellin"
                        data-cargo="Analista de Datos"
                        data-empresa="Seguros SURA"
                        data-destacado="false"
                    >
                        <div class="card-top-banner">
                            <span class="card-promo-badge">Promoción 2018</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar egresado-avatar-amber" aria-hidden="true">D</div>

                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Daniela Cardona Pineda</h2>
                            <p class="card-emphasis">Técnico en Sistemas e Informática</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Analista de Datos en Seguros SURA
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">SQL</span>
                                <span class="card-skill-pill">Power BI</span>
                                <span class="card-skill-pill">Estadística</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <button type="button" class="btn-card-profile btn-ver-perfil" data-egresado-id="7">Ver perfil</button>
                            <button type="button" class="btn-card-connect btn-conectar-egresado" data-name="Daniela Cardona">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                                Conectar
                            </button>
                        </div>
                    </article>

                    <!-- Tarjeta 8: Santiago Emitola (Tu perfil) -->
                    <article class="egresado-card" 
                        data-name="Santiago Emitola" 
                        data-promo="2021" 
                        data-enfasis="sistemas" 
                        data-ciudad="medellin"
                        data-cargo="Desarrollador Web"
                        data-empresa="InnovaTech Medellín"
                        data-destacado="true"
                    >
                        <div class="card-top-banner">
                            <span class="card-promo-badge">Promoción 2021 (Tú)</span>
                        </div>
                        <div class="card-avatar-wrap">
                            <div class="egresado-avatar" aria-hidden="true">S</div>
                            <span class="card-badge-status" style="background-color: #E3F2FD; color: #1565C0; border-color: rgba(21, 101, 192, 0.25);">
                                Tu perfil
                            </span>
                        </div>
                        <div class="card-body-content">
                            <h2 class="card-name">Santiago Emitola</h2>
                            <p class="card-emphasis">Técnico en Sistemas y Gestión Comercial</p>
                            <p class="card-occupation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                Desarrollador Web en InnovaTech
                            </p>
                            <p class="card-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </p>
                            <div class="card-skills-row">
                                <span class="card-skill-pill">PHP</span>
                                <span class="card-skill-pill">JavaScript</span>
                                <span class="card-skill-pill">HTML/CSS</span>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <a href="perfil.php" class="btn-card-profile" style="flex: 1 1 100%;">Editar mi perfil</a>
                        </div>
                    </article>

                </section>

                <!-- F. Estado Vacío (Sin Resultados) -->
                <div class="egresados-empty-state" id="emptyStateEgresados" role="status" aria-live="polite">
                    <div class="empty-state-icon" aria-hidden="true">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </div>
                    <h2 class="empty-state-title">No se encontraron egresados con los filtros seleccionados</h2>
                    <p class="empty-state-desc">
                        Intenta ajustar el término de búsqueda, seleccionar otra promoción o limpiar los filtros para ver todos los exalumnos registrados.
                    </p>
                    <button type="button" class="btn btn-primary" id="btnRestablecerVacios">
                        Restablecer todos los filtros
                    </button>
                </div>

                <!-- G. Paginación Accesible -->
                <nav class="egresados-pagination-bar" aria-label="Páginas del directorio de egresados">
                    <span class="pagination-info-text">
                        Mostrando <strong>1</strong> a <strong>8</strong> de <strong>1,250</strong> egresados registrados
                    </span>
                    <div class="pagination-buttons-group">
                        <button type="button" class="btn-page-item" disabled aria-label="Página anterior">&laquo;</button>
                        <button type="button" class="btn-page-item active" aria-current="page">1</button>
                        <button type="button" class="btn-page-item">2</button>
                        <button type="button" class="btn-page-item">3</button>
                        <button type="button" class="btn-page-item" aria-label="Página siguiente">&raquo;</button>
                    </div>
                </nav>

            </main>
        </div>
    </div>

    <!-- H. Modal Accesible de Detalle del Egresado -->
    <div class="egresado-modal-overlay" id="modalDetalleEgresado" role="dialog" aria-modal="true" aria-labelledby="modalEgresadoName">
        <div class="egresado-modal-window modal-egresado-card">
            
            <!-- Cabecera Fija Estática (Sticky Header) -->
            <div class="modal-fixed-header">
                <div class="egresado-modal-cover modal-banner">
                    <button type="button" class="egresado-modal-close-btn" id="btnCerrarModalEgresado" aria-label="Cerrar ventana de detalle">&times;</button>
                </div>

                <div class="egresado-modal-avatar-row modal-profile-header">
                    <div class="egresado-modal-avatar modal-avatar" id="modalEgresadoAvatar">E</div>
                    <div class="modal-identity">
                        <h2 class="modal-egresado-name modal-nombre" id="modalEgresadoName">Nombre del egresado</h2>
                        <p class="modal-egresado-emphasis modal-titulo-academico" id="modalEgresadoEmphasis">Técnico en Sistemas e Informática</p>
                    </div>
                </div>
            </div>

            <!-- Contenedor con Scroll Vertical Exclusivo -->
            <div class="egresado-modal-body modal-body modal-body-scrollable">

                <div class="modal-details-grid modal-quick-info">
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Promoción</span>
                        <span class="modal-detail-val" id="modalEgresadoPromo">Promoción 2021</span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Ubicación actual</span>
                        <span class="modal-detail-val" id="modalEgresadoCiudad">Medellín, Colombia</span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Ocupación / Cargo</span>
                        <span class="modal-detail-val" id="modalEgresadoCargo">Ingeniera de Software</span>
                    </div>
                    <div class="modal-detail-item">
                        <span class="modal-detail-label">Organización / Empresa</span>
                        <span class="modal-detail-val" id="modalEgresadoEmpresa">Mercado Libre</span>
                    </div>
                </div>

                <div class="modal-info-block">
                    <h3 class="modal-info-title">Trayectoria y Aporte Alzatista</h3>
                    <p class="modal-info-text" id="modalEgresadoBio">
                        Exalumno destacado por su compromiso académico y comunitario en la I.E. Gilberto Alzate Avendaño. Participa activamente en redes profesionales y programas de mentoría para bachilleres alzatistas.
                    </p>
                </div>

                <div class="modal-info-block">
                    <h3 class="modal-info-title">Habilidades y Competencias</h3>
                    <div class="card-skills-row" id="modalEgresadoSkills">
                        <span class="card-skill-pill">Tecnología</span>
                        <span class="card-skill-pill">Liderazgo</span>
                    </div>
                </div>

                <div class="modal-info-block">
                    <h3 class="modal-info-title">Enlaces y Contacto Profesional</h3>
                    <div class="modal-links-row">
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="modal-link-chip" id="modalLinkLinkedin">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                            LinkedIn
                        </a>
                        <a href="mailto:contacto@ejemplo.com" class="modal-link-chip" id="modalLinkEmail">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            Enviar correo
                        </a>
                    </div>
                </div>

            </div>

            <footer class="egresado-modal-footer modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-modal-cancel" id="btnCerrarModalFooter">Cerrar</button>
                <button type="button" class="btn btn-primary btn-modal-conectar btn-modal-connect" id="btnModalConectar">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                    <span>Conectar con egresado</span>
                </button>
            </footer>

        </div>
    </div>

    <!-- I. Notificación Toast Flotante -->
    <div class="egresado-toast" id="toastNotificacionEgresados" role="status" aria-live="polite">
        <span class="egresado-toast-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </span>
        <span id="toastMensajeEgresados">¡Solicitud de conexión enviada!</span>
    </div>

    <!-- J. Lógica JavaScript Vanilla para el Directorio de Egresados -->
    <script src="assets/js/egresados.js?v=<?php echo defined('APP_VERSION') ? APP_VERSION : '0.1.1'; ?>"></script>

    <!-- Script global del Page Loader (desvanecimiento y desmontaje) -->
    <script src="assets/js/loader.js?v=<?php echo APP_VERSION; ?>"></script>
</body>
</html>
