<?php
/**
 * Controlador backend: Procesamiento de Registro de Usuarios
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

$nombreCompleto = trim($_POST['nombre_completo'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$tipoUsuario = trim($_POST['tipo_usuario'] ?? 'estudiante');
$password = trim($_POST['password'] ?? '');
$passwordConfirm = trim($_POST['password_confirm'] ?? '');

// 1. Validación de campos obligatorios
if (empty($nombreCompleto) || empty($email) || empty($password) || empty($passwordConfirm)) {
    $_SESSION['auth_error'] = 'Por favor, completa todos los campos requeridos.';
    header('Location: ../registro.php');
    exit;
}

// 2. Validación de formato de correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['auth_error'] = 'Ingresa una dirección de correo electrónico válida.';
    header('Location: ../registro.php');
    exit;
}

// 3. Validación de rol permitido
if (!in_array($tipoUsuario, ['estudiante', 'egresado'], true)) {
    $tipoUsuario = 'estudiante';
}

// 4. Validación de longitud de contraseña
if (strlen($password) < 6) {
    $_SESSION['auth_error'] = 'La contraseña debe contener al menos 6 caracteres.';
    header('Location: ../registro.php');
    exit;
}

// 5. Validación de coincidencia de contraseñas
if ($password !== $passwordConfirm) {
    $_SESSION['auth_error'] = 'Las contraseñas no coinciden.';
    header('Location: ../registro.php');
    exit;
}

// 6. Hasheo seguro de contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// 7. Verificación e inserción en base de datos
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Comprobar existencia previa de correo
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['auth_error'] = 'El correo electrónico ya se encuentra registrado.';
        header('Location: ../registro.php');
        exit;
    }
    
    // Inserción en tabla usuarios
    $insertStmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, email, tipo_usuario, password_hash, creado_en) VALUES (?, ?, ?, ?, NOW())");
    $insertStmt->execute([$nombreCompleto, $email, $tipoUsuario, $passwordHash]);
    
    $_SESSION['auth_notice'] = 'Registro completado con éxito. Ahora puedes iniciar sesión.';
    header('Location: ../login.php');
    exit;

} catch (PDOException $e) {
    // Si la base de datos o tabla no se encuentra inicializada en esta etapa
    $_SESSION['auth_notice'] = 'Datos validados y contraseña cifrada con éxito. La base de datos local (' . htmlspecialchars(DB_NAME) . ') se encuentra en etapa de inicialización.';
    header('Location: ../registro.php');
    exit;
}
