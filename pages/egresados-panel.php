<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Panel Privado de Directorio de Egresados
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$seccion_activa = 'egresados';
$titulo_seccion = 'Egresados';
$titulo_pagina = 'Directorio de Egresados — ' . APP_NAME;

require_once __DIR__ . '/../includes/egresados-privado.php';
