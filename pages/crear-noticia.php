<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Publicar Noticia Institucional (Exclusivo Administrador)
 */

ob_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// 1. Verificación estricta: Solo cuenta de administración
if (!isAuthenticated()) {
    header('Location: index.php?page=login');
    exit;
}
if (!hasRole('administrador')) {
    header('Location: index.php?page=dashboard');
    exit;
}

$currentUser = currentUser();
$userId      = (int)($currentUser['id'] ?? 0);

if ($userId <= 0 || empty($pdo)) {
    header('Location: index.php?page=login');
    exit;
}

// Variables de interfaz y navegación
$seccion_activa = 'noticias';
$titulo_seccion = 'Publicar Noticia';
$titulo_pagina  = 'Publicar Noticia — ' . APP_NAME;

$currentPage    = 'noticias';
$pageTitle      = $titulo_pagina;
$topbarTitle    = $titulo_seccion;

// Identidad de usuario para la cabecera
$userName  = trim($currentUser['nombre'] ?? 'Administrador');
$userRole  = trim($currentUser['rol'] ?? 'Administrador');
$userEmail = trim($currentUser['email'] ?? 'admin@alzate.edu.co');

$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'A';

// Procesamiento del formulario
$mensajeExito = '';
$mensajeError = '';
$formData = [
    'titulo'      => '',
    'categoria'   => '',
    'descripcion' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $mensajeError = 'El token de seguridad ha expirado. Por favor, intenta de nuevo.';
    } else {
        $formData['titulo']      = trim($_POST['titulo'] ?? '');
        $formData['categoria']   = trim($_POST['categoria'] ?? '');
        $formData['descripcion'] = trim($_POST['descripcion'] ?? '');

        if (empty($formData['titulo']) || empty($formData['categoria']) || empty($formData['descripcion'])) {
            $mensajeError = 'Por favor completa todos los campos obligatorios marcados con (*).';
        } else {
            // Manejo de portada de la noticia
            $portadaDbPath = null;
            if (isset($_FILES['imagen_noticia']) && $_FILES['imagen_noticia']['error'] === UPLOAD_ERR_OK) {
                $fileTmp  = $_FILES['imagen_noticia']['tmp_name'];
                $fileSize = (int)$_FILES['imagen_noticia']['size'];

                if ($fileSize > 0 && $fileSize <= 5 * 1024 * 1024) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $fileTmp);
                    finfo_close($finfo);

                    $allowedMimes = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/webp' => 'webp'
                    ];

                    if (isset($allowedMimes[$mimeType])) {
                        $ext = $allowedMimes[$mimeType];
                        $uploadDir = __DIR__ . '/../uploads/noticias';
                        if (!is_dir($uploadDir)) {
                            @mkdir($uploadDir, 0755, true);
                        }
                        $fileName = 'noticia_' . $userId . '_' . time() . '.' . $ext;
                        $destPath = $uploadDir . '/' . $fileName;

                        if (move_uploaded_file($fileTmp, $destPath)) {
                            $portadaDbPath = 'uploads/noticias/' . $fileName;
                        }
                    }
                }
            }

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO noticias (
                        autor_id, titulo, descripcion, categoria, portada, estado, revisado_por, fecha_publicacion, creado_en, actualizado_en
                    ) VALUES (
                        :autor_id, :titulo, :descripcion, :categoria, :portada, 'publicado', :revisado_por, NOW(), NOW(), NOW()
                    )
                ");
                $stmt->execute([
                    ':autor_id'     => $userId,
                    ':titulo'       => mb_substr($formData['titulo'], 0, 180, 'UTF-8'),
                    ':descripcion'  => $formData['descripcion'],
                    ':categoria'    => mb_substr($formData['categoria'], 0, 60, 'UTF-8'),
                    ':portada'      => $portadaDbPath,
                    ':revisado_por' => $userId
                ]);

                $tituloPublicado = $formData['titulo'];
                $mensajeExito = '¡La noticia «' . htmlspecialchars($tituloPublicado) . '» ha sido publicada exitosamente en el portal!';
                
                $formData = [
                    'titulo'      => '',
                    'categoria'   => '',
                    'descripcion' => ''
                ];

            } catch (PDOException $e) {
                error_log('Error insertando noticia en MySQL: ' . $e->getMessage());
                $mensajeError = 'Ocurrió un error al guardar la noticia en el sistema. Intenta de nuevo.';
            }
        }
    }
}

// Estilos
$extraCss = ['dashboard.css', 'crear-proyecto.css'];
require_once __DIR__ . '/../includes/header.php';
?>
<script>
    document.body.classList.add('dashboard-body-page', 'noticias-page', 'crear-noticia-page');
</script>

<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="dashboard-content content-area crear-proyecto-content-wrapper" id="crearNoticiaMainContent" tabindex="-1">
            <div class="crear-proyecto-container">
                
                <?php if (!empty($mensajeExito)): ?>
                <div class="alert-status alert-success" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="alert-content">
                        <strong>¡Publicación oficial exitosa!</strong>
                        <span><?php echo htmlspecialchars($mensajeExito); ?></span>
                    </div>
                    <a href="index.php?page=noticias&view=panel" class="alert-link">Ver catálogo de noticias &rarr;</a>
                </div>
                <?php endif; ?>

                <?php if (!empty($mensajeError)): ?>
                <div class="alert-status alert-error" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="alert-content">
                        <strong>Atención:</strong>
                        <span><?php echo htmlspecialchars($mensajeError); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <section class="crear-proyecto-card form-card card-container" aria-label="Formulario para publicar una nueva noticia institucional">
                    <div class="crear-proyecto-card-header card-header form-header">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h2 class="card-title">Publicar Noticia Institucional</h2>
                                <p class="card-subtitle">Publicación oficial visible para la comunidad de egresados y visitantes del portal.</p>
                            </div>
                            <span class="type-pill" style="background-color: #E3F2FD; color: #1565C0; font-weight: 700; padding: 6px 14px; border-radius: 20px; font-size: 0.82rem;">
                                Rol: Administrador
                            </span>
                        </div>
                    </div>

                    <form action="index.php?page=crear-noticia" method="POST" enctype="multipart/form-data" class="crear-proyecto-form" id="formCrearNoticia">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
                        
                        <div class="form-grid">
                            
                            <!-- Titulo -->
                            <div class="form-group form-group-full">
                                <label for="titulo" class="form-label">
                                    Título de la noticia <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="titulo" 
                                    name="titulo" 
                                    class="form-input" 
                                    placeholder="Ej: Gran Jornada de Reencuentro de Promociones Alzatistas 2026" 
                                    value="<?php echo htmlspecialchars($formData['titulo']); ?>" 
                                    required 
                                    autocomplete="off"
                                >
                            </div>

                            <!-- Categoría -->
                            <div class="form-group">
                                <label for="categoria" class="form-label">
                                    Categoría <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <select id="categoria" name="categoria" class="form-select" required>
                                        <option value="" disabled <?php echo empty($formData['categoria']) ? 'selected' : ''; ?>>Selecciona una categoría</option>
                                        <option value="Institucional" <?php echo ($formData['categoria'] === 'Institucional') ? 'selected' : ''; ?>>Institucional</option>
                                        <option value="Eventos" <?php echo ($formData['categoria'] === 'Eventos') ? 'selected' : ''; ?>>Eventos</option>
                                        <option value="Logros y Reconocimientos" <?php echo ($formData['categoria'] === 'Logros y Reconocimientos') ? 'selected' : ''; ?>>Logros y Reconocimientos</option>
                                        <option value="Académica" <?php echo ($formData['categoria'] === 'Académica') ? 'selected' : ''; ?>>Académica</option>
                                        <option value="Infraestructura" <?php echo ($formData['categoria'] === 'Infraestructura') ? 'selected' : ''; ?>>Infraestructura</option>
                                        <option value="Comunidad" <?php echo ($formData['categoria'] === 'Comunidad') ? 'selected' : ''; ?>>Comunidad</option>
                                    </select>
                                    <span class="select-chevron-icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <!-- Portada -->
                            <div class="form-group">
                                <label class="form-label" id="labelImagenNoticia">
                                    Imagen / Portada de la noticia
                                </label>
                                <div class="dropzone-container" id="dropzoneImagen" role="button" tabindex="0">
                                    <input 
                                        type="file" 
                                        id="imagen_noticia" 
                                        name="imagen_noticia" 
                                        accept="image/png, image/jpeg, image/jpg, image/webp" 
                                        class="dropzone-file-input" 
                                        style="display: none;"
                                    >
                                    <div class="dropzone-placeholder" id="dropzonePlaceholder">
                                        <span class="dropzone-action-text">Selecciona una imagen de portada</span>
                                        <span class="dropzone-subtext">PNG, JPG o WEBP (máx. 5MB)</span>
                                    </div>
                                    <div class="dropzone-preview-wrap" id="dropzonePreviewWrap" style="display: none;">
                                        <img src="" alt="Vista previa" class="dropzone-preview-img" id="imgPreview">
                                        <div class="dropzone-preview-meta">
                                            <span class="preview-filename" id="previewFilename">portada.jpg</span>
                                            <div class="preview-actions">
                                                <button type="button" class="btn-preview-action btn-remove-image" id="btnQuitarImagen">Quitar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenido / Descripción -->
                            <div class="form-group form-group-full">
                                <label for="descripcion" class="form-label">
                                    Cuerpo / Contenido de la noticia <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <textarea 
                                    id="descripcion" 
                                    name="descripcion" 
                                    class="form-textarea" 
                                    rows="8" 
                                    placeholder="Redacta los detalles completos de la noticia institucional..." 
                                    required
                                ><?php echo htmlspecialchars($formData['descripcion']); ?></textarea>
                            </div>

                        </div>

                        <div class="form-actions-footer">
                            <a href="index.php?page=administracion" class="btn-cancelar btn-outline-secondary" role="button">
                                Volver a Administración
                            </a>
                            <button type="submit" class="btn-publicar-proyecto btn-primary-publish" id="btnPublicarNoticia">
                                Publicar noticia oficial
                            </button>
                        </div>

                    </form>

                </section>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzoneImagen');
    const fileInput = document.getElementById('imagen_noticia');
    const placeholder = document.getElementById('dropzonePlaceholder');
    const previewWrap = document.getElementById('dropzonePreviewWrap');
    const imgPreview = document.getElementById('imgPreview');
    const filenameLabel = document.getElementById('previewFilename');
    const btnQuitar = document.getElementById('btnQuitarImagen');

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', function(e) {
            if (e.target !== btnQuitar) {
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                filenameLabel.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    placeholder.style.display = 'none';
                    previewWrap.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        });

        if (btnQuitar) {
            btnQuitar.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                imgPreview.src = '';
                previewWrap.style.display = 'none';
                placeholder.style.display = 'block';
            });
        }
    }
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
ob_end_flush();
