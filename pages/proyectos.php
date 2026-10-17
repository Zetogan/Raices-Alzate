<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Proyectos (Público y Panel Privado)
 * Versión: 0.2.1
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

// Si el usuario cuenta con sesión activa y no solicita vista pública explícita
$hasActiveSession = isAuthenticated();
if ($hasActiveSession && (!isset($_GET['view']) || $_GET['view'] !== 'public')) {
    require_once __DIR__ . '/../includes/proyectos-privado.php';
    exit;
}

$currentPage = 'proyectos';
$pageTitle = 'Proyectos | ' . APP_NAME;

require_once __DIR__ . '/../includes/header.php';
?>
<main class="main-content proyectos-main portal-main">
    <section class="proyectos-section portal-section" id="proyectos" aria-labelledby="proyectos-heading">
        <div class="container proyectos-container portal-container">
            <div class="proyectos-grid portal-grid">
                <!-- 1. Bloque de textos -->
                <div class="proyectos-header-text portal-header-text">
                    <h1 class="proyectos-title portal-title" id="proyectos-heading">Proyectos</h1>
                    <p class="proyectos-desc portal-desc">
                        Descubre los proyectos, iniciativas y oportunidades que conectan a nuestra comunidad de egresados. Para consultar los proyectos y conocer las iniciativas de la comunidad, inicia sesión en Raíces Alzate.
                    </p>
                </div>

                <!-- 2. Tarjeta con la ilustración institucional -->
                <div class="proyectos-visual proyectos-illustration-card portal-visual portal-illustration-card">
                    <div class="proyectos-card proyectos-visual-card portal-card portal-visual-card">
                        <img src="assets/img/proyectos-illustration.svg" 
                             alt="Ilustración de proyectos de egresados con laptop, planta en crecimiento y bombilla de innovación" 
                             class="proyectos-illustration proyectos-illustration-img portal-illustration" 
                             width="520" 
                             height="390" 
                             loading="eager">
                    </div>
                </div>

                <!-- 3. Grupo de botones de acción -->
                <div class="proyectos-actions portal-actions">
                    <a href="index.php?page=login" class="btn btn-primary btn-proyectos-primary btn-proyectos-login" id="btnProyectosLogin">Iniciar sesión</a>
                    <a href="index.php?page=registro" class="btn btn-outline-secondary btn-proyectos-secondary" id="btnProyectosRegistro">Crear una cuenta</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
