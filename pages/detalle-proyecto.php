<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Detalle de Proyecto (Figma: 10 - Detalle de proyecto - Desktop y Mobile)
 * Tecnologías: PHP 8.2, HTML5, CSS3, JavaScript y MySQL (PDO)
 * Versión: 0.1.1
 */

ob_start();

if (file_exists(__DIR__ . '/../config/config.php')) {
    require_once __DIR__ . '/../config/config.php';
}

if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si no hay sesión activa (tanto por $_SESSION['user'] como $_SESSION['usuario'])
require_once __DIR__ . '/../includes/auth.php';

if (!isAuthenticated()) {
    header('Location: index.php?page=login');
    exit;
}

// 1. Variables obligatorias para integración con la arquitectura global
$seccion_activa = 'proyectos';
$titulo_seccion = 'Detalle de proyecto';
$titulo_pagina  = 'Detalle de proyecto — Raíces Alzate';

$currentPage = 'proyectos';
$pageTitle   = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// 2. Extracción segura de datos del usuario en sesión
$currentUser = currentUser();
$userId      = (int)($currentUser['id'] ?? 0);
$userName    = trim($currentUser['nombre_completo'] ?? $currentUser['nombre'] ?? 'Usuario Alzatista');
$userRole    = trim($currentUser['rol_nombre'] ?? $currentUser['rol'] ?? 'Egresado');
$userEmail   = trim($currentUser['email'] ?? '');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'U';

$esAdmin = (strtolower($userRole) === 'administrador' || strtolower($userRole) === 'admin');

// 3. Validación y saneamiento del parámetro ID recibido por GET
$proyectoId = (int)($_GET['id'] ?? 0);
if ($proyectoId < 1) {
    $proyectoId = 1;
}

// 4. Catálogo de fallback estático alineado con Figma y con la base de datos oficial
$proyectosFallback = [
    1 => [
        'id'                => 1,
        'titulo'            => 'Sistema de gestión académica',
        'descripcion'       => "Plataforma web orientada al seguimiento curricular, registro de calificaciones y comunicación directa entre docentes, estudiantes y acudientes de la Institución Educativa Gilberto Alzate Avendaño.\n\nEste proyecto busca optimizar los procesos administrativos institucionales, centralizando la información académica de manera ágil y transparente, y brindando herramientas para el análisis del rendimiento estudiantil a lo largo de los periodos escolares.",
        'portada'           => '',
        'autor_nombre'      => 'Juan Pérez',
        'autor_id'          => 101,
        'categoria'         => 'Educación y Software',
        'enlace'            => 'https://ejemplo.com/gestion-academica',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-10 14:30:00'
    ],
    2 => [
        'id'                => 2,
        'titulo'            => 'Aplicación para emprendedores',
        'descripcion'       => "Herramienta digital diseñada para facilitar la administración básica de inventarios, finanzas y ventas en pequeños negocios y emprendimientos liderados por egresados y familias alzatistas.\n\nLa aplicación permite llevar un registro ordenado del flujo de caja, catalogar productos con facilidad y generar reportes financieros simples para la toma oportuna de decisiones.",
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
        'descripcion'       => "Espacio interactivo y visual para la exhibición de proyectos de diseño gráfico, identidad corporativa, modelado 3D y prototipado de interfaces de usuario.\n\nReúne creaciones conceptuales y proyectos comerciales desarrollados por egresados del énfasis técnico comercial y de diseño de la institución.",
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
        'descripcion'       => "Solución de comercio electrónico moderna, modular y accesible con catálogo de productos, carrito de compras integrado, cálculo automatizado de envíos y pasarela de pagos segura.\n\nDesarrollada para impulsar el comercio digital de productos locales y fomentar la autogestión económica de los graduados.",
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
        'descripcion'       => "Aplicación comunitaria para conectar prestadores de servicios técnicos (electricidad, mantenimiento de computadores, diseño, plomería) con vecinos que requieren soluciones rápidas y de confianza en el hogar.\n\nPromueve la economía circular barrial y la contratación de mano de obra técnica calificada de nuestra comunidad.",
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
        'descripcion'       => "Iniciativa social y comunitaria que promueve el reciclaje inclusivo, la sostenibilidad ambiental y la participación activa de los jóvenes en la comuna a través de jornadas ecológicas y talleres formativos.\n\nEl proyecto articula esfuerzos comunitarios para recuperar espacios públicos y crear huertas urbanas sostenibles.",
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
        'descripcion'       => "Programa de acompañamiento vocacional y tutoría profesional liderado por egresados universitarios y técnicos para orientar a bachilleres de últimos grados en su elección de carrera y vida profesional.\n\nIncluye charlas periódicas, revisión de hojas de vida y acompañamiento personalizado.",
        'portada'           => '',
        'autor_nombre'      => 'Santiago Emitola',
        'autor_id'          => 1,
        'categoria'         => 'Educación y Mentoría',
        'enlace'            => 'https://ejemplo.com/red-mentorias',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-04 15:00:00'
    ],
    8 => [
        'id'                => 8,
        'titulo'            => 'Plataforma de empleo joven',
        'descripcion'       => "Bolsa de empleo y prácticas técnicas enfocada en vincular laboralmente a egresados con empresas del sector tecnológico, comercial e industrial de Medellín y el Valle de Aburrá.\n\nBrinda visibilidad a los perfiles técnicos formados en la I.E. Gilberto Alzate Avendaño.",
        'portada'           => '',
        'autor_nombre'      => 'Diego Castro',
        'autor_id'          => 107,
        'categoria'         => 'Empleabilidad',
        'enlace'            => 'https://ejemplo.com/empleo-joven',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-03 13:40:00'
    ],
    9 => [
        'id'                => 9,
        'titulo'            => 'Semillero de robótica educativa',
        'descripcion'       => "Iniciativa orientada a fomentar el pensamiento computacional, la robótica y la innovación con microcontroladores en secundaria.\n\nFacilita talleres prácticos de electrónica básica y programación de sensores para incentivar vocaciones STEM en los estudiantes.",
        'portada'           => '',
        'autor_nombre'      => 'Camila Morales',
        'autor_id'          => 108,
        'categoria'         => 'Tecnología e Innovación',
        'enlace'            => 'https://ejemplo.com/semillero-robotica',
        'estado'            => 'publicado',
        'fecha_publicacion' => '2026-10-02 11:10:00'
    ]
];

// 5. Consulta en base de datos MySQL mediante PDO (Consultas preparadas seguras)
$proyecto = null;
$errorConsulta = false;

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
            // Preservar enlace si está definido en el catálogo de fallback o sesión
            if (empty($proyecto['enlace']) && !empty($proyectosFallback[$proyectoId]['enlace'])) {
                $proyecto['enlace'] = $proyectosFallback[$proyectoId]['enlace'];
            }
        }
    } catch (PDOException $e) {
        $errorConsulta = true;
        error_log('Error en detalle-proyecto.php (PDO): ' . $e->getMessage());
    }
}

// Fallback elegante a la colección canónica si la BD no retorna datos o no está disponible
if (!$proyecto && isset($proyectosFallback[$proyectoId])) {
    $proyecto = $proyectosFallback[$proyectoId];
}

// 6. Validación de permisos para acciones del autor (Editar proyecto)
$puedeEditar = false;
if ($proyecto) {
    $esAutor = (!empty($proyecto['autor_id']) && (int)$userId === (int)$proyecto['autor_id']) 
            || (!empty($proyecto['autor_nombre']) && strcasecmp($userName, $proyecto['autor_nombre']) === 0);
    $puedeEditar = ($esAutor || $esAdmin);

    // Ajustar título dinámico de la página
    $pageTitle = htmlspecialchars($proyecto['titulo']) . ' — Detalle de proyecto | ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');
}

// Función auxiliar para formatear fechas de manera amigable en español
function formatearFechaEspanol($fechaStr) {
    if (empty($fechaStr)) return 'Recientemente';
    $timestamp = strtotime($fechaStr);
    if (!$timestamp) return htmlspecialchars($fechaStr);

    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];

    $dia  = date('j', $timestamp);
    $mes  = $meses[(int)date('n', $timestamp)] ?? date('m', $timestamp);
    $anio = date('Y', $timestamp);

    return "{$dia} de {$mes} de {$anio}";
}

// Hojas de estilo y scripts modulares
$extraCss = ['dashboard.css', 'detalle-proyecto.css'];
$extraJs  = ['assets/js/detalle-proyecto.js'];

// Inclusión del encabezado global compartido
require_once __DIR__ . '/../includes/header.php';
?>
<script>
    // Asignación de clases en body para el entorno privado del Dashboard
    document.body.classList.add('dashboard-body-page', 'proyectos-page', 'detalle-proyecto-page');
</script>

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="dashboard-layout" id="dashboardLayout">
    <!-- Menú lateral de navegación privada (Sidebar con ítem "Proyectos" activo en rojo institucional #D32F2F) -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="dashboard-main-area">
        <!-- Barra superior (Topbar con título dinámico "Detalle de proyecto" e identidad de usuario) -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <!-- Contenido Central: Fluye de manera natural debajo de la Topbar sin solapamiento -->
        <main class="dashboard-content content-area detalle-proyecto-content-wrapper" id="detalleProyectoMainContent" tabindex="-1">
            
            <div class="detalle-proyecto-container">

                <!-- Barra de Navegación de Retorno y Acciones de Cabecera -->
                <nav class="detalle-top-nav-bar" aria-label="Navegación de retorno al catálogo de proyectos">
                    <a href="index.php?page=proyectos&view=panel" class="btn-volver-proyectos" id="btnVolverListado">
                        <svg class="volver-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Volver a proyectos</span>
                    </a>

                    <?php if ($proyecto && $puedeEditar): ?>
                    <div class="detalle-nav-actions">
                        <a href="editar-proyecto.php?id=<?php echo (int)$proyecto['id']; ?>" class="btn-editar-proyecto btn-outline-blue" id="btnEditarProyecto">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <span>Editar proyecto</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </nav>

                <?php if (!$proyecto): ?>
                <!-- Estado: Proyecto No Encontrado -->
                <section class="detalle-empty-card" role="status" aria-label="Aviso de proyecto no encontrado">
                    <div class="empty-icon-circle" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </div>
                    <h2 class="empty-card-title">Proyecto no encontrado</h2>
                    <p class="empty-card-desc">El proyecto solicitado no existe, ha sido retirado o el identificador en la dirección web no es correcto.</p>
                    <a href="index.php?page=proyectos&view=panel" class="btn-cancelar btn-outline-blue">
                        &larr; Regresar al catálogo de proyectos
                    </a>
                </section>

                <?php else: ?>
                <!-- Tarjeta Principal del Detalle (#FFFFFF, borde #E0E0E0, radio 16px, padding generoso) -->
                <article class="detalle-proyecto-card" aria-label="Detalle completo del proyecto <?php echo htmlspecialchars($proyecto['titulo']); ?>">
                    
                    <!-- 1. Portada del Proyecto (Proporción Panorámica 16:9 con soporte para imagen real o ilustración institucional) -->
                    <div class="detalle-cover-wrapper">
                        <?php 
                            $defaultProyCovers = ['assets/img/proyecto-cover-1.svg', 'assets/img/proyecto-cover-2.svg', 'assets/img/proyecto-cover-3.svg'];
                            $coverIdx = ((int)($proyecto['id'] ?? 1)) % count($defaultProyCovers);
                            $coverToShow = !empty($proyecto['portada']) ? $proyecto['portada'] : $defaultProyCovers[$coverIdx];
                        ?>
                        <img 
                            src="<?php echo htmlspecialchars($coverToShow); ?>" 
                            alt="Portada oficial del proyecto <?php echo htmlspecialchars($proyecto['titulo']); ?>" 
                            class="detalle-cover-img"
                            loading="eager"
                            style="width: 100%; height: 100%; max-height: 400px; object-fit: cover; display: block; border-radius: 10px;"
                        >
                    </div>

                    <!-- 2. Encabezado Interior de la Tarjeta (Badges, Título Principal y Metadatos) -->
                    <div class="detalle-card-header">
                        
                        <div class="detalle-badges-group">
                            <span class="badge-categoria">
                                <?php echo htmlspecialchars($proyecto['categoria'] ?? 'General'); ?>
                            </span>
                            <span class="badge-estado badge-publicado">
                                <span class="badge-dot" aria-hidden="true"></span>
                                <?php echo htmlspecialchars(ucfirst($proyecto['estado'] ?? 'Publicado')); ?>
                            </span>
                        </div>

                        <h1 class="detalle-proyecto-title">
                            <?php echo htmlspecialchars($proyecto['titulo']); ?>
                        </h1>

                        <div class="detalle-meta-row">
                            <div class="meta-item meta-autor">
                                <svg class="meta-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Por <strong><?php echo htmlspecialchars($proyecto['autor_nombre'] ?? 'Egresado Alzatista'); ?></strong></span>
                            </div>

                            <span class="meta-separator" aria-hidden="true">•</span>

                            <div class="meta-item meta-fecha">
                                <svg class="meta-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>Publicado el <?php echo htmlspecialchars(formatearFechaEspanol($proyecto['fecha_publicacion'] ?? '')); ?></span>
                            </div>
                        </div>

                    </div>

                    <!-- Divisor visual armónico -->
                    <hr class="detalle-content-divider">

                    <!-- 3. Descripción Completa del Proyecto -->
                    <div class="detalle-body-block">
                        <h2 class="detalle-section-title">Acerca de este proyecto</h2>
                        <div class="detalle-description-text">
                            <?php echo nl2br(htmlspecialchars($proyecto['descripcion'])); ?>
                        </div>
                    </div>

                    <!-- 4. Enlace Web del Proyecto (si existe) -->
                    <?php if (!empty($proyecto['enlace'])): ?>
                    <div class="detalle-resource-card">
                        <div class="resource-card-left">
                            <div class="resource-icon-wrap" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                            </div>
                            <div class="resource-meta">
                                <span class="resource-label">Enlace externo del proyecto</span>
                                <span class="resource-url" title="<?php echo htmlspecialchars($proyecto['enlace']); ?>">
                                    <?php echo htmlspecialchars($proyecto['enlace']); ?>
                                </span>
                            </div>
                        </div>

                        <a href="<?php echo htmlspecialchars($proyecto['enlace']); ?>" target="_blank" rel="noopener noreferrer" class="btn-visitar-proyecto" id="btnVisitarProyecto">
                            <span>Visitar proyecto</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </a>
                    </div>
                    <?php endif; ?>

                    <!-- 5. Acciones al Pie de la Tarjeta -->
                    <div class="detalle-card-footer">
                        <a href="index.php?page=proyectos&view=panel" class="btn-cancelar btn-outline-blue" id="btnVolverPie">
                            &larr; Volver al catálogo de proyectos
                        </a>
                        
                        <?php if ($puedeEditar): ?>
                        <a href="editar-proyecto.php?id=<?php echo (int)$proyecto['id']; ?>" class="btn-publicar-proyecto btn-primary-publish" id="btnEditarPie">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-right: 6px;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <span>Editar proyecto</span>
                        </a>
                        <?php endif; ?>
                    </div>

                </article>
                <?php endif; ?>

            </div>

        </main>
    </div>
</div>

<?php
// Inclusión del pie de página global compartido
require_once __DIR__ . '/../includes/footer.php';
ob_end_flush();
