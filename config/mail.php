<?php
/**
 * Raíces Alzate - Configuración de Servicio de Correo Electrónico (SMTP / PHPMailer)
 * Institución Educativa Gilberto Alzate Avendaño
 * 
 * Este archivo centraliza los parámetros de conexión SMTP protegida.
 * En entornos de producción o desarrollo, las credenciales pueden ser provistas
 * mediante variables de entorno del sistema o configuradas directamente en este archivo.
 */

declare(strict_types=1);

if (!defined('APP_NAME') && !defined('MAIL_TEST_RUNNER')) {
    http_response_code(403);
    exit('Acceso directo no permitido.');
}

// 1. Parámetros del Servidor SMTP
if (!defined('SMTP_HOST')) {
    define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
}

if (!defined('SMTP_PORT')) {
    define('SMTP_PORT', (int)(getenv('SMTP_PORT') ?: 587));
}

if (!defined('SMTP_ENCRYPTION')) {
    // 'tls' (STARTTLS en puerto 587) o 'ssl' (puerto 465)
    define('SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') ?: 'tls');
}

if (!defined('SMTP_AUTH')) {
    define('SMTP_AUTH', true);
}

// 2. Credenciales de Autenticación
// IMPORTANTE: Para Gmail, utiliza tu dirección de correo y una Contraseña de Aplicación
// de 16 caracteres generada desde: https://myaccount.google.com/apppasswords
if (!defined('SMTP_USER')) {
    define('SMTP_USER', getenv('SMTP_USER') ?: 'soporte.raicesalzate@gmail.com');
}

if (!defined('SMTP_PASS')) {
    define('SMTP_PASS', getenv('SMTP_PASS') ?: 'xqtx vsco etaq ilon');
}

// 3. Identidad del Remitente
if (!defined('SMTP_FROM_EMAIL')) {
    define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: SMTP_USER);
}

if (!defined('SMTP_FROM_NAME')) {
    define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Raíces Alzate - IE Gilberto Alzate Avendaño');
}

// 4. Modo de Depuración (0 = desactivado, 1 = comandos cliente, 2 = cliente y servidor)
if (!defined('SMTP_DEBUG')) {
    define('SMTP_DEBUG', (int)(getenv('SMTP_DEBUG') ?: 0));
}
