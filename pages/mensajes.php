<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Mensajes / Comunicaciones (Panel Privado)
 * Funcionalidad: Gestión de solicitudes de conexión y chat interactivo entre egresados
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.2
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
if (!headers_sent()) {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

// 1. Control de acceso para usuarios autenticados
if (!isAuthenticated()) {
    header('Location: index.php?page=login');
    exit;
}

$currentUser   = currentUser();
$currentUserId = (int)($currentUser['id'] ?? 0);

if ($currentUserId <= 0) {
    header('Location: index.php?page=login');
    exit;
}

// 2. Definición de variables de entorno y vista
$seccion_activa = 'mensajes';
$titulo_seccion = 'Mensajes';
$titulo_pagina = 'Mensajes — Raíces Alzate';

$currentPage = 'mensajes';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$userName    = trim($currentUser['nombre_completo'] ?? $currentUser['nombre'] ?? 'Usuario Alzatista');
$userRole    = trim($currentUser['rol_nombre'] ?? $currentUser['rol'] ?? 'Egresado');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';

// Hojas de estilo y scripts modulares para includes globales
$extraCss = ['dashboard.css', 'mensajes.css'];
$extraJs = ['assets/js/mensajes.js'];

// =========================================================================
// 3. CONSULTAS A LA BASE DE DATOS MYSQL (100% DATOS REALES)
// =========================================================================

$solicitudesConexion = [];
$conversaciones = [];
$totalNoLeidos = 0;
$totalSolicitudes = 0;

if (!empty($pdo) && $currentUserId > 0) {
    try {
        $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?")->execute([$currentUserId]);
    } catch (PDOException $e) {
        error_log('Error actualizando ultimo_acceso en mensajes.php: ' . $e->getMessage());
    }
}

if (!empty($pdo)) {
    // 3.1. Cargar Solicitudes de Conexión Entrantes (Receptor = Usuario en sesión)
    try {
        $stmtSol = $pdo->prepare("
            SELECT 
                c.id AS conexion_id,
                c.solicitante_id,
                c.creado_en AS fecha_solicitud,
                u.id AS usuario_id,
                u.nombre_completo,
                pe.anio_graduacion,
                pe.titulo_institucional,
                pe.area_especialidad,
                pe.ocupacion,
                pe.empresa,
                pe.ciudad,
                pe.foto_perfil
            FROM conexiones c
            JOIN usuarios u ON u.id = c.solicitante_id
            LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
            WHERE c.receptor_id = :cur AND c.estado = 'pendiente' AND u.activo = 1
            ORDER BY c.creado_en DESC
        ");
        $stmtSol->execute([':cur' => $currentUserId]);
        $solRows = $stmtSol->fetchAll(PDO::FETCH_ASSOC);

        $coloresAvatar = ['avatar-teal', 'avatar-purple', 'avatar-blue', 'avatar-amber', 'avatar-green', 'avatar-red'];

        foreach ($solRows as $s) {
            $solNombre = trim($s['nombre_completo'] ?? 'Egresado Alzatista');
            $solInicial = !empty($solNombre) ? mb_strtoupper(mb_substr($solNombre, 0, 1, 'UTF-8'), 'UTF-8') : 'E';
            $solPromo = !empty($s['anio_graduacion']) ? (string)$s['anio_graduacion'] : 'Alzatista';
            $solEnfasis = !empty($s['area_especialidad']) ? $s['area_especialidad'] : (!empty($s['titulo_institucional']) ? $s['titulo_institucional'] : 'Bachillerato Académico');
            $cargoParts = array_filter([$s['ocupacion'] ?? '', !empty($s['empresa']) ? 'en ' . $s['empresa'] : '']);
            $solCargo = !empty($cargoParts) ? implode(' ', $cargoParts) : 'Egresado Alzatista';
            $solUbicacion = !empty($s['ciudad']) ? $s['ciudad'] . ', Colombia' : 'Medellín, Colombia';
            $solColor = $coloresAvatar[$s['solicitante_id'] % count($coloresAvatar)];

            $solFotoUrl = '';
            if (!empty($s['foto_perfil'])) {
                $cleanPath = ltrim($s['foto_perfil'], '/');
                if (file_exists(__DIR__ . '/' . $cleanPath)) {
                    $solFotoUrl = $cleanPath;
                }
            }

            // Formatear tiempo relativo
            $segundos = time() - strtotime($s['fecha_solicitud']);
            if ($segundos < 3600) {
                $solFecha = 'Hace ' . max(1, (int)($segundos / 60)) . ' min';
            } elseif ($segundos < 86400) {
                $solFecha = 'Hace ' . (int)($segundos / 3600) . ' horas';
            } elseif ($segundos < 172800) {
                $solFecha = 'Ayer';
            } else {
                $solFecha = 'Hace ' . (int)($segundos / 86400) . ' días';
            }

            // Conexiones en común (conteo real de conexiones aceptadas que comparten)
            $stmtMutual = $pdo->prepare("
                SELECT COUNT(*) FROM (
                    SELECT CASE WHEN solicitante_id = :u1 THEN receptor_id ELSE solicitante_id END AS u_comun
                    FROM conexiones WHERE (solicitante_id = :u2 OR receptor_id = :u3) AND estado = 'aceptada'
                ) AS a
                JOIN (
                    SELECT CASE WHEN solicitante_id = :s1 THEN receptor_id ELSE solicitante_id END AS u_comun
                    FROM conexiones WHERE (solicitante_id = :s2 OR receptor_id = :s3) AND estado = 'aceptada'
                ) AS b ON a.u_comun = b.u_comun
            ");
            $stmtMutual->execute([
                ':u1' => $currentUserId,
                ':u2' => $currentUserId,
                ':u3' => $currentUserId,
                ':s1' => (int)$s['solicitante_id'],
                ':s2' => (int)$s['solicitante_id'],
                ':s3' => (int)$s['solicitante_id']
            ]);
            $conexionesComun = (int)$stmtMutual->fetchColumn();

            $solicitudesConexion[] = [
                'id'          => (int)$s['solicitante_id'],
                'nombre'      => $solNombre,
                'promocion'   => $solPromo,
                'enfasis'     => $solEnfasis,
                'cargo'       => $solCargo,
                'ubicacion'   => $solUbicacion,
                'conexiones'  => $conexionesComun,
                'fecha'       => $solFecha,
                'color'       => $solColor,
                'inicial'     => $solInicial,
                'foto_url'    => $solFotoUrl
            ];
        }
    } catch (PDOException $e) {
        error_log('Error cargando solicitudes en mensajes.php: ' . $e->getMessage());
        $solicitudesConexion = [];
    }

    // 3.2. Cargar Contactos Conectados Mutuamente y sus Mensajes Reales
    try {
        $stmtConectados = $pdo->prepare("
            SELECT 
                CASE WHEN c.solicitante_id = :u1 THEN c.receptor_id ELSE c.solicitante_id END AS contacto_id,
                c.actualizado_en AS fecha_conexion,
                u.id,
                u.nombre_completo,
                u.email,
                u.ultimo_acceso,
                pe.anio_graduacion,
                pe.ocupacion,
                pe.empresa,
                pe.foto_perfil
            FROM conexiones c
            JOIN usuarios u ON u.id = (CASE WHEN c.solicitante_id = :u2 THEN c.receptor_id ELSE c.solicitante_id END)
            LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
            WHERE (c.solicitante_id = :u3 OR c.receptor_id = :u4)
              AND c.estado = 'aceptada'
              AND u.activo = 1
            ORDER BY c.actualizado_en DESC
        ");
        $stmtConectados->execute([
            ':u1' => $currentUserId,
            ':u2' => $currentUserId,
            ':u3' => $currentUserId,
            ':u4' => $currentUserId
        ]);
        $contactosRows = $stmtConectados->fetchAll(PDO::FETCH_ASSOC);

        $coloresAvatar = ['avatar-blue', 'avatar-green', 'avatar-teal', 'avatar-purple', 'avatar-orange', 'avatar-red'];

        foreach ($contactosRows as $cRow) {
            $cid = (int)$cRow['contacto_id'];
            $cNombre = trim($cRow['nombre_completo'] ?? 'Egresado Alzatista');
            $cInicial = !empty($cNombre) ? mb_strtoupper(mb_substr($cNombre, 0, 1, 'UTF-8'), 'UTF-8') : 'E';
            $cPromo = !empty($cRow['anio_graduacion']) ? (string)$cRow['anio_graduacion'] : 'Alzatista';
            $cargoParts = array_filter([$cRow['ocupacion'] ?? '', !empty($cRow['empresa']) ? 'en ' . $cRow['empresa'] : '']);
            $cCargo = !empty($cargoParts) ? implode(' ', $cargoParts) : 'Egresado Alzatista';
            $cColor = $coloresAvatar[$cid % count($coloresAvatar)];

            $cFotoUrl = '';
            if (!empty($cRow['foto_perfil'])) {
                $cleanPath = ltrim($cRow['foto_perfil'], '/');
                if (file_exists(__DIR__ . '/' . $cleanPath)) {
                    $cFotoUrl = $cleanPath;
                }
            }

            // Estado online
            $ultimoAccesoTs = !empty($cRow['ultimo_acceso']) ? strtotime($cRow['ultimo_acceso']) : 0;
            $diffMinutos = (time() - $ultimoAccesoTs) / 60;
            $cOnline = ($diffMinutos <= 20);
            $cEstado = $cOnline ? 'En línea' : ($diffMinutos <= 1440 ? 'Desconectado hace ' . max(1, (int)($diffMinutos / 60)) . ' h' : 'Desconectado');

            // Mensajes reales del hilo
            $stmtMsg = $pdo->prepare("
                SELECT id, remitente_id, destinatario_id, mensaje, leido, creado_en
                FROM mensajes
                WHERE (remitente_id = :cur1 AND destinatario_id = :cid1)
                   OR (remitente_id = :cid2 AND destinatario_id = :cur2)
                ORDER BY creado_en ASC, id ASC
            ");
            $stmtMsg->execute([
                ':cur1' => $currentUserId,
                ':cid1' => $cid,
                ':cid2' => $cid,
                ':cur2' => $currentUserId
            ]);
            $msgRows = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);

            $mensajesList = [];
            $noLeidos = 0;
            $ultimoMsgTs = strtotime($cRow['fecha_conexion']);
            $horaUltimo = 'Hoy';

            foreach ($msgRows as $m) {
                $esPropio = ((int)$m['remitente_id'] === $currentUserId);
                if (!$esPropio && empty($m['leido'])) {
                    $noLeidos++;
                }
                $mTs = strtotime($m['creado_en']);
                $ultimoMsgTs = $mTs;
                $horaUltimo = date('g:i A', $mTs);
                $mensajesList[] = [
                    'id'    => (int)$m['id'],
                    'tipo'  => $esPropio ? 'outgoing' : 'incoming',
                    'texto' => $m['mensaje'],
                    'hora'  => $horaUltimo
                ];
            }

            $conversaciones[] = [
                'id'          => $cid,
                'nombre'      => $cNombre,
                'promocion'   => $cPromo,
                'cargo'       => $cCargo,
                'estado'      => $cEstado,
                'online'      => $cOnline,
                'color'       => $cColor,
                'inicial'     => $cInicial,
                'foto_url'    => $cFotoUrl,
                'no_leidos'   => $noLeidos,
                'hora'        => $horaUltimo,
                'ultimo_ts'   => $ultimoMsgTs,
                'mensajes'    => $mensajesList
            ];
        }

        // Ordenar conversaciones: las que tengan mensajes o actividad más reciente primero
        usort($conversaciones, function ($a, $b) {
            return $b['ultimo_ts'] <=> $a['ultimo_ts'];
        });

    } catch (PDOException $e) {
        error_log('Error cargando conversaciones en mensajes.php: ' . $e->getMessage());
        $conversaciones = [];
    }
}

// Métricas de insignias reales
foreach ($conversaciones as $c) {
    $totalNoLeidos += (int)($c['no_leidos'] ?? 0);
}
$totalSolicitudes = count($solicitudesConexion);

// Contacto activo por parámetro URL ?contacto=ID o ?id=ID, o primer contacto de la lista
$requestedContactId = isset($_GET['contacto']) ? (int)$_GET['contacto'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
$contactoActivo = null;

if ($requestedContactId > 0) {
    foreach ($conversaciones as $c) {
        if ($c['id'] === $requestedContactId) {
            $contactoActivo = $c;
            break;
        }
    }
}

if (!$contactoActivo && !empty($conversaciones)) {
    $contactoActivo = $conversaciones[0];
}

// Marcar como leídos los mensajes del contacto activo
if ($contactoActivo && !empty($pdo) && $currentUserId > 0) {
    try {
        $stmtMarkRead = $pdo->prepare("
            UPDATE mensajes 
            SET leido = 1 
            WHERE remitente_id = :cid AND destinatario_id = :uid AND leido = 0
        ");
        $stmtMarkRead->execute([
            ':cid' => (int)$contactoActivo['id'],
            ':uid' => $currentUserId
        ]);
        $contactoActivo['no_leidos'] = 0;
    } catch (PDOException $e) {
        error_log('Error marcando mensajes como leídos: ' . $e->getMessage());
    }
}

// Inclusión del layout global de cabecera
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido con el ítem Mensajes activo -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="content-area dashboard-content mensajes-content-wrapper" id="mensajesMainContent" tabindex="-1">
            <input type="hidden" id="csrfTokenMensajes" value="<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <!-- Encabezado de la pantalla -->
            <header class="section-header mensajes-header-block">
                <h1 class="section-title mensajes-header-title">
                    Mensajes y Conexiones
                </h1>
                <p class="section-subtitle mensajes-header-subtitle">
                    Gestiona tus solicitudes de contacto y conversa en tiempo real con los egresados con los que ya has conectado.
                </p>
            </header>

            <!-- Pestañas de navegación: Conectados vs Solicitudes de conexión -->
            <nav class="mensajes-nav-tabs" aria-label="Navegación de mensajes y solicitudes">
                <button type="button" class="mensajes-tab-btn active" id="tabConectados" data-tab="chats" aria-selected="true" role="tab">
                    <svg class="mensajes-tab-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Conectados</span>
                    <?php if ($totalNoLeidos > 0): ?>
                    <span class="mensajes-tab-badge" id="badgeTotalUnread"><?= $totalNoLeidos ?></span>
                    <?php endif; ?>
                </button>

                <button type="button" class="mensajes-tab-btn" id="tabSolicitudes" data-tab="solicitudes" aria-selected="false" role="tab">
                    <svg class="mensajes-tab-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="19" y1="8" x2="19" y2="14"></line>
                        <line x1="22" y1="11" x2="16" y2="11"></line>
                    </svg>
                    <span>Solicitudes</span>
                    <?php if ($totalSolicitudes > 0): ?>
                    <span class="mensajes-tab-badge mensajes-badge-alert" id="badgePendingRequests"><?= $totalSolicitudes ?></span>
                    <?php endif; ?>
                </button>
            </nav>

            <!-- ============================================================= -->
            <!-- CONTENEDOR PRINCIPAL DEL CHAT Y DE SOLICITUDES (2 COLUMNAS)   -->
            <!-- ============================================================= -->
            <section class="mensajes-chat-wrapper chat-main-container" id="mensajesChatWrapper" aria-label="Área de chat y mensajería">
                
                <!-- COLUMNA IZQUIERDA: Directorio de contactos conectados (340px) -->
                <aside class="mensajes-contacts-pane chat-sidebar-list" id="panelConectados" aria-label="Lista de conversaciones con egresados">
                    <div class="contacts-header">
                        <div class="contacts-title-row">
                            <h2 class="contacts-title">Contactos conectados</h2>
                            <span class="contacts-count-tag" id="contactsCountTag"><?= count($conversaciones) ?> activos</span>
                        </div>
                        <div class="contacts-search-box">
                            <span class="contacts-search-icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </span>
                            <input 
                                type="text" 
                                class="contacts-search-input" 
                                id="inputSearchContacts" 
                                placeholder="Buscar contacto o profesión..." 
                                aria-label="Buscar en contactos"
                                autocomplete="off"
                            >
                            <button type="button" class="contacts-search-clear" id="btnClearContactsSearch" aria-label="Limpiar búsqueda">&times;</button>
                        </div>
                    </div>

                    <div class="contacts-scroll-area" id="contactsScrollArea">
                        <?php if (empty($conversaciones)): ?>
                        <div class="contacts-empty-list" style="padding: 32px 16px; text-align: center; color: #757575;">
                            <p style="margin: 0 0 10px 0; font-size: 0.92rem;">Aún no tienes contactos conectados.</p>
                            <a href="index.php?page=egresados&view=panel" style="color: #1565C0; font-weight: 600; font-size: 0.88rem; text-decoration: none;">Explorar egresados &rarr;</a>
                        </div>
                        <?php else: ?>
                            <?php foreach ($conversaciones as $idx => $contacto): 
                                $isActive = ($contactoActivo && (int)$contacto['id'] === (int)$contactoActivo['id']);
                                $lastMsg = !empty($contacto['mensajes']) ? end($contacto['mensajes']) : null;
                                $lastMsgText = $lastMsg ? $lastMsg['texto'] : 'Inicia la conversación';
                                if ($lastMsg && $lastMsg['tipo'] === 'outgoing') {
                                    $lastMsgText = 'Tú: ' . $lastMsgText;
                                }
                            ?>
                            <div 
                                class="contact-card-item <?= $isActive ? 'active' : '' ?>" 
                                data-contact-id="<?= (int)$contacto['id'] ?>"
                                data-name="<?= htmlspecialchars($contacto['nombre']) ?>"
                                data-role="<?= htmlspecialchars($contacto['cargo'] . ' · Prom. ' . $contacto['promocion']) ?>"
                                data-status="<?= htmlspecialchars($contacto['estado']) ?>"
                                data-online="<?= $contacto['online'] ? 'true' : 'false' ?>"
                                data-initial="<?= htmlspecialchars($contacto['inicial']) ?>"
                                data-color="<?= htmlspecialchars($contacto['color']) ?>"
                                data-avatar-img="<?= htmlspecialchars($contacto['foto_url'] ?? '') ?>"
                                role="button"
                                tabindex="0"
                            >
                                <div class="contact-avatar-wrapper">
                                    <div class="contact-avatar <?= empty($contacto['foto_url']) ? htmlspecialchars($contacto['color']) : '' ?>">
                                        <?php if (!empty($contacto['foto_url'])): ?>
                                            <img src="<?= htmlspecialchars($contacto['foto_url']) ?>" alt="<?= htmlspecialchars($contacto['nombre']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        <?php else: ?>
                                            <?= htmlspecialchars($contacto['inicial']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <span class="contact-status-dot <?= $contacto['online'] ? 'online' : '' ?>"></span>
                                </div>
                                <div class="contact-meta-info">
                                    <div class="contact-row-top">
                                        <span class="contact-name"><?= htmlspecialchars($contacto['nombre']) ?></span>
                                        <span class="contact-time"><?= htmlspecialchars($contacto['hora']) ?></span>
                                    </div>
                                    <span class="contact-role-snippet"><?= htmlspecialchars($contacto['cargo']) ?></span>
                                    <div class="contact-row-bottom">
                                        <span class="contact-last-msg"><?= htmlspecialchars($lastMsgText) ?></span>
                                        <?php if (!empty($contacto['no_leidos'])): ?>
                                        <span class="contact-unread-badge"><?= (int)$contacto['no_leidos'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="contacts-empty-search" id="contactsEmptySearch">
                            No se encontraron egresados con ese criterio.
                        </div>
                    </div>
                </aside>

                <!-- Panel lateral de solicitudes reservado para compatibilidad con scripts pero oculto -->
                <aside class="mensajes-contacts-pane chat-sidebar-list d-none" id="panelSolicitudes" style="display: none !important;" aria-label="Resumen de solicitudes de conexión" aria-hidden="true">
                </aside>

                <!-- COLUMNA DERECHA 1: Área de Conversación del Chat (.chat-conversation-area) -->
                <section class="mensajes-thread-pane chat-conversation-area" id="chatConversationArea" aria-label="Hilo de conversación activa">
                    
                    <!-- Cabecera de la conversación activa (.conversation-header) -->
                    <div class="thread-header conversation-header" id="threadHeader" style="<?= empty($contactoActivo) ? 'display: none !important;' : '' ?>">
                        <a href="index.php?page=detalle-egresado&id=<?= (int)($contactoActivo['id'] ?? 0) ?>" class="thread-header-left conversation-header-user" id="linkHeaderUser" style="text-decoration: none; color: inherit;" title="Ver perfil institucional de <?= htmlspecialchars($contactoActivo['nombre'] ?? '') ?>">
                            <button type="button" class="btn-back-contacts" id="btnBackToContacts" aria-label="Volver a lista de contactos" onclick="event.preventDefault(); event.stopPropagation();">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <div class="thread-avatar <?= empty($contactoActivo['foto_url']) ? htmlspecialchars($contactoActivo['color'] ?? 'avatar-blue') : '' ?>" id="threadAvatar">
                                <?php if (!empty($contactoActivo['foto_url'])): ?>
                                    <img src="<?= htmlspecialchars($contactoActivo['foto_url']) ?>" alt="<?= htmlspecialchars($contactoActivo['nombre'] ?? '') ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <?= htmlspecialchars($contactoActivo['inicial'] ?? 'E') ?>
                                <?php endif; ?>
                            </div>
                            <div class="thread-user-meta">
                                <h2 class="thread-user-name" id="threadUserName"><?= htmlspecialchars($contactoActivo['nombre'] ?? '') ?></h2>
                                <span class="thread-user-status-text" id="threadUserStatusText">
                                    <?php if (!empty($contactoActivo['online'])): ?>
                                    <span class="thread-online-indicator"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($contactoActivo['estado'] ?? 'Desconectado') ?>
                                </span>
                            </div>
                        </a>

                        <div class="thread-header-actions conversation-header-actions">
                            <a href="index.php?page=detalle-egresado&id=<?= (int)($contactoActivo['id'] ?? 0) ?>" class="btn-view-profile" id="btnHeaderVerPerfil" title="Ver perfil institucional completo">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Ver perfil</span>
                            </a>
                        </div>
                    </div>

                    <!-- Cuerpo de mensajes con scroll automático -->
                    <div class="thread-messages-body" id="threadMessagesBody" style="<?= empty($contactoActivo) ? 'display: none !important;' : '' ?>">
                        
                        <?php foreach ($conversaciones as $cIndex => $conv): 
                            $displayStyle = ($contactoActivo && (int)$conv['id'] === (int)$contactoActivo['id']) ? 'flex' : 'none';
                        ?>
                        <div class="chat-thread-container" id="thread-messages-<?= (int)$conv['id'] ?>" style="display: <?= $displayStyle ?>; flex-direction: column; gap: 16px;">
                            <div class="thread-date-separator">
                                <span class="thread-date-badge">Hoy</span>
                            </div>

                            <?php if (empty($conv['mensajes'])): ?>
                            <div class="chat-system-notice" style="text-align: center; color: #757575; font-size: 0.88rem; padding: 24px 0;">
                                Has conectado con <?= htmlspecialchars($conv['nombre']) ?>. ¡Inicia la conversación!
                            </div>
                            <?php else: ?>
                                <?php foreach ($conv['mensajes'] as $m): ?>
                                <div class="msg-row <?= ($m['tipo'] === 'outgoing') ? 'outgoing' : 'incoming' ?>" data-msg-id="<?= (int)$m['id'] ?>">
                                    <?php if ($m['tipo'] === 'incoming'): ?>
                                    <div class="msg-mini-avatar <?= empty($conv['foto_url']) ? htmlspecialchars($conv['color']) : '' ?>">
                                        <?php if (!empty($conv['foto_url'])): ?>
                                            <img src="<?= htmlspecialchars($conv['foto_url']) ?>" alt="<?= htmlspecialchars($conv['nombre']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        <?php else: ?>
                                            <?= htmlspecialchars($conv['inicial']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

                                    <div class="msg-bubble">
                                        <?= htmlspecialchars($m['texto']) ?>
                                        <div class="msg-footer-meta">
                                            <span><?= htmlspecialchars($m['hora']) ?></span>
                                            <?php if ($m['tipo'] === 'outgoing'): ?>
                                            <span class="msg-status-check" title="Entregado">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6L7 17l-5-5"></path>
                                                    <path d="M22 10l-7.5 7.5L13 16"></path>
                                                </svg>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>

                        <!-- Indicador animado de "escribiendo..." (activado por JS) -->
                        <div class="typing-indicator-row" id="typingIndicatorRow">
                            <span id="typingIndicatorName"><?= htmlspecialchars($contactoActivo['nombre'] ?? 'Egresado') ?></span> está escribiendo
                            <div class="typing-dots">
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                            </div>
                        </div>

                    </div>

                    <!-- Barra inferior de redacción y envío -->
                    <div class="thread-composer-box" id="threadComposerBox" style="<?= empty($contactoActivo) ? 'display: none !important;' : '' ?>">
                        <div class="composer-input-wrapper">
                            <textarea 
                                class="composer-textarea" 
                                id="inputChatMessage" 
                                rows="1" 
                                placeholder="Escribe un mensaje aquí... (Presiona Enter para enviar)"
                                aria-label="Redactar mensaje"
                            ></textarea>
                        </div>
                        <button type="button" class="btn-send-message" id="btnSendMessage" title="Enviar mensaje" aria-label="Enviar mensaje">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>

                    <!-- Estado vacío cuando no hay contactos conectados aún o ninguno seleccionado -->
                    <div class="chat-empty-selection" id="chatEmptySelection" style="<?= !empty($contactoActivo) ? 'display: none !important;' : 'flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 48px 24px; text-align: center; color: #757575;' ?>">
                        <div style="width: 68px; height: 68px; border-radius: 50%; background: #E3F2FD; color: #1565C0; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <h3 style="margin: 0 0 10px 0; font-size: 1.25rem; font-weight: 700; color: #212121;">No hay conversaciones activas</h3>
                        <p style="margin: 0 0 24px 0; max-width: 440px; font-size: 0.95rem; line-height: 1.5; color: #616161;">Conecta con otros egresados alzatistas para iniciar una conversación directa o revisa las solicitudes pendientes en la pestaña superior.</p>
                        <a href="index.php?page=egresados&view=panel" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; background: #1565C0; color: #ffffff;">
                            Explorar Directorio de Egresados &rarr;
                        </a>
                    </div>

                </section>

                <!-- COLUMNA DERECHA 2: Vista Expandida de Solicitudes (#requestsMainView) -->
                <section class="mensajes-requests-pane requests-main-view d-none" id="requestsMainView" aria-label="Panel principal de solicitudes de conexión">
                    
                    <div class="requests-view-header">
                        <div class="requests-header-text">
                            <h2 class="requests-main-title">Solicitudes de conexión pendientes</h2>
                            <p class="requests-main-subtitle">Egresados que desean conectar contigo en Raíces Alzate.</p>
                        </div>
                        <span class="requests-badge-pill" id="requestsMainCountTag"><?= count($solicitudesConexion) ?> solicitudes</span>
                    </div>

                    <div class="requests-scroll-content">
                        <div class="solicitudes-grid" id="solicitudesGrid">
                            <?php foreach ($solicitudesConexion as $solicitud): ?>
                            <article 
                                class="solicitud-card request-card-large" 
                                onclick="window.location.href='index.php?page=detalle-egresado&id=<?= (int)$solicitud['id'] ?>';"
                                data-id="<?= (int)$solicitud['id'] ?>"
                                data-name="<?= htmlspecialchars($solicitud['nombre']) ?>"
                                data-promo="<?= htmlspecialchars($solicitud['promocion']) ?>"
                                data-cargo="<?= htmlspecialchars($solicitud['cargo']) ?>"
                                data-enfasis="<?= htmlspecialchars($solicitud['enfasis']) ?>"
                                data-initial="<?= htmlspecialchars($solicitud['inicial']) ?>"
                                data-color="<?= htmlspecialchars($solicitud['color']) ?>"
                                data-avatar-img="<?= htmlspecialchars($solicitud['foto_url'] ?? '') ?>"
                            >
                                <div class="solicitud-card-header">
                                    <div class="solicitud-avatar <?= empty($solicitud['foto_url']) ? htmlspecialchars($solicitud['color']) : '' ?>">
                                        <?php if (!empty($solicitud['foto_url'])): ?>
                                            <img src="<?= htmlspecialchars($solicitud['foto_url']) ?>" alt="<?= htmlspecialchars($solicitud['nombre']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        <?php else: ?>
                                            <?= htmlspecialchars($solicitud['inicial']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="solicitud-user-meta">
                                        <div class="solicitud-name-row">
                                            <h3 class="solicitud-name"><?= htmlspecialchars($solicitud['nombre']) ?></h3>
                                            <span class="solicitud-time-badge"><?= htmlspecialchars($solicitud['fecha']) ?></span>
                                        </div>
                                        <p class="solicitud-academic-program"><?= htmlspecialchars($solicitud['enfasis']) ?> • Promoción <?= htmlspecialchars($solicitud['promocion']) ?></p>
                                        <div class="solicitud-mutual-pill">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <span><?= (int)$solicitud['conexiones'] ?> conexiones en común</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="solicitud-actions-row">
                                    <button type="button" class="btn-solicitud-accept btn-accept-request btn-action-accept" onclick="event.stopPropagation(); aceptarSolicitud(this, <?= (int)$solicitud['id'] ?>);" title="Aceptar conexión">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Aceptar
                                    </button>
                                    <button type="button" class="btn-solicitud-reject btn-reject-request btn-action-reject" onclick="event.stopPropagation(); rechazarSolicitud(this, <?= (int)$solicitud['id'] ?>);" title="Rechazar solicitud">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Rechazar
                                    </button>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>

                        <!-- Estado vacío si se resuelven todas las solicitudes -->
                        <div class="solicitudes-empty-state" id="solicitudesEmptyState" style="display: <?= empty($solicitudesConexion) ? 'flex' : 'none' ?>;">
                            <div class="empty-state-icon-wrap">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <polyline points="16 11 18 13 22 9"></polyline>
                                </svg>
                            </div>
                            <h3 class="empty-state-title">No tienes solicitudes pendientes</h3>
                            <p class="empty-state-text">
                                ¡Estás al día con tus conexiones! Puedes explorar el directorio institucional de egresados para descubrir nuevos colegas y compañeros de promoción.
                            </p>
                            <a href="index.php?page=egresados&view=panel" class="btn-empty-explore">
                                Explorar directorio de egresados &rarr;
                            </a>
                        </div>
                    </div>
                </section>

            </section>

        </main>
    </div>
</div>


<!-- Componente Toast de notificación flotante -->
<div class="mensajes-toast" id="toastMensajes" role="alert" aria-live="polite">
    <svg class="toast-icon-blue" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="16" x2="12" y2="12"></line>
        <line x1="12" y1="8" x2="12.01" y2="8"></line>
    </svg>
    <span id="toastMensajeTexto">Notificación</span>
</div>

<?php 
// Inclusión del pie de página global compartido
require_once __DIR__ . '/../includes/footer.php'; 
?>
