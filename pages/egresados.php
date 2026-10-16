<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Pantalla de Egresados (Portal Público)
 * Especificación Figma: 05 - Egresados - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}
?>
<main class="main-content egresados-main portal-main">
    <section class="egresados-section portal-section" id="egresados" aria-labelledby="egresados-heading">
        <div class="container egresados-container portal-container">
            <div class="egresados-grid portal-grid">
                <!-- 1. Bloque de textos (Título y Descripción) -->
                <div class="egresados-header-text portal-header-text">
                    <h1 class="egresados-title portal-title" id="egresados-heading">Egresados</h1>
                    <p class="egresados-desc portal-desc">
                        Conoce a la comunidad de egresados de la I.E. Gilberto Alzate Avendaño. Conecta con compañeros de distintas promociones, comparte experiencias y fortalece tu red profesional.
                    </p>
                </div>

                <!-- 2. Tarjeta con la ilustración institucional -->
                <div class="egresados-visual egresados-illustration-card portal-visual portal-illustration-card">
                    <div class="egresados-card egresados-visual-card portal-card portal-visual-card">
                        <img src="assets/img/egresados-illustration.svg" 
                             alt="Ilustración de comunidad de egresados con birrete de graduación, diplomas y red institucional de exalumnos" 
                             class="egresados-illustration egresados-illustration-img portal-illustration" 
                             width="520" 
                             height="390" 
                             loading="eager">
                    </div>
                </div>

                <!-- 3. Grupo de botones de acción -->
                <div class="egresados-actions portal-actions">
                    <a href="login.php" class="btn btn-primary btn-egresados-primary btn-egresados-login" id="btnEgresadosLogin">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-outline-secondary btn-egresados-secondary" id="btnEgresadosRegistro">Crear una cuenta</a>
                </div>
            </div>
        </div>
    </section>
</main>
