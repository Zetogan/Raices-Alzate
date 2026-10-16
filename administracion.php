<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Panel de Administración y Moderación (08 - Administración - Desktop y Mobile)
 * Arquitectura: Frontend Puro (CERO bases de datos, CERO endpoints AJAX)
 * Paleta Oficial: #D32F2F (Rojo Alzate), #1565C0 (Azul Alzate), #212121, #757575, #E0E0E0, #F5F5F5, #FFFFFF
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// 1. Simulación segura de sesión administrativa para vista previa y desarrollo
if (empty($_SESSION['user']) && empty($_SESSION['usuario'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'nombre' => 'Santiago Alzate',
        'email' => 'admin@alzate.edu.co',
        'rol' => 'Administrador'
    ];
}

$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Administrador');
$userRole = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Administrador');
$userEmail = trim($user['email'] ?? 'admin@alzate.edu.co');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'A';

// 2. Metadatos de la vista para layouts compartidos
$seccion_activa = 'administracion';
$currentPage = 'administracion';
$titulo_seccion = 'Administración';
$titulo_pagina = 'Panel de Administración — Raíces Alzate';
$pageTitle = $titulo_pagina;
$topbarTitle = $titulo_seccion;

// Hojas de estilo y scripts requeridos
$extraCss = ['dashboard.css', 'administracion.css'];
$extraJs = ['assets/js/dashboard.js'];

// 3. DATOS ESTÁTICOS OFICIALES (CERO BASE DE DATOS)

// A. Métricas Generales (KPIs)
$metricas = [
    'solicitudes_pendientes' => 8,
    'contenidos_revision' => 5,
    'pqrs_activas' => 4,
    'egresados_totales' => 1284,
];

// B. Solicitudes de Registro de Egresados
$solicitudesRegistro = [
    [
        'id' => 1,
        'nombre' => 'Esteban Morales Salazar',
        'documento' => '1017234891',
        'promocion' => '2021',
        'titulo' => 'Bachiller Académico',
        'email' => 'esteban.morales@ejemplo.com',
        'telefono' => '+57 312 456 7890',
        'fecha' => '14 Oct 2026',
        'estado' => 'Pendiente',
        'avatar' => 'E',
        'color' => '#1565C0',
        'observaciones' => 'Adjunta acta de grado número 4512 y cédula de ciudadanía escaneada.'
    ],
    [
        'id' => 2,
        'nombre' => 'Mariana Gómez Valencia',
        'documento' => '1020456123',
        'promocion' => '2019',
        'titulo' => 'Técnico en Sistemas y Redes',
        'email' => 'mariana.gomez@ejemplo.com',
        'telefono' => '+57 300 987 6543',
        'fecha' => '13 Oct 2026',
        'estado' => 'Pendiente',
        'avatar' => 'M',
        'color' => '#D32F2F',
        'observaciones' => 'Solicitud de verificación con doble titulación SENA articulada.'
    ],
    [
        'id' => 3,
        'nombre' => 'Julián David Castro',
        'documento' => '1035789456',
        'promocion' => '2018',
        'titulo' => 'Bachiller Técnico Comercial',
        'email' => 'julian.castro@ejemplo.com',
        'telefono' => '+57 315 222 3344',
        'fecha' => '12 Oct 2026',
        'estado' => 'Pendiente',
        'avatar' => 'J',
        'color' => '#2E7D32',
        'observaciones' => 'Validación de registro para creación de proyecto institucional.'
    ],
    [
        'id' => 4,
        'nombre' => 'Carolina Zapata Ríos',
        'documento' => '1018901234',
        'promocion' => '2022',
        'titulo' => 'Bachiller Académico',
        'email' => 'carolina.zapata@ejemplo.com',
        'telefono' => '+57 318 777 8899',
        'fecha' => '11 Oct 2026',
        'estado' => 'Aprobado',
        'avatar' => 'C',
        'color' => '#6A1B9A',
        'observaciones' => 'Aprobada el 12 de octubre por Coordinación Académica.'
    ],
    [
        'id' => 5,
        'nombre' => 'Andrés Felipe Ruiz',
        'documento' => '1042345678',
        'promocion' => '2015',
        'titulo' => 'Ingeniero de Software (UdeA)',
        'email' => 'andres.ruiz@ejemplo.com',
        'telefono' => '+57 301 555 6677',
        'fecha' => '09 Oct 2026',
        'estado' => 'Aprobado',
        'avatar' => 'A',
        'color' => '#EF6C00',
        'observaciones' => 'Egresado verificado con vinculación laboral destacada.'
    ],
    [
        'id' => 6,
        'nombre' => 'Sara Lucía Bermúdez',
        'documento' => '1023889012',
        'promocion' => '2023',
        'titulo' => 'Bachiller Académico',
        'email' => 'sara.bermudez@ejemplo.com',
        'telefono' => '+57 320 111 4455',
        'fecha' => '08 Oct 2026',
        'estado' => 'Rechazado',
        'avatar' => 'S',
        'color' => '#757575',
        'observaciones' => 'Documento ilegible. Se solicitó reenvío de documento oficial.'
    ]
];

// C. Moderación de Contenidos (Noticias, Oportunidades y Proyectos)
$contenidosModeracion = [
    [
        'id' => 101,
        'tipo' => 'Noticia',
        'titulo' => 'Gran Feria de Emprendimiento y Talento Alzatista 2026',
        'autor' => 'Comité de Egresados',
        'categoria' => 'Eventos',
        'fecha' => '15 Oct 2026',
        'estado' => 'Pendiente',
        'resumen' => 'Invitación a todos los egresados a presentar sus iniciativas comerciales en la sede principal durante la semana institucional.',
        'contenido_completo' => 'La Institución Educativa Gilberto Alzate Avendaño abre sus puertas a toda la comunidad de egresados para la Gran Feria de Emprendimiento 2026. Habrá stands para 30 proyectos, ruedas de contacto con empresarios del sector y conferencias sobre financiamiento solidario.',
        'badge_class' => 'badge-blue'
    ],
    [
        'id' => 102,
        'tipo' => 'Oportunidad',
        'titulo' => 'Convocatoria para Desarrollador Frontend Junior',
        'autor' => 'Innovatech Medellín (Carlos Morales)',
        'categoria' => 'Laboral',
        'fecha' => '14 Oct 2026',
        'estado' => 'Pendiente',
        'resumen' => 'Empresa aliada de egresados busca talentos con conocimientos en HTML5, CSS3, JavaScript y pasión por el diseño accesible.',
        'contenido_completo' => 'Se requiere persona proactiva para integración al equipo de producto. Modalidad híbrida en Medellín, contrato a término indefinido y plan de beneficios académicos continuos.',
        'badge_class' => 'badge-purple'
    ],
    [
        'id' => 103,
        'tipo' => 'Proyecto',
        'titulo' => 'Plataforma de Tutorías Solidarias para Primaria',
        'autor' => 'Valentina Ríos Gómez',
        'categoria' => 'Educación',
        'fecha' => '13 Oct 2026',
        'estado' => 'Pendiente',
        'resumen' => 'Iniciativa de mentoría liderada por egresados de licenciaturas para apoyar a estudiantes de básica primaria en comprensión lectora.',
        'contenido_completo' => 'El proyecto busca articular a 20 egresados voluntarios para brindar 2 horas semanales de refuerzo escolar personalizado a niños de 3° a 5° en sedes alternas.',
        'badge_class' => 'badge-orange'
    ],
    [
        'id' => 104,
        'tipo' => 'Noticia',
        'titulo' => 'Remodelación de los Laboratorios de Ciencia y Robótica',
        'autor' => 'Rectoría Institucional',
        'categoria' => 'Infraestructura',
        'fecha' => '10 Oct 2026',
        'estado' => 'Publicado',
        'resumen' => 'Culminó con éxito la entrega de nuevos equipos tecnológicos y microscopios de alta resolución para los estudiantes.',
        'contenido_completo' => 'Con inversión del fondo distrital y aportes de egresados benefactores, se modernizaron 3 aulas especializadas para la experimentación científica y robótica.',
        'badge_class' => 'badge-green'
    ],
    [
        'id' => 105,
        'tipo' => 'Oportunidad',
        'titulo' => 'Beca del 100% en Diplomado de Gestión de Proyectos',
        'autor' => 'Fundación Alzatista',
        'categoria' => 'Becas',
        'fecha' => '07 Oct 2026',
        'estado' => 'Publicado',
        'resumen' => 'Cinco cupos directos para egresados graduados entre 2018 y 2024 que lideren iniciativas comunitarias.',
        'contenido_completo' => 'El diplomado tiene certificación universitaria avalada por el Ministerio de Educación. Formación 100% virtual sincrónica.',
        'badge_class' => 'badge-green'
    ]
];

// D. Gestión de PQRS y Reportes Técnicos
$pqrsLista = [
    [
        'radicado' => 'RA-2026-089',
        'tipo' => 'Reporte de Error',
        'remitente' => 'Santiago Emitola',
        'correo' => 'santiago@ejemplo.com',
        'asunto' => 'No carga la imagen del certificado en mi perfil',
        'fecha' => '15 Oct 2026',
        'estado' => 'Pendiente',
        'prioridad' => 'Alta',
        'descripcion' => 'Al intentar subir mi soporte de grado en formato PNG de 2MB, la zona de dropzone no procesa la previsualización y se queda congelada.',
        'respuesta' => ''
    ],
    [
        'radicado' => 'RA-2026-088',
        'tipo' => 'Petición',
        'remitente' => 'Camila Zuluaga Henao',
        'correo' => 'camila.z@ejemplo.com',
        'asunto' => 'Solicitud de certificado de egresado año 2020',
        'fecha' => '14 Oct 2026',
        'estado' => 'Pendiente',
        'prioridad' => 'Media',
        'descripcion' => 'Requiero con urgencia la copia de mi acta de grado y certificación de egresada para trámite de matrícula universitaria en EAFIT.',
        'respuesta' => ''
    ],
    [
        'radicado' => 'RA-2026-087',
        'tipo' => 'Reclamo',
        'remitente' => 'Mateo Henao Castro',
        'correo' => 'mateo.h@ejemplo.com',
        'asunto' => 'Error en la fecha de mi promoción registrada',
        'fecha' => '13 Oct 2026',
        'estado' => 'Pendiente',
        'prioridad' => 'Baja',
        'descripcion' => 'En mi perfil público aparece promoción 2017 pero soy egresado legítimo de la promoción 2018. Adjunto copia de mi carnet escolar.',
        'respuesta' => ''
    ],
    [
        'radicado' => 'RA-2026-086',
        'tipo' => 'Felicitación',
        'remitente' => 'Laura Restrepo Silva',
        'correo' => 'laura.restrepo@ejemplo.com',
        'asunto' => 'Felicitaciones por la nueva plataforma Raíces Alzate',
        'fecha' => '11 Oct 2026',
        'estado' => 'Resuelto',
        'prioridad' => 'Normal',
        'descripcion' => 'Excelente iniciativa para mantenernos en contacto y enterarnos de los proyectos que lideran los compañeros de distintas promociones.',
        'respuesta' => 'Agradecemos tu mensaje, Laura. Nos llena de orgullo saber que la plataforma te resulta de gran valor.'
    ]
];

// Carga del layout compartido (encabezado HTML y carga de CSS)
require_once __DIR__ . '/includes/header.php';
?>

<!-- Estilos específicos del Dashboard y Administración -->
<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/administracion.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Barra Lateral Privada -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <!-- Área Principal de Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Barra Superior (Topbar) -->
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <!-- Contenido Central del Panel Administrativo -->
        <main class="content-area dashboard-content admin-content-area" id="mainContent" tabindex="-1">
            
            <!-- 1. Cabecera y Bienvenida Administrativa -->
            <div class="admin-header-banner">
                <div class="admin-header-text">
                    <span class="admin-badge-pill">Gestión y Moderación Oficial</span>
                    <h1 class="admin-page-title">Panel de Administración</h1>
                    <p class="admin-page-subtitle">
                        Supervisa y aprueba solicitudes de nuevos egresados, modera noticias u oportunidades y atiende las peticiones y reportes ciudadanos.
                    </p>
                </div>
                <div class="admin-header-actions">
                    <a href="crear-proyecto.php" class="admin-btn admin-btn-outline" title="Crear nuevo proyecto institucional">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Nuevo Proyecto</span>
                    </a>
                    <button type="button" class="admin-btn admin-btn-primary" id="btnExportarReporte" onclick="exportarReporteAdmin()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Descargar Reporte</span>
                    </button>
                </div>
            </div>

            <!-- 2. Grid de Métricas / Tarjetas KPI Clave -->
            <section class="admin-kpi-grid" aria-label="Métricas del sistema">
                <!-- KPI 1: Solicitudes de Registro -->
                <article class="admin-kpi-card kpi-blue">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Registros Pendientes</span>
                        <h2 class="kpi-value" id="kpiRegistros"><?= $metricas['solicitudes_pendientes'] ?></h2>
                        <span class="kpi-subtext kpi-subtext-alert">+3 recibidas en las últimas 24h</span>
                    </div>
                </article>

                <!-- KPI 2: Contenidos por Moderar -->
                <article class="admin-kpi-card kpi-orange">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Contenidos en Revisión</span>
                        <h2 class="kpi-value" id="kpiContenidos"><?= $metricas['contenidos_revision'] ?></h2>
                        <span class="kpi-subtext">Noticias, Oportunidades y Proyectos</span>
                    </div>
                </article>

                <!-- KPI 3: PQRS & Reportes Activos -->
                <article class="admin-kpi-card kpi-red">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">PQRS por Resolver</span>
                        <h2 class="kpi-value" id="kpiPQRS"><?= $metricas['pqrs_activas'] ?></h2>
                        <span class="kpi-subtext kpi-subtext-alert">1 reporte técnico prioritario</span>
                    </div>
                </article>

                <!-- KPI 4: Total de Egresados Activos -->
                <article class="admin-kpi-card kpi-green">
                    <div class="kpi-icon-wrap" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Egresados Verificados</span>
                        <h2 class="kpi-value" id="kpiEgresados"><?= number_format($metricas['egresados_totales']) ?></h2>
                        <span class="kpi-subtext">98.5% validados institucionalmente</span>
                    </div>
                </article>
            </section>

            <!-- 3. Navegación Modular por Pestañas (Tabs) -->
            <div class="admin-tabs-container">
                <div class="admin-tabs-list" role="tablist" aria-label="Secciones del panel de administración">
                    <button type="button" class="admin-tab-btn active" role="tab" id="tabBtnRegistros" aria-selected="true" aria-controls="panelRegistros" onclick="cambiarPestana('registros')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Aprobación de Registros</span>
                        <span class="tab-counter-badge" id="badgeCountRegistros">3</span>
                    </button>

                    <button type="button" class="admin-tab-btn" role="tab" id="tabBtnContenidos" aria-selected="false" aria-controls="panelContenidos" onclick="cambiarPestana('contenidos')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path>
                        </svg>
                        <span>Moderación de Contenidos</span>
                        <span class="tab-counter-badge" id="badgeCountContenidos">3</span>
                    </button>

                    <button type="button" class="admin-tab-btn" role="tab" id="tabBtnPQRS" aria-selected="false" aria-controls="panelPQRS" onclick="cambiarPestana('pqrs')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span>Resolución de PQRS</span>
                        <span class="tab-counter-badge" id="badgeCountPQRS">3</span>
                    </button>
                </div>
            </div>

            <!-- 4. Barra de Búsqueda y Filtros de la Tabla Activa -->
            <div class="admin-table-toolbar">
                <div class="admin-search-wrap">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="adminSearchInput" class="admin-search-input" placeholder="Buscar por nombre, documento, título, radicado o correo..." oninput="filtrarTablaActual()">
                    <button type="button" id="adminClearSearch" class="search-clear-btn" style="display: none;" onclick="limpiarBusqueda()">✕</button>
                </div>

                <div class="admin-filter-group">
                    <label for="adminStatusFilter" class="filter-label">Estado:</label>
                    <select id="adminStatusFilter" class="admin-select" onchange="filtrarTablaActual()">
                        <option value="todos">Todos los estados</option>
                        <option value="Pendiente" selected>Solo Pendientes</option>
                        <option value="Aprobado">Aprobados / Publicados</option>
                        <option value="Resuelto">Resueltos</option>
                        <option value="Rechazado">Rechazados</option>
                    </select>

                    <span class="admin-results-counter" id="adminResultsCounter">Mostrando 3 de 6 registros</span>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 5. PANEL 1: SOLICITUDES DE REGISTRO                           -->
            <!-- ============================================================== -->
            <div class="admin-panel active" id="panelRegistros" role="tabpanel" aria-labelledby="tabBtnRegistros">
                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaRegistros">
                            <thead>
                                <tr>
                                    <th>Egresado / Solicitante</th>
                                    <th>Documento</th>
                                    <th>Promoción</th>
                                    <th>Título Obtenido</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudesRegistro as $sol): ?>
                                <tr class="admin-table-row" id="row-registro-<?= $sol['id'] ?>" 
                                    data-id="<?= $sol['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($sol['nombre']) ?>"
                                    data-documento="<?= htmlspecialchars($sol['documento']) ?>"
                                    data-promocion="<?= htmlspecialchars($sol['promocion']) ?>"
                                    data-titulo="<?= htmlspecialchars($sol['titulo']) ?>"
                                    data-email="<?= htmlspecialchars($sol['email']) ?>"
                                    data-telefono="<?= htmlspecialchars($sol['telefono']) ?>"
                                    data-observaciones="<?= htmlspecialchars($sol['observaciones']) ?>"
                                    data-estado="<?= $sol['estado'] ?>"
                                    data-fecha="<?= $sol['fecha'] ?>">
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-avatar" style="background-color: <?= $sol['color'] ?>;">
                                                <?= $sol['avatar'] ?>
                                            </div>
                                            <div class="user-cell-info">
                                                <span class="user-cell-name"><?= htmlspecialchars($sol['nombre']) ?></span>
                                                <span class="user-cell-email"><?= htmlspecialchars($sol['email']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code class="doc-code"><?= htmlspecialchars($sol['documento']) ?></code></td>
                                    <td><span class="promo-badge"><?= htmlspecialchars($sol['promocion']) ?></span></td>
                                    <td class="text-truncate-cell"><?= htmlspecialchars($sol['titulo']) ?></td>
                                    <td><?= htmlspecialchars($sol['fecha']) ?></td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($sol['estado']) ?>">
                                            <?= $sol['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud completa" onclick="abrirModalRegistro(<?= $sol['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span>Revisar</span>
                                            </button>
                                            <?php if ($sol['estado'] === 'Pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Aprobar registro inmediatamente" onclick="aprobarRegistroDirecto(<?= $sol['id'] ?>, '<?= htmlspecialchars(addslashes($sol['nombre'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                            <button type="button" class="btn-action-sm btn-action-reject" title="Rechazar solicitud" onclick="rechazarRegistroDirecto(<?= $sol['id'] ?>, '<?= htmlspecialchars(addslashes($sol['nombre'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyRegistros" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>No se encontraron solicitudes con el criterio de búsqueda seleccionado.</p>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 6. PANEL 2: MODERACIÓN DE CONTENIDOS                          -->
            <!-- ============================================================== -->
            <div class="admin-panel" id="panelContenidos" role="tabpanel" aria-labelledby="tabBtnContenidos">
                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaContenidos">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Título del Contenido</th>
                                    <th>Autor / Remitente</th>
                                    <th>Categoría</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contenidosModeracion as $item): ?>
                                <tr class="admin-table-row" id="row-contenido-<?= $item['id'] ?>"
                                    data-id="<?= $item['id'] ?>"
                                    data-tipo="<?= htmlspecialchars($item['tipo']) ?>"
                                    data-titulo="<?= htmlspecialchars($item['titulo']) ?>"
                                    data-autor="<?= htmlspecialchars($item['autor']) ?>"
                                    data-categoria="<?= htmlspecialchars($item['categoria']) ?>"
                                    data-resumen="<?= htmlspecialchars($item['resumen']) ?>"
                                    data-contenido="<?= htmlspecialchars($item['contenido_completo']) ?>"
                                    data-estado="<?= $item['estado'] ?>"
                                    data-fecha="<?= $item['fecha'] ?>">
                                    <td>
                                        <span class="type-pill type-<?= strtolower($item['tipo']) ?>">
                                            <?= htmlspecialchars($item['tipo']) ?>
                                        </span>
                                    </td>
                                    <td class="font-semibold-cell">
                                        <div class="content-title-cell">
                                            <span><?= htmlspecialchars($item['titulo']) ?></span>
                                            <p class="content-desc-sub"><?= htmlspecialchars($item['resumen']) ?></p>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($item['autor']) ?></td>
                                    <td><span class="category-badge"><?= htmlspecialchars($item['categoria']) ?></span></td>
                                    <td><?= htmlspecialchars($item['fecha']) ?></td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($item['estado']) ?>">
                                            <?= $item['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Examinar y publicar" onclick="abrirModalContenido(<?= $item['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span>Moderar</span>
                                            </button>
                                            <?php if ($item['estado'] === 'Pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Aprobar y publicar en el portal" onclick="publicarContenidoDirecto(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                            <button type="button" class="btn-action-sm btn-action-reject" title="Rechazar contenido" onclick="rechazarContenidoDirecto(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyContenidos" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>No se encontraron publicaciones con los filtros aplicados.</p>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 7. PANEL 3: RESOLUCIÓN DE PQRS Y REPORTES                     -->
            <!-- ============================================================== -->
            <div class="admin-panel" id="panelPQRS" role="tabpanel" aria-labelledby="tabBtnPQRS">
                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaPQRS">
                            <thead>
                                <tr>
                                    <th>Radicado</th>
                                    <th>Tipo de Caso</th>
                                    <th>Remitente</th>
                                    <th>Asunto</th>
                                    <th>Fecha</th>
                                    <th>Prioridad</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pqrsLista as $pq): ?>
                                <tr class="admin-table-row" id="row-pqrs-<?= $pq['radicado'] ?>"
                                    data-id="<?= $pq['radicado'] ?>"
                                    data-radicado="<?= htmlspecialchars($pq['radicado']) ?>"
                                    data-tipo="<?= htmlspecialchars($pq['tipo']) ?>"
                                    data-remitente="<?= htmlspecialchars($pq['remitente']) ?>"
                                    data-correo="<?= htmlspecialchars($pq['correo']) ?>"
                                    data-asunto="<?= htmlspecialchars($pq['asunto']) ?>"
                                    data-prioridad="<?= htmlspecialchars($pq['prioridad']) ?>"
                                    data-descripcion="<?= htmlspecialchars($pq['descripcion']) ?>"
                                    data-respuesta="<?= htmlspecialchars($pq['respuesta']) ?>"
                                    data-estado="<?= $pq['estado'] ?>"
                                    data-fecha="<?= $pq['fecha'] ?>">
                                    <td><code class="radicado-code"><?= htmlspecialchars($pq['radicado']) ?></code></td>
                                    <td>
                                        <span class="type-pill type-<?= strtolower(str_replace(' ', '-', $pq['tipo'])) ?>">
                                            <?= htmlspecialchars($pq['tipo']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-info">
                                                <span class="user-cell-name"><?= htmlspecialchars($pq['remitente']) ?></span>
                                                <span class="user-cell-email"><?= htmlspecialchars($pq['correo']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-semibold-cell"><?= htmlspecialchars($pq['asunto']) ?></td>
                                    <td><?= htmlspecialchars($pq['fecha']) ?></td>
                                    <td>
                                        <span class="priority-badge priority-<?= strtolower($pq['prioridad']) ?>">
                                            <?= htmlspecialchars($pq['prioridad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($pq['estado']) ?>">
                                            <?= $pq['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Atender y responder caso" onclick="abrirModalPQRS('<?= $pq['radicado'] ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                                <span>Responder</span>
                                            </button>
                                            <?php if ($pq['estado'] === 'Pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Marcar como resuelto" onclick="resolverPQRSDirecto('<?= $pq['radicado'] ?>', '<?= htmlspecialchars(addslashes($pq['remitente'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyPQRS" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>No se encontraron casos de PQRS con el filtro aplicado.</p>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 8. MODAL 1: REVISIÓN Y APROBACIÓN DE REGISTRO                           -->
<!-- ======================================================================= -->
<div class="admin-modal-backdrop" id="modalRegistro" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalRegistroTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <polyline points="17 11 19 13 23 9"></polyline>
                </svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalRegistroTitle">Verificación de Registro de Egresado</h3>
                <span class="admin-modal-subtitle">Revisa la documentación y autoriza el acceso a la plataforma</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="modal-user-hero">
                <div class="modal-user-avatar" id="modalRegAvatar">E</div>
                <div class="modal-user-hero-info">
                    <h4 class="modal-user-name" id="modalRegNombre">Esteban Morales Salazar</h4>
                    <span class="modal-user-email" id="modalRegEmail">esteban.morales@ejemplo.com</span>
                    <div class="modal-user-badges">
                        <span class="status-pill status-pendiente" id="modalRegEstadoBadge">Pendiente</span>
                        <span class="promo-badge" id="modalRegPromocionBadge">Promoción 2021</span>
                    </div>
                </div>
            </div>

            <div class="admin-detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Documento de Identidad</span>
                    <span class="detail-value" id="modalRegDocumento">1017234891</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Teléfono de Contacto</span>
                    <span class="detail-value" id="modalRegTelefono">+57 312 456 7890</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Título Académico</span>
                    <span class="detail-value" id="modalRegTitulo">Bachiller Académico</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de Solicitud</span>
                    <span class="detail-value" id="modalRegFecha">14 Oct 2026</span>
                </div>
            </div>

            <div class="modal-observations-box">
                <span class="obs-title">Observaciones o Soportes Adjuntos:</span>
                <p class="obs-text" id="modalRegObservaciones">
                    Adjunta acta de grado número 4512 y cédula de ciudadanía escaneada para verificación oficial.
                </p>
            </div>

            <div class="modal-form-group">
                <label for="adminObservacionInput" class="modal-input-label">Comentarios o nota de resolución (opcional):</label>
                <textarea id="adminObservacionInput" class="admin-textarea" rows="2" placeholder="Ej: Registro verificado con secretaría académica..."></textarea>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-danger" id="btnModalRechazarReg" onclick="rechazarRegistroDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Rechazar Solicitud</span>
            </button>
            <button type="button" class="btn-modal-success" id="btnModalAprobarReg" onclick="aprobarRegistroDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Aprobar Registro</span>
            </button>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 9. MODAL 2: MODERACIÓN Y PUBLICACIÓN DE CONTENIDOS                      -->
<!-- ======================================================================= -->
<div class="admin-modal-backdrop" id="modalContenido" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalContenidoTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap icon-orange">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E65100" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                </svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalContenidoTitle">Moderación de Publicación</h3>
                <span class="admin-modal-subtitle">Valida la veracidad y calidad antes de publicar en el portal público</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="modal-content-meta">
                <span class="type-pill type-noticia" id="modalContTipo">Noticia</span>
                <span class="category-badge" id="modalContCategoria">Eventos</span>
                <span class="status-pill status-pendiente" id="modalContEstado">Pendiente</span>
            </div>

            <h4 class="modal-content-title" id="modalContTitulo">Gran Feria de Emprendimiento y Talento Alzatista 2026</h4>
            
            <div class="modal-author-row">
                <span class="author-label">Publicado por:</span>
                <strong id="modalContAutor">Comité de Egresados</strong>
                <span class="author-sep">•</span>
                <span id="modalContFecha">15 Oct 2026</span>
            </div>

            <div class="modal-content-preview-box">
                <span class="box-label">Cuerpo de la Publicación:</span>
                <p class="preview-full-text" id="modalContCompleto">
                    La Institución Educativa Gilberto Alzate Avendaño abre sus puertas a toda la comunidad de egresados para la Gran Feria de Emprendimiento 2026. Habrá stands para 30 proyectos, ruedas de contacto con empresarios del sector y conferencias sobre financiamiento solidario.
                </p>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cerrar</button>
            <button type="button" class="btn-modal-danger" id="btnModalRechazarCont" onclick="rechazarContenidoDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Rechazar</span>
            </button>
            <button type="button" class="btn-modal-success" id="btnModalPublicarCont" onclick="publicarContenidoDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Aprobar y Publicar</span>
            </button>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 10. MODAL 3: RESOLUCIÓN Y RESPUESTA DE PQRS                             -->
<!-- ======================================================================= -->
<div class="admin-modal-backdrop" id="modalPQRS" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalPQRSTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap icon-red">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalPQRSTitle">Atención y Resolución de PQRS</h3>
                <span class="admin-modal-subtitle">Radicado Oficial: <strong id="modalPQRSRadicadoCode">RA-2026-089</strong></span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="pqrs-info-banner">
                <div class="pqrs-meta-pill-group">
                    <span class="type-pill type-reporte-de-error" id="modalPQRSTipo">Reporte de Error</span>
                    <span class="priority-badge priority-alta" id="modalPQRSPrioridad">Prioridad Alta</span>
                    <span class="status-pill status-pendiente" id="modalPQRSEstado">Pendiente</span>
                </div>
                <div class="pqrs-citizen-info">
                    <span class="citizen-name" id="modalPQRSRemitente">Santiago Emitola</span>
                    <span class="citizen-email" id="modalPQRSCorreo">santiago@ejemplo.com</span>
                    <span class="citizen-date" id="modalPQRSFecha">15 Oct 2026</span>
                </div>
            </div>

            <div class="modal-content-preview-box">
                <span class="box-label">Asunto:</span>
                <strong class="pqrs-subject-text" id="modalPQRSAsunto">No carga la imagen del certificado en mi perfil</strong>
                <span class="box-label" style="margin-top: 10px;">Detalle o Descripción de la Solicitud:</span>
                <p class="preview-full-text" id="modalPQRSDescripcion">
                    Al intentar subir mi soporte de grado en formato PNG de 2MB, la zona de dropzone no procesa la previsualización y se queda congelada.
                </p>
            </div>

            <div class="modal-form-group">
                <label for="adminRespuestaPQRS" class="modal-input-label">
                    Respuesta Institucional Oficial <span class="required-mark">*</span>:
                </label>
                <textarea id="adminRespuestaPQRS" class="admin-textarea" rows="4" placeholder="Escribe aquí la respuesta oficial que se enviará al correo del solicitante y quedará registrada en el radicado..."></textarea>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-success" id="btnModalEnviarPQRS" onclick="enviarRespuestaPQRSDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                <span>Enviar Respuesta y Resolver</span>
            </button>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 11. NOTIFICACIÓN FLOTANTE (TOAST ADMINISTRATIVO)                        -->
<!-- ======================================================================= -->
<div class="admin-toast-notification" id="adminToast" role="alert" aria-live="assertive">
    <div class="admin-toast-icon-wrap" id="adminToastIcon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>
    <div class="admin-toast-content">
        <strong class="admin-toast-title" id="adminToastTitle">Acción completada</strong>
        <p class="admin-toast-desc" id="adminToastDesc">La operación administrativa se realizó con éxito.</p>
    </div>
    <button type="button" class="admin-toast-close" onclick="ocultarToastAdmin()" aria-label="Cerrar notificación">&times;</button>
</div>

<!-- ======================================================================= -->
<!-- 12. LOGICA JAVASCRIPT MODULAR Y ACCESIBLE (VANILLA JS PURO)              -->
<!-- ======================================================================= -->
<script>
// Estado administrativo local
let activeTab = 'registros';
let currentModalEntityId = null;
let toastTimeout = null;

// A. Cambio de Pestañas
function cambiarPestana(tabName) {
    activeTab = tabName;
    document.querySelectorAll('.admin-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    document.querySelectorAll('.admin-panel').forEach(panel => {
        panel.classList.remove('active');
    });

    if (tabName === 'registros') {
        document.getElementById('tabBtnRegistros').classList.add('active');
        document.getElementById('tabBtnRegistros').setAttribute('aria-selected', 'true');
        document.getElementById('panelRegistros').classList.add('active');
    } else if (tabName === 'contenidos') {
        document.getElementById('tabBtnContenidos').classList.add('active');
        document.getElementById('tabBtnContenidos').setAttribute('aria-selected', 'true');
        document.getElementById('panelContenidos').classList.add('active');
    } else if (tabName === 'pqrs') {
        document.getElementById('tabBtnPQRS').classList.add('active');
        document.getElementById('tabBtnPQRS').setAttribute('aria-selected', 'true');
        document.getElementById('panelPQRS').classList.add('active');
    }

    filtrarTablaActual();
}

// B. Filtros de Búsqueda y Estado en Tiempo Real
function filtrarTablaActual() {
    const query = document.getElementById('adminSearchInput').value.toLowerCase().trim();
    const statusFilter = document.getElementById('adminStatusFilter').value;
    const clearBtn = document.getElementById('adminClearSearch');

    if (clearBtn) {
        clearBtn.style.display = query.length > 0 ? 'inline-flex' : 'none';
    }

    let currentTableId = 'tablaRegistros';
    let emptyNoticeId = 'emptyRegistros';
    if (activeTab === 'contenidos') {
        currentTableId = 'tablaContenidos';
        emptyNoticeId = 'emptyContenidos';
    } else if (activeTab === 'pqrs') {
        currentTableId = 'tablaPQRS';
        emptyNoticeId = 'emptyPQRS';
    }

    const table = document.getElementById(currentTableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr.admin-table-row');
    let visibleCount = 0;
    const totalCount = rows.length;

    rows.forEach(row => {
        const textContent = row.textContent.toLowerCase();
        const rowStatus = row.getAttribute('data-estado') || '';

        const matchesQuery = query === '' || textContent.includes(query);
        const matchesStatus = (statusFilter === 'todos') || (rowStatus.toLowerCase() === statusFilter.toLowerCase());

        if (matchesQuery && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Actualizar contador
    const counterEl = document.getElementById('adminResultsCounter');
    if (counterEl) {
        counterEl.textContent = `Mostrando ${visibleCount} de ${totalCount} registros`;
    }

    // Estado vacío
    const emptyNotice = document.getElementById(emptyNoticeId);
    if (emptyNotice) {
        emptyNotice.style.display = visibleCount === 0 ? 'flex' : 'none';
    }
}

function limpiarBusqueda() {
    document.getElementById('adminSearchInput').value = '';
    filtrarTablaActual();
    document.getElementById('adminSearchInput').focus();
}

// C. Control de Modales
function cerrarModales() {
    document.querySelectorAll('.admin-modal-backdrop').forEach(modal => {
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
    });
    currentModalEntityId = null;
}

// Cerrar con Escape o clic en backdrop
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModales();
    }
});

document.querySelectorAll('.admin-modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModales();
        }
    });
});

// D. Modal 1: Registro de Egresado
function abrirModalRegistro(id) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    currentModalEntityId = id;
    const nombre = row.getAttribute('data-nombre');
    const doc = row.getAttribute('data-documento');
    const promo = row.getAttribute('data-promocion');
    const titulo = row.getAttribute('data-titulo');
    const email = row.getAttribute('data-email');
    const tel = row.getAttribute('data-telefono');
    const fecha = row.getAttribute('data-fecha');
    const estado = row.getAttribute('data-estado');
    const obs = row.getAttribute('data-observaciones');

    document.getElementById('modalRegAvatar').textContent = nombre.charAt(0);
    document.getElementById('modalRegNombre').textContent = nombre;
    document.getElementById('modalRegEmail').textContent = email;
    document.getElementById('modalRegDocumento').textContent = doc;
    document.getElementById('modalRegTelefono').textContent = tel;
    document.getElementById('modalRegTitulo').textContent = titulo;
    document.getElementById('modalRegFecha').textContent = fecha;
    document.getElementById('modalRegPromocionBadge').textContent = `Promoción ${promo}`;
    document.getElementById('modalRegObservaciones').textContent = obs;

    const badge = document.getElementById('modalRegEstadoBadge');
    badge.className = `status-pill status-${estado.toLowerCase()}`;
    badge.textContent = estado;

    document.getElementById('adminObservacionInput').value = '';

    const modal = document.getElementById('modalRegistro');
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
}

function aprobarRegistroDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-registro-${currentModalEntityId}`);
    const nombre = row ? row.getAttribute('data-nombre') : 'Egresado';
    aprobarRegistroDirecto(currentModalEntityId, nombre);
    cerrarModales();
}

function rechazarRegistroDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-registro-${currentModalEntityId}`);
    const nombre = row ? row.getAttribute('data-nombre') : 'Egresado';
    rechazarRegistroDirecto(currentModalEntityId, nombre);
    cerrarModales();
}

function aprobarRegistroDirecto(id, nombre) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    row.setAttribute('data-estado', 'Aprobado');
    const statusCell = row.querySelector('.status-pill');
    if (statusCell) {
        statusCell.className = 'status-pill status-aprobado';
        statusCell.textContent = 'Aprobado';
    }

    // Quitar botones de aprobación rápida
    const actionsGroup = row.querySelector('.actions-button-group');
    if (actionsGroup) {
        actionsGroup.innerHTML = `
            <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud completa" onclick="abrirModalRegistro(${id})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span>Revisar</span>
            </button>
            <span class="action-confirmed-tag">✓ Aprobado</span>
        `;
    }

    // Actualizar contadores
    actualizarKpi('kpiRegistros', -1);
    actualizarBadgeContador('badgeCountRegistros', -1);

    mostrarToastAdmin('¡Registro Aprobado!', `Se ha verificado la cuenta de ${nombre} exitosamente.`);
    filtrarTablaActual();
}

function rechazarRegistroDirecto(id, nombre) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    row.setAttribute('data-estado', 'Rechazado');
    const statusCell = row.querySelector('.status-pill');
    if (statusCell) {
        statusCell.className = 'status-pill status-rechazado';
        statusCell.textContent = 'Rechazado';
    }

    const actionsGroup = row.querySelector('.actions-button-group');
    if (actionsGroup) {
        actionsGroup.innerHTML = `
            <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud completa" onclick="abrirModalRegistro(${id})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span>Revisar</span>
            </button>
            <span class="action-rejected-tag">✕ Rechazado</span>
        `;
    }

    actualizarKpi('kpiRegistros', -1);
    actualizarBadgeContador('badgeCountRegistros', -1);

    mostrarToastAdmin('Solicitud Rechazada', `La solicitud de ${nombre} ha sido rechazada.`);
    filtrarTablaActual();
}

// E. Modal 2: Moderación de Contenido
function abrirModalContenido(id) {
    const row = document.getElementById(`row-contenido-${id}`);
    if (!row) return;

    currentModalEntityId = id;
    const tipo = row.getAttribute('data-tipo');
    const titulo = row.getAttribute('data-titulo');
    const autor = row.getAttribute('data-autor');
    const cat = row.getAttribute('data-categoria');
    const fecha = row.getAttribute('data-fecha');
    const estado = row.getAttribute('data-estado');
    const comp = row.getAttribute('data-contenido');

    document.getElementById('modalContTipo').textContent = tipo;
    document.getElementById('modalContTipo').className = `type-pill type-${tipo.toLowerCase()}`;
    document.getElementById('modalContCategoria').textContent = cat;
    document.getElementById('modalContEstado').textContent = estado;
    document.getElementById('modalContEstado').className = `status-pill status-${estado.toLowerCase()}`;
    document.getElementById('modalContTitulo').textContent = titulo;
    document.getElementById('modalContAutor').textContent = autor;
    document.getElementById('modalContFecha').textContent = fecha;
    document.getElementById('modalContCompleto').textContent = comp;

    const modal = document.getElementById('modalContenido');
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
}

function publicarContenidoDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-contenido-${currentModalEntityId}`);
    const titulo = row ? row.getAttribute('data-titulo') : 'Contenido';
    publicarContenidoDirecto(currentModalEntityId, titulo);
    cerrarModales();
}

function rechazarContenidoDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-contenido-${currentModalEntityId}`);
    const titulo = row ? row.getAttribute('data-titulo') : 'Contenido';
    rechazarContenidoDirecto(currentModalEntityId, titulo);
    cerrarModales();
}

function publicarContenidoDirecto(id, titulo) {
    const row = document.getElementById(`row-contenido-${id}`);
    if (!row) return;

    row.setAttribute('data-estado', 'Publicado');
    const statusCell = row.querySelector('.status-pill');
    if (statusCell) {
        statusCell.className = 'status-pill status-publicado';
        statusCell.textContent = 'Publicado';
    }

    const actionsGroup = row.querySelector('.actions-button-group');
    if (actionsGroup) {
        actionsGroup.innerHTML = `
            <button type="button" class="btn-action-sm btn-action-view" title="Examinar y publicar" onclick="abrirModalContenido(${id})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span>Examinar</span>
            </button>
            <span class="action-confirmed-tag">✓ Publicado</span>
        `;
    }

    actualizarKpi('kpiContenidos', -1);
    actualizarBadgeContador('badgeCountContenidos', -1);

    mostrarToastAdmin('¡Publicación Aprobada!', `"${titulo}" ya se encuentra visible en el portal institucional.`);
    filtrarTablaActual();
}

function rechazarContenidoDirecto(id, titulo) {
    const row = document.getElementById(`row-contenido-${id}`);
    if (!row) return;

    row.setAttribute('data-estado', 'Rechazado');
    const statusCell = row.querySelector('.status-pill');
    if (statusCell) {
        statusCell.className = 'status-pill status-rechazado';
        statusCell.textContent = 'Rechazado';
    }

    const actionsGroup = row.querySelector('.actions-button-group');
    if (actionsGroup) {
        actionsGroup.innerHTML = `
            <button type="button" class="btn-action-sm btn-action-view" title="Examinar y publicar" onclick="abrirModalContenido(${id})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span>Examinar</span>
            </button>
            <span class="action-rejected-tag">✕ Rechazado</span>
        `;
    }

    actualizarKpi('kpiContenidos', -1);
    actualizarBadgeContador('badgeCountContenidos', -1);

    mostrarToastAdmin('Contenido Rechazado', `"${titulo}" ha sido desestimado.`);
    filtrarTablaActual();
}

// F. Modal 3: Resolución de PQRS
function abrirModalPQRS(radicado) {
    const row = document.getElementById(`row-pqrs-${radicado}`);
    if (!row) return;

    currentModalEntityId = radicado;
    const tipo = row.getAttribute('data-tipo');
    const remitente = row.getAttribute('data-remitente');
    const correo = row.getAttribute('data-correo');
    const asunto = row.getAttribute('data-asunto');
    const fecha = row.getAttribute('data-fecha');
    const prioridad = row.getAttribute('data-prioridad');
    const estado = row.getAttribute('data-estado');
    const desc = row.getAttribute('data-descripcion');
    const resp = row.getAttribute('data-respuesta');

    document.getElementById('modalPQRSRadicadoCode').textContent = radicado;
    document.getElementById('modalPQRSTipo').textContent = tipo;
    document.getElementById('modalPQRSTipo').className = `type-pill type-${tipo.toLowerCase().replace(/ /g, '-')}`;
    document.getElementById('modalPQRSPrioridad').textContent = `Prioridad ${prioridad}`;
    document.getElementById('modalPQRSPrioridad').className = `priority-badge priority-${prioridad.toLowerCase()}`;
    document.getElementById('modalPQRSEstado').textContent = estado;
    document.getElementById('modalPQRSEstado').className = `status-pill status-${estado.toLowerCase()}`;
    
    document.getElementById('modalPQRSRemitente').textContent = remitente;
    document.getElementById('modalPQRSCorreo').textContent = correo;
    document.getElementById('modalPQRSFecha').textContent = fecha;
    document.getElementById('modalPQRSAsunto').textContent = asunto;
    document.getElementById('modalPQRSDescripcion').textContent = desc;

    document.getElementById('adminRespuestaPQRS').value = resp || '';

    const modal = document.getElementById('modalPQRS');
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
}

function enviarRespuestaPQRSDesdeModal() {
    if (!currentModalEntityId) return;
    const respuesta = document.getElementById('adminRespuestaPQRS').value.trim();
    if (!respuesta) {
        alert('Por favor redacta la respuesta institucional oficial antes de enviar.');
        document.getElementById('adminRespuestaPQRS').focus();
        return;
    }

    const row = document.getElementById(`row-pqrs-${currentModalEntityId}`);
    const remitente = row ? row.getAttribute('data-remitente') : 'Ciudadano';

    resolverPQRSDirecto(currentModalEntityId, remitente);
    cerrarModales();
}

function resolverPQRSDirecto(radicado, remitente) {
    const row = document.getElementById(`row-pqrs-${radicado}`);
    if (!row) return;

    row.setAttribute('data-estado', 'Resuelto');
    const statusCell = row.querySelector('.status-pill');
    if (statusCell) {
        statusCell.className = 'status-pill status-resuelto';
        statusCell.textContent = 'Resuelto';
    }

    const actionsGroup = row.querySelector('.actions-button-group');
    if (actionsGroup) {
        actionsGroup.innerHTML = `
            <button type="button" class="btn-action-sm btn-action-view" title="Atender y responder caso" onclick="abrirModalPQRS('${radicado}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Ver Caso</span>
            </button>
            <span class="action-confirmed-tag">✓ Resuelto</span>
        `;
    }

    actualizarKpi('kpiPQRS', -1);
    actualizarBadgeContador('badgeCountPQRS', -1);

    mostrarToastAdmin('¡Caso Resuelto!', `Respuesta enviada a ${remitente} con radicado ${radicado}.`);
    filtrarTablaActual();
}

// G. Notificación Toast Flotante
function mostrarToastAdmin(titulo, mensaje) {
    const toast = document.getElementById('adminToast');
    if (!toast) return;

    document.getElementById('adminToastTitle').textContent = titulo;
    document.getElementById('adminToastDesc').textContent = mensaje;

    clearTimeout(toastTimeout);
    toast.classList.add('is-visible');

    toastTimeout = setTimeout(() => {
        ocultarToastAdmin();
    }, 3800);
}

function ocultarToastAdmin() {
    const toast = document.getElementById('adminToast');
    if (toast) {
        toast.classList.remove('is-visible');
    }
}

// H. Funciones Auxiliares de Actualización de Métricas
function actualizarKpi(kpiId, delta) {
    const el = document.getElementById(kpiId);
    if (!el) return;
    let val = parseInt(el.textContent.replace(/,/g, ''), 10) || 0;
    val = Math.max(0, val + delta);
    el.textContent = val;
}

function actualizarBadgeContador(badgeId, delta) {
    const el = document.getElementById(badgeId);
    if (!el) return;
    let val = parseInt(el.textContent, 10) || 0;
    val = Math.max(0, val + delta);
    el.textContent = val;
}

// I. Exportar Reporte Simulado
function exportarReporteAdmin() {
    mostrarToastAdmin('Generando Reporte', 'El reporte consolidado de egresados y moderación se está descargando.');
}

// Inicialización de la vista
document.addEventListener('DOMContentLoaded', () => {
    filtrarTablaActual();
});
</script>

<!-- Carga del pie de página y scripts institucionales -->
<?php require_once __DIR__ . '/includes/footer.php'; ?>
