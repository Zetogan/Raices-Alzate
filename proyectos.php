<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Controlador: Pantalla de Proyectos
 * Versión: 0.1.1
 */

// Asegurar inicio de sesión sin generar cabeceras prematuras
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/config/config.php';

// Si se solicita explícitamente el panel privado desde el sidebar con sesión activa
if ((isset($_GET['view']) && $_GET['view'] === 'panel') && (!empty($_SESSION['user']) || !empty($_SESSION['usuario']))) {
    require_once __DIR__ . '/includes/proyectos-privado.php';
    exit;
}

// Definir variables para activar la pestaña en el header público
$currentPage = 'proyectos';
$pageTitle = ucfirst($currentPage) . ' | Raíces Alzate';

// Cargar la vista pública de 2 columnas previa
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/' . basename(__FILE__);
require_once __DIR__ . '/includes/footer.php';
