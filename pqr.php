<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: PQRS y Reporte de Errores (pqr.php)
 * Arquitectura: Frontend Puro (CERO bases de datos, CERO endpoints AJAX)
 * Paleta Oficial: #D32F2F (Rojo), #1565C0 (Azul), #212121, #757575, #E0E0E0, #F5F5F5, #FFFFFF
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

$estaAutenticado = !empty($_SESSION['user']) || !empty($_SESSION['usuario']) || !empty($_SESSION['usuario_id']);
$seccion_activa = $estaAutenticado ? 'pqr' : '';
$currentPage = 'pqr';
$titulo_seccion = 'PQRS y Soporte';
$titulo_pagina = 'PQRS y Reporte de Errores — Raíces Alzate';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Datos de sesión (si existe usuario autenticado)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$nombreUsuario = $_SESSION['usuario_nombre'] ?? $user['name'] ?? $user['nombre'] ?? $_SESSION['nombre'] ?? '';
$correoUsuario = $_SESSION['usuario_email'] ?? $user['email'] ?? $user['correo'] ?? $_SESSION['email'] ?? '';
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
// Hojas de estilo y scripts adicionales para el panel privado y formulario
$extraCss = ['dashboard.css', 'pqr.css'];
$extraJs = ['assets/js/dashboard.js'];

require_once __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/pqr.css">

<?php if ($estaAutenticado): ?>
    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="layout-wrapper dashboard-layout" id="dashboardLayout">
        <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

        <div class="main-wrapper dashboard-main-area">
            <?php require_once __DIR__ . '/includes/topbar.php'; ?>

            <main class="content-area dashboard-content pqr-content-area" id="mainContent">
<?php else: ?>
    <div class="public-page-wrapper">
        <div class="public-content-container">
            <main class="content-area pqr-content-area" id="mainContent">
<?php endif; ?>
            
            <!-- Contenedor centrado exclusivo del formulario -->
            <div class="pqr-form-wrapper">
                
                <header class="pqr-header text-center">
                    <span class="pqr-badge-pill">Atención al usuario</span>
                    <h1 class="pqr-title">PQRS y Reporte de Errores</h1>
                    <p class="pqr-subtitle">¿Tienes alguna duda, reclamo o encontraste un error en la plataforma? Déjanos tu mensaje y te responderemos a tu correo.</p>
                </header>

                <div class="pqr-card">
                    <form id="formPQR" onsubmit="event.preventDefault(); enviarSolicitudPQR();">
                        
                        <!-- Fila 1: Nombre y Correo -->
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="nombre">Nombre completo <span class="req">*</span></label>
                                <input type="text" id="nombre" class="form-input" placeholder="Tu nombre y apellido" value="<?= htmlspecialchars($nombreUsuario) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="correo">Correo electrónico <span class="req">*</span></label>
                                <input type="email" id="correo" class="form-input" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($correoUsuario) ?>" required>
                            </div>
                        </div>

                        <!-- Fila 2: Tipo de Solicitud -->
                        <div class="form-group">
                            <label for="tipo">Tipo de solicitud <span class="req">*</span></label>
                            <select id="tipo" class="form-select" required>
                                <option value="" disabled selected>Selecciona el tipo de mensaje</option>
                                <option value="Reporte de error técnico">🐞 Reportar un error técnico en la plataforma</option>
                                <option value="Petición">📄 Petición (Consulta o solicitud institucional)</option>
                                <option value="Queja">⚠️ Queja (Inconformidad con la atención)</option>
                                <option value="Reclamo">⛔ Reclamo (Exigencia sobre un servicio)</option>
                                <option value="Sugerencia">💡 Sugerencia de mejora</option>
                            </select>
                        </div>

                        <!-- Fila 3: Asunto -->
                        <div class="form-group">
                            <label for="asunto">Asunto <span class="req">*</span></label>
                            <input type="text" id="asunto" class="form-input" placeholder="Ej: No me carga la foto de perfil / Consulta sobre mi promoción" required>
                        </div>

                        <!-- Fila 4: Descripción detallada -->
                        <div class="form-group">
                            <label for="mensaje">Descripción o detalle <span class="req">*</span></label>
                            <textarea id="mensaje" rows="5" class="form-textarea" placeholder="Escribe aquí tu mensaje o describe los pasos de lo sucedido..." required></textarea>
                        </div>

                        <!-- Fila 5: Adjuntar imagen (Opcional para reportar errores) -->
                        <div class="form-group">
                            <label>Adjuntar imagen o captura de pantalla <span class="opcional">(Opcional)</span></label>
                            <div class="dropzone-box" id="dropzoneBox" onclick="document.getElementById('inputImg').click();">
                                <input type="file" id="inputImg" accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="cargarPreview(this)">
                                
                                <div class="dropzone-empty" id="dropzoneEmpty">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <span class="dropzone-text">Haz clic o arrastra una imagen aquí</span>
                                    <span class="dropzone-sub">PNG, JPG o WEBP (Máx. 5MB)</span>
                                </div>

                                <div class="dropzone-has-file" id="dropzoneHasFile" style="display: none;">
                                    <img id="previewImg" src="" alt="Vista previa">
                                    <div class="file-details">
                                        <span id="fileName" class="file-name">captura.png</span>
                                        <button type="button" class="btn-remove-img" onclick="event.stopPropagation(); borrarImagen();">Quitar imagen</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botonera: Cancelar / Enviar -->
                        <div class="pqr-actions">
                            <button type="button" class="btn btn-outline-secondary btn-pqr-cancel" onclick="limpiarFormulario()">Limpiar</button>
                            <button type="submit" class="btn btn-primary btn-pqr-submit">Enviar solicitud</button>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Toast flotante inferior derecho -->
<div class="toast-bottom-right" id="toastPQR" role="status" aria-live="polite">
    <div class="toast-box">
        <span class="toast-badge-check">✓</span>
        <span class="toast-msg" id="toastPQRMsg">Solicitud enviada correctamente</span>
    </div>
</div>

<script>
function cargarPreview(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('dropzoneEmpty').style.display = 'none';
            document.getElementById('dropzoneHasFile').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}

function borrarImagen() {
    const input = document.getElementById('inputImg');
    if (input) input.value = '';
    document.getElementById('dropzoneEmpty').style.display = 'flex';
    document.getElementById('dropzoneHasFile').style.display = 'none';
}

function limpiarFormulario() {
    document.getElementById('formPQR').reset();
    borrarImagen();
}

let toastTimer;
function enviarSolicitudPQR() {
    // Limpiar formulario y quitar foto
    limpiarFormulario();

    // Mostrar alerta toast en la esquina inferior derecha
    const toast = document.getElementById('toastPQR');
    if (!toast) return;
    clearTimeout(toastTimer);
    toast.classList.add('show');
    toastTimer = setTimeout(function() {
        toast.classList.remove('show');
    }, 3500);
}

// Soporte para Drag and Drop en dropzoneBox
(function() {
    const box = document.getElementById('dropzoneBox');
    const input = document.getElementById('inputImg');
    if (!box || !input) return;

    ['dragenter', 'dragover'].forEach(eventName => {
        box.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            box.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        box.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            box.classList.remove('dragover');
        }, false);
    });

    box.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            input.files = files;
            cargarPreview(input);
        }
    }, false);
})();
</script>

<?php if ($estaAutenticado): ?>
<script src="assets/js/dashboard.js"></script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
