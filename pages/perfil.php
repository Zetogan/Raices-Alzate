<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Perfil del Egresado (Panel Privado)
 * Versión: 0.2.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/helpers.php';

// 1. Verificación canónica de autenticación
if (!isAuthenticated()) {
    header('Location: ' . url('index.php?page=login'));
    exit;
}

if (function_exists('hasRole') && hasRole('administrador')) {
    header('Location: index.php?page=administracion');
    exit;
}

$currentUser = currentUser();
$userId      = (int)($currentUser['id'] ?? 0);

if ($userId <= 0 || empty($pdo)) {
    header('Location: ' . url('index.php?page=login'));
    exit;
}

// Procesamiento AJAX de visibilidad en el directorio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_visibilidad') {
    $nuevoValor = !empty($_POST['perfil_publico']) ? 1 : 0;
    try {
        $stmtToggle = $pdo->prepare("UPDATE perfiles_egresados SET perfil_publico = :pub, actualizado_en = NOW() WHERE usuario_id = :uid");
        $stmtToggle->execute([':pub' => $nuevoValor, ':uid' => $userId]);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'perfil_publico' => $nuevoValor]);
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

$mensajeExito = $_SESSION['perfil_flash_success'] ?? (!empty($_GET['guardado']) ? '¡Tu perfil ha sido actualizado correctamente!' : null);
unset($_SESSION['perfil_flash_success']);

// 2. Consulta de información real del usuario autenticado
try {
    $stmtUser = $pdo->prepare("
        SELECT u.id, u.nombre_completo, u.email, u.rol_id, r.nombre AS rol_nombre, u.creado_en
        FROM usuarios u
        JOIN roles r ON r.id = u.rol_id
        WHERE u.id = :uid
        LIMIT 1
    ");
    $stmtUser->execute([':uid' => $userId]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        header('Location: ' . url('index.php?page=login'));
        exit;
    }

    $userName    = trim($userData['nombre_completo'] ?? '');
    $userEmail   = trim($userData['email'] ?? '');
    $userRole    = trim($userData['rol_nombre'] ?? 'Egresado');
    $userInitial = !empty($userName) 
        ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
        : 'U';

    $stmtPerfil = $pdo->prepare("SELECT * FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
    $stmtPerfil->execute([':uid' => $userId]);
    $perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC) ?: [];

    $skillsArray = [];
    if (!empty($perfil['id'])) {
        $stmtHab = $pdo->prepare("
            SELECT h.nombre 
            FROM habilidades h
            JOIN perfil_habilidades ph ON ph.habilidad_id = h.id
            WHERE ph.perfil_id = :pid
            ORDER BY h.nombre ASC
        ");
        $stmtHab->execute([':pid' => $perfil['id']]);
        $skillsArray = $stmtHab->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    $stmtProyCount = $pdo->prepare("SELECT COUNT(*) FROM proyectos WHERE autor_id = :uid");
    $stmtProyCount->execute([':uid' => $userId]);
    $proyectosCount = (int)$stmtProyCount->fetchColumn();

    $stmtUserProy = $pdo->prepare("
        SELECT id, titulo, descripcion, categoria, estado, creado_en 
        FROM proyectos 
        WHERE autor_id = :uid 
        ORDER BY creado_en DESC 
        LIMIT 6
    ");
    $stmtUserProy->execute([':uid' => $userId]);
    $userProyectos = $stmtUserProy->fetchAll(PDO::FETCH_ASSOC) ?: [];

    try {
        $stmtCon = $pdo->prepare("
            SELECT COUNT(*) FROM conexiones 
            WHERE (solicitante_id = :uid1 OR receptor_id = :uid2) 
              AND estado = 'aceptada'
        ");
        $stmtCon->execute([':uid1' => $userId, ':uid2' => $userId]);
        $egresadosConectadosCount = (int)$stmtCon->fetchColumn();
    } catch (PDOException $e) {
        $egresadosConectadosCount = 0;
    }

    $porcentajeCompletitud = calcularCompletitudPerfil($userData, $perfil, $skillsArray);
    $userFoto = $perfil['foto_perfil'] ?? null;

} catch (PDOException $e) {
    error_log('Error cargando perfil: ' . $e->getMessage());
    $userName                 = trim($currentUser['nombre'] ?? '');
    $userEmail                = trim($currentUser['email'] ?? '');
    $userRole                 = trim($currentUser['rol'] ?? 'Egresado');
    $userInitial              = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';
    $perfil                   = [];
    $skillsArray              = [];
    $proyectosCount           = 0;
    $userProyectos            = [];
    $egresadosConectadosCount = 0;
    $porcentajeCompletitud    = calcularCompletitudPerfil(['nombre_completo' => $userName, 'email' => $userEmail], []);
    $userFoto                 = null;
}

$pageTitle      = 'Mi perfil | ' . APP_NAME;
$topbarTitle    = 'Mi perfil';
$currentPage    = 'perfil';
$seccion_activa = 'perfil';
$extraCss       = ['dashboard.css', 'perfil.css'];

require_once __DIR__ . '/../includes/header.php';
?>

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
                            <a href="index.php?page=dashboard" class="profile-breadcrumb-link">Dashboard</a>
                        </li>
                        <li class="profile-breadcrumb-item">
                            <span class="profile-breadcrumb-sep" aria-hidden="true">/</span>
                            <span class="profile-breadcrumb-current" aria-current="page">Mi perfil</span>
                        </li>
                    </ol>
                </nav>

                <?php if (!empty($mensajeExito)): ?>
                    <div class="alert alert-success profile-flash-alert" role="alert" style="background-color: #E8F5E9; border: 1.5px solid #81C784; color: #1B5E20; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-weight: 500; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span><?php echo htmlspecialchars($mensajeExito); ?></span>
                    </div>
                <?php endif; ?>

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
                                    <?php if (!empty($perfil['foto_perfil']) && file_exists(__DIR__ . '/../' . $perfil['foto_perfil'])): ?>
                                        <img src="<?php echo htmlspecialchars($perfil['foto_perfil']); ?>" alt="<?php echo htmlspecialchars($userName); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($userInitial); ?>
                                    <?php endif; ?>
                                </div>
                                <?php $esUsuarioActivo = !empty($usuario['activo']) && (int)$usuario['activo'] === 1; ?>
                                <span class="profile-avatar-online-dot <?php echo $esUsuarioActivo ? '' : 'offline'; ?>" title="<?php echo $esUsuarioActivo ? 'Egresado en línea' : 'Cuenta inactiva'; ?>" aria-label="<?php echo $esUsuarioActivo ? 'Usuario en línea' : 'Usuario inactivo'; ?>"></span>
                                <a href="index.php?page=editar-perfil" class="profile-avatar-edit-btn" id="btnEditarAvatar" title="Actualizar foto de perfil" aria-label="Actualizar foto de perfil" style="text-decoration: none;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                </a>
                            </div>

                            <!-- Textos de Identidad y Metadatos -->
                            <div class="profile-identity-details">
                                <h1 class="profile-user-name" id="profileDisplayName">
                                    <?php echo htmlspecialchars($userName); ?>
                                </h1>
                                <?php
                                $headlineParts = [];
                                if (!empty($perfil['titulo_institucional'])) {
                                    $headlineParts[] = $perfil['titulo_institucional'];
                                }
                                if (!empty($perfil['anio_graduacion'])) {
                                    $headlineParts[] = 'Promoción ' . $perfil['anio_graduacion'];
                                }
                                $headlineText = !empty($headlineParts) ? implode(' • ', $headlineParts) : ($userRole . ' Alzatista');
                                ?>
                                <p class="profile-headline" id="profileDisplayHeadline">
                                    <?php echo htmlspecialchars($headlineText); ?>
                                </p>
                                <div class="profile-meta-pills-row">
                                    <?php if (!empty($perfil['ciudad'])): ?>
                                    <span class="profile-meta-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span id="profileDisplayCiudad"><?php echo htmlspecialchars($perfil['ciudad']); ?></span>
                                    </span>
                                    <?php endif; ?>
                                    <span class="profile-meta-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <span><?php echo htmlspecialchars($userRole); ?> Alzatista</span>
                                    </span>
                                    <?php 
                                    $trabajoPill = '';
                                    if (!empty($perfil['ocupacion']) && !empty($perfil['empresa'])) {
                                        $trabajoPill = $perfil['ocupacion'] . ' en ' . $perfil['empresa'];
                                    } elseif (!empty($perfil['ocupacion'])) {
                                        $trabajoPill = $perfil['ocupacion'];
                                    } elseif (!empty($perfil['empresa'])) {
                                        $trabajoPill = $perfil['empresa'];
                                    }
                                    ?>
                                    <?php if (!empty($trabajoPill)): ?>
                                    <span class="profile-meta-pill">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                        </svg>
                                        <span id="profileDisplayEmpresa"><?php echo htmlspecialchars($trabajoPill); ?></span>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción de Cabecera -->
                        <div class="profile-hero-actions">
                            <a href="index.php?page=editar-perfil" class="btn-profile-primary" id="btnAbrirEditarPerfil" style="text-decoration: none;">
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
                            <p class="stat-value"><?php echo (int)($proyectosCount ?? 0); ?></p>
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
                            <p class="stat-value"><?php echo !empty($perfil['anio_graduacion']) ? htmlspecialchars($perfil['anio_graduacion']) : '—'; ?></p>
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
                        <div class="stat-info" title="<?php echo !empty($egresadosConectadosCount) ? ($egresadosConectadosCount . ' egresados conectados') : 'Aún no tienes egresados conectados'; ?>">
                            <p class="stat-value"><?php echo (int)($egresadosConectadosCount ?? 0); ?></p>
                            <p class="stat-label">Egresados conectados contigo</p>
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
                            <p class="stat-value"><?php echo (int)($porcentajeCompletitud ?? 0); ?>%</p>
                            <p class="stat-label">Perfil completado</p>
                            <div class="stat-progress-bar">
                                <div class="stat-progress-fill" style="width: <?php echo (int)($porcentajeCompletitud ?? 0); ?>%;"></div>
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
                                            <span class="profile-info-label">Correo institucional</span>
                                            <a href="mailto:<?php echo htmlspecialchars($userEmail); ?>" class="profile-info-value profile-info-link" id="profileDisplayEmail">
                                                <?php echo htmlspecialchars($userEmail); ?>
                                            </a>
                                        </div>
                                    </li>

                                    <?php if (!empty($perfil['email_secundario'])): ?>
                                    <!-- Correo Secundario -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Correo secundario</span>
                                            <a href="mailto:<?php echo htmlspecialchars($perfil['email_secundario']); ?>" class="profile-info-value profile-info-link" id="profileDisplayEmailSecundario">
                                                <?php echo htmlspecialchars($perfil['email_secundario']); ?>
                                            </a>
                                        </div>
                                    </li>
                                    <?php endif; ?>

                                    <!-- Teléfono -->
                                    <li class="profile-info-item">
                                        <div class="profile-info-icon" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                            </svg>
                                        </div>
                                        <div class="profile-info-content">
                                            <span class="profile-info-label">Teléfono celular</span>
                                            <span class="profile-info-value" id="profileDisplayTelefono"><?php echo !empty($perfil['telefono']) ? htmlspecialchars($perfil['telefono']) : 'No especificado'; ?></span>
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
                                            <span class="profile-info-value" id="profileDisplayBarrio"><?php echo !empty($perfil['direccion']) ? htmlspecialchars($perfil['direccion']) : (!empty($perfil['ciudad']) ? htmlspecialchars($perfil['ciudad']) : 'No especificada'); ?></span>
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
                                            <?php
                                            $tsReg = !empty($userData['creado_en']) ? strtotime($userData['creado_en']) : null;
                                            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                            $fechaStr = $tsReg ? ($meses[(int)date('n', $tsReg) - 1] . ' de ' . date('Y', $tsReg)) : 'Reciente';
                                            ?>
                                            <span class="profile-info-value"><?php echo htmlspecialchars($fechaStr); ?></span>
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
                                    <?php 
                                    $hasSocial = false;
                                    ?>
                                    <!-- LinkedIn -->
                                    <?php if (!empty($perfil['linkedin'])): $hasSocial = true; ?>
                                    <li class="profile-social-link-item">
                                        <a href="<?php echo htmlspecialchars($perfil['linkedin']); ?>" target="_blank" rel="noopener noreferrer" id="linkLinkedin" title="Abrir perfil de LinkedIn">
                                            <div class="social-lead">
                                                <span class="social-icon-box">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                                    </svg>
                                                </span>
                                                <span id="profileDisplayLinkedin"><?php echo htmlspecialchars(preg_replace('#^https?://#', '', $perfil['linkedin'])); ?></span>
                                            </div>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <!-- GitHub -->
                                    <?php if (!empty($perfil['github'])): $hasSocial = true; ?>
                                    <li class="profile-social-link-item">
                                        <a href="<?php echo htmlspecialchars($perfil['github']); ?>" target="_blank" rel="noopener noreferrer" id="linkGithub" title="Abrir perfil de GitHub">
                                            <div class="social-lead">
                                                <span class="social-icon-box">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                                                    </svg>
                                                </span>
                                                <span id="profileDisplayGithub"><?php echo htmlspecialchars(preg_replace('#^https?://#', '', $perfil['github'])); ?></span>
                                            </div>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <!-- Portafolio / Web -->
                                    <?php if (!empty($perfil['sitio_web'])): $hasSocial = true; ?>
                                    <li class="profile-social-link-item">
                                        <a href="<?php echo htmlspecialchars($perfil['sitio_web']); ?>" target="_blank" rel="noopener noreferrer" id="linkPortafolio" title="Abrir sitio web o portafolio">
                                            <div class="social-lead">
                                                <span class="social-icon-box">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                    </svg>
                                                </span>
                                                <span id="profileDisplayWeb"><?php echo htmlspecialchars(preg_replace('#^https?://#', '', $perfil['sitio_web'])); ?></span>
                                            </div>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <!-- Red Adicional -->
                                    <?php if (!empty($perfil['red_adicional'])): $hasSocial = true; ?>
                                    <li class="profile-social-link-item">
                                        <div class="social-lead" style="padding: 10px 0;">
                                            <span class="social-icon-box">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                            </span>
                                            <span><?php echo htmlspecialchars($perfil['red_adicional']); ?></span>
                                        </div>
                                    </li>
                                    <?php endif; ?>

                                    <?php if (!$hasSocial): ?>
                                    <li style="color: #757575; font-size: 0.875rem; font-style: italic; list-style: none; padding: 6px 0;">
                                        No has añadido enlaces profesionales aún.
                                    </li>
                                    <?php endif; ?>
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
                                        <input type="checkbox" id="toggleVisibilidad" <?php echo (!isset($perfil['perfil_publico']) || (int)$perfil['perfil_publico'] === 1) ? 'checked' : ''; ?> aria-label="Alternar visibilidad del perfil en el directorio de egresados">
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
                                <?php if (!empty($perfil['bio'])): ?>
                                    <p class="profile-bio-text" id="profileDisplayBio">
                                        <?php echo nl2br(htmlspecialchars($perfil['bio'])); ?>
                                    </p>
                                <?php else: ?>
                                    <p class="profile-bio-text" id="profileDisplayBio" style="color: #757575; font-style: italic;">
                                        Aún no has agregado una presentación personal. Haz clic en "Editar perfil" para compartir tu trayectoria con la comunidad.
                                    </p>
                                <?php endif; ?>
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
                                <?php if (!empty($perfil['titulo_institucional']) || !empty($perfil['programa_superior'])): ?>
                                <div class="profile-timeline">
                                    
                                    <?php if (!empty($perfil['titulo_institucional'])): ?>
                                    <!-- Elemento 1: I.E. Gilberto Alzate Avendaño -->
                                    <div class="timeline-item">
                                        <div class="timeline-bullet" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title">I.E. Gilberto Alzate Avendaño</h3>
                                            <span class="timeline-badge-tag">Secundaria & Media</span>
                                        </div>
                                        <p class="timeline-subtitle" id="profileDisplayGrado"><?php echo htmlspecialchars($perfil['titulo_institucional']); ?></p>
                                        <?php if (!empty($perfil['anio_graduacion'])): ?>
                                        <span class="timeline-period">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            Promoción <?php echo htmlspecialchars($perfil['anio_graduacion']); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($perfil['programa_superior'])): ?>
                                    <!-- Elemento 2: Formación Superior -->
                                    <div class="timeline-item">
                                        <div class="timeline-bullet timeline-bullet-blue" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title"><?php echo htmlspecialchars($perfil['institucion_superior'] ?: 'Educación Superior'); ?></h3>
                                            <span class="timeline-badge-tag">Educación Superior</span>
                                        </div>
                                        <p class="timeline-subtitle"><?php echo htmlspecialchars($perfil['programa_superior']); ?></p>
                                    </div>
                                    <?php endif; ?>

                                </div>
                                <?php else: ?>
                                <p style="color: #757575; font-style: italic; margin: 0; padding: 6px 0;">
                                    No has registrado información académica aún.
                                </p>
                                <?php endif; ?>
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
                                <?php if (!empty($perfil['ocupacion']) || !empty($perfil['empresa'])): ?>
                                <div class="profile-timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-bullet" aria-hidden="true">
                                            <div class="timeline-bullet-inner"></div>
                                        </div>
                                        <div class="timeline-header-row">
                                            <h3 class="timeline-title" id="profileDisplayCargo"><?php echo htmlspecialchars($perfil['ocupacion'] ?: 'Ocupación profesional'); ?></h3>
                                            <span class="timeline-badge-tag" style="background-color: #E8F5E9; color: #2E7D32;">Actual</span>
                                        </div>
                                        <?php if (!empty($perfil['empresa'])): ?>
                                        <p class="timeline-subtitle" id="profileDisplayEmpresaDetalle"><?php echo htmlspecialchars($perfil['empresa']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($perfil['area_especialidad']) || !empty($perfil['anios_experiencia'])): ?>
                                        <span class="timeline-period">
                                            <?php 
                                            $expItems = [];
                                            if (!empty($perfil['area_especialidad'])) $expItems[] = $perfil['area_especialidad'];
                                            if (!empty($perfil['anios_experiencia'])) $expItems[] = $perfil['anios_experiencia'] . ' años de experiencia';
                                            echo htmlspecialchars(implode(' • ', $expItems));
                                            ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p style="color: #757575; font-style: italic; margin: 0; padding: 6px 0;">
                                    No has registrado experiencia laboral o profesional aún.
                                </p>
                                <?php endif; ?>
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
                                    <?php if (!empty($skillsArray)): ?>
                                        <?php foreach ($skillsArray as $idx => $skill): ?>
                                            <span class="skill-chip <?php echo $idx % 3 === 0 ? 'skill-chip-primary' : ($idx % 3 === 1 ? 'skill-chip-blue' : ''); ?>">
                                                <?php echo htmlspecialchars($skill); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p style="color: #757575; font-style: italic; margin: 0; padding: 6px 0;">
                                            No has agregado habilidades aún. Puedes agregarlas editando tu perfil.
                                        </p>
                                    <?php endif; ?>
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
                                <a href="index.php?page=proyectos&view=panel" class="profile-card-action-btn" title="Ver catálogo completo de proyectos">
                                    Ver todos &rarr;
                                </a>
                            </header>
                            <div class="profile-card-body">
                                <?php if (!empty($userProyectos)): ?>
                                <div class="profile-projects-grid">
                                    <?php foreach ($userProyectos as $proy): ?>
                                    <div class="profile-project-card" data-href="index.php?page=detalle-proyecto&id=<?php echo (int)$proy['id']; ?>" role="link" tabindex="0" aria-label="Ver detalle del proyecto <?php echo htmlspecialchars($proy['titulo']); ?>">
                                        <div class="project-card-content">
                                            <div class="project-card-header">
                                                <span class="project-tag project-tag-tech"><?php echo htmlspecialchars($proy['categoria'] ?? 'Proyecto'); ?></span>
                                            </div>
                                            <h3 class="project-card-title">
                                                <a href="index.php?page=detalle-proyecto&id=<?php echo (int)$proy['id']; ?>" class="project-title-link"><?php echo htmlspecialchars($proy['titulo']); ?></a>
                                            </h3>
                                            <p class="project-card-desc">
                                                <?php echo htmlspecialchars($proy['descripcion'] ?? ''); ?>
                                            </p>
                                        </div>
                                        <div class="project-card-footer">
                                            <a href="index.php?page=detalle-proyecto&id=<?php echo (int)$proy['id']; ?>" class="project-card-link" onclick="event.stopPropagation();">
                                                Consultar proyecto &rarr;
                                            </a>
                                            <a href="index.php?page=editar-proyecto&id=<?php echo (int)$proy['id']; ?>" class="btn-editar-proyecto" title="Editar proyecto" onclick="event.stopPropagation();">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-right: 4px;">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                <span>Editar</span>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <div style="text-align: center; padding: 24px 16px; color: #757575;">
                                    <p style="margin: 0 0 8px 0; font-size: 0.95rem;">Aún no has publicado proyectos en la plataforma.</p>
                                </div>
                                <?php endif; ?>

                                <div class="profile-projects-footer">
                                    <a href="index.php?page=crear-proyecto" class="btn-publicar-proyecto btn-outline-secondary" id="btnPublicarNuevoProyecto">
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

            <form action="index.php?page=editar-perfil" method="POST" class="profile-modal-form" id="formEditarPerfil">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
                <div class="profile-modal-body">
                    
                    <!-- Pestaña 1: Datos Personales -->
                    <div class="modal-tab-panel active" id="tab-personal">
                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputNombre" class="form-field-label">Nombre completo *</label>
                                <input type="text" id="inputNombre" name="nombre" class="form-field-input" value="<?php echo htmlspecialchars($userName); ?>" required>
                            </div>
                            <div class="form-group-field">
                                <label for="inputEmail" class="form-field-label">Correo electrónico *</label>
                                <input type="email" id="inputEmail" name="email" class="form-field-input" value="<?php echo htmlspecialchars($userEmail); ?>" readonly style="background-color: #F5F5F5; cursor: not-allowed;">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputTelefono" class="form-field-label">Teléfono celular</label>
                                <input type="tel" id="inputTelefono" name="telefono" class="form-field-input" value="<?php echo htmlspecialchars($perfil['telefono'] ?? ''); ?>">
                            </div>
                            <div class="form-group-field">
                                <label for="inputCiudad" class="form-field-label">Ciudad de residencia</label>
                                <input type="text" id="inputCiudad" name="ciudad" class="form-field-input" value="<?php echo htmlspecialchars($perfil['ciudad'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group-field">
                            <label for="inputBarrio" class="form-field-label">Barrio o comuna</label>
                            <input type="text" id="inputBarrio" name="direccion" class="form-field-input" value="<?php echo htmlspecialchars($perfil['direccion'] ?? ''); ?>">
                        </div>

                        <div class="form-group-field">
                            <label for="inputBio" class="form-field-label">Biografía / Sobre mí</label>
                            <textarea id="inputBio" name="bio" class="form-field-textarea" rows="3"><?php echo htmlspecialchars($perfil['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Pestaña 2: Información Académica y Laboral -->
                    <div class="modal-tab-panel" id="tab-academico">
                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputTitulo" class="form-field-label">Titulación o énfasis alzatista</label>
                                <input type="text" id="inputTitulo" name="titulo_institucional" class="form-field-input" value="<?php echo htmlspecialchars($perfil['titulo_institucional'] ?? ''); ?>">
                            </div>
                            <div class="form-group-field">
                                <label for="inputPromocion" class="form-field-label">Año de promoción</label>
                                <input type="number" id="inputPromocion" name="anio_graduacion" class="form-field-input" value="<?php echo htmlspecialchars($perfil['anio_graduacion'] ?? ''); ?>" min="1950" max="2100">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group-field">
                                <label for="inputCargo" class="form-field-label">Cargo u ocupación actual</label>
                                <input type="text" id="inputCargo" name="ocupacion" class="form-field-input" value="<?php echo htmlspecialchars($perfil['ocupacion'] ?? ''); ?>">
                            </div>
                            <div class="form-group-field">
                                <label for="inputEmpresa" class="form-field-label">Empresa u organización</label>
                                <input type="text" id="inputEmpresa" name="empresa" class="form-field-input" value="<?php echo htmlspecialchars($perfil['empresa'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña 3: Redes y Enlaces -->
                    <div class="modal-tab-panel" id="tab-enlaces">
                        <div class="form-group-field">
                            <label for="inputLinkedin" class="form-field-label">Perfil de LinkedIn (URL)</label>
                            <input type="text" id="inputLinkedin" name="linkedin" class="form-field-input" value="<?php echo htmlspecialchars($perfil['linkedin'] ?? ''); ?>">
                        </div>

                        <div class="form-group-field">
                            <label for="inputGithub" class="form-field-label">Perfil de GitHub (URL)</label>
                            <input type="text" id="inputGithub" name="github" class="form-field-input" value="<?php echo htmlspecialchars($perfil['github'] ?? ''); ?>">
                        </div>

                        <div class="form-group-field">
                            <label for="inputWeb" class="form-field-label">Sitio web personal o portafolio</label>
                            <input type="text" id="inputWeb" name="web" class="form-field-input" value="<?php echo htmlspecialchars($perfil['sitio_web'] ?? ''); ?>">
                        </div>
                    </div>

                </div>

                <footer class="profile-modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="btnCancelarModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-perfil-save" id="btnGuardarCambios">Guardar cambios</button>
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
                    const userNameEl = document.getElementById('profileDisplayName');
                    const userName = userNameEl ? userNameEl.textContent.trim() : 'Egresado Alzatista';

                    if (navigator.share) {
                        try {
                            await navigator.share({
                                title: `Perfil de ${userName} | Raíces Alzate`,
                                text: `Conoce el perfil de ${userName} en la comunidad de egresados de la I.E. Gilberto Alzate Avendaño.`,
                                url: url
                            });
                            return;
                        } catch (err) {
                            if (err.name === 'AbortError') return;
                        }
                    }

                    // Fallback a portapapeles
                    if (navigator.clipboard && window.isSecureContext) {
                        try {
                            await navigator.clipboard.writeText(url);
                            mostrarToast('¡Enlace de perfil copiado al portapapeles!');
                            return;
                        } catch (err) {}
                    }

                    // Fallback para entornos sin API de portapapeles
                    try {
                        const tempInput = document.createElement('textarea');
                        tempInput.value = url;
                        tempInput.style.position = 'fixed';
                        tempInput.style.opacity = '0';
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                        mostrarToast('¡Enlace de perfil copiado al portapapeles!');
                    } catch (err) {
                        mostrarToast('URL del perfil: ' + url);
                    }
                });
            }

            // --- 5. CAMBIAR FOTO DE PERFIL: el enlace navega de forma nativa a editar-perfil.php ---

            // --- 6. SWITCH DE PRIVACIDAD EN EL DIRECTORIO ---
            const toggleVisibilidad = document.getElementById('toggleVisibilidad');
            if (toggleVisibilidad) {
                toggleVisibilidad.addEventListener('change', async (e) => {
                    const activo = e.target.checked;
                    try {
                        const fd = new FormData();
                        fd.append('action', 'toggle_visibilidad');
                        fd.append('perfil_publico', activo ? '1' : '0');
                        await fetch('index.php?page=perfil', {
                            method: 'POST',
                            body: fd
                        });
                        mostrarToast(activo 
                            ? 'Tu perfil ahora es visible en el directorio alzatista.' 
                            : 'Tu perfil ahora está oculto en el directorio de egresados.');
                    } catch (err) {
                        mostrarToast(activo 
                            ? 'Tu perfil ahora es visible en el directorio alzatista.' 
                            : 'Tu perfil ahora está oculto en el directorio de egresados.');
                    }
                });
            }

            // --- 6.1 INTERACTIVIDAD EN TARJETAS DE PROYECTOS ("Mis proyectos en Raíces Alzate") ---
            document.addEventListener('click', (e) => {
                const card = e.target.closest('.profile-project-card');
                if (!card) return;

                // Si el clic fue en el botón de edición o dentro de él, detener
                if (e.target.closest('.btn-editar-proyecto') || e.target.closest('.btn-editar-card')) {
                    return;
                }

                // Si el clic fue en el enlace explícito de consultar proyecto, permitir acción nativa
                if (e.target.closest('.project-card-link')) {
                    return;
                }

                // Navegación nativa ante cualquier clic sobre la tarjeta hacia detalle de proyecto
                const href = card.getAttribute('data-href') || 'index.php?page=detalle-proyecto';
                if (href) {
                    window.location.href = href;
                }
            });

            // Accesibilidad mediante teclado en tarjetas (Enter para navegar a detalle)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const activeCard = document.activeElement;
                    if (activeCard && activeCard.classList.contains('profile-project-card')) {
                        const href = activeCard.getAttribute('data-href') || 'index.php?page=detalle-proyecto';
                        window.location.href = href;
                    }
                }
            });

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

            if (btnAbrirModal) {
                btnAbrirModal.addEventListener('click', (e) => {
                    e.preventDefault();
                    abrirModal();
                });
            }
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

            // Envío real del formulario de edición hacia el backend
            if (formEditar) {
                formEditar.addEventListener('submit', () => {
                    const btn = document.getElementById('btnGuardarCambios');
                    if (btn) {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.85';
                        btn.textContent = 'Actualizando...';
                    }
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

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
