<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Detalle de Oportunidad
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Control de acceso para usuarios autenticados
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../config/config.php';

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'oportunidades';
$titulo_seccion = 'Detalle de oportunidad';
$titulo_pagina  = 'Detalle de oportunidad — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'oportunidades';
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
$extraCss = ['dashboard.css', 'oportunidad-detalle.css'];
$extraJs  = ['assets/js/oportunidades.js'];

// Catálogo canónico de oportunidades en PHP estático compartido
$oportunidadesCatalogo = require __DIR__ . '/../config/oportunidades_data.php';

// Identificación de la oportunidad solicitada (por defecto ID 1 si no se envía parámetro)
$oportunidadId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$oportunidadId || !isset($oportunidadesCatalogo[$oportunidadId])) {
    $oportunidadId = 1;
}

$oportunidad = $oportunidadesCatalogo[$oportunidadId];

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

<link rel="stylesheet" href="assets/css/oportunidades.css">
<link rel="stylesheet" href="assets/css/oportunidad-detalle.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido (con ítem Oportunidades activo) -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="content-area dashboard-content detalle-oportunidad-content-wrapper" id="mainContent" tabindex="-1">
            <div class="detalle-oportunidad-container">

                <!-- Enlace de retorno en la parte superior antes de la tarjeta -->
                <nav class="detalle-oportunidad-top-nav" aria-label="Navegación de retorno al catálogo de oportunidades">
                    <a href="index.php?page=oportunidades" class="btn-back-link" id="btnBackTop">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Volver a oportunidades</span>
                    </a>
                </nav>

                <!-- Tarjeta contenedora blanca (#FFFFFF, borde #E0E0E0, radio 16px, padding 40px) -->
                <article class="detalle-oportunidad-card" aria-label="Detalle de la oportunidad: <?php echo htmlspecialchars($oportunidad['titulo']); ?>">
                    
                    <!-- 1. Encabezado de la oportunidad -->
                    <header class="detalle-oportunidad-header">
                        <div class="detalle-oportunidad-badge-row">
                            <span class="oportunidad-categoria-badge"><?php echo htmlspecialchars($oportunidad['categoria']); ?></span>
                            <span class="oportunidad-modalidad-badge"><?php echo htmlspecialchars($oportunidad['modalidad']); ?></span>
                        </div>
                        <h1 class="detalle-oportunidad-titulo"><?php echo htmlspecialchars($oportunidad['titulo']); ?></h1>
                        <div class="detalle-oportunidad-entidad-row">
                            <span class="detalle-oportunidad-empresa">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align: -2px; margin-right: 4px;">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V7l8-4v18"></path>
                                    <path d="M19 21V11l-6-4"></path>
                                    <path d="M9 9v.01"></path>
                                    <path d="M9 12v.01"></path>
                                    <path d="M9 15v.01"></path>
                                    <path d="M9 18v.01"></path>
                                </svg>
                                <?php echo htmlspecialchars($oportunidad['empresa']); ?>
                            </span>
                            <time class="detalle-oportunidad-fecha" datetime="<?php echo htmlspecialchars($oportunidad['fecha_publicacion']); ?>">
                                Publicado el <?php echo htmlspecialchars($oportunidad['fecha_publicacion']); ?>
                            </time>
                        </div>
                    </header>

                    <!-- 2. Portada/Banner panorámico con ilustración oficial -->
                    <div class="detalle-oportunidad-banner" role="img" aria-label="Banner de la oportunidad" style="overflow: hidden; border-radius: 10px; max-height: 340px; background-color: #ECEFF1;">
                        <?php
                            $cat = mb_strtolower($oportunidad['categoria'] ?? '');
                            if (strpos($cat, 'beca') !== false) {
                                $bannerImg = 'assets/img/oportunidad-beca.svg';
                            } elseif (strpos($cat, 'pasant') !== false) {
                                $bannerImg = 'assets/img/oportunidad-pasantia.svg';
                            } else {
                                $bannerImg = 'assets/img/oportunidad-empleo.svg';
                            }
                            $bannerFinal = !empty($oportunidad['portada']) ? $oportunidad['portada'] : $bannerImg;
                        ?>
                        <img src="<?php echo htmlspecialchars($bannerFinal); ?>" alt="<?php echo htmlspecialchars($oportunidad['titulo']); ?>" class="detalle-oportunidad-banner-img" style="width: 100%; height: 100%; max-height: 340px; object-fit: cover; display: block; border-radius: 10px;">
                    </div>

                    <!-- 3. Sección de contenido: "Sobre esta oportunidad" + Párrafo -->
                    <section class="detalle-oportunidad-body">
                        <h2 class="detalle-oportunidad-subtitulo">Sobre esta oportunidad</h2>
                        <p class="detalle-oportunidad-parrafo"><?php echo nl2br(htmlspecialchars($oportunidad['descripcion'])); ?></p>
                        
                        <?php if (!empty($oportunidad['requisitos'])): ?>
                        <div class="detalle-oportunidad-requisitos-box">
                            <h3 class="detalle-oportunidad-requisitos-title">Requisitos y perfil deseado</h3>
                            <ul class="detalle-oportunidad-requisitos-list">
                                <?php foreach ((array)$oportunidad['requisitos'] as $req): ?>
                                    <li><?php echo htmlspecialchars($req); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($oportunidad['beneficios'])): ?>
                        <div style="margin-top: 12px;">
                            <h3 class="detalle-oportunidad-subtitulo" style="font-size: 16px; margin-bottom: 8px;">Beneficios ofrecidos</h3>
                            <p class="detalle-oportunidad-parrafo"><?php echo nl2br(htmlspecialchars($oportunidad['beneficios'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </section>

                    <!-- 4. Ficha de datos (Grid de 3 columnas simétricas) -->
                    <div class="detalle-oportunidad-meta-grid" role="region" aria-label="Datos de la convocatoria">
                        <div class="detalle-oportunidad-meta-item">
                            <span class="detalle-oportunidad-meta-label">Publicado por</span>
                            <span class="detalle-oportunidad-meta-valor"><?php echo htmlspecialchars($oportunidad['publicado_por']); ?></span>
                        </div>
                        <div class="detalle-oportunidad-meta-item">
                            <span class="detalle-oportunidad-meta-label">Modalidad / Ubicación</span>
                            <span class="detalle-oportunidad-meta-valor"><?php echo htmlspecialchars($oportunidad['ubicacion']); ?></span>
                        </div>
                        <div class="detalle-oportunidad-meta-item">
                            <span class="detalle-oportunidad-meta-label">Fecha límite de postulación</span>
                            <span class="detalle-oportunidad-meta-valor" style="color: #D32F2F;"><?php echo htmlspecialchars($oportunidad['fecha_limite']); ?></span>
                        </div>
                    </div>

                    <!-- 5. Botón inferior: "Volver a oportunidades" (Outline azul #1565C0, hover azul tenue transparente, NUNCA rojo) -->
                    <div class="detalle-oportunidad-actions">
                        <a href="index.php?page=oportunidades" class="btn-volver-oportunidades" id="btnVolverOportunidades">
                            <svg class="btn-volver-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            <span>Volver a oportunidades</span>
                        </a>
                    </div>

                </article>

            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
