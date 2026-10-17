<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: PQRS y Reporte de Errores (pqr.php)
 * Conexión completa a MySQL (tabla pqrs) y radicación institucional
 * Versión: 0.2.0
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Procesamiento de formulario POST (compatible con AJAX y envío estándar)
$pqrSuccess = $_SESSION['pqr_success'] ?? null;
$pqrError = $_SESSION['pqr_error'] ?? null;
unset($_SESSION['pqr_success'], $_SESSION['pqr_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

    $nombre  = trim($_POST['nombre'] ?? '');
    $correo  = filter_var(trim($_POST['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
    $tipo    = trim($_POST['tipo'] ?? 'Petición');
    $asunto  = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($correo) || empty($tipo) || empty($asunto) || empty($mensaje)) {
        $msgErr = 'Por favor completa todos los campos requeridos (*).';
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $msgErr]);
            exit;
        }
        $_SESSION['pqr_error'] = $msgErr;
        header('Location: index.php?page=pqr');
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $msgErr = 'Ingresa un correo electrónico válido para poder responderte.';
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $msgErr]);
            exit;
        }
        $_SESSION['pqr_error'] = $msgErr;
        header('Location: index.php?page=pqr');
        exit;
    }

    // Procesar archivo adjunto si se envió
    $rutaImagen = null;
    if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['png', 'jpg', 'jpeg', 'webp'];

        if (in_array($ext, $allowedExts, true) && $file['size'] <= 5 * 1024 * 1024) {
            $uploadsDir = __DIR__ . '/../uploads/pqrs';
            if (!is_dir($uploadsDir)) {
                @mkdir($uploadsDir, 0777, true);
            }
            $nuevoNombre = 'pqr_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $destinoFisico = $uploadsDir . '/' . $nuevoNombre;
            if (move_uploaded_file($file['tmp_name'], $destinoFisico)) {
                $rutaImagen = 'uploads/pqrs/' . $nuevoNombre;
            }
        }
    }

    // Generar número de radicado único
    $anio = date('Y');
    $radicado = 'RA-' . $anio . '-' . str_pad((string)mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    // Prioridad por defecto según el tipo
    $prioridad = 'Media';
    if (stripos($tipo, 'error') !== false || stripos($tipo, 'reclamo') !== false) {
        $prioridad = 'Alta';
    } elseif (stripos($tipo, 'sugerencia') !== false) {
        $prioridad = 'Baja';
    }

    try {
        $stmtInsert = $pdo->prepare("
            INSERT INTO pqrs (radicado, tipo, nombre, email, asunto, mensaje, imagen, estado, prioridad, creado_en)
            VALUES (:radicado, :tipo, :nombre, :email, :asunto, :mensaje, :imagen, 'pendiente', :prioridad, NOW())
        ");
        $stmtInsert->execute([
            ':radicado'  => $radicado,
            ':tipo'      => $tipo,
            ':nombre'    => $nombre,
            ':email'     => $correo,
            ':asunto'    => $asunto,
            ':mensaje'   => $mensaje,
            ':imagen'    => $rutaImagen,
            ':prioridad' => $prioridad
        ]);

        $exitoMsg = "Tu solicitud ha sido radicada oficialmente con el número {$radicado}. Se responderá a tu correo {$correo}.";

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success'  => true,
                'radicado' => $radicado,
                'message'  => $exitoMsg
            ]);
            exit;
        }

        $_SESSION['pqr_success'] = $radicado;
        header('Location: index.php?page=pqr');
        exit;

    } catch (PDOException $e) {
        error_log('Error insertando PQRS: ' . $e->getMessage());
        $msgErr = 'Hubo un error al guardar tu solicitud. Por favor intenta nuevamente.';
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $msgErr]);
            exit;
        }
        $_SESSION['pqr_error'] = $msgErr;
        header('Location: index.php?page=pqr');
        exit;
    }
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

require_once __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/pqr.css">

<?php if ($estaAutenticado): ?>
    <!-- Telón de fondo para menú en dispositivos móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <div class="layout-wrapper dashboard-layout" id="dashboardLayout">
        <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="main-wrapper dashboard-main-area">
            <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

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
                    <p class="pqr-subtitle">¿Tienes alguna duda, petición, reclamo o encontraste un error en la plataforma? Déjanos tu mensaje y te responderemos a tu correo electrónico.</p>
                </header>

                <?php if ($pqrSuccess): ?>
                <!-- Notificación de Radicado Exitoso -->
                <div class="pqr-success-card" id="pqrSuccessCard" style="background: #E8F5E9; border: 1px solid #A5D6A7; border-left: 5px solid #2E7D32; border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; color: #1B5E20; display: flex; align-items: flex-start; gap: 16px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div>
                        <strong style="font-size: 1.1rem; display: block; margin-bottom: 6px; color: #1B5E20;">¡Solicitud radicada con éxito!</strong>
                        <p style="margin: 0 0 8px; font-size: 0.95rem; line-height: 1.5;">
                            Tu número de radicado oficial es: <strong style="font-family: monospace; font-size: 1.05rem; background: #C8E6C9; padding: 2px 8px; border-radius: 4px;"><?= htmlspecialchars($pqrSuccess) ?></strong>
                        </p>
                        <span style="font-size: 0.85rem; color: #388E3C;">Nuestro equipo institucional revisará tu solicitud y te enviará respuesta al correo registrado.</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($pqrError): ?>
                <div class="pqr-error-card" style="background: #FFEBEE; border: 1px solid #FFCDD2; border-left: 5px solid #D32F2F; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; color: #B71C1C;">
                    <strong>Error:</strong> <?= htmlspecialchars($pqrError) ?>
                </div>
                <?php endif; ?>

                <!-- Aviso institucional oficial -->
                <div class="pqr-status-notice" style="background: #F8F9FA; border: 1px solid #E0E0E0; border-left: 4px solid #1565C0; border-radius: 8px; padding: 14px 18px; margin-bottom: 24px; color: #212121; display: flex; align-items: center; gap: 12px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <div style="font-size: 0.9rem; line-height: 1.4;">
                        Canal digital oficial de la <strong>I.E. Gilberto Alzate Avendaño</strong>. Toda solicitud genera un código de radicado único para seguimiento administrativo.
                    </div>
                </div>

                <div class="pqr-card">
                    <form id="formPQR" action="index.php?page=pqr" method="POST" enctype="multipart/form-data" onsubmit="enviarSolicitudPQR(event);">
                        
                        <!-- Fila 1: Nombre y Correo -->
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="nombre">Nombre completo <span class="req">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-input" placeholder="Tu nombre y apellido" value="<?= htmlspecialchars($nombreUsuario) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="correo">Correo electrónico <span class="req">*</span></label>
                                <input type="email" name="correo" id="correo" class="form-input" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($correoUsuario) ?>" required>
                            </div>
                        </div>

                        <!-- Fila 2: Tipo de Solicitud -->
                        <div class="form-group">
                            <label for="tipo">Tipo de solicitud <span class="req">*</span></label>
                            <select name="tipo" id="tipo" class="form-select" required>
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
                            <input type="text" name="asunto" id="asunto" class="form-input" placeholder="Ej: Consulta sobre certificado / Error en carga de imagen" required>
                        </div>

                        <!-- Fila 4: Descripción detallada -->
                        <div class="form-group">
                            <label for="mensaje">Descripción o detalle <span class="req">*</span></label>
                            <textarea name="mensaje" id="mensaje" rows="5" class="form-textarea" placeholder="Escribe aquí tu mensaje o describe los pasos de lo sucedido..." required></textarea>
                        </div>

                        <!-- Fila 5: Adjuntar imagen (Opcional) -->
                        <div class="form-group">
                            <label>Adjuntar imagen o captura de pantalla <span class="opcional">(Opcional)</span></label>
                            <div class="dropzone-box" id="dropzoneBox" onclick="document.getElementById('inputImg').click();">
                                <input type="file" name="imagen" id="inputImg" accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="cargarPreview(this)">
                                
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

                        <!-- Botonera: Limpiar / Enviar -->
                        <div class="pqr-actions">
                            <button type="button" class="btn btn-outline-secondary btn-pqr-cancel" onclick="limpiarFormulario()">Limpiar</button>
                            <button type="submit" class="btn btn-primary btn-pqr-submit" id="btnSubmitPQR">
                                <span>Radicar solicitud</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Toast flotante inferior derecho -->
<div class="toast-bottom-right" id="toastPQR" role="status" aria-live="polite">
    <div class="toast-box" style="background:#1B5E20; border-color:#2E7D32; max-width: 420px;">
        <span class="toast-badge-check" style="background: rgba(255,255,255,0.2);">✓</span>
        <span class="toast-msg" id="toastPQRMsg">Solicitud radicada con éxito.</span>
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

function enviarSolicitudPQR(e) {
    e.preventDefault();
    const form = document.getElementById('formPQR');
    const submitBtn = document.getElementById('btnSubmitPQR');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Radicando...';

    const formData = new FormData(form);

    fetch('pqr.php', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;

        if (data.success) {
            // Mostrar notificación exitosa
            const toast = document.getElementById('toastPQR');
            const toastMsg = document.getElementById('toastPQRMsg');
            if (toast && toastMsg) {
                toastMsg.textContent = '¡Radicado ' + data.radicado + ' generado exitosamente!';
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 6000);
            }

            // Inyectar tarjeta de éxito en la vista
            const wrapper = document.querySelector('.pqr-form-wrapper');
            let successCard = document.getElementById('pqrSuccessCard');
            if (!successCard) {
                successCard = document.createElement('div');
                successCard.id = 'pqrSuccessCard';
                successCard.style.cssText = 'background: #E8F5E9; border: 1px solid #A5D6A7; border-left: 5px solid #2E7D32; border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; color: #1B5E20; display: flex; align-items: flex-start; gap: 16px;';
                const formCard = document.querySelector('.pqr-card');
                wrapper.insertBefore(successCard, formCard);
            }

            successCard.innerHTML = `
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div>
                    <strong style="font-size: 1.1rem; display: block; margin-bottom: 6px; color: #1B5E20;">¡Solicitud radicada con éxito!</strong>
                    <p style="margin: 0 0 8px; font-size: 0.95rem; line-height: 1.5;">
                        Tu número de radicado oficial es: <strong style="font-family: monospace; font-size: 1.05rem; background: #C8E6C9; padding: 2px 8px; border-radius: 4px;">${data.radicado}</strong>
                    </p>
                    <span style="font-size: 0.85rem; color: #388E3C;">Nuestro equipo institucional revisará tu solicitud y te enviará respuesta al correo registrado.</span>
                </div>
            `;

            successCard.scrollIntoView({ behavior: 'smooth' });
            limpiarFormulario();
        } else {
            alert(data.message || 'Ocurrió un error al enviar la solicitud.');
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        // Fallback: enviar formulario tradicional
        form.submit();
    });
}

// Soporte Drag and Drop
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
