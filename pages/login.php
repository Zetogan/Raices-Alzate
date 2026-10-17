<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Inicio de Sesión / Autenticación
 * Versión: 0.2.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/auth.php';

// Redirección si ya cuenta con sesión activa
if (isAuthenticated()) {
    header('Location: ' . url('index.php?page=dashboard'));
    exit;
}

$pageTitle = 'Iniciar sesión | ' . APP_NAME;
$currentPage = 'login';

$serverError = $_SESSION['auth_error'] ?? null;
$serverNotice = $_SESSION['auth_notice'] ?? null;
unset($_SESSION['auth_error'], $_SESSION['auth_notice']);

require_once __DIR__ . '/../includes/header.php';
?>
<main class="auth-page">
    <div class="auth-card">
        <!-- Encabezado Institucional -->
        <header class="auth-header">
            <h1 class="auth-brand-title"><?php echo htmlspecialchars(APP_NAME); ?></h1>
            <p class="auth-brand-subtitle">Inicia sesión en tu cuenta</p>
        </header>

        <!-- Bloque de Formulario -->
        <section class="auth-content">
            <h2 class="auth-section-title">Iniciar sesión</h2>

            <form action="auth/login-process.php" method="POST" class="auth-form" id="loginForm" novalidate>
                <?php if (function_exists('csrfField')): echo csrfField(); endif; ?>
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

                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-input" 
                        placeholder="Ingresa tu correo electrónico" 
                        required 
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-input" 
                        placeholder="Ingresa tu contraseña" 
                        required 
                        autocomplete="current-password"
                    >
                </div>

                <div class="auth-actions-recovery">
                    <a href="index.php?page=recuperar-password" class="auth-link-secondary">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="form-submit">
                    <button type="submit" class="btn btn-primary auth-submit-btn" id="submitBtn">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </section>

        <!-- Pie de Tarjeta -->
        <footer class="auth-footer">
            <p class="auth-footer-text">
                ¿No tienes una cuenta? <a href="index.php?page=registro" class="auth-link-secondary auth-link-bold">Registrarse</a>
            </p>
        </footer>
    </div>
</main>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
