<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Pantalla de Dashboard (03 - Dashboard - Desktop y Mobile)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

if (!function_exists('dashboardAssetCssUrl')) {
    function dashboardAssetCssUrl($filename) {
        $fullPath = __DIR__ . '/../assets/css/' . $filename;
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        return 'assets/css/' . $filename . '?v=' . APP_VERSION . '&amp;t=' . $time;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ('Dashboard | ' . APP_NAME)); ?></title>

    <!-- Favicon e Iconografía Institucional Oficial -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">

    <!-- Tipografía Inter (Preconexión y Google Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Hojas de estilo modulares -->
    <link rel="stylesheet" href="<?php echo dashboardAssetCssUrl('fonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo dashboardAssetCssUrl('main.css'); ?>">
    <link rel="stylesheet" href="<?php echo dashboardAssetCssUrl('dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php echo dashboardAssetCssUrl('loader.css'); ?>">

    <!-- Prevención de FOUC para sidebar colapsado según preferencia en localStorage -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="dashboard-body-page">
    <!-- Componente de precarga global (Page Loader con Skeleton y Spinner) -->
    <?php require_once __DIR__ . '/../includes/loader.php'; ?>

    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="dashboard-layout" id="dashboardLayout">
        <!-- 1. Barra Lateral de Navegación Privada (Sidebar 240px) -->
        <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- 2. Área Principal de Contenido -->
        <div class="dashboard-main-area">
            <!-- Barra Superior (Topbar) -->
            <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

            <!-- Contenido Central del Dashboard -->
            <main class="dashboard-content" id="dashboardMainContent" tabindex="-1">
                <!-- A. Sección de Bienvenida -->
                <?php
                // Obtener primer nombre o nombre completo disponible en sesión
                $userName = '';
                if (!empty($_SESSION['user']['nombre'])) {
                    $userName = htmlspecialchars(trim(explode(' ', $_SESSION['user']['nombre'])[0]), ENT_QUOTES, 'UTF-8');
                } elseif (!empty($_SESSION['usuario']['nombre'])) {
                    $userName = htmlspecialchars(trim(explode(' ', $_SESSION['usuario']['nombre'])[0]), ENT_QUOTES, 'UTF-8');
                } elseif (!empty($_SESSION['nombre'])) {
                    $userName = htmlspecialchars(trim(explode(' ', $_SESSION['nombre'])[0]), ENT_QUOTES, 'UTF-8');
                }
                ?>

                <div class="dashboard-welcome dashboard-welcome-header">
                    <h1 class="welcome-title dashboard-welcome-title">
                        <?= !empty($userName) ? "¡Hola, {$userName}!" : "¡Bienvenido a Raíces Alzate!" ?>
                    </h1>
                    <p class="welcome-message dashboard-welcome-desc">
                        Tus raíces siguen creciendo. Conecta con tu comunidad, descubre proyectos y encuentra nuevas oportunidades para construir tu futuro.
                    </p>
                </div>

                <!-- B. Fila de 3 Tarjetas de Resumen (Grid de 3 columnas en Desktop) -->
                <section class="dashboard-summary-cards" aria-label="Tarjetas de resumen de la plataforma">
                    <!-- Tarjeta 1: Mi perfil -->
                    <article class="dashboard-card summary-card" id="cardPerfil">
                        <div class="summary-card-body">
                            <div class="summary-card-icon-wrap" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <h3 class="summary-card-title">Mi perfil</h3>
                            <p class="summary-card-desc">
                                Mantén actualizada tu información personal y profesional.
                            </p>
                        </div>
                        <div class="summary-card-footer">
                            <a href="perfil.php" class="dashboard-action-link" id="linkVerPerfil">
                                Ver perfil <span class="arrow-symbol" aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>

                    <!-- Tarjeta 2: Mis proyectos -->
                    <article class="dashboard-card summary-card" id="cardProyectos">
                        <div class="summary-card-body">
                            <div class="summary-card-icon-wrap" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="summary-card-title">Mis proyectos</h3>
                            <p class="summary-card-desc">
                                Consulta y comparte proyectos con la comunidad.
                            </p>
                        </div>
                        <div class="summary-card-footer">
                            <a href="proyectos.php" class="dashboard-action-link" id="linkVerProyectos">
                                Ver proyectos <span class="arrow-symbol" aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>

                    <!-- Tarjeta 3: Oportunidades -->
                    <article class="dashboard-card summary-card" id="cardOportunidades">
                        <div class="summary-card-body">
                            <div class="summary-card-icon-wrap" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <h3 class="summary-card-title">Oportunidades</h3>
                            <p class="summary-card-desc">
                                Encuentra oportunidades académicas y profesionales.
                            </p>
                        </div>
                        <div class="summary-card-footer">
                            <a href="#oportunidadesSection" class="dashboard-action-link" id="linkVerOportunidades">
                                Ver oportunidades <span class="arrow-symbol" aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>
                </section>

                <!-- C. Bloque Inferior a Dos Columnas (Desktop: ~65% / ~35% | Mobile: Apilado ordenado) -->
                <div class="dashboard-split-block">
                    <!-- Columna Izquierda: Actividad reciente + Acciones rápidas -->
                    <div class="dashboard-column-left">
                        <!-- 1. Actividad reciente -->
                        <section class="dashboard-block-section activity-section" aria-labelledby="activityTitle">
                            <h3 class="dashboard-section-title" id="activityTitle">Actividad reciente</h3>
                            <div class="dashboard-card activity-card">
                                <ul class="activity-timeline-list">
                                    <li class="activity-timeline-item">
                                        <div class="activity-icon-bullet" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                            </svg>
                                        </div>
                                        <div class="activity-item-details">
                                            <p class="activity-action-text">Actualizaste tu información de perfil</p>
                                            <span class="activity-timestamp">Hace 2 horas</span>
                                        </div>
                                    </li>
                                    <li class="activity-timeline-item">
                                        <div class="activity-icon-bullet" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                            </svg>
                                        </div>
                                        <div class="activity-item-details">
                                            <p class="activity-action-text">Publicaste un nuevo proyecto</p>
                                            <span class="activity-timestamp">Ayer</span>
                                        </div>
                                    </li>
                                    <li class="activity-timeline-item">
                                        <div class="activity-icon-bullet" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        </div>
                                        <div class="activity-item-details">
                                            <p class="activity-action-text">Te uniste a la red de egresados</p>
                                            <span class="activity-timestamp">Hace 3 días</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section>

                        <!-- 2. Acciones rápidas (en desktop abajo de actividad, en mobile orden 3) -->
                        <section class="dashboard-block-section quick-actions-section" aria-labelledby="quickActionsTitle">
                            <h3 class="dashboard-section-title" id="quickActionsTitle">Acciones rápidas</h3>
                            <div class="quick-actions-buttons-group dashboard-actions">
                                <a href="perfil.php" class="btn btn-primary btn-quick-primary btn-actualizar-perfil" id="btnQuickActualizarPerfil">
                                    Actualizar mi perfil
                                </a>
                                <a href="proyectos.php" class="btn btn-outline-secondary btn-quick-outline btn-publicar-proyecto" id="btnQuickPublicarProyecto">
                                    Publicar proyecto
                                </a>
                            </div>
                        </section>
                    </div>

                    <!-- Columna Derecha: Próximas oportunidades -->
                    <div class="dashboard-column-right">
                        <section class="dashboard-block-section opportunities-section" id="oportunidadesSection" aria-labelledby="opportunitiesTitle">
                            <h3 class="dashboard-section-title" id="opportunitiesTitle">Próximas oportunidades</h3>
                            <div class="dashboard-card opportunities-card">
                                <ul class="opportunities-list">
                                    <!-- Oportunidad 1: Beca -->
                                    <li class="opportunity-item">
                                        <div class="opportunity-header-row">
                                            <span class="opportunity-tag tag-blue">Beca</span>
                                        </div>
                                        <h4 class="opportunity-item-title">Beca de formación tecnológica</h4>
                                        <p class="opportunity-item-desc">
                                            Convocatoria abierta &mdash; Límite: 25 de Octubre
                                        </p>
                                    </li>

                                    <!-- Oportunidad 2: Empleo -->
                                    <li class="opportunity-item">
                                        <div class="opportunity-header-row">
                                            <span class="opportunity-tag tag-red">Empleo</span>
                                        </div>
                                        <h4 class="opportunity-item-title">Oferta laboral para egresados</h4>
                                        <p class="opportunity-item-desc">
                                            Sector tecnología y gestión &mdash; Postulación inmediata
                                        </p>
                                    </li>

                                    <!-- Oportunidad 3: Capacitación -->
                                    <li class="opportunity-item">
                                        <div class="opportunity-header-row">
                                            <span class="opportunity-tag tag-dark">Capacitación</span>
                                        </div>
                                        <h4 class="opportunity-item-title">Curso de actualización profesional</h4>
                                        <p class="opportunity-item-desc">
                                            Modalidad virtual &mdash; Certificación incluida
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Script de interacción del Dashboard (Sidebar colapsable desktop + Drawer móvil) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('dashboardSidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const menuToggle = document.getElementById('dashboardMenuToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            // --- 1. LÓGICA DE SIDEBAR COLAPSABLE / MINI-SIDEBAR (DESKTOP) ---
            if (sidebar && sidebarToggleBtn) {
                const updateToggleState = (collapsed) => {
                    if (collapsed) {
                        sidebarToggleBtn.setAttribute('aria-label', 'Expandir barra lateral');
                        sidebarToggleBtn.setAttribute('title', 'Expandir menú');
                    } else {
                        sidebarToggleBtn.setAttribute('aria-label', 'Contraer barra lateral');
                        sidebarToggleBtn.setAttribute('title', 'Contraer menú');
                    }
                };

                // Inicializar estado persistido desde localStorage (solo en pantallas de escritorio)
                try {
                    const savedCollapsed = localStorage.getItem('sidebar_collapsed');
                    if (savedCollapsed === 'true' && window.innerWidth > 768) {
                        sidebar.classList.add('is-collapsed', 'collapsed');
                        document.documentElement.classList.add('sidebar-is-collapsed');
                        updateToggleState(true);
                    } else if (savedCollapsed === 'false') {
                        sidebar.classList.remove('is-collapsed', 'collapsed');
                        document.documentElement.classList.remove('sidebar-is-collapsed');
                        updateToggleState(false);
                    } else if (document.documentElement.classList.contains('sidebar-is-collapsed')) {
                        sidebar.classList.add('is-collapsed', 'collapsed');
                        updateToggleState(true);
                    }
                } catch (err) {
                    console.warn('Acceso a localStorage no disponible:', err);
                }

                // Evento click del botón toggle
                sidebarToggleBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    const isCurrentlyCollapsed = sidebar.classList.contains('is-collapsed');
                    const nextCollapsed = !isCurrentlyCollapsed;

                    sidebar.classList.toggle('is-collapsed', nextCollapsed);
                    sidebar.classList.toggle('collapsed', nextCollapsed);
                    document.documentElement.classList.toggle('sidebar-is-collapsed', nextCollapsed);

                    try {
                        localStorage.setItem('sidebar_collapsed', nextCollapsed ? 'true' : 'false');
                    } catch (err) {
                        console.warn('No se pudo guardar la preferencia en localStorage:', err);
                    }

                    updateToggleState(nextCollapsed);
                });
            }

            // --- 2. LÓGICA DE MENÚ DRAWER (MÓVIL <= 768px) ---
            if (menuToggle && sidebar && backdrop) {
                const toggleMobileSidebar = (open) => {
                    const isOpen = open !== undefined ? open : !sidebar.classList.contains('is-open');
                    sidebar.classList.toggle('is-open', isOpen);
                    backdrop.classList.toggle('is-visible', isOpen);
                    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    document.body.classList.toggle('sidebar-drawer-active', isOpen);
                };

                menuToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleMobileSidebar();
                });

                backdrop.addEventListener('click', () => {
                    toggleMobileSidebar(false);
                });

                // Cerrar menú con la tecla Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                        toggleMobileSidebar(false);
                        menuToggle.focus();
                    }
                });

                // Cerrar drawer móvil si la pantalla se redimensiona a escritorio
                window.addEventListener('resize', () => {
                    if (window.innerWidth > 768 && sidebar.classList.contains('is-open')) {
                        toggleMobileSidebar(false);
                    }
                });
            }
        });
    </script>

    <!-- Script global del Page Loader (desvanecimiento y desmontaje) -->
    <script src="assets/js/loader.js?v=<?php echo APP_VERSION; ?>"></script>
</body>
</html>
