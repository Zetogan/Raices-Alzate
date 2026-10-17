<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Publicar Oportunidad o Convocatoria (Exclusivo Administrador)
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
$seccion_activa = 'oportunidades';
$titulo_seccion = 'Publicar Convocatoria';
$titulo_pagina  = 'Publicar Convocatoria — ' . APP_NAME;

$currentPage    = 'oportunidades';
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
    'titulo'             => '',
    'empresa'            => '',
    'categoria'          => '',
    'modalidad'          => '',
    'ubicacion'          => '',
    'fecha_limite'       => '',
    'enlace_postulacion' => '',
    'descripcion'        => '',
    'requisitos'         => '',
    'beneficios'         => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $mensajeError = 'El token de seguridad ha expirado. Por favor, intenta de nuevo.';
    } else {
        $formData['titulo']             = trim($_POST['titulo'] ?? '');
        $formData['empresa']            = trim($_POST['empresa'] ?? '');
        $formData['categoria']          = trim($_POST['categoria'] ?? '');
        $formData['modalidad']          = trim($_POST['modalidad'] ?? '');
        $formData['ubicacion']          = trim($_POST['ubicacion'] ?? '');
        $formData['fecha_limite']       = trim($_POST['fecha_limite'] ?? '');
        $formData['enlace_postulacion'] = trim($_POST['enlace_postulacion'] ?? '');
        $formData['descripcion']        = trim($_POST['descripcion'] ?? '');
        $formData['requisitos']         = trim($_POST['requisitos'] ?? '');
        $formData['beneficios']         = trim($_POST['beneficios'] ?? '');

        if (empty($formData['titulo']) || empty($formData['empresa']) || empty($formData['categoria']) || empty($formData['modalidad']) || empty($formData['descripcion'])) {
            $mensajeError = 'Por favor completa todos los campos obligatorios marcados con (*).';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO oportunidades (
                        autor_id, titulo, empresa, publicado_por, categoria, modalidad, ubicacion,
                        descripcion, requisitos, beneficios, enlace_postulacion, fecha_limite,
                        estado, revisado_por, fecha_publicacion, creado_en, actualizado_en
                    ) VALUES (
                        :autor_id, :titulo, :empresa, :publicado_por, :categoria, :modalidad, :ubicacion,
                        :descripcion, :requisitos, :beneficios, :enlace_postulacion, :fecha_limite,
                        'publicado', :revisado_por, NOW(), NOW(), NOW()
                    )
                ");
                $stmt->execute([
                    ':autor_id'           => $userId,
                    ':titulo'             => mb_substr($formData['titulo'], 0, 180, 'UTF-8'),
                    ':empresa'            => mb_substr($formData['empresa'], 0, 150, 'UTF-8'),
                    ':publicado_por'      => 'Administración I.E. Gilberto Alzate',
                    ':categoria'          => mb_substr($formData['categoria'], 0, 40, 'UTF-8'),
                    ':modalidad'          => mb_substr($formData['modalidad'], 0, 100, 'UTF-8'),
                    ':ubicacion'          => !empty($formData['ubicacion']) ? mb_substr($formData['ubicacion'], 0, 150, 'UTF-8') : 'Medellín, Colombia',
                    ':descripcion'        => $formData['descripcion'],
                    ':requisitos'         => !empty($formData['requisitos']) ? $formData['requisitos'] : null,
                    ':beneficios'         => !empty($formData['beneficios']) ? $formData['beneficios'] : null,
                    ':enlace_postulacion' => !empty($formData['enlace_postulacion']) ? mb_substr($formData['enlace_postulacion'], 0, 255, 'UTF-8') : null,
                    ':fecha_limite'       => !empty($formData['fecha_limite']) ? $formData['fecha_limite'] : null,
                    ':revisado_por'       => $userId
                ]);

                $tituloPublicado = $formData['titulo'];
                $mensajeExito = '¡La convocatoria «' . htmlspecialchars($tituloPublicado) . '» ha sido publicada exitosamente en el portal!';
                
                $formData = [
                    'titulo'             => '',
                    'empresa'            => '',
                    'categoria'          => '',
                    'modalidad'          => '',
                    'ubicacion'          => '',
                    'fecha_limite'       => '',
                    'enlace_postulacion' => '',
                    'descripcion'        => '',
                    'requisitos'         => '',
                    'beneficios'         => ''
                ];

            } catch (PDOException $e) {
                error_log('Error insertando oportunidad en MySQL: ' . $e->getMessage());
                $mensajeError = 'Ocurrió un error al guardar la convocatoria en el sistema. Intenta de nuevo.';
            }
        }
    }
}

$extraCss = ['dashboard.css', 'crear-proyecto.css'];
require_once __DIR__ . '/../includes/header.php';
?>
<script>
    document.body.classList.add('dashboard-body-page', 'oportunidades-page', 'crear-oportunidad-page');
</script>

<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <main class="dashboard-content content-area crear-proyecto-content-wrapper" id="crearOportunidadMainContent" tabindex="-1">
            <div class="crear-proyecto-container">
                
                <?php if (!empty($mensajeExito)): ?>
                <div class="alert-status alert-success" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="alert-content">
                        <strong>¡Convocatoria oficial publicada!</strong>
                        <span><?php echo htmlspecialchars($mensajeExito); ?></span>
                    </div>
                    <a href="index.php?page=oportunidades&view=panel" class="alert-link">Ver catálogo de oportunidades &rarr;</a>
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

                <section class="crear-proyecto-card form-card card-container" aria-label="Formulario para publicar una nueva oportunidad institucional">
                    <div class="crear-proyecto-card-header card-header form-header">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h2 class="card-title">Publicar Convocatoria u Oportunidad</h2>
                                <p class="card-subtitle">Publica ofertas laborales, becas, pasantías y convocatorias para los egresados alzatistas.</p>
                            </div>
                            <span class="type-pill" style="background-color: #EDE7F6; color: #6A1B9A; font-weight: 700; padding: 6px 14px; border-radius: 20px; font-size: 0.82rem;">
                                Rol: Administrador
                            </span>
                        </div>
                    </div>

                    <form action="index.php?page=crear-oportunidad" method="POST" class="crear-proyecto-form" id="formCrearOportunidad">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
                        
                        <div class="form-grid">
                            
                            <!-- Titulo -->
                            <div class="form-group">
                                <label for="titulo" class="form-label">
                                    Título de la convocatoria <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="titulo" 
                                    name="titulo" 
                                    class="form-input" 
                                    placeholder="Ej: Convocatoria Desarrollador Web Junior" 
                                    value="<?php echo htmlspecialchars($formData['titulo']); ?>" 
                                    required 
                                    autocomplete="off"
                                >
                            </div>

                            <!-- Empresa / Organización -->
                            <div class="form-group">
                                <label for="empresa" class="form-label">
                                    Empresa u Organización <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="empresa" 
                                    name="empresa" 
                                    class="form-input" 
                                    placeholder="Ej: Alcaldía de Medellín / Empresa Aliada" 
                                    value="<?php echo htmlspecialchars($formData['empresa']); ?>" 
                                    required 
                                    autocomplete="off"
                                >
                            </div>

                            <!-- Categoría -->
                            <div class="form-group">
                                <label for="categoria" class="form-label">
                                    Tipo / Categoría <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <select id="categoria" name="categoria" class="form-select" required>
                                        <option value="" disabled <?php echo empty($formData['categoria']) ? 'selected' : ''; ?>>Selecciona un tipo</option>
                                        <option value="Laboral" <?php echo ($formData['categoria'] === 'Laboral') ? 'selected' : ''; ?>>Laboral / Empleo</option>
                                        <option value="Becas" <?php echo ($formData['categoria'] === 'Becas') ? 'selected' : ''; ?>>Becas y Estudios</option>
                                        <option value="Pasantías" <?php echo ($formData['categoria'] === 'Pasantías') ? 'selected' : ''; ?>>Pasantías / Prácticas</option>
                                        <option value="Convocatoria" <?php echo ($formData['categoria'] === 'Convocatoria') ? 'selected' : ''; ?>>Convocatoria Pública</option>
                                        <option value="Voluntariado" <?php echo ($formData['categoria'] === 'Voluntariado') ? 'selected' : ''; ?>>Voluntariado</option>
                                    </select>
                                    <span class="select-chevron-icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <!-- Modalidad -->
                            <div class="form-group">
                                <label for="modalidad" class="form-label">
                                    Modalidad de trabajo <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <select id="modalidad" name="modalidad" class="form-select" required>
                                        <option value="" disabled <?php echo empty($formData['modalidad']) ? 'selected' : ''; ?>>Selecciona modalidad</option>
                                        <option value="Presencial" <?php echo ($formData['modalidad'] === 'Presencial') ? 'selected' : ''; ?>>Presencial</option>
                                        <option value="Híbrido" <?php echo ($formData['modalidad'] === 'Híbrido') ? 'selected' : ''; ?>>Híbrido</option>
                                        <option value="Remoto" <?php echo ($formData['modalidad'] === 'Remoto') ? 'selected' : ''; ?>>100% Remoto</option>
                                    </select>
                                    <span class="select-chevron-icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="form-group">
                                <label for="ubicacion" class="form-label">
                                    Ubicación / Ciudad
                                </label>
                                <input 
                                    type="text" 
                                    id="ubicacion" 
                                    name="ubicacion" 
                                    class="form-input" 
                                    placeholder="Ej: Medellín, Antioquia" 
                                    value="<?php echo htmlspecialchars($formData['ubicacion']); ?>" 
                                    autocomplete="off"
                                >
                            </div>

                            <!-- Fecha Límite -->
                            <div class="form-group">
                                <label for="fecha_limite" class="form-label">
                                    Fecha límite de postulación
                                </label>
                                <input 
                                    type="date" 
                                    id="fecha_limite" 
                                    name="fecha_limite" 
                                    class="form-input" 
                                    value="<?php echo htmlspecialchars($formData['fecha_limite']); ?>"
                                >
                            </div>

                            <!-- Enlace de postulación -->
                            <div class="form-group form-group-full">
                                <label for="enlace_postulacion" class="form-label">
                                    Enlace de postulación o contacto directo
                                </label>
                                <input 
                                    type="url" 
                                    id="enlace_postulacion" 
                                    name="enlace_postulacion" 
                                    class="form-input" 
                                    placeholder="https://ejemplo.com/postularse o mailto:contacto@empresa.com" 
                                    value="<?php echo htmlspecialchars($formData['enlace_postulacion']); ?>" 
                                    autocomplete="off"
                                >
                            </div>

                            <!-- Descripción -->
                            <div class="form-group form-group-full">
                                <label for="descripcion" class="form-label">
                                    Descripción general de la oportunidad <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <textarea 
                                    id="descripcion" 
                                    name="descripcion" 
                                    class="form-textarea" 
                                    rows="5" 
                                    placeholder="Detalla el alcance, objetivos y funciones del rol o la beca..." 
                                    required
                                ><?php echo htmlspecialchars($formData['descripcion']); ?></textarea>
                            </div>

                            <!-- Requisitos -->
                            <div class="form-group">
                                <label for="requisitos" class="form-label">
                                    Requisitos del perfil
                                </label>
                                <textarea 
                                    id="requisitos" 
                                    name="requisitos" 
                                    class="form-textarea" 
                                    rows="4" 
                                    placeholder="Ej: Estudiante o egresado técnico, manejo de herramientas digitales..."
                                ><?php echo htmlspecialchars($formData['requisitos']); ?></textarea>
                            </div>

                            <!-- Beneficios -->
                            <div class="form-group">
                                <label for="beneficios" class="form-label">
                                    Beneficios ofrecidos
                                </label>
                                <textarea 
                                    id="beneficios" 
                                    name="beneficios" 
                                    class="form-textarea" 
                                    rows="4" 
                                    placeholder="Ej: Apoyo de sostenimiento, afiliación a EPS/ARL, horarios flexibles..."
                                ><?php echo htmlspecialchars($formData['beneficios']); ?></textarea>
                            </div>

                        </div>

                        <div class="form-actions-footer">
                            <a href="index.php?page=administracion" class="btn-cancelar btn-outline-secondary" role="button">
                                Volver a Administración
                            </a>
                            <button type="submit" class="btn-publicar-proyecto btn-primary-publish" id="btnPublicarOportunidad">
                                Publicar convocatoria oficial
                            </button>
                        </div>

                    </form>

                </section>

            </div>
        </main>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
ob_end_flush();
