<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Pantalla de Proyectos de Egresados
 * Especificación Figma: 06 - Proyectos - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

if (!function_exists('proyectosAssetCssUrl')) {
    function proyectosAssetCssUrl($filename) {
        $fullPath = __DIR__ . '/../assets/css/' . $filename;
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        $version = defined('APP_VERSION') ? APP_VERSION : '0.1.5';
        return 'assets/css/' . $filename . '?v=' . $version . '&amp;t=' . $time;
    }
}

// Variables de sesión y estado
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

$topbarTitle = 'Proyectos';
$currentPage = 'proyectos';
$seccion_activa = 'proyectos';

$proyectosList = $proyectos ?? [];
$hayError = !empty($dbError);
$hayMas = !empty($hayMasProyectos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ('Proyectos | ' . APP_NAME), ENT_QUOTES, 'UTF-8'); ?></title>

    <!-- Favicon e Iconografía Institucional Oficial -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">

    <!-- Tipografía Inter (Preconexión y Google Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Hojas de estilo modulares -->
    <link rel="stylesheet" href="<?php echo proyectosAssetCssUrl('fonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo proyectosAssetCssUrl('main.css'); ?>">
    <link rel="stylesheet" href="<?php echo proyectosAssetCssUrl('dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php echo proyectosAssetCssUrl('proyectos.css'); ?>">
    <link rel="stylesheet" href="<?php echo proyectosAssetCssUrl('loader.css'); ?>">

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
<body class="dashboard-body-page proyectos-body-page">
    <!-- Componente de precarga global (Page Loader con Spinner y Logotipo) -->
    <?php require_once __DIR__ . '/../includes/loader.php'; ?>

    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="dashboard-layout" id="dashboardLayout">
        <!-- 1. Barra Lateral de Navegación Privada (Sidebar con ítem Proyectos activo) -->
        <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- 2. Área Principal de Contenido -->
        <div class="dashboard-main-area">
            <!-- Barra Superior (Topbar: Título "Proyectos", datos de usuario "Santiago", rol "Egresado", inicial "S") -->
            <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

            <!-- Contenido Central del Módulo de Proyectos -->
            <main class="dashboard-content proyectos-content-wrapper" id="proyectosMainContent" tabindex="-1">
                
                <!-- Encabezado de contenido -->
                <header class="proyectos-header-block">
                    <h1 class="proyectos-header-title">Proyectos de egresados</h1>
                    <p class="proyectos-header-subtitle">Conoce y comparte proyectos realizados por la comunidad de egresados.</p>
                </header>

                <!-- Contenedor Principal: Tarjeta blanca (#FFFFFF) con bordes redondeados (16px) y padding generoso -->
                <section class="proyectos-card-container" aria-label="Catálogo de proyectos de egresados alzatistas">
                    
                    <?php if ($hayError): ?>
                        <!-- Estado de Error de Conexión Amigable -->
                        <div class="proyectos-error-state" role="alert">
                            <div class="error-icon-wrap" aria-hidden="true">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <h2 class="error-state-title">No fue posible conectar con el servidor</h2>
                            <p class="error-state-desc"><?php echo htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>

                    <?php elseif (empty($proyectosList)): ?>
                        <!-- Estado Vacío (Sin Proyectos Registrados) -->
                        <div class="proyectos-empty-state" role="status">
                            <div class="empty-icon-wrap" aria-hidden="true">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <h2 class="empty-state-title">Aún no hay proyectos registrados</h2>
                            <p class="empty-state-desc">Los proyectos e iniciativas compartidos por los egresados alzatistas aparecerán reflejados aquí.</p>
                        </div>

                    <?php else: ?>
                        <!-- Cuadrícula de proyectos: Grid simétrico de 3 columnas en Desktop -->
                        <div class="proyectos-grid" id="proyectosGrid">
                            <?php foreach ($proyectosList as $item): ?>
                                <?php 
                                    $id = (int) $item['id'];
                                    $titulo = htmlspecialchars($item['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $descripcion = htmlspecialchars($item['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $autor = htmlspecialchars($item['autor_nombre'] ?? 'Egresado anónimo', ENT_QUOTES, 'UTF-8');
                                    $portada = trim($item['portada'] ?? '');
                                ?>
                                <article class="proyecto-card" id="proyecto-<?php echo $id; ?>">
                                    <!-- 1. Contenedor de portada rectangular (proporción 16:9) con bordes redondeados -->
                                    <div class="proyecto-cover-wrapper">
                                        <?php if (!empty($portada)): ?>
                                            <img 
                                                src="<?php echo htmlspecialchars($portada, ENT_QUOTES, 'UTF-8'); ?>" 
                                                alt="Portada del proyecto <?php echo $titulo; ?>" 
                                                class="proyecto-cover-img"
                                                loading="lazy"
                                            >
                                        <?php else: ?>
                                            <!-- Placeholder SVG neutro (#ECECEC / #BDBDBD) en proporción 16:9 -->
                                            <svg class="proyecto-placeholder-svg" viewBox="0 0 640 360" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Portada no disponible">
                                                <rect width="640" height="360" fill="#ECECEC" rx="8" />
                                                <g stroke="#BDBDBD" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="250" y="115" width="140" height="95" rx="6" />
                                                    <circle cx="285" cy="145" r="12" />
                                                    <path d="M253 192 L290 158 L330 188 L350 172 L388 194" />
                                                    <line x1="265" y1="230" x2="375" y2="230" stroke-width="2.5" />
                                                    <line x1="285" y1="242" x2="355" y2="242" stroke-width="2" />
                                                </g>
                                            </svg>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Contenido textual de la tarjeta -->
                                    <div class="proyecto-info">
                                        <!-- 2. Título del proyecto en negrita (#212121), enlazado a detalle-proyecto.php?id={id} -->
                                        <h2 class="proyecto-title">
                                            <a href="detalle-proyecto.php?id=<?php echo $id; ?>" class="proyecto-title-link">
                                                <?php echo $titulo; ?>
                                            </a>
                                        </h2>

                                        <!-- 3. Descripción breve (máximo 2 líneas con elipsis CSS, #757575) -->
                                        <p class="proyecto-description" title="<?php echo $descripcion; ?>">
                                            <?php echo $descripcion; ?>
                                        </p>

                                        <!-- 4. Autoría al pie con formato "Por [Nombre del autor]" (12px, #757575) -->
                                        <div class="proyecto-author">
                                            Por <span class="proyecto-author-name"><?php echo $autor; ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <!-- Acción inferior: Botón centrado "Ver más proyectos" con estilo outline -->
                        <div class="proyectos-actions-bottom" id="proyectosStatusContainer">
                            <?php if ($hayMas): ?>
                                <button 
                                    type="button" 
                                    class="btn-ver-mas-proyectos" 
                                    id="btnVerMasProyectos" 
                                    data-offset="<?php echo count($proyectosList); ?>"
                                    aria-label="Cargar más proyectos de la comunidad alzatista"
                                >
                                    Ver más proyectos
                                </button>
                            <?php else: ?>
                                <p class="proyectos-fin-mensaje">Has visualizado todos los proyectos disponibles.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </section>

            </main>
        </div>
    </div>

    <!-- Scripts del Módulo de Proyectos -->
    <script src="assets/js/proyectos.js?v=<?php echo APP_VERSION; ?>"></script>
    <!-- Script global del Page Loader (desvanecimiento y desmontaje) -->
    <script src="assets/js/loader.js?v=<?php echo APP_VERSION; ?>"></script>
</body>
</html>
