<?php
/**
 * Controlador Backend: Procesamiento de Autenticación / Inicio de Sesión
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * Versión: 0.2.0
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/auth.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=login');
    exit;
}

// Verificar conexión a la base de datos
if (!$pdo) {
    $_SESSION['auth_error'] = 'Error temporal de conexión con el servicio. Intenta más tarde.';
    header('Location: ../index.php?page=login');
    exit;
}

// 1. Verificación de seguridad CSRF
if (!verifyCsrfToken()) {
    $_SESSION['auth_error'] = 'El token de seguridad ha expirado o es inválido. Por favor, intenta de nuevo.';
    header('Location: ../index.php?page=login');
    exit;
}

// 2. Captura y saneamiento de entradas
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// 3. Validación de campos obligatorios
if ($email === '' || $password === '') {
    $_SESSION['auth_error'] = 'Por favor, ingresa tu correo electrónico y tu contraseña.';
    header('Location: ../index.php?page=login');
    exit;
}

// 4. Búsqueda de usuario con consulta preparada y obtención del rol
try {
    $stmt = $pdo->prepare("
        SELECT 
            u.id,
            u.rol_id,
            u.nombre_completo,
            u.email,
            u.password_hash,
            u.estado_verificacion,
            u.activo,
            r.nombre AS rol_nombre
        FROM usuarios u
        INNER JOIN roles r ON u.rol_id = r.id
        WHERE u.email = :email
        LIMIT 1
    ");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // 5. Verificación de credenciales (mensaje genérico para no revelar existencia de correos)
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['auth_error'] = 'Correo electrónico o contraseña incorrectos.';
        header('Location: ../index.php?page=login');
        exit;
    }

    // 6. Comprobar que la cuenta se encuentre activa
    if ((int)$user['activo'] !== 1) {
        $_SESSION['auth_error'] = 'Tu cuenta se encuentra desactivada. Comunícate con la administración.';
        header('Location: ../index.php?page=login');
        exit;
    }

    // 7. Comprobar que el estado de verificación permita iniciar sesión
    if ($user['estado_verificacion'] === 'pendiente') {
        $_SESSION['auth_error'] = 'Tu cuenta aún está pendiente de aprobación por parte del administrador.';
        header('Location: ../index.php?page=login');
        exit;
    }

    if ($user['estado_verificacion'] === 'rechazado') {
        $_SESSION['auth_error'] = 'Tu solicitud de registro ha sido rechazada por la administración.';
        header('Location: ../index.php?page=login');
        exit;
    }

    if ($user['estado_verificacion'] !== 'aprobado') {
        $_SESSION['auth_error'] = 'Tu cuenta no tiene autorización para acceder.';
        header('Location: ../index.php?page=login');
        exit;
    }

    // 8. Regenerar el identificador de sesión para prevenir Session Fixation
    session_regenerate_id(true);

    // 9. Actualizar último acceso en la base de datos
    try {
        $updateStmt = $pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id");
        $updateStmt->execute([':id' => $user['id']]);
    } catch (PDOException $e) {
        error_log('Error actualizando ultimo_acceso: ' . $e->getMessage());
    }

    // 10. Guardar la sesión canónica y datos de retrocompatibilidad
    setAuthSession($user);

    // 11. Redirección inteligente según el rol autenticado
    $redirect = $_SESSION['redirect_to'] ?? '';
    unset($_SESSION['redirect_to']);

    if (!empty($redirect) && strpos($redirect, 'index.php') !== false) {
        header('Location: ' . $redirect);
    } elseif (($user['rol_nombre'] ?? '') === 'administrador') {
        header('Location: ../index.php?page=administracion');
    } else {
        header('Location: ../index.php?page=dashboard');
    }
    exit;

} catch (PDOException $e) {
    error_log('Error en proceso de login: ' . $e->getMessage());
    $_SESSION['auth_error'] = 'Ocurrió un error al procesar la autenticación. Intenta de nuevo más tarde.';
    header('Location: ../index.php?page=login');
    exit;
}
