<?php
// Asegurar que no haya salidas previas
ob_start();

// Cargar configuración base para mantener coherencia en cookies y rutas
if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Estructura de usuario en inglés (la que planteamos)
$_SESSION['user'] = [
    'id'     => 1,
    'nombre' => 'Santiago Emitola',
    'rol'    => 'Egresado',
    'email'  => 'santiago@ejemplo.com'
];

// 2. Estructura de usuario en español (por compatibilidad)
$_SESSION['usuario'] = [
    'id'     => 1,
    'nombre' => 'Santiago Emitola',
    'rol'    => 'Egresado',
    'email'  => 'santiago@ejemplo.com'
];

// 3. Variables directas comunes
$_SESSION['id']             = 1;
$_SESSION['user_id']        = 1;
$_SESSION['usuario_id']     = 1;
$_SESSION['nombre']         = 'Santiago Emitola';
$_SESSION['rol']            = 'Egresado';
$_SESSION['autenticado']    = true;
$_SESSION['logged_in']      = true;

// Asegurar guardado en disco de la sesión
session_write_close();

// Redirección limpia
header('Location: dashboard.php');
exit;