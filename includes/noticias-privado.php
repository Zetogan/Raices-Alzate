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

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Usuario');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

// Hojas de estilo y scripts adicionales gestionados por los includes globales
$extraCss = ['dashboard.css', 'noticias.css'];
$extraJs = ['assets/js/noticias.js'];

// Catálogo canónico de noticias ordenado estrictamente de la MÁS RECIENTE a la MÁS ANTIGUA
$noticiasBase = [
    [
        'id'          => 1,
        'titulo'      => 'Nueva oportunidad para egresados',
        'fecha'       => '15 de septiembre de 2026',
        'categoria'   => 'Comunidad',
        'portada'     => 'assets/img/noticia-1.svg',
        'descripcion' => 'Conoce las nuevas oportunidades disponibles para nuestra comunidad de egresados. Mantente informado sobre convocatorias, actividades y beneficios que pueden contribuir a tu desarrollo académico y profesional.'
    ],
    [
        'id'          => 2,
        'titulo'      => 'Encuentro de egresados',
        'fecha'       => '10 de septiembre de 2026',
        'categoria'   => 'Eventos',
        'portada'     => 'assets/img/noticia-2.svg',
        'descripcion' => 'Participa en nuestro próximo encuentro y vuelve a conectar con la institución.'
    ],
    [
        'id'          => 3,
        'titulo'      => 'Historias que inspiran',
        'fecha'       => '5 de septiembre de 2026',
        'categoria'   => 'Destacados',
        'portada'     => 'assets/img/noticia-3.svg',
        'descripcion' => 'Conoce las experiencias y logros de nuestros egresados.'
    ],
    [
        'id'          => 4,
        'titulo'      => 'Nuevos proyectos de la comunidad',
        'fecha'       => '28 de agosto de 2026',
        'categoria'   => 'Proyectos',
        'portada'     => 'assets/img/proyecto-cover-1.svg',
        'descripcion' => 'Descubre los proyectos que están desarrollando nuestros egresados.'
    ],
    [
        'id'          => 5,
        'titulo'      => 'Actividades institucionales',
        'fecha'       => '20 de agosto de 2026',
        'categoria'   => 'Institucional',
        'portada'     => 'assets/img/noticia-2.svg',
        'descripcion' => 'Consulta las próximas actividades y eventos de la institución.'
    ],
    [
        'id'          => 6,
        'titulo'      => 'Comunidad Raíces Alzate',
        'fecha'       => '12 de agosto de 2026',
        'categoria'   => 'Comunidad',
        'portada'     => 'assets/img/noticia-1.svg',
        'descripcion' => 'Seguimos fortaleciendo los vínculos entre egresados.'
    ]
];

$noticias = [];

// Carga de noticias publicadas desde la base de datos MySQL
require_once __DIR__ . '/../config/database.php';
if (!empty($pdo)) {
    try {
        $stmtNotDb = $pdo->query("
            SELECT id, titulo, descripcion, categoria, portada, creado_en
            FROM noticias
            WHERE estado IN ('publicado', 'aprobado')
            ORDER BY creado_en DESC
        ");
        $notRows = $stmtNotDb->fetchAll(PDO::FETCH_ASSOC);
        foreach ($notRows as $nr) {
            $noticias[] = [
                'id'          => (int)$nr['id'],
                'titulo'      => $nr['titulo'],
                'categoria'   => $nr['categoria'],
                'portada'     => !empty($nr['portada']) ? $nr['portada'] : '',
                'fecha'       => !empty($nr['creado_en']) ? date('d M Y', strtotime($nr['creado_en'])) : 'Reciente',
                'descripcion' => $nr['descripcion']
            ];
        }
    } catch (PDOException $e) {
        // Silencioso
    }
}

// Agregar las noticias canónicas base si no existen en BD
foreach ($noticiasBase as $nb) {
    $found = false;
    foreach ($noticias as $n) {
        if ($n['titulo'] === $nb['titulo']) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $noticias[] = $nb;
    }
}

$isAdmin = function_exists('hasRole') ? hasRole('administrador') : (strtolower($userRole ?? '') === 'administrador');

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
            <header class="section-header news-header noticias-header-block" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div class="noticias-header-titles">
                    <h1 class="section-title news-title noticias-header-title">Noticias y novedades</h1>
                    <p class="section-subtitle news-subtitle noticias-header-subtitle">Mantente informado sobre las novedades y actividades de la comunidad de egresados.</p>
                </div>
                <?php if ($isAdmin): ?>
                <div class="noticias-admin-action">
                    <a href="index.php?page=crear-noticia" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 10px 18px; border-radius: 8px; text-decoration: none; background-color: #D32F2F; color: #FFFFFF; font-size: 14px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Publicar noticia</span>
                    </a>
                </div>
                <?php endif; ?>
            </header>

            <!-- Contenedor blanco unificado -->
            <div class="news-card-container noticias-card-container" aria-label="Catálogo de noticias y novedades de egresados">
                <!-- Grid o contenido de noticias -->
                <div class="noticias-grid news-grid" id="noticiasGrid">
                    <?php foreach ($noticias as $item): ?>
                    <!-- Tarjeta interactiva clickeable en cualquier área que redirige a detalle-noticia.php -->
                    <a href="index.php?page=detalle-noticia&id=<?php echo (int)$item['id']; ?>" class="noticia-card news-card card-noticia" data-href="index.php?page=detalle-noticia&id=<?php echo (int)$item['id']; ?>" aria-label="Ver detalle de <?php echo htmlspecialchars($item['titulo']); ?>">
                        <div class="noticia-cover-wrapper">
                            <?php 
                                $covers = ['assets/img/noticia-1.svg', 'assets/img/noticia-2.svg', 'assets/img/noticia-3.svg'];
                                $coverToShow = !empty($item['portada']) ? $item['portada'] : $covers[((int)($item['id'] ?? 1)) % count($covers)];
                            ?>
                            <img src="<?php echo htmlspecialchars($coverToShow); ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>" class="noticia-cover-img" style="width: 100%; height: 100%; object-fit: cover; display: block; border-top-left-radius: 8px; border-top-right-radius: 8px;">
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
