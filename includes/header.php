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
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>
