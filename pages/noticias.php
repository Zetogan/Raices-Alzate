<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Pantalla de Noticias
 * Especificación Figma: 07 - Noticias - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}
?>
<main class="main-content noticias-main portal-main">
    <section class="noticias-section portal-section" id="noticias" aria-labelledby="noticias-heading">
        <div class="container noticias-container portal-container">
            <div class="noticias-grid portal-grid">
                <!-- 1. Bloque de textos (Título y Descripción) -->
                <div class="noticias-header-text portal-header-text">
                    <h1 class="noticias-title portal-title" id="noticias-heading">Noticias</h1>
                    <p class="noticias-desc portal-desc">
                        Mantente al día con los logros, eventos, comunicados y novedades institucionales de la comunidad de egresados. Para consultar las noticias y estar al tanto de los acontecimientos de la institución, inicia sesión en Raíces Alzate.
                    </p>
                </div>

                <!-- 2. Tarjeta con la ilustración institucional -->
                <div class="noticias-visual noticias-illustration-card portal-visual portal-illustration-card">
                    <div class="noticias-card noticias-visual-card portal-card portal-visual-card">
                        <img src="assets/img/noticias-illustration.svg" 
                             alt="Ilustración de portal de noticias, eventos y novedades institucionales de la comunidad de egresados" 
                             class="noticias-illustration noticias-illustration-img portal-illustration" 
                             width="520" 
                             height="390" 
                             loading="eager">
                    </div>
                </div>

                <!-- 3. Grupo de botones de acción -->
                <div class="noticias-actions portal-actions">
                    <a href="login.php" class="btn btn-primary btn-noticias-primary btn-noticias-login" id="btnNoticiasLogin">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-outline-secondary btn-noticias-secondary" id="btnNoticiasRegistro">Crear una cuenta</a>
                </div>
            </div>
        </div>
    </section>
</main>
