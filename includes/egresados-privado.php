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

// 1. Asignación obligatoria de variables requeridas por la directiva
$seccion_activa = 'egresados';
$titulo_seccion = 'Egresados';
$titulo_pagina = 'Egresados — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'egresados';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Datos de sesión del usuario autenticado
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Usuario');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ('Egresados | ' . APP_NAME)); ?></title>

    <!-- Base URL para resolución de enlaces y assets -->
    <base href="<?php echo BASE_URL; ?>/">

    <!-- Favicon e Iconografía Institucional Oficial -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo-alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo-alzate.ico">

    <!-- 1. Tipografía Inter local inmediata (@font-face de assets/fonts/) -->
    <link rel="stylesheet" href="<?php echo egresadosAssetCssUrl('fonts.css'); ?>">

    <!-- Fallback asíncrono no bloqueante de Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
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
    <?php require_once __DIR__ . '/loader.php'; ?>

    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="dashboard-layout" id="dashboardLayout">
        <!-- 1. Barra Lateral de Navegación Privada (Sidebar con ítem Egresados activo) -->
        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <!-- 2. Área Principal de Contenido -->
        <div class="dashboard-main-area">
            <!-- Barra Superior (Topbar) -->
            <?php require_once __DIR__ . '/topbar.php'; ?>

            <!-- Contenido Central del Directorio de Egresados -->
            <main class="dashboard-content egresados-content-wrapper" id="egresadosMainContent" tabindex="-1">
                
                <!-- A. Miga de pan / Breadcrumb -->
                <nav class="egresados-breadcrumb-nav" aria-label="Ruta de navegación">
                    <ol class="egresados-breadcrumb-list">
                        <li class="egresados-breadcrumb-item">
                            <a href="index.php?page=dashboard" class="egresados-breadcrumb-link">Dashboard</a>
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
                            <span><strong><?= number_format($totalEgresados) ?></strong> Egresados</span>
                        </div>

                        <div class="hero-stat-badge hero-stat-badge-blue">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                            <span><strong><?= count($promocionesDisponibles) ?></strong> Promociones</span>
                        </div>
                    </div>
                </header>

                <!-- C. Barra de Búsqueda y Filtros en Tiempo Real -->
                <section class="egresados-filter-card" aria-label="Búsqueda y filtros del directorio">
                    <!-- Token CSRF para peticiones seguras de conexión -->
                    <input type="hidden" id="csrfTokenEgresados" value="<?php echo htmlspecialchars(function_exists('getCsrfToken') ? getCsrfToken() : ($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

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

                        <!-- Filtro por Promoción Dinámico -->
                        <div class="filter-select-wrap">
                            <select class="filter-select" id="selectPromocion" aria-label="Filtrar por año de promoción">
                                <option value="">Todas las promociones</option>
                                <?php foreach ($promocionesDisponibles as $promoAnio): ?>
                                    <option value="<?= htmlspecialchars((string)$promoAnio) ?>">Promoción <?= htmlspecialchars((string)$promoAnio) ?></option>
                                <?php endforeach; ?>
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
                            Mostrando <strong id="contadorResultados"><?= $totalEgresados ?></strong> egresados encontrados
                        </span>
                        <div class="active-filter-chips" id="contenedorChipsFiltros"></div>
                    </div>
                </section>

                <!-- D. Pestañas de Vista Rápida Dinámicas -->
                <nav class="egresados-tabs-container" aria-label="Filtrado rápido de egresados">
                    <button type="button" class="egresados-tab-btn active" data-category="todos">
                        Todos los egresados
                        <span class="tab-badge" id="badgeTotalEgresados"><?= $totalEgresados ?></span>
                    </button>
                    <button type="button" class="egresados-tab-btn" data-category="mi_promocion" data-promo="<?= htmlspecialchars($miPromocionYear, ENT_QUOTES, 'UTF-8') ?>">
                        Mi promoción (<?= htmlspecialchars($miPromocionYear, ENT_QUOTES, 'UTF-8') ?>)
                        <span class="tab-badge" id="badgeMiPromocion"><?= $totalMiPromocion ?></span>
                    </button>
                    <button type="button" class="egresados-tab-btn" data-category="destacados">
                        Destacados
                        <span class="tab-badge" id="badgeDestacados"><?= $totalDestacados ?></span>
                    </button>
                    <button type="button" class="egresados-tab-btn" data-category="conectados">
                        Conectados
                        <span class="tab-badge" id="badgeConectados"><?= $totalConectados ?></span>
                    </button>
                </nav>

                <!-- E. Grid de Tarjetas de Egresados (Datos Reales de Base de Datos MySQL) -->
                <section class="egresados-cards-grid" id="egresadosCardsGrid" aria-label="Listado de egresados alzatistas">
                    <?php if (!empty($egresadosList)): ?>
                        <?php foreach ($egresadosList as $item): ?>
                            <article class="egresado-card" 
                                data-id="<?= (int)$item['id'] ?>"
                                data-name="<?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8') ?>" 
                                data-promo="<?= htmlspecialchars($item['promocion'], ENT_QUOTES, 'UTF-8') ?>" 
                                data-enfasis="<?= htmlspecialchars($item['enfasis'], ENT_QUOTES, 'UTF-8') ?>" 
                                data-ciudad="<?= htmlspecialchars($item['ciudad_slug'], ENT_QUOTES, 'UTF-8') ?>"
                                data-cargo="<?= htmlspecialchars($item['ocupacion'], ENT_QUOTES, 'UTF-8') ?>"
                                data-empresa="<?= htmlspecialchars($item['empresa'], ENT_QUOTES, 'UTF-8') ?>"
                                data-destacado="<?= $item['es_destacado'] ? 'true' : 'false' ?>"
                                data-conectado="<?= ($item['conexion_estado'] === 'conectado') ? 'true' : 'false' ?>"
                            >
                                <div class="card-top-banner">
                                    <span class="card-promo-badge">Promoción <?= htmlspecialchars($item['promocion'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php if ($item['es_destacado'] || $item['porcentaje'] > 0): ?>
                                        <span class="card-destacado-pill" title="Completitud de perfil: <?= (int)$item['porcentaje'] ?>%">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                            <?= (int)$item['porcentaje'] ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-avatar-wrap">
                                    <?php if (!empty($item['foto_perfil']) && file_exists(__DIR__ . '/../' . ltrim($item['foto_perfil'], '/'))): ?>
                                        <img src="<?= htmlspecialchars($item['foto_perfil'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8') ?>" class="egresado-avatar egresado-avatar-img">
                                    <?php else: ?>
                                        <div class="egresado-avatar <?= htmlspecialchars($item['avatar_color'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true">
                                            <?= htmlspecialchars($item['inicial'], ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body-content">
                                    <h2 class="card-name"><?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
                                    <p class="card-emphasis"><?= htmlspecialchars($item['carrera'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="card-occupation">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                        <?= htmlspecialchars($item['ocupacion_empresa'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                    <p class="card-location">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        <?= htmlspecialchars($item['ubicacion'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                    <?php if (!empty($item['habilidades'])): ?>
                                        <div class="card-skills-row">
                                            <?php foreach (array_slice($item['habilidades'], 0, 3) as $habilidad): ?>
                                                <span class="card-skill-pill"><?= htmlspecialchars($habilidad, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="egresado-card-actions">
                                    <!-- Botón izquierdo: Ver perfil (Siempre en rojo corporativo) -->
                                    <a href="index.php?page=detalle-egresado&id=<?= (int)$item['id'] ?>" class="btn-card-ver">
                                        Ver perfil
                                    </a>

                                    <!-- Botón derecho condicional según estado de conexión -->
                                    <?php if ($item['conexion_estado'] === 'propio'): ?>
                                        <a href="index.php?page=perfil" class="btn-card-action btn-card-own" title="Ir a mi perfil">
                                            <span>Mi perfil</span>
                                        </a>
                                    <?php elseif ($item['conexion_estado'] === 'conectado'): ?>
                                        <a href="index.php?page=mensajes" class="btn-card-action btn-card-message" title="Enviar mensaje">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                            <span>Enviar mensaje</span>
                                        </a>
                                    <?php elseif ($item['conexion_estado'] === 'solicitud_enviada'): ?>
                                        <button type="button" class="btn-card-action btn-card-pending" disabled title="Solicitud pendiente de respuesta">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span>Solicitud enviada</span>
                                        </button>
                                    <?php elseif ($item['conexion_estado'] === 'solicitud_recibida'): ?>
                                        <button type="button" class="btn-card-action btn-card-connect" data-id="<?= (int)$item['id'] ?>" onclick="conectarEgresado(this, <?= (int)$item['id'] ?>)" title="Aceptar solicitud de conexión">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <span>Aceptar soli</span>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn-card-action btn-card-connect" data-id="<?= (int)$item['id'] ?>" onclick="conectarEgresado(this, <?= (int)$item['id'] ?>)" title="Conectar con egresado">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="8.5" cy="7.5" r="4"></circle>
                                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                                <line x1="23" y1="11" x2="17" y2="11"></line>
                                            </svg>
                                            <span>Conectar</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                        Mostrando <strong>1</strong> a <strong id="paginacionVisibles"><?= $totalEgresados ?></strong> de <strong id="paginacionTotal"><?= $totalEgresados ?></strong> egresados registrados
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

    <!-- H. Navegación fluida a pantalla completa detalle-egresado.php (Modal retirado) -->

    <!-- I. Notificación Toast Flotante -->
    <div class="egresado-toast" id="toastNotificacionEgresados" role="status" aria-live="polite" style="display: none;">
        <span class="egresado-toast-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </span>
        <span id="toastMensajeEgresados"></span>
    </div>

    <!-- J. Lógica JavaScript Vanilla para el Directorio de Egresados -->
    <script src="assets/js/egresados.js?v=<?php echo defined('APP_VERSION') ? APP_VERSION : '0.1.1'; ?>"></script>

    <!-- Script global del Page Loader (desvanecimiento y desmontaje) -->
    <script src="assets/js/loader.js?v=<?php echo APP_VERSION; ?>"></script>
</body>
</html>
