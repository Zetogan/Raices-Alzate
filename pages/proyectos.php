<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Vista: Pantalla de Proyectos
 * Especificación Figma: 06 - Proyectos - Desktop (y adaptación Mobile)
 * Versión: 0.1.1
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}
?>
<main class="main-content proyectos-main">
    <section class="proyectos-section" id="proyectos" aria-labelledby="proyectos-heading">
        <div class="container proyectos-container">
            <div class="proyectos-grid">
                <!-- 1. Bloque de textos (Título y Descripción) -->
                <div class="proyectos-header-text">
                    <h1 class="proyectos-title" id="proyectos-heading">Proyectos</h1>
                    <p class="proyectos-desc">
                        Descubre los proyectos, iniciativas y oportunidades que conectan a nuestra comunidad de egresados. Para consultar los proyectos y conocer las iniciativas de la comunidad, inicia sesión en Raíces Alzate.
                    </p>
                </div>

                <!-- 2. Tarjeta con la ilustración institucional -->
                <div class="proyectos-visual proyectos-illustration-card">
                    <div class="proyectos-card proyectos-visual-card">
                        <img src="assets/img/proyectos-illustration.svg" 
                             alt="Ilustración de proyectos de egresados con laptop, planta en crecimiento y bombilla de innovación" 
                             class="proyectos-illustration proyectos-illustration-img" 
                             width="520" 
                             height="390" 
                             loading="eager">
                    </div>
                </div>

                <!-- 3. Grupo de botones de acción -->
                <div class="proyectos-actions">
                    <a href="login.php" class="btn btn-primary btn-proyectos-primary btn-proyectos-login" id="btnProyectosLogin">Iniciar sesión</a>
                    <a href="registro.php" class="btn btn-outline-secondary btn-proyectos-secondary" id="btnProyectosRegistro">Crear una cuenta</a>
                </div>
            </div>
        </div>
    </section>
</main>
