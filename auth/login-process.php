<?php
/**
 * Controlador backend preliminar: Procesamiento de Autenticación
 * Fase 4 — Implementación (Pendiente integración de BD y sesiones definitivas)
 * Versión: 0.1.5
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password'] ?? '');

// Validación básica del lado del servidor
if (empty($email) || empty($password)) {
    $_SESSION['auth_error'] = 'Por favor, completa todos los campos requeridos.';
    header('Location: ../login.php');
    exit;
}

// En esta etapa de desarrollo frontend (Fase 4), la autenticación con BD se encuentra en construcción
$_SESSION['auth_notice'] = 'El sistema de autenticación backend se encuentra en fase de construcción e integración con la base de datos.';
header('Location: ../login.php');
exit;
