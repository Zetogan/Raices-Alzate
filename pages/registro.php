<?php
/**
 * Vista: Pantalla de Registro de Usuarios
 * Especificación Figma: 02 - Registro / Crear cuenta (Desktop / Mobile)
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
            <h2 class="auth-section-title">Crear una cuenta</h2>
            <p class="auth-brand-subtitle">Regístrate para formar parte de la comunidad de egresados.</p>
        </header>

        <!-- 2. Bloque de Formulario -->
        <section class="auth-content">
            <form action="auth/registro-process.php" method="POST" class="auth-form" id="registroForm" novalidate>
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

                <!-- Campo 1: Nombre completo -->
                <div class="form-group">
                    <label for="nombre_completo" class="form-label">Nombre completo</label>
                    <input 
                        type="text" 
                        name="nombre_completo" 
                        id="nombre_completo" 
                        class="form-input" 
                        placeholder="Ingresa tu nombre completo" 
                        required 
                        autocomplete="name"
                        aria-describedby="error_nombre_completo"
                    >
                    <span class="field-error-message" id="error_nombre_completo" role="alert" aria-live="polite"></span>
                </div>

                <!-- Campo 2: Correo electrónico -->
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-input" 
                        placeholder="correo@ejemplo.com" 
                        required 
                        autocomplete="email"
                        aria-describedby="error_email"
                    >
                    <span class="field-error-message" id="error_email" role="alert" aria-live="polite"></span>
                </div>

                <!-- Campo 3: Selector de Rol (Tipo de usuario) -->
                <div class="form-group">
                    <span class="form-label" id="label_tipo_usuario">Tipo de usuario</span>
                    <div class="radio-group" role="radiogroup" aria-labelledby="label_tipo_usuario">
                        <label class="radio-option" for="rol_estudiante">
                            <input 
                                type="radio" 
                                name="tipo_usuario" 
                                id="rol_estudiante" 
                                value="estudiante" 
                                class="custom-radio-input" 
                                checked
                            >
                            <span class="radio-text">Estudiante</span>
                        </label>
                        
                        <label class="radio-option" for="rol_egresado">
                            <input 
                                type="radio" 
                                name="tipo_usuario" 
                                id="rol_egresado" 
                                value="egresado" 
                                class="custom-radio-input"
                            >
                            <span class="radio-text">Egresado</span>
                        </label>
                    </div>
                    <span class="field-error-message" id="error_tipo_usuario" role="alert" aria-live="polite"></span>
                </div>

                <!-- Campo 4: Contraseña -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-input" 
                        placeholder="Ingresa una contraseña" 
                        required 
                        autocomplete="new-password"
                        aria-describedby="error_password"
                    >
                    <span class="field-error-message" id="error_password" role="alert" aria-live="polite"></span>
                </div>

                <!-- Campo 5: Confirmar contraseña -->
                <div class="form-group">
                    <label for="password_confirm" class="form-label">Confirmar contraseña</label>
                    <input 
                        type="password" 
                        name="password_confirm" 
                        id="password_confirm" 
                        class="form-input" 
                        placeholder="Repite tu contraseña" 
                        required 
                        autocomplete="new-password"
                        aria-describedby="error_password_confirm"
                    >
                    <span class="field-error-message" id="error_password_confirm" role="alert" aria-live="polite"></span>
                </div>

                <!-- Botón de Envío -->
                <div class="form-submit">
                    <button type="submit" class="btn btn-primary auth-submit-btn" id="submitBtn">
                        Registrarse
                    </button>
                </div>
            </form>
        </section>

        <!-- 3. Pie de Tarjeta -->
        <footer class="auth-footer">
            <p class="auth-footer-text">
                ¿Ya tienes una cuenta? <a href="login.php" class="auth-link-secondary auth-link-bold">Iniciar sesión</a>
            </p>
        </footer>
    </div>
</main>
