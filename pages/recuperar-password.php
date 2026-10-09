<?php
/**
 * Vista: Pantalla de Recuperación de Contraseña
 * Especificación Figma: 02 - Recuperar contraseña (Desktop / Mobile)
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$serverError = $_SESSION['auth_error'] ?? null;
$serverNotice = $_SESSION['auth_notice'] ?? null;
unset($_SESSION['auth_error'], $_SESSION['auth_notice']);
?>
<main class="auth-page">
    <div class="auth-card">
        <!-- 1. Encabezado Institucional -->
        <header class="auth-header">
            <h1 class="auth-brand-title"><?php echo htmlspecialchars(APP_NAME); ?></h1>
            <h2 class="auth-section-title">Recuperar contraseña</h2>
            <p class="auth-brand-subtitle">Ingresa tu correo electrónico y te enviaremos instrucciones para recuperar tu contraseña.</p>
        </header>

        <!-- 2. Bloque de Formulario -->
        <section class="auth-content">
            <form action="auth/recuperar-process.php" method="POST" class="auth-form" id="recuperarForm" novalidate>
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

                <!-- Campo de Entrada Único: Correo electrónico -->
                <div class="form-group">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input 
                        type="email" 
                        name="correo" 
                        id="correo" 
                        class="form-input" 
                        placeholder="correo@ejemplo.com" 
                        required 
                        autocomplete="email"
                        aria-describedby="error_correo"
                    >
                    <span class="field-error-message" id="error_correo" role="alert" aria-live="polite"></span>
                </div>

                <!-- Botón de Envío -->
                <div class="form-submit">
                    <button type="submit" class="btn btn-primary auth-submit-btn" id="submitBtn">
                        Enviar instrucciones
                    </button>
                </div>
            </form>
        </section>

        <!-- 3. Pie de Tarjeta (Navegación) -->
        <footer class="auth-footer">
            <a href="login.php" class="auth-link-secondary auth-link-bold">Volver a iniciar sesión</a>
        </footer>
    </div>
</main>
