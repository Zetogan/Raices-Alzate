<?php
/**
 * Layout: Encabezado HTML y carga de recursos globales
 * Incluye tipografía Inter (local y fallback), hojas de estilo modulares y navegación
 */

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}

$pageTitle = $pageTitle ?? (APP_NAME . ' | IE Gilberto Alzate Avendaño');

if (!function_exists('assetCssUrl')) {
    function assetCssUrl($filename) {
        $clean = ltrim($filename, '/');
        $fullPath = BASE_PATH . '/assets/css/' . basename($clean);
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        return 'assets/css/' . basename($clean) . '?v=' . APP_VERSION . '&t=' . $time;
    }
}

// Detección de entorno privado del dashboard para evitar FOUC y saltos de layout
$dashboardSections = [
    'dashboard', 'perfil', 'editar-perfil', 'egresados', 'proyectos', 'noticias',
    'oportunidades', 'mensajes', 'administracion', 'admin', 'pqr', 'pqrs',
    'crear-proyecto', 'editar-proyecto', 'detalle-proyecto',
    'crear-noticia', 'detalle-noticia',
    'crear-oportunidad', 'detalle-oportunidad', 'detalle-egresado',
    'blog', 'novedades'
];

$isDashboardView = (!empty($seccion_activa) && in_array($seccion_activa, $dashboardSections)) 
                || (isset($currentPage) && in_array($currentPage, $dashboardSections) && ($isPanel ?? false));

// Ajuste explícito si la variable $isDashboardView ya fue declarada previamente
if (isset($isDashboard) && is_bool($isDashboard)) {
    $isDashboardView = $isDashboard;
}

$activeSectionClass = !empty($seccion_activa) ? htmlspecialchars($seccion_activa) . '-page' : (!empty($currentPage) ? htmlspecialchars($currentPage) . '-page' : '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Base URL para resolución unificada de recursos locales y navegación -->
    <base href="<?php echo BASE_URL; ?>/">

    <!-- Favicon e Iconografía Institucional -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo-alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo-alzate.ico">
    
    <!-- Script Anti-FOUC para transiciones suaves si el sidebar está contraído -->
    <script>
        try {
            if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        } catch(e) {}
    </script>

    <!-- 1. Configuración tipográfica modular local inmediata (@font-face de assets/fonts/) -->
    <link rel="stylesheet" href="<?php echo assetCssUrl('fonts.css'); ?>">

    <!-- Fallback asíncrono no bloqueante de Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- 2. Hoja de estilos principal (Tokens, Reset, Layout Base y Secciones) -->
    <link rel="stylesheet" href="<?php echo assetCssUrl('main.css'); ?>">

    <!-- 3. Componentes modulares del Frontend -->
    <link rel="stylesheet" href="<?php echo assetCssUrl('header.css'); ?>">
    <link rel="stylesheet" href="<?php echo assetCssUrl('footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo assetCssUrl('loader.css'); ?>">

    <!-- 4. Estilos específicos por pantalla / módulo -->
    <?php if (isset($currentPage) && in_array($currentPage, ['login', 'auth', 'registro', 'recuperar', 'recuperar-password', 'reset-password', 'cambiar-password', 'reset'])): ?>
    <link rel="stylesheet" href="<?php echo assetCssUrl('auth.css'); ?>">
    <?php endif; ?>
    <?php if (isset($currentPage) && in_array($currentPage, ['proyectos', 'portal', 'egresados', 'noticias']) && !$isDashboardView): ?>
    <link rel="stylesheet" href="<?php echo assetCssUrl('portal.css'); ?>">
    <?php endif; ?>
    <?php if (!empty($extraCss)): ?>
        <?php foreach ((array)$extraCss as $cssFile): ?>
            <link rel="stylesheet" href="<?php echo assetCssUrl($cssFile); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body<?php echo $isDashboardView ? ' class="dashboard-body-page ' . $activeSectionClass . '"' : ''; ?>>
    <!-- Componente de precarga global (Page Loader con Skeleton y Spinner) -->
    <?php include __DIR__ . '/loader.php'; ?>

    <?php if ($isDashboardView): ?>
        <!-- Telón de fondo para menú en dispositivos móviles en vistas privadas -->
        <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
    <?php else: ?>
        <!-- Navbar / Header unificado del portal público -->
        <?php include __DIR__ . '/../components/header.php'; ?>
    <?php endif; ?>
