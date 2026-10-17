<?php
/**
 * Controlador Backend: Cambiar Contraseña (Restablecimiento por Token)
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * 
 * Especialidad en Ciberseguridad:
 * 1. Verificación obligatoria de token anti-CSRF.
 * 2. Comprobación criptográfica del token (hash SHA-256).
 * 3. Validación de expiración estricta de 60 minutos.
 * 4. Hashing de contraseña con algoritmo estándar robusto: password_hash(PASSWORD_DEFAULT).
 * 5. Invalidez atómica inmediata: token_recuperacion = NULL, expiracion_token = NULL.
 * 6. Prepared Statements con PDO nativo.
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';

// 1. Verificación estricta de método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: ../index.php?page=login');
    exit;
}

// 2. Conexión a la base de datos
if (!$pdo) {
    $_SESSION['auth_error'] = 'El servicio no se encuentra disponible temporalmente. Intenta más tarde.';
    header('Location: ../index.php?page=login');
    exit;
}

// 3. Captura y saneamiento inicial
$token           = trim($_POST['token'] ?? '');
$password        = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

$redirectUrl = !empty($token) 
    ? '../index.php?page=reset-password&token=' . urlencode($token) 
    : '../index.php?page=recuperar-password';

// 4. Verificación de token anti-CSRF
if (!verifyCsrfToken()) {
    $_SESSION['auth_error'] = 'La sesión de seguridad ha expirado o es inválida. Por favor, intenta de nuevo.';
    header('Location: ' . $redirectUrl);
    exit;
}

// 5. Validación de formato de token (64 caracteres hexadecimales)
if (empty($token) || !preg_match('/^[a-f0-9]{64}$/i', $token)) {
    $_SESSION['auth_error'] = 'El enlace de recuperación es inválido o se encuentra dañado.';
    header('Location: ../index.php?page=recuperar-password');
    exit;
}

// 6. Validación de longitud y complejidad de la contraseña
if (strlen($password) < 6) {
    $_SESSION['auth_error'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
    header('Location: ' . $redirectUrl);
    exit;
}

if (strlen($password) > 120) {
    $_SESSION['auth_error'] = 'La contraseña no puede exceder los 120 caracteres.';
    header('Location: ' . $redirectUrl);
    exit;
}

// 7. Validación de coincidencia de contraseñas
if ($password !== $passwordConfirm) {
    $_SESSION['auth_error'] = 'Las contraseñas ingresadas no coinciden.';
    header('Location: ' . $redirectUrl);
    exit;
}

try {
    // 8. Búsqueda por el hash SHA-256 del token
    $tokenHash = hash('sha256', $token);

    $stmt = $pdo->prepare("
        SELECT id, email, expiracion_token 
        FROM usuarios 
        WHERE token_recuperacion = :token_hash 
        LIMIT 1
    ");
    $stmt->execute([':token_hash' => $tokenHash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['auth_error'] = 'El enlace de recuperación es inválido o ya fue utilizado.';
        header('Location: ../index.php?page=recuperar-password');
        exit;
    }

    // 9. Comprobación estricta de caducidad
    $fechaExpiracion = strtotime((string)$user['expiracion_token']);
    if ($fechaExpiracion < time()) {
        // Limpieza preventiva si ya expiró
        $stmtClean = $pdo->prepare("UPDATE usuarios SET token_recuperacion = NULL, expiracion_token = NULL WHERE id = :id");
        $stmtClean->execute([':id' => $user['id']]);

        $_SESSION['auth_error'] = 'El enlace de recuperación ha expirado. Por favor, solicita uno nuevo.';
        header('Location: ../index.php?page=recuperar-password');
        exit;
    }

    // 10. Hashing criptográfico estándar de la nueva contraseña
    $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);

    // 11. Actualización atómica de la clave e INVALIDACIÓN INMEDIATA DEL TOKEN (NULL)
    $stmtUpdate = $pdo->prepare("
        UPDATE usuarios 
        SET password_hash      = :password_hash, 
            token_recuperacion = NULL, 
            expiracion_token   = NULL, 
            actualizado_en     = NOW() 
        WHERE id = :id
    ");
    $stmtUpdate->execute([
        ':password_hash' => $newPasswordHash,
        ':id'            => (int)$user['id']
    ]);

    // 12. Regeneración de ID de sesión por seguridad tras cambio de clave
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    // 13. Notificación de éxito y redirección a login
    $_SESSION['auth_notice'] = '¡Tu contraseña ha sido restablecida exitosamente! Ya puedes iniciar sesión con tu nueva contraseña.';
    header('Location: ../index.php?page=login');
    exit;

} catch (Throwable $e) {
    error_log('Error en cambio de contraseña: ' . $e->getMessage());
    $_SESSION['auth_error'] = 'Ocurrió un error en el servidor al actualizar la contraseña. Por favor, intenta de nuevo.';
    header('Location: ' . $redirectUrl);
    exit;
}
