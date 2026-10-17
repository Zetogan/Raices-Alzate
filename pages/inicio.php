<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Inicio (01 - Inicio - Desktop)
 * Versión: 0.2.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = APP_NAME . ' | IE Gilberto Alzate Avendaño';
$currentPage = 'inicio';

require_once __DIR__ . '/../includes/header.php';
?>
<main class="main-content">
    <!-- ====================================================================
         2. SECCIÓN HERO (Altura: 500px | Fondo: #F5F5F5)
         ==================================================================== -->
    <section class="hero-section" id="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Conectando generaciones,<br>construyendo futuro.</h1>
                <p class="hero-desc">Una plataforma para conectar, acompañar y fortalecer la comunidad de egresados.</p>
                <div class="hero-actions">
                    <a href="#que-es" class="btn btn-primary hero-cta-btn">Conocer más</a>
                </div>
            </div>
            <div class="hero-visual" aria-hidden="true">
                <div class="visual-card hero-illustration-card">
                    <img src="assets/img/hero-illustration.svg" alt="Comunidad de egresados alzatistas conectando generaciones" class="hero-illustration-img" width="560" height="380" loading="eager" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================================
         3. SECCIÓN "¿QUÉ ES RAÍCES ALZATE?" (Altura: 320px | Fondo: #FFFFFF)
         ==================================================================== -->
    <section class="que-es-section" id="que-es">
        <div class="container que-es-container">
            <div class="que-es-content">
                <h2 class="section-title">¿Qué es Raíces Alzate?</h2>
                <p class="que-es-desc">
                    Una plataforma digital que conecta a los egresados del Colegio Gilberto Alzate Avendaño,
                    fortaleciendo la comunicación, el acompañamiento y las oportunidades de la comunidad.
                </p>
            </div>
            <div class="que-es-visual" aria-hidden="true">
                <div class="visual-card que-es-illustration-card">
                    <img src="assets/img/about-illustration.svg" alt="Plataforma institucional Raíces Alzate" class="que-es-illustration-img" width="520" height="300" loading="lazy" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================================
         4. SECCIÓN "¿POR QUÉ USAR RAÍCES ALZATE?" (Altura: 360px | Fondo: #F5F5F5)
         ==================================================================== -->
    <section class="beneficios-section" id="porque-usar">
        <div class="container beneficios-container">
            <h2 class="section-title beneficios-title">¿Por qué usar Raíces Alzate?</h2>

            <div class="beneficios-grid">
                <!-- Tarjeta 1: Conexión -->
                <article class="benefit-card">
                    <h3 class="benefit-card-title">Conexión</h3>
                    <p class="benefit-card-text">Mantén el contacto con la comunidad de egresados.</p>
                </article>

                <!-- Tarjeta 2: Oportunidades -->
                <article class="benefit-card">
                    <h3 class="benefit-card-title">Oportunidades</h3>
                    <p class="benefit-card-text">Encuentra oportunidades académicas y profesionales.</p>
                </article>

                <!-- Tarjeta 3: Comunidad -->
                <article class="benefit-card">
                    <h3 class="benefit-card-title">Comunidad</h3>
                    <p class="benefit-card-text">Comparte proyectos y fortalece el sentido de pertenencia.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- ====================================================================
         5. SECCIÓN ATENCIÓN AL CIUDADANO, PQRS Y SOPORTE TÉCNICO
         ==================================================================== -->
    <section class="soporte-section" id="soporte-pqrs">
        <div class="container">
            <div class="soporte-banner-card">
                <div class="soporte-text-content">
                    <span class="soporte-badge">Canal Institucional Oficial</span>
                    <h2 class="soporte-title">¿Tienes dudas, sugerencias o detectaste un error?</h2>
                    <p class="soporte-desc">
                        La I.E. Gilberto Alzate Avendaño dispone de un canal ágil y transparente para radicar peticiones, quejas, reclamos, reconocimientos o notificar incidencias técnicas en la plataforma con radicado oficial de seguimiento.
                    </p>
                </div>
                <div class="soporte-action">
                    <a href="index.php?page=pqr" class="btn-pqrs-cta">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span>Radicar PQRS o Reporte</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>

