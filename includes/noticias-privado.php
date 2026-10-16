<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Catálogo de Noticias (Panel Privado)
 * Especificación Figma: 07 - Noticias - Desktop
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'noticias';
$titulo_seccion = 'Noticias';
$titulo_pagina = 'Noticias — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'noticias';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

// Hojas de estilo y scripts adicionales gestionados por los includes globales
$extraCss = ['dashboard.css', 'noticias.css'];
$extraJs = ['assets/js/noticias.js'];

// Catálogo canónico de noticias ordenado estrictamente de la MÁS RECIENTE a la MÁS ANTIGUA
$noticias = [
    [
        'id'          => 1,
        'titulo'      => 'Nueva oportunidad para egresados',
        'fecha'       => '15 de septiembre de 2026',
        'categoria'   => 'Comunidad',
        'descripcion' => 'Conoce las nuevas oportunidades disponibles para nuestra comunidad de egresados. Mantente informado sobre convocatorias, actividades y beneficios que pueden contribuir a tu desarrollo académico y profesional.'
    ],
    [
        'id'          => 2,
        'titulo'      => 'Encuentro de egresados',
        'fecha'       => '10 de septiembre de 2026',
        'categoria'   => 'Eventos',
        'descripcion' => 'Participa en nuestro próximo encuentro y vuelve a conectar con la institución.'
    ],
    [
        'id'          => 3,
        'titulo'      => 'Historias que inspiran',
        'fecha'       => '5 de septiembre de 2026',
        'categoria'   => 'Destacados',
        'descripcion' => 'Conoce las experiencias y logros de nuestros egresados.'
    ],
    [
        'id'          => 4,
        'titulo'      => 'Nuevos proyectos de la comunidad',
        'fecha'       => '28 de agosto de 2026',
        'categoria'   => 'Proyectos',
        'descripcion' => 'Descubre los proyectos que están desarrollando nuestros egresados.'
    ],
    [
        'id'          => 5,
        'titulo'      => 'Actividades institucionales',
        'fecha'       => '20 de agosto de 2026',
        'categoria'   => 'Institucional',
        'descripcion' => 'Consulta las próximas actividades y eventos de la institución.'
    ],
    [
        'id'          => 6,
        'titulo'      => 'Comunidad Raíces Alzate',
        'fecha'       => '12 de agosto de 2026',
        'categoria'   => 'Comunidad',
        'descripcion' => 'Seguimos fortaleciendo los vínculos entre egresados.'
    ]
];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/header.php';
?>

<!-- Script Anti-FOUC para transiciones suaves si el sidebar está contraído -->
<script>
    try {
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    } catch(e) {}
</script>

<link rel="stylesheet" href="assets/css/noticias.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/topbar.php'; ?>

        <main class="content-area dashboard-content noticias-content-wrapper" id="mainContent" tabindex="-1">
            <!-- Encabezado de la sección -->
            <header class="section-header news-header noticias-header-block">
                <h1 class="section-title news-title noticias-header-title">Noticias y novedades</h1>
                <p class="section-subtitle news-subtitle noticias-header-subtitle">Mantente informado sobre las novedades y actividades de la comunidad de egresados.</p>
            </header>

            <!-- Contenedor blanco unificado -->
            <div class="news-card-container noticias-card-container" aria-label="Catálogo de noticias y novedades de egresados">
                <!-- Grid o contenido de noticias -->
                <div class="noticias-grid news-grid" id="noticiasGrid">
                    <?php foreach ($noticias as $item): ?>
                    <!-- Tarjeta interactiva clickeable en cualquier área que redirige a detalle-noticia.php -->
                    <a href="detalle-noticia.php?id=<?php echo (int)$item['id']; ?>" class="noticia-card news-card card-noticia" data-href="detalle-noticia.php?id=<?php echo (int)$item['id']; ?>" aria-label="Ver detalle de <?php echo htmlspecialchars($item['titulo']); ?>">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <span class="noticia-categoria-badge"><?php echo htmlspecialchars($item['categoria']); ?></span>
                            <h2 class="noticia-title news-card-title"><?php echo htmlspecialchars($item['titulo']); ?></h2>
                            <p class="noticia-description"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                            <div class="noticia-date"><?php echo htmlspecialchars($item['fecha']); ?></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Botón inferior: "Ver más noticias" centrado, estilo outline azul (#1565C0) -->
                <div class="noticias-actions-bottom" id="noticiasStatusContainer">
                    <button type="button" class="btn-ver-mas-noticias" id="btnVerMasNoticias" aria-label="Ver más noticias de la comunidad">
                        Ver más noticias
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
