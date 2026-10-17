<?php
/**
 * Vista: Pantalla de Restablecimiento de Contraseña
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * 
 * Valida la existencia y vigencia del token criptográfico enviado por correo
 * antes de renderizar los campos para la nueva contraseña.
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/auth.php';

// Si el usuario ya está autenticado, redirigir al dashboard
if (isAuthenticated()) {
    header('Location: ' . url('index.php?page=dashboard'));
    exit;
}

$pageTitle = 'Establecer nueva contraseña | ' . APP_NAME;
$currentPage = 'reset-password';
$extraCss = ['auth.css'];
$extraJs = ['assets/js/auth.js'];

$tokenCrudo = trim($_GET['token'] ?? '');
$tokenValido = false;
$tokenError = null;
$userEmail = '';

// Validación de presencia y formato del token (64 caracteres hexadecimales)
if (empty($tokenCrudo) || !preg_match('/^[a-f0-9]{64}$/i', $tokenCrudo)) {
    $tokenError = 'El enlace de recuperación es inválido o se encuentra incompleto.';
} else {
    if (!$pdo) {
        $tokenError = 'Error temporal de conexión con el servicio. Por favor, intenta de nuevo más tarde.';
    } else {
        try {
            // Se calcula el hash SHA-256 para buscar en la base de datos
            $tokenHash = hash('sha256', $tokenCrudo);

            $stmt = $pdo->prepare("
                SELECT id, email, nombre_completo, expiracion_token 
                FROM usuarios 
                WHERE token_recuperacion = :token_hash 
                LIMIT 1
            ");
            $stmt->execute([':token_hash' => $tokenHash]);
            $userFound = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userFound) {
                $tokenError = 'Este enlace de recuperación no es válido o ya ha sido utilizado anteriormente.';
            } else {
                // Comprobación de caducidad temporal
                $fechaExpiracion = strtotime((string)$userFound['expiracion_token']);
                if ($fechaExpiracion < time()) {
                    $tokenError = 'Este enlace de recuperación ha expirado por superar el tiempo límite de validez (1 hora).';
                } else {
                    $tokenValido = true;
                    $userEmail = $userFound['email'];
                }
            }
        } catch (Throwable $e) {
            error_log('Error verificando token de recuperación: ' . $e->getMessage());
            $tokenError = 'Ocurrió un error al verificar tu solicitud. Por favor intenta más tarde.';
        }
    }
}

$serverError = $_SESSION['auth_error'] ?? null;
$serverNotice = $_SESSION['auth_notice'] ?? null;
unset($_SESSION['auth_error'], $_SESSION['auth_notice']);

require_once __DIR__ . '/../includes/header.php';
?>
<main class="auth-page">
    <div class="auth-card">
        <!-- 1. Encabezado Institucional Unificado y Jerárquico -->
        <header class="auth-header" style="margin-bottom: 20px;">
            <h1 class="auth-brand-title"><?php echo htmlspecialchars(APP_NAME); ?></h1>
            <h2 class="auth-section-title" style="margin: 4px 0 6px 0; font-size: 20px;">Crear nueva contraseña</h2>
            <p class="auth-brand-subtitle">
                <?php if ($tokenValido): ?>
                    Ingresa y confirma tu nueva contraseña de acceso.
                <?php else: ?>
                    Estado del enlace de recuperación.
                <?php endif; ?>
            </p>
        </header>

        <!-- 2. Bloque de Contenido -->
        <section class="auth-content">

            <?php if (!$tokenValido): ?>
                <!-- Estado: Token Inválido o Expirado -->
                <div class="alert alert-error" role="alert" style="display: flex; margin-bottom: 24px;">
                    <span class="alert-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                            <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                        </svg>
                    </span>
                    <p class="alert-text"><?php echo htmlspecialchars($tokenError ?: 'Enlace inválido.'); ?></p>
                </div>

                <div class="form-submit" style="margin-top: 20px;">
                    <a href="index.php?page=recuperar-password" class="btn btn-primary auth-submit-btn" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        Solicitar nuevo enlace
                    </a>
                </div>

            <?php else: ?>
                <!-- Estado: Token Válido, Formulario de Cambio de Clave -->
                <form action="auth/cambiar_password.php" method="POST" class="auth-form" id="resetPasswordForm" novalidate>
                    <?php if (function_exists('csrfField')): echo csrfField(); endif; ?>
                    
                    <!-- Token crudo para verificación atómica en el backend -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($tokenCrudo, ENT_QUOTES, 'UTF-8'); ?>">

                    <!-- Alerta de feedback del servidor si existe -->
                    <div class="alert <?php echo $serverError ? 'alert-error' : ($serverNotice ? 'alert-success' : 'alert-error'); ?>" id="formFeedback" role="alert" aria-live="polite" style="<?php echo ($serverError || $serverNotice) ? 'display: flex;' : 'display: none;'; ?>">
                        <span class="alert-icon" id="formFeedbackIcon" aria-hidden="true">
                            <?php if ($serverNotice): ?>
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            <?php else: ?>
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                            <?php endif; ?>
                        </span>
                        <p class="alert-text" id="formFeedbackText"><?php echo htmlspecialchars($serverError ?: ($serverNotice ?: '')); ?></p>
                    </div>

                    <!-- Indicador de cuenta en chip estilizado -->
                    <div class="auth-account-chip">
                        <div class="auth-account-avatar">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <div class="auth-account-info">
                            <span>Restableciendo para:</span>
                            <span class="auth-account-email"><?php echo htmlspecialchars($userEmail); ?></span>
                        </div>
                    </div>

                    <!-- Campo 1: Nueva Contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva contraseña</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-input" 
                                placeholder="Mínimo 6 caracteres" 
                                required 
                                autocomplete="new-password"
                                minlength="6"
                                aria-describedby="error_password"
                            >
                            <button type="button" class="btn-toggle-password" data-target="password" aria-label="Mostrar contraseña" title="Mostrar u ocultar contraseña">
                                <svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            </button>
                        </div>
                        <span class="field-error-message" id="error_password" role="alert" aria-live="polite"></span>
                    </div>

                    <!-- Campo 2: Confirmar Contraseña -->
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmar nueva contraseña</label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                name="password_confirm" 
                                id="password_confirm" 
                                class="form-input" 
                                placeholder="Repite la contraseña" 
                                required 
                                autocomplete="new-password"
                                minlength="6"
                                aria-describedby="error_password_confirm"
                            >
                            <button type="button" class="btn-toggle-password" data-target="password_confirm" aria-label="Mostrar contraseña" title="Mostrar u ocultar contraseña">
                                <svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            </button>
                        </div>
                        <span class="field-error-message" id="error_password_confirm" role="alert" aria-live="polite"></span>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="form-submit">
                        <button type="submit" class="btn btn-primary auth-submit-btn" id="submitBtn">
                            Guardar contraseña
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </section>

        <!-- 3. Pie de Tarjeta -->
        <footer class="auth-footer">
            <a href="index.php?page=login" class="auth-link-secondary auth-link-bold">
                &larr; Volver a iniciar sesión
            </a>
        </footer>
    </div>
</main>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
