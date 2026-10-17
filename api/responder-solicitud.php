<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * API Endpoint: Responder a Solicitud de Conexión (Aceptar o Rechazar)
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
        'error'   => 'Debes iniciar sesión para gestionar solicitudes de conexión.'
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
$solicitanteId = (int)($_POST['solicitante_id'] ?? $_POST['target_id'] ?? $_POST['id'] ?? 0);
$accion        = strtolower(trim($_POST['accion'] ?? 'aceptar'));

if ($solicitanteId <= 0 || $solicitanteId === $currentUserId) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Identificador de solicitante no válido.'
    ]);
    exit;
}

if (!in_array($accion, ['aceptar', 'rechazar'], true)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'Acción no válida. Se permite "aceptar" o "rechazar".'
    ]);
    exit;
}

try {
    // Buscar la conexión pendiente donde el otro es solicitante y el usuario en sesión es receptor
    $stmtCheck = $pdo->prepare("
        SELECT id, solicitante_id, receptor_id, estado
        FROM conexiones
        WHERE solicitante_id = :sol AND receptor_id = :rec
        LIMIT 1
    ");
    $stmtCheck->execute([
        ':sol' => $solicitanteId,
        ':rec' => $currentUserId
    ]);
    $conexion = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$conexion) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error'   => 'No se encontró una solicitud pendiente de este egresado.'
        ]);
        exit;
    }

    // Datos del solicitante para respuesta rica
    $stmtUser = $pdo->prepare("
        SELECT 
            u.id, 
            u.nombre_completo,
            u.ultimo_acceso,
            pe.anio_graduacion,
            pe.ocupacion,
            pe.empresa,
            pe.area_especialidad,
            pe.ciudad
        FROM usuarios u
        LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
        WHERE u.id = :uid AND u.activo = 1
        LIMIT 1
    ");
    $stmtUser->execute([':uid' => $solicitanteId]);
    $solicitanteUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $nombre = $solicitanteUser ? $solicitanteUser['nombre_completo'] : 'Egresado Alzatista';
    $inicial = !empty($nombre) ? mb_strtoupper(mb_substr($nombre, 0, 1, 'UTF-8'), 'UTF-8') : 'E';
    $cargo = trim(($solicitanteUser['ocupacion'] ?? '') . (!empty($solicitanteUser['empresa']) ? ' en ' . $solicitanteUser['empresa'] : ''));
    if (empty($cargo)) {
        $cargo = 'Egresado Alzatista';
    }
    $promocion = !empty($solicitanteUser['anio_graduacion']) ? (string)$solicitanteUser['anio_graduacion'] : 'Alzatista';

    // Estado online real del solicitante
    $uAccesoTs = !empty($solicitanteUser['ultimo_acceso']) ? strtotime($solicitanteUser['ultimo_acceso']) : 0;
    $diffMin = (time() - $uAccesoTs) / 60;
    $solOnline = ($diffMin <= 20);
    $solEstado = $solOnline ? 'En línea' : ($diffMin <= 1440 ? 'Desconectado hace ' . max(1, (int)($diffMin / 60)) . ' h' : 'Desconectado');

    $colores = ['avatar-blue', 'avatar-teal', 'avatar-purple', 'avatar-orange', 'avatar-green'];
    $colorAvatar = $colores[$solicitanteId % count($colores)];

    // Actualizar actividad del usuario en sesión
    $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?")->execute([$currentUserId]);

    if ($accion === 'aceptar') {
        $stmtUpdate = $pdo->prepare("
            UPDATE conexiones 
            SET estado = 'aceptada', actualizado_en = NOW() 
            WHERE id = :cid
        ");
        $stmtUpdate->execute([':cid' => $conexion['id']]);

        echo json_encode([
            'success' => true,
            'accion'  => 'aceptada',
            'mensaje' => "¡Has aceptado la conexión con {$nombre}!",
            'contacto' => [
                'id'        => $solicitanteId,
                'nombre'    => $nombre,
                'cargo'     => $cargo,
                'promocion' => $promocion,
                'inicial'   => $inicial,
                'color'     => $colorAvatar,
                'online'    => $solOnline,
                'estado'    => $solEstado
            ]
        ]);
        exit;
    } else {
        $stmtUpdate = $pdo->prepare("
            UPDATE conexiones 
            SET estado = 'rechazada', actualizado_en = NOW() 
            WHERE id = :cid
        ");
        $stmtUpdate->execute([':cid' => $conexion['id']]);

        echo json_encode([
            'success' => true,
            'accion'  => 'rechazada',
            'mensaje' => "Solicitud de {$nombre} descartada correctamente."
        ]);
        exit;
    }

} catch (PDOException $e) {
    error_log('Error en responder-solicitud.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Error interno al procesar la solicitud de conexión.'
    ]);
}
