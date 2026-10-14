<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Noticias (Figma: 07 - Noticias - Desktop)
 * Versión: 0.1.1
 */

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'noticias';
$titulo_pagina = 'Noticias — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'noticias';
$pageTitle = $titulo_pagina;
$topbarTitle = 'Noticias';

// Identidad de usuario para la barra superior (Topbar)
$userName = 'Santiago';
$userRole = 'Egresado';
$userInitial = 'S';

// Hojas de estilo y scripts adicionales gestionados por los includes globales
$extraCss = ['dashboard.css', 'noticias.css'];
$extraJs = ['assets/js/noticias.js'];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/includes/header.php';
?>
<script>
    // Asignación de clases en body para el entorno privado del Dashboard
    document.body.classList.add('dashboard-body-page', 'noticias-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Noticias" activo en rojo institucional) -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar con título "Noticias" y perfil de usuario) -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <!-- Contenido Central del Módulo de Noticias -->
        <main class="dashboard-content noticias-content-wrapper" id="noticiasMainContent" tabindex="-1">
            
            <!-- Encabezado de contenido según Figma ("07 - Noticias - Desktop") -->
            <header class="noticias-header-block">
                <h1 class="noticias-header-title">Noticias y novedades</h1>
                <p class="noticias-header-subtitle">Mantente informado sobre las novedades y actividades de la comunidad de egresados.</p>
            </header>

            <!-- Tarjeta contenedora blanca (#FFFFFF, borde #E0E0E0, radio 16px, padding 32px) -->
            <section class="noticias-card-container" aria-label="Catálogo de noticias y novedades de egresados">
                
                <!-- Grid de noticias: 3 columnas simétricas en Desktop, 6 tarjetas iniciales -->
                <div class="noticias-grid" id="noticiasGrid">
                    
                    <!-- Tarjeta 1: Nueva oportunidad para egresados -->
                    <article class="noticia-card">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <h2 class="noticia-title">Nueva oportunidad para egresados</h2>
                            <p class="noticia-description">Conoce las nuevas oportunidades disponibles para nuestra comunidad.</p>
                            <div class="noticia-date">15 de septiembre de 2026</div>
                        </div>
                    </article>

                    <!-- Tarjeta 2: Encuentro de egresados -->
                    <article class="noticia-card">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <h2 class="noticia-title">Encuentro de egresados</h2>
                            <p class="noticia-description">Participa en nuestro próximo encuentro y vuelve a conectar con la institución.</p>
                            <div class="noticia-date">10 de septiembre de 2026</div>
                        </div>
                    </article>

                    <!-- Tarjeta 3: Historias que inspiran -->
                    <article class="noticia-card">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <h2 class="noticia-title">Historias que inspiran</h2>
                            <p class="noticia-description">Conoce las experiencias y logros de nuestros egresados.</p>
                            <div class="noticia-date">5 de septiembre de 2026</div>
                        </div>
                    </article>

                    <!-- Tarjeta 4: Nuevos proyectos de la comunidad -->
                    <article class="noticia-card">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <h2 class="noticia-title">Nuevos proyectos de la comunidad</h2>
                            <p class="noticia-description">Descubre los proyectos que están desarrollando nuestros egresados.</p>
                            <div class="noticia-date">28 de agosto de 2026</div>
                        </div>
                    </article>

                    <!-- Tarjeta 5: Actividades institucionales -->
                    <article class="noticia-card">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <h2 class="noticia-title">Actividades institucionales</h2>
                            <p class="noticia-description">Consulta las próximas actividades y eventos de la institución.</p>
                            <div class="noticia-date">20 de agosto de 2026</div>
                        </div>
                    </article>

                    <!-- Tarjeta 6: Comunidad Raíces Alzate -->
                    <article class="noticia-card">
                        <div class="noticia-cover-wrapper">
                            <div class="noticia-cover-placeholder"></div>
                        </div>
                        <div class="noticia-info">
                            <h2 class="noticia-title">Comunidad Raíces Alzate</h2>
                            <p class="noticia-description">Seguimos fortaleciendo los vínculos entre egresados.</p>
                            <div class="noticia-date">12 de agosto de 2026</div>
                        </div>
                    </article>

                </div>

                <!-- Botón inferior: "Ver más noticias" centrado, estilo outline azul (#1565C0) -->
                <div class="noticias-actions-bottom" id="noticiasStatusContainer">
                    <button type="button" class="btn-ver-mas-noticias" id="btnVerMasNoticias" aria-label="Ver más noticias de la comunidad">
                        Ver más noticias
                    </button>
                </div>

            </section>
        </main>
    </div>
</div>

<?php
// 3. Inclusión del pie de página global compartido
require_once __DIR__ . '/includes/footer.php';
