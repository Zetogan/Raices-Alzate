<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * API Endpoint: Enviar solicitud de conexión entre egresados ("Mandar soli")
 * Versión: 0.1.2
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
        'error'   => 'Debes iniciar sesión para conectar con otros egresados.'
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

// 3. Verificación de token CSRF (soporta campo POST o cabecera X-CSRF-TOKEN)
$csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
if (!verifyCsrfToken($csrfToken)) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error'   => 'Token de seguridad inválido o expirado. Por favor recarga la página.'
    ]);
    exit;
}

// 4. Obtención y validación de destinatario
$targetId = (int)($_POST['target_id'] ?? $_POST['egresado_id'] ?? 0);
if ($targetId <= 0 || $targetId === $currentUserId) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Destinatario de conexión no válido.'
    ]);
    exit;
}

try {
    // Verificar que el usuario destino exista y esté activo
    $stmtUser = $pdo->prepare("SELECT id, nombre_completo FROM usuarios WHERE id = :id AND activo = 1 LIMIT 1");
    $stmtUser->execute([':id' => $targetId]);
    $targetUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$targetUser) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error'   => 'El perfil del egresado no fue encontrado o está inactivo.'
        ]);
        exit;
    }

    $targetNombre = htmlspecialchars($targetUser['nombre_completo'], ENT_QUOTES, 'UTF-8');

    // 5. Verificar si ya existe relación previa entre ambos
    $stmtCheck = $pdo->prepare("
        SELECT id, solicitante_id, receptor_id, estado 
        FROM conexiones 
        WHERE (solicitante_id = :u1 AND receptor_id = :t1) 
           OR (solicitante_id = :t2 AND receptor_id = :u2)
        LIMIT 1
    ");
    $stmtCheck->execute([
        ':u1' => $currentUserId,
        ':t1' => $targetId,
        ':t2' => $targetId,
        ':u2' => $currentUserId
    ]);
    $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        if ($existing['estado'] === 'aceptada') {
            echo json_encode([
                'success'      => true,
                'estado'       => 'aceptada',
                'mensaje'      => 'Ya estás conectado con ' . $targetNombre . '.',
                'action_label' => 'Enviar mensaje',
                'action_url'   => 'mensajes.php'
            ]);
            exit;
        } elseif ($existing['estado'] === 'pendiente') {
            if ((int)$existing['solicitante_id'] === $currentUserId) {
                echo json_encode([
                    'success'      => true,
                    'estado'       => 'pendiente',
                    'mensaje'      => 'Ya has enviado una solicitud a ' . $targetNombre . '. Espera su confirmación.',
                    'action_label' => 'Solicitud enviada'
                ]);
                exit;
            } else {
                // El otro usuario ya le había enviado solicitud: Aceptar la conexión
                $stmtAccept = $pdo->prepare("UPDATE conexiones SET estado = 'aceptada', actualizado_en = NOW() WHERE id = :cid");
                $stmtAccept->execute([':cid' => $existing['id']]);

                echo json_encode([
                    'success'      => true,
                    'estado'       => 'aceptada',
                    'mensaje'      => '¡Has aceptado la conexión con ' . $targetNombre . '!',
                    'action_label' => 'Enviar mensaje',
                    'action_url'   => 'mensajes.php'
                ]);
                exit;
            }
        }
    }

    // 6. Insertar nueva solicitud en conexiones
    $stmtInsert = $pdo->prepare("
        INSERT INTO conexiones (solicitante_id, receptor_id, estado, creado_en, actualizado_en)
        VALUES (:sol, :rec, 'pendiente', NOW(), NOW())
        ON DUPLICATE KEY UPDATE estado = 'pendiente', actualizado_en = NOW()
    ");
    $stmtInsert->execute([':sol' => $currentUserId, ':rec' => $targetId]);

    echo json_encode([
        'success'      => true,
        'estado'       => 'pendiente',
        'mensaje'      => '¡Solicitud enviada a ' . $targetNombre . ' correctamente!',
        'action_label' => 'Solicitud enviada'
    ]);

} catch (PDOException $e) {
    error_log('Error en api/conectar-egresado.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Error interno al procesar la solicitud de conexión.'
    ]);
}
