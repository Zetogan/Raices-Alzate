<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * API Endpoint: Obtener Historial de Mensajes con un Egresado Conectado
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
if (!headers_sent()) {
    header('Content-Type: application/json; charset=UTF-8');
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

// 1. Verificación de autenticación
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error'   => 'Debes iniciar sesión para consultar mensajes.'
    ]);
    exit;
}

$currentUser   = currentUser();
$currentUserId = (int)($currentUser['id'] ?? 0);

if ($currentUserId <= 0 || empty($pdo)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error'   => 'Sesión no válida o conexión de base de datos ausente.'
    ]);
    exit;
}

// 2. Parámetros
$contactoId = (int)($_GET['contacto_id'] ?? $_GET['id'] ?? 0);
$desdeId    = (int)($_GET['desde_id'] ?? 0);

if ($contactoId <= 0 || $contactoId === $currentUserId) {
    if (isset($_GET['resumen'])) {
        try {
            $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?")->execute([$currentUserId]);
            $stmtUnread = $pdo->prepare("
                SELECT m.remitente_id, COUNT(*) as count, MAX(m.creado_en) as ultimo_ts,
                       (SELECT mensaje FROM mensajes WHERE remitente_id = m.remitente_id AND destinatario_id = :uid1 ORDER BY id DESC LIMIT 1) as ultimo_mensaje
                FROM mensajes m
                WHERE m.destinatario_id = :uid2 AND m.leido = 0
                GROUP BY m.remitente_id
            ");
            $stmtUnread->execute([':uid1' => $currentUserId, ':uid2' => $currentUserId]);
            $resumen = $stmtUnread->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success'           => true,
                'mensajes'          => [],
                'resumen_no_leidos' => $resumen
            ]);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error al consultar resumen.']);
            exit;
        }
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Identificador de contacto no válido.'
    ]);
    exit;
}

try {
    // 3. Validar conexión mutua aceptada
    $stmtCon = $pdo->prepare("
        SELECT id FROM conexiones 
        WHERE ((solicitante_id = :u1 AND receptor_id = :c1) 
            OR (solicitante_id = :c2 AND receptor_id = :u2))
          AND estado = 'aceptada'
        LIMIT 1
    ");
    $stmtCon->execute([
        ':u1' => $currentUserId,
        ':c1' => $contactoId,
        ':c2' => $contactoId,
        ':u2' => $currentUserId
    ]);
    if (!$stmtCon->fetch()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error'   => 'No tienes una conexión activa con este egresado.'
        ]);
        exit;
    }

    // 4. Actualizar actividad del usuario en sesión
    $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?")->execute([$currentUserId]);

    // 5. Marcar mensajes entrantes como leídos
    $stmtRead = $pdo->prepare("
        UPDATE mensajes 
        SET leido = 1 
        WHERE remitente_id = :cid AND destinatario_id = :uid AND leido = 0
    ");
    $stmtRead->execute([
        ':cid' => $contactoId,
        ':uid' => $currentUserId
    ]);

    // 6. Consultar estado en línea y foto del contacto
    $stmtStatus = $pdo->prepare("
        SELECT u.ultimo_acceso, pe.foto_perfil 
        FROM usuarios u
        LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
        WHERE u.id = ? LIMIT 1
    ");
    $stmtStatus->execute([$contactoId]);
    $contactoData = $stmtStatus->fetch(PDO::FETCH_ASSOC) ?: [];
    $cAcceso = $contactoData['ultimo_acceso'] ?? null;
    $cFotoPerfil = '';
    if (!empty($contactoData['foto_perfil'])) {
        $cleanPath = ltrim($contactoData['foto_perfil'], '/');
        if (file_exists(__DIR__ . '/../' . $cleanPath)) {
            $cFotoPerfil = $cleanPath;
        }
    }

    $ultimoAccesoTs = !empty($cAcceso) ? strtotime($cAcceso) : 0;
    $diffMinutos = (time() - $ultimoAccesoTs) / 60;
    $contactoOnline = ($diffMinutos <= 20);
    $contactoEstado = $contactoOnline ? 'En línea' : ($diffMinutos <= 1440 ? 'Desconectado hace ' . max(1, (int)($diffMinutos / 60)) . ' h' : 'Desconectado');

    // 7. Consultar mensajes
    $sql = "
        SELECT id, remitente_id, destinatario_id, mensaje, leido, creado_en
        FROM mensajes
        WHERE ((remitente_id = :u1 AND destinatario_id = :c1) 
            OR (remitente_id = :c2 AND destinatario_id = :u2))
    ";
    $params = [
        ':u1' => $currentUserId,
        ':c1' => $contactoId,
        ':c2' => $contactoId,
        ':u2' => $currentUserId
    ];

    if ($desdeId > 0) {
        $sql .= " AND id > :desde";
        $params[':desde'] = $desdeId;
    }

    $sql .= " ORDER BY creado_en ASC, id ASC LIMIT 100";

    $stmtMsg = $pdo->prepare($sql);
    $stmtMsg->execute($params);
    $rows = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);

    $mensajes = [];
    foreach ($rows as $r) {
        $esPropio = ((int)$r['remitente_id'] === $currentUserId);
        $timestamp = strtotime($r['creado_en']);
        $mensajes[] = [
            'id'              => (int)$r['id'],
            'remitente_id'    => (int)$r['remitente_id'],
            'destinatario_id' => (int)$r['destinatario_id'],
            'tipo'            => $esPropio ? 'outgoing' : 'incoming',
            'texto'           => $r['mensaje'],
            'hora'            => date('g:i A', $timestamp),
            'creado_en'       => $r['creado_en'],
            'leido'           => (bool)$r['leido']
        ];
    }

    // 8. Resumen de no leídos de otros contactos
    $stmtUnread = $pdo->prepare("
        SELECT m.remitente_id, COUNT(*) as count, MAX(m.creado_en) as ultimo_ts,
               (SELECT mensaje FROM mensajes WHERE remitente_id = m.remitente_id AND destinatario_id = :uid1 ORDER BY id DESC LIMIT 1) as ultimo_mensaje
        FROM mensajes m
        WHERE m.destinatario_id = :uid2 AND m.leido = 0
        GROUP BY m.remitente_id
    ");
    $stmtUnread->execute([':uid1' => $currentUserId, ':uid2' => $currentUserId]);
    $resumenNoLeidos = $stmtUnread->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success'           => true,
        'mensajes'          => $mensajes,
        'contacto_online'   => $contactoOnline,
        'contacto_estado'   => $contactoEstado,
        'contacto_foto'     => $cFotoPerfil,
        'resumen_no_leidos' => $resumenNoLeidos
    ]);

} catch (PDOException $e) {
    error_log('Error en api/obtener-mensajes.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Error interno al consultar los mensajes.'
    ]);
}
