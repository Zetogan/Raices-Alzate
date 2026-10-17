<?php
/**
 * Raíces Alzate - Configuración Global
 * Institución Educativa Gilberto Alzate Avendaño
 */

// Zona horaria oficial del proyecto: Colombia (UTC-5)
date_default_timezone_set('America/Bogota');

// Parámetros de conexión a base de datos MySQL / MariaDB
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', 3310);
if (!defined('DB_NAME')) define('DB_NAME', 'raices_alzate');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

// Metadatos de la aplicación
if (!defined('APP_NAME')) define('APP_NAME', 'Raíces Alzate');
if (!defined('APP_VERSION')) define('APP_VERSION', '0.2.1');

// Rutas base absolutas y relativas
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (!defined('BASE_URL')) {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (strpos($script, '/pages/') !== false || strpos($script, '/api/') !== false || strpos($script, '/auth/') !== false || strpos($script, '/database/') !== false) {
        $base = dirname(dirname($script));
    } else {
        $base = dirname($script);
    }
    $base = rtrim(str_replace('\\', '/', $base), '/');
    define('BASE_URL', $base);
}

// Helpers globales de resolución de URLs
if (!function_exists('url')) {
    function url(string $path = ''): string {
        $trimmed = ltrim($path, '/');
        return BASE_URL . ($trimmed !== '' ? '/' . $trimmed : '');
    }
}

if (!function_exists('assetUrl')) {
    function assetUrl(string $path): string {
        $fullPath = BASE_PATH . '/' . ltrim($path, '/');
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        return url($path) . '?v=' . APP_VERSION . '&t=' . $time;
    }
}