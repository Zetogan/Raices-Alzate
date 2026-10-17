<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Detalle de Egresado (detalle-egresado.php)
 * Conectado completamente a la base de datos MySQL
 * Diseño 100% alineado con perfil.php y especificación visual institucional
 * Versión: 0.3.0
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$seccion_activa = 'egresados';
$titulo_seccion = 'Perfil de egresado';

// 1. Datos del usuario autenticado
$currentUser   = currentUser();
$currentUserId = (int)($currentUser['id'] ?? 0);

$userName    = trim($currentUser['nombre_completo'] ?? $currentUser['nombre'] ?? 'Usuario Alzatista');
$userRole    = trim($currentUser['rol_nombre'] ?? $currentUser['rol'] ?? 'Egresado');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';

// 2. ID del egresado a consultar
$egresadoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($egresadoId <= 0) {
    $egresadoId = $currentUserId > 0 ? $currentUserId : 19;
}

// 3. Cargar datos del egresado desde MySQL
$targetUser = null;
$skills = [];
$proyectos = [];
$totalRedConexiones = 0;
$conexionEstado = 'no_conectado';
$esSolicitante = false;

if (!empty($pdo)) {
    try {
        $stmtTarget = $pdo->prepare("
            SELECT 
                u.id,
                u.nombre_completo,
                u.email,
                u.activo,
                u.ultimo_acceso,
                u.creado_en,
                r.nombre AS rol_nombre,
                pe.id AS perfil_id,
                pe.documento,
                pe.bio,
                pe.genero,
                pe.ciudad,
                pe.direccion,
                pe.telefono,
                pe.email_secundario,
                pe.titulo_institucional,
                pe.anio_graduacion,
                pe.programa_superior,
                pe.institucion_superior,
                pe.ocupacion,
                pe.empresa,
                pe.area_especialidad,
                pe.anios_experiencia,
                pe.linkedin,
                pe.github,
                pe.sitio_web,
                pe.foto_perfil
            FROM usuarios u
            JOIN roles r ON r.id = u.rol_id
            LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
            WHERE u.id = :id AND u.activo = 1
            LIMIT 1
        ");
        $stmtTarget->execute([':id' => $egresadoId]);
        $targetUser = $stmtTarget->fetch(PDO::FETCH_ASSOC);

        // Fallback si no existe: primer egresado activo
        if (!$targetUser) {
            $stmtFallback = $pdo->query("
                SELECT u.id FROM usuarios u 
                JOIN roles r ON r.id = u.rol_id 
                WHERE (r.nombre = 'egresado' OR r.nombre = 'estudiante') AND u.activo = 1 
                ORDER BY u.id ASC LIMIT 1
            ");
            $fallbackId = $stmtFallback->fetchColumn();
            if ($fallbackId) {
                $stmtTarget->execute([':id' => (int)$fallbackId]);
                $targetUser = $stmtTarget->fetch(PDO::FETCH_ASSOC);
                $egresadoId = (int)$fallbackId;
            }
        }

        if ($targetUser) {
            $perfilId = (int)($targetUser['perfil_id'] ?? 0);

            // Habilidades asociadas
            if ($perfilId > 0) {
                $stmtSkills = $pdo->prepare("
                    SELECT h.nombre 
                    FROM perfil_habilidades ph
                    JOIN habilidades h ON h.id = ph.habilidad_id
                    WHERE ph.perfil_id = :pid
                    ORDER BY h.nombre ASC
                ");
                $stmtSkills->execute([':pid' => $perfilId]);
                $skills = $stmtSkills->fetchAll(PDO::FETCH_COLUMN) ?: [];
            }

            // Proyectos publicados
            $stmtProy = $pdo->prepare("
                SELECT id, titulo, descripcion, categoria, creado_en, estado 
                FROM proyectos 
                WHERE autor_id = :uid AND estado = 'publicado'
                ORDER BY creado_en DESC
                LIMIT 6
            ");
            $stmtProy->execute([':uid' => $egresadoId]);
            $proyectos = $stmtProy->fetchAll(PDO::FETCH_ASSOC) ?: [];

            // Red de conexiones aceptadas
            $stmtNet = $pdo->prepare("
                SELECT COUNT(*) FROM conexiones 
                WHERE (solicitante_id = :uid1 OR receptor_id = :uid2) AND estado = 'aceptada'
            ");
            $stmtNet->execute([
                ':uid1' => $egresadoId,
                ':uid2' => $egresadoId
            ]);
            $totalRedConexiones = (int)$stmtNet->fetchColumn();

            // Estado de conexión respecto al usuario en sesión
            if ($currentUserId > 0) {
                if ($currentUserId === $egresadoId) {
                    $conexionEstado = 'propio';
                } else {
                    $stmtConCheck = $pdo->prepare("
                        SELECT id, solicitante_id, receptor_id, estado 
                        FROM conexiones 
                        WHERE (solicitante_id = :cur1 AND receptor_id = :tgt1) 
                           OR (solicitante_id = :tgt2 AND receptor_id = :cur2)
                        LIMIT 1
                    ");
                    $stmtConCheck->execute([
                        ':cur1' => $currentUserId,
                        ':tgt1' => $egresadoId,
                        ':tgt2' => $egresadoId,
                        ':cur2' => $currentUserId
                    ]);
                    $conRecord = $stmtConCheck->fetch(PDO::FETCH_ASSOC);
                    if ($conRecord) {
                        if ($conRecord['estado'] === 'aceptada') {
                            $conexionEstado = 'conectado';
                        } elseif ($conRecord['estado'] === 'pendiente') {
                            if ((int)$conRecord['solicitante_id'] === $currentUserId) {
                                $conexionEstado = 'solicitud_enviada';
                                $esSolicitante = true;
                            } else {
                                $conexionEstado = 'solicitud_recibida';
                                $esSolicitante = false;
                            }
                        }
                    }
                }
            }
        }
    } catch (PDOException $e) {
        error_log('Error en detalle-egresado.php: ' . $e->getMessage());
    }
}

// Fallback por si la base de datos estuviera inaccesible
if (!$targetUser) {
    $targetUser = [
        'id' => 19,
        'nombre_completo' => 'Valentina Ríos Gómez',
        'email' => 'valentina.rios@ejemplo.com',
        'creado_en' => '2024-03-01 10:00:00',
        'documento' => '1036000000',
        'telefono' => '+57 300 123 4567',
        'ciudad' => 'Medellín',
        'direccion' => 'Comuna 10 (La Candelaria), Medellín',
        'titulo_institucional' => 'Bachiller Técnico en Sistemas e Informática',
        'anio_graduacion' => 2021,
        'ocupacion' => 'Ingeniera de Software',
        'empresa' => 'Mercado Libre',
        'bio' => 'Egresada alzatista apasionada por el desarrollo web y las soluciones tecnológicas con impacto social.',
        'linkedin' => 'https://linkedin.com/in/valentina-rios',
        'github' => 'https://github.com/valentina-rios',
        'sitio_web' => 'https://valentinarios.dev'
    ];
    $skills = ['PHP', 'React', 'MySQL', 'JavaScript', 'HTML5 & CSS3'];
}

$nombreCompleto = trim($targetUser['nombre_completo'] ?? 'Egresado Alzatista');
$inicial = !empty($nombreCompleto) ? mb_strtoupper(mb_substr($nombreCompleto, 0, 1, 'UTF-8'), 'UTF-8') : 'E';
$tituloInstitucional = !empty($targetUser['titulo_institucional']) ? $targetUser['titulo_institucional'] : 'Bachiller Institucional';
$promocion = !empty($targetUser['anio_graduacion']) ? (string)$targetUser['anio_graduacion'] : '';
$rolNombre = ucfirst(trim($targetUser['rol_nombre'] ?? 'Egresado'));

$headlineParts = [];
if (!empty($tituloInstitucional)) {
    $headlineParts[] = $tituloInstitucional;
}
if (!empty($promocion)) {
    $headlineParts[] = 'Promoción ' . $promocion;
}
$headlineText = !empty($headlineParts) ? implode(' • ', $headlineParts) : ($rolNombre . ' Alzatista');

$ocupacion = !empty($targetUser['ocupacion']) ? $targetUser['ocupacion'] : '';
$empresa = !empty($targetUser['empresa']) ? $targetUser['empresa'] : '';
$ocupacionEmpresa = '';
if (!empty($ocupacion) && !empty($empresa)) {
    $ocupacionEmpresa = $ocupacion . ' en ' . $empresa;
} elseif (!empty($ocupacion)) {
    $ocupacionEmpresa = $ocupacion;
} elseif (!empty($empresa)) {
    $ocupacionEmpresa = $empresa;
}

$ubicacion = !empty($targetUser['direccion']) ? $targetUser['direccion'] : (!empty($targetUser['ciudad']) ? $targetUser['ciudad'] : 'Medellín, Colombia');
$email = $targetUser['email'] ?? 'correo@ejemplo.com';
$telefono = !empty($targetUser['telefono']) ? $targetUser['telefono'] : 'No especificado';

// Determinación precisa del estado de actividad / en línea
$isUserActive = !empty($targetUser['activo']) && (int)$targetUser['activo'] === 1;
$isOnline = false;
if ($isUserActive) {
    if ((int)$currentUserId === (int)$egresadoId) {
        $isOnline = true;
    } elseif (!empty($targetUser['ultimo_acceso'])) {
        $lastAccessTs = strtotime($targetUser['ultimo_acceso']);
        if ($lastAccessTs && (time() - $lastAccessTs) <= 1800) {
            $isOnline = true;
        }
    }
}

// Fecha de vinculación legible
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$tsVinculacion = !empty($targetUser['creado_en']) ? strtotime($targetUser['creado_en']) : time();
$mesNum = (int)date('n', $tsVinculacion);
$fechaVinculacion = ($meses[$mesNum] ?? 'Enero') . ' de ' . date('Y', $tsVinculacion);

// Porcentaje de completitud real
$porcentajeCompletitud = calcularCompletitudPerfil($targetUser, $targetUser, $skills);

// Foto de perfil comprobada en disco
$targetFotoUrl = '';
if (!empty($targetUser['foto_perfil'])) {
    $cleanPath = ltrim($targetUser['foto_perfil'], '/');
    if (file_exists(__DIR__ . '/' . $cleanPath)) {
        $targetFotoUrl = $cleanPath;
    }
}

$titulo_pagina = 'Perfil de ' . htmlspecialchars($nombreCompleto) . ' — Raíces Alzate';
$pageTitle = $titulo_pagina;
$currentPage = 'egresados';
$extraCss = ['dashboard.css', 'perfil.css'];

require_once __DIR__ . '/../includes/header.php';
?>

<script>
    try {
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    } catch(e) {}
</script>

<!-- Token CSRF para peticiones seguras -->
<input type="hidden" id="csrfTokenEgresados" value="<?php echo htmlspecialchars(function_exists('getCsrfToken') ? getCsrfToken() : ($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
    
    <div class="dashboard-main-area">
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="dashboard-content profile-content-wrapper" id="profileMainContent" tabindex="-1">
            
            <!-- A. Miga de pan / Breadcrumb idéntica a perfil.php -->
            <nav class="profile-breadcrumb-nav" aria-label="Ruta de navegación">
                <ol class="profile-breadcrumb-list">
                    <li class="profile-breadcrumb-item">
                        <a href="index.php?page=dashboard" class="profile-breadcrumb-link">Dashboard</a>
                    </li>
                    <li class="profile-breadcrumb-item">
                        <span class="profile-breadcrumb-sep" aria-hidden="true">/</span>
                        <a href="index.php?page=egresados" class="profile-breadcrumb-link">Egresados</a>
                    </li>
                    <li class="profile-breadcrumb-item">
                        <span class="profile-breadcrumb-sep" aria-hidden="true">/</span>
                        <span class="profile-breadcrumb-current" aria-current="page"><?= htmlspecialchars($nombreCompleto) ?></span>
                    </li>
                </ol>
            </nav>

            <!-- B. Tarjeta Hero del Perfil (Portada, Avatar, Identidad y Acciones) -->
            <section class="profile-hero-card" id="profileHeroSection" aria-label="Cabecera del perfil de <?= htmlspecialchars($nombreCompleto) ?>">
                <!-- Portada institucional con degradado y badge oficial -->
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
                        <!-- Avatar con foto real del usuario o inicial grande -->
                        <div class="profile-avatar-container">
                            <div class="profile-avatar-large" id="profileAvatarDisplay" aria-hidden="true">
                                <?php if (!empty($targetFotoUrl)): ?>
                                    <img src="<?= htmlspecialchars($targetFotoUrl) ?>" alt="<?= htmlspecialchars($nombreCompleto) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <span><?= htmlspecialchars($inicial) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($isOnline): ?>
                                <span class="profile-avatar-online-dot" title="Egresado en línea" aria-label="Usuario en línea"></span>
                            <?php elseif ($isUserActive): ?>
                                <span class="profile-avatar-online-dot offline" title="Egresado desconectado" aria-label="Usuario desconectado"></span>
                            <?php endif; ?>
                        </div>

                        <!-- Textos de Identidad y Metadatos -->
                        <div class="profile-identity-details">
                            <h1 class="profile-user-name" id="profileDisplayName">
                                <?= htmlspecialchars($nombreCompleto) ?>
                            </h1>
                            <p class="profile-headline" id="profileDisplayHeadline">
                                <?= htmlspecialchars($headlineText) ?>
                            </p>
                            <div class="profile-meta-pills-row">
                                <?php if (!empty($ubicacion)): ?>
                                <span class="profile-meta-pill">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span><?= htmlspecialchars($ubicacion) ?></span>
                                </span>
                                <?php endif; ?>
                                
                                <span class="profile-meta-pill">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    <span><?= htmlspecialchars($rolNombre) ?> Alzatista</span>
                                </span>

                                <?php if (!empty($ocupacionEmpresa)): ?>
                                <span class="profile-meta-pill">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    <span><?= htmlspecialchars($ocupacionEmpresa) ?></span>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción de Cabecera -->
                    <div class="profile-hero-actions">
                        <?php if ($conexionEstado === 'propio'): ?>
                            <a href="index.php?page=perfil" class="btn-profile-primary" id="btnEditarPerfil" style="text-decoration: none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Editar mi perfil
                            </a>
                        <?php elseif ($conexionEstado === 'conectado'): ?>
                            <a href="index.php?page=mensajes&contacto=<?= (int)$egresadoId ?>" class="btn-profile-primary" id="btnEnviarMensaje" style="text-decoration: none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                Enviar mensaje
                            </a>
                        <?php elseif ($conexionEstado === 'solicitud_enviada'): ?>
                            <button type="button" class="btn-profile-secondary" id="btnConectarEgresado" disabled title="Solicitud pendiente de respuesta">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Solicitud enviada
                            </button>
                        <?php elseif ($conexionEstado === 'solicitud_recibida'): ?>
                            <button type="button" class="btn-profile-primary" id="btnConectarEgresado" data-id="<?= $egresadoId ?>" onclick="conectarConEgresado(this, <?= $egresadoId ?>)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Aceptar conexión
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn-profile-primary" id="btnConectarEgresado" data-id="<?= $egresadoId ?>" onclick="conectarConEgresado(this, <?= $egresadoId ?>)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7.5" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                Conectar
                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn-profile-secondary" id="btnCompartirPerfil" onclick="compartirPerfilUrl();">
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

            <!-- C. Fila de 4 Métricas / Resumen Rápido -->
            <section class="profile-stats-grid" aria-label="Estadísticas de <?= htmlspecialchars($nombreCompleto) ?>">
                <!-- Stat 1: Proyectos -->
                <article class="profile-stat-card">
                    <div class="stat-icon-wrap stat-icon-red">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <p class="stat-value"><?= count($proyectos) ?></p>
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
                        <p class="stat-value"><?= !empty($promocion) ? htmlspecialchars($promocion) : '—' ?></p>
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
                        <p class="stat-value"><?= (int)$totalRedConexiones ?></p>
                        <p class="stat-label">Egresados en su red</p>
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
                        <p class="stat-value"><?= (int)$porcentajeCompletitud ?>%</p>
                        <p class="stat-label">Perfil completado</p>
                        <div class="stat-progress-bar">
                            <div class="stat-progress-fill" style="width: <?= (int)$porcentajeCompletitud ?>%;"></div>
                        </div>
                    </div>
                </article>
            </section>

            <!-- D. Distribución Principal a Dos Columnas -->
            <div class="profile-main-layout">
                
                <!-- Columna Izquierda (Contacto y Redes) -->
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
                                <!-- Correo Institucional -->
                                <li class="profile-info-item">
                                    <div class="profile-info-icon" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </div>
                                    <div class="profile-info-content">
                                        <span class="profile-info-label">Correo institucional</span>
                                        <a href="mailto:<?= htmlspecialchars($email) ?>" class="profile-info-value profile-info-link">
                                            <?= htmlspecialchars($email) ?>
                                        </a>
                                    </div>
                                </li>

                                <?php if (!empty($targetUser['email_secundario'])): ?>
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
                                        <a href="mailto:<?= htmlspecialchars($targetUser['email_secundario']) ?>" class="profile-info-value profile-info-link">
                                            <?= htmlspecialchars($targetUser['email_secundario']) ?>
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
                                        <span class="profile-info-value"><?= htmlspecialchars($telefono) ?></span>
                                    </div>
                                </li>


                                <!-- Ubicación y Barrio -->
                                <li class="profile-info-item">
                                    <div class="profile-info-icon" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </div>
                                    <div class="profile-info-content">
                                        <span class="profile-info-label">Ubicación y barrio</span>
                                        <span class="profile-info-value"><?= htmlspecialchars($ubicacion) ?></span>
                                    </div>
                                </li>

                                <!-- Fecha de Vinculación -->
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
                                        <span class="profile-info-value"><?= htmlspecialchars($fechaVinculacion) ?></span>
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
                                if (!empty($targetUser['linkedin'])): 
                                    $hasSocial = true; ?>
                                <li class="profile-social-link-item">
                                    <a href="<?= htmlspecialchars($targetUser['linkedin']) ?>" target="_blank" rel="noopener noreferrer" title="Abrir perfil de LinkedIn">
                                        <div class="social-lead">
                                            <span class="social-icon-box">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                                                </svg>
                                            </span>
                                            <span><?= htmlspecialchars(preg_replace('#^https?://#', '', $targetUser['linkedin'])) ?></span>
                                        </div>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if (!empty($targetUser['github'])): 
                                    $hasSocial = true; ?>
                                <li class="profile-social-link-item">
                                    <a href="<?= htmlspecialchars($targetUser['github']) ?>" target="_blank" rel="noopener noreferrer" title="Abrir perfil de GitHub">
                                        <div class="social-lead">
                                            <span class="social-icon-box">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                                                </svg>
                                            </span>
                                            <span><?= htmlspecialchars(preg_replace('#^https?://#', '', $targetUser['github'])) ?></span>
                                        </div>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if (!empty($targetUser['sitio_web'])): 
                                    $hasSocial = true; ?>
                                <li class="profile-social-link-item">
                                    <a href="<?= htmlspecialchars($targetUser['sitio_web']) ?>" target="_blank" rel="noopener noreferrer" title="Abrir sitio web o portafolio">
                                        <div class="social-lead">
                                            <span class="social-icon-box">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                </svg>
                                            </span>
                                            <span><?= htmlspecialchars(preg_replace('#^https?://#', '', $targetUser['sitio_web'])) ?></span>
                                        </div>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </a>
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

                </div>

                <!-- Columna Derecha (Sobre mí, Formación, Experiencia, Habilidades y Proyectos) -->
                <div class="profile-column-right">

                    <!-- Tarjeta 3: Sobre mí -->
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
                            <?php if (!empty($targetUser['bio'])): ?>
                                <p class="profile-bio-text">
                                    <?= nl2br(htmlspecialchars($targetUser['bio'])) ?>
                                </p>
                            <?php else: ?>
                                <p class="profile-bio-text" style="color: #757575; font-style: italic;">
                                    Aún no ha agregado una presentación personal.
                                </p>
                            <?php endif; ?>
                        </div>
                    </article>

                    <!-- Tarjeta 4: Formación Académica -->
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
                            <?php if (!empty($tituloInstitucional) || !empty($targetUser['programa_superior'])): ?>
                            <div class="profile-timeline">
                                
                                <?php if (!empty($tituloInstitucional)): ?>
                                <!-- Elemento 1: I.E. Gilberto Alzate Avendaño -->
                                <div class="timeline-item">
                                    <div class="timeline-bullet" aria-hidden="true">
                                        <div class="timeline-bullet-inner"></div>
                                    </div>
                                    <div class="timeline-header-row">
                                        <h3 class="timeline-title">I.E. Gilberto Alzate Avendaño</h3>
                                        <span class="timeline-badge-tag">Secundaria & Media</span>
                                    </div>
                                    <p class="timeline-subtitle"><?= htmlspecialchars($tituloInstitucional) ?></p>
                                    <?php if (!empty($promocion)): ?>
                                    <span class="timeline-period">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        Promoción <?= htmlspecialchars($promocion) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($targetUser['programa_superior']) || !empty($targetUser['institucion_superior'])): ?>
                                <!-- Elemento 2: Formación Superior -->
                                <div class="timeline-item">
                                    <div class="timeline-bullet timeline-bullet-blue" aria-hidden="true">
                                        <div class="timeline-bullet-inner"></div>
                                    </div>
                                    <div class="timeline-header-row">
                                        <h3 class="timeline-title"><?= htmlspecialchars($targetUser['institucion_superior'] ?: 'Educación Superior') ?></h3>
                                        <span class="timeline-badge-tag">Educación Superior</span>
                                    </div>
                                    <p class="timeline-subtitle"><?= htmlspecialchars($targetUser['programa_superior'] ?: 'Formación Profesional') ?></p>
                                </div>
                                <?php endif; ?>

                            </div>
                            <?php else: ?>
                            <p style="color: #757575; font-style: italic; margin: 0; padding: 6px 0;">
                                No ha registrado información académica aún.
                            </p>
                            <?php endif; ?>
                        </div>
                    </article>

                    <!-- Tarjeta 5: Experiencia Laboral y Profesional -->
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
                            <?php if (!empty($targetUser['ocupacion']) || !empty($targetUser['empresa'])): ?>
                            <div class="profile-timeline">
                                <div class="timeline-item">
                                    <div class="timeline-bullet" aria-hidden="true">
                                        <div class="timeline-bullet-inner"></div>
                                    </div>
                                    <div class="timeline-header-row">
                                        <h3 class="timeline-title"><?= htmlspecialchars($targetUser['ocupacion'] ?: 'Ocupación profesional') ?></h3>
                                        <span class="timeline-badge-tag" style="background-color: #E8F5E9; color: #2E7D32;">Actual</span>
                                    </div>
                                    <?php if (!empty($targetUser['empresa'])): ?>
                                    <p class="timeline-subtitle"><?= htmlspecialchars($targetUser['empresa']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($targetUser['area_especialidad']) || !empty($targetUser['anios_experiencia'])): ?>
                                    <span class="timeline-period">
                                        <?php 
                                        $expItems = [];
                                        if (!empty($targetUser['area_especialidad'])) $expItems[] = $targetUser['area_especialidad'];
                                        if (!empty($targetUser['anios_experiencia'])) $expItems[] = $targetUser['anios_experiencia'] . ' años de experiencia';
                                        echo htmlspecialchars(implode(' • ', $expItems));
                                        ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?>
                            <p style="color: #757575; font-style: italic; margin: 0; padding: 6px 0;">
                                No ha registrado experiencia laboral o profesional aún.
                            </p>
                            <?php endif; ?>
                        </div>
                    </article>

                    <!-- Tarjeta 6: Habilidades y Competencias -->
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
                                <?php if (!empty($skills)): ?>
                                    <?php foreach ($skills as $idx => $skill): ?>
                                        <span class="skill-chip <?= $idx % 3 === 0 ? 'skill-chip-primary' : ($idx % 3 === 1 ? 'skill-chip-blue' : '') ?>">
                                            <?= htmlspecialchars($skill) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: #757575; font-style: italic; margin: 0; padding: 6px 0;">
                                        No ha registrado habilidades aún.
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>

                    <!-- Tarjeta 7: Proyectos en Raíces Alzate -->
                    <article class="profile-card" id="cardProyectosVinculados">
                        <header class="profile-card-header">
                            <div class="profile-card-title-group">
                                <span class="profile-card-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </span>
                                <h2 class="profile-card-title">Proyectos en Raíces Alzate</h2>
                            </div>
                            <a href="index.php?page=proyectos&view=panel" class="profile-card-action-btn" title="Ver catálogo completo de proyectos">
                                Ver todos &rarr;
                            </a>
                        </header>
                        <div class="profile-card-body">
                            <?php if (!empty($proyectos)): ?>
                            <div class="profile-projects-grid">
                                <?php foreach ($proyectos as $proy): ?>
                                <div class="profile-project-card" onclick="window.location.href='index.php?page=detalle-proyecto&id=<?= (int)$proy['id'] ?>';" role="link" tabindex="0" style="cursor: pointer;">
                                    <div class="project-card-content">
                                        <div class="project-card-header">
                                            <span class="project-tag project-tag-tech"><?= htmlspecialchars($proy['categoria'] ?? 'Proyecto') ?></span>
                                        </div>
                                        <h3 class="project-card-title">
                                            <a href="index.php?page=detalle-proyecto&id=<?= (int)$proy['id'] ?>" class="project-title-link"><?= htmlspecialchars($proy['titulo']) ?></a>
                                        </h3>
                                        <p class="project-card-desc">
                                            <?= htmlspecialchars(mb_strimwidth($proy['descripcion'] ?? '', 0, 140, '...')) ?>
                                        </p>
                                    </div>
                                    <div class="project-card-footer">
                                        <a href="index.php?page=detalle-proyecto&id=<?= (int)$proy['id'] ?>" class="project-card-link" onclick="event.stopPropagation();">
                                            Ver proyecto &rarr;
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p style="color: #757575; font-style: italic; margin: 0; padding: 12px 0; text-align: center;">
                                Aún no ha publicado proyectos en la plataforma.
                            </p>
                            <?php endif; ?>
                        </div>
                    </article>

                </div>
            </div>

        </main>
    </div>
</div>

<!-- Notificación Toast Flotante -->
<div class="egresado-toast" id="toastNotificacionEgresados" role="status" aria-live="polite" style="display: none;">
    <span class="egresado-toast-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
    </span>
    <span id="toastMensajeEgresados"></span>
</div>

<script src="assets/js/dashboard.js"></script>
<script>
function mostrarToastLocal(msg) {
    const toast = document.getElementById('toastNotificacionEgresados');
    const toastMsg = document.getElementById('toastMensajeEgresados');
    if (toast && toastMsg) {
        toastMsg.textContent = msg;
        toast.style.display = 'flex';
        toast.classList.add('show');
        setTimeout(() => { 
            toast.classList.remove('show'); 
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 3500);
    } else {
        alert(msg);
    }
}

function compartirPerfilUrl() {
    if (navigator.clipboard && window.location.href) {
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                mostrarToastLocal('¡Enlace del perfil copiado al portapapeles!');
            })
            .catch(() => {
                prompt('Copia este enlace:', window.location.href);
            });
    } else {
        prompt('Copia este enlace:', window.location.href);
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
