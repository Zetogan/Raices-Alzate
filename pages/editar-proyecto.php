<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Editar Proyecto (Figma: Editar proyecto - Desktop y Mobile)
 * Tecnologías: PHP 8.2, HTML5, CSS3, JavaScript vanilla y MySQL (PDO)
 * Versión: 0.1.1
 */

ob_start();

if (file_exists(__DIR__ . '/../config/config.php')) {
    require_once __DIR__ . '/../config/config.php';
}

if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
}

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

// Verificar si no hay sesión activa
if (!isAuthenticated()) {
    header('Location: index.php?page=login');
    exit;
}

// 1. Generación y validación de Token CSRF
$csrfToken = getCsrfToken();

// 2. Definición obligatoria de variables requeridas por la arquitectura global
$seccion_activa = 'proyectos';
$titulo_seccion = 'Editar proyecto';
$titulo_pagina  = 'Editar proyecto — Raíces Alzate';

$currentPage = 'proyectos';
$pageTitle   = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// 3. Extracción segura de la identidad del usuario en sesión
$currentUser = currentUser();
$userId      = (int)($currentUser['id'] ?? 0);
$userName    = trim($currentUser['nombre_completo'] ?? $currentUser['nombre'] ?? 'Usuario Alzatista');
$userRole    = trim($currentUser['rol_nombre'] ?? $currentUser['rol'] ?? 'Egresado');
$userEmail   = trim($currentUser['email'] ?? '');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

$esAdmin = (strtolower($userRole) === 'administrador' || strtolower($userRole) === 'admin');

$proyectoId = (int)($_POST['proyecto_id'] ?? $_GET['id'] ?? 0);
if ($proyectoId < 1) {
    $proyectoId = 1;
}

// 5. Catálogo canónico de fallback alineado con la base de datos oficial
$proyectosFallback = [
    1 => [
        'id'                => 1,
        'titulo'            => 'Sistema de gestión académica',
        'descripcion'       => "Plataforma para facilitar la gestión y organización académica.",
        'portada'           => '',
        'autor_nombre'      => 'Santiago Emitola',
        'autor_id'          => 1,
        'categoria'         => 'Desarrollo de Software',
        'enlace'            => 'https://ejemplo.com',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-10 14:30:00'
    ],
    2 => [
        'id'                => 2,
        'titulo'            => 'Aplicación para emprendedores',
        'descripcion'       => "Herramienta digital para apoyar pequeños emprendimientos.",
        'portada'           => '',
        'autor_nombre'      => 'María López',
        'autor_id'          => 102,
        'categoria'         => 'Emprendimiento y Negocios',
        'enlace'            => 'https://ejemplo.com/emprendedores',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-09 11:15:00'
    ],
    3 => [
        'id'                => 3,
        'titulo'            => 'Portafolio de diseño',
        'descripcion'       => "Espacio para mostrar trabajos y proyectos de diseño gráfico.",
        'portada'           => '',
        'autor_nombre'      => 'Carlos Rodríguez',
        'autor_id'          => 103,
        'categoria'         => 'Arte, Diseño y Creatividad',
        'enlace'            => 'https://ejemplo.com/portafolio-diseno',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-08 16:45:00'
    ],
    4 => [
        'id'                => 4,
        'titulo'            => 'Tienda virtual',
        'descripcion'       => "Proyecto enfocado en la creación de una tienda en línea.",
        'portada'           => '',
        'autor_nombre'      => 'Laura Martínez',
        'autor_id'          => 104,
        'categoria'         => 'Comercio Electrónico',
        'enlace'            => 'https://ejemplo.com/tienda-virtual',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-07 10:00:00'
    ],
    5 => [
        'id'                => 5,
        'titulo'            => 'App de servicios',
        'descripcion'       => "Aplicación para conectar usuarios con servicios locales.",
        'portada'           => '',
        'autor_nombre'      => 'Andrés Gómez',
        'autor_id'          => 105,
        'categoria'         => 'Servicios y Comunidad',
        'enlace'            => 'https://ejemplo.com/app-servicios',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-06 18:20:00'
    ],
    6 => [
        'id'                => 6,
        'titulo'            => 'Proyecto comunitario',
        'descripcion'       => "Iniciativa para fortalecer la participación de la comunidad.",
        'portada'           => '',
        'autor_nombre'      => 'Valentina Torres',
        'autor_id'          => 106,
        'categoria'         => 'Comunidad e Impacto Social',
        'enlace'            => 'https://ejemplo.com/proyecto-comunitario',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-05 09:30:00'
    ],
    7 => [
        'id'                => 7,
        'titulo'            => 'Red de mentorías alzatistas',
        'descripcion'       => "Programa de acompañamiento vocacional y tutoría profesional liderado por egresados.",
        'portada'           => '',
        'autor_nombre'      => 'Santiago Emitola',
        'autor_id'          => 1,
        'categoria'         => 'Educación y Mentoría',
        'enlace'            => 'https://ejemplo.com/red-mentorias',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-04 15:00:00'
    ]
];

// 6. Consulta en base de datos MySQL mediante PDO (Consultas preparadas)
$proyecto = null;
$errorDb = null;

if (isset($pdo) && $pdo instanceof PDO) {
    try {
        $sql = "SELECT p.id, p.titulo, p.descripcion, p.portada, p.enlace, p.autor_id, p.categoria, p.estado, p.fecha_publicacion,
                       u.nombre_completo AS autor_nombre
                FROM proyectos p
                LEFT JOIN usuarios u ON u.id = p.autor_id
                WHERE p.id = :id 
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $proyectoId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $proyecto = $resultado;
            // Preservar enlace si está definido en el catálogo o sesión
            if (empty($proyecto['enlace']) && !empty($proyectosFallback[$proyectoId]['enlace'])) {
                $proyecto['enlace'] = $proyectosFallback[$proyectoId]['enlace'];
            }
        }
    } catch (PDOException $e) {
        $errorDb = $e->getMessage();
        error_log('Error en editar-proyecto.php (PDO Select): ' . $errorDb);
    }
}

// Fallback canónico si no se encontró en MySQL
if (!$proyecto && isset($proyectosFallback[$proyectoId])) {
    $proyecto = $proyectosFallback[$proyectoId];
}

// 7. Verificación de permisos de edición
$puedeEditar = false;
if ($proyecto) {
    // Es autor si coincide el autor_id con el usuario en sesión o si es administrador
    $esAutor = (!empty($proyecto['autor_id']) && (int)$userId === (int)$proyecto['autor_id']);
    $puedeEditar = ($esAutor || $esAdmin);

    // Ajuste dinámico de título
    $pageTitle = 'Editar: ' . htmlspecialchars($proyecto['titulo']) . ' — Raíces Alzate';
}

// 8. Procesamiento del Formulario de Edición (POST)
$mensajeExito = '';
$mensajeError = '';

$formData = [
    'nombre_proyecto'      => $proyecto['titulo'] ?? '',
    'categoria'            => $proyecto['categoria'] ?? '',
    'descripcion_proyecto' => $proyecto['descripcion'] ?? '',
    'enlace_proyecto'      => $proyecto['enlace'] ?? ''
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && $proyecto) {
    // 8.1 Verificación de CSRF y Permisos en el Servidor
    $tokenEnviado = $_POST['csrf_token'] ?? '';
    if (!hash_equals($csrfToken, $tokenEnviado)) {
        $mensajeError = 'Error de seguridad: Solicitud no autorizada o sesión expirada.';
    } elseif (!$puedeEditar) {
        $mensajeError = 'Acceso denegado: No dispones de permisos para modificar este proyecto.';
    } else {
        // 8.2 Captura y saneamiento de campos
        $nombreProyecto      = trim($_POST['nombre_proyecto'] ?? '');
        $categoria           = trim($_POST['categoria'] ?? '');
        $descripcionProyecto = trim($_POST['descripcion_proyecto'] ?? '');
        $enlaceProyecto      = trim($_POST['enlace_proyecto'] ?? '');
        $quitarImagen        = !empty($_POST['quitar_imagen']);

        $formData['nombre_proyecto']      = $nombreProyecto;
        $formData['categoria']            = $categoria;
        $formData['descripcion_proyecto'] = $descripcionProyecto;
        $formData['enlace_proyecto']      = $enlaceProyecto;

        // 8.3 Validaciones de negocio en el Servidor
        if (empty($nombreProyecto)) {
            $mensajeError = 'El nombre del proyecto es obligatorio.';
        } elseif (mb_strlen($nombreProyecto, 'UTF-8') < 3) {
            $mensajeError = 'El nombre del proyecto debe contener al menos 3 caracteres.';
        } elseif (empty($categoria)) {
            $mensajeError = 'Por favor selecciona o indica una categoría válida.';
        } elseif (empty($descripcionProyecto)) {
            $mensajeError = 'La descripción del proyecto es obligatoria.';
        } elseif (mb_strlen($descripcionProyecto, 'UTF-8') < 10) {
            $mensajeError = 'La descripción debe tener al menos 10 caracteres.';
        } elseif (!empty($enlaceProyecto) && !filter_var($enlaceProyecto, FILTER_VALIDATE_URL) && !filter_var('https://' . $enlaceProyecto, FILTER_VALIDATE_URL)) {
            $mensajeError = 'El enlace ingresado no tiene un formato web válido.';
        } else {
            // 8.4 Gestión de archivo de imagen (si se seleccionó una nueva)
            $nuevaPortada = $proyecto['portada'] ?? '';
            $subidaExitosa = true;

            if ($quitarImagen) {
                $nuevaPortada = '';
            }

            if (isset($_FILES['imagen_proyecto']) && $_FILES['imagen_proyecto']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['imagen_proyecto'];
                $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $tiposPermitidos, true)) {
                    $mensajeError = 'Formato de imagen no permitido. Solo se admiten archivos PNG, JPG o WEBP.';
                    $subidaExitosa = false;
                } elseif ($file['size'] > 5 * 1024 * 1024) {
                    $mensajeError = 'La imagen seleccionada supera el límite máximo de 5 MB.';
                    $subidaExitosa = false;
                } else {
                    $uploadDir = __DIR__ . '/../uploads/proyectos';
                    if (!is_dir($uploadDir)) {
                        @mkdir($uploadDir, 0755, true);
                    }
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nuevoNombre = 'proyecto_' . $proyectoId . '_' . time() . '.' . strtolower($extension);
                    $destino = $uploadDir . '/' . $nuevoNombre;

                    if (move_uploaded_file($file['tmp_name'], $destino)) {
                        $nuevaPortada = 'uploads/proyectos/' . $nuevoNombre;
                    } else {
                        $mensajeError = 'No se pudo guardar la imagen en el servidor.';
                        $subidaExitosa = false;
                    }
                }
            }

            // 8.5 Actualización en MySQL mediante PDO
            if ($subidaExitosa && empty($mensajeError)) {
                $actualizadoEnDb = false;

                if (isset($pdo) && $pdo instanceof PDO) {
                    try {
                        $sqlUpdate = "UPDATE proyectos 
                                      SET titulo = :titulo, 
                                          categoria = :categoria, 
                                          descripcion = :descripcion, 
                                          enlace = :enlace, 
                                          portada = :portada, 
                                          actualizado_en = NOW() 
                                      WHERE id = :id";
                        $stmtUpdate = $pdo->prepare($sqlUpdate);
                        $stmtUpdate->execute([
                            ':titulo'      => $nombreProyecto,
                            ':categoria'   => $categoria,
                            ':descripcion' => $descripcionProyecto,
                            ':enlace'      => $enlaceProyecto,
                            ':portada'     => $nuevaPortada,
                            ':id'          => $proyectoId
                        ]);

                        $actualizadoEnDb = true;
                    } catch (PDOException $e) {
                        error_log('Error en editar-proyecto.php (PDO Update): ' . $e->getMessage());
                        $mensajeError = 'Error al actualizar los datos en el servidor: ' . $e->getMessage();
                    }
                }

                if (empty($mensajeError)) {
                    // Reflejar cambios en la vista local
                    $proyecto['titulo']      = $nombreProyecto;
                    $proyecto['categoria']   = $categoria;
                    $proyecto['descripcion'] = $descripcionProyecto;
                    $proyecto['portada']     = $nuevaPortada;
                    $proyecto['enlace']      = $enlaceProyecto;

                    $mensajeExito = '¡Los cambios del proyecto han sido guardados correctamente!';
                }
            }
        }
    }
}

// 9. Categorías oficiales de la plataforma (incluyendo Desarrollo de Software de Figma)
$categoriasDisponibles = [
    'Desarrollo de Software',
    'Educación y Software',
    'Tecnología e Innovación',
    'Emprendimiento y Negocios',
    'Comunidad e Impacto Social',
    'Educación y Pedagogía',
    'Arte, Diseño y Creatividad',
    'Comercio Electrónico',
    'Servicios y Comunidad',
    'Ciencia y Medio Ambiente',
    'Salud y Bienestar',
    'Empleabilidad'
];

// Si el proyecto actual tiene una categoría que no está en la lista, se agrega dinámicamente
if (!empty($proyecto['categoria']) && !in_array($proyecto['categoria'], $categoriasDisponibles, true)) {
    array_unshift($categoriasDisponibles, $proyecto['categoria']);
}

// Hojas de estilo y scripts modulares
$extraCss = ['dashboard.css', 'editar-proyecto.css'];
$extraJs  = ['assets/js/editar-proyecto.js'];

// Inclusión del encabezado global compartido
require_once __DIR__ . '/../includes/header.php';
?>
<script>
    // Asignación de clases en body para el entorno privado del Dashboard
    document.body.classList.add('dashboard-body-page', 'proyectos-page', 'editar-proyecto-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Proyectos" activo en rojo institucional #D32F2F) -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar con título dinámico "Editar proyecto" e identidad de usuario) -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <!-- Contenido Central: Fluye de manera natural debajo de la Topbar sin solapamiento -->
        <main class="dashboard-content content-area editar-proyecto-content-wrapper" id="editarProyectoMainContent" tabindex="-1">
            
            <div class="editar-proyecto-container">

                <?php if (!$proyecto): ?>
                <!-- Estado: Proyecto No Encontrado -->
                <section class="editar-empty-card" role="status" aria-label="Aviso de proyecto no encontrado">
                    <div class="empty-icon-circle" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </div>
                    <h2 class="empty-card-title">Proyecto no encontrado</h2>
                    <p class="empty-card-desc">El proyecto que intentas editar no existe, ha sido retirado o el identificador en la dirección web no es correcto.</p>
                    <a href="index.php?page=proyectos&view=panel" class="btn-cancelar btn-outline-blue">
                        &larr; Regresar al catálogo de proyectos
                    </a>
                </section>

                <?php elseif (!$puedeEditar): ?>
                <!-- Estado: Usuario Sin Permisos de Edición -->
                <section class="editar-unauthorized-card" role="alert" aria-label="Aviso de permisos insuficientes">
                    <div class="unauthorized-icon-circle" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h2 class="unauthorized-card-title">Acceso no autorizado</h2>
                    <p class="unauthorized-card-desc">No dispones de los permisos necesarios para modificar este proyecto. Solo el egresado autor (<strong><?php echo htmlspecialchars($proyecto['autor_nombre'] ?? 'Autor original'); ?></strong>) o un administrador de Raíces Alzate pueden editarlo.</p>
                    <div class="unauthorized-actions">
                        <a href="index.php?page=detalle-proyecto&id=<?php echo (int)$proyecto['id']; ?>" class="btn-cancelar btn-outline-blue">
                            Ver detalle del proyecto
                        </a>
                        <a href="index.php?page=proyectos&view=panel" class="btn-cancelar btn-outline-blue">
                            &larr; Volver al catálogo
                        </a>
                    </div>
                </section>

                <?php else: ?>

                <!-- Alerta de Éxito al Actualizar -->
                <?php if (!empty($mensajeExito)): ?>
                <div class="alert-status alert-success" id="alertSuccess" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="alert-content">
                        <strong>¡Operación exitosa!</strong>
                        <span><?php echo htmlspecialchars($mensajeExito); ?></span>
                    </div>
                    <a href="index.php?page=detalle-proyecto&id=<?php echo (int)$proyecto['id']; ?>" class="alert-link" id="linkVerDetalleActualizado">
                        Ver proyecto actualizado &rarr;
                    </a>
                </div>
                <?php endif; ?>

                <!-- Alerta de Error de Validación o Servidor -->
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
                <section class="editar-proyecto-card form-card card-container" aria-label="Formulario para editar proyecto">
                    
                    <!-- Encabezado DENTRO de la tarjeta blanca según Figma ("Editar proyecto - Desktop") -->
                    <div class="editar-proyecto-card-header card-header form-header">
                        <h2 class="card-title">Editar información del proyecto</h2>
                        <p class="card-subtitle">Actualiza la información de tu proyecto.</p>
                    </div>

                    <!-- Formulario de Edición de Proyecto -->
                    <form action="editar-proyecto.php?id=<?php echo (int)$proyecto['id']; ?>" method="POST" enctype="multipart/form-data" class="editar-proyecto-form" id="formEditarProyecto" novalidate>
                        
                        <!-- Token de seguridad CSRF e identificador oculto -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        <input type="hidden" name="proyecto_id" value="<?php echo (int)$proyecto['id']; ?>">
                        <input type="hidden" name="quitar_imagen" id="inputQuitarImagen" value="0">

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
                                    placeholder="Sistema de gestión académica" 
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
                                        <option value="" disabled>Selecciona una categoría</option>
                                        <?php foreach ($categoriasDisponibles as $cat): ?>
                                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($formData['categoria'] === $cat) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="select-chevron-icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <span class="field-error-msg" id="errorCategoria" aria-live="polite"></span>
                            </div>

                            <!-- Fila 2 - Ancho completo: Descripción -->
                            <div class="form-group form-group-full">
                                <label for="descripcion_proyecto" class="form-label">
                                    Descripción <span class="required-indicator" aria-hidden="true">*</span>
                                </label>
                                <textarea 
                                    id="descripcion_proyecto" 
                                    name="descripcion_proyecto" 
                                    class="form-textarea" 
                                    rows="5" 
                                    placeholder="Plataforma para facilitar la gestión y organización académica." 
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

                            <!-- Fila 3 - Columna 2: Imagen del proyecto (Dropzone de edición con estado "Imagen actual") -->
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

                                    <!-- Estado Inicial de Edición según Figma: "Imagen actual / Haz clic para cambiar la imagen" -->
                                    <div class="dropzone-placeholder" id="dropzonePlaceholder" style="<?php echo !empty($proyecto['portada']) ? 'display: none;' : 'display: flex;'; ?>">
                                        <span class="dropzone-action-text" id="dropzoneActionText">Imagen actual</span>
                                        <span class="dropzone-subtext" id="dropzoneSubtext">Haz clic para cambiar la imagen</span>
                                    </div>

                                    <!-- Vista previa de imagen actual o recién seleccionada -->
                                    <div class="dropzone-preview-wrap" id="dropzonePreviewWrap" style="<?php echo !empty($proyecto['portada']) ? 'display: flex;' : 'display: none;'; ?>">
                                        <img 
                                            src="<?php echo !empty($proyecto['portada']) ? htmlspecialchars($proyecto['portada']) : ''; ?>" 
                                            alt="Portada del proyecto" 
                                            class="dropzone-preview-img" 
                                            id="imgPreview"
                                        >
                                        <div class="dropzone-preview-meta">
                                            <span class="preview-filename" id="previewFilename">
                                                <?php echo !empty($proyecto['portada']) ? basename($proyecto['portada']) : 'imagen_portada.jpg'; ?>
                                            </span>
                                            <div class="preview-actions">
                                                <button type="button" class="btn-preview-action btn-change-image" id="btnCambiarImagen">
                                                    Cambiar imagen
                                                </button>
                                                <button type="button" class="btn-preview-action btn-remove-image" id="btnQuitarImagen" title="Eliminar imagen">
                                                    Quitar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <span class="field-error-msg" id="errorImagenProyecto" aria-live="polite"></span>
                            </div>

                        </div>

                        <!-- Acciones al final: Alineadas a la derecha con "Cancelar" y "Guardar cambios" -->
                        <div class="form-actions-footer">
                            <a href="index.php?page=detalle-proyecto&id=<?php echo (int)$proyecto['id']; ?>" class="btn-cancelar btn-outline-blue btn-outline-secondary" id="btnCancelarEdicion" role="button">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-guardar-cambios btn-primary-publish" id="btnGuardarCambios">
                                Guardar cambios
                            </button>
                        </div>

                    </form>

                </section>

                <?php endif; ?>

            </div>

        </main>
    </div>
</div>

<?php
// Inclusión del pie de página global compartido
require_once __DIR__ . '/../includes/footer.php';
ob_end_flush();
