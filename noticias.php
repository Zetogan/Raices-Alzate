<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Entrada directa: Pantalla de Noticias
 * Versión: 0.1.1
 */

// Carga de configuración base
require_once __DIR__ . '/config/config.php';

// Metadatos de vista
$pageTitle = 'Noticias | ' . APP_NAME;
$currentPage = 'noticias';

// Renderizado modular de la plantilla
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/noticias.php';
require_once __DIR__ . '/includes/footer.php';
