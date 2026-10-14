<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Proyectos (Figma: 06 - Proyectos - Desktop)
 * Versión: 0.1.1
 */

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'proyectos';
$titulo_seccion = 'Proyectos';
$titulo_pagina = 'Proyectos — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'proyectos';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$userName = 'Santiago';
$userRole = 'Egresado';
$userInitial = 'S';

// Hojas de estilo y scripts adicionales gestionados por los includes globales
$extraCss = ['dashboard.css', 'proyectos.css'];
$extraJs = ['assets/js/proyectos.js'];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/includes/header.php';
?>
<script>
    // Asignación de clases en body para el entorno privado del Dashboard
    document.body.classList.add('dashboard-body-page', 'proyectos-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Proyectos" activo en rojo institucional) -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar con título "Proyectos" y perfil de usuario) -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <!-- Contenido Central del Módulo de Proyectos: Fluye debajo de la Topbar sin solapamiento -->
        <main class="dashboard-content content-area proyectos-content-wrapper" id="proyectosMainContent" tabindex="-1">
            
            <!-- Encabezado de contenido según Figma ("06 - Proyectos - Desktop") -->
            <header class="section-header proyectos-header-block">
                <h1 class="section-title proyectos-header-title">Proyectos de egresados</h1>
                <p class="section-subtitle proyectos-header-subtitle">Conoce y comparte proyectos realizados por la comunidad de egresados.</p>
            </header>

            <!-- Tarjeta contenedora blanca (#FFFFFF, borde #E0E0E0, radio 16px, padding 32px) -->
            <section class="projects-card-container proyectos-card-container" aria-label="Catálogo de proyectos de egresados">
                
                <!-- Grid de proyectos: 3 columnas simétricas en Desktop, 6 tarjetas iniciales -->
                <div class="proyectos-grid" id="proyectosGrid">
                    
                    <!-- Tarjeta 1: Sistema de gestión académica -->
                    <article class="proyecto-card">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title">Sistema de gestión académica</h2>
                            <p class="proyecto-description">Plataforma para facilitar la gestión y organización académica.</p>
                            <div class="proyecto-author">Por Juan Pérez</div>
                        </div>
                    </article>

                    <!-- Tarjeta 2: Aplicación para emprendedores -->
                    <article class="proyecto-card">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title">Aplicación para emprendedores</h2>
                            <p class="proyecto-description">Herramienta digital para apoyar pequeños emprendimientos.</p>
                            <div class="proyecto-author">Por María López</div>
                        </div>
                    </article>

                    <!-- Tarjeta 3: Portafolio de diseño -->
                    <article class="proyecto-card">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title">Portafolio de diseño</h2>
                            <p class="proyecto-description">Espacio para mostrar trabajos y proyectos de diseño gráfico.</p>
                            <div class="proyecto-author">Por Carlos Rodríguez</div>
                        </div>
                    </article>

                    <!-- Tarjeta 4: Tienda virtual -->
                    <article class="proyecto-card">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title">Tienda virtual</h2>
                            <p class="proyecto-description">Proyecto enfocado en la creación de una tienda en línea.</p>
                            <div class="proyecto-author">Por Laura Martínez</div>
                        </div>
                    </article>

                    <!-- Tarjeta 5: App de servicios -->
                    <article class="proyecto-card">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title">App de servicios</h2>
                            <p class="proyecto-description">Aplicación para conectar usuarios con servicios locales.</p>
                            <div class="proyecto-author">Por Andrés Gómez</div>
                        </div>
                    </article>

                    <!-- Tarjeta 6: Proyecto comunitario -->
                    <article class="proyecto-card">
                        <div class="proyecto-cover-wrapper">
                            <div class="proyecto-cover-placeholder"></div>
                        </div>
                        <div class="proyecto-info">
                            <h2 class="proyecto-title">Proyecto comunitario</h2>
                            <p class="proyecto-description">Iniciativa para fortalecer la participación de la comunidad.</p>
                            <div class="proyecto-author">Por Valentina Torres</div>
                        </div>
                    </article>

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
require_once __DIR__ . '/includes/footer.php';
