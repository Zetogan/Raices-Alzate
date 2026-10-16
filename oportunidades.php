<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Oportunidades (Panel Privado)
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Control de acceso para usuarios autenticados
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/config.php';

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'oportunidades';
$titulo_seccion = 'Oportunidades';
$titulo_pagina = 'Oportunidades — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'oportunidades';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

// Hojas de estilo y scripts adicionales gestionados por los includes globales
$extraCss = ['dashboard.css', 'oportunidades.css'];
$extraJs = ['assets/js/oportunidades.js'];

// Catálogo canónico estático de oportunidades (CERO BASES DE DATOS / CERO SQL)
$oportunidades = [
    [
        'id'          => 1,
        'titulo'      => 'Convocatoria laboral: Desarrollador Web Junior',
        'empresa'     => 'Tech Innovate Medellín',
        'categoria'   => 'Empleo',
        'modalidad'   => 'Híbrido',
        'ubicacion'   => 'Medellín, Antioquia',
        'fecha'       => '12 de octubre de 2026',
        'descripcion' => 'Empresa del sector tecnológico busca egresados alzatistas con formación en desarrollo de software para integrarse al equipo de proyectos frontend y backend.'
    ],
    [
        'id'          => 2,
        'titulo'      => 'Beca de pregrado: Alianza Universitaria 2027',
        'empresa'     => 'Institución Universitaria Pascual Bravo',
        'categoria'   => 'Beca',
        'modalidad'   => 'Presencial',
        'ubicacion'   => 'Medellín',
        'fecha'       => '8 de octubre de 2026',
        'descripcion' => 'Convocatoria de becas completas y parciales para egresados alzatistas con destacado rendimiento que deseen cursar carreras de ingeniería y tecnología.'
    ],
    [
        'id'          => 3,
        'titulo'      => 'Pasantía técnica en Soporte y Redes',
        'empresa'     => 'Comunicaciones del Valle',
        'categoria'   => 'Pasantía',
        'modalidad'   => 'Presencial',
        'ubicacion'   => 'Medellín',
        'fecha'       => '2 de octubre de 2026',
        'descripcion' => 'Oportunidad de práctica formativa para egresados técnicos en sistemas e informática para soporte a usuarios y configuración de redes LAN.'
    ],
    [
        'id'          => 4,
        'titulo'      => 'Taller de Emprendimiento y Finanzas Personales',
        'empresa'     => 'Cámara de Comercio & Red Alzatista',
        'categoria'   => 'Capacitación',
        'modalidad'   => 'Virtual',
        'ubicacion'   => 'Online',
        'fecha'       => '25 de septiembre de 2026',
        'descripcion' => 'Programa de 4 módulos formativos gratuitos para egresados que deseen estructurar su modelo de negocio, acceder a capital semilla y organizar finanzas.'
    ],
    [
        'id'          => 5,
        'titulo'      => 'Vacante: Asistente Administrativo y Contable',
        'empresa'     => 'Soluciones Integrales del Norte',
        'categoria'   => 'Empleo',
        'modalidad'   => 'Tiempo completo',
        'ubicacion'   => 'Bello / Medellín',
        'fecha'       => '18 de septiembre de 2026',
        'descripcion' => 'Se requiere asistente con conocimientos en archivo documental, conciliaciones básicas y atención al público para empresa en expansión.'
    ],
    [
        'id'          => 6,
        'titulo'      => 'Fondo de Emprendimiento Comunitario',
        'empresa'     => 'Ruta N & Alcaldía de Medellín',
        'categoria'   => 'Convocatoria',
        'modalidad'   => 'Mixta',
        'ubicacion'   => 'Medellín',
        'fecha'       => '10 de septiembre de 2026',
        'descripcion' => 'Financiamiento no reembolsable y mentoría técnica para iniciativas de innovación social y ambiental lideradas por egresados de colegios públicos.'
    ]
];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/includes/header.php';
?>

<!-- Script Anti-FOUC para transiciones suaves si el sidebar está contraído -->
<script>
    try {
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    } catch(e) {}
</script>

<link rel="stylesheet" href="<?php echo assetCssUrl('oportunidades.css'); ?>">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido (con ítem Oportunidades activo) -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <main class="content-area dashboard-content oportunidades-content-wrapper" id="mainContent" tabindex="-1">
            <!-- Encabezado de la sección (apilado verticalmente en columna) -->
            <header class="section-header oportunidades-header-block">
                <h1 class="section-title oportunidades-header-title">Oportunidades y convocatorias</h1>
                <p class="section-subtitle oportunidades-header-subtitle">Encuentra convocatorias laborales, pasantías, becas y programas de formación para egresados.</p>
            </header>

            <!-- Contenedor blanco unificado (#FFFFFF, borde #E0E0E0, radio 16px, pad 32px) -->
            <div class="oportunidades-card-container" aria-label="Catálogo de oportunidades para egresados">
                <!-- Cuadrícula de 3 columnas simétricas en Desktop -->
                <div class="oportunidades-grid" id="oportunidadesGrid">
                    <?php foreach ($oportunidades as $item): ?>
                    <!-- Tarjeta interactiva clickeable que redirige a detalle-oportunidad.php -->
                    <a href="detalle-oportunidad.php?id=<?php echo (int)$item['id']; ?>" class="oportunidad-card" data-href="detalle-oportunidad.php?id=<?php echo (int)$item['id']; ?>" aria-label="Ver detalle de <?php echo htmlspecialchars($item['titulo']); ?>">
                        <div class="oportunidad-cover-wrapper">
                            <div class="oportunidad-cover-placeholder" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="oportunidad-info">
                            <div class="oportunidad-badges-row">
                                <span class="oportunidad-categoria-badge"><?php echo htmlspecialchars($item['categoria']); ?></span>
                                <span class="oportunidad-modalidad-badge"><?php echo htmlspecialchars($item['modalidad']); ?></span>
                            </div>
                            <h2 class="oportunidad-title"><?php echo htmlspecialchars($item['titulo']); ?></h2>
                            <div class="oportunidad-empresa">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V7l8-4v18"></path>
                                    <path d="M19 21V11l-6-4"></path>
                                    <path d="M9 9v.01"></path>
                                    <path d="M9 12v.01"></path>
                                    <path d="M9 15v.01"></path>
                                    <path d="M9 18v.01"></path>
                                </svg>
                                <span><?php echo htmlspecialchars($item['empresa']); ?></span>
                            </div>
                            <p class="oportunidad-description"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                            <div class="oportunidad-footer-meta">
                                <span class="oportunidad-date">Publicado: <?php echo htmlspecialchars($item['fecha']); ?></span>
                                <span class="oportunidad-location">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span><?php echo htmlspecialchars($item['ubicacion']); ?></span>
                                </span>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Botón inferior: "Ver más oportunidades" centrado, estilo outline azul (#1565C0) -->
                <div class="oportunidades-actions-bottom" id="oportunidadesStatusContainer">
                    <button type="button" class="btn-ver-mas-oportunidades" id="btnVerMasOportunidades" aria-label="Ver más oportunidades disponibles">
                        Ver más oportunidades
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
