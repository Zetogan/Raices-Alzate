<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Controlador: Pantalla de Egresados
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/config/config.php';

$currentPage = 'egresados';
$pageTitle = 'Egresados | ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');

// Si NO hay sesión activa o es visita pública, cargar la vista pública institucional
$hasActiveSession = (!empty($_SESSION['user']) || !empty($_SESSION['usuario']));
$isPanelView = (isset($_GET['view']) && $_GET['view'] === 'panel')
    || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'detalle-egresado') !== false);

if (!$hasActiveSession || !$isPanelView) {
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/pages/egresados-public.php';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Arreglo estático institucional de egresados y estado de conexión
$egresados = [
    [
        'id' => 1,
        'nombre' => 'Valentina Ríos Gómez',
        'inicial' => 'V',
        'carrera' => 'Técnico en Sistemas e Informática',
        'cargo' => 'Ingeniera de Software en Mercado Libre',
        'ubicacion' => 'Medellín, Colombia',
        'promocion' => '2021',
        'habilidades' => ['PHP', 'React', 'MySQL'],
        'conectado' => true // Conectada -> Enviar mensaje
    ],
    [
        'id' => 2,
        'nombre' => 'Carlos Andrés Morales',
        'inicial' => 'C',
        'carrera' => 'Técnico en Gestión Comercial y Contable',
        'cargo' => 'Coordinador Financiero en Bancolombia',
        'ubicacion' => 'Medellín, Colombia',
        'promocion' => '2020',
        'habilidades' => ['Finanzas', 'Excel Avanzado', 'Liderazgo'],
        'conectado' => false // No conectado -> Botón Conectar
    ],
    [
        'id' => 3,
        'nombre' => 'Camila Zuluaga Henao',
        'inicial' => 'C',
        'carrera' => 'Bachillerato Académico',
        'cargo' => 'Diseñadora de Producto UI/UX en Globant',
        'ubicacion' => 'Medellín, Colombia',
        'promocion' => '2021',
        'habilidades' => ['Figma', 'Design Systems', 'UX Research'],
        'conectado' => true // Conectada -> Enviar mensaje
    ],
    [
        'id' => 4,
        'nombre' => 'Mateo Henao Castro',
        'inicial' => 'M',
        'carrera' => 'Técnico en Sistemas e Informática',
        'cargo' => 'Líder Técnico Cloud en Ruta N',
        'ubicacion' => 'Medellín, Colombia',
        'promocion' => '2019',
        'habilidades' => ['AWS', 'DevOps', 'Docker'],
        'conectado' => true // Conectado -> Enviar mensaje
    ],
    [
        'id' => 5,
        'nombre' => 'Laura Restrepo Silva',
        'inicial' => 'L',
        'carrera' => 'Bachillerato Académico con Honores',
        'cargo' => 'Médica Interna - Univ. de Antioquia',
        'ubicacion' => 'Medellín, Colombia',
        'promocion' => '2022',
        'habilidades' => ['Investigación', 'Salud Pública'],
        'conectado' => false // No conectado -> Botón Conectar
    ],
    [
        'id' => 6,
        'nombre' => 'Alejandro Quintero',
        'inicial' => 'A',
        'carrera' => 'Técnico en Gestión Comercial',
        'cargo' => 'Especialista Marketing en Cementos Argos',
        'ubicacion' => 'Bogotá, Colombia',
        'promocion' => '2021',
        'habilidades' => ['Marketing B2B', 'Ventas'],
        'conectado' => true // Conectado -> Enviar mensaje
    ]
];

// Si hay sesión autenticada o se solicita el panel privado:
$seccion_activa = 'egresados';
$titulo_seccion = 'Egresados';
$titulo_pagina = 'Directorio de Egresados — ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');
require_once __DIR__ . '/pages/egresados-panel.php';

