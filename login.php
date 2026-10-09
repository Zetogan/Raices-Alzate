<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Entrada directa: Pantalla de Autenticación / Inicio de Sesión
 * Versión: 0.1.5
 */

// Carga de configuración global
require_once __DIR__ . '/config/config.php';

// Variables de vista
$pageTitle = 'Iniciar sesión | ' . APP_NAME;
$currentPage = 'login';

// Renderizado modular de la interfaz
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/login.php';
require_once __DIR__ . '/includes/footer.php';
