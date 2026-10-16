<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Detalle de Oportunidad
 * Arquitectura: Frontend puro (CERO bases de datos, CERO SQL, CERO endpoints AJAX)
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Control de acceso para usuarios autenticados
if (empty($_SESSION['user']) && empty($_SESSION['usuario']) && empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/config.php';

// 1. Definición obligatoria de variables requeridas por la directiva
$seccion_activa = 'oportunidades';
$titulo_seccion = 'Detalle de oportunidad';
$titulo_pagina  = 'Detalle de oportunidad — Raíces Alzate';

// Metadatos y variables compatibles para includes globales
$currentPage = 'oportunidades';
$pageTitle   = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Identidad de usuario para la barra superior (Topbar)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName    = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago');
$userRole    = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) 
    ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') 
    : 'S';

// Hojas de estilo y scripts modulares
$extraCss = ['dashboard.css', 'oportunidad-detalle.css'];
$extraJs  = ['assets/js/oportunidades.js'];

// Catálogo canónico de oportunidades en PHP estático
$oportunidadesCatalogo = [
    1 => [
        'id'                => 1,
        'titulo'            => 'Convocatoria laboral: Desarrollador Web Junior',
        'empresa'           => 'Tech Innovate Medellín',
        'publicado_por'     => 'Alianza Empresarial Alzatista',
        'categoria'         => 'Empleo',
        'modalidad'         => 'Híbrido (3 días presencial / 2 días remoto)',
        'ubicacion'         => 'Medellín, Antioquia',
        'fecha_publicacion' => '12 de octubre de 2026',
        'fecha_limite'      => '30 de octubre de 2026',
        'descripcion'       => 'Importante empresa del ecosistema tecnológico de Medellín abre convocatoria exclusiva para egresados alzatistas con formación técnica o tecnológica en desarrollo de software, análisis de sistemas o carreras afines. El candidato seleccionado participará en proyectos reales de desarrollo web construyendo componentes interactivos, maquetación responsiva e integración de plataformas institucionales.',
        'requisitos'        => [
            'Título de bachiller técnico o tecnólogo egresado de la IE Gilberto Alzate Avendaño.',
            'Conocimientos sólidos en maquetación HTML5 semántica y CSS3 modular (diseño responsive y Flexbox/Grid).',
            'Manejo básico o intermedio de JavaScript vanilla y fundamentos de PHP nativo.',
            'Uso de control de versiones con Git (commits, ramas y flujos colaborativos).',
            'Capacidad de trabajo en equipo, orientación al detalle y proactividad.'
        ],
        'beneficios'        => 'Contrato a término indefinido con todas las prestaciones de ley, plan de capacitación continua en nuevas tecnologías, auxilio de conectividad y horario flexible para quienes continúen estudios universitarios.'
    ],
    2 => [
        'id'                => 2,
        'titulo'            => 'Beca de pregrado: Alianza Universitaria 2027',
        'empresa'           => 'Institución Universitaria Pascual Bravo',
        'publicado_por'     => 'Convenio Institucional IE GAA',
        'categoria'         => 'Beca',
        'modalidad'         => 'Presencial',
        'ubicacion'         => 'Medellín, Campus Robledo',
        'fecha_publicacion' => '8 de octubre de 2026',
        'fecha_limite'      => '15 de noviembre de 2026',
        'descripcion'       => 'Programa de becas del 100% de la matrícula para egresados de la Institución Educativa Gilberto Alzate Avendaño interesados en cursar programas profesionales en Ingeniería de Software, Ingeniería Mecatrónica o Tecnología en Gestión del Mantenimiento.',
        'requisitos'        => [
            'Haberse graduado de bachiller en la IE Gilberto Alzate Avendaño en las promociones 2023 a 2025.',
            'Puntaje global en pruebas Saber 11 igual o superior a 280 puntos.',
            'Carta de postulación y motivación personal (máximo 2 páginas).',
            'Disponibilidad horaria para jornada diurna o nocturna.'
        ],
        'beneficios'        => 'Exoneración total del valor de matrícula durante toda la carrera, bono de alimentación semestral y acompañamiento pedagógico y psicosocial permanente.'
    ],
    3 => [
        'id'                => 3,
        'titulo'            => 'Pasantía técnica en Soporte y Redes',
        'empresa'           => 'Comunicaciones del Valle',
        'publicado_por'     => 'Coordinación de Prácticas',
        'categoria'         => 'Pasantía',
        'modalidad'         => 'Presencial (Medio tiempo)',
        'ubicacion'         => 'Medellín, Sector Estadio',
        'fecha_publicacion' => '2 de octubre de 2026',
        'fecha_limite'      => '25 de octubre de 2026',
        'descripcion'       => 'Empresa de telecomunicaciones y servicios de conectividad busca 2 pasantes técnicos para apoyar labores de diagnóstico de hardware, configuración de routers/switches y atención de solicitudes de soporte técnico de primer nivel.',
        'requisitos'        => [
            'Egresado de la especialidad técnica en Sistemas o Redes de la IE GAA.',
            'Conocimiento en arquitectura de computadores, cableado estructurado y sistemas operativos Windows/Linux.',
            'Excelente servicio al cliente y comunicación asertiva.'
        ],
        'beneficios'        => 'Apoyo de sostenimiento económico mensual, afiliación a ARL y posibilidad de vinculación laboral directa al finalizar el periodo de práctica.'
    ],
    4 => [
        'id'                => 4,
        'titulo'            => 'Taller de Emprendimiento y Finanzas Personales',
        'empresa'           => 'Cámara de Comercio & Red Alzatista',
        'publicado_por'     => 'Comité de Egresados Emprendedores',
        'categoria'         => 'Capacitación',
        'modalidad'         => 'Virtual (Sesiones sincrónicas los sábados)',
        'ubicacion'         => 'Plataforma Online',
        'fecha_publicacion' => '25 de septiembre de 2026',
        'fecha_limite'      => '10 de noviembre de 2026',
        'descripcion'       => 'Ciclo formativo de 24 horas académicas diseñado para brindar herramientas prácticas de modelado de negocio Canvas, costos, presupuestos, fijación de precios y estrategias de marketing digital para emprendimientos en etapa temprana.',
        'requisitos'        => [
            'Ser egresado registrado en la plataforma Raíces Alzate.',
            'Contar con una idea o proyecto de negocio en formulación o ejecución.',
            'Compromiso de asistencia al 80% de las sesiones virtuales.'
        ],
        'beneficios'        => 'Certificado de participación con valor curricular avalado por la Cámara de Comercio y acceso a mentorías individuales personalizadas.'
    ],
    5 => [
        'id'                => 5,
        'titulo'            => 'Vacante: Asistente Administrativo y Contable',
        'empresa'           => 'Soluciones Integrales del Norte',
        'publicado_por'     => 'Bolsa de Empleo Institucional',
        'categoria'         => 'Empleo',
        'modalidad'         => 'Tiempo completo (Lunes a viernes)',
        'ubicacion'         => 'Bello / Medellín',
        'fecha_publicacion' => '18 de septiembre de 2026',
        'fecha_limite'      => '5 de noviembre de 2026',
        'descripcion'       => 'Organización de servicios contables y tributarios requiere bachiller técnico comercial o contable egresado para apoyar el registro de facturación electrónica, archivo físico/digital, conciliaciones bancarias y atención telefónica de clientes.',
        'requisitos'        => [
            'Bachiller técnico comercial o egresado con formación contable básica.',
            'Manejo de Microsoft Excel intermedio (tablas dinámicas, fórmulas lógicas).',
            'Puntualidad, discreción en el manejo de información confidencial y orden documental.'
        ],
        'beneficios'        => 'Salario competitivo con prestaciones de ley, ambiente laboral colaborativo y oportunidades de ascenso a mediano plazo.'
    ],
    6 => [
        'id'                => 6,
        'titulo'            => 'Fondo de Emprendimiento Comunitario',
        'empresa'           => 'Ruta N & Alcaldía de Medellín',
        'publicado_por'     => 'Convocatorias Públicas Medellín',
        'categoria'         => 'Convocatoria',
        'modalidad'         => 'Mixta (Acompañamiento presencial y virtual)',
        'ubicacion'         => 'Medellín, Complejo Ruta N',
        'fecha_publicacion' => '10 de septiembre de 2026',
        'fecha_limite'      => '20 de noviembre de 2026',
        'descripcion'       => 'Convocatoria especial que entrega recursos no reembolsables de capital semilla (hasta $15.000.000 COP) y 6 meses de aceleración para proyectos productivos, ambientales o tecnológicos liderados por egresados de instituciones educativas oficiales de Medellín.',
        'requisitos'        => [
            'Equipo de mínimo 2 egresados de colegios oficiales de la ciudad.',
            'Producto mínimo viable (PMV) o prototipo funcional demostrado.',
            'Presentación de video pitch de 3 minutos explicando la propuesta de valor y su impacto social.'
        ],
        'beneficios'        => 'Capital semilla en especie y equipamiento, mentoría por expertos de la industria y visibilidad en ferias de innovación y emprendimiento.'
    ],
    7 => [
        'id'                => 7,
        'titulo'            => 'Curso Certificado en Ciberseguridad Básica',
        'empresa'           => 'Mintic & Alianza Educativa',
        'publicado_por'     => 'Ministerio TIC',
        'categoria'         => 'Capacitación',
        'modalidad'         => 'Virtual (Autoformación guiada)',
        'ubicacion'         => 'Online',
        'fecha_publicacion' => '5 de septiembre de 2026',
        'fecha_limite'      => '30 de noviembre de 2026',
        'descripcion'       => 'Entrenamiento intensivo en concienciación de amenazas cibernéticas, protección de datos personales, seguridad en redes domésticas y corporativas, y gestión segura de identidades digitales.',
        'requisitos'        => [
            'Egresado alzatista con interés en tecnología y seguridad de la información.',
            'Disponibilidad de computador con conexión a internet (mínimo 5 horas semanales).'
        ],
        'beneficios'        => 'Certificación oficial gratuita avalada por el Ministerio TIC y la plataforma nacional de talento digital.'
    ],
    8 => [
        'id'                => 8,
        'titulo'            => 'Pasantía en Producción Audiovisual y Redes',
        'empresa'           => 'Medios Digitales de Antioquia',
        'publicado_por'     => 'Red de Comunicaciones GAA',
        'categoria'         => 'Pasantía',
        'modalidad'         => 'Presencial',
        'ubicacion'         => 'Medellín, El Poblado',
        'fecha_publicacion' => '28 de agosto de 2026',
        'fecha_limite'      => '20 de octubre de 2026',
        'descripcion'       => 'Agencia creativa audiovisual abre plazas de práctica para egresados con destrezas en fotografía, grabación con cámara, edición de audio/video y apoyo en producción de contenidos para redes sociales institucionales.',
        'requisitos'        => [
            'Manejo de herramientas de edición (Adobe Premiere, Photoshop o DaVinci Resolve).',
            'Portafolio breve o muestras de proyectos previos realizados.',
            'Creatividad, iniciativa y sentido estético visual.'
        ],
        'beneficios'        => 'Remuneración de práctica formativa, uso de equipos profesionales de filmación y cartas de recomendación para vinculación laboral.'
    ],
    9 => [
        'id'                => 9,
        'titulo'            => 'Beca de Idiomas: Inglés para el Empleo',
        'empresa'           => 'Centro Colombo Americano & IE GAA',
        'publicado_por'     => 'Fondo de Becas Alzatistas',
        'categoria'         => 'Beca',
        'modalidad'         => 'Híbrido',
        'ubicacion'         => 'Medellín, Sede Centro',
        'fecha_publicacion' => '15 de agosto de 2026',
        'fecha_limite'      => '15 de diciembre de 2026',
        'descripcion'       => 'Beca del 80% para cursar módulos de inglés B1 y B2 con enfoque en preparación para entrevistas de trabajo internacionales, elaboración de hojas de vida en inglés y presentaciones comerciales.',
        'requisitos'        => [
            'Egresado alzatista de cualquier promoción con nivel A2 básico evaluado en prueba diagnóstica.',
            'Disponibilidad para asistir a sesiones presenciales de speaking club.'
        ],
        'beneficios'        => 'Material didáctico digital incluido, docentes nativos certificados y preparación gratuita para prueba estandarizada internacional.'
    ]
];

// Identificación de la oportunidad solicitada (por defecto ID 1 si no se envía parámetro)
$oportunidadId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$oportunidadId || !isset($oportunidadesCatalogo[$oportunidadId])) {
    $oportunidadId = 1;
}

$oportunidad = $oportunidadesCatalogo[$oportunidadId];

// 2. Inclusión del encabezado global compartido
require_once __DIR__ . '/includes/header.php';
?>

<!-- Script Anti-FOUC para transiciones suaves si el sidebar está contraído -->
<script>
    try {
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    } catch(e) {}
</script>

<link rel="stylesheet" href="assets/css/oportunidades.css">
<link rel="stylesheet" href="assets/css/oportunidad-detalle.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Sidebar compartido (con ítem Oportunidades activo) -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <!-- Contenedor principal que envuelve Topbar y Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Topbar compartido -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <main class="content-area dashboard-content detalle-oportunidad-content-wrapper" id="mainContent" tabindex="-1">
            <div class="detalle-oportunidad-container">

                <!-- Enlace de retorno en la parte superior antes de la tarjeta -->
                <nav class="detalle-oportunidad-top-nav" aria-label="Navegación de retorno al catálogo de oportunidades">
                    <a href="oportunidades.php" class="btn-back-link" id="btnBackTop">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Volver a oportunidades</span>
                    </a>
                </nav>

                <!-- Tarjeta contenedora blanca (#FFFFFF, borde #E0E0E0, radio 16px, padding 40px) -->
                <article class="detalle-oportunidad-card" aria-label="Detalle de la oportunidad: <?php echo htmlspecialchars($oportunidad['titulo']); ?>">
                    
                    <!-- 1. Encabezado de la oportunidad -->
                    <header class="detalle-oportunidad-header">
                        <div class="detalle-oportunidad-badge-row">
                            <span class="oportunidad-categoria-badge"><?php echo htmlspecialchars($oportunidad['categoria']); ?></span>
                            <span class="oportunidad-modalidad-badge"><?php echo htmlspecialchars($oportunidad['modalidad']); ?></span>
                        </div>
                        <h1 class="detalle-oportunidad-titulo"><?php echo htmlspecialchars($oportunidad['titulo']); ?></h1>
                        <div class="detalle-oportunidad-entidad-row">
                            <span class="detalle-oportunidad-empresa">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align: -2px; margin-right: 4px;">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V7l8-4v18"></path>
                                    <path d="M19 21V11l-6-4"></path>
                                    <path d="M9 9v.01"></path>
                                    <path d="M9 12v.01"></path>
                                    <path d="M9 15v.01"></path>
                                    <path d="M9 18v.01"></path>
                                </svg>
                                <?php echo htmlspecialchars($oportunidad['empresa']); ?>
                            </span>
                            <time class="detalle-oportunidad-fecha" datetime="<?php echo htmlspecialchars($oportunidad['fecha_publicacion']); ?>">
                                Publicado el <?php echo htmlspecialchars($oportunidad['fecha_publicacion']); ?>
                            </time>
                        </div>
                    </header>

                    <!-- 2. Portada/Banner panorámico (radio 10px, fondo #D9D9D9, altura 280px-340px) -->
                    <div class="detalle-oportunidad-banner" role="img" aria-label="Banner de la oportunidad">
                        <div class="detalle-oportunidad-banner-placeholder" aria-hidden="true">
                            <svg class="detalle-oportunidad-banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- 3. Sección de contenido: "Sobre esta oportunidad" + Párrafo -->
                    <section class="detalle-oportunidad-body">
                        <h2 class="detalle-oportunidad-subtitulo">Sobre esta oportunidad</h2>
                        <p class="detalle-oportunidad-parrafo"><?php echo nl2br(htmlspecialchars($oportunidad['descripcion'])); ?></p>
                        
                        <?php if (!empty($oportunidad['requisitos'])): ?>
                        <div class="detalle-oportunidad-requisitos-box">
                            <h3 class="detalle-oportunidad-requisitos-title">Requisitos y perfil deseado</h3>
                            <ul class="detalle-oportunidad-requisitos-list">
                                <?php foreach ((array)$oportunidad['requisitos'] as $req): ?>
                                    <li><?php echo htmlspecialchars($req); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($oportunidad['beneficios'])): ?>
                        <div style="margin-top: 12px;">
                            <h3 class="detalle-oportunidad-subtitulo" style="font-size: 16px; margin-bottom: 8px;">Beneficios ofrecidos</h3>
                            <p class="detalle-oportunidad-parrafo"><?php echo nl2br(htmlspecialchars($oportunidad['beneficios'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </section>

                    <!-- 4. Ficha de datos (Grid de 3 columnas simétricas) -->
                    <div class="detalle-oportunidad-meta-grid" role="region" aria-label="Datos de la convocatoria">
                        <div class="detalle-oportunidad-meta-item">
                            <span class="detalle-oportunidad-meta-label">Publicado por</span>
                            <span class="detalle-oportunidad-meta-valor"><?php echo htmlspecialchars($oportunidad['publicado_por']); ?></span>
                        </div>
                        <div class="detalle-oportunidad-meta-item">
                            <span class="detalle-oportunidad-meta-label">Modalidad / Ubicación</span>
                            <span class="detalle-oportunidad-meta-valor"><?php echo htmlspecialchars($oportunidad['ubicacion']); ?></span>
                        </div>
                        <div class="detalle-oportunidad-meta-item">
                            <span class="detalle-oportunidad-meta-label">Fecha límite de postulación</span>
                            <span class="detalle-oportunidad-meta-valor" style="color: #D32F2F;"><?php echo htmlspecialchars($oportunidad['fecha_limite']); ?></span>
                        </div>
                    </div>

                    <!-- 5. Botón inferior: "Volver a oportunidades" (Outline azul #1565C0, hover azul tenue transparente, NUNCA rojo) -->
                    <div class="detalle-oportunidad-actions">
                        <a href="oportunidades.php" class="btn-volver-oportunidades" id="btnVolverOportunidades">
                            <svg class="btn-volver-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            <span>Volver a oportunidades</span>
                        </a>
                    </div>

                </article>

            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
