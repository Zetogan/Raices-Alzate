<?php
/**
 * Controlador Backend: Solicitud de Restablecimiento de Contraseña
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * 
 * Especialidad en Ciberseguridad:
 * 1. Verificación obligatoria de token anti-CSRF (hash_equals).
 * 2. Mitigación de enumeración de usuarios (respuesta uniforme tiempo/mensaje).
 * 3. Generación criptográfica CSPRNG: bin2hex(random_bytes(32)) [256 bits].
 * 4. Almacenamiento seguro: solo se persiste el hash SHA-256 del token en MySQL.
 * 5. Expiración estricta a 60 minutos.
 * 6. Prepared Statements con PDO nativo (ATTR_EMULATE_PREPARES => false).
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/mailer.php';

// 1. Verificación estricta de método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: ../index.php?page=recuperar-password');
    exit;
}

// 2. Verificación de conexión a la base de datos
if (!$pdo) {
    $_SESSION['auth_error'] = 'El servicio no se encuentra disponible temporalmente. Intenta más tarde.';
    header('Location: ../index.php?page=recuperar-password');
    exit;
}

// 3. Verificación de token anti-CSRF
if (!verifyCsrfToken()) {
    $_SESSION['auth_error'] = 'La sesión de seguridad ha expirado o es inválida. Por favor, intenta de nuevo.';
    header('Location: ../index.php?page=recuperar-password');
    exit;
}

// 4. Captura y saneamiento del correo electrónico
$correo = filter_var(trim($_POST['correo'] ?? $_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

// Mensaje neutro de respuesta para mitigar enumeración de cuentas
$mensajeRespuestaNeutro = 'Si el correo electrónico ingresado corresponde a una cuenta registrada en Raíces Alzate, recibirás en breve un enlace con instrucciones para restablecer tu contraseña. Revisa también tu carpeta de spam o correo no deseado.';

if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    // Si el formato es visiblemente inválido se solicita corregir el formato
    $_SESSION['auth_error'] = 'Por favor, ingresa una dirección de correo electrónico válida.';
    header('Location: ../index.php?page=recuperar-password');
    exit;
}

try {
    // 5. Consulta preparada PDO para localizar la cuenta
    $stmt = $pdo->prepare("
        SELECT id, nombre_completo, email, activo 
        FROM usuarios 
        WHERE email = :email 
        LIMIT 1
    ");
    $stmt->execute([':email' => $correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && (int)$user['activo'] === 1) {
        // 6. Generación de token criptográficamente seguro (256 bits = 64 hex chars)
        $tokenCrudo = bin2hex(random_bytes(32));

        // 7. Hash del token para almacenamiento en BD (Defensa en profundidad OWASP)
        $tokenHash = hash('sha256', $tokenCrudo);

        // 8. Expiración a 1 hora (60 minutos)
        $expiracion = date('Y-m-d H:i:s', time() + 3600);

        // 9. Actualización atómica en MySQL
        $updateStmt = $pdo->prepare("
            UPDATE usuarios 
            SET token_recuperacion = :token_hash, 
                expiracion_token   = :expiracion,
                actualizado_en     = NOW() 
            WHERE id = :id
        ");
        $updateStmt->execute([
            ':token_hash' => $tokenHash,
            ':expiracion' => $expiracion,
            ':id'         => (int)$user['id']
        ]);

        // 10. Despacho por PHPMailer con enlace al token crudo
        $mailSent = enviarCorreoRecuperacionToken($user['email'], $user['nombre_completo'], $tokenCrudo);

        if (!$mailSent) {
            error_log("[Mailer Error] No se pudo enviar el correo de recuperación a: {$user['email']}");
        }
    } else {
        // Retardo preventivo para mitigar ataques de temporización (timing attacks)
        usleep(random_int(100000, 250000));
    }

    // 11. Respuesta uniforme e indistinguible contra enumeración de usuarios
    $_SESSION['auth_notice'] = $mensajeRespuestaNeutro;
    header('Location: ../index.php?page=recuperar-password');
    exit;

} catch (Throwable $e) {
    error_log('Error en solicitud de recuperación de contraseña: ' . $e->getMessage());
    $_SESSION['auth_error'] = 'Ocurrió un error en el servidor al procesar la solicitud. Por favor intenta más tarde.';
    header('Location: ../index.php?page=recuperar-password');
    exit;
}
