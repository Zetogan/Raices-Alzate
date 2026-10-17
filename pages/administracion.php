<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Panel de Administración y Moderación (08 - Administración - Desktop y Mobile)
 * Arquitectura: Frontend Puro (CERO bases de datos, CERO endpoints AJAX)
 * Paleta Oficial: #D32F2F (Rojo Alzate), #1565C0 (Azul Alzate), #212121, #757575, #E0E0E0, #F5F5F5, #FFFFFF
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/mailer.php';

// 1. Verificación de autorización: solo administradores autenticados
if (!isAuthenticated() || !hasRole('administrador')) {
    header('Location: index.php?page=login');
    exit;
}

$adminUser   = currentUser();
$userName    = trim($adminUser['nombre'] ?? 'Administrador');
$userRole    = trim($adminUser['rol'] ?? 'Administrador');
$userEmail   = trim($adminUser['email'] ?? 'admin@alzate.edu.co');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'A';

// 2. Procesamiento de acciones de gestión de usuarios (Aceptar / Rechazar)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
           || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
           || (isset($_POST['is_ajax']) && $_POST['is_ajax'] === '1');

    // Validación estricta del token CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido o expirado.']);
            exit;
        }
        $_SESSION['admin_flash_error'] = 'Token de seguridad inválido. Intenta de nuevo.';
        header('Location: index.php?page=administracion');
        exit;
    }

    $action = $_POST['action'] ?? '';

    // Manejo de acciones de moderación de contenidos (Proyectos, Noticias, Oportunidades)
    if (in_array($action, ['aprobar_contenido', 'rechazar_contenido'], true)) {
        $contentId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        $tipo = strtolower(trim($_POST['tipo'] ?? ''));

        if (!$contentId || !in_array($tipo, ['proyecto', 'noticia', 'oportunidad'], true)) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Parámetros de moderación incorrectos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Parámetros de moderación incorrectos.';
            header('Location: index.php?page=administracion');
            exit;
        }

        $tablas = [
            'proyecto'    => 'proyectos',
            'noticia'     => 'noticias',
            'oportunidad' => 'oportunidades'
        ];
        $tabla = $tablas[$tipo];
        $nuevoEstado = ($action === 'aprobar_contenido') ? 'publicado' : 'rechazado';
        $adminId = (int)($adminUser['id'] ?? 0);

        try {
            if ($action === 'aprobar_contenido') {
                $stmt = $pdo->prepare("
                    UPDATE {$tabla} 
                    SET estado = :estado, revisado_por = :admin_id, fecha_publicacion = COALESCE(fecha_publicacion, NOW()), actualizado_en = NOW() 
                    WHERE id = :id
                ");
            } else {
                $stmt = $pdo->prepare("
                    UPDATE {$tabla} 
                    SET estado = :estado, revisado_por = :admin_id, actualizado_en = NOW() 
                    WHERE id = :id
                ");
            }
            $stmt->execute([
                ':estado'   => $nuevoEstado,
                ':admin_id' => $adminId,
                ':id'       => $contentId
            ]);

            $labelTipo = ucfirst($tipo);
            $mensajeRespuesta = ($action === 'aprobar_contenido')
                ? "{$labelTipo} aprobado y publicado exitosamente en el portal institucional."
                : "{$labelTipo} rechazado.";

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success'      => true,
                    'nuevo_estado' => ucfirst($nuevoEstado),
                    'tipo'         => $tipo,
                    'id'           => $contentId,
                    'message'      => $mensajeRespuesta
                ]);
                exit;
            }

            $_SESSION['admin_flash_success'] = $mensajeRespuesta;
            header('Location: index.php?page=administracion');
            exit;
        } catch (PDOException $e) {
            error_log("Error moderando {$tipo} en administración: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el contenido en la base de datos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Error al actualizar el contenido en la base de datos.';
            header('Location: index.php?page=administracion');
            exit;
        }
    }

    // Moderación y atención de PQRS
    if (in_array($action, ['responder_pqrs', 'cerrar_pqrs'], true)) {
        $radicado = trim($_POST['radicado'] ?? '');
        $respuesta = trim($_POST['respuesta'] ?? '');
        $adminId = (int)($adminUser['id'] ?? 0);

        try {
            if ($action === 'responder_pqrs') {
                $stmt = $pdo->prepare("
                    UPDATE pqrs 
                    SET estado = 'respondido', respuesta = :respuesta, respondido_por = :admin_id, respondido_en = NOW(), actualizado_en = NOW() 
                    WHERE radicado = :radicado
                ");
                $stmt->execute([
                    ':respuesta' => $respuesta,
                    ':admin_id'  => $adminId,
                    ':radicado'  => $radicado
                ]);
                $msg = "Solicitud PQRS {$radicado} respondida con éxito.";
            } else {
                $stmt = $pdo->prepare("
                    UPDATE pqrs 
                    SET estado = 'cerrado', actualizado_en = NOW() 
                    WHERE radicado = :radicado
                ");
                $stmt->execute([':radicado' => $radicado]);
                $msg = "Solicitud PQRS {$radicado} cerrada.";
            }

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_success'] = $msg;
            header('Location: index.php?page=administracion');
            exit;
        } catch (PDOException $e) {
            error_log('Error gestionando PQRS: ' . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar PQRS en base de datos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Error en base de datos al actualizar la solicitud.';
            header('Location: index.php?page=administracion');
            exit;
        }
    }

    $targetUserId = filter_var($_POST['usuario_id'] ?? 0, FILTER_VALIDATE_INT);

    if (!$targetUserId || !in_array($action, ['aprobar_registro', 'rechazar_registro'], true)) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Parámetros no válidos.']);
            exit;
        }
        $_SESSION['admin_flash_error'] = 'Parámetros de solicitud incorrectos.';
        header('Location: index.php?page=administracion');
        exit;
    }

    try {
        // Consultar los datos del usuario antes de actualizar para la notificación por correo
        $userQuery = $pdo->prepare("SELECT id, nombre_completo, email, estado_verificacion FROM usuarios WHERE id = :id LIMIT 1");
        $userQuery->execute([':id' => $targetUserId]);
        $targetUser = $userQuery->fetch(PDO::FETCH_ASSOC);

        if (!$targetUser) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'El usuario no existe en la base de datos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'El usuario no existe.';
            header('Location: index.php?page=administracion');
            exit;
        }

        $nuevoEstado = ($action === 'aprobar_registro') ? 'aprobado' : 'rechazado';

        $updateStmt = $pdo->prepare("
            UPDATE usuarios 
            SET estado_verificacion = :estado, actualizado_en = NOW() 
            WHERE id = :id
        ");
        $updateStmt->execute([
            ':estado' => $nuevoEstado,
            ':id'     => $targetUserId
        ]);

        // Enviar notificación formal por correo electrónico
        $correoEnviado = enviarNotificacionRegistro(
            $targetUser['email'],
            $targetUser['nombre_completo'],
            $action
        );

        $mensajeRespuesta = ($nuevoEstado === 'aprobado')
            ? 'Registro aprobado exitosamente. Se ha enviado un correo al solicitante notificándole que ya puede acceder.'
            : 'Solicitud rechazada. Se ha enviado una notificación formal por correo electrónico.';

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success'        => true,
                'nuevo_estado'   => ucfirst($nuevoEstado),
                'usuario_id'     => $targetUserId,
                'correo_enviado' => $correoEnviado,
                'message'        => $mensajeRespuesta
            ]);
            exit;
        }

        $_SESSION['admin_flash_success'] = $mensajeRespuesta;
        header('Location: index.php?page=administracion');
        exit;
    } catch (PDOException $e) {
        error_log('Error al actualizar estado en administración: ' . $e->getMessage());
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario en la base de datos.']);
            exit;
        }
        $_SESSION['admin_flash_error'] = 'Error en base de datos al actualizar el registro.';
        header('Location: index.php?page=administracion');
        exit;
    }
}

// 3. Metadatos de la vista para layouts compartidos
$seccion_activa = 'administracion';
$currentPage = 'administracion';
$titulo_seccion = 'Administración';
$titulo_pagina = 'Panel de Administración — Raíces Alzate';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Hojas de estilo y scripts requeridos
$extraCss = ['dashboard.css', 'administracion.css'];
$extraJs = ['assets/js/dashboard.js'];

// 4. Carga real de usuarios desde la base de datos MySQL (con roles y perfiles)
try {
    $usuariosStmt = $pdo->query("
        SELECT 
            u.id,
            u.rol_id,
            u.nombre_completo,
            u.email,
            u.estado_verificacion,
            u.activo,
            u.creado_en,
            r.nombre AS rol_nombre
        FROM usuarios u
        LEFT JOIN roles r ON u.rol_id = r.id
        ORDER BY 
            CASE WHEN u.estado_verificacion = 'pendiente' THEN 1 ELSE 2 END ASC,
            u.creado_en DESC
    ");
    $usuariosDb = $usuariosStmt ? $usuariosStmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (PDOException $e) {
    error_log('Error consultando usuarios en administración: ' . $e->getMessage());
    $usuariosDb = [];
}

$coloresAvatar = ['#1565C0', '#D32F2F', '#2E7D32', '#6A1B9A', '#EF6C00', '#00838F'];
$solicitudesRegistro = [];

foreach ($usuariosDb as $idx => $row) {
    $estadoCapitalizado = ucfirst($row['estado_verificacion'] ?? 'pendiente');
    $color = $coloresAvatar[$idx % count($coloresAvatar)];
    $fechaFormateada = !empty($row['creado_en']) ? date('d M Y', strtotime($row['creado_en'])) : 'Reciente';

    $solicitudesRegistro[] = [
        'id'     => (int)$row['id'],
        'nombre' => $row['nombre_completo'],
        'email'  => $row['email'],
        'fecha'  => $fechaFormateada,
        'estado' => $estadoCapitalizado,
        'avatar' => mb_strtoupper(mb_substr($row['nombre_completo'] ?? 'U', 0, 1, 'UTF-8'), 'UTF-8'),
        'color'  => $color
    ];
}

// C. Moderación de Contenidos reales desde MySQL (Proyectos, Noticias y Oportunidades)
$contenidosModeracion = [];

try {
    // 1. Proyectos de la base de datos
    $proyStmt = $pdo->query("
        SELECT 
            p.id,
            p.titulo,
            p.categoria,
            p.descripcion,
            p.enlace,
            p.portada,
            p.estado,
            p.creado_en,
            COALESCE(u.nombre_completo, 'Egresado') AS autor_nombre
        FROM proyectos p
        LEFT JOIN usuarios u ON p.autor_id = u.id
        ORDER BY 
            CASE WHEN p.estado = 'pendiente' THEN 1 ELSE 2 END ASC,
            p.creado_en DESC
    ");
    $proyectosDb = $proyStmt ? $proyStmt->fetchAll(PDO::FETCH_ASSOC) : [];
    foreach ($proyectosDb as $p) {
        $fechaFmt = !empty($p['creado_en']) ? date('d M Y', strtotime($p['creado_en'])) : 'Reciente';
        $desc = $p['descripcion'] ?? '';
        $resumen = mb_substr(strip_tags($desc), 0, 140, 'UTF-8') . (mb_strlen($desc) > 140 ? '...' : '');
        $contenidosModeracion[] = [
            'id'                 => (int)$p['id'],
            'tipo'               => 'Proyecto',
            'titulo'             => $p['titulo'],
            'autor'              => $p['autor_nombre'],
            'categoria'          => $p['categoria'] ?: 'General',
            'fecha'              => $fechaFmt,
            'estado'             => ucfirst($p['estado'] ?: 'pendiente'),
            'resumen'            => $resumen,
            'contenido_completo' => $desc,
            'badge_class'        => 'badge-orange',
            'portada'            => $p['portada'] ?? null,
            'enlace'             => $p['enlace'] ?? null
        ];
    }

    // 2. Noticias de la base de datos
    $notStmt = $pdo->query("
        SELECT 
            n.id,
            n.titulo,
            n.categoria,
            n.descripcion,
            n.portada,
            n.estado,
            n.creado_en,
            COALESCE(u.nombre_completo, 'Administración') AS autor_nombre
        FROM noticias n
        LEFT JOIN usuarios u ON n.autor_id = u.id
        ORDER BY 
            CASE WHEN n.estado = 'pendiente' THEN 1 ELSE 2 END ASC,
            n.creado_en DESC
    ");
    $noticiasDb = $notStmt ? $notStmt->fetchAll(PDO::FETCH_ASSOC) : [];
    foreach ($noticiasDb as $n) {
        $fechaFmt = !empty($n['creado_en']) ? date('d M Y', strtotime($n['creado_en'])) : 'Reciente';
        $desc = $n['descripcion'] ?? '';
        $resumen = mb_substr(strip_tags($desc), 0, 140, 'UTF-8') . (mb_strlen($desc) > 140 ? '...' : '');
        $contenidosModeracion[] = [
            'id'                 => (int)$n['id'],
            'tipo'               => 'Noticia',
            'titulo'             => $n['titulo'],
            'autor'              => $n['autor_nombre'],
            'categoria'          => $n['categoria'] ?: 'Institucional',
            'fecha'              => $fechaFmt,
            'estado'             => ucfirst($n['estado'] ?: 'pendiente'),
            'resumen'            => $resumen,
            'contenido_completo' => $desc,
            'badge_class'        => 'badge-blue',
            'portada'            => $n['portada'] ?? null
        ];
    }

    // 3. Oportunidades de la base de datos
    $opoStmt = $pdo->query("
        SELECT 
            o.id,
            o.titulo,
            o.empresa,
            o.categoria,
            o.modalidad,
            o.ubicacion,
            o.descripcion,
            o.requisitos,
            o.beneficios,
            o.enlace_postulacion,
            o.estado,
            o.creado_en,
            COALESCE(u.nombre_completo, o.empresa, 'Institucional') AS autor_nombre
        FROM oportunidades o
        LEFT JOIN usuarios u ON o.autor_id = u.id
        ORDER BY 
            CASE WHEN o.estado = 'pendiente' THEN 1 ELSE 2 END ASC,
            o.creado_en DESC
    ");
    $oportunidadesDb = $opoStmt ? $opoStmt->fetchAll(PDO::FETCH_ASSOC) : [];
    foreach ($oportunidadesDb as $o) {
        $fechaFmt = !empty($o['creado_en']) ? date('d M Y', strtotime($o['creado_en'])) : 'Reciente';
        $desc = $o['descripcion'] ?? '';
        $resumen = mb_substr(strip_tags($desc), 0, 140, 'UTF-8') . (mb_strlen($desc) > 140 ? '...' : '');
        $contenidosModeracion[] = [
            'id'                 => (int)$o['id'],
            'tipo'               => 'Oportunidad',
            'titulo'             => $o['titulo'],
            'autor'              => $o['empresa'] ?: $o['autor_nombre'],
            'categoria'          => $o['categoria'] ?: 'Laboral',
            'fecha'              => $fechaFmt,
            'estado'             => ucfirst($o['estado'] ?: 'pendiente'),
            'resumen'            => $resumen,
            'contenido_completo' => $desc,
            'badge_class'        => 'badge-purple'
        ];
    }
} catch (PDOException $e) {
    error_log('Error consultando contenidos para moderación: ' . $e->getMessage());
}

// Fallback visual si aún no hay noticias u oportunidades en BD
if (empty($noticiasDb)) {
    $contenidosModeracion[] = [
        'id'                 => 101,
        'tipo'               => 'Noticia',
        'titulo'             => 'Gran Feria de Emprendimiento y Talento Alzatista 2026',
        'autor'              => 'Comité de Egresados',
        'categoria'          => 'Eventos',
        'fecha'              => '15 Oct 2026',
        'estado'             => 'Pendiente',
        'resumen'            => 'Invitación a todos los egresados a presentar sus iniciativas comerciales en la sede principal durante la semana institucional.',
        'contenido_completo' => 'La Institución Educativa Gilberto Alzate Avendaño abre sus puertas a toda la comunidad de egresados para la Gran Feria de Emprendimiento 2026. Habrá stands para 30 proyectos, ruedas de contacto con empresarios del sector y conferencias sobre financiamiento solidario.',
        'badge_class'        => 'badge-blue'
    ];
}
if (empty($oportunidadesDb)) {
    $contenidosModeracion[] = [
        'id'                 => 102,
        'tipo'               => 'Oportunidad',
        'titulo'             => 'Convocatoria para Desarrollador Frontend Junior',
        'autor'              => 'Innovatech Medellín (Carlos Morales)',
        'categoria'          => 'Laboral',
        'fecha'              => '14 Oct 2026',
        'estado'             => 'Pendiente',
        'resumen'            => 'Empresa aliada de egresados busca talentos con conocimientos en HTML5, CSS3, JavaScript y pasión por el diseño accesible.',
        'contenido_completo' => 'Se requiere persona proactiva para integración al equipo de producto. Modalidad híbrida en Medellín, contrato a término indefinido y plan de beneficios académicos continuos.',
        'badge_class'        => 'badge-purple'
    ];
}

// D. Gestión de PQRS y Reportes Técnicos
$pqrsLista = [];
try {
    $stmtPqrs = $pdo->query("SELECT * FROM pqrs ORDER BY creado_en DESC");
    $pqrsDb = $stmtPqrs ? $stmtPqrs->fetchAll(PDO::FETCH_ASSOC) : [];
    foreach ($pqrsDb as $row) {
        $fechaFmt = !empty($row['creado_en']) ? date('d M Y', strtotime($row['creado_en'])) : 'Reciente';
        $estadoLabel = ($row['estado'] === 'respondido') ? 'Resuelto' : (($row['estado'] === 'cerrado') ? 'Cerrado' : 'Pendiente');
        $pqrsLista[] = [
            'id'          => (int)$row['id'],
            'radicado'    => $row['radicado'],
            'tipo'        => $row['tipo'],
            'remitente'   => $row['nombre'],
            'correo'      => $row['email'],
            'asunto'      => $row['asunto'],
            'fecha'       => $fechaFmt,
            'estado'      => $estadoLabel,
            'prioridad'   => $row['prioridad'] ?: 'Media',
            'descripcion' => $row['mensaje'],
            'imagen'      => $row['imagen'] ?? '',
            'respuesta'   => $row['respuesta'] ?? ''
        ];
    }
} catch (PDOException $e) {
    error_log('Error consultando tabla pqrs: ' . $e->getMessage());
}


// Métricas Generales (KPIs) calculadas limpiamente de la base de datos real
$solicitudesPendientesCount = count(array_filter($solicitudesRegistro, fn($s) => strtolower($s['estado']) === 'pendiente'));
$egresadosTotalesCount = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol_id = 2 AND estado_verificacion = 'aprobado'")->fetchColumn();
$contenidosRevisionCount = count(array_filter($contenidosModeracion, fn($c) => strtolower($c['estado']) === 'pendiente'));
$pqrsActivasCount = count(array_filter($pqrsLista, fn($p) => strtolower($p['estado']) === 'pendiente'));

$metricas = [
    'solicitudes_pendientes' => $solicitudesPendientesCount,
    'contenidos_revision'    => $contenidosRevisionCount,
    'pqrs_activas'           => $pqrsActivasCount,
    'egresados_totales'      => $egresadosTotalesCount,
];

// Carga del layout compartido (encabezado HTML y carga de CSS)
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Estilos específicos del Dashboard y Administración -->
<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/administracion.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Barra Lateral Privada -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Área Principal de Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Barra Superior (Topbar) -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <!-- Contenido Central del Panel Administrativo -->
        <main class="content-area dashboard-content admin-content-area" id="mainContent" tabindex="-1">
            
            <!-- 1. Cabecera y Bienvenida Administrativa -->
            <div class="admin-header-banner">
                <div class="admin-header-text">
                    <span class="admin-badge-pill">Gestión y Moderación Oficial</span>
                    <h1 class="admin-page-title">Panel de Administración</h1>
                    <p class="admin-page-subtitle">
                        Supervisa y aprueba solicitudes de nuevos egresados, modera noticias u oportunidades y atiende las peticiones y reportes ciudadanos.
                    </p>
                </div>
            </div>

            <!-- 2. Grid de Métricas / Tarjetas KPI Clave -->
            <section class="admin-kpi-grid" aria-label="Métricas del sistema">
                <!-- KPI 1: Solicitudes de Registro -->
                <article class="admin-kpi-card kpi-blue">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Registros Pendientes</span>
                        <h2 class="kpi-value" id="kpiRegistros"><?= (int)$metricas['solicitudes_pendientes'] ?></h2>
                    </div>
                </article>

                <!-- KPI 2: Contenidos por Moderar -->
                <article class="admin-kpi-card kpi-orange">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Contenidos en Revisión</span>
                        <h2 class="kpi-value" id="kpiContenidos"><?= (int)$metricas['contenidos_revision'] ?></h2>
                    </div>
                </article>

                <!-- KPI 3: PQRS & Reportes Activos -->
                <article class="admin-kpi-card kpi-red">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">PQRS por Resolver</span>
                        <h2 class="kpi-value" id="kpiPQRS"><?= (int)$metricas['pqrs_activas'] ?></h2>
                    </div>
                </article>

                <!-- KPI 4: Total de Egresados Activos -->
                <article class="admin-kpi-card kpi-green">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Egresados Verificados</span>
                        <h2 class="kpi-value" id="kpiEgresados"><?= number_format((int)$metricas['egresados_totales']) ?></h2>
                    </div>
                </article>
            </section>

            <!-- 3. Navegación Modular por Pestañas (Tabs) -->
            <div class="admin-tabs-container">
                <div class="admin-tabs-list" role="tablist" aria-label="Secciones del panel de administración">
                    <button type="button" class="admin-tab-btn active" role="tab" id="tabBtnRegistros" aria-selected="true" aria-controls="panelRegistros" onclick="cambiarPestana('registros')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Aprobación de Registros</span>
                        <span class="tab-counter-badge" id="badgeCountRegistros"><?= (int)$metricas['solicitudes_pendientes'] ?></span>
                    </button>

                    <button type="button" class="admin-tab-btn" role="tab" id="tabBtnContenidos" aria-selected="false" aria-controls="panelContenidos" onclick="cambiarPestana('contenidos')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path>
                        </svg>
                        <span>Moderación de Contenidos</span>
                        <span class="tab-counter-badge" id="badgeCountContenidos">3</span>
                    </button>

                    <button type="button" class="admin-tab-btn" role="tab" id="tabBtnPQRS" aria-selected="false" aria-controls="panelPQRS" onclick="cambiarPestana('pqrs')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span>Resolución de PQRS</span>
                        <span class="tab-counter-badge" id="badgeCountPQRS">3</span>
                    </button>
                </div>
            </div>

            <!-- 4. Barra de Búsqueda y Filtros de la Tabla Activa -->
            <div class="admin-table-toolbar">
                <div class="admin-search-wrap">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="adminSearchInput" class="admin-search-input" placeholder="Buscar por nombre, correo o radicado..." oninput="filtrarTablaActual()">
                    <button type="button" id="adminClearSearch" class="search-clear-btn" style="display: none;" onclick="limpiarBusqueda()">✕</button>
                </div>

                <div class="admin-filter-group">
                    <label for="adminStatusFilter" class="filter-label">Estado:</label>
                    <select id="adminStatusFilter" class="admin-select" onchange="filtrarTablaActual()">
                        <option value="todos" selected>Todos los estados</option>
                        <option value="pendiente">Solo Pendientes</option>
                        <option value="aprobado_publicado">Aprobados / Publicados</option>
                        <option value="rechazado">Rechazados</option>
                    </select>

                    <span class="admin-results-counter" id="adminResultsCounter">Mostrando 3 de 6 registros</span>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 5. PANEL 1: SOLICITUDES DE REGISTRO                           -->
            <!-- ============================================================== -->
            <div class="admin-panel active" id="panelRegistros" role="tabpanel" aria-labelledby="tabBtnRegistros">
                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaRegistros">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Fecha de solicitud</th>
                                    <th class="text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudesRegistro as $sol): ?>
                                <tr class="admin-table-row" id="row-registro-<?= $sol['id'] ?>" 
                                    data-id="<?= $sol['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($sol['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-email="<?= htmlspecialchars($sol['email'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-fecha="<?= htmlspecialchars($sol['fecha'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-estado="<?= htmlspecialchars($sol['estado'], ENT_QUOTES, 'UTF-8') ?>">
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-avatar" style="background-color: <?= $sol['color'] ?>;">
                                                <?= htmlspecialchars($sol['avatar'], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                            <div class="user-cell-info">
                                                <span class="user-cell-name"><?= htmlspecialchars($sol['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-cell-email" style="font-size: 0.85rem; color: #424242; font-weight: 500;"><?= htmlspecialchars($sol['email'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($sol['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud" onclick="abrirModalRegistro(<?= $sol['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span>Revisar</span>
                                            </button>
                                            <?php if (strtolower($sol['estado']) === 'aprobado'): ?>
                                                <span class="action-confirmed-tag">✓ Aprobado</span>
                                            <?php elseif (strtolower($sol['estado']) === 'rechazado'): ?>
                                                <span class="action-rejected-tag">✕ Rechazado</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyRegistros" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>No se encontraron solicitudes con el criterio de búsqueda seleccionado.</p>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 6. PANEL 2: MODERACIÓN DE CONTENIDOS                          -->
            <!-- ============================================================== -->
            <div class="admin-panel" id="panelContenidos" role="tabpanel" aria-labelledby="tabBtnContenidos">
                <div class="admin-card-table-wrapper">
                    <!-- Barra de Creación y Publicación Rápida para el Administrador -->
                    <div class="admin-tab-actions-bar">
                        <div class="admin-tab-title-wrap">
                            <h3 class="admin-section-heading">Gestión y Aprobación de Publicaciones</h3>
                            <p class="admin-section-sub">Revisa proyectos de egresados, aprueba convocatorias y redacta comunicados oficiales.</p>
                        </div>
                        <div class="admin-tab-buttons-group">
                            <a href="index.php?page=crear-noticia" class="admin-action-pill pill-blue" title="Publicar noticia oficial">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>+ Nueva Noticia</span>
                            </a>
                            <a href="index.php?page=crear-oportunidad" class="admin-action-pill pill-purple" title="Publicar convocatoria u oportunidad">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>+ Nueva Convocatoria</span>
                            </a>
                            <a href="index.php?page=crear-proyecto" class="admin-action-pill pill-orange" title="Crear proyecto institucional">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>+ Nuevo Proyecto</span>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="admin-table" id="tablaContenidos">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Título del Contenido</th>
                                    <th>Autor / Remitente</th>
                                    <th>Categoría</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contenidosModeracion as $item): ?>
                                <tr class="admin-table-row" id="row-contenido-<?= strtolower($item['tipo']) ?>-<?= $item['id'] ?>"
                                    data-id="<?= $item['id'] ?>"
                                    data-tipo="<?= strtolower($item['tipo']) ?>"
                                    data-tipo-label="<?= htmlspecialchars($item['tipo']) ?>"
                                    data-titulo="<?= htmlspecialchars($item['titulo']) ?>"
                                    data-autor="<?= htmlspecialchars($item['autor']) ?>"
                                    data-categoria="<?= htmlspecialchars($item['categoria']) ?>"
                                    data-resumen="<?= htmlspecialchars($item['resumen']) ?>"
                                    data-contenido="<?= htmlspecialchars($item['contenido_completo']) ?>"
                                    data-estado="<?= $item['estado'] ?>"
                                    data-fecha="<?= $item['fecha'] ?>">
                                    <td>
                                        <span class="type-pill type-<?= strtolower($item['tipo']) ?>">
                                            <?= htmlspecialchars($item['tipo']) ?>
                                        </span>
                                    </td>
                                    <td class="font-semibold-cell">
                                        <div class="content-title-cell">
                                            <span><?= htmlspecialchars($item['titulo']) ?></span>
                                            <p class="content-desc-sub"><?= htmlspecialchars($item['resumen']) ?></p>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($item['autor']) ?></td>
                                    <td><span class="category-badge"><?= htmlspecialchars($item['categoria']) ?></span></td>
                                    <td><?= htmlspecialchars($item['fecha']) ?></td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($item['estado']) ?>">
                                            <?= $item['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Examinar y publicar" onclick="abrirModalContenido('<?= strtolower($item['tipo']) ?>', <?= $item['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span>Moderar</span>
                                            </button>
                                            <?php if (strtolower($item['estado']) === 'pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Aprobar y publicar en el portal" onclick="publicarContenidoDirecto('<?= strtolower($item['tipo']) ?>', <?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                            <button type="button" class="btn-action-sm btn-action-reject" title="Rechazar contenido" onclick="rechazarContenidoDirecto('<?= strtolower($item['tipo']) ?>', <?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyContenidos" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>No se encontraron publicaciones con los filtros aplicados.</p>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 7. PANEL 3: RESOLUCIÓN DE PQRS Y REPORTES                     -->
            <!-- ============================================================== -->
            <div class="admin-panel" id="panelPQRS" role="tabpanel" aria-labelledby="tabBtnPQRS">
                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaPQRS">
                            <thead>
                                <tr>
                                    <th>Radicado</th>
                                    <th>Tipo de Caso</th>
                                    <th>Remitente</th>
                                    <th>Asunto</th>
                                    <th>Fecha</th>
                                    <th>Prioridad</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pqrsLista as $pq): ?>
                                <tr class="admin-table-row" id="row-pqrs-<?= $pq['radicado'] ?>"
                                    data-id="<?= $pq['radicado'] ?>"
                                    data-radicado="<?= htmlspecialchars($pq['radicado']) ?>"
                                    data-tipo="<?= htmlspecialchars($pq['tipo']) ?>"
                                    data-remitente="<?= htmlspecialchars($pq['remitente']) ?>"
                                    data-correo="<?= htmlspecialchars($pq['correo']) ?>"
                                    data-asunto="<?= htmlspecialchars($pq['asunto']) ?>"
                                    data-prioridad="<?= htmlspecialchars($pq['prioridad']) ?>"
                                    data-descripcion="<?= htmlspecialchars($pq['descripcion']) ?>"
                                    data-respuesta="<?= htmlspecialchars($pq['respuesta']) ?>"
                                    data-estado="<?= $pq['estado'] ?>"
                                    data-fecha="<?= $pq['fecha'] ?>">
                                    <td><code class="radicado-code"><?= htmlspecialchars($pq['radicado']) ?></code></td>
                                    <td>
                                        <span class="type-pill type-<?= strtolower(str_replace(' ', '-', $pq['tipo'])) ?>">
                                            <?= htmlspecialchars($pq['tipo']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-info">
                                                <span class="user-cell-name"><?= htmlspecialchars($pq['remitente']) ?></span>
                                                <span class="user-cell-email"><?= htmlspecialchars($pq['correo']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-semibold-cell"><?= htmlspecialchars($pq['asunto']) ?></td>
                                    <td><?= htmlspecialchars($pq['fecha']) ?></td>
                                    <td>
                                        <span class="priority-badge priority-<?= strtolower($pq['prioridad']) ?>">
                                            <?= htmlspecialchars($pq['prioridad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($pq['estado']) ?>">
                                            <?= $pq['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Atender y responder caso" onclick="abrirModalPQRS('<?= $pq['radicado'] ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                                <span>Responder</span>
                                            </button>
                                            <?php if ($pq['estado'] === 'Pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Marcar como resuelto" onclick="resolverPQRSDirecto('<?= $pq['radicado'] ?>', '<?= htmlspecialchars(addslashes($pq['remitente'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyPQRS" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>No se encontraron casos de PQRS con el filtro aplicado.</p>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 8. MODAL 1: REVISIÓN DE SOLICITUD DE REGISTRO                           -->
<!-- ======================================================================= -->
<div class="admin-modal-backdrop" id="modalRegistro" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalRegistroTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <polyline points="17 11 19 13 23 9"></polyline>
                </svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalRegistroTitle">Revisión de Solicitud de Registro</h3>
                <span class="admin-modal-subtitle">Gestiona la autorización de acceso a la plataforma</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="modal-user-hero">
                <div class="modal-user-avatar" id="modalRegAvatar">U</div>
                <div class="modal-user-hero-info">
                    <h4 class="modal-user-name" id="modalRegNombre">—</h4>
                    <span class="modal-user-email" id="modalRegEmail">—</span>
                </div>
            </div>

            <div class="admin-detail-grid" style="grid-template-columns: 1fr; margin-top: 16px;">
                <div class="detail-item">
                    <span class="detail-label">Fecha de Solicitud</span>
                    <span class="detail-value" id="modalRegFecha">—</span>
                </div>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-danger" id="btnModalRechazarReg" onclick="rechazarRegistroDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Rechazar Solicitud</span>
            </button>
            <button type="button" class="btn-modal-success" id="btnModalAprobarReg" onclick="aprobarRegistroDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Aceptar Solicitud</span>
            </button>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 9. MODAL 2: MODERACIÓN Y PUBLICACIÓN DE CONTENIDOS                      -->
<!-- ======================================================================= -->
<div class="admin-modal-backdrop" id="modalContenido" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalContenidoTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap icon-orange">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E65100" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                </svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalContenidoTitle">Moderación de Publicación</h3>
                <span class="admin-modal-subtitle">Valida la veracidad y calidad antes de publicar en el portal público</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="modal-content-meta">
                <span class="type-pill type-noticia" id="modalContTipo">Noticia</span>
                <span class="category-badge" id="modalContCategoria">Eventos</span>
                <span class="status-pill status-pendiente" id="modalContEstado">Pendiente</span>
            </div>

            <h4 class="modal-content-title" id="modalContTitulo">Gran Feria de Emprendimiento y Talento Alzatista 2026</h4>
            
            <div class="modal-author-row">
                <span class="author-label">Publicado por:</span>
                <strong id="modalContAutor">Comité de Egresados</strong>
                <span class="author-sep">•</span>
                <span id="modalContFecha">15 Oct 2026</span>
            </div>

            <div class="modal-content-preview-box">
                <span class="box-label">Cuerpo de la Publicación:</span>
                <p class="preview-full-text" id="modalContCompleto">
                    La Institución Educativa Gilberto Alzate Avendaño abre sus puertas a toda la comunidad de egresados para la Gran Feria de Emprendimiento 2026. Habrá stands para 30 proyectos, ruedas de contacto con empresarios del sector y conferencias sobre financiamiento solidario.
                </p>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cerrar</button>
            <button type="button" class="btn-modal-danger" id="btnModalRechazarCont" onclick="rechazarContenidoDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Rechazar</span>
            </button>
            <button type="button" class="btn-modal-success" id="btnModalPublicarCont" onclick="publicarContenidoDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Aprobar y Publicar</span>
            </button>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 10. MODAL 3: RESOLUCIÓN Y RESPUESTA DE PQRS                             -->
<!-- ======================================================================= -->
<div class="admin-modal-backdrop" id="modalPQRS" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalPQRSTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap icon-red">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalPQRSTitle">Atención y Resolución de PQRS</h3>
                <span class="admin-modal-subtitle">Radicado Oficial: <strong id="modalPQRSRadicadoCode">RA-2026-089</strong></span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="pqrs-info-banner">
                <div class="pqrs-meta-pill-group">
                    <span class="type-pill type-reporte-de-error" id="modalPQRSTipo">Reporte de Error</span>
                    <span class="priority-badge priority-alta" id="modalPQRSPrioridad">Prioridad Alta</span>
                    <span class="status-pill status-pendiente" id="modalPQRSEstado">Pendiente</span>
                </div>
                <div class="pqrs-citizen-info">
                    <span class="citizen-name" id="modalPQRSRemitente">Santiago Emitola</span>
                    <span class="citizen-email" id="modalPQRSCorreo">santiago@ejemplo.com</span>
                    <span class="citizen-date" id="modalPQRSFecha">15 Oct 2026</span>
                </div>
            </div>

            <div class="modal-content-preview-box">
                <span class="box-label">Asunto:</span>
                <strong class="pqrs-subject-text" id="modalPQRSAsunto">No carga la imagen del certificado en mi perfil</strong>
                <span class="box-label" style="margin-top: 10px;">Detalle o Descripción de la Solicitud:</span>
                <p class="preview-full-text" id="modalPQRSDescripcion">
                    Al intentar subir mi soporte de grado en formato PNG de 2MB, la zona de dropzone no procesa la previsualización y se queda congelada.
                </p>
            </div>

            <div class="modal-form-group">
                <label for="adminRespuestaPQRS" class="modal-input-label">
                    Respuesta Institucional Oficial <span class="required-mark">*</span>:
                </label>
                <textarea id="adminRespuestaPQRS" class="admin-textarea" rows="4" placeholder="Escribe aquí la respuesta oficial que se enviará al correo del solicitante y quedará registrada en el radicado..."></textarea>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-success" id="btnModalEnviarPQRS" onclick="enviarRespuestaPQRSDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                <span>Enviar Respuesta y Resolver</span>
            </button>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 11. NOTIFICACIÓN FLOTANTE (TOAST ADMINISTRATIVO)                        -->
<!-- ======================================================================= -->
<div class="admin-toast-notification" id="adminToast" role="alert" aria-live="assertive">
    <div class="admin-toast-icon-wrap" id="adminToastIcon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>
    <div class="admin-toast-content">
        <strong class="admin-toast-title" id="adminToastTitle">Acción completada</strong>
        <p class="admin-toast-desc" id="adminToastDesc">La operación administrativa se realizó con éxito.</p>
    </div>
    <button type="button" class="admin-toast-close" onclick="ocultarToastAdmin()" aria-label="Cerrar notificación">&times;</button>
</div>

<!-- ======================================================================= -->
<!-- 12. LOGICA JAVASCRIPT MODULAR Y ACCESIBLE (VANILLA JS PURO)              -->
<!-- ======================================================================= -->
<script>
// Token CSRF generado por el backend
const CSRF_TOKEN = '<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8') ?>';

// Estado administrativo local
let activeTab = 'registros';
let currentModalEntityId = null;
let toastTimeout = null;

// A. Cambio de Pestañas
function cambiarPestana(tabName) {
    activeTab = tabName;
    document.querySelectorAll('.admin-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    document.querySelectorAll('.admin-panel').forEach(panel => {
        panel.classList.remove('active');
    });

    if (tabName === 'registros') {
        document.getElementById('tabBtnRegistros').classList.add('active');
        document.getElementById('tabBtnRegistros').setAttribute('aria-selected', 'true');
        document.getElementById('panelRegistros').classList.add('active');
    } else if (tabName === 'contenidos') {
        document.getElementById('tabBtnContenidos').classList.add('active');
        document.getElementById('tabBtnContenidos').setAttribute('aria-selected', 'true');
        document.getElementById('panelContenidos').classList.add('active');
    } else if (tabName === 'pqrs') {
        document.getElementById('tabBtnPQRS').classList.add('active');
        document.getElementById('tabBtnPQRS').setAttribute('aria-selected', 'true');
        document.getElementById('panelPQRS').classList.add('active');
    }

    filtrarTablaActual();
}

// B. Filtros de Búsqueda y Estado en Tiempo Real
function filtrarTablaActual() {
    const query = document.getElementById('adminSearchInput').value.toLowerCase().trim();
    const statusFilter = document.getElementById('adminStatusFilter').value;
    const clearBtn = document.getElementById('adminClearSearch');

    if (clearBtn) {
        clearBtn.style.display = query.length > 0 ? 'inline-flex' : 'none';
    }

    let currentTableId = 'tablaRegistros';
    let emptyNoticeId = 'emptyRegistros';
    if (activeTab === 'contenidos') {
        currentTableId = 'tablaContenidos';
        emptyNoticeId = 'emptyContenidos';
    } else if (activeTab === 'pqrs') {
        currentTableId = 'tablaPQRS';
        emptyNoticeId = 'emptyPQRS';
    }

    const table = document.getElementById(currentTableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr.admin-table-row');
    let visibleCount = 0;
    const totalCount = rows.length;

    rows.forEach(row => {
        const textContent = row.textContent.toLowerCase();
        const rowStatus = (row.getAttribute('data-estado') || '').toLowerCase().trim();

        const matchesQuery = query === '' || textContent.includes(query);
        let matchesStatus = false;
        if (statusFilter === 'todos') {
            matchesStatus = true;
        } else if (statusFilter === 'pendiente') {
            matchesStatus = (rowStatus === 'pendiente');
        } else if (statusFilter === 'aprobado_publicado') {
            matchesStatus = (rowStatus === 'aprobado' || rowStatus === 'publicado' || rowStatus === 'resuelto');
        } else if (statusFilter === 'rechazado') {
            matchesStatus = (rowStatus === 'rechazado');
        }

        if (matchesQuery && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Actualizar contador
    const counterEl = document.getElementById('adminResultsCounter');
    if (counterEl) {
        counterEl.textContent = `Mostrando ${visibleCount} de ${totalCount} registros`;
    }

    // Estado vacío
    const emptyNotice = document.getElementById(emptyNoticeId);
    if (emptyNotice) {
        emptyNotice.style.display = visibleCount === 0 ? 'flex' : 'none';
    }
}

function limpiarBusqueda() {
    document.getElementById('adminSearchInput').value = '';
    filtrarTablaActual();
    document.getElementById('adminSearchInput').focus();
}

// C. Control de Modales
function cerrarModales() {
    document.querySelectorAll('.admin-modal-backdrop').forEach(modal => {
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
    });
    currentModalEntityId = null;
}

// Cerrar con Escape o clic en backdrop
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModales();
    }
});

document.querySelectorAll('.admin-modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModales();
        }
    });
});

// D. Modal 1: Registro de Egresado
function abrirModalRegistro(id) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    currentModalEntityId = id;
    const nombre = row.getAttribute('data-nombre') || '';
    const email = row.getAttribute('data-email') || '';
    const fecha = row.getAttribute('data-fecha') || '';
    const estado = row.getAttribute('data-estado') || 'Pendiente';

    const avatarEl = document.getElementById('modalRegAvatar');
    if (avatarEl) avatarEl.textContent = nombre.charAt(0).toUpperCase();

    const nombreEl = document.getElementById('modalRegNombre');
    if (nombreEl) nombreEl.textContent = nombre;

    const emailEl = document.getElementById('modalRegEmail');
    if (emailEl) emailEl.textContent = email;

    const fechaEl = document.getElementById('modalRegFecha');
    if (fechaEl) fechaEl.textContent = fecha;

    const btnAprobar = document.getElementById('btnModalAprobarReg');
    const btnRechazar = document.getElementById('btnModalRechazarReg');
    const isPendiente = estado.toLowerCase() === 'pendiente';

    if (btnAprobar) {
        btnAprobar.style.display = isPendiente ? 'inline-flex' : 'none';
    }
    if (btnRechazar) {
        btnRechazar.style.display = isPendiente ? 'inline-flex' : 'none';
    }

    const modal = document.getElementById('modalRegistro');
    if (modal) {
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');
    }
}

function aprobarRegistroDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-registro-${currentModalEntityId}`);
    const nombre = row ? row.getAttribute('data-nombre') : 'Solicitante';
    ejecutarAccionRegistro(currentModalEntityId, 'aprobar_registro', nombre);
    cerrarModales();
}

function rechazarRegistroDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-registro-${currentModalEntityId}`);
    const nombre = row ? row.getAttribute('data-nombre') : 'Solicitante';
    ejecutarAccionRegistro(currentModalEntityId, 'rechazar_registro', nombre);
    cerrarModales();
}

function ejecutarAccionRegistro(id, accion, nombre) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    // Deshabilitar temporalmente los botones mientras se procesa la solicitud
    const buttons = row.querySelectorAll('button');
    buttons.forEach(b => b.disabled = true);

    const formData = new FormData();
    formData.append('action', accion);
    formData.append('usuario_id', id);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('administracion.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en el servidor (' + response.status + ')');
        }
        return response.json();
    })
    .then(data => {
        buttons.forEach(b => b.disabled = false);
        if (data && data.success) {
            const nuevoEstado = (accion === 'aprobar_registro') ? 'Aprobado' : 'Rechazado';
            row.setAttribute('data-estado', nuevoEstado);

            // Actualizar botones de acción a etiqueta de estado definitivo manteniendo "Revisar"
            const actionsGroup = row.querySelector('.actions-button-group');
            if (actionsGroup) {
                actionsGroup.innerHTML = `
                    <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud" onclick="abrirModalRegistro(${id})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Revisar</span>
                    </button>
                    <span class="${accion === 'aprobar_registro' ? 'action-confirmed-tag' : 'action-rejected-tag'}">
                        ${accion === 'aprobar_registro' ? '✓ Aprobado' : '✕ Rechazado'}
                    </span>
                `;
            }

            // Actualizar contadores visuales
            actualizarKpi('kpiRegistros', -1);
            actualizarBadgeContador('badgeCountRegistros', -1);
            if (accion === 'aprobar_registro') {
                actualizarKpi('kpiEgresados', 1);
            }

            const mensajeToast = data.message || ((accion === 'aprobar_registro') 
                ? `Se ha aceptado la solicitud de ${nombre} y se ha notificado por correo.` 
                : `Se ha rechazado la solicitud de ${nombre} y se ha notificado por correo.`);

            mostrarToastAdmin(
                (accion === 'aprobar_registro') ? '¡Registro Aceptado!' : 'Solicitud Rechazada',
                mensajeToast
            );
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error en la operación', (data && data.message) ? data.message : 'No se pudo completar la acción.', 'error');
        }
    })
    .catch(err => {
        buttons.forEach(b => b.disabled = false);
        console.error('Error de comunicación con el backend:', err);
        mostrarToastAdmin('Error de conexión', 'No fue posible comunicar con el servidor.', 'error');
    });
}

// E. Modal 2: Moderación de Contenido
let currentModalEntityType = null;

function abrirModalContenido(tipo, id) {
    const row = document.getElementById(`row-contenido-${tipo}-${id}`);
    if (!row) return;

    currentModalEntityId = id;
    currentModalEntityType = tipo;

    const tipoLabel = row.getAttribute('data-tipo-label') || tipo;
    const titulo = row.getAttribute('data-titulo') || '';
    const autor = row.getAttribute('data-autor') || '';
    const cat = row.getAttribute('data-categoria') || '';
    const fecha = row.getAttribute('data-fecha') || '';
    const estado = row.getAttribute('data-estado') || 'Pendiente';
    const comp = row.getAttribute('data-contenido') || '';

    document.getElementById('modalContTipo').textContent = tipoLabel;
    document.getElementById('modalContTipo').className = `type-pill type-${tipo.toLowerCase()}`;
    document.getElementById('modalContCategoria').textContent = cat;
    document.getElementById('modalContEstado').textContent = estado;
    document.getElementById('modalContEstado').className = `status-pill status-${estado.toLowerCase()}`;
    document.getElementById('modalContTitulo').textContent = titulo;
    document.getElementById('modalContAutor').textContent = autor;
    document.getElementById('modalContFecha').textContent = fecha;
    document.getElementById('modalContCompleto').textContent = comp;

    const btnAprobar = document.getElementById('btnModalPublicarCont');
    const btnRechazar = document.getElementById('btnModalRechazarCont');
    const isPendiente = estado.toLowerCase() === 'pendiente';
    if (btnAprobar) btnAprobar.style.display = isPendiente ? 'inline-flex' : 'none';
    if (btnRechazar) btnRechazar.style.display = isPendiente ? 'inline-flex' : 'none';

    const modal = document.getElementById('modalContenido');
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
}

function publicarContenidoDesdeModal() {
    if (!currentModalEntityId || !currentModalEntityType) return;
    const row = document.getElementById(`row-contenido-${currentModalEntityType}-${currentModalEntityId}`);
    const titulo = row ? row.getAttribute('data-titulo') : 'Contenido';
    publicarContenidoDirecto(currentModalEntityType, currentModalEntityId, titulo);
    cerrarModales();
}

function rechazarContenidoDesdeModal() {
    if (!currentModalEntityId || !currentModalEntityType) return;
    const row = document.getElementById(`row-contenido-${currentModalEntityType}-${currentModalEntityId}`);
    const titulo = row ? row.getAttribute('data-titulo') : 'Contenido';
    rechazarContenidoDirecto(currentModalEntityType, currentModalEntityId, titulo);
    cerrarModales();
}

function publicarContenidoDirecto(tipo, id, titulo) {
    ejecutarAccionContenido(tipo, id, 'aprobar_contenido', titulo);
}

function rechazarContenidoDirecto(tipo, id, titulo) {
    ejecutarAccionContenido(tipo, id, 'rechazar_contenido', titulo);
}

function ejecutarAccionContenido(tipo, id, accion, titulo) {
    const row = document.getElementById(`row-contenido-${tipo}-${id}`);
    if (!row) return;

    const buttons = row.querySelectorAll('button');
    buttons.forEach(b => b.disabled = true);

    const formData = new FormData();
    formData.append('action', accion);
    formData.append('tipo', tipo);
    formData.append('id', id);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('administracion.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en el servidor (' + response.status + ')');
        }
        return response.json();
    })
    .then(data => {
        buttons.forEach(b => b.disabled = false);
        if (data && data.success) {
            const nuevoEstado = (accion === 'aprobar_contenido') ? 'Publicado' : 'Rechazado';
            row.setAttribute('data-estado', nuevoEstado);

            const statusCell = row.querySelector('.status-pill');
            if (statusCell) {
                statusCell.className = `status-pill status-${nuevoEstado.toLowerCase()}`;
                statusCell.textContent = nuevoEstado;
            }

            const actionsGroup = row.querySelector('.actions-button-group');
            if (actionsGroup) {
                actionsGroup.innerHTML = `
                    <button type="button" class="btn-action-sm btn-action-view" title="Examinar y publicar" onclick="abrirModalContenido('${tipo}', ${id})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Examinar</span>
                    </button>
                    <span class="${accion === 'aprobar_contenido' ? 'action-confirmed-tag' : 'action-rejected-tag'}">
                        ${accion === 'aprobar_contenido' ? '✓ Publicado' : '✕ Rechazado'}
                    </span>
                `;
            }

            actualizarKpi('kpiContenidos', -1);
            actualizarBadgeContador('badgeCountContenidos', -1);

            const tituloToast = (accion === 'aprobar_contenido') ? '¡Publicación Aprobada!' : 'Contenido Rechazado';
            mostrarToastAdmin(tituloToast, data.message || `"${titulo}" fue actualizado.`);
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error en la operación', (data && data.message) ? data.message : 'No se pudo completar la moderación.', 'error');
        }
    })
    .catch(err => {
        buttons.forEach(b => b.disabled = false);
        console.error('Error al moderar contenido:', err);
        mostrarToastAdmin('Error de conexión', 'No fue posible comunicar con el servidor.', 'error');
    });
}

// F. Modal 3: Resolución de PQRS
function abrirModalPQRS(radicado) {
    const row = document.getElementById(`row-pqrs-${radicado}`);
    if (!row) return;

    currentModalEntityId = radicado;
    const tipo = row.getAttribute('data-tipo');
    const remitente = row.getAttribute('data-remitente');
    const correo = row.getAttribute('data-correo');
    const asunto = row.getAttribute('data-asunto');
    const fecha = row.getAttribute('data-fecha');
    const prioridad = row.getAttribute('data-prioridad');
    const estado = row.getAttribute('data-estado');
    const desc = row.getAttribute('data-descripcion');
    const resp = row.getAttribute('data-respuesta');

    document.getElementById('modalPQRSRadicadoCode').textContent = radicado;
    document.getElementById('modalPQRSTipo').textContent = tipo;
    document.getElementById('modalPQRSTipo').className = `type-pill type-${tipo.toLowerCase().replace(/ /g, '-')}`;
    document.getElementById('modalPQRSPrioridad').textContent = `Prioridad ${prioridad}`;
    document.getElementById('modalPQRSPrioridad').className = `priority-badge priority-${prioridad.toLowerCase()}`;
    document.getElementById('modalPQRSEstado').textContent = estado;
    document.getElementById('modalPQRSEstado').className = `status-pill status-${estado.toLowerCase()}`;
    
    document.getElementById('modalPQRSRemitente').textContent = remitente;
    document.getElementById('modalPQRSCorreo').textContent = correo;
    document.getElementById('modalPQRSFecha').textContent = fecha;
    document.getElementById('modalPQRSAsunto').textContent = asunto;
    document.getElementById('modalPQRSDescripcion').textContent = desc;

    document.getElementById('adminRespuestaPQRS').value = resp || '';

    const modal = document.getElementById('modalPQRS');
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
}

function enviarRespuestaPQRSDesdeModal() {
    if (!currentModalEntityId) return;
    const respuesta = document.getElementById('adminRespuestaPQRS').value.trim();
    if (!respuesta) {
        alert('Por favor redacta la respuesta institucional oficial antes de enviar.');
        document.getElementById('adminRespuestaPQRS').focus();
        return;
    }

    const row = document.getElementById(`row-pqrs-${currentModalEntityId}`);
    const remitente = row ? row.getAttribute('data-remitente') : 'Ciudadano';

    resolverPQRSDirecto(currentModalEntityId, remitente, respuesta);
    cerrarModales();
}

function resolverPQRSDirecto(radicado, remitente, respuesta = '') {
    const row = document.getElementById(`row-pqrs-${radicado}`);
    if (!row) return;

    const formData = new FormData();
    formData.append('action', 'responder_pqrs');
    formData.append('radicado', radicado);
    formData.append('respuesta', respuesta);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('administracion.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            row.setAttribute('data-estado', 'Resuelto');
            row.setAttribute('data-respuesta', respuesta);
            const statusCell = row.querySelector('.status-pill');
            if (statusCell) {
                statusCell.className = 'status-pill status-resuelto';
                statusCell.textContent = 'Resuelto';
            }

            const actionsGroup = row.querySelector('.actions-button-group');
            if (actionsGroup) {
                actionsGroup.innerHTML = `
                    <button type="button" class="btn-action-sm btn-action-view" title="Atender y responder caso" onclick="abrirModalPQRS('${radicado}')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Ver Caso</span>
                    </button>
                    <span class="action-confirmed-tag">✓ Resuelto</span>
                `;
            }

            actualizarKpi('kpiPQRS', -1);
            actualizarBadgeContador('badgeCountPQRS', -1);

            mostrarToastAdmin('¡Caso Resuelto!', `Respuesta guardada para ${remitente} con radicado ${radicado}.`);
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error al responder', (data && data.message) ? data.message : 'No se pudo guardar la respuesta.', 'error');
        }
    })
    .catch(err => {
        console.error('Error enviando respuesta PQRS:', err);
        mostrarToastAdmin('Error de conexión', 'No fue posible comunicar con el servidor.', 'error');
    });
}

// G. Notificación Toast Flotante
function mostrarToastAdmin(titulo, mensaje) {
    const toast = document.getElementById('adminToast');
    if (!toast) return;

    document.getElementById('adminToastTitle').textContent = titulo;
    document.getElementById('adminToastDesc').textContent = mensaje;

    clearTimeout(toastTimeout);
    toast.classList.add('is-visible');

    toastTimeout = setTimeout(() => {
        ocultarToastAdmin();
    }, 3800);
}

function ocultarToastAdmin() {
    const toast = document.getElementById('adminToast');
    if (toast) {
        toast.classList.remove('is-visible');
    }
}

// H. Funciones Auxiliares de Actualización de Métricas
function actualizarKpi(kpiId, delta) {
    const el = document.getElementById(kpiId);
    if (!el) return;
    let val = parseInt(el.textContent.replace(/,/g, ''), 10) || 0;
    val = Math.max(0, val + delta);
    el.textContent = val;
}

function actualizarBadgeContador(badgeId, delta) {
    const el = document.getElementById(badgeId);
    if (!el) return;
    let val = parseInt(el.textContent, 10) || 0;
    val = Math.max(0, val + delta);
    el.textContent = val;
}

// I. Exportar Reporte Simulado
function exportarReporteAdmin() {
    mostrarToastAdmin('Generando Reporte', 'El reporte consolidado de egresados y moderación se está descargando.');
}

// Inicialización de la vista
document.addEventListener('DOMContentLoaded', () => {
    filtrarTablaActual();
});
</script>

<!-- Carga del pie de página y scripts institucionales -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
