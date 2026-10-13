<?php
/**
 * Componente: Header Institucional
 * Soporte: Desktop (72px) y Mobile limpio según Figma
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$currentPage = $currentPage ?? 'inicio';
?>
<header class="portal-header main-navbar site-header header">
    <div class="navbar-container header-content container header-inner">
        <!-- Izquierda: Identidad de Marca -->
        <a href="index.php" class="brand header-logo" aria-label="<?php echo htmlspecialchars(APP_NAME); ?>">
            <img src="assets/icons/logo sin fondo raices alzate.ico" alt="Logo Raíces Alzate" class="brand-logo-img">
            <span class="brand-title"><?php echo htmlspecialchars(APP_NAME); ?></span>
        </a>

        <!-- Menú / Drawer de navegación -->
        <nav class="main-nav nav" id="mainNav" aria-label="Navegación principal">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo ($currentPage === 'inicio') ? 'active' : ''; ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="proyectos.php" class="nav-link <?php echo ($currentPage === 'proyectos') ? 'active' : ''; ?>">Proyectos</a>
                </li>
                <li class="nav-item">
                    <a href="egresados.php" class="nav-link <?php echo ($currentPage === 'egresados') ? 'active' : ''; ?>">Egresados</a>
                </li>
                <li class="nav-item">
                    <a href="noticias.php" class="nav-link <?php echo ($currentPage === 'noticias') ? 'active' : ''; ?>">Noticias</a>
                </li>
            </ul>

            <!-- Acciones trasladadas dentro del drawer para mobile -->
            <div class="mobile-drawer-actions">
                <a href="login.php" class="btn-login-drawer">Iniciar sesión</a>
                <a href="registro.php" class="btn btn-primary btn-block">Registrarse</a>
            </div>
        </nav>

        <!-- Derecha: Acciones directas de Desktop + Disparador hamburguesa -->
        <div class="header-right-group">
            <div class="desktop-actions">
                <a href="login.php" class="btn-login header-login-link">Iniciar sesión</a>
                <a href="registro.php" class="btn btn-primary btn-sm header-register-btn">Registrarse</a>
            </div>
            
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú de navegación" aria-expanded="false" aria-controls="mainNav">
                <span class="menu-toggle-bar"></span>
                <span class="menu-toggle-bar"></span>
                <span class="menu-toggle-bar"></span>
            </button>
        </div>
    </div>
</header>