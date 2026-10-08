<?php
/**
 * Componente: Header / Navegación Institucional
 * Especificación: Desktop (72px) y Mobile según Figma
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$currentPage = $currentPage ?? 'inicio';
?>
<header class="site-header header">
    <div class="container header-inner">
        <!-- Izquierda: Logo / Marca -->
        <a href="index.php" class="brand header-logo" aria-label="<?php echo htmlspecialchars(APP_NAME); ?>">
            <img src="assets/icons/logo sin fondo raices alzate.ico" alt="Logo <?php echo htmlspecialchars(APP_NAME); ?>" class="brand-logo-img brand-logo header-logo-img" width="36" height="36">
            <span class="brand-title"><?php echo htmlspecialchars(APP_NAME); ?></span>
        </a>

        <!-- Centro: Navegación principal (en desktop en medio; en mobile actúa como drawer lateral) -->
        <nav class="main-nav nav" id="mainNav" aria-label="Navegación principal">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo ($currentPage === 'inicio') ? 'active' : ''; ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="#proyectos" class="nav-link">Proyectos</a>
                </li>
                <li class="nav-item">
                    <a href="#egresados" class="nav-link">Egresados</a>
                </li>
                <li class="nav-item">
                    <a href="#noticias" class="nav-link">Noticias</a>
                </li>
            </ul>

            <!-- Acciones secundarias exclusivas dentro del drawer en mobile -->
            <div class="mobile-drawer-actions">
                <a href="#registro" class="btn btn-primary btn-sm btn-block">Registrarse</a>
            </div>
        </nav>

        <!-- Derecha: Acciones directas + menú hamburguesa -->
        <div class="header-right-group">
            <a href="#login" class="btn-login header-login-link">Iniciar sesión</a>
            <a href="#registro" class="btn btn-primary btn-sm header-register-btn">Registrarse</a>
            
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú de navegación" aria-expanded="false" aria-controls="mainNav">
                <span class="menu-toggle-bar"></span>
                <span class="menu-toggle-bar"></span>
                <span class="menu-toggle-bar"></span>
            </button>
        </div>
    </div>
</header>