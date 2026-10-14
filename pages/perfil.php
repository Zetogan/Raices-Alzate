<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Pantalla de Perfil del Egresado
 * Especificación Figma: 04 - Perfil - Desktop y adaptación Mobile
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

if (!function_exists('perfilAssetCssUrl')) {
    function perfilAssetCssUrl($filename) {
        $fullPath = __DIR__ . '/../assets/css/' . $filename;
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        $version = defined('APP_VERSION') ? APP_VERSION : '0.1.5';
        return 'assets/css/' . $filename . '?v=' . $version . '&amp;t=' . $time;
    }
}

// Extracción de datos del usuario autenticado
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userEmail = trim($user['email'] ?? 'santiago@ejemplo.com');

// Obtener inicial para el avatar de forma segura y compatible con UTF-8
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

$topbarTitle = 'Mi perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ('Mi perfil | ' . APP_NAME)); ?></title>

    <!-- Favicon e Iconografía Institucional Oficial -->
    <link rel="icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/icons/logo%20sin%20fondo%20raices%20alzate.ico">

    <!-- Tipografía Inter (Preconexión y Google Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Hojas de estilo modulares -->
    <link rel="stylesheet" href="<?php echo perfilAssetCssUrl('fonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo perfilAssetCssUrl('main.css'); ?>">
    <link rel="stylesheet" href="<?php echo perfilAssetCssUrl('dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php echo perfilAssetCssUrl('perfil.css'); ?>">
    <link rel="stylesheet" href="<?php echo perfilAssetCssUrl('loader.css'); ?>">

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
<body class="dashboard-body-page profile-body-page">
    <!-- Componente de precarga global (Page Loader con Spinner y Logotipo) -->
    <?php require_once __DIR__ . '/../includes/loader.php'; ?>

    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="dashboard-layout" id="dashboardLayout">
        <!-- 1. Barra Lateral de Navegación Privada (Sidebar con ítem Perfil activo) -->
        <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- 2. Área Principal de Contenido -->
        <div class="dashboard-main-area">
            <!-- Barra Superior (Topbar) -->
            <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

            <!-- Contenido Central del Perfil -->
            <main class="dashboard-content profile-content-wrapper" id="profileMainContent" tabindex="-1">
                
                <!-- A. Miga de pan / Breadcrumb -->
                <nav class="profile-breadcrumb-nav" aria-label="Ruta de navegación">
                    <ol class="profile-breadcrumb-list">
                        <li class="profile-breadcrumb-item">
                            <a href="dashboard.php" class="profile-breadcrumb-link">Dashboard</a>
                        </li>
                        <li class="profile-breadcrumb-item">
                            <span class="profile-breadcrumb-sep" aria-hidden="true">/</span>
                            <span class="profile-breadcrumb-current" aria-current="page">Mi perfil</span>
                        </li>
                    </ol>
                </nav>

                <!-- B. Tarjeta Hero del Perfil (Portada, Avatar, Identidad y Acciones) -->
                <section class="profile-hero-card" id="profileHeroSection" aria-label="Cabecera del perfil">
                    <!-- Portada institucional con degradado y patrón -->
                    <div class="profile-hero-cover" aria-hidden="true">
                        <div class="profile-cover-pattern"></div>
                        <span class="profile-cover-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                            I.E. Gilberto Alzate Avendaño
                        </span>
                    </div>

                    <!-- Contenido de identidad que solapa la portada -->
                    <div class="profile-hero-body">
                        <div class="profile-hero-identity">
                            <!-- Avatar con inicial grande y botón de cambio de foto -->
                            <div class="profile-avatar-container">
                                <div class="profile-avatar-large" id="profileAvatarDisplay" aria-hidden="true">
                                    <?php echo htmlspecialchars($userInitial); ?>
                                </div>
                                <span class="profile-avatar-online-dot" title="Egresado activo en la plataforma" aria-label="Usuario activo"></span>
                                <button type="button" class="profile-avatar-edit-btn" id="btnEditarAvatar" title="Actualizar foto de perfil" aria-label="Actualizar foto de perfil">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                </button>
                            </div>

                            <!-- Textos de Identidad y Metadatos -->
                            <div class="profile-identity-details">
                                <h1 class="profile-user-name" id="profileDisplayName">
                                    <?php echo htmlspecialchars($userName); ?>
                                </h1>
                                <p class="profile-headline" id="profileDisplayHeadline">
                                    Bachiller Técnico en Sistemas y Gestión Comercial • Promoción 2021
                                </p>
                                <div class="profile-meta-pills-row">
                                    <span class="profile-meta-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span id="profileDisplayCiudad">Medellín, Colombia</span>
                                    </span>
                                    <span class="profile-meta-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <span><?php echo htmlspecialchars($userRole); ?> Alzatista</span>
                                    </span>
                                    <span class="profile-meta-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                        </svg>
                                        <span id="profileDisplayEmpresa">InnovaTech Medellín</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción de Cabecera -->
                        <div class="profile-hero-actions">
                            <a href="editar-perfil.php" class="btn-profile-primary" id="btnAbrirEditarPerfil" style="text-decoration: none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Editar perfil
                            </a>
                            <button type="button" class="btn-profile-secondary" id="btnCompartirPerfil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                </svg>
                                Compartir
                            </button>
                        </div>
                    </div>
                </section>

                <!-- C. Fila de Métricas / Resumen Rápido -->
                <section class="profile-stats-grid" aria-label="Estadísticas del perfil">
                    <!-- Stat 1: Proyectos -->
                    <article class="profile-stat-card">
                        <div class="stat-icon-wrap stat-icon-red">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-value">2</p>
                            <p class="stat-label">Proyectos publicados</p>
                        </div>
                    </article>

                    <!-- Stat 2: Promoción -->
                    <article class="profile-stat-card">
                        <div class="stat-icon-wrap stat-icon-blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-value">2021</p>
                            <p class="stat-label">Promoción egresado</p>
                        </div>
                    </article>

                    <!-- Stat 3: Egresados Conectados -->
                    <article class="profile-stat-card">
                        <div class="stat-icon-wrap stat-icon-green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-value">34</p>
                            <p class="stat-label">Egresados en tu red</p>
                        </div>
                    </article>

                    <!-- Stat 4: Completitud -->
                    <article class="profile-stat-card">
                        <div class="stat-icon-wrap stat-icon-amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <p class="stat-value">95%</p>
                            <p class="stat-label">Perfil completado</p>
                            <div class="stat-progress-bar">
                                <div class="stat-progress-fill"></div>
                            </div>
                        </div>
                    </article>
                </section>

                <!-- D. Distribución Principal a Dos Columnas -->
                <div class="profile-main-layout">
                    
                    <!-- Columna Izquierda (Contacto, Redes y Privacidad) -->
                    <div class="profile-column-left">
                        
                        <!-- Tarjeta 1: Información de Contacto -->
                        <article class="profile-card" id="cardContacto">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Información de contacto</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <ul class="profile-info-list">
                                    <!-- Correo -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Correo electrónico</span>
                                            <a href="mailto:<?php echo htmlspecialchars($userEmail); ?>" class="profile-info-value profile-info-link" id="profileDisplayEmail">
                                                <?php echo htmlspecialchars($userEmail); ?>
                                            </a>
                                        </div>
                                    </li>

                                    <!-- Teléfono -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Teléfono celular</span>
                                            <span class="profile-info-value" id="profileDisplayTelefono">+57 (300) 123-4567</span>
                                        </div>
                                    </li>

                                    <!-- Documento -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                <line x1="7" y1="8" x2="17" y2="8"></line>
                                                <line x1="7" y1="12" x2="13" y2="12"></line>
                                                <line x1="7" y1="16" x2="10" y2="16"></line>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Documento de identidad</span>
                                            <span class="profile-info-value">C.C. 1.036.***.***</span>
                                        </div>
                                    </li>

                                    <!-- Ubicación -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Ubicación y barrio</span>
                                            <span class="profile-info-value" id="profileDisplayBarrio">Comuna 10 (La Candelaria), Medellín</span>
                                        </div>
                                    </li>

                                    <!-- Registro -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Fecha de vinculación</span>
                                            <span class="profile-info-value">Marzo de 2024</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </article>

                        <!-- Tarjeta 2: Redes y Enlaces Profesionales -->
                        <article class="profile-card" id="cardRedes">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Redes y enlaces</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <ul class="profile-social-links-list">
                                    <!-- LinkedIn -->
                                    <li class="profile-social-link-item">
                                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" id="linkLinkedin" title="Abrir perfil de LinkedIn">
                                            <div class="social-lead">
                                                <span class="social-icon-box">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                                    </svg>
                                                </span>
                                                <span id="profileDisplayLinkedin">linkedin.com/in/santiago-emitola</span>
                                            </div>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </li>

                                    <!-- GitHub -->
                                    <li class="profile-social-link-item">
                                        <a href="https://github.com" target="_blank" rel="noopener noreferrer" id="linkGithub" title="Abrir perfil de GitHub">
                                            <div class="social-lead">
                                                <span class="social-icon-box">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                                                    </svg>
                                                </span>
                                                <span id="profileDisplayGithub">github.com/santiagoemitola</span>
                                            </div>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </li>

                                    <!-- Portafolio / Web -->
                                    <li class="profile-social-link-item">
                                        <a href="#" id="linkPortafolio" title="Abrir sitio web o portafolio">
                                            <div class="social-lead">
                                                <span class="social-icon-box">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                    </svg>
                                                </span>
                                                <span id="profileDisplayWeb">santiagoemitola.dev</span>
                                            </div>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </article>

                        <!-- Tarjeta 3: Visibilidad y Privacidad Institucional -->
                        <article class="profile-card" id="cardPrivacidad">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Visibilidad en la red</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <div class="profile-privacy-box">
                                    <div class="privacy-info-text">
                                        <h3 class="privacy-title">Perfil visible en directorio</h3>
                                        <p class="privacy-desc">Permite que otros egresados alzatistas puedan encontrarte y conectarse contigo.</p>
                                    </div>
                                    <label class="switch-toggle" for="toggleVisibilidad">
                                        <input type="checkbox" id="toggleVisibilidad" checked aria-label="Alternar visibilidad del perfil en el directorio de egresados">
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </article>

                    </div>

                    <!-- Columna Derecha (Sobre mí, Formación, Experiencia, Habilidades y Proyectos) -->
                    <div class="profile-column-right">

                        <!-- Tarjeta 4: Sobre mí / Biografía -->
                        <article class="profile-card" id="cardSobreMi">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Sobre mí</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <p class="profile-bio-text" id="profileDisplayBio">
                                    Egresado alzatista apasionado por el desarrollo web y las soluciones tecnológicas con impacto social. Enfocado en la arquitectura de aplicaciones modernas, escalables y accesibles. Miembro activo de la comunidad de egresados de la I.E. Gilberto Alzate Avendaño, promoviendo iniciativas de mentoría para bachilleres e impulsando el talento de las nuevas generaciones.
                                </p>
                                <blockquote class="profile-quote-highlight">
                                    "Las raíces que forjamos en el Alzate son los cimientos con los que construimos el futuro de nuestra comunidad."
                                </blockquote>
                            </div>
                        </article>

                        <!-- Tarjeta 5: Formación Académica -->
                        <article class="profile-card" id="cardAcademica">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Formación académica</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <div class="profile-timeline">
                                    
                                    <!-- Elemento 1: I.E. Gilberto Alzate Avendaño -->
                                    <div class="timeline-item">
                                        <div class="timeline-bullet" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title">I.E. Gilberto Alzate Avendaño</h3>
                                            <span class="timeline-badge-tag">Secundaria & Media</span>
                                        </div>
                                        <p class="timeline-subtitle" id="profileDisplayGrado">Bachiller Técnico en Sistemas y Gestión Comercial</p>
                                        <span class="timeline-period">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            2016 – 2021 • Graduado con mención de honor
                                        </span>
                                        <p class="timeline-desc">
                                            Participación activa en el semillero institucional de innovación y personería estudiantil. Desarrollo del proyecto tecnológico de grado orientado al registro comunitario.
                                        </p>
                                    </div>

                                    <!-- Elemento 2: Formación Superior / SENA -->
                                    <div class="timeline-item">
                                        <div class="timeline-bullet timeline-bullet-blue" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title">SENA - Centro de Tecnología de la Manufactura Avanzada</h3>
                                            <span class="timeline-badge-tag">Educación Superior</span>
                                        </div>
                                        <p class="timeline-subtitle">Tecnología en Análisis y Desarrollo de Software (ADSO)</p>
                                        <span class="timeline-period">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            2022 – 2024 • Titulado
                                        </span>
                                        <p class="timeline-desc">
                                            Especialización en metodologías ágiles, bases de datos relacionales, programación web orientada a objetos y despliegue de soluciones en servidores seguros.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </article>

                        <!-- Tarjeta 6: Experiencia Laboral y Ocupación -->
                        <article class="profile-card" id="cardLaboral">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Experiencia laboral y profesional</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <div class="profile-timeline">
                                    
                                    <!-- Empleo 1: Actual -->
                                    <div class="timeline-item">
                                        <div class="timeline-bullet" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title" id="profileDisplayCargo">Desarrollador Web Frontend & Backend</h3>
                                            <span class="timeline-badge-tag" style="background-color: #E8F5E9; color: #2E7D32;">Actual</span>
                                        </div>
                                        <p class="timeline-subtitle" id="profileDisplayEmpresaDetalle">InnovaTech Medellín S.A.S. • Jornada completa</p>
                                        <span class="timeline-period">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            Junio 2023 – Presente (1 año y 5 meses) • Medellín
                                        </span>
                                        <p class="timeline-desc">
                                            Desarrollo y mantenimiento de portales web interactivos, integración de APIs REST, optimización de velocidad de carga y aseguramiento de accesibilidad WCAG.
                                        </p>
                                    </div>

                                    <!-- Empleo 2: Anterior -->
                                    <div class="timeline-item">
                                        <div class="timeline-bullet timeline-bullet-blue" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title">Auxiliar Técnico en Sistemas</h3>
                                            <span class="timeline-badge-tag">Concluido</span>
                                        </div>
                                        <p class="timeline-subtitle">Red de Soluciones Digitales Antioquia • Medio tiempo</p>
                                        <span class="timeline-period">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            Enero 2022 – Mayo 2023 • Medellín
                                        </span>
                                        <p class="timeline-desc">
                                            Soporte a infraestructura de redes, mantenimiento preventivo de equipos y asistencia técnica en el despliegue de plataformas institucionales.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </article>

                        <!-- Tarjeta 7: Habilidades y Competencias -->
                        <article class="profile-card" id="cardHabilidades">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Habilidades y competencias</h2>
                                </div>
                            </header>
                            <div class="profile-card-body">
                                <div class="profile-skills-group">
                                    <span class="skill-chip skill-chip-primary">Desarrollo Web (HTML5/CSS3)</span>
                                    <span class="skill-chip skill-chip-primary">JavaScript Vanilla</span>
                                    <span class="skill-chip skill-chip-primary">PHP 8.x & MySQL</span>
                                    <span class="skill-chip skill-chip-blue">Arquitectura de Software</span>
                                    <span class="skill-chip skill-chip-blue">Git & Control de Versiones</span>
                                    <span class="skill-chip">Liderazgo Comunitario</span>
                                    <span class="skill-chip">Trabajo en Equipo</span>
                                    <span class="skill-chip">Gestión Comercial</span>
                                    <span class="skill-chip">Resolución de Problemas</span>
                                    <span class="skill-chip">Mentoría a Estudiantes</span>
                                </div>
                            </div>
                        </article>

                        <!-- Tarjeta 8: Proyectos Vinculados en Raíces Alzate -->
                        <article class="profile-card" id="cardProyectosVinculados">
                            <header class="profile-card-header">
                                <div class="profile-card-title-group">
                                    <span class="profile-card-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                    </span>
                                    <h2 class="profile-card-title">Mis proyectos en Raíces Alzate</h2>
                                </div>
                                <a href="proyectos.php" class="profile-card-action-btn" title="Ver catálogo completo de proyectos">
                                    Ver todos &rarr;
                                </a>
                            </header>
                            <div class="profile-card-body">
                                <div class="profile-projects-grid">
                                    
                                    <!-- Proyecto 1 -->
                                    <div class="profile-project-card">
                                        <div>
                                            <div class="project-card-header">
                                                <span class="project-tag project-tag-tech">Tecnología</span>
                                                <span class="project-role-badge">Co-creador</span>
                                            </div>
                                            <h3 class="project-card-title">Semillero de Robótica Alzatista</h3>
                                            <p class="project-card-desc">
                                                Iniciativa orientada a fomentar el pensamiento computacional y la robótica educativa en los grados 9°, 10° y 11°.
                                            </p>
                                        </div>
                                        <a href="proyectos.php" class="project-card-link">
                                            Consultar proyecto &rarr;
                                        </a>
                                    </div>

                                    <!-- Proyecto 2 -->
                                    <div class="profile-project-card">
                                        <div>
                                            <div class="project-card-header">
                                                <span class="project-tag project-tag-community">Comunidad</span>
                                                <span class="project-role-badge">Líder</span>
                                            </div>
                                            <h3 class="project-card-title">Red de Mentorías para Bachilleres</h3>
                                            <p class="project-card-desc">
                                                Programa de acompañamiento vocacional liderado por egresados profesionales para orientar a próximos graduandos.
                                            </p>
                                        </div>
                                        <a href="proyectos.php" class="project-card-link">
                                            Consultar proyecto &rarr;
                                        </a>
                                    </div>

                                </div>

                                <div class="profile-projects-footer">
                                    <a href="proyectos.php" class="btn-publicar-proyecto btn-outline-secondary" id="btnPublicarNuevoProyecto">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Publicar un nuevo proyecto
                                    </a>
                                </div>
                            </div>
                        </article>

                    </div>

                </div>

            </main>
        </div>
    </div>

    <!-- E. Modal Accesible de Edición de Perfil (Figma: 11 - Editar perfil) -->
    <div class="profile-modal-backdrop" id="modalEditarPerfil" role="dialog" aria-modal="true" aria-labelledby="modalEditarPerfilTitle">
        <div class="profile-modal-window">
            
            <header class="profile-modal-header">
                <h2 class="profile-modal-title" id="modalEditarPerfilTitle">Editar mi perfil</h2>
                <button type="button" class="profile-modal-close-btn" id="btnCerrarModal" aria-label="Cerrar ventana de edición">&times;</button>
            </header>

            <!-- Pestañas de edición -->
            <nav class="profile-modal-tabs" aria-label="Secciones del formulario de edición">
                <button type="button" class="modal-tab-btn active" data-tab="tab-personal">Datos personales</button>
                <button type="button" class="modal-tab-btn" data-tab="tab-academico">Académico y laboral</button>
                <button type="button" class="modal-tab-btn" data-tab="tab-enlaces">Redes y enlaces</button>
            </nav>

            <form class="profile-modal-form" id="formEditarPerfil">
                <div class="profile-modal-body">
                    
                    <!-- Pestaña 1: Datos Personales -->
                    <div class="modal-tab-panel active" id="tab-personal">
                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputNombre" class="form-field-label">Nombre completo *</label>
                                <input type="text" id="inputNombre" class="form-field-input" value="<?php echo htmlspecialchars($userName); ?>" required>
                            </div>
                            <div class="form-group-field">
                                <label for="inputEmail" class="form-field-label">Correo electrónico *</label>
                                <input type="email" id="inputEmail" class="form-field-input" value="<?php echo htmlspecialchars($userEmail); ?>" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputTelefono" class="form-field-label">Teléfono celular</label>
                                <input type="tel" id="inputTelefono" class="form-field-input" value="+57 (300) 123-4567">
                            </div>
                            <div class="form-group-field">
                                <label for="inputCiudad" class="form-field-label">Ciudad de residencia</label>
                                <input type="text" id="inputCiudad" class="form-field-input" value="Medellín, Colombia">
                            </div>
                        </div>

                        <div class="form-group-field">
                            <label for="inputBarrio" class="form-field-label">Barrio o comuna</label>
                            <input type="text" id="inputBarrio" class="form-field-input" value="Comuna 10 (La Candelaria)">
                        </div>

                        <div class="form-group-field">
                            <label for="inputBio" class="form-field-label">Biografía / Sobre mí</label>
                            <textarea id="inputBio" class="form-field-textarea" rows="3">Egresado alzatista apasionado por el desarrollo web y las soluciones tecnológicas con impacto social. Enfocado en la arquitectura de aplicaciones modernas, escalables y accesibles. Miembro activo de la comunidad de egresados de la I.E. Gilberto Alzate Avendaño.</textarea>
                        </div>
                    </div>

                    <!-- Pestaña 2: Información Académica y Laboral -->
                    <div class="modal-tab-panel" id="tab-academico">
                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputTitulo" class="form-field-label">Titulación o énfasis alzatista</label>
                                <input type="text" id="inputTitulo" class="form-field-input" value="Bachiller Técnico en Sistemas y Gestión Comercial">
                            </div>
                            <div class="form-group-field">
                                <label for="inputPromocion" class="form-field-label">Año de promoción</label>
                                <input type="number" id="inputPromocion" class="form-field-input" value="2021" min="1970" max="2026">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputCargo" class="form-field-label">Cargo u ocupación actual</label>
                                <input type="text" id="inputCargo" class="form-field-input" value="Desarrollador Web Frontend & Backend">
                            </div>
                            <div class="form-group-field">
                                <label for="inputEmpresa" class="form-field-label">Empresa u organización</label>
                                <input type="text" id="inputEmpresa" class="form-field-input" value="InnovaTech Medellín S.A.S.">
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña 3: Redes y Enlaces -->
                    <div class="modal-tab-panel" id="tab-enlaces">
                        <div class="form-group-field">
                            <label for="inputLinkedin" class="form-field-label">Perfil de LinkedIn (URL)</label>
                            <input type="text" id="inputLinkedin" class="form-field-input" value="linkedin.com/in/santiago-emitola">
                        </div>

                        <div class="form-group-field">
                            <label for="inputGithub" class="form-field-label">Perfil de GitHub (URL)</label>
                            <input type="text" id="inputGithub" class="form-field-input" value="github.com/santiagoemitola">
                        </div>

                        <div class="form-group-field">
                            <label for="inputWeb" class="form-field-label">Sitio web personal o portafolio</label>
                            <input type="text" id="inputWeb" class="form-field-input" value="santiagoemitola.dev">
                        </div>
                    </div>

                </div>

                <footer class="profile-modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCambios">Guardar cambios</button>
                </footer>
            </form>

        </div>
    </div>

    <!-- F. Notificación Toast Flotante -->
    <div class="profile-toast" id="toastNotificacion" role="status" aria-live="polite">
        <span class="toast-icon-check">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </span>
        <span id="toastMensaje">¡Información actualizada con éxito!</span>
    </div>

    <!-- G. Lógica JavaScript Vanilla para la Pantalla de Perfil -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Elementos del Sidebar y Topbar
            const sidebar = document.getElementById('dashboardSidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const menuToggle = document.getElementById('dashboardMenuToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            // --- 1. LÓGICA DE SIDEBAR COLAPSABLE (MINI-SIDEBAR EN ESCRITORIO) ---
            if (sidebarToggleBtn && sidebar) {
                const updateToggleState = (isCollapsed) => {
                    sidebarToggleBtn.setAttribute('aria-label', isCollapsed ? 'Expandir barra lateral' : 'Contraer barra lateral');
                    sidebarToggleBtn.setAttribute('title', isCollapsed ? 'Expandir menú' : 'Contraer menú');
                };

                // Recuperar estado desde localStorage
                try {
                    const savedCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
                    if (savedCollapsed && window.innerWidth > 768) {
                        sidebar.classList.add('is-collapsed', 'collapsed');
                        document.documentElement.classList.add('sidebar-is-collapsed');
                        updateToggleState(true);
                    }
                } catch (err) {
                    console.warn('Acceso a localStorage restringido:', err);
                }

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
                    } catch (err) {}

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

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                        toggleMobileSidebar(false);
                        menuToggle.focus();
                    }
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth > 768 && sidebar.classList.contains('is-open')) {
                        toggleMobileSidebar(false);
                    }
                });
            }

            // --- 3. TOAST NOTIFICATION HELPER ---
            const toast = document.getElementById('toastNotificacion');
            const toastMsg = document.getElementById('toastMensaje');
            let toastTimer = null;

            const mostrarToast = (mensaje) => {
                if (!toast || !toastMsg) return;
                toastMsg.textContent = mensaje;
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            };

            // --- 4. COMPARTIR PERFIL ---
            const btnCompartir = document.getElementById('btnCompartirPerfil');
            if (btnCompartir) {
                btnCompartir.addEventListener('click', async () => {
                    const url = window.location.href;
                    if (navigator.clipboard && window.isSecureContext) {
                        try {
                            await navigator.clipboard.writeText(url);
                            mostrarToast('¡Enlace de perfil copiado al portapapeles!');
                            return;
                        } catch (err) {}
                    }
                    mostrarToast('URL del perfil: ' + url);
                });
            }

            // --- 5. CAMBIAR FOTO DE PERFIL ---
            const btnEditarAvatar = document.getElementById('btnEditarAvatar');
            if (btnEditarAvatar) {
                btnEditarAvatar.addEventListener('click', () => {
                    mostrarToast('Función de actualización de fotografía lista para vinculación.');
                });
            }

            // --- 6. SWITCH DE PRIVACIDAD EN EL DIRECTORIO ---
            const toggleVisibilidad = document.getElementById('toggleVisibilidad');
            if (toggleVisibilidad) {
                toggleVisibilidad.addEventListener('change', (e) => {
                    const activo = e.target.checked;
                    mostrarToast(activo 
                        ? 'Tu perfil ahora es visible en el directorio alzatista.' 
                        : 'Tu perfil ahora está oculto en el directorio público.');
                });
            }

            // --- 7. MODAL DE EDICIÓN DE PERFIL ---
            const modal = document.getElementById('modalEditarPerfil');
            const btnAbrirModal = document.getElementById('btnAbrirModalEditar');
            const btnCerrarModal = document.getElementById('btnCerrarModal');
            const btnCancelarModal = document.getElementById('btnCancelarModal');
            const formEditar = document.getElementById('formEditarPerfil');

            const abrirModal = () => {
                if (!modal) return;
                modal.classList.add('is-active');
                document.body.style.overflow = 'hidden';
                const firstInput = document.getElementById('inputNombre');
                if (firstInput) setTimeout(() => firstInput.focus(), 100);
            };

            const cerrarModal = () => {
                if (!modal) return;
                modal.classList.remove('is-active');
                document.body.style.overflow = '';
            };

            if (btnAbrirModal) btnAbrirModal.addEventListener('click', abrirModal);
            if (btnCerrarModal) btnCerrarModal.addEventListener('click', cerrarModal);
            if (btnCancelarModal) btnCancelarModal.addEventListener('click', cerrarModal);

            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) cerrarModal();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                        cerrarModal();
                    }
                });
            }

            // Pestañas / Tabs del modal
            const tabBtns = document.querySelectorAll('.modal-tab-btn');
            const tabPanels = document.querySelectorAll('.modal-tab-panel');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanels.forEach(p => p.classList.remove('active'));

                    btn.classList.add('active');
                    const targetId = btn.getAttribute('data-tab');
                    const targetPanel = document.getElementById(targetId);
                    if (targetPanel) targetPanel.classList.add('active');
                });
            });

            // Envío del formulario de edición y actualización visual en vivo
            if (formEditar) {
                formEditar.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const nuevoNombre = document.getElementById('inputNombre')?.value.trim();
                    const nuevoEmail = document.getElementById('inputEmail')?.value.trim();
                    const nuevoTelefono = document.getElementById('inputTelefono')?.value.trim();
                    const nuevaCiudad = document.getElementById('inputCiudad')?.value.trim();
                    const nuevoBarrio = document.getElementById('inputBarrio')?.value.trim();
                    const nuevaBio = document.getElementById('inputBio')?.value.trim();
                    const nuevoTitulo = document.getElementById('inputTitulo')?.value.trim();
                    const nuevaEmpresa = document.getElementById('inputEmpresa')?.value.trim();
                    const nuevoCargo = document.getElementById('inputCargo')?.value.trim();
                    const nuevoLinkedin = document.getElementById('inputLinkedin')?.value.trim();
                    const nuevoGithub = document.getElementById('inputGithub')?.value.trim();
                    const nuevoWeb = document.getElementById('inputWeb')?.value.trim();

                    // Actualizar elementos visuales en pantalla
                    if (nuevoNombre) {
                        const nameEl = document.getElementById('profileDisplayName');
                        if (nameEl) {
                            nameEl.textContent = nuevoNombre;
                        }
                        const avatarEl = document.getElementById('profileAvatarDisplay');
                        if (avatarEl) avatarEl.textContent = nuevoNombre.charAt(0).toUpperCase();

                        // Actualizar topbar
                        const topbarName = document.querySelector('.topbar-user-name');
                        if (topbarName) topbarName.textContent = nuevoNombre;
                        const topbarAvatar = document.querySelector('.topbar-user-avatar');
                        if (topbarAvatar) topbarAvatar.textContent = nuevoNombre.charAt(0).toUpperCase();
                    }

                    if (nuevoEmail) {
                        const emailEl = document.getElementById('profileDisplayEmail');
                        if (emailEl) {
                            emailEl.textContent = nuevoEmail;
                            emailEl.href = 'mailto:' + nuevoEmail;
                        }
                    }

                    if (nuevoTelefono) {
                        const telEl = document.getElementById('profileDisplayTelefono');
                        if (telEl) telEl.textContent = nuevoTelefono;
                    }

                    if (nuevaCiudad) {
                        const ciudadEl = document.getElementById('profileDisplayCiudad');
                        if (ciudadEl) ciudadEl.textContent = nuevaCiudad;
                    }

                    if (nuevoBarrio) {
                        const barrioEl = document.getElementById('profileDisplayBarrio');
                        if (barrioEl) barrioEl.textContent = nuevoBarrio + (nuevaCiudad ? ', ' + nuevaCiudad : '');
                    }

                    if (nuevaBio) {
                        const bioEl = document.getElementById('profileDisplayBio');
                        if (bioEl) bioEl.textContent = nuevaBio;
                    }

                    if (nuevoTitulo) {
                        const headlineEl = document.getElementById('profileDisplayHeadline');
                        if (headlineEl) headlineEl.textContent = nuevoTitulo + ' • Promoción 2021';
                        const gradoEl = document.getElementById('profileDisplayGrado');
                        if (gradoEl) gradoEl.textContent = nuevoTitulo;
                    }

                    if (nuevaEmpresa) {
                        const empEl = document.getElementById('profileDisplayEmpresa');
                        if (empEl) empEl.textContent = nuevaEmpresa;
                        const empDetEl = document.getElementById('profileDisplayEmpresaDetalle');
                        if (empDetEl) empDetEl.textContent = nuevaEmpresa + ' • Jornada completa';
                    }

                    if (nuevoCargo) {
                        const cargoEl = document.getElementById('profileDisplayCargo');
                        if (cargoEl) cargoEl.textContent = nuevoCargo;
                    }

                    if (nuevoLinkedin) {
                        const linEl = document.getElementById('profileDisplayLinkedin');
                        if (linEl) linEl.textContent = nuevoLinkedin;
                    }

                    if (nuevoGithub) {
                        const gitEl = document.getElementById('profileDisplayGithub');
                        if (gitEl) gitEl.textContent = nuevoGithub;
                    }

                    if (nuevoWeb) {
                        const webEl = document.getElementById('profileDisplayWeb');
                        if (webEl) webEl.textContent = nuevoWeb;
                    }

                    cerrarModal();
                    mostrarToast('¡Perfil actualizado con éxito!');
                });
            }

            // Mostrar toast si se redirige desde la pantalla de edición
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('guardado') === '1') {
                setTimeout(() => {
                    mostrarToast('¡Perfil actualizado con éxito!');
                }, 300);
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>

    <!-- Script global del Page Loader (desvanecimiento y desmontaje) -->
    <script src="assets/js/loader.js?v=<?php echo APP_VERSION; ?>"></script>
</body>
</html>
