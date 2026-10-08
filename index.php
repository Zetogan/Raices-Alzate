<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Archivo de entrada principal (Front Controller)
 * Versión: 0.1.1
 */

// Carga de configuración global (sin conexión a base de datos en esta etapa)
require_once __DIR__ . '/config/config.php';

// Definición de variables de vista
$pageTitle = APP_NAME . ' | IE Gilberto Alzate Avendaño';
$currentPage = 'inicio';

// Renderizado modular de la interfaz
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/inicio.php';
require_once __DIR__ . '/includes/footer.php';
