<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Entrada directa: Pantalla de Registro de Usuarios
 * Versión: 0.1.1
 */

// 1. Iniciar sesión de forma segura antes de emitir cualquier cabecera o contenido HTML
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// 2. Redirección automática desactivada para facilitar desarrollo y maquetación visual
// DESACTIVADO TEMPORALMENTE PARA DESARROLLO/MAQUETACIÓN:
// if (!empty($_SESSION['user']) && is_array($_SESSION['user'])) {
//     header('Location: dashboard.php');
//     exit;
// }

// 3. Carga de configuración global
require_once __DIR__ . '/config/config.php';

// 4. Variables de vista
$pageTitle = 'Crear una cuenta | ' . APP_NAME;
$currentPage = 'registro';

// Renderizado modular de la interfaz
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/registro.php';
require_once __DIR__ . '/includes/footer.php';
