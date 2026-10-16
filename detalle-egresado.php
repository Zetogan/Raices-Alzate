<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Detalle de Egresado (detalle-egresado.php)
 * Versión: 0.1.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$seccion_activa = 'egresados';
$titulo_seccion = 'Perfil de egresado';
$titulo_pagina = 'Perfil de Valentina Ríos Gómez — Raíces Alzate';

// Datos del usuario autenticado para la barra superior (Topbar)
$user = !empty($_SESSION['user']) && is_array($_SESSION['user']) 
    ? $_SESSION['user'] 
    : (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) ? $_SESSION['usuario'] : []);

$userName    = trim($user['nombre'] ?? $user['name'] ?? $_SESSION['nombre'] ?? 'Santiago Emitola');
$userRole    = trim($user['rol'] ?? $user['role'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'S';

// Catálogo estático de egresados para soporte dinámico
$catalogoDetalleEgresados = [
    1 => [
        'id' => 1,
        'nombre' => 'Valentina Ríos Gómez',
        'inicial' => 'V',
        'carrera_alzate' => 'Bachiller Técnico en Sistemas e Informática',
        'promocion' => '2021',
        'ubicacion' => 'Comuna 10 (La Candelaria), Medellín',
        'ocupacion' => 'Desarrolladora Frontend Junior',
        'empresa' => 'Mercado Libre',
        'email' => 'valentina.rios@ejemplo.com',
        'telefono' => '+57 (300) 123-4567',
        'documento' => 'C.C. 1.036.***.***',
        'vinculacion' => 'Marzo de 2024',
        'conectado' => true, // Conectada -> Enviar mensaje
        'sobre_mi' => 'Egresada alzatista apasionada por el desarrollo web y las soluciones tecnológicas con impacto social. Enfocada en la arquitectura de aplicaciones modernas, escalables y accesibles. Miembro activo de la comunidad de egresados de la I.E. Gilberto Alzate Avendaño, promoviendo iniciativas de mentoría para bachilleres e impulsando el talento de las nuevas generaciones.',
        'cita' => 'Las raíces que forjamos en el Alzate son los cimientos con los que construimos el futuro de nuestra comunidad.',
        'proyectos_count' => 2,
        'red_count' => 34,
        'verificado' => 95,
        'habilidades' => ['PHP', 'React', 'MySQL', 'JavaScript', 'HTML5 & CSS3', 'Arquitectura Web', 'Liderazgo'],
        'proyecto_nombre' => 'Semillero de Robótica Alzatista',
        'proyecto_cat' => 'TECNOLOGÍA',
        'proyecto_desc' => 'Plataforma formativa para promover el pensamiento computacional y la innovación en estudiantes de secundaria.'
    ],
    2 => [
        'id' => 2,
        'nombre' => 'Carlos Andrés Morales',
        'inicial' => 'C',
        'carrera_alzate' => 'Técnico en Gestión Comercial y Contable',
        'promocion' => '2020',
        'ubicacion' => 'Belén, Medellín',
        'ocupacion' => 'Coordinador Financiero',
        'empresa' => 'Bancolombia',
        'email' => 'carlos.morales@ejemplo.com',
        'telefono' => '+57 (312) 345-6789',
        'documento' => 'C.C. 1.017.***.***',
        'vinculacion' => 'Enero de 2023',
        'conectado' => false, // No conectado -> Botón Conectar
        'sobre_mi' => 'Egresado de la especialidad comercial y contable, con sólida experiencia en análisis de riesgos, coordinación financiera y asesoría estratégica para pymes.',
        'cita' => 'La disciplina contable y los valores alzatistas son el motor de todo crecimiento financiero.',
        'proyectos_count' => 1,
        'red_count' => 28,
        'verificado' => 90,
        'habilidades' => ['Finanzas', 'Excel Avanzado', 'Liderazgo', 'Contabilidad', 'Análisis de Riesgos'],
        'proyecto_nombre' => 'Semillero de Emprendimiento y Finanzas',
        'proyecto_cat' => 'FINANZAS',
        'proyecto_desc' => 'Taller formativo de educación financiera básica para egresados y familias de la comunidad alzatista.'
    ],
    3 => [
        'id' => 3,
        'nombre' => 'Camila Zuluaga Henao',
        'inicial' => 'C',
        'carrera_alzate' => 'Bachillerato Académico',
        'promocion' => '2021',
        'ubicacion' => 'El Poblado, Medellín',
        'ocupacion' => 'Diseñadora de Producto UI/UX',
        'empresa' => 'Globant',
        'email' => 'camila.zuluaga@ejemplo.com',
        'telefono' => '+57 (315) 890-1234',
        'documento' => 'C.C. 1.020.***.***',
        'vinculacion' => 'Febrero de 2024',
        'conectado' => true, // Conectada -> Enviar mensaje
        'sobre_mi' => 'Egresada alzatista apasionada por el diseño de interfaces centradas en las personas y sistemas de diseño escalables. Con experiencia en proyectos fintech y consultoría de experiencia de usuario.',
        'cita' => 'El diseño no es solo cómo se ve, sino cómo transforma la vida de las personas.',
        'proyectos_count' => 1,
        'red_count' => 42,
        'verificado' => 98,
        'habilidades' => ['Figma', 'Design Systems', 'UX Research', 'Prototipado', 'Arquitectura de Información'],
        'proyecto_nombre' => 'Sistema de Diseño Institucional Alzatista',
        'proyecto_cat' => 'DISEÑO',
        'proyecto_desc' => 'Estandarización de componentes y guías visuales digitales para la comunidad educativa.'
    ],
    4 => [
        'id' => 4,
        'nombre' => 'Mateo Henao Castro',
        'inicial' => 'M',
        'carrera_alzate' => 'Bachiller Técnico en Sistemas e Informática',
        'promocion' => '2019',
        'ubicacion' => 'Laureles, Medellín',
        'ocupacion' => 'Líder Técnico Cloud',
        'empresa' => 'Ruta N',
        'email' => 'mateo.henao@ejemplo.com',
        'telefono' => '+57 (301) 456-7890',
        'documento' => 'C.C. 1.015.***.***',
        'vinculacion' => 'Agosto de 2022',
        'conectado' => true, // Conectado -> Enviar mensaje
        'sobre_mi' => 'Especialista en infraestructura en la nube y automatización de despliegues. Mentor activo en comunidades de código abierto en Medellín y líder de iniciativas tecnológicas en Ruta N.',
        'cita' => 'La perseverancia técnica convierte grandes retos en soluciones escalables.',
        'proyectos_count' => 3,
        'red_count' => 56,
        'verificado' => 96,
        'habilidades' => ['AWS', 'DevOps', 'Docker', 'Kubernetes', 'Python', 'CI/CD'],
        'proyecto_nombre' => 'Infraestructura Cloud para Semilleros',
        'proyecto_cat' => 'CLOUD',
        'proyecto_desc' => 'Despliegue y arquitectura escalable para servidores educativos en la nube.'
    ],
    5 => [
        'id' => 5,
        'nombre' => 'Laura Restrepo Silva',
        'inicial' => 'L',
        'carrera_alzate' => 'Bachillerato Académico con Honores',
        'promocion' => '2022',
        'ubicacion' => 'Robledo, Medellín',
        'ocupacion' => 'Médica Interna',
        'empresa' => 'Univ. de Antioquia',
        'email' => 'laura.restrepo@ejemplo.com',
        'telefono' => '+57 (318) 765-4321',
        'documento' => 'C.C. 1.039.***.***',
        'vinculacion' => 'Junio de 2023',
        'conectado' => false, // No conectado -> Botón Conectar
        'sobre_mi' => 'Médica interna con enfoque en salud comunitaria y programas de prevención en el área metropolitana de Medellín. Apasionada por la docencia y la investigación clínica.',
        'cita' => 'La vocación de servicio aprendida en las aulas alzatistas guía mi labor médica cada día.',
        'proyectos_count' => 1,
        'red_count' => 19,
        'verificado' => 92,
        'habilidades' => ['Investigación', 'Salud Pública', 'Bioética', 'Epidemiología'],
        'proyecto_nombre' => 'Brigadas de Salud Comunitaria',
        'proyecto_cat' => 'SALUD',
        'proyecto_desc' => 'Jornadas de atención básica y prevención en salud dirigidas a familias del sector.'
    ],
    6 => [
        'id' => 6,
        'nombre' => 'Alejandro Quintero',
        'inicial' => 'A',
        'carrera_alzate' => 'Técnico en Gestión Comercial',
        'promocion' => '2021',
        'ubicacion' => 'Chapinero, Bogotá',
        'ocupacion' => 'Especialista Marketing',
        'empresa' => 'Cementos Argos',
        'email' => 'alejandro.quintero@ejemplo.com',
        'telefono' => '+57 (310) 987-6543',
        'documento' => 'C.C. 1.025.***.***',
        'vinculacion' => 'Noviembre de 2023',
        'conectado' => true, // Conectado -> Enviar mensaje
        'sobre_mi' => 'Profesional en mercadeo estratégico y ventas corporativas B2B con amplia experiencia en posicionamiento de marca y desarrollo de clientes en el sector de la construcción.',
        'cita' => 'Generar valor real y confianza es el pilar de toda relación profesional exitosa.',
        'proyectos_count' => 1,
        'red_count' => 31,
        'verificado' => 90,
        'habilidades' => ['Marketing B2B', 'Ventas', 'Estrategia Digital', 'Analítica'],
        'proyecto_nombre' => 'Estrategia de Expansión Sostenible',
        'proyecto_cat' => 'MARKETING',
        'proyecto_desc' => 'Campaña de fidelización y adopción de canales digitales para distribución de materiales.'
    ],
    7 => [
        'id' => 7,
        'nombre' => 'Daniela Cardona Pineda',
        'inicial' => 'D',
        'carrera_alzate' => 'Bachiller Técnico en Sistemas e Informática',
        'promocion' => '2018',
        'ubicacion' => 'Envigado, Medellín',
        'ocupacion' => 'Analista de Datos',
        'empresa' => 'Seguros SURA',
        'email' => 'daniela.cardona@ejemplo.com',
        'telefono' => '+57 (304) 321-9876',
        'documento' => 'C.C. 1.011.***.***',
        'vinculacion' => 'Mayo de 2021',
        'conectado' => false, // No conectado -> Botón Conectar
        'sobre_mi' => 'Analista de datos enfocada en modelamiento predictivo y tableros analíticos para toma de decisiones gerenciales en el sector asegurador.',
        'cita' => 'Los datos cuentan historias que nos permiten tomar mejores decisiones para el futuro.',
        'proyectos_count' => 2,
        'red_count' => 24,
        'verificado' => 88,
        'habilidades' => ['SQL', 'Power BI', 'Estadística', 'Python', 'ETL'],
        'proyecto_nombre' => 'Dashboard de Indicadores Alzatistas',
        'proyecto_cat' => 'ANALÍTICA',
        'proyecto_desc' => 'Visualización interactiva de estadísticas de egresados y trayectorias formativas.'
    ]
];

$egresadoId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$egresado = $catalogoDetalleEgresados[$egresadoId] ?? $catalogoDetalleEgresados[1];

$titulo_pagina = 'Perfil de ' . htmlspecialchars($egresado['nombre']) . ' — Raíces Alzate';
$pageTitle = $titulo_pagina;
$currentPage = 'egresados';
$extraCss = ['dashboard.css', 'perfil.css', 'perfil-detalle.css'];

require_once __DIR__ . '/includes/header.php';
?>

<script>
    try {
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    } catch(e) {}
</script>

<link rel="stylesheet" href="assets/css/perfil.css">
<link rel="stylesheet" href="assets/css/perfil-detalle.css">

<!-- Telón de fondo para menú en dispositivos móviles -->
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper dashboard-main-area">
        <?php require_once __DIR__ . '/includes/topbar.php'; ?>

        <main class="content-area dashboard-content profile-content-wrapper" id="mainContent">
            
            <!-- Botón de retorno al directorio de egresados -->
            <div class="breadcrumb-back-wrapper">
                <a href="egresados.php" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Volver al directorio de egresados</span>
                </a>
            </div>

            <!-- ================= 1. HERO CARD (IDÉNTICA A PERFIL.PHP) ================= -->
            <div class="profile-card profile-hero-card">
                <!-- Banner degradado con insignia arriba a la derecha -->
                <div class="profile-banner">
                    <span class="banner-institution-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        I.E. Gilberto Alzate Avendaño
                    </span>
                </div>

                <!-- Contenedor principal de la cabecera en Desktop -->
                <div class="profile-hero-body">
                    <!-- 1. Avatar a la izquierda -->
                    <div class="profile-avatar-wrapper">
                        <div class="profile-avatar-large">
                            <span><?= htmlspecialchars($egresado['inicial'] ?? 'V') ?></span>
                        </div>
                    </div>

                    <!-- 2. Información central alineada a la izquierda -->
                    <div class="profile-identity-info">
                        <h1 class="profile-user-name"><?= htmlspecialchars($egresado['nombre'] ?? 'Valentina Ríos Gómez') ?></h1>
                        <p class="profile-user-subtitle"><?= htmlspecialchars($egresado['carrera_alzate'] ?? 'Bachiller Técnico en Sistemas e Informática') ?><?= !empty($egresado['promocion']) ? ' • Promoción ' . htmlspecialchars($egresado['promocion']) : '' ?></p>
                        
                        <div class="profile-meta-pills">
                            <span class="meta-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Medellín, Colombia
                            </span>
                            <span class="meta-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                Egresada Alzatista
                            </span>
                            <span class="meta-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                <?= htmlspecialchars($egresado['empresa'] ?? 'Mercado Libre') ?>
                            </span>
                        </div>
                    </div>

                    <!-- 3. Botonera a la extrema derecha -->
                    <div class="profile-header-actions">
                        <?php if (!empty($egresado['conectado'])): ?>
                            <a href="mensajes.php" class="btn btn-primary btn-action-main" id="btnEnviarMensaje">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span class="btn-text">Enviar mensaje</span>
                            </a>
                        <?php else: ?>
                            <button type="button" class="btn btn-action-main btn-conectar" id="btnConectarEgresado" onclick="conectarConEgresado(this)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7.5" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                <span class="btn-text">Conectar</span>
                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-action-share btn-outline-share" id="btnCompartirPerfil" onclick="navigator.clipboard.writeText(window.location.href); alert('Enlace copiado al portapapeles');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                            <span class="btn-text">Compartir</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================= 2. FILA DE 4 TARJETAS DE ESTADÍSTICAS ================= -->
            <div class="profile-stats-grid">
                <div class="stat-card">
                    <div class="stat-icon-wrap icon-red-bg">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <div class="stat-text">
                        <span class="stat-number">2</span>
                        <span class="stat-label">Proyectos publicados</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap icon-blue-bg">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <div class="stat-text">
                        <span class="stat-number"><?= htmlspecialchars($egresado['promocion']) ?></span>
                        <span class="stat-label">Promoción egresado</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap icon-green-bg">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="stat-text">
                        <span class="stat-number">34</span>
                        <span class="stat-label">Egresados en su red</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap icon-orange-bg">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#EF6C00" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="stat-text stat-progress-box">
                        <div class="stat-progress-header">
                            <span class="stat-number">95%</span>
                            <span class="stat-label">Perfil verificado</span>
                        </div>
                        <div class="stat-progress-bar"><div class="stat-progress-fill" style="width: 95%;"></div></div>
                    </div>
                </div>
            </div>

            <!-- ================= 3. DISTRIBUCIÓN DE DOS COLUMNAS ================= -->
            <div class="profile-layout-grid">
                
                <!-- COLUMNA IZQUIERDA (Contacto y Enlaces) -->
                <div class="profile-col-side">
                    <!-- Tarjeta: Información de contacto -->
                    <div class="profile-card content-card">
                        <div class="card-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <h3 class="card-title">Información de contacto</h3>
                        </div>
                        <div class="contact-data-list">
                            <div class="contact-item-row">
                                <span class="contact-icon-sub">✉</span>
                                <div class="contact-info-block">
                                    <span class="contact-label">CORREO ELECTRÓNICO</span>
                                    <a href="mailto:<?= htmlspecialchars($egresado['email']) ?>" class="contact-val text-red"><?= htmlspecialchars($egresado['email']) ?></a>
                                </div>
                            </div>
                            <div class="contact-item-row">
                                <span class="contact-icon-sub">📞</span>
                                <div class="contact-info-block">
                                    <span class="contact-label">TELÉFONO CELULAR</span>
                                    <span class="contact-val"><?= htmlspecialchars($egresado['telefono']) ?></span>
                                </div>
                            </div>
                            <div class="contact-item-row">
                                <span class="contact-icon-sub">🪪</span>
                                <div class="contact-info-block">
                                    <span class="contact-label">DOCUMENTO DE IDENTIDAD</span>
                                    <span class="contact-val"><?= htmlspecialchars($egresado['documento']) ?></span>
                                </div>
                            </div>
                            <div class="contact-item-row">
                                <span class="contact-icon-sub">📍</span>
                                <div class="contact-info-block">
                                    <span class="contact-label">UBICACIÓN Y BARRIO</span>
                                    <span class="contact-val"><?= htmlspecialchars($egresado['ubicacion']) ?></span>
                                </div>
                            </div>
                            <div class="contact-item-row">
                                <span class="contact-icon-sub">📅</span>
                                <div class="contact-info-block">
                                    <span class="contact-label">FECHA DE VINCULACIÓN</span>
                                    <span class="contact-val"><?= htmlspecialchars($egresado['vinculacion']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta: Redes y enlaces -->
                    <div class="profile-card content-card">
                        <div class="card-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            <h3 class="card-title">Redes y enlaces</h3>
                        </div>
                        <div class="links-data-list">
                            <a href="https://linkedin.com/in/valentina-rios" class="social-link-box" target="_blank" rel="noopener">
                                <span class="social-name">linkedin.com/in/valentina-rios</span>
                                <span class="social-arrow">↗</span>
                            </a>
                            <a href="https://github.com/valentina-rios" class="social-link-box" target="_blank" rel="noopener">
                                <span class="social-name">github.com/valentina-rios</span>
                                <span class="social-arrow">↗</span>
                            </a>
                            <a href="https://valentinarios.dev" class="social-link-box" target="_blank" rel="noopener">
                                <span class="social-name">valentinarios.dev</span>
                                <span class="social-arrow">↗</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA (Sobre mí, Formación, Habilidades y Proyectos) -->
                <div class="profile-col-main">
                    <!-- Tarjeta: Sobre mí -->
                    <div class="profile-card content-card">
                        <div class="card-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <h3 class="card-title">Sobre mí</h3>
                        </div>
                        <p class="about-text-body"><?= htmlspecialchars($egresado['sobre_mi']) ?></p>
                        
                        <blockquote class="profile-quote-box">
                            <p>"<?= htmlspecialchars($egresado['cita']) ?>"</p>
                        </blockquote>
                    </div>

                    <!-- Tarjeta: Formación académica -->
                    <div class="profile-card content-card">
                        <div class="card-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                            <h3 class="card-title">Formación académica</h3>
                        </div>
                        <div class="academic-timeline">
                            <div class="timeline-entry red-entry">
                                <div class="timeline-dot red-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header-row">
                                        <h4 class="timeline-institution">I.E. Gilberto Alzate Avendaño</h4>
                                        <span class="badge-pill-blue">Secundaria & Media</span>
                                    </div>
                                    <p class="timeline-career"><?= htmlspecialchars($egresado['carrera_alzate']) ?></p>
                                    <span class="timeline-meta">2016 – 2021 • Graduado con mención de honor</span>
                                    <p class="timeline-desc">Participación activa en el semillero de robótica y tecnología institucional. Desarrollo del proyecto de grado enfocado en plataformas comunitarias.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta: Habilidades y competencias -->
                    <div class="profile-card content-card">
                        <div class="card-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            <h3 class="card-title">Habilidades y competencias</h3>
                        </div>
                        <div class="skills-tag-wrap">
                            <span class="skill-pill">Desarrollo Frontend</span>
                            <span class="skill-pill">JavaScript</span>
                            <span class="skill-pill">HTML5 & CSS3</span>
                            <span class="skill-pill">React</span>
                            <span class="skill-pill">Arquitectura Web</span>
                            <span class="skill-pill">Liderazgo</span>
                            <span class="skill-pill">Trabajo en Equipo</span>
                        </div>
                    </div>

                    <!-- Tarjeta: Proyectos en Raíces Alzate -->
                    <div class="profile-card content-card">
                        <div class="card-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            <h3 class="card-title">Proyectos en Raíces Alzate</h3>
                        </div>
                        <div class="linked-project-card clickable-project" onclick="window.location.href='detalle-proyecto.php'">
                            <span class="badge-category-blue">TECNOLOGÍA</span>
                            <h4 class="project-title-link">Semillero de Robótica Alzatista</h4>
                            <p class="project-summary">Plataforma formativa para promover el pensamiento computacional y la innovación en estudiantes de secundaria.</p>
                            <span class="project-view-more">Ver detalles del proyecto →</span>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Notificación Toast Flotante -->
<div class="egresado-toast" id="toastNotificacionEgresados" role="status" aria-live="polite">
    <span class="egresado-toast-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
    </span>
    <span id="toastMensajeEgresados">¡Solicitud de conexión enviada!</span>
</div>

<script src="assets/js/dashboard.js"></script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
