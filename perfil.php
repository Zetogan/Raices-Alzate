<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Controlador de Entrada: Pantalla de Perfil del Egresado (Panel Privado)
 * Especificación Figma: 04 - Perfil - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

ob_start();

if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si no hay sesión activa (tanto por $_SESSION['user'] como $_SESSION['usuario'])
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
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

$pageTitle = 'Mi perfil | ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');
$topbarTitle = 'Mi perfil';
$currentPage = 'perfil';

// Carga de la vista
require_once __DIR__ . '/pages/perfil.php';

ob_end_flush();
