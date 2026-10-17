<?php
/**
 * Controlador Backend: Procesamiento de Registro de Usuarios
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * Versión: 0.2.0
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=registro');
    exit;
}

// Verificar conexión a la base de datos
if (!$pdo) {
    $_SESSION['auth_error'] = 'Error temporal de conexión con el servicio. Intenta más tarde.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 1. Verificación de seguridad CSRF
if (!verifyCsrfToken()) {
    $_SESSION['auth_error'] = 'El token de seguridad ha expirado o es inválido. Por favor, intenta de nuevo.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 2. Captura y saneamiento de entradas
$nombreCompleto  = trim($_POST['nombre_completo'] ?? '');
$email           = trim($_POST['email'] ?? '');
$tipoUsuario     = strtolower(trim($_POST['tipo_usuario'] ?? ''));
$password        = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

// 3. Validación de campos obligatorios
if ($nombreCompleto === '' || $email === '' || $password === '' || $passwordConfirm === '') {
    $_SESSION['auth_error'] = 'Por favor, completa todos los campos requeridos.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 4. Validación de longitud de nombre
if (mb_strlen($nombreCompleto, 'UTF-8') < 3 || mb_strlen($nombreCompleto, 'UTF-8') > 150) {
    $_SESSION['auth_error'] = 'El nombre completo debe tener entre 3 y 150 caracteres.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 5. Validación del formato de correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['auth_error'] = 'Ingresa una dirección de correo electrónico válida.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 6. Mapeo seguro de roles permitidos en registro público (NO se permite rol administrador)
$rolesPermitidos = [
    'egresado'   => 2,
    'estudiante' => 3
];

if (!isset($rolesPermitidos[$tipoUsuario])) {
    $_SESSION['auth_error'] = 'El tipo de usuario seleccionado no está permitido.';
    header('Location: ../index.php?page=registro');
    exit;
}
$rolId = $rolesPermitidos[$tipoUsuario];

// 7. Validación de longitud mínima de contraseña
if (strlen($password) < 6) {
    $_SESSION['auth_error'] = 'La contraseña debe contener al menos 6 caracteres.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 8. Validación de coincidencia de contraseñas
if ($password !== $passwordConfirm) {
    $_SESSION['auth_error'] = 'Las contraseñas no coinciden.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 9. Detección de correo electrónico duplicado mediante consulta preparada
try {
    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $stmtCheck->execute([$email]);
    if ($stmtCheck->fetch()) {
        $_SESSION['auth_error'] = 'El correo electrónico ya se encuentra registrado.';
        header('Location: ../index.php?page=registro');
        exit;
    }
} catch (PDOException $e) {
    error_log('Error comprobando correo en registro: ' . $e->getMessage());
    $_SESSION['auth_error'] = 'Ocurrió un error al procesar los datos. Intenta nuevamente.';
    header('Location: ../index.php?page=registro');
    exit;
}

// 10. Generación del hash criptográfico de la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// 11. Inserción con transacción segura en la base de datos
try {
    $pdo->beginTransaction();

    // Inserción en la tabla usuarios con estado_verificacion = 'pendiente' y activo = 1
    $insertUser = $pdo->prepare("
        INSERT INTO usuarios (
            rol_id,
            nombre_completo,
            email,
            password_hash,
            estado_verificacion,
            activo,
            creado_en
        ) VALUES (
            :rol_id,
            :nombre_completo,
            :email,
            :password_hash,
            'pendiente',
            1,
            NOW()
        )
    ");
    $insertUser->execute([
        ':rol_id'          => $rolId,
        ':nombre_completo' => $nombreCompleto,
        ':email'           => $email,
        ':password_hash'   => $passwordHash
    ]);

    $nuevoUsuarioId = (int)$pdo->lastInsertId();

    // Si el usuario es egresado, inicializar su perfil 1:1 en perfiles_egresados
    if ($rolId === 2) {
        $insertPerfil = $pdo->prepare("
            INSERT INTO perfiles_egresados (
                usuario_id,
                perfil_publico,
                creado_en
            ) VALUES (
                :usuario_id,
                1,
                NOW()
            )
        ");
        $insertPerfil->execute([':usuario_id' => $nuevoUsuarioId]);
    }

    $pdo->commit();

    $_SESSION['auth_notice'] = 'Registro completado con éxito. Tu cuenta se encuentra en proceso de aprobación por la administración.';
    header('Location: ../index.php?page=login');
    exit;

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error en transacción de registro: ' . $e->getMessage());
    $_SESSION['auth_error'] = 'No fue posible completar el registro. Intenta de nuevo más tarde.';
    header('Location: ../index.php?page=registro');
    exit;
}
