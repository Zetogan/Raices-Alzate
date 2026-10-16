<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Catálogo de Proyectos (Panel Privado)
 * Especificación Figma: 06 - Proyectos - Desktop
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'proyectos';
$titulo_seccion = 'Proyectos';
$titulo_pagina = 'Proyectos — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'proyectos';
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
$extraCss = ['dashboard.css', 'proyectos.css'];
$extraJs = ['assets/js/proyectos.js'];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/header.php';
?>

<!-- Script Anti-FOUC para transiciones suaves si el sidebar está contraído -->
<script>
    try {
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    } catch(e) {}
</script>

<link rel="stylesheet" href="<?php echo assetCssUrl('proyectos.css'); ?>">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Proyectos" activo en rojo institucional) -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Barra superior (Topbar con título "Proyectos" y perfil de usuario) -->
        <?php require_once __DIR__ . '/topbar.php'; ?>

        <!-- Contenido Central del Módulo de Proyectos: Fluye debajo de la Topbar sin solapamiento -->
        <main class="content-area dashboard-content proyectos-content-wrapper" id="mainContent" tabindex="-1">
            
            <!-- Encabezado de contenido con botón de acción "+ Publicar proyecto" alineado a la derecha -->
            <header class="section-header proyectos-header-block">
                <div class="proyectos-header-titles">
                    <h1 class="section-title proyectos-header-title">Proyectos de egresados</h1>
                    <p class="section-subtitle proyectos-header-subtitle">Conoce y comparte proyectos realizados por la comunidad de egresados.</p>
                </div>
                <div class="proyectos-header-actions">
                    <a href="crear-proyecto.php" class="btn-crear-proyecto" id="btnCrearProyecto" role="button">
                        <svg class="btn-icon-plus" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Publicar proyecto</span>
                    </a>
                </div>
            </header>

            <!-- Tarjeta contenedora blanca (#FFFFFF, borde #E0E0E0, radio 16px, padding 32px) -->
            <section class="projects-card-container proyectos-card-container" aria-label="Catálogo de proyectos de egresados">
                
                <?php
                // Colección estática de proyectos comunitarios (CERO BASES DE DATOS / CERO SQL)
                $proyectosCatalogo = [
                    [
                        'id'          => 1,
                        'titulo'      => 'Sistema de gestión académica',
                        'descripcion' => 'Plataforma para facilitar la gestión y organización académica.',
                        'autor'       => 'Santiago Emitola',
                        'es_propio'   => true
                    ],
                    [
                        'id'          => 2,
                        'titulo'      => 'Aplicación para emprendedores',
                        'descripcion' => 'Herramienta digital para apoyar pequeños emprendimientos.',
                        'autor'       => 'María López',
                        'es_propio'   => false
                    ],
                    [
                        'id'          => 3,
                        'titulo'      => 'Portafolio de diseño',
                        'descripcion' => 'Espacio para mostrar trabajos y proyectos de diseño gráfico.',
                        'autor'       => 'Carlos Rodríguez',
                        'es_propio'   => false
                    ],
                    [
                        'id'          => 4,
                        'titulo'      => 'Tienda virtual',
                        'descripcion' => 'Proyecto enfocado en la creación de una tienda en línea.',
                        'autor'       => 'Laura Martínez',
                        'es_propio'   => false
                    ],
                    [
                        'id'          => 5,
                        'titulo'      => 'App de servicios',
                        'descripcion' => 'Aplicación para conectar usuarios con servicios locales.',
                        'autor'       => 'Andrés Gómez',
                        'es_propio'   => false
                    ],
                    [
                        'id'          => 6,
                        'titulo'      => 'Proyecto comunitario',
                        'descripcion' => 'Iniciativa para fortalecer la participación de la comunidad.',
                        'autor'       => 'Valentina Torres',
                        'es_propio'   => false
                    ]
                ];
                ?>

                <!-- Grid de proyectos: 3 columnas simétricas en Desktop, 6 tarjetas iniciales -->
                <div class="proyectos-grid" id="proyectosGrid">
                    
                    <?php foreach ($proyectosCatalogo as $item): ?>
                    <!-- Tarjeta interactiva con acceso a Detalle y botón Editar para proyectos propios -->
                    <article class="proyecto-card project-card" data-href="detalle-proyecto.php?id=<?php echo (int)$item['id']; ?>" role="link" tabindex="0" aria-label="Ver detalle del proyecto <?php echo htmlspecialchars($item['titulo']); ?>">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title project-title">
                                <a href="detalle-proyecto.php?id=<?php echo (int)$item['id']; ?>" class="proyecto-title-link project-link stretched-link">
                                    <?php echo htmlspecialchars($item['titulo']); ?>
                                </a>
                            </h2>
                            <p class="proyecto-description"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                            
                            <div class="proyecto-footer-row">
                                <div class="proyecto-author">Por <?php echo htmlspecialchars($item['autor']); ?></div>
                                
                                <?php if (!empty($item['es_propio'])): ?>
                                <a href="editar-proyecto.php?id=<?php echo (int)$item['id']; ?>" class="btn-editar-card" title="Editar proyecto <?php echo htmlspecialchars($item['titulo']); ?>" onclick="event.stopPropagation();">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    <span>Editar</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>

                </div>

                <!-- Botón inferior: "Ver más proyectos" centrado, estilo outline azul (#1565C0) -->
                <div class="proyectos-actions-bottom" id="proyectosStatusContainer">
                    <button type="button" class="btn-ver-mas-proyectos" id="btnVerMasProyectos" aria-label="Ver más proyectos de egresados">
                        Ver más proyectos
                    </button>
                </div>

            </section>
        </main>
    </div>
</div>

<?php
// 3. Inclusión del pie de página global compartido
require_once __DIR__ . '/footer.php';
