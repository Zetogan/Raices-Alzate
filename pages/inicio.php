<?php
/**
 * Vista: Pantalla de Inicio (01 - Inicio - Desktop)
 * Especificación Figma: 1440 × 1472 px
 * Secciones:
 *  1. Hero: 500px (Fondo: #F5F5F5)
 *  2. ¿Qué es Raíces Alzate?: 320px (Fondo: #FFFFFF)
 *  3. ¿Por qué usar Raíces Alzate?: 360px (Fondo: #F5F5F5)
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}
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
                    <a href="#que-es" class="btn btn-primary">Conocer más</a>
                </div>
            </div>
            <div class="hero-visual" aria-hidden="true">
                <div class="visual-card hero-placeholder" role="img" aria-label="Espacio visual reservado para ilustración del Hero"></div>
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
                <div class="visual-card que-es-placeholder" role="img" aria-label="Espacio visual reservado para recurso gráfico"></div>
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
</main>
