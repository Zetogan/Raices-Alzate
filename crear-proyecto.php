<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Crear Proyecto (Figma: 09 - Crear proyecto - Desktop y Mobile)
 * Arquitectura: Frontend Puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.1
 */

ob_start();

if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si no hay sesión activa (tanto por $_SESSION['user'] como $_SESSION['usuario'])
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// 1. Definición obligatoria de variables requeridas por la arquitectura
$seccion_activa = 'proyectos';
$titulo_seccion = 'Crear proyecto';
$titulo_pagina = 'Crear proyecto — Raíces Alzate';

$currentPage = 'proyectos';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// 2. Extracción de identidad del usuario de sesión

$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userId = $user['id'] ?? $_SESSION['user_id'] ?? null;
$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userEmail = trim($user['email'] ?? 'santiago@ejemplo.com');

$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

// 3. Manejo de formulario puramente en frontend (CERO bases de datos, CERO SQL)
$mensajeExito = '';
$mensajeError = '';
$formData = [
    'nombre_proyecto'      => '',
    'categoria'            => '',
    'descripcion_proyecto' => '',
    'enlace_proyecto'      => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['nombre_proyecto']      = trim($_POST['nombre_proyecto'] ?? '');
    $formData['categoria']            = trim($_POST['categoria'] ?? '');
    $formData['descripcion_proyecto'] = trim($_POST['descripcion_proyecto'] ?? '');
    $formData['enlace_proyecto']      = trim($_POST['enlace_proyecto'] ?? '');

    // Validación básica de campos obligatorios
    if (empty($formData['nombre_proyecto']) || empty($formData['categoria']) || empty($formData['descripcion_proyecto'])) {
        $mensajeError = 'Por favor completa todos los campos obligatorios marcados con (*).';
    } else {
        $mensajeExito = '¡Proyecto publicado exitosamente con la comunidad de egresados!';
        // Limpiar campos del formulario tras envío exitoso
        $formData = [
            'nombre_proyecto'      => '',
            'categoria'            => '',
            'descripcion_proyecto' => '',
            'enlace_proyecto'      => ''
        ];
    }
}

// 4. Hojas de estilo y scripts modulares
$extraCss = ['dashboard.css', 'crear-proyecto.css'];
$extraJs = ['assets/js/crear-proyecto.js'];

// Inclusión del encabezado global compartido
require_once __DIR__ . '/includes/header.php';
?>
<script>
    // Asignación de clases en body para el entorno privado del Dashboard
    document.body.classList.add('dashboard-body-page', 'proyectos-page', 'crear-proyecto-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Proyectos" activo en rojo institucional #D32F2F) -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar con título dinámico "Crear proyecto" e identidad de usuario) -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <!-- Contenido Central: Fluye de manera natural debajo de la Topbar sin solapamiento -->
        <main class="dashboard-content content-area crear-proyecto-content-wrapper" id="crearProyectoMainContent" tabindex="-1">
            
            <div class="crear-proyecto-container">
                
                <!-- Notificación Toast o Alerta si hubo éxito o error -->
                <?php if (!empty($mensajeExito)): ?>
                <div class="alert-status alert-success" id="alertSuccess" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="alert-content">
                        <strong>¡Publicación exitosa!</strong>
                        <span><?php echo htmlspecialchars($mensajeExito); ?></span>
                    </div>
                    <a href="proyectos.php?view=panel" class="alert-link">Ver catálogo de proyectos &rarr;</a>
                </div>
                <?php endif; ?>

                <?php if (!empty($mensajeError)): ?>
                <div class="alert-status alert-error" id="alertError" role="alert">
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

                <!-- Tarjeta Principal del Formulario (#FFFFFF, borde #E0E0E0, radio 16px, padding generoso) -->
                <section class="crear-proyecto-card form-card card-container" aria-label="Formulario para publicar un nuevo proyecto">
                    
                    <!-- Encabezado DENTRO de la tarjeta blanca según Figma ("09 - Crear proyecto - Desktop") -->
                    <div class="crear-proyecto-card-header card-header form-header">
                        <h2 class="card-title">Publicar un proyecto</h2>
                        <p class="card-subtitle">Comparte un proyecto con la comunidad de egresados.</p>
                    </div>

                    <!-- Formulario de Creación de Proyecto -->
                    <form action="crear-proyecto.php" method="POST" enctype="multipart/form-data" class="crear-proyecto-form" id="formCrearProyecto" novalidate>
                        
                        <div class="form-grid">
                            
                            <!-- Fila 1 - Columna 1: Nombre del proyecto -->
                            <div class="form-group">
                                <label for="nombre_proyecto" class="form-label">
                                    Nombre del proyecto <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="nombre_proyecto" 
                                    name="nombre_proyecto" 
                                    class="form-input" 
                                    placeholder="Ingresa el nombre del proyecto" 
                                    value="<?php echo htmlspecialchars($formData['nombre_proyecto']); ?>" 
                                    required 
                                    aria-required="true"
                                    autocomplete="off"
                                >
                                <span class="field-error-msg" id="errorNombreProyecto" aria-live="polite"></span>
                            </div>

                            <!-- Fila 1 - Columna 2: Categoría -->
                            <div class="form-group">
                                <label for="categoria" class="form-label">
                                    Categoría <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <select id="categoria" name="categoria" class="form-select" required aria-required="true">
                                        <option value="" disabled <?php echo empty($formData['categoria']) ? 'selected' : ''; ?>>Selecciona una categoría</option>
                                        <option value="Tecnología e Innovación" <?php echo ($formData['categoria'] === 'Tecnología e Innovación') ? 'selected' : ''; ?>>Tecnología e Innovación</option>
                                        <option value="Emprendimiento y Negocios" <?php echo ($formData['categoria'] === 'Emprendimiento y Negocios') ? 'selected' : ''; ?>>Emprendimiento y Negocios</option>
                                        <option value="Comunidad e Impacto Social" <?php echo ($formData['categoria'] === 'Comunidad e Impacto Social') ? 'selected' : ''; ?>>Comunidad e Impacto Social</option>
                                        <option value="Educación y Pedagogía" <?php echo ($formData['categoria'] === 'Educación y Pedagogía') ? 'selected' : ''; ?>>Educación y Pedagogía</option>
                                        <option value="Arte, Diseño y Creatividad" <?php echo ($formData['categoria'] === 'Arte, Diseño y Creatividad') ? 'selected' : ''; ?>>Arte, Diseño y Creatividad</option>
                                        <option value="Ciencia y Medio Ambiente" <?php echo ($formData['categoria'] === 'Ciencia y Medio Ambiente') ? 'selected' : ''; ?>>Ciencia y Medio Ambiente</option>
                                        <option value="Salud y Bienestar" <?php echo ($formData['categoria'] === 'Salud y Bienestar') ? 'selected' : ''; ?>>Salud y Bienestar</option>
                                    </select>
                                    <span class="select-chevron-icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <span class="field-error-msg" id="errorCategoria" aria-live="polite"></span>
                            </div>

                            <!-- Fila 2 - Ancho completo: Descripción del proyecto -->
                            <div class="form-group form-group-full">
                                <label for="descripcion_proyecto" class="form-label">
                                    Descripción del proyecto <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <textarea 
                                    id="descripcion_proyecto" 
                                    name="descripcion_proyecto" 
                                    class="form-textarea" 
                                    rows="5" 
                                    placeholder="Describe brevemente tu proyecto..." 
                                    required 
                                    aria-required="true"
                                ><?php echo htmlspecialchars($formData['descripcion_proyecto']); ?></textarea>
                                <span class="field-error-msg" id="errorDescripcionProyecto" aria-live="polite"></span>
                            </div>

                            <!-- Fila 3 - Columna 1: Enlace del proyecto -->
                            <div class="form-group">
                                <label for="enlace_proyecto" class="form-label">
                                    Enlace del proyecto
                                </label>
                                <input 
                                    type="url" 
                                    id="enlace_proyecto" 
                                    name="enlace_proyecto" 
                                    class="form-input" 
                                    placeholder="https://ejemplo.com" 
                                    value="<?php echo htmlspecialchars($formData['enlace_proyecto']); ?>"
                                    autocomplete="off"
                                >
                                <span class="field-error-msg" id="errorEnlaceProyecto" aria-live="polite"></span>
                            </div>

                            <!-- Fila 3 - Columna 2: Imagen del proyecto (Dropzone con borde 1px sólido #E0E0E0) -->
                            <div class="form-group">
                                <label class="form-label" id="labelImagenProyecto">
                                    Imagen del proyecto
                                </label>
                                
                                <div class="dropzone-container" id="dropzoneImagen" role="button" tabindex="0" aria-labelledby="labelImagenProyecto" aria-describedby="dropzoneSubtext">
                                    <input 
                                        type="file" 
                                        id="imagen_proyecto" 
                                        name="imagen_proyecto" 
                                        accept="image/png, image/jpeg, image/jpg, image/webp" 
                                        class="dropzone-file-input" 
                                        aria-hidden="true"
                                    >

                                    <!-- Estado Inicial (Vacío según Figma) -->
                                    <div class="dropzone-placeholder" id="dropzonePlaceholder">
                                        <span class="dropzone-action-text">Selecciona una imagen</span>
                                        <span class="dropzone-subtext" id="dropzoneSubtext">PNG, JPG o JPEG</span>
                                    </div>

                                    <!-- Estado Con Imagen Previsualizada -->
                                    <div class="dropzone-preview-wrap" id="dropzonePreviewWrap" style="display: none;">
                                        <img src="" alt="Vista previa de portada del proyecto" class="dropzone-preview-img" id="imgPreview">
                                        <div class="dropzone-preview-meta">
                                            <span class="preview-filename" id="previewFilename">nombre_archivo.jpg</span>
                                            <div class="preview-actions">
                                                <button type="button" class="btn-preview-action btn-change-image" id="btnCambiarImagen">
                                                    Cambiar imagen
                                                </button>
                                                <button type="button" class="btn-preview-action btn-remove-image" id="btnQuitarImagen" title="Eliminar imagen seleccionada">
                                                    Quitar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <span class="field-error-msg" id="errorImagenProyecto" aria-live="polite"></span>
                            </div>

                        </div>

                        <!-- Acciones al final: Alineadas estrictamente a la derecha con margin-top: 32px -->
                        <div class="form-actions-footer">
                            <a href="proyectos.php?view=panel" class="btn-cancelar btn-outline-blue btn-outline-secondary" id="btnCancelarProyecto" role="button">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-publicar-proyecto btn-primary-publish" id="btnPublicarProyecto">
                                Publicar proyecto
                            </button>
                        </div>

                    </form>

                </section>

            </div>

        </main>
    </div>
</div>

<?php
// Inclusión del pie de página global compartido
require_once __DIR__ . '/includes/footer.php';
ob_end_flush();
