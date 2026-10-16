<?php
/**
 * Layout: Encabezado HTML y carga de recursos globales
 * Incluye tipografía Inter, hojas de estilo modulares y componente de navegación
 */

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}

$pageTitle = $pageTitle ?? (APP_NAME . ' | IE Gilberto Alzate Avendaño');

if (!function_exists('assetCssUrl')) {
    function assetCssUrl($filename) {
        $fullPath = __DIR__ . '/../assets/css/' . $filename;
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        return 'assets/css/' . $filename . '?v=' . APP_VERSION . '&amp;t=' . $time;
    }
}

// Detección de entorno privado del dashboard para evitar FOUC y saltos de layout
$isDashboardView = !empty($seccion_activa) || (isset($currentPage) && in_array($currentPage, ['dashboard', 'perfil', 'editar-perfil']));
$activeSectionClass = !empty($seccion_activa) ? htmlspecialchars($seccion_activa) . '-page' : (!empty($currentPage) ? htmlspecialchars($currentPage) . '-page' : '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Favicon e Iconografía Institucional -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">
    
    <!-- Script Anti-FOUC para transiciones suaves si el sidebar está contraído -->
    <script>
        try {
            if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        } catch(e) {}
    </script>

    <!-- Tipografía Inter: Preconexión Google Fonts (fallback y soporte de red) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- 1. Configuración tipográfica modular (@font-face local) -->
    <link rel="stylesheet" href="<?php echo assetCssUrl('fonts.css'); ?>">

    <!-- 2. Hoja de estilos principal (Tokens, Reset, Layout Base y Secciones) -->
    <link rel="stylesheet" href="<?php echo assetCssUrl('main.css'); ?>">

    <!-- 3. Componentes modulares del Frontend -->
    <link rel="stylesheet" href="<?php echo assetCssUrl('header.css'); ?>">
    <link rel="stylesheet" href="<?php echo assetCssUrl('footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo assetCssUrl('loader.css'); ?>">

    <!-- 4. Estilos específicos por pantalla / módulo -->
    <?php if (isset($currentPage) && in_array($currentPage, ['login', 'auth', 'registro', 'recuperar', 'recuperar-password'])): ?>
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

    <!-- Navbar / Header unificado del portal público (solo se muestra cuando NO es vista de dashboard) -->
    <?php if (!$isDashboardView): ?>
        <?php include __DIR__ . '/../components/header.php'; ?>
    <?php endif; ?>
