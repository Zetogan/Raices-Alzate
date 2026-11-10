<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * API Endpoint: Enviar Mensaje a Egresado Conectado
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
require_once __DIR__ . '/../includes/csrf.php';

// 1. Verificación de autenticación
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error'   => 'Debes iniciar sesión para enviar mensajes.'
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

// 2. Solo método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error'   => 'Método no permitido. Se requiere POST.'
    ]);
    exit;
}

// 3. Verificación de CSRF
$csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
if (!verifyCsrfToken($csrfToken)) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error'   => 'Token de seguridad inválido o expirado. Por favor recarga la página.'
    ]);
    exit;
}

// 4. Parámetros
$destinatarioId = (int)($_POST['destinatario_id'] ?? $_POST['contacto_id'] ?? 0);
$mensajeTexto   = trim($_POST['mensaje'] ?? $_POST['texto'] ?? '');

if ($destinatarioId <= 0 || $destinatarioId === $currentUserId) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Destinatario de mensaje no válido.'
    ]);
    exit;
}

if ($mensajeTexto === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'El mensaje no puede estar vacío.'
    ]);
    exit;
}

if (mb_strlen($mensajeTexto, 'UTF-8') > 3000) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'El mensaje excede la longitud máxima permitida (3000 caracteres).'
    ]);
    exit;
}

try {
    // 5. Validar conexión mutua aceptada o canal directo administrativo
    $stmtDestRol = $pdo->prepare("SELECT rol_id FROM usuarios WHERE id = ? LIMIT 1");
    $stmtDestRol->execute([$destinatarioId]);
    $destRolId = (int)$stmtDestRol->fetchColumn();

    $isAdminInvolved = ((int)($currentUser['rol_id'] ?? 0) === 1 || $destRolId === 1);

    if ($isAdminInvolved) {
        // Los administradores institucionales no usan solicitudes de conexión; operan con mensaje directo
        $stmtAutoConn = $pdo->prepare("
            INSERT INTO conexiones (solicitante_id, receptor_id, estado, creado_en, actualizado_en)
            VALUES (:u1, :u2, 'aceptada', NOW(), NOW())
            ON DUPLICATE KEY UPDATE estado = 'aceptada', actualizado_en = NOW()
        ");
        $stmtAutoConn->execute([':u1' => $currentUserId, ':u2' => $destinatarioId]);
    } else {
        $stmtCon = $pdo->prepare("
            SELECT id FROM conexiones 
            WHERE ((solicitante_id = :u1 AND receptor_id = :d1) 
                OR (solicitante_id = :d2 AND receptor_id = :u2))
              AND estado = 'aceptada'
            LIMIT 1
        ");
        $stmtCon->execute([
            ':u1' => $currentUserId,
            ':d1' => $destinatarioId,
            ':d2' => $destinatarioId,
            ':u2' => $currentUserId
        ]);
        $conexionAceptada = $stmtCon->fetch(PDO::FETCH_ASSOC);

        if (!$conexionAceptada) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error'   => 'Solo puedes enviar mensajes a egresados con los que tengas una conexión mutua aceptada.'
            ]);
            exit;
        }
    }

    // 6. Insertar mensaje en la base de datos
    $stmtInsert = $pdo->prepare("
        INSERT INTO mensajes (remitente_id, destinatario_id, mensaje, leido, creado_en)
        VALUES (:rem, :dest, :txt, 0, NOW())
    ");
    $stmtInsert->execute([
        ':rem'  => $currentUserId,
        ':dest' => $destinatarioId,
        ':txt'  => $mensajeTexto
    ]);
    $nuevoId = (int)$pdo->lastInsertId();

    // Actualizar actividad del usuario en sesión
    $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?")->execute([$currentUserId]);

    $horaFormateada = date('g:i A');

    echo json_encode([
        'success' => true,
        'mensaje' => [
            'id'              => $nuevoId,
            'remitente_id'    => $currentUserId,
            'destinatario_id' => $destinatarioId,
            'texto'           => $mensajeTexto,
            'hora'            => $horaFormateada,
            'creado_en'       => date('Y-m-d H:i:s')
        ]
    ]);

} catch (PDOException $e) {
    error_log('Error en api/enviar-mensaje.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Error interno al enviar el mensaje.'
    ]);
}
