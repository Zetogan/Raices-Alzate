<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Controlador de Entrada: Dashboard (Panel Privado)
 * Especificación Figma: 03 - Dashboard - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

// Iniciar sesión de forma segura si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Control estricto de acceso: Redirección inmediata si no hay sesión activa
if (empty($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// 2. Carga de configuración base
require_once __DIR__ . '/config/config.php';

// 3. Extracción dinámica de datos del usuario autenticado (sin nombres fijos en duro)
$user = $_SESSION['user'];
$userName = trim($user['nombre'] ?? $user['name'] ?? 'Usuario');
$userRole = trim($user['rol'] ?? $user['role'] ?? 'Egresado');
$userEmail = trim($user['email'] ?? '');

// Obtener inicial para el avatar de forma segura y compatible con UTF-8
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

// 4. Metadatos de la vista
$pageTitle = 'Dashboard | ' . APP_NAME;
$currentPage = 'dashboard';

// 5. Renderizado modular de la plantilla privada del Dashboard
require_once __DIR__ . '/pages/dashboard.php';
