<?php
/**
 * Raíces Alzate - Helper de Protección CSRF (Cross-Site Request Forgery)
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

/**
 * Obtiene el token CSRF actual de la sesión o genera uno nuevo si no existe.
 *
 * @return string Token CSRF criptográficamente seguro
 */
function getCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Alias de compatibilidad para generar/obtener token CSRF
 */
function generateCsrfToken(): string {
    return getCsrfToken();
}

/**
 * Retorna un campo HTML input oculto listo para incrustar en cualquier formulario.
 *
 * @return string Campo HTML
 */
function csrfField(): string {
    $token = htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Valida el token CSRF recibido contra el almacenado en la sesión.
 * Utiliza hash_equals para evitar ataques de temporización (timing attacks).
 *
 * @param string|null $token Token a verificar (si es null, busca en $_POST o headers)
 * @return bool True si el token es válido, false en caso contrario
 */
function verifyCsrfToken(?string $token = null): bool {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    $sessionToken = $_SESSION['csrf_token'] ?? null;
    if (empty($sessionToken) || !is_string($sessionToken)) {
        return false;
    }

    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    }

    if (!is_string($token) || $token === '') {
        return false;
    }

    return hash_equals($sessionToken, $token);
}
