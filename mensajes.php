<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Mensajes / Comunicaciones (Panel Privado)
 * Funcionalidad: Gestión de solicitudes de conexión y chat interactivo entre egresados
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.2
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Control de acceso para usuarios autenticados
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/config.php';

// 2. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'mensajes';
$titulo_seccion = 'Mensajes';
$titulo_pagina = 'Mensajes — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'mensajes';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

// Hojas de estilo y scripts modulares para includes globales
$extraCss = ['dashboard.css', 'mensajes.css'];
$extraJs = ['assets/js/mensajes.js'];

// =========================================================================
// 3. DATOS ESTÁTICOS EN PHP (CERO BASES DE DATOS / CERO SQL)
// =========================================================================

// Solicitudes de conexión pendientes recibidas de otros egresados
$solicitudesConexion = [
    [
        'id'          => 2,
        'nombre'      => 'Carlos Andrés Morales',
        'promocion'   => '2020',
        'enfasis'     => 'Técnico en Gestión Comercial y Contable',
        'cargo'       => 'Coordinador Financiero en Bancolombia',
        'ubicacion'   => 'Medellín, Colombia',
        'conexiones'  => 4,
        'fecha'       => 'Hace 2 horas',
        'color'       => 'avatar-teal',
        'inicial'     => 'C'
    ],
    [
        'id'          => 3,
        'nombre'      => 'Mariana Ospina Londoño',
        'promocion'   => '2021',
        'enfasis'     => 'Bachillerato Académico',
        'cargo'       => 'Diseñadora UI/UX en Globant',
        'ubicacion'   => 'Remoto / Medellín',
        'conexiones'  => 6,
        'fecha'       => 'Ayer',
        'color'       => 'avatar-purple',
        'inicial'     => 'M'
    ],
    [
        'id'          => 6,
        'nombre'      => 'Alejandro Vélez Castro',
        'promocion'   => '2019',
        'enfasis'     => 'Bachillerato Académico',
        'cargo'       => 'Abogado Especialista en Consultoría Vélez',
        'ubicacion'   => 'Medellín, Colombia',
        'conexiones'  => 2,
        'fecha'       => 'Hace 3 días',
        'color'       => 'avatar-amber',
        'inicial'     => 'A'
    ]
];

// Conversaciones activas con egresados ya conectados
$conversaciones = [
    [
        'id'          => 101,
        'nombre'      => 'Valentina Ríos Gómez',
        'promocion'   => '2021',
        'cargo'       => 'Ingeniera de Software en Mercado Libre',
        'estado'      => 'En línea',
        'online'      => true,
        'color'       => 'avatar-blue',
        'inicial'     => 'V',
        'no_leidos'   => 1,
        'hora'        => '10:42 AM',
        'mensajes'    => [
            [
                'tipo'  => 'incoming',
                'texto' => '¡Hola Santiago! ¿Cómo has estado? Me alegra mucho verte activo en la plataforma comunitaria.',
                'hora'  => '10:30 AM'
            ],
            [
                'tipo'  => 'outgoing',
                'texto' => '¡Hola Valentina! Todo excelente por fortuna. Estaba revisando tu trayectoria en el directorio y me pareció muy destacado tu trabajo en Mercado Libre.',
                'hora'  => '10:35 AM'
            ],
            [
                'tipo'  => 'incoming',
                'texto' => '¡Muchas gracias! Si en algún momento necesitas apoyo o mentoría en desarrollo web con los chicos de grados 10° y 11°, cuenta con mi apoyo.',
                'hora'  => '10:38 AM'
            ],
            [
                'tipo'  => 'outgoing',
                'texto' => 'Justo estamos coordinando una jornada de talleres virtuales para el próximo mes. ¿Te gustaría participar con una pequeña charla?',
                'hora'  => '10:40 AM'
            ],
            [
                'tipo'  => 'incoming',
                'texto' => '¡Claro que sí, me encantaría colaborar con el proyecto de egresados! Cuéntame qué fecha tentativa tienen pensada.',
                'hora'  => '10:42 AM'
            ]
        ]
    ],
    [
        'id'          => 102,
        'nombre'      => 'Mateo Henao Morales',
        'promocion'   => '2021',
        'cargo'       => 'Psicólogo Organizacional en Grupo Nutresa',
        'estado'      => 'Desconectado hace 25 min',
        'online'      => false,
        'color'       => 'avatar-green',
        'inicial'     => 'M',
        'no_leidos'   => 0,
        'hora'        => 'Ayer',
        'mensajes'    => [
            [
                'tipo'  => 'outgoing',
                'texto' => 'Hola Mateo, ¿cómo van los talleres de orientación para entrevistas de trabajo?',
                'hora'  => 'Ayer 3:15 PM'
            ],
            [
                'tipo'  => 'incoming',
                'texto' => 'Muy bien Santiago. He venido adaptando material enfocado en habilidades blandas y primera oportunidad laboral.',
                'hora'  => 'Ayer 3:20 PM'
            ],
            [
                'tipo'  => 'incoming',
                'texto' => 'Listo, te paso los formatos de orientación laboral el viernes para que los revises.',
                'hora'  => 'Ayer 3:22 PM'
            ]
        ]
    ],
    [
        'id'          => 103,
        'nombre'      => 'Laura Camila Gómez Duque',
        'promocion'   => '2022',
        'cargo'       => 'Desarrolladora Frontend en Ruta N',
        'estado'      => 'En línea',
        'online'      => true,
        'color'       => 'avatar-teal',
        'inicial'     => 'L',
        'no_leidos'   => 2,
        'hora'        => '9:15 AM',
        'mensajes'    => [
            [
                'tipo'  => 'incoming',
                'texto' => 'Hola Santiago, logramos publicar la primera versión de la guía de código abierto de la comunidad.',
                'hora'  => '9:12 AM'
            ],
            [
                'tipo'  => 'incoming',
                'texto' => 'Te envié el enlace del repositorio del proyecto colaborativo para que le des un vistazo.',
                'hora'  => '9:15 AM'
            ]
        ]
    ],
    [
        'id'          => 104,
        'nombre'      => 'Sara Muñoz Cardona',
        'promocion'   => '2023',
        'cargo'       => 'Mercadóloga Digital en Pragma',
        'estado'      => 'Desconectada',
        'online'      => false,
        'color'       => 'avatar-red',
        'inicial'     => 'S',
        'no_leidos'   => 0,
        'hora'        => '8 oct',
        'mensajes'    => [
            [
                'tipo'  => 'outgoing',
                'texto' => 'Sara, ¿vas a asistir al próximo encuentro de egresados alzatistas?',
                'hora'  => '8 oct 11:00 AM'
            ],
            [
                'tipo'  => 'incoming',
                'texto' => '¡Sí, totalmente confirmada! ¡Nos vemos en el próximo encuentro de exalumnos!',
                'hora'  => '8 oct 11:25 AM'
            ]
        ]
    ]
];

// Métricas de insignias
$totalNoLeidos = 0;
foreach ($conversaciones as $c) {
    $totalNoLeidos += (int)($c['no_leidos'] ?? 0);
}
$totalSolicitudes = count($solicitudesConexion);

// Conversación seleccionada por defecto (Valentina Ríos)
$contactoActivo = $conversaciones[0];

// Inclusión del layout global de cabecera
require_once __DIR__ . '/includes/header.php';
?>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido con el ítem Mensajes activo -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <main class="content-area dashboard-content mensajes-content-wrapper" id="mensajesMainContent" tabindex="-1">
            
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
                        <?php foreach ($conversaciones as $idx => $contacto): 
                            $isActive = ($idx === 0);
                            $lastMsg = end($contacto['mensajes']);
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
                            role="button"
                            tabindex="0"
                        >
                            <div class="contact-avatar-wrapper">
                                <div class="contact-avatar <?= htmlspecialchars($contacto['color']) ?>">
                                    <?= htmlspecialchars($contacto['inicial']) ?>
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
                    <div class="thread-header conversation-header">
                        <a href="detalle-egresado.php?id=<?= (int)$contactoActivo['id'] ?>" class="thread-header-left conversation-header-user" id="linkHeaderUser" style="text-decoration: none; color: inherit;" title="Ver perfil institucional de <?= htmlspecialchars($contactoActivo['nombre']) ?>">
                            <button type="button" class="btn-back-contacts" id="btnBackToContacts" aria-label="Volver a lista de contactos" onclick="event.preventDefault(); event.stopPropagation();">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <div class="thread-avatar <?= htmlspecialchars($contactoActivo['color']) ?>" id="threadAvatar">
                                <?= htmlspecialchars($contactoActivo['inicial']) ?>
                            </div>
                            <div class="thread-user-meta">
                                <h2 class="thread-user-name" id="threadUserName"><?= htmlspecialchars($contactoActivo['nombre']) ?></h2>
                                <span class="thread-user-status-text" id="threadUserStatusText">
                                    <?php if ($contactoActivo['online']): ?>
                                    <span class="thread-online-indicator"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($contactoActivo['estado']) ?>
                                </span>
                            </div>
                        </a>

                        <div class="thread-header-actions conversation-header-actions">
                            <a href="detalle-egresado.php?id=<?= (int)$contactoActivo['id'] ?>" class="btn-view-profile" id="btnHeaderVerPerfil" title="Ver perfil institucional completo">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Ver perfil</span>
                            </a>
                        </div>
                    </div>

                    <!-- Cuerpo de mensajes con scroll automático -->
                    <div class="thread-messages-body" id="threadMessagesBody">
                        
                        <?php foreach ($conversaciones as $cIndex => $conv): 
                            $displayStyle = ($cIndex === 0) ? 'flex' : 'none';
                        ?>
                        <div class="chat-thread-container" id="thread-messages-<?= (int)$conv['id'] ?>" style="display: <?= $displayStyle ?>; flex-direction: column; gap: 16px;">
                            <div class="thread-date-separator">
                                <span class="thread-date-badge">Hoy, 15 de octubre</span>
                            </div>

                            <?php foreach ($conv['mensajes'] as $m): ?>
                            <div class="msg-row <?= ($m['tipo'] === 'outgoing') ? 'outgoing' : 'incoming' ?>">
                                <?php if ($m['tipo'] === 'incoming'): ?>
                                <div class="msg-mini-avatar <?= htmlspecialchars($conv['color']) ?>">
                                    <?= htmlspecialchars($conv['inicial']) ?>
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
                        </div>
                        <?php endforeach; ?>

                        <!-- Indicador animado de "escribiendo..." (activado por JS) -->
                        <div class="typing-indicator-row" id="typingIndicatorRow">
                            <span id="typingIndicatorName"><?= htmlspecialchars($contactoActivo['nombre']) ?></span> está escribiendo
                            <div class="typing-dots">
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                            </div>
                        </div>

                    </div>

                    <!-- Barra inferior de redacción y envío -->
                    <div class="thread-composer-box">
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
                                onclick="window.location.href='detalle-egresado.php?id=<?= (int)$solicitud['id'] ?>';"
                                data-id="<?= (int)$solicitud['id'] ?>"
                                data-name="<?= htmlspecialchars($solicitud['nombre']) ?>"
                                data-promo="<?= htmlspecialchars($solicitud['promocion']) ?>"
                                data-cargo="<?= htmlspecialchars($solicitud['cargo']) ?>"
                                data-enfasis="<?= htmlspecialchars($solicitud['enfasis']) ?>"
                                data-initial="<?= htmlspecialchars($solicitud['inicial']) ?>"
                                data-color="<?= htmlspecialchars($solicitud['color']) ?>"
                            >
                                <div class="solicitud-card-header">
                                    <div class="solicitud-avatar <?= htmlspecialchars($solicitud['color']) ?>">
                                        <?= htmlspecialchars($solicitud['inicial']) ?>
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
                            <a href="egresados.php?view=panel" class="btn-empty-explore">
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
require_once __DIR__ . '/includes/footer.php'; 
?>
