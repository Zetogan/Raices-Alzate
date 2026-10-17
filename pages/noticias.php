<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Noticias (Público y Panel Privado)
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
    require_once __DIR__ . '/../includes/noticias-privado.php';
    exit;
}

$currentPage = 'noticias';
$pageTitle = 'Noticias | ' . APP_NAME;

require_once __DIR__ . '/../includes/header.php';
?>
<main class="main-content noticias-main portal-main">
    <section class="noticias-section portal-section" id="noticias" aria-labelledby="noticias-heading">
        <div class="container noticias-container portal-container">
            <div class="noticias-grid portal-grid">
                <!-- 1. Bloque de textos -->
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
                    <a href="index.php?page=login" class="btn btn-primary btn-noticias-primary btn-noticias-login" id="btnNoticiasLogin">Iniciar sesión</a>
                    <a href="index.php?page=registro" class="btn btn-outline-secondary btn-noticias-secondary" id="btnNoticiasRegistro">Crear una cuenta</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>
