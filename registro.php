<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Entrada directa: Pantalla de Registro de Usuarios
 * Versión: 0.1.1
 */

// Carga de configuración global
require_once __DIR__ . '/config/config.php';

// Variables de vista
$pageTitle = 'Crear una cuenta | ' . APP_NAME;
$currentPage = 'registro';

// Renderizado modular de la interfaz
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/registro.php';
require_once __DIR__ . '/includes/footer.php';
