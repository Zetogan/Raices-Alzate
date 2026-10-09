<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Entrada directa: Pantalla de Recuperación de Contraseña
 * Versión: 0.1.1
 */

// Carga de configuración global
require_once __DIR__ . '/config/config.php';

// Variables de vista
$pageTitle = 'Recuperar contraseña | ' . APP_NAME;
$currentPage = 'recuperar';

// Renderizado modular de la interfaz
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/recuperar-password.php';
require_once __DIR__ . '/includes/footer.php';
