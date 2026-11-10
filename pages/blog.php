<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Blog y Novedades de la Comunidad (Noticias, Proyectos y Oportunidades Unificados)
 * Versión: 1.0.0
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

// 1. Detección de sesión y configuración de layout
$estaAutenticado = isAuthenticated();
$user = $estaAutenticado ? currentUser() : [];
$userId = (int)($user['id'] ?? 0);
$userName = trim($user['nombre'] ?? $user['name'] ?? 'Usuario');
$userRole = trim($user['rol'] ?? $user['role'] ?? 'Egresado');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';

$seccion_activa = $estaAutenticado ? 'blog' : '';
$currentPage = 'blog';
$titulo_seccion = 'Blog & Novedades';
$titulo_pagina = 'Blog, Noticias, Proyectos y Oportunidades — Raíces Alzate';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Recursos CSS y JS específicos del blog
$extraCss = ['dashboard.css', 'blog.css'];
$extraJs = ['assets/js/dashboard.js', 'assets/js/blog.js'];

// Filtro inicial por parámetro GET (?tipo=noticias, ?tipo=proyectos, ?tipo=oportunidades)
$tipoFiltroInicial = strtolower(trim($_GET['tipo'] ?? 'todos'));
if (!in_array($tipoFiltroInicial, ['todos', 'noticias', 'proyectos', 'oportunidades'], true)) {
    $tipoFiltroInicial = 'todos';
}

// =============================================================================
// 2. RECOPILACIÓN UNIFICADA DE DATOS (NOTICIAS, PROYECTOS, OPORTUNIDADES)
// =============================================================================
$publicaciones = [];

// A. NOTICIAS
$noticiasBase = [
    [
        'id'          => 1,
        'titulo'      => 'Nueva oportunidad de desarrollo y formación para egresados',
        'fecha'       => '15 Oct 2026',
        'timestamp'   => strtotime('2026-10-15 10:00:00'),
        'categoria'   => 'Comunidad',
        'portada'     => 'assets/img/noticia-1.svg',
        'descripcion' => 'Conoce las nuevas oportunidades disponibles para nuestra comunidad de egresados alzatistas. Mantente informado sobre convocatorias, actividades y beneficios continuos.'
    ],
    [
        'id'          => 2,
        'titulo'      => 'Gran Encuentro Institucional de Egresados Alzatistas 2026',
        'fecha'       => '10 Oct 2026',
        'timestamp'   => strtotime('2026-10-10 14:30:00'),
        'categoria'   => 'Eventos',
        'portada'     => 'assets/img/noticia-2.svg',
        'descripcion' => 'Participa en nuestro próximo encuentro presencial y vuelve a conectar con tus compañeros, profesores y proyectos que transforman la institución.'
    ],
    [
        'id'          => 3,
        'titulo'      => 'Historias que inspiran: De las aulas al liderazgo profesional',
        'fecha'       => '05 Oct 2026',
        'timestamp'   => strtotime('2026-10-05 09:15:00'),
        'categoria'   => 'Destacados',
        'portada'     => 'assets/img/noticia-3.svg',
        'descripcion' => 'Conoce las experiencias y logros alcanzados por egresados destacados en áreas de tecnología, emprendimiento social y desarrollo científico.'
    ]
];

if (!empty($pdo)) {
    try {
        $stmtNot = $pdo->query("
            SELECT n.id, n.titulo, n.descripcion, n.categoria, n.portada, n.creado_en,
                   u.nombre_completo AS autor_nombre
            FROM noticias n
            LEFT JOIN usuarios u ON u.id = n.autor_id
            WHERE n.estado IN ('publicado', 'aprobado')
            ORDER BY n.creado_en DESC
        ");
        $notRows = $stmtNot ? $stmtNot->fetchAll(PDO::FETCH_ASSOC) : [];
        foreach ($notRows as $nr) {
            $ts = !empty($nr['creado_en']) ? strtotime($nr['creado_en']) : time();
            $publicaciones[] = [
                'id'          => (int)$nr['id'],
                'tipo'        => 'noticias',
                'tipo_label'  => 'Noticia',
                'badge_class' => 'badge-tipo-noticia',
                'badge_icon'  => '📰',
                'titulo'      => $nr['titulo'],
                'descripcion' => $nr['descripcion'],
                'categoria'   => $nr['categoria'] ?: 'Institucional',
                'portada'     => !empty($nr['portada']) ? $nr['portada'] : 'assets/img/noticia-1.svg',
                'autor'       => !empty($nr['autor_nombre']) ? $nr['autor_nombre'] : 'Comité Institucional',
                'empresa'     => '',
                'modalidad'   => '',
                'ubicacion'   => '',
                'enlace'      => '',
                'fecha'       => date('d M Y', $ts),
                'timestamp'   => $ts,
                'url_detalle' => 'index.php?page=detalle-noticia&id=' . (int)$nr['id'],
                'es_propio'   => false,
            ];
        }
    } catch (PDOException $e) {
        error_log('Error cargando noticias en blog: ' . $e->getMessage());
    }
}

// Fallback de noticias base para catálogo completo
foreach ($noticiasBase as $nb) {
    $exists = false;
    foreach ($publicaciones as $p) {
        if ($p['tipo'] === 'noticias' && $p['titulo'] === $nb['titulo']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $publicaciones[] = [
            'id'          => $nb['id'],
            'tipo'        => 'noticias',
            'tipo_label'  => 'Noticia',
            'badge_class' => 'badge-tipo-noticia',
            'badge_icon'  => '📰',
            'titulo'      => $nb['titulo'],
            'descripcion' => $nb['descripcion'],
            'categoria'   => $nb['categoria'],
            'portada'     => $nb['portada'],
            'autor'       => 'Comité de Egresados',
            'empresa'     => '',
            'modalidad'   => '',
            'ubicacion'   => '',
            'enlace'      => '',
            'fecha'       => $nb['fecha'],
            'timestamp'   => $nb['timestamp'],
            'url_detalle' => 'index.php?page=detalle-noticia&id=' . $nb['id'],
            'es_propio'   => false,
        ];
    }
}

// B. PROYECTOS DE EGRESADOS
$proyectosBase = [
    [
        'id'          => 1,
        'titulo'      => 'Sistema de gestión y seguimiento académico para colegios públicos',
        'descripcion' => 'Plataforma web colaborativa para facilitar la organización académica, analítica de calificaciones y comunicación en instituciones educativas.',
        'autor'       => 'Santiago Emitola',
        'categoria'   => 'Tecnología',
        'portada'     => 'assets/img/proyecto-cover-1.svg',
        'fecha'       => '12 Oct 2026',
        'timestamp'   => strtotime('2026-10-12 11:20:00'),
        'enlace'      => 'https://github.com'
    ],
    [
        'id'          => 2,
        'titulo'      => 'App móvil para fomento de emprendimientos de egresados',
        'descripcion' => 'Herramienta digital nativa desarrollada en Flutter para promover pequeños negocios, servicios locales y redes de colaboración alzatista.',
        'autor'       => 'María López',
        'categoria'   => 'Emprendimiento',
        'portada'     => 'assets/img/proyecto-cover-2.svg',
        'fecha'       => '08 Oct 2026',
        'timestamp'   => strtotime('2026-10-08 16:45:00'),
        'enlace'      => ''
    ],
    [
        'id'          => 3,
        'titulo'      => 'Laboratorio de Diseño e Identidad Visual Comunitaria',
        'descripcion' => 'Espacio colectivo de egresados diseñadores para apoyar a organizaciones barriales de Medellín con identidad corporativa y packaging sostenible.',
        'autor'       => 'Carlos Rodríguez',
        'categoria'   => 'Diseño',
        'portada'     => 'assets/img/proyecto-cover-3.svg',
        'fecha'       => '01 Oct 2026',
        'timestamp'   => strtotime('2026-10-01 18:00:00'),
        'enlace'      => ''
    ]
];

if (!empty($pdo)) {
    try {
        $stmtProy = $pdo->prepare("
            SELECT p.id, p.titulo, p.descripcion, p.categoria, p.enlace, p.portada, p.autor_id, p.creado_en,
                   u.nombre_completo AS autor_nombre
            FROM proyectos p
            LEFT JOIN usuarios u ON u.id = p.autor_id
            WHERE p.estado IN ('publicado', 'aprobado') OR p.autor_id = :uid
            ORDER BY p.creado_en DESC
        ");
        $stmtProy->execute([':uid' => $userId]);
        $proyRows = $stmtProy->fetchAll(PDO::FETCH_ASSOC);
        foreach ($proyRows as $pr) {
            $ts = !empty($pr['creado_en']) ? strtotime($pr['creado_en']) : time();
            $esPropio = ((int)$pr['autor_id'] === $userId && $userId > 0);
            $publicaciones[] = [
                'id'          => (int)$pr['id'],
                'tipo'        => 'proyectos',
                'tipo_label'  => 'Proyecto',
                'badge_class' => 'badge-tipo-proyecto',
                'badge_icon'  => '🚀',
                'titulo'      => $pr['titulo'],
                'descripcion' => $pr['descripcion'],
                'categoria'   => $pr['categoria'] ?: 'Innovación',
                'portada'     => !empty($pr['portada']) ? $pr['portada'] : 'assets/img/proyecto-cover-1.svg',
                'autor'       => !empty($pr['autor_nombre']) ? $pr['autor_nombre'] : 'Egresado Alzatista',
                'empresa'     => '',
                'modalidad'   => '',
                'ubicacion'   => '',
                'enlace'      => $pr['enlace'] ?? '',
                'fecha'       => date('d M Y', $ts),
                'timestamp'   => $ts,
                'url_detalle' => 'index.php?page=detalle-proyecto&id=' . (int)$pr['id'],
                'es_propio'   => $esPropio,
            ];
        }
    } catch (PDOException $e) {
        error_log('Error cargando proyectos en blog: ' . $e->getMessage());
    }
}

// Fallback de proyectos base
foreach ($proyectosBase as $pb) {
    $exists = false;
    foreach ($publicaciones as $p) {
        if ($p['tipo'] === 'proyectos' && $p['titulo'] === $pb['titulo']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $publicaciones[] = [
            'id'          => $pb['id'],
            'tipo'        => 'proyectos',
            'tipo_label'  => 'Proyecto',
            'badge_class' => 'badge-tipo-proyecto',
            'badge_icon'  => '🚀',
            'titulo'      => $pb['titulo'],
            'descripcion' => $pb['descripcion'],
            'categoria'   => $pb['categoria'],
            'portada'     => $pb['portada'],
            'autor'       => $pb['autor'],
            'empresa'     => '',
            'modalidad'   => '',
            'ubicacion'   => '',
            'enlace'      => $pb['enlace'],
            'fecha'       => $pb['fecha'],
            'timestamp'   => $pb['timestamp'],
            'url_detalle' => 'index.php?page=detalle-proyecto&id=' . $pb['id'],
            'es_propio'   => false,
        ];
    }
}

// C. OPORTUNIDADES, BECAS Y EMPLEO
$oportunidadesEstaticas = require __DIR__ . '/../config/oportunidades_data.php';

if (!empty($pdo)) {
    try {
        $stmtOp = $pdo->query("
            SELECT o.id, o.titulo, o.empresa, o.publicado_por, o.categoria, o.modalidad, o.ubicacion,
                   o.descripcion, o.enlace_postulacion, o.fecha_limite, o.creado_en
            FROM oportunidades o
            WHERE o.estado IN ('publicado', 'aprobado')
            ORDER BY o.creado_en DESC
        ");
        $opRows = $stmtOp ? $stmtOp->fetchAll(PDO::FETCH_ASSOC) : [];
        foreach ($opRows as $or) {
            $ts = !empty($or['creado_en']) ? strtotime($or['creado_en']) : time();
            $coverIcon = 'assets/img/oportunidad-empleo.svg';
            if (stripos($or['categoria'], 'beca') !== false) {
                $coverIcon = 'assets/img/oportunidad-beca.svg';
            } elseif (stripos($or['categoria'], 'pasant') !== false) {
                $coverIcon = 'assets/img/oportunidad-pasantia.svg';
            }

            $publicaciones[] = [
                'id'           => (int)$or['id'],
                'tipo'         => 'oportunidades',
                'tipo_label'   => 'Oportunidad',
                'badge_class'  => 'badge-tipo-oportunidad',
                'badge_icon'   => '💼',
                'titulo'       => $or['titulo'],
                'descripcion'  => $or['descripcion'],
                'categoria'    => $or['categoria'] ?: 'Empleo',
                'portada'      => $coverIcon,
                'autor'        => $or['publicado_por'] ?: 'Alianza Institucional',
                'empresa'      => $or['empresa'] ?: 'Entidad Aliada',
                'modalidad'    => $or['modalidad'] ?: 'Presencial',
                'ubicacion'    => $or['ubicacion'] ?: 'Medellín, Antioquia',
                'fecha_limite' => !empty($or['fecha_limite']) ? date('d M Y', strtotime($or['fecha_limite'])) : 'Convocatoria abierta',
                'enlace'       => $or['enlace_postulacion'] ?? '',
                'fecha'        => date('d M Y', $ts),
                'timestamp'    => $ts,
                'url_detalle'  => 'index.php?page=detalle-oportunidad&id=' . (int)$or['id'],
                'es_propio'    => false,
            ];
        }
    } catch (PDOException $e) {
        error_log('Error cargando oportunidades en blog: ' . $e->getMessage());
    }
}

// Fallback de oportunidades estáticas
foreach ($oportunidadesEstaticas as $itemOp) {
    $exists = false;
    foreach ($publicaciones as $p) {
        if ($p['tipo'] === 'oportunidades' && $p['titulo'] === $itemOp['titulo']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $coverIcon = 'assets/img/oportunidad-empleo.svg';
        if (stripos($itemOp['categoria'], 'beca') !== false) {
            $coverIcon = 'assets/img/oportunidad-beca.svg';
        } elseif (stripos($itemOp['categoria'], 'pasant') !== false) {
            $coverIcon = 'assets/img/oportunidad-pasantia.svg';
        }

        $publicaciones[] = [
            'id'           => $itemOp['id'],
            'tipo'         => 'oportunidades',
            'tipo_label'   => 'Oportunidad',
            'badge_class'  => 'badge-tipo-oportunidad',
            'badge_icon'   => '💼',
            'titulo'       => $itemOp['titulo'],
            'descripcion'  => $itemOp['descripcion'],
            'categoria'    => $itemOp['categoria'],
            'portada'      => $coverIcon,
            'autor'        => $itemOp['publicado_por'] ?? 'Convenio Institucional',
            'empresa'      => $itemOp['empresa'],
            'modalidad'    => $itemOp['modalidad'],
            'ubicacion'    => $itemOp['ubicacion'],
            'fecha_limite' => $itemOp['fecha_limite'] ?? 'Convocatoria abierta',
            'enlace'       => '',
            'fecha'        => $itemOp['fecha'] ?? '10 Oct 2026',
            'timestamp'    => strtotime($itemOp['fecha_limite'] ?? '2026-10-30'),
            'url_detalle'  => 'index.php?page=detalle-oportunidad&id=' . $itemOp['id'],
            'es_propio'    => false,
        ];
    }
}

// 3. Ordenar todas las publicaciones cronológicamente (más recientes primero)
usort($publicaciones, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

// 4. Calcular Métricas para Contadores del Blog
$totalNoticias = count(array_filter($publicaciones, fn($p) => $p['tipo'] === 'noticias'));
$totalProyectos = count(array_filter($publicaciones, fn($p) => $p['tipo'] === 'proyectos'));
$totalOportunidades = count(array_filter($publicaciones, fn($p) => $p['tipo'] === 'oportunidades'));
$totalGeneral = count($publicaciones);

// Categorías únicas recopiladas
$categoriasUnicas = array_values(array_unique(array_filter(array_map(fn($p) => $p['categoria'], $publicaciones))));

// 5. Cargar layout inicial (header HTML)
require_once __DIR__ . '/../includes/header.php';
?>

<?php if ($estaAutenticado): ?>
<!-- Layout Privado con Menú Lateral Sidebar y Topbar -->
<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="main-wrapper dashboard-main-area">
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>
        <main class="content-area dashboard-content blog-content-wrapper" id="mainContent" tabindex="-1">
<?php else: ?>
<!-- Layout Público en Contenedor Institucional -->
<main class="main-content blog-public-container" id="mainContent">
<?php endif; ?>

    <div class="blog-wrapper">

        <!-- 1. Encabezado de la Pantalla del Blog -->
        <div class="blog-section-header">
            <div class="blog-header-titles">
                <h1 class="blog-main-title">Blog &amp; Novedades</h1>
                <p class="blog-main-subtitle">Noticias institucionales, proyectos de egresados y convocatorias de la comunidad alzatista.</p>
            </div>

            <?php if ($estaAutenticado): ?>
                <div class="blog-create-wrapper" id="blogCreateWrapper">
                    <button type="button" class="btn-blog-action-primary" id="btnBlogCreate" aria-haspopup="true" aria-expanded="false">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Publicar en el blog</span>
                        <svg class="chevron-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="blog-create-dropdown" id="blogCreateDropdown" role="menu">
                        <a href="index.php?page=crear-proyecto" class="blog-dropdown-item" role="menuitem">
                            <div class="dropdown-item-icon" style="background: #E8F5E9; color: #059669;">🚀</div>
                            <div class="dropdown-item-text">
                                <strong>Compartir Proyecto</strong>
                                <small>Iniciativas y emprendimientos</small>
                            </div>
                        </a>
                        <a href="index.php?page=crear-noticia" class="blog-dropdown-item" role="menuitem">
                            <div class="dropdown-item-icon" style="background: #EEF2FF; color: #4F46E5;">📰</div>
                            <div class="dropdown-item-text">
                                <strong>Redactar Noticia</strong>
                                <small>Novedades y logros destacados</small>
                            </div>
                        </a>
                        <a href="index.php?page=crear-oportunidad" class="blog-dropdown-item" role="menuitem">
                            <div class="dropdown-item-icon" style="background: #FFFBEB; color: #D97706;">💼</div>
                            <div class="dropdown-item-text">
                                <strong>Publicar Oportunidad</strong>
                                <small>Empleos, becas y pasantías</small>
                            </div>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?page=login" class="btn-blog-action-primary">
                    <span>Iniciar sesión para publicar</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            <?php endif; ?>
        </div>

        <!-- 2. Contenedor Minimalista de Contenido (Tarjeta Blanca) -->
        <div class="blog-card-container">

            <!-- Barra de Herramientas: Filtros de Pestañas (Tabs) y Búsqueda -->
            <div class="blog-toolbar" role="toolbar" aria-label="Filtros del blog">
                <div class="blog-tabs-list" role="tablist">
                    <button type="button" class="blog-tab-btn <?= ($tipoFiltroInicial === 'todos') ? 'is-active' : '' ?>" data-filter="todos" role="tab" aria-selected="<?= ($tipoFiltroInicial === 'todos') ? 'true' : 'false' ?>">
                        <span>Todas</span>
                        <span class="blog-tab-badge"><?= $totalGeneral ?></span>
                    </button>
                    <button type="button" class="blog-tab-btn <?= ($tipoFiltroInicial === 'noticias') ? 'is-active' : '' ?>" data-filter="noticias" role="tab" aria-selected="<?= ($tipoFiltroInicial === 'noticias') ? 'true' : 'false' ?>">
                        <span>Noticias</span>
                        <span class="blog-tab-badge"><?= $totalNoticias ?></span>
                    </button>
                    <button type="button" class="blog-tab-btn <?= ($tipoFiltroInicial === 'proyectos') ? 'is-active' : '' ?>" data-filter="proyectos" role="tab" aria-selected="<?= ($tipoFiltroInicial === 'proyectos') ? 'true' : 'false' ?>">
                        <span>Proyectos</span>
                        <span class="blog-tab-badge"><?= $totalProyectos ?></span>
                    </button>
                    <button type="button" class="blog-tab-btn <?= ($tipoFiltroInicial === 'oportunidades') ? 'is-active' : '' ?>" data-filter="oportunidades" role="tab" aria-selected="<?= ($tipoFiltroInicial === 'oportunidades') ? 'true' : 'false' ?>">
                        <span>Oportunidades</span>
                        <span class="blog-tab-badge"><?= $totalOportunidades ?></span>
                    </button>
                </div>

                <div class="blog-filter-controls">
                    <div class="blog-search-wrap">
                        <svg class="blog-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" class="blog-search-field" id="blogSearchInput" placeholder="Buscar publicaciones..." aria-label="Buscar publicaciones">
                        <button type="button" class="blog-search-clear-btn" id="blogSearchClear" aria-label="Limpiar búsqueda">&times;</button>
                    </div>

                    <select class="blog-select-simple" id="blogCategorySelect" aria-label="Filtrar por categoría">
                        <option value="todas">Todas las categorías</option>
                        <?php foreach ($categoriasUnicas as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select class="blog-select-simple" id="blogSortSelect" aria-label="Ordenar publicaciones">
                        <option value="recientes">Más recientes</option>
                        <option value="antiguos">Más antiguos</option>
                    </select>
                </div>
            </div>

            <!-- Grilla de Publicaciones -->
            <div class="blog-grid" id="blogGrid">
                <?php foreach ($publicaciones as $post): ?>
                <a href="<?= htmlspecialchars($post['url_detalle']) ?>" class="blog-card"
                   data-tipo="<?= htmlspecialchars($post['tipo']) ?>"
                   data-categoria="<?= htmlspecialchars($post['categoria']) ?>"
                   data-titulo="<?= htmlspecialchars($post['titulo']) ?>"
                   data-descripcion="<?= htmlspecialchars($post['descripcion']) ?>"
                   data-autor="<?= htmlspecialchars($post['autor'] ?? '') ?>"
                   data-empresa="<?= htmlspecialchars($post['empresa'] ?? '') ?>"
                   data-timestamp="<?= (int)$post['timestamp'] ?>">
                    
                    <div class="blog-card-media">
                        <img src="<?= htmlspecialchars($post['portada']) ?>" alt="<?= htmlspecialchars($post['titulo']) ?>" loading="lazy">
                        <span class="blog-badge-tag <?= ($post['tipo'] === 'noticias' ? 'badge-noticia' : ($post['tipo'] === 'proyectos' ? 'badge-proyecto' : 'badge-oportunidad')) ?>">
                            <?= htmlspecialchars($post['tipo_label']) ?>
                        </span>
                    </div>

                    <div class="blog-card-content">
                        <div class="blog-card-meta">
                            <span class="category-text"><?= htmlspecialchars($post['categoria']) ?></span>
                            <span>•</span>
                            <time><?= htmlspecialchars($post['fecha']) ?></time>
                        </div>

                        <h3 class="blog-card-title"><?= htmlspecialchars($post['titulo']) ?></h3>

                        <p class="blog-card-excerpt"><?= htmlspecialchars($post['descripcion']) ?></p>

                        <?php if (!empty($post['empresa']) || !empty($post['modalidad']) || !empty($post['fecha_limite'])): ?>
                            <div class="blog-card-chips">
                                <?php if (!empty($post['empresa'])): ?>
                                    <span class="blog-chip"><?= htmlspecialchars($post['empresa']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($post['modalidad'])): ?>
                                    <span class="blog-chip"><?= htmlspecialchars($post['modalidad']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($post['fecha_limite'])): ?>
                                    <span class="blog-chip" style="color:#D32F2F;">Cierra: <?= htmlspecialchars($post['fecha_limite']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="blog-card-footer">
                            <span class="blog-author-label"><?= htmlspecialchars($post['autor'] ?: ($post['empresa'] ?? 'Comunidad')) ?></span>
                            <span class="blog-link-label">
                                Ver más
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Estado Vacío -->
            <div class="blog-empty-state" id="blogEmptyState" style="display: none;">
                <div class="blog-empty-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                </div>
                <h3 class="blog-empty-title">No encontramos publicaciones que coincidan</h3>
                <p class="blog-empty-text">
                    Prueba ajustando el término de búsqueda, seleccionando otra categoría o cambiando a la pestaña "Todas".
                </p>
                <button type="button" class="btn-blog-reset" id="btnResetFilters">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                    </svg>
                    <span>Restablecer filtros</span>
                </button>
            </div>

        </div>

    </div>

<?php if ($estaAutenticado): ?>
        </main>
    </div>
</div>
<?php else: ?>
</main>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
