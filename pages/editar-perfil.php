<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Editar Perfil (Figma: 04 / 11 - Editar perfil - Desktop y Mobile)
 * Versión: 0.1.2
 */

ob_start();

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

// 1. Verificación estricta de sesión de usuario autenticado
if (!isAuthenticated()) {
    header('Location: index.php?page=login');
    exit;
}

if (function_exists('hasRole') && hasRole('administrador')) {
    header('Location: index.php?page=administracion');
    exit;
}

$currentUser = currentUser();
$userId      = (int)($currentUser['id'] ?? 0);

if ($userId <= 0 || empty($pdo)) {
    header('Location: index.php?page=login');
    exit;
}

// 2. Procesamiento del guardado de perfil (POST)
$mensajeError = '';
$mensajeExito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A. Verificación de token CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $mensajeError = 'El token de seguridad ha expirado. Por favor, intenta de nuevo.';
    } else {
        // B. Captura y saneamiento de datos enviados
        $nuevoNombre        = trim($_POST['nombre'] ?? $_POST['nombre_completo'] ?? '');
        $bio                = trim($_POST['bio'] ?? $_POST['biografia'] ?? '');
        $genero             = trim($_POST['genero'] ?? '');
        $fechaNacimiento    = !empty($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : null;
        $ciudad             = trim($_POST['ciudad'] ?? '');
        $direccion          = trim($_POST['direccion'] ?? $_POST['barrio'] ?? '');
        $telefono           = trim($_POST['telefono'] ?? '');
        $emailSecundario    = trim($_POST['email_secundario'] ?? '');
        $tituloInstitucional= trim($_POST['titulo_institucional'] ?? $_POST['titulo_obtenido'] ?? $_POST['titulo'] ?? '');
        $anioGraduacionRaw  = trim($_POST['anio_graduacion'] ?? $_POST['promocion'] ?? '');
        $anioGraduacion     = ($anioGraduacionRaw !== '' && is_numeric($anioGraduacionRaw)) ? (int)$anioGraduacionRaw : null;
        $programaSuperior   = trim($_POST['programa_superior'] ?? '');
        $institucionSuperior= trim($_POST['institucion_superior'] ?? '');
        $ocupacion          = trim($_POST['ocupacion'] ?? $_POST['cargo_actual'] ?? $_POST['cargo'] ?? '');
        $empresa            = trim($_POST['empresa'] ?? $_POST['empresa_actual'] ?? '');
        $areaEspecialidad   = trim($_POST['area_especialidad'] ?? '');
        $aniosExpRaw        = trim($_POST['anios_experiencia'] ?? '');
        $aniosExperiencia   = ($aniosExpRaw !== '' && is_numeric($aniosExpRaw)) ? (int)$aniosExpRaw : null;
        $linkedin           = trim($_POST['linkedin'] ?? '');
        $github             = trim($_POST['github'] ?? '');
        $sitioWeb           = trim($_POST['web'] ?? $_POST['sitio_web'] ?? '');
        $redAdicional       = trim($_POST['red_adicional'] ?? '');
        $habilidadesRaw     = trim($_POST['habilidades'] ?? '');

        // Validación de año
        if ($anioGraduacion !== null && ($anioGraduacion < 1950 || $anioGraduacion > 2100)) {
            $anioGraduacion = null;
        }

        // Validación de fecha de nacimiento simple
        if ($fechaNacimiento !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNacimiento)) {
            $fechaNacimiento = null;
        }

        try {
            $pdo->beginTransaction();

            // C. Actualizar nombre en tabla usuarios para el usuario autenticado
            if (!empty($nuevoNombre)) {
                $stmtUpdateUser = $pdo->prepare("
                    UPDATE usuarios 
                    SET nombre_completo = :nombre, actualizado_en = NOW() 
                    WHERE id = :uid
                ");
                $stmtUpdateUser->execute([
                    ':nombre' => mb_substr($nuevoNombre, 0, 150, 'UTF-8'),
                    ':uid'    => $userId
                ]);

                // Actualizar sesión canónica
                if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
                    $_SESSION['user']['nombre'] = $nuevoNombre;
                    $_SESSION['user']['nombre_completo'] = $nuevoNombre;
                }
                if (isset($_SESSION['usuario']) && is_array($_SESSION['usuario'])) {
                    $_SESSION['usuario']['nombre'] = $nuevoNombre;
                    $_SESSION['usuario']['nombre_completo'] = $nuevoNombre;
                }
                $_SESSION['nombre'] = $nuevoNombre;
            }

            // D. Comprobar si ya existe registro en perfiles_egresados
            $stmtCheckPerfil = $pdo->prepare("SELECT id, foto_perfil FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
            $stmtCheckPerfil->execute([':uid' => $userId]);
            $perfilExistente = $stmtCheckPerfil->fetch(PDO::FETCH_ASSOC);
            $perfilId = !empty($perfilExistente['id']) ? (int)$perfilExistente['id'] : null;
            $fotoActual = $perfilExistente['foto_perfil'] ?? null;

            // Procesamiento seguro de fotografía de perfil si se envió un archivo
            $fotoPerfilFinal = $fotoActual;
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $fileTmp  = $_FILES['foto_perfil']['tmp_name'];
                $fileSize = (int)$_FILES['foto_perfil']['size'];

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
                        $uploadDir = __DIR__ . '/../uploads/perfiles';
                        if (!is_dir($uploadDir)) {
                            @mkdir($uploadDir, 0755, true);
                        }
                        $safeFileName = 'perfil_' . $userId . '_' . time() . '.' . $ext;
                        $destPath = $uploadDir . '/' . $safeFileName;
                        $relDbPath = 'uploads/perfiles/' . $safeFileName;

                        if (move_uploaded_file($fileTmp, $destPath)) {
                            if (!empty($fotoActual) && file_exists(__DIR__ . '/' . $fotoActual) && strpos($fotoActual, 'uploads/perfiles/') === 0) {
                                @unlink(__DIR__ . '/' . $fotoActual);
                            }
                            $fotoPerfilFinal = $relDbPath;
                        }
                    }
                }
            }

            if ($perfilId) {
                // Actualizar perfil existente
                $stmtSavePerfil = $pdo->prepare("
                    UPDATE perfiles_egresados SET
                        bio                  = :bio,
                        genero               = :genero,
                        fecha_nacimiento     = :fecha_nacimiento,
                        ciudad               = :ciudad,
                        direccion            = :direccion,
                        telefono             = :telefono,
                        email_secundario     = :email_secundario,
                        titulo_institucional = :titulo_institucional,
                        anio_graduacion      = :anio_graduacion,
                        programa_superior    = :programa_superior,
                        institucion_superior = :institucion_superior,
                        ocupacion            = :ocupacion,
                        empresa              = :empresa,
                        area_especialidad    = :area_especialidad,
                        anios_experiencia    = :anios_experiencia,
                        linkedin             = :linkedin,
                        github               = :github,
                        sitio_web            = :sitio_web,
                        red_adicional        = :red_adicional,
                        foto_perfil          = :foto_perfil,
                        perfil_publico       = :perfil_publico,
                        actualizado_en       = NOW()
                    WHERE id = :id AND usuario_id = :uid
                ");
                $stmtSavePerfil->execute([
                    ':bio'                  => ($bio !== '') ? $bio : null,
                    ':genero'               => ($genero !== '') ? mb_substr($genero, 0, 30, 'UTF-8') : null,
                    ':fecha_nacimiento'     => $fechaNacimiento,
                    ':ciudad'               => ($ciudad !== '') ? mb_substr($ciudad, 0, 100, 'UTF-8') : null,
                    ':direccion'            => ($direccion !== '') ? mb_substr($direccion, 0, 150, 'UTF-8') : null,
                    ':telefono'             => ($telefono !== '') ? mb_substr($telefono, 0, 25, 'UTF-8') : null,
                    ':email_secundario'     => ($emailSecundario !== '') ? mb_substr($emailSecundario, 0, 255, 'UTF-8') : null,
                    ':titulo_institucional' => ($tituloInstitucional !== '') ? mb_substr($tituloInstitucional, 0, 150, 'UTF-8') : null,
                    ':anio_graduacion'      => $anioGraduacion,
                    ':programa_superior'    => ($programaSuperior !== '') ? mb_substr($programaSuperior, 0, 150, 'UTF-8') : null,
                    ':institucion_superior' => ($institucionSuperior !== '') ? mb_substr($institucionSuperior, 0, 150, 'UTF-8') : null,
                    ':ocupacion'            => ($ocupacion !== '') ? mb_substr($ocupacion, 0, 120, 'UTF-8') : null,
                    ':empresa'              => ($empresa !== '') ? mb_substr($empresa, 0, 120, 'UTF-8') : null,
                    ':area_especialidad'    => ($areaEspecialidad !== '') ? mb_substr($areaEspecialidad, 0, 120, 'UTF-8') : null,
                    ':anios_experiencia'    => $aniosExperiencia,
                    ':linkedin'             => ($linkedin !== '') ? mb_substr($linkedin, 0, 255, 'UTF-8') : null,
                    ':github'               => ($github !== '') ? mb_substr($github, 0, 255, 'UTF-8') : null,
                    ':sitio_web'            => ($sitioWeb !== '') ? mb_substr($sitioWeb, 0, 255, 'UTF-8') : null,
                    ':red_adicional'        => ($redAdicional !== '') ? mb_substr($redAdicional, 0, 255, 'UTF-8') : null,
                    ':foto_perfil'          => $fotoPerfilFinal,
                    ':perfil_publico'       => isset($_POST['perfil_publico']) ? 1 : 0,
                    ':id'                   => (int)$perfilId,
                    ':uid'                  => $userId
                ]);
            } else {
                // Insertar nuevo perfil exclusivo para este usuario
                $stmtInsertPerfil = $pdo->prepare("
                    INSERT INTO perfiles_egresados (
                        usuario_id, bio, genero, fecha_nacimiento, ciudad, direccion, telefono,
                        email_secundario, titulo_institucional, anio_graduacion, programa_superior,
                        institucion_superior, ocupacion, empresa, area_especialidad, anios_experiencia,
                        linkedin, github, sitio_web, red_adicional, foto_perfil, perfil_publico, creado_en, actualizado_en
                    ) VALUES (
                        :uid, :bio, :genero, :fecha_nacimiento, :ciudad, :direccion, :telefono,
                        :email_secundario, :titulo_institucional, :anio_graduacion, :programa_superior,
                        :institucion_superior, :ocupacion, :empresa, :area_especialidad, :anios_experiencia,
                        :linkedin, :github, :sitio_web, :red_adicional, :foto_perfil, :perfil_publico, NOW(), NOW()
                    )
                ");
                $stmtInsertPerfil->execute([
                    ':uid'                  => $userId,
                    ':bio'                  => ($bio !== '') ? $bio : null,
                    ':genero'               => ($genero !== '') ? mb_substr($genero, 0, 30, 'UTF-8') : null,
                    ':fecha_nacimiento'     => $fechaNacimiento,
                    ':ciudad'               => ($ciudad !== '') ? mb_substr($ciudad, 0, 100, 'UTF-8') : null,
                    ':direccion'            => ($direccion !== '') ? mb_substr($direccion, 0, 150, 'UTF-8') : null,
                    ':telefono'             => ($telefono !== '') ? mb_substr($telefono, 0, 25, 'UTF-8') : null,
                    ':email_secundario'     => ($emailSecundario !== '') ? mb_substr($emailSecundario, 0, 255, 'UTF-8') : null,
                    ':titulo_institucional' => ($tituloInstitucional !== '') ? mb_substr($tituloInstitucional, 0, 150, 'UTF-8') : null,
                    ':anio_graduacion'      => $anioGraduacion,
                    ':programa_superior'    => ($programaSuperior !== '') ? mb_substr($programaSuperior, 0, 150, 'UTF-8') : null,
                    ':institucion_superior' => ($institucionSuperior !== '') ? mb_substr($institucionSuperior, 0, 150, 'UTF-8') : null,
                    ':ocupacion'            => ($ocupacion !== '') ? mb_substr($ocupacion, 0, 120, 'UTF-8') : null,
                    ':empresa'              => ($empresa !== '') ? mb_substr($empresa, 0, 120, 'UTF-8') : null,
                    ':area_especialidad'    => ($areaEspecialidad !== '') ? mb_substr($areaEspecialidad, 0, 120, 'UTF-8') : null,
                    ':anios_experiencia'    => $aniosExperiencia,
                    ':linkedin'             => ($linkedin !== '') ? mb_substr($linkedin, 0, 255, 'UTF-8') : null,
                    ':github'               => ($github !== '') ? mb_substr($github, 0, 255, 'UTF-8') : null,
                    ':sitio_web'            => ($sitioWeb !== '') ? mb_substr($sitioWeb, 0, 255, 'UTF-8') : null,
                    ':red_adicional'        => ($redAdicional !== '') ? mb_substr($redAdicional, 0, 255, 'UTF-8') : null,
                    ':foto_perfil'          => $fotoPerfilFinal,
                    ':perfil_publico'       => isset($_POST['perfil_publico']) ? 1 : 0
                ]);
                $perfilId = (int)$pdo->lastInsertId();
            }

            // E. Sincronización de habilidades
            if ($perfilId > 0) {
                $stmtDelHab = $pdo->prepare("DELETE FROM perfil_habilidades WHERE perfil_id = :pid");
                $stmtDelHab->execute([':pid' => $perfilId]);

                $skillsArray = array_filter(array_map('trim', explode(',', $habilidadesRaw)));
                if (!empty($skillsArray)) {
                    $stmtHabInsert = $pdo->prepare("INSERT IGNORE INTO habilidades (nombre) VALUES (?)");
                    $stmtHabGet    = $pdo->prepare("SELECT id FROM habilidades WHERE nombre = ? LIMIT 1");
                    $stmtRelInsert = $pdo->prepare("INSERT IGNORE INTO perfil_habilidades (perfil_id, habilidad_id) VALUES (?, ?)");

                    foreach ($skillsArray as $habItem) {
                        $habClean = mb_substr($habItem, 0, 60, 'UTF-8');
                        if (mb_strlen($habClean) > 0) {
                            $stmtHabInsert->execute([$habClean]);
                            $stmtHabGet->execute([$habClean]);
                            $hid = $stmtHabGet->fetchColumn();
                            if ($hid) {
                                $stmtRelInsert->execute([$perfilId, (int)$hid]);
                            }
                        }
                    }
                }
            }

            $pdo->commit();

            $_SESSION['perfil_flash_success'] = '¡Tu perfil ha sido actualizado correctamente!';
            header('Location: index.php?page=perfil&guardado=1');
            exit;

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Error guardando perfil de usuario ' . $userId . ': ' . $e->getMessage());
            $mensajeError = 'Ocurrió un error al guardar los cambios. Intenta nuevamente.';
        }
    }
}

// 3. Consulta de datos reales del usuario autenticado desde MySQL
try {
    $stmtUser = $pdo->prepare("
        SELECT u.id, u.nombre_completo, u.email, u.rol_id, r.nombre AS rol_nombre, u.creado_en
        FROM usuarios u
        JOIN roles r ON r.id = u.rol_id
        WHERE u.id = :uid
        LIMIT 1
    ");
    $stmtUser->execute([':uid' => $userId]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        header('Location: index.php?page=login');
        exit;
    }

    $userName    = trim($userData['nombre_completo'] ?? '');
    $userEmail   = trim($userData['email'] ?? '');
    $userRole    = trim($userData['rol_nombre'] ?? 'Egresado');
    $userInitial = !empty($userName) 
        ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
        : 'U';

    // Consulta del perfil del usuario (sin datos estáticos ni de terceros)
    $stmtPerfil = $pdo->prepare("SELECT * FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
    $stmtPerfil->execute([':uid' => $userId]);
    $perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC) ?: [];

    // Consulta de habilidades reales asociadas a este perfil
    $habilidadesValue = '';
    if (!empty($perfil['id'])) {
        $stmtHab = $pdo->prepare("
            SELECT h.nombre 
            FROM habilidades h
            JOIN perfil_habilidades ph ON ph.habilidad_id = h.id
            WHERE ph.perfil_id = :pid
            ORDER BY h.nombre ASC
        ");
        $stmtHab->execute([':pid' => $perfil['id']]);
        $habs = $stmtHab->fetchAll(PDO::FETCH_COLUMN) ?: [];
        $habilidadesValue = implode(', ', $habs);
    }

    $porcentajeCompletitudInicial = calcularCompletitudPerfil($userData, $perfil, $habs ?? []);

} catch (PDOException $e) {
    error_log('Error cargando perfil: ' . $e->getMessage());
    $userName    = trim($currentUser['nombre'] ?? '');
    $userEmail   = trim($currentUser['email'] ?? '');
    $userRole    = trim($currentUser['rol'] ?? 'Egresado');
    $userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';
    $perfil      = [];
    $habilidadesValue = '';
    $porcentajeCompletitudInicial = calcularCompletitudPerfil(['nombre_completo' => $userName, 'email' => $userEmail], []);
}

// 4. Variables obligatorias de interfaz y navegación
$seccion_activa = 'perfil';
$titulo_seccion = 'Editar perfil';
$titulo_pagina  = 'Editar perfil — ' . APP_NAME;
$currentPage    = 'perfil';
$pageTitle      = $titulo_pagina;
$topbarTitle    = $titulo_seccion;

$extraCss = ['dashboard.css', 'perfil-form.css'];
$extraJs  = ['assets/js/perfil.js'];

// Inclusión del encabezado global
require_once __DIR__ . '/../includes/header.php';
?>
<script>
    document.body.classList.add('dashboard-body-page', 'editar-perfil-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar) -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <!-- Área Principal -->
        <main class="dashboard-content content-area editar-perfil-content-wrapper" id="editarPerfilMainContent" tabindex="-1">
            
            <div class="perfil-form-container">
                <!-- A. Miga de pan / Breadcrumb -->
                <nav class="perfil-form-breadcrumb" aria-label="Ruta de navegación">
                    <ol class="perfil-breadcrumb-list">
                        <li class="perfil-breadcrumb-item">
                            <a href="index.php?page=dashboard" class="perfil-breadcrumb-link">Dashboard</a>
                        </li>
                        <li class="perfil-breadcrumb-item">
                            <span class="perfil-breadcrumb-sep" aria-hidden="true">/</span>
                            <a href="index.php?page=perfil" class="perfil-breadcrumb-link">Mi perfil</a>
                        </li>
                        <li class="perfil-breadcrumb-item">
                            <span class="perfil-breadcrumb-sep" aria-hidden="true">/</span>
                            <span class="perfil-breadcrumb-current" aria-current="page">Editar perfil</span>
                        </li>
                    </ol>
                </nav>

                <?php if (!empty($mensajeError)): ?>
                    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background-color: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; font-size: 0.95rem;">
                        <?php echo htmlspecialchars($mensajeError); ?>
                    </div>
                <?php endif; ?>

                <!-- B. Cabecera de identidad del usuario actual -->
                <section class="profile-section-card perfil-header-card" aria-label="Identidad del egresado">
                    <div class="perfil-header-left">
                        <div class="perfil-avatar-circle" id="avatarPreviewCircle" aria-hidden="true">
                            <?php if (!empty($perfil['foto_perfil']) && file_exists(__DIR__ . '/' . $perfil['foto_perfil'])): ?>
                                <img src="<?php echo htmlspecialchars($perfil['foto_perfil']); ?>" alt="Foto de <?php echo htmlspecialchars($userName); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <?php echo htmlspecialchars($userInitial); ?>
                            <?php endif; ?>
                        </div>
                        <div class="perfil-header-user-info">
                            <h1 class="perfil-header-name"><?php echo htmlspecialchars($userName); ?></h1>
                            <p class="perfil-header-role"><?php echo htmlspecialchars($userRole); ?> Alzatista</p>
                        </div>
                    </div>
                    <div class="perfil-header-right">
                        <button type="button" class="btn-cambiar-foto" id="btnCambiarFoto" aria-label="Cambiar fotografía de perfil">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                            Cambiar foto
                        </button>
                        <input type="file" id="inputFotoOculta" name="foto_perfil" accept="image/png, image/jpeg, image/webp" style="display: none;" aria-hidden="true" form="formEditarPerfil">
                    </div>
                </section>

                <!-- Tarjeta de completitud dinámica en tiempo real -->
                <div class="perfil-completion-meter-card" style="background: #FFFFFF; border: 1px solid #E0E0E0; border-radius: 12px; padding: 18px 24px; margin-bottom: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 14px; font-weight: 600; color: #212121; display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Completitud del perfil
                        </span>
                        <span id="labelPorcentajeCompletitud" style="font-size: 16px; font-weight: 700; color: #D32F2F;"><?php echo (int)($porcentajeCompletitudInicial ?? 0); ?>%</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: #EEEEEE; border-radius: 4px; overflow: hidden;">
                        <div id="barPorcentajeCompletitud" style="height: 100%; background: #D32F2F; border-radius: 4px; width: <?php echo (int)($porcentajeCompletitudInicial ?? 0); ?>%; transition: width 0.3s ease;"></div>
                    </div>
                    <p style="font-size: 12px; color: #757575; margin-top: 8px; margin-bottom: 0;">El porcentaje disminuye o aumenta en tiempo real a medida que borras o completas campos de tu perfil.</p>
                </div>

                <!-- C. Formulario de Edición con datos reales del usuario -->
                <form action="index.php?page=editar-perfil" method="POST" enctype="multipart/form-data" class="perfil-edit-form" id="formEditarPerfil">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
                    
                    <!-- 1. Sobre mí -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloSobreMi">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloSobreMi">Sobre mí</h2>
                            <p class="card-subtitle perfil-card-subtitle">Escribe una breve presentación sobre tu trayectoria, intereses y metas profesionales.</p>
                        </div>
                        <div class="form-field-group">
                            <label for="textareaBio" class="form-field-label">Descripción y presentación personal</label>
                            <textarea id="textareaBio" name="bio" class="form-field-textarea" rows="4" placeholder="Cuéntale a la comunidad alzatista sobre ti..."><?php echo htmlspecialchars($perfil['bio'] ?? ''); ?></textarea>
                            <span class="form-field-helper">Máximo 500 caracteres recomendados.</span>
                        </div>
                    </section>

                    <!-- 2. Información personal -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloInfoPersonal">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloInfoPersonal">Información personal</h2>
                            <p class="card-subtitle perfil-card-subtitle">Consulta y actualiza tus datos básicos de identificación.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputNombre" class="form-field-label">Nombre completo</label>
                                <input type="text" id="inputNombre" name="nombre" class="form-field-input" value="<?php echo htmlspecialchars($userName); ?>" required>
                            </div>
                            <div class="form-field-group">
                                <label for="selectGenero" class="form-field-label">Género</label>
                                <?php $genActual = $perfil['genero'] ?? ''; ?>
                                <select id="selectGenero" name="genero" class="form-field-select">
                                    <option value="" <?php echo empty($genActual) ? 'selected' : ''; ?>>Seleccionar género...</option>
                                    <option value="Masculino" <?php echo ($genActual === 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="Femenino" <?php echo ($genActual === 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                    <option value="Otro" <?php echo ($genActual === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                    <option value="Prefiero no decir" <?php echo ($genActual === 'Prefiero no decir') ? 'selected' : ''; ?>>Prefiero no decir</option>
                                </select>
                            </div>
                            <div class="form-field-group">
                                <label for="inputNacimiento" class="form-field-label">Fecha de nacimiento</label>
                                <input type="date" id="inputNacimiento" name="fecha_nacimiento" class="form-field-input" value="<?php echo htmlspecialchars($perfil['fecha_nacimiento'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputCiudad" class="form-field-label">Ciudad</label>
                                <input type="text" id="inputCiudad" name="ciudad" class="form-field-input" placeholder="Ej: Medellín, Colombia" value="<?php echo htmlspecialchars($perfil['ciudad'] ?? ''); ?>">
                            </div>
                        </div>
                    </section>

                    <!-- 3. Información de contacto -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloInfoContacto">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloInfoContacto">Información de contacto</h2>
                            <p class="card-subtitle perfil-card-subtitle">Mantén tus canales de comunicación actualizados para oportunidades y convocatorias.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputEmailPrincipal" class="form-field-label">Correo electrónico principal</label>
                                <input type="email" id="inputEmailPrincipal" name="email" class="form-field-input" value="<?php echo htmlspecialchars($userEmail); ?>" readonly style="background-color: #F5F5F5; cursor: not-allowed;" title="El correo principal está vinculado a tu cuenta institucional">
                            </div>
                            <div class="form-field-group">
                                <label for="inputEmailSecundario" class="form-field-label">Correo secundario</label>
                                <input type="email" id="inputEmailSecundario" name="email_secundario" class="form-field-input" placeholder="ejemplo@correo.com" value="<?php echo htmlspecialchars($perfil['email_secundario'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputTelefono" class="form-field-label">Teléfono celular</label>
                                <input type="tel" id="inputTelefono" name="telefono" class="form-field-input" placeholder="Ej: +57 300 123 4567" value="<?php echo htmlspecialchars($perfil['telefono'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputDireccion" class="form-field-label">Dirección / Barrio</label>
                                <input type="text" id="inputDireccion" name="direccion" class="form-field-input" placeholder="Ej: Comuna 10 (La Candelaria)" value="<?php echo htmlspecialchars($perfil['direccion'] ?? ''); ?>">
                            </div>
                        </div>
                    </section>

                    <!-- 4. Formación académica -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloFormacion">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloFormacion">Formación académica</h2>
                            <p class="card-subtitle perfil-card-subtitle">Registra tus títulos institucionales y estudios superiores complementarios.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputTituloInstitucional" class="form-field-label">Título obtenido en la institución</label>
                                <input type="text" id="inputTituloInstitucional" name="titulo_institucional" class="form-field-input" placeholder="Ej: Bachiller Técnico en Sistemas" value="<?php echo htmlspecialchars($perfil['titulo_institucional'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputAnioGraduacion" class="form-field-label">Año de graduación</label>
                                <input type="number" id="inputAnioGraduacion" name="anio_graduacion" class="form-field-input" placeholder="Ej: 2024" min="1950" max="2100" value="<?php echo htmlspecialchars($perfil['anio_graduacion'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputProgramaSuperior" class="form-field-label">Programa académico superior</label>
                                <input type="text" id="inputProgramaSuperior" name="programa_superior" class="form-field-input" placeholder="Ej: Ingeniería de Sistemas" value="<?php echo htmlspecialchars($perfil['programa_superior'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputInstitucionSuperior" class="form-field-label">Institución de estudios superiores</label>
                                <input type="text" id="inputInstitucionSuperior" name="institucion_superior" class="form-field-input" placeholder="Ej: Universidad de Antioquia / ITM / SENA" value="<?php echo htmlspecialchars($perfil['institucion_superior'] ?? ''); ?>">
                            </div>
                        </div>
                    </section>

                    <!-- 5. Experiencia laboral y profesional -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloExperiencia">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloExperiencia">Experiencia laboral y profesional</h2>
                            <p class="card-subtitle perfil-card-subtitle">Comparte tu trayectoria y posición laboral actual con la red de egresados.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputOcupacionRol" class="form-field-label">Ocupación / Rol profesional</label>
                                <input type="text" id="inputOcupacionRol" name="ocupacion" class="form-field-input" placeholder="Ej: Desarrollador Frontend Junior" value="<?php echo htmlspecialchars($perfil['ocupacion'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputEmpresa" class="form-field-label">Empresa u organización</label>
                                <input type="text" id="inputEmpresa" name="empresa" class="form-field-input" placeholder="Ej: Empresa S.A.S." value="<?php echo htmlspecialchars($perfil['empresa'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputAreaEspecialidad" class="form-field-label">Área de especialidad</label>
                                <input type="text" id="inputAreaEspecialidad" name="area_especialidad" class="form-field-input" placeholder="Ej: Desarrollo Web y Aplicaciones" value="<?php echo htmlspecialchars($perfil['area_especialidad'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputAniosExperiencia" class="form-field-label">Años de experiencia</label>
                                <input type="number" id="inputAniosExperiencia" name="anios_experiencia" class="form-field-input" placeholder="Ej: 2" min="0" max="60" value="<?php echo htmlspecialchars($perfil['anios_experiencia'] ?? ''); ?>">
                            </div>
                        </div>
                    </section>

                    <!-- 6. Habilidades y competencias -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloHabilidades">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloHabilidades">Habilidades y competencias</h2>
                            <p class="card-subtitle perfil-card-subtitle">Escribe tus competencias técnicas o blandas y pulsa coma o Enter para agregarlas.</p>
                        </div>
                        <div class="form-field-group">
                            <label for="inputSkills" class="form-field-label">Competencias y habilidades</label>
                            <div class="skills-badges-container" id="skillsBadgesContainer" aria-live="polite"></div>
                            <input type="text" id="inputSkills" class="form-field-input" placeholder="Ej: React, Python, Liderazgo (escribe y pulsa Enter o coma)">
                            <input type="hidden" name="habilidades" id="inputHabilidadesHidden" value="<?php echo htmlspecialchars($habilidadesValue); ?>">
                            <span class="form-field-helper">Haz clic en la "×" de cualquier etiqueta para removerla.</span>
                        </div>
                    </section>

                    <!-- 7. Redes y enlaces -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloRedes">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloRedes">Redes y enlaces</h2>
                            <p class="card-subtitle perfil-card-subtitle">Enlaza tus perfiles profesionales y portafolios digitales.</p>
                        </div>
                        <div class="perfil-grid-2">
                            <div class="form-field-group">
                                <label for="inputLinkedin" class="form-field-label">LinkedIn</label>
                                <input type="url" id="inputLinkedin" name="linkedin" class="form-field-input" placeholder="https://linkedin.com/in/tu-perfil" value="<?php echo htmlspecialchars($perfil['linkedin'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputGithub" class="form-field-label">GitHub</label>
                                <input type="url" id="inputGithub" name="github" class="form-field-input" placeholder="https://github.com/tu-usuario" value="<?php echo htmlspecialchars($perfil['github'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputWeb" class="form-field-label">Portafolio web</label>
                                <input type="url" id="inputWeb" name="web" class="form-field-input" placeholder="https://tu-sitio-web.com" value="<?php echo htmlspecialchars($perfil['sitio_web'] ?? ''); ?>">
                            </div>
                            <div class="form-field-group">
                                <label for="inputRedAdicional" class="form-field-label">Red social adicional</label>
                                <input type="text" id="inputRedAdicional" name="red_adicional" class="form-field-input" placeholder="Twitter, Behance o Instagram" value="<?php echo htmlspecialchars($perfil['red_adicional'] ?? ''); ?>">
                            </div>
                        </div>
                    </section>

                    <!-- 8. Visibilidad y Privacidad en el Directorio -->
                    <section class="profile-section-card perfil-card" aria-labelledby="cardTituloPrivacidad">
                        <div class="section-card-header perfil-card-header">
                            <h2 class="card-title perfil-card-title" id="cardTituloPrivacidad">Visibilidad en el directorio</h2>
                            <p class="card-subtitle perfil-card-subtitle">Controla si tu perfil es visible para otros compañeros egresados en el directorio de la institución.</p>
                        </div>
                        <div class="form-field-group">
                            <label style="display: flex; align-items: center; gap: 14px; cursor: pointer; padding: 16px; background: #FAFAFA; border: 1px solid #E0E0E0; border-radius: 8px;">
                                <input type="checkbox" name="perfil_publico" value="1" id="inputPerfilPublico" <?php echo (!isset($perfil['perfil_publico']) || (int)$perfil['perfil_publico'] === 1) ? 'checked' : ''; ?> style="width: 22px; height: 22px; accent-color: #D32F2F; cursor: pointer;">
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-weight: 600; color: #212121; font-size: 15px;">Perfil visible en el directorio de egresados</span>
                                    <span style="color: #757575; font-size: 13px;">Al desmarcar esta opción, tu perfil se ocultará de la lista pública y privada de egresados alzatistas.</span>
                                </div>
                            </label>
                        </div>
                    </section>

                    <!-- 9. Barra Inferior de Acciones -->
                    <div class="perfil-actions-bottom">
                        <a href="index.php?page=perfil" class="btn-perfil-cancel">Cancelar</a>
                        <button type="submit" class="btn-perfil-save" id="btnActualizarPerfil">Guardar cambios</button>
                    </div>

                </form>
            </div>

        </main>
    </div>
</div>

<?php
// Inclusión del pie de página global compartido
require_once __DIR__ . '/../includes/footer.php';
ob_end_flush();
?>
