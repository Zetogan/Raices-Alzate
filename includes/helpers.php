<?php
/**
 * Raíces Alzate - Utilidades y Helpers Reutilizables
 * Centraliza funciones comunes para evitar código duplicado
 */

declare(strict_types=1);

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}

/**
 * Escapado seguro contra XSS para salida en HTML
 */
if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Cálculo de tiempo relativo en español (hace x minutos, hace x horas, etc.)
 * Evita la duplicación identificada en dashboard, mensajes, noticias y proyectos.
 */
if (!function_exists('formatearTiempoRelativo')) {
    function formatearTiempoRelativo($datetime): string {
        if (empty($datetime)) {
            return 'Reciente';
        }
        $timestamp = is_numeric($datetime) ? (int)$datetime : strtotime((string)$datetime);
        if (!$timestamp) {
            return 'Reciente';
        }
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'Hace unos momentos';
        } elseif ($diff < 3600) {
            $mins = max(1, (int)floor($diff / 60));
            return "Hace {$mins} " . ($mins === 1 ? 'minuto' : 'minutos');
        } elseif ($diff < 86400) {
            $hours = max(1, (int)floor($diff / 3600));
            return "Hace {$hours} " . ($hours === 1 ? 'hora' : 'horas');
        } elseif ($diff < 172800) {
            return 'Ayer';
        } elseif ($diff < 604800) {
            $days = max(1, (int)floor($diff / 86400));
            return "Hace {$days} " . ($days === 1 ? 'día' : 'días');
        } else {
            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            $mesIndex = (int)date('n', $timestamp) - 1;
            $mes = $meses[$mesIndex] ?? date('M', $timestamp);
            return date('j', $timestamp) . ' ' . $mes . ' ' . date('Y', $timestamp);
        }
    }
}

/**
 * Redirección segura dentro de la plataforma
 */
if (!function_exists('redireccionar')) {
    function redireccionar(string $destino): void {
        header('Location: ' . url($destino));
        exit;
    }
}
