<?php
/**
 * Componente: Barra Lateral de Navegación Privada (Sidebar)
 * Pantalla de referencia: 03 - Dashboard - Desktop (Ancho: 240px, Fondo: #FFFFFF)
 * Paleta Oficial:
 *   - Primario activo: #D32F2F (Rojo Alzate)
 *   - Texto inactivo: #212121
 *   - Subtítulo / Muted: #757575
 *   - Borde: #E0E0E0
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$currentPage = $currentPage ?? $seccion_activa ?? 'dashboard';
$userRole = $userRole ?? 'Egresado';
$isAdmin = (strtolower($userRole) === 'administrador' || strtolower($userRole) === 'admin');
?>
<aside class="dashboard-sidebar" id="dashboardSidebar" aria-label="Menú principal del panel">
    <!-- 1. Cabecera del Sidebar: Marca Institucional y Botón Toggle -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <a href="dashboard.php" class="sidebar-brand-link" aria-label="<?php echo htmlspecialchars(APP_NAME); ?>" title="<?php echo htmlspecialchars(APP_NAME); ?>">
                <img src="assets/icons/logo sin fondo raices alzate.ico" alt="Logo Raíces Alzate" class="sidebar-brand-logo" width="32" height="32">
                <div class="sidebar-brand-text">
                    <span class="brand-title sidebar-brand-title"><?php echo htmlspecialchars(APP_NAME); ?></span>
                    <span class="brand-subtitle sidebar-brand-subtitle">Comunidad de egresados</span>
                </div>
            </a>
        </div>
        <!-- Botón para alternar colapso -->
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Contraer barra lateral" title="Contraer menú">
            <svg class="toggle-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>
    </div>

    <!-- 2. Navegación del Sidebar -->
    <nav class="sidebar-nav" aria-label="Navegación del panel">
        <ul class="sidebar-menu sidebar-nav-list">
            <!-- Dashboard (Elemento Activo por defecto) -->
            <li class="sidebar-nav-item">
                <a href="dashboard.php" class="nav-item sidebar-nav-link <?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>" title="Dashboard" aria-current="<?php echo ($currentPage === 'dashboard') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Dashboard</span>
                </a>
            </li>

            <!-- Perfil -->
            <li class="sidebar-nav-item">
                <a href="perfil.php" class="nav-item sidebar-nav-link <?php echo ($currentPage === 'perfil') ? 'active' : ''; ?>" title="Perfil" aria-current="<?php echo ($currentPage === 'perfil') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Perfil</span>
                </a>
            </li>

            <!-- Egresados -->
            <li class="sidebar-nav-item">
                <a href="egresados.php" class="nav-item sidebar-nav-link <?php echo ($currentPage === 'egresados') ? 'active' : ''; ?>" title="Egresados" aria-current="<?php echo ($currentPage === 'egresados') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Egresados</span>
                </a>
            </li>

            <!-- Proyectos -->
            <li class="sidebar-nav-item">
                <a href="proyectos.php" class="nav-item sidebar-nav-link <?php echo ($currentPage === 'proyectos') ? 'active' : ''; ?>" title="Proyectos" aria-current="<?php echo ($currentPage === 'proyectos') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Proyectos</span>
                </a>
            </li>

            <!-- Noticias -->
            <li class="sidebar-nav-item">
                <a href="noticias.php" class="nav-item sidebar-nav-link <?php echo ($currentPage === 'noticias') ? 'active' : ''; ?>" title="Noticias">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Noticias</span>
                </a>
            </li>

            <!-- Administración (Opcional según rol) -->
            <?php if ($isAdmin): ?>
            <li class="sidebar-nav-item">
                <a href="admin.php" class="nav-item sidebar-nav-link <?php echo ($currentPage === 'admin') ? 'active' : ''; ?>" title="Administración">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Administración</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Cerrar sesión -->
            <li class="sidebar-nav-item sidebar-footer-item">
                <a href="logout.php" class="nav-item nav-logout sidebar-logout-link" id="btnLogout" title="Cerrar sesión" aria-label="Cerrar sesión">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Cerrar sesión</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
