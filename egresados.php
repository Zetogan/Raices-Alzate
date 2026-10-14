<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Controlador de Entrada: Pantalla de Egresados (Panel Privado)
 * Especificación Figma: 05 - Egresados - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

ob_start();

if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validación de sesión activa (con valores por defecto para previsualización en desarrollo)
if (empty($_SESSION['user']) && empty($_SESSION['usuario'])) {
    $_SESSION['user'] = [
        'nombre' => 'Santiago Emitola',
        'rol'    => 'Egresado',
        'email'  => 'santiago@ejemplo.com'
    ];
}

// Extracción dinámica y segura de los datos de sesión del usuario
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userEmail = trim($user['email'] ?? 'santiago@ejemplo.com');

// Obtener inicial para el avatar de forma segura y compatible con UTF-8
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

$pageTitle = 'Egresados | ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');
$topbarTitle = 'Egresados';
$currentPage = 'egresados';

// Carga de la vista privada del directorio de egresados
require_once __DIR__ . '/pages/egresados.php';

ob_end_flush();
