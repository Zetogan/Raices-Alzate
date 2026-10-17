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
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../config/config.php';

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

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Usuario');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

// Hojas de estilo y scripts adicionales gestionados por los includes globales
$extraCss = ['dashboard.css', 'oportunidades.css'];
$extraJs = ['assets/js/oportunidades.js'];

// Catálogo canónico estático de oportunidades compartido
$oportunidadesEstaticas = require __DIR__ . '/../config/oportunidades_data.php';
$oportunidades = [];

// Cargar oportunidades publicadas desde MySQL
require_once __DIR__ . '/../config/database.php';
if (!empty($pdo)) {
    try {
        $stmtOpDb = $pdo->query("
            SELECT id, titulo, empresa, publicado_por, categoria, modalidad, ubicacion, descripcion, requisitos, beneficios, enlace_postulacion, fecha_limite, creado_en
            FROM oportunidades
            WHERE estado IN ('publicado', 'aprobado')
            ORDER BY creado_en DESC
        ");
        $opRows = $stmtOpDb->fetchAll(PDO::FETCH_ASSOC);
        foreach ($opRows as $or) {
            $oportunidades[] = [
                'id'          => (int)$or['id'],
                'titulo'      => $or['titulo'],
                'empresa'     => $or['empresa'],
                'categoria'   => $or['categoria'],
                'modalidad'   => $or['modalidad'],
                'ubicacion'   => !empty($or['ubicacion']) ? $or['ubicacion'] : 'Medellín, Colombia',
                'fecha'       => !empty($or['creado_en']) ? date('d M Y', strtotime($or['creado_en'])) : 'Reciente',
                'descripcion' => $or['descripcion']
            ];
        }
    } catch (PDOException $e) {
        // Silencioso
    }
}

// Completar con las oportunidades base si no existen suficientes en BD
foreach ($oportunidadesEstaticas as $itemEst) {
    $exists = false;
    foreach ($oportunidades as $opItem) {
        if ($opItem['titulo'] === $itemEst['titulo']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $oportunidades[] = $itemEst;
    }
}

$isAdmin = function_exists('hasRole') ? hasRole('administrador') : (strtolower($userRole ?? '') === 'administrador');

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/../includes/header.php';
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
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="content-area dashboard-content oportunidades-content-wrapper" id="mainContent" tabindex="-1">
            <!-- Encabezado de la sección -->
            <header class="section-header oportunidades-header-block" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div class="oportunidades-header-titles">
                    <h1 class="section-title oportunidades-header-title">Oportunidades y convocatorias</h1>
                    <p class="section-subtitle oportunidades-header-subtitle">Encuentra convocatorias laborales, pasantías, becas y programas de formación para egresados.</p>
                </div>
                <?php if ($isAdmin): ?>
                <div class="oportunidades-admin-action">
                    <a href="index.php?page=crear-oportunidad" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 10px 18px; border-radius: 8px; text-decoration: none; background-color: #D32F2F; color: #FFFFFF; font-size: 14px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Publicar convocatoria</span>
                    </a>
                </div>
                <?php endif; ?>
            </header>

            <!-- Contenedor blanco unificado (#FFFFFF, borde #E0E0E0, radio 16px, pad 32px) -->
            <div class="oportunidades-card-container" aria-label="Catálogo de oportunidades para egresados">
                <!-- Cuadrícula de 3 columnas simétricas en Desktop -->
                <div class="oportunidades-grid" id="oportunidadesGrid">
                    <?php foreach ($oportunidades as $item): ?>
                    <!-- Tarjeta interactiva clickeable que redirige a detalle-oportunidad.php -->
                    <a href="index.php?page=detalle-oportunidad&id=<?php echo (int)$item['id']; ?>" class="oportunidad-card" data-href="index.php?page=detalle-oportunidad&id=<?php echo (int)$item['id']; ?>" aria-label="Ver detalle de <?php echo htmlspecialchars($item['titulo']); ?>">
                        <div class="oportunidad-cover-wrapper">
                            <?php
                                $cat = mb_strtolower($item['categoria'] ?? '');
                                if (strpos($cat, 'beca') !== false) {
                                    $coverImg = 'assets/img/oportunidad-beca.svg';
                                } elseif (strpos($cat, 'pasant') !== false) {
                                    $coverImg = 'assets/img/oportunidad-pasantia.svg';
                                } else {
                                    $coverImg = 'assets/img/oportunidad-empleo.svg';
                                }
                            ?>
                            <img src="<?php echo $coverImg; ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>" class="oportunidad-cover-img" style="width: 100%; height: 100%; object-fit: cover; display: block; border-top-left-radius: 8px; border-top-right-radius: 8px;">
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
