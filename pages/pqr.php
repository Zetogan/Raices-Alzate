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

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
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

<?php if ($estaAutenticado): ?>
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
            
            <!-- Contenedor centrado exclusivo del formulario PQRS -->
            <div class="pqr-form-wrapper">
                
                <header class="pqr-header text-center">
                    <span class="pqr-badge-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Atención Institucional
                    </span>
                    <h1 class="pqr-title">PQRS y Reporte de Errores</h1>
                    <p class="pqr-subtitle">Canal oficial para radicar peticiones, quejas, reclamos, sugerencias o reportar incidencias técnicas en la plataforma Raíces Alzate.</p>
                </header>

                <?php if ($pqrSuccess): ?>
                <!-- Notificación de Radicado Exitoso -->
                <div class="pqr-alert-card pqr-success-card" id="pqrSuccessCard">
                    <div class="pqr-alert-icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="pqr-alert-body">
                        <strong class="pqr-alert-headline">¡Solicitud radicada con éxito!</strong>
                        <p class="pqr-alert-meta">
                            Número de radicado oficial: <strong class="pqr-radicado-badge"><?= htmlspecialchars($pqrSuccess) ?></strong>
                        </p>
                        <span class="pqr-alert-subtext">Nuestro equipo institucional revisará tu solicitud y te enviará respuesta formal al correo registrado.</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($pqrError): ?>
                <div class="pqr-alert-card pqr-error-card" id="pqrErrorCard">
                    <div class="pqr-alert-icon-box pqr-icon-error">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div class="pqr-alert-body">
                        <strong class="pqr-alert-headline">No fue posible radicar tu solicitud</strong>
                        <span class="pqr-alert-subtext"><?= htmlspecialchars($pqrError) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Aviso institucional oficial -->
                <div class="pqr-status-notice">
                    <div class="pqr-notice-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <div class="pqr-notice-body">
                        <span class="pqr-notice-kicker">Trámite Oficial Transparente</span>
                        <p class="pqr-notice-text">
                            Canal digital de la <strong>I.E. Gilberto Alzate Avendaño</strong>. Cada solicitud genera un número de radicado único para trazabilidad y respuesta administrativa.
                        </p>
                    </div>
                </div>

                <div class="pqr-card">
                    <div class="pqr-card-header">
                        <h2 class="pqr-card-title">Formulario de Radicación</h2>
                        <span class="pqr-card-legend">Los campos señalados con (<span class="req">*</span>) son obligatorios.</span>
                    </div>

                    <form id="formPQR" action="index.php?page=pqr" method="POST" enctype="multipart/form-data" onsubmit="enviarSolicitudPQR(event);">
                        
                        <!-- Fila 1: Nombre y Correo -->
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="nombre" class="form-label">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span>Nombre completo</span>
                                    <span class="req" title="Campo obligatorio">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" class="form-input" placeholder="Tu nombre y apellido" value="<?= htmlspecialchars($nombreUsuario) ?>" required autocomplete="name">
                            </div>

                            <div class="form-group">
                                <label for="correo" class="form-label">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <span>Correo electrónico</span>
                                    <span class="req" title="Campo obligatorio">*</span>
                                </label>
                                <input type="email" name="correo" id="correo" class="form-input" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($correoUsuario) ?>" required autocomplete="email">
                            </div>
                        </div>

                        <!-- Fila 2: Tipo de Solicitud -->
                        <div class="form-group">
                            <label for="tipo" class="form-label">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                <span>Tipo de solicitud</span>
                                <span class="req" title="Campo obligatorio">*</span>
                            </label>
                            <div class="form-select-wrap">
                                <select name="tipo" id="tipo" class="form-select" required onchange="actualizarPlaceholderAsunto(this.value)">
                                    <option value="" disabled selected>Selecciona la categoría de tu solicitud</option>
                                    <option value="Reporte de error técnico">🐞 Reportar un error técnico en la plataforma</option>
                                    <option value="Petición">📄 Petición (Consulta o solicitud de información institucional)</option>
                                    <option value="Queja">⚠️ Queja (Inconformidad con la atención recibida)</option>
                                    <option value="Reclamo">⛔ Reclamo (Exigencia por un servicio prestado)</option>
                                    <option value="Sugerencia">💡 Sugerencia de mejora para la comunidad</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fila 3: Asunto Organizado con Contador y Ayuda Dinámica -->
                        <div class="form-group form-group-asunto">
                            <div class="form-label-row">
                                <label for="asunto" class="form-label">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    <span>Asunto de la solicitud</span>
                                    <span class="req" title="Campo obligatorio">*</span>
                                </label>
                                <span class="char-counter" id="asuntoCharCounter">0 / 120</span>
                            </div>
                            <input type="text" name="asunto" id="asunto" class="form-input form-input-asunto" placeholder="Ej: Consulta sobre certificado / Error en carga de imagen" maxlength="120" required autocomplete="off" oninput="contarCaracteresAsunto(this)">
                            <div class="form-field-helper">
                                <span id="asuntoDynamicHint">Escribe un título breve y específico. Encabezará tu radicado ante el equipo institucional.</span>
                            </div>
                        </div>

                        <!-- Fila 4: Descripción detallada -->
                        <div class="form-group">
                            <label for="mensaje" class="form-label">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="7" y2="18"></line></svg>
                                <span>Descripción o detalle <span class="req">*</span></span>
                            </label>
                            <textarea name="mensaje" id="mensaje" rows="5" class="form-textarea" placeholder="Escribe aquí tu mensaje, explicando claramente los antecedentes, fechas o pasos sucedidos..." required></textarea>
                            <span class="form-field-helper">Proporciona detalles específicos para agilizar el análisis y resolución de tu caso.</span>
                        </div>

                        <!-- Fila 5: Adjuntar imagen (Opcional) -->
                        <div class="form-group">
                            <label class="form-label">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span>Adjuntar imagen o captura de pantalla</span>
                                <span class="opcional">(Opcional)</span>
                            </label>
                            <div class="dropzone-box" id="dropzoneBox" onclick="document.getElementById('inputImg').click();" role="button" tabindex="0" aria-label="Adjuntar archivo de imagen">
                                <input type="file" name="imagen" id="inputImg" accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="cargarPreview(this)">
                                
                                <div class="dropzone-empty" id="dropzoneEmpty">
                                    <div class="dropzone-icon-circle">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                    </div>
                                    <span class="dropzone-text">Haz clic o arrastra una captura de pantalla aquí</span>
                                    <span class="dropzone-sub">Formatos admitidos: PNG, JPG o WEBP (Máx. 5MB)</span>
                                </div>

                                <div class="dropzone-has-file" id="dropzoneHasFile" style="display: none;">
                                    <img id="previewImg" src="" alt="Vista previa de evidencia">
                                    <div class="file-details">
                                        <span id="fileName" class="file-name">captura.png</span>
                                        <span class="file-status-tag">Imagen adjuntada</span>
                                        <button type="button" class="btn-remove-img" onclick="event.stopPropagation(); borrarImagen();">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            <span>Quitar imagen</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botonera: Limpiar / Enviar -->
                        <div class="pqr-actions">
                            <button type="button" class="btn-pqr-cancel" onclick="limpiarFormulario()">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                <span>Limpiar formulario</span>
                            </button>
                            <button type="submit" class="btn-pqr-submit" id="btnSubmitPQR">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
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
    <div class="toast-box">
        <span class="toast-badge-check">✓</span>
        <span class="toast-msg" id="toastPQRMsg">Solicitud radicada con éxito.</span>
    </div>
</div>

<script>
function contarCaracteresAsunto(input) {
    const counter = document.getElementById('asuntoCharCounter');
    if (counter) {
        const len = input.value.length;
        counter.textContent = len + ' / 120';
        if (len >= 110) {
            counter.classList.add('near-limit');
        } else {
            counter.classList.remove('near-limit');
        }
    }
}

function actualizarPlaceholderAsunto(tipo) {
    const asuntoInput = document.getElementById('asunto');
    const hintEl = document.getElementById('asuntoDynamicHint');
    if (!asuntoInput) return;

    const guias = {
        'Reporte de error técnico': {
            placeholder: 'Ej: Error al subir certificado en mi perfil de egresado',
            hint: 'Describe brevemente la falla o pantalla donde ocurre el inconveniente técnico.'
        },
        'Petición': {
            placeholder: 'Ej: Solicitud de copia de acta de grado promoción 2020',
            hint: 'Indica el trámite académico o documento que deseas solicitar al colegio.'
        },
        'Queja': {
            placeholder: 'Ej: Demora en los tiempos de respuesta de mi trámite',
            hint: 'Describe con claridad y respeto el motivo de tu inconformidad institucional.'
        },
        'Reclamo': {
            placeholder: 'Ej: Inconsistencia en la fecha de graduación registrada',
            hint: 'Detalla el servicio o información específica sobre la cual presentas tu reclamo.'
        },
        'Sugerencia': {
            placeholder: 'Ej: Propuesta para habilitar foro de mentorías entre egresados',
            hint: 'Comparte tu propuesta constructiva para mejorar la plataforma o los procesos.'
        }
    };

    if (guias[tipo]) {
        asuntoInput.placeholder = guias[tipo].placeholder;
        if (hintEl) hintEl.textContent = guias[tipo].hint;
    }
}

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
    const counter = document.getElementById('asuntoCharCounter');
    if (counter) counter.textContent = '0 / 120';
    const hintEl = document.getElementById('asuntoDynamicHint');
    if (hintEl) hintEl.textContent = 'Escribe un título breve y específico. Encabezará tu radicado ante el equipo institucional.';
    const asuntoInput = document.getElementById('asunto');
    if (asuntoInput) asuntoInput.placeholder = 'Ej: Consulta sobre certificado / Error en carga de imagen';
}

function enviarSolicitudPQR(e) {
    e.preventDefault();
    const form = document.getElementById('formPQR');
    const submitBtn = document.getElementById('btnSubmitPQR');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>Radicando solicitud...</span>';

    const formData = new FormData(form);

    fetch('index.php?page=pqr', {
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
            // Mostrar notificación toast
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
                successCard.className = 'pqr-alert-card pqr-success-card';
                const formCard = document.querySelector('.pqr-card');
                wrapper.insertBefore(successCard, formCard);
            }

            successCard.innerHTML = `
                <div class="pqr-alert-icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="pqr-alert-body">
                    <strong class="pqr-alert-headline">¡Solicitud radicada con éxito!</strong>
                    <p class="pqr-alert-meta">
                        Número de radicado oficial: <strong class="pqr-radicado-badge">${data.radicado}</strong>
                    </p>
                    <span class="pqr-alert-subtext">Nuestro equipo institucional revisará tu solicitud y te enviará respuesta formal al correo registrado.</span>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
