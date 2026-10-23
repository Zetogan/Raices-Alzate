<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Dashboard (Panel Principal)
 * Especificación Visual: Figma 03 - Dashboard
 * Versión: 0.3.0
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// 1. Verificación de sesión canónica
if (!isAuthenticated()) {
    header('Location: ' . url('index.php?page=login'));
    exit;
}

// Los administradores gestionan la plataforma desde el panel de administración
if (function_exists('hasRole') && hasRole('administrador')) {
    header('Location: index.php?page=administracion');
    exit;
}

// 2. Extracción de datos del usuario autenticado
$currentUser = currentUser();
$userId      = (int)($currentUser['id'] ?? 0);
$userName    = trim($currentUser['nombre'] ?? 'Usuario');
$userRole    = trim($currentUser['rol'] ?? 'Egresado');
$userEmail   = trim($currentUser['email'] ?? '');

$userFoto = null;
if (!empty($pdo) && $userId > 0) {
    try {
        $stmtFoto = $pdo->prepare("SELECT foto_perfil FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
        $stmtFoto->execute([':uid' => $userId]);
        $rowFoto = $stmtFoto->fetch(PDO::FETCH_ASSOC);
        if (!empty($rowFoto['foto_perfil'])) {
            $userFoto = $rowFoto['foto_perfil'];
        }
    } catch (PDOException $e) {
        $userFoto = null;
    }
}

$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

// 3. Metadatos de la vista
$pageTitle      = 'Dashboard | ' . APP_NAME;
$seccion_activa = 'dashboard';
$currentPage    = 'dashboard';
$topbarTitle    = 'Dashboard';
$extraCss       = ['dashboard.css'];
$extraJs        = ['assets/js/dashboard.js'];

// 4. Consulta de actividades reales del usuario autenticado desde MySQL
$actividades = [];
if (!empty($pdo) && $userId > 0) {
    try {
        // A. Proyectos creados por el usuario
        $stmtProyectos = $pdo->prepare("
            SELECT 
                'proyecto' AS tipo,
                id AS ref_id,
                CONCAT('Publicaste el proyecto «', titulo, '»') AS descripcion,
                creado_en AS fecha,
                'folder' AS icono
            FROM proyectos
            WHERE autor_id = :uid
            ORDER BY creado_en DESC
            LIMIT 5
        ");
        $stmtProyectos->execute([':uid' => $userId]);
        $proyectosActs = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

        // B. Actualización de perfil
        $stmtPerfil = $pdo->prepare("
            SELECT 
                'perfil' AS tipo,
                pe.usuario_id AS ref_id,
                'Actualizaste tu información de perfil' AS descripcion,
                pe.actualizado_en AS fecha,
                'user' AS icono
            FROM perfiles_egresados pe
            WHERE pe.usuario_id = :uid
              AND pe.actualizado_en IS NOT NULL 
              AND pe.actualizado_en > pe.creado_en
            LIMIT 1
        ");
        $stmtPerfil->execute([':uid' => $userId]);
        $perfilAct = $stmtPerfil->fetch(PDO::FETCH_ASSOC);

        // C. Noticias publicadas
        $stmtNoticias = $pdo->prepare("
            SELECT 
                'noticia' AS tipo,
                id AS ref_id,
                CONCAT('Publicaste la noticia «', titulo, '»') AS descripcion,
                creado_en AS fecha,
                'file' AS icono
            FROM noticias
            WHERE autor_id = :uid
            ORDER BY creado_en DESC
            LIMIT 3
        ");
        $stmtNoticias->execute([':uid' => $userId]);
        $noticiasActs = $stmtNoticias->fetchAll(PDO::FETCH_ASSOC);

        // D. Registro en la plataforma
        $stmtRegistro = $pdo->prepare("
            SELECT 
                'registro' AS tipo,
                id AS ref_id,
                'Te uniste a la red de egresados' AS descripcion,
                creado_en AS fecha,
                'network' AS icono
            FROM usuarios
            WHERE id = :uid
            LIMIT 1
        ");
        $stmtRegistro->execute([':uid' => $userId]);
        $registroAct = $stmtRegistro->fetch(PDO::FETCH_ASSOC);

        // E. Conexiones
        $conexionesActs = [];
        try {
            $stmtConexiones = $pdo->prepare("
                SELECT 
                    'conexion' AS tipo,
                    IF(c.solicitante_id = :u1, c.receptor_id, c.solicitante_id) AS ref_id,
                    CASE 
                        WHEN c.estado = 'aceptada' THEN CONCAT('Agregaste a ', u_otro.nombre_completo, ' como compañero')
                        WHEN c.estado = 'pendiente' AND c.solicitante_id = :u2 THEN CONCAT('Enviaste solicitud de conexión a ', u_otro.nombre_completo)
                        WHEN c.estado = 'pendiente' AND c.receptor_id = :u3 THEN CONCAT(u_otro.nombre_completo, ' te envió solicitud de conexión')
                        ELSE CONCAT('Agregaste a ', u_otro.nombre_completo, ' como compañero')
                    END AS descripcion,
                    COALESCE(c.actualizado_en, c.creado_en) AS fecha,
                    IF(c.estado = 'aceptada', 'user-check', 'user-plus') AS icono
                FROM conexiones c
                JOIN usuarios u_otro ON u_otro.id = IF(c.solicitante_id = :u4, c.receptor_id, c.solicitante_id)
                WHERE (c.solicitante_id = :u5 OR c.receptor_id = :u6)
                  AND (c.estado = 'aceptada' OR (c.estado = 'pendiente' AND c.solicitante_id = :u7))
                ORDER BY fecha DESC
                LIMIT 5
            ");
            $stmtConexiones->execute([
                ':u1' => $userId,
                ':u2' => $userId,
                ':u3' => $userId,
                ':u4' => $userId,
                ':u5' => $userId,
                ':u6' => $userId,
                ':u7' => $userId
            ]);
            $conexionesActs = $stmtConexiones->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $conexionesActs = [];
        }

        $todas = array_merge(
            $proyectosActs ?: [],
            $perfilAct ? [$perfilAct] : [],
            $conexionesActs ?: [],
            $noticiasActs ?: [],
            $registroAct ? [$registroAct] : []
        );

        usort($todas, function ($a, $b) {
            return strtotime($b['fecha']) <=> strtotime($a['fecha']);
        });

        $actividades = array_slice($todas, 0, 5);

    } catch (PDOException $e) {
        error_log('Error al consultar actividades en dashboard: ' . $e->getMessage());
        $actividades = [];
    }
}

// Asignar URLs de destino a las actividades dinámicas
foreach ($actividades as &$act) {
    $refId = (int)($act['ref_id'] ?? 0);
    if ($act['tipo'] === 'proyecto') {
        $act['url'] = 'index.php?page=detalle-proyecto&id=' . ($refId > 0 ? $refId : 1);
    } elseif ($act['tipo'] === 'noticia') {
        $act['url'] = 'index.php?page=detalle-noticia&id=' . ($refId > 0 ? $refId : 1);
    } elseif ($act['tipo'] === 'conexion') {
        $act['url'] = 'index.php?page=detalle-egresado&id=' . ($refId > 0 ? $refId : 1);
    } elseif ($act['tipo'] === 'perfil') {
        $act['url'] = 'index.php?page=perfil';
    } else {
        $act['url'] = 'index.php?page=egresados';
    }
}
unset($act);

// Actividades por defecto de alta fidelidad si aún no hay registros suficientes
if (empty($actividades)) {
    $actividades = [
        [
            'tipo'        => 'perfil',
            'descripcion' => 'Actualizaste tu información de perfil',
            'fecha'       => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'icono'       => 'user',
            'url'         => 'index.php?page=perfil'
        ],
        [
            'tipo'        => 'proyecto',
            'descripcion' => 'Publicaste un nuevo proyecto',
            'fecha'       => date('Y-m-d H:i:s', strtotime('-1 day')),
            'icono'       => 'folder',
            'url'         => 'index.php?page=detalle-proyecto&id=1'
        ],
        [
            'tipo'        => 'registro',
            'descripcion' => 'Te uniste a la red de egresados',
            'fecha'       => date('Y-m-d H:i:s', strtotime('-3 days')),
            'icono'       => 'network',
            'url'         => 'index.php?page=egresados'
        ]
    ];
}

// 5. Carga de próximas oportunidades
$catalogoOportunidades = [];
if (file_exists(__DIR__ . '/../config/oportunidades_data.php')) {
    $catalogoOportunidades = require __DIR__ . '/../config/oportunidades_data.php';
}

$proximasOportunidades = [];
if (!empty($catalogoOportunidades)) {
    foreach (array_slice($catalogoOportunidades, 0, 3) as $op) {
        $cat = $op['categoria'] ?? 'Oportunidad';
        $catLower = mb_strtolower($cat);
        $tagClass = 'tag-dark';
        if (str_contains($catLower, 'beca')) {
            $tagClass = 'tag-blue';
        } elseif (str_contains($catLower, 'empleo') || str_contains($catLower, 'laboral')) {
            $tagClass = 'tag-red';
        } elseif (str_contains($catLower, 'pasant')) {
            $tagClass = 'tag-amber';
        }

        $desc = (!empty($op['empresa']) ? $op['empresa'] . ' — ' : '') . ($op['fecha'] ?? $op['fecha_limite'] ?? 'Convocatoria abierta');

        $proximasOportunidades[] = [
            'id'        => (int)($op['id'] ?? 1),
            'categoria' => $cat,
            'tag_class' => $tagClass,
            'titulo'    => $op['titulo'] ?? 'Oportunidad',
            'desc'      => $desc,
            'url'       => 'index.php?page=detalle-oportunidad&id=' . (int)($op['id'] ?? 1)
        ];
    }
} else {
    $proximasOportunidades = [
        [
            'id'        => 1,
            'categoria' => 'Beca',
            'tag_class' => 'tag-blue',
            'titulo'    => 'Beca de formación tecnológica',
            'desc'      => 'Convocatoria abierta — Límite: 25 de Octubre',
            'url'       => 'index.php?page=detalle-oportunidad&id=1'
        ],
        [
            'id'        => 2,
            'categoria' => 'Empleo',
            'tag_class' => 'tag-red',
            'titulo'    => 'Oferta laboral para egresados',
            'desc'      => 'Sector tecnología y gestión — Postulación inmediata',
            'url'       => 'index.php?page=detalle-oportunidad&id=2'
        ],
        [
            'id'        => 3,
            'categoria' => 'Capacitación',
            'tag_class' => 'tag-dark',
            'titulo'    => 'Curso de actualización profesional',
            'desc'      => 'Modalidad virtual — Certificación incluida',
            'url'       => 'index.php?page=detalle-oportunidad&id=3'
        ]
    ];
}

// 6. Renderizado de la página con includes estándar
require_once __DIR__ . '/../includes/header.php';
?>
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
            $firstName = !empty($userName) ? htmlspecialchars(explode(' ', $userName)[0], ENT_QUOTES, 'UTF-8') : 'Usuario';
            ?>
            <div class="dashboard-welcome dashboard-welcome-header">
                <h1 class="welcome-title dashboard-welcome-title">
                    ¡Hola, <?= $firstName ?>!
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
                        <a href="index.php?page=perfil" class="dashboard-action-link" id="linkVerPerfil">
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
                        <a href="index.php?page=proyectos" class="dashboard-action-link" id="linkVerProyectos">
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
                        <a href="index.php?page=oportunidades" class="dashboard-action-link" id="linkVerOportunidades">
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
                            <ul class="activity-timeline-list" role="list">
                                <?php if (!empty($actividades)): ?>
                                    <?php foreach ($actividades as $act): ?>
                                        <li class="activity-timeline-item">
                                            <div class="activity-icon-bullet icon-bullet-<?= htmlspecialchars($act['icono']) ?>" aria-hidden="true">
                                                <?php if ($act['icono'] === 'folder'): ?>
                                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                                <?php elseif ($act['icono'] === 'file'): ?>
                                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                                <?php elseif ($act['icono'] === 'user-check' || $act['icono'] === 'user-plus'): ?>
                                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                <?php elseif ($act['icono'] === 'network'): ?>
                                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M12 2v4m0 12v4m-10-10h4m12 0h4"></path></svg>
                                                <?php else: ?>
                                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                <?php endif; ?>
                                            </div>
                                            <div class="activity-item-details">
                                                <p class="activity-action-text">
                                                    <?php if (!empty($act['url'])): ?>
                                                        <a href="<?= htmlspecialchars($act['url']) ?>" style="color: inherit; text-decoration: none;" class="activity-link-title">
                                                            <?= htmlspecialchars($act['descripcion']) ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <?= htmlspecialchars($act['descripcion']) ?>
                                                    <?php endif; ?>
                                                </p>
                                                <span class="activity-timestamp"><?= formatearTiempoRelativo($act['fecha']) ?></span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </section>

                    <!-- 2. Acciones rápidas (en desktop abajo de actividad, en mobile orden 3) -->
                    <section class="dashboard-block-section quick-actions-section" aria-labelledby="quickActionsTitle">
                        <h3 class="dashboard-section-title" id="quickActionsTitle">Acciones rápidas</h3>
                        <div class="quick-actions-buttons-group dashboard-actions">
                            <a href="index.php?page=editar-perfil" class="btn btn-primary btn-quick-primary btn-actualizar-perfil" id="btnQuickActualizarPerfil">
                                Actualizar mi perfil
                            </a>
                            <a href="index.php?page=crear-proyecto" class="btn btn-outline-secondary btn-quick-outline btn-publicar-proyecto" id="btnQuickPublicarProyecto">
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
                            <ul class="opportunities-list" role="list">
                                <?php foreach ($proximasOportunidades as $op): ?>
                                    <li class="opportunity-item" style="cursor: pointer;" onclick="window.location.href='<?= htmlspecialchars($op['url']) ?>';">
                                        <div class="opportunity-header-row">
                                            <span class="opportunity-tag <?= htmlspecialchars($op['tag_class']) ?>"><?= htmlspecialchars($op['categoria']) ?></span>
                                        </div>
                                        <h4 class="opportunity-item-title">
                                            <a href="<?= htmlspecialchars($op['url']) ?>" style="color: inherit; text-decoration: none;">
                                                <?= htmlspecialchars($op['titulo']) ?>
                                            </a>
                                        </h4>
                                        <p class="opportunity-item-desc">
                                            <?= htmlspecialchars($op['desc']) ?>
                                        </p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
