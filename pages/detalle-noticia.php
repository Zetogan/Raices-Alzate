<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Detalle de Noticia (Figma: Detalle de noticia - Desktop)
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si no hay sesión activa (tanto por $_SESSION['user'] como $_SESSION['usuario'])
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: index.php?page=login');
    exit;
}

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'noticias';
$titulo_seccion = 'Detalle de noticia';
$titulo_pagina  = 'Detalle de noticia — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'noticias';
$pageTitle   = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName    = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Usuario');
$userRole    = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

// Hojas de estilo y scripts modulares
$extraCss = ['dashboard.css', 'noticia-detalle.css'];
$extraJs  = ['assets/js/noticias.js'];

// Catálogo canónico de noticias en PHP estático
$noticiasCatalogo = [
    1 => [
        'id'            => 1,
        'titulo'        => 'Nueva oportunidad para egresados',
        'fecha'         => '15 de septiembre de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Comunidad',
        'descripcion'   => 'Conoce las nuevas oportunidades disponibles para nuestra comunidad de egresados. Mantente informado sobre convocatorias, actividades y beneficios que pueden contribuir a tu desarrollo académico y profesional.',
        'portada'       => ''
    ],
    2 => [
        'id'            => 2,
        'titulo'        => 'Encuentro de egresados',
        'fecha'         => '10 de septiembre de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Eventos',
        'descripcion'   => 'Participa en nuestro próximo encuentro y vuelve a conectar con la institución. Tendremos espacios de integración, conferencias y actividades para revivir momentos inolvidables y afianzar la red de egresados alzatistas.',
        'portada'       => ''
    ],
    3 => [
        'id'            => 3,
        'titulo'        => 'Historias que inspiran',
        'fecha'         => '5 de septiembre de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Destacados',
        'descripcion'   => 'Conoce las experiencias y logros de nuestros egresados más destacados que continúan dejando en alto el nombre de nuestra institución a través de su liderazgo, proyectos comunitarios y excelencia profesional.',
        'portada'       => ''
    ],
    4 => [
        'id'            => 4,
        'titulo'        => 'Nuevos proyectos de la comunidad',
        'fecha'         => '28 de agosto de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Proyectos',
        'descripcion'   => 'Descubre los proyectos que están desarrollando nuestros egresados en áreas de tecnología, emprendimiento social y medio ambiente. Una muestra del talento y compromiso de nuestra comunidad.',
        'portada'       => ''
    ],
    5 => [
        'id'            => 5,
        'titulo'        => 'Actividades institucionales',
        'fecha'         => '20 de agosto de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Institucional',
        'descripcion'   => 'Consulta las próximas actividades, ceremonias y jornadas organizadas por la institución. Te invitamos a participar y seguir siendo parte activa de los eventos institucionales.',
        'portada'       => ''
    ],
    6 => [
        'id'            => 6,
        'titulo'        => 'Comunidad Raíces Alzate',
        'fecha'         => '12 de agosto de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Comunidad',
        'descripcion'   => 'Seguimos fortaleciendo los vínculos entre egresados de todas las promociones. Súmate a las mesas de trabajo y aporta tus ideas para continuar construyendo comunidad.',
        'portada'       => ''
    ],
    7 => [
        'id'            => 7,
        'titulo'        => 'Jornada de actualización profesional',
        'fecha'         => '5 de agosto de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Académico',
        'descripcion'   => 'Talleres y conferencias para fortalecer las competencias de nuestros egresados en el sector productivo y tecnológico.',
        'portada'       => ''
    ],
    8 => [
        'id'            => 8,
        'titulo'        => 'Convocatoria para proyectos de innovación',
        'fecha'         => '28 de julio de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Innovación',
        'descripcion'   => 'Postula tus iniciativas para recibir acompañamiento y visibilidad en los canales institucionales de Raíces Alzate.',
        'portada'       => ''
    ],
    9 => [
        'id'            => 9,
        'titulo'        => 'Reconocimiento a egresados destacados',
        'fecha'         => '18 de julio de 2026',
        'publicado_por' => 'Administración',
        'categoria'     => 'Comunidad',
        'descripcion'   => 'Celebramos las trayectorias de exalumnos que generan impacto positivo en la región y el país.',
        'portada'       => ''
    ]
];

// Identificación de la noticia solicitada (por defecto ID 1 si no se envía parámetro)
$noticiaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$noticiaId || !isset($noticiasCatalogo[$noticiaId])) {
    $noticiaId = 1;
}

$noticia = $noticiasCatalogo[$noticiaId];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/../includes/header.php';
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
<link rel="stylesheet" href="assets/css/noticia-detalle.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="content-area dashboard-content detalle-noticia-content-wrapper" id="mainContent" tabindex="-1">
            <div class="detalle-noticia-container">

                <!-- Enlace de retorno en la parte superior antes de la tarjeta -->
                <nav class="detalle-noticia-top-nav" aria-label="Navegación de retorno al listado de noticias">
                    <a href="index.php?page=noticias&view=panel" class="btn-back-link" id="btnBackTop">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Volver a noticias</span>
                    </a>
                </nav>

                <!-- Contenedor blanco unificado -->
                <article class="news-card-container detalle-noticia-card" aria-label="Detalle de noticia: <?php echo htmlspecialchars($noticia['titulo']); ?>">
                    
                    <!-- 1. Encabezado de la noticia -->
                    <header class="section-header detalle-noticia-header">
                        <h1 class="section-title detalle-noticia-titulo"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
                        <time class="detalle-noticia-fecha" datetime="2026-09-15"><?php echo htmlspecialchars($noticia['fecha']); ?></time>
                    </header>

                    <!-- 2. Portada/Banner panorámico (radio 10px, altura 280px-340px) -->
                    <div class="detalle-noticia-banner" role="img" aria-label="Portada de la noticia">
                        <?php 
                            $defaultNoticiaCovers = ['assets/img/noticia-1.svg', 'assets/img/noticia-2.svg', 'assets/img/noticia-3.svg'];
                            $noticiaCoverToShow = !empty($noticia['portada']) ? $noticia['portada'] : $defaultNoticiaCovers[((int)($noticia['id'] ?? 1)) % count($defaultNoticiaCovers)];
                        ?>
                        <img 
                            src="<?php echo htmlspecialchars($noticiaCoverToShow); ?>" 
                            alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" 
                            class="detalle-noticia-banner-img"
                            style="width: 100%; height: 100%; max-height: 380px; object-fit: cover; display: block; border-radius: 10px;"
                        >
                    </div>

                    <!-- 3. Sección de contenido: "Sobre esta noticia" + Párrafo -->
                    <section class="detalle-noticia-body">
                        <h2 class="detalle-noticia-subtitulo">Sobre esta noticia</h2>
                        <p class="detalle-noticia-parrafo"><?php echo nl2br(htmlspecialchars($noticia['descripcion'])); ?></p>
                    </section>

                    <!-- 4. Ficha de datos (Grid de 3 columnas simétricas) -->
                    <div class="detalle-noticia-meta-grid" role="region" aria-label="Datos de la noticia">
                        <div class="detalle-noticia-meta-item">
                            <span class="detalle-noticia-meta-label">Publicado por</span>
                            <span class="detalle-noticia-meta-valor"><?php echo htmlspecialchars($noticia['publicado_por']); ?></span>
                        </div>
                        <div class="detalle-noticia-meta-item">
                            <span class="detalle-noticia-meta-label">Categoría</span>
                            <span class="detalle-noticia-meta-valor"><?php echo htmlspecialchars($noticia['categoria']); ?></span>
                        </div>
                        <div class="detalle-noticia-meta-item">
                            <span class="detalle-noticia-meta-label">Fecha</span>
                            <span class="detalle-noticia-meta-valor"><?php echo htmlspecialchars($noticia['fecha']); ?></span>
                        </div>
                    </div>

                    <!-- 5. Botón inferior: "Volver a noticias" (Outline azul #1565C0, hover azul tenue transparente, NUNCA rojo) -->
                    <div class="detalle-noticia-actions">
                        <a href="index.php?page=noticias&view=panel" class="btn-volver-noticias" id="btnVolverNoticias">
                            <svg class="btn-volver-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            <span>Volver a noticias</span>
                        </a>
                    </div>

                </article>

            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
