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

require_once __DIR__ . '/auth.php';

$seccion_activa = $seccion_activa ?? $currentPage ?? '';
$isAdmin = function_exists('hasRole') ? hasRole('administrador') : (strtolower($userRole ?? '') === 'administrador');
?>
<aside class="dashboard-sidebar" id="dashboardSidebar" aria-label="Menú principal del panel">
    <!-- 1. Cabecera del Sidebar: Marca Institucional y Botón Toggle -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <a href="index.php?page=dashboard" class="sidebar-brand-link" aria-label="<?php echo htmlspecialchars(APP_NAME); ?>" title="<?php echo htmlspecialchars(APP_NAME); ?>">
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
            <!-- Dashboard -->
            <?php if ($isAdmin): ?>
            <!-- SECCIÓN ADMINISTRADOR: PANEL DE CONTROL Y GESTIÓN -->
            <?php $tabActualAdmin = $tabActiva ?? ($_GET['tab'] ?? ($_GET['view'] ?? 'dashboard')); ?>
            <!-- 1. Dashboard Administrativo -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=administracion&tab=dashboard" class="nav-item sidebar-nav-link <?= (in_array(($seccion_activa ?? ''), ['administracion', 'admin']) && $tabActualAdmin === 'dashboard') ? 'active' : '' ?>" title="Panel de Control y Métricas" aria-current="<?= ($tabActualAdmin === 'dashboard') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Dashboard</span>
                </a>
            </li>

            <!-- 2. Control de Usuarios (CRUD) -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=administracion&tab=usuarios" class="nav-item sidebar-nav-link <?= (in_array(($seccion_activa ?? ''), ['administracion', 'admin']) && $tabActualAdmin === 'usuarios') ? 'active' : '' ?>" title="Control de Usuarios" aria-current="<?= ($tabActualAdmin === 'usuarios') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Control de Usuarios</span>
                </a>
            </li>

            <!-- 3. Aprobación de Registros -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=administracion&tab=registros" class="nav-item sidebar-nav-link <?= (in_array(($seccion_activa ?? ''), ['administracion', 'admin']) && $tabActualAdmin === 'registros') ? 'active' : '' ?>" title="Aprobación de Registros" aria-current="<?= ($tabActualAdmin === 'registros') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Aprobación de Registros</span>
                </a>
            </li>

            <!-- 4. Moderación de Blog -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=administracion&tab=contenidos" class="nav-item sidebar-nav-link <?= (in_array(($seccion_activa ?? ''), ['administracion', 'admin']) && $tabActualAdmin === 'contenidos') ? 'active' : '' ?>" title="Moderación de Blog" aria-current="<?= ($tabActualAdmin === 'contenidos') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Moderación de Blog</span>
                </a>
            </li>

            <!-- 5. Resolución de PQRS -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=administracion&tab=pqrs" class="nav-item sidebar-nav-link <?= (in_array(($seccion_activa ?? ''), ['administracion', 'admin']) && $tabActualAdmin === 'pqrs') ? 'active' : '' ?>" title="Resolución de PQRS" aria-current="<?= ($tabActualAdmin === 'pqrs') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Resolución de PQRS</span>
                </a>
            </li>

            <!-- 6. Mensajes y Comunicaciones -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=mensajes" class="nav-item sidebar-nav-link <?= (($seccion_activa ?? '') === 'mensajes') ? 'active' : '' ?>" title="Mensajes y Comunicaciones" aria-current="<?= (($seccion_activa ?? '') === 'mensajes') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Mensajes</span>
                </a>
            </li>

            <!-- 6. Blog & Novedades -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=blog" class="nav-item sidebar-nav-link <?= in_array(($seccion_activa ?? ''), ['blog', 'noticias', 'proyectos', 'oportunidades'], true) ? 'active' : '' ?>" title="Blog & Novedades" aria-current="<?= in_array(($seccion_activa ?? ''), ['blog', 'noticias', 'proyectos', 'oportunidades'], true) ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Blog & Novedades</span>
                </a>
            </li>

            <!-- 7. Comunidad de Egresados (Supervisión) -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=egresados&view=panel" class="nav-item sidebar-nav-link <?= (($seccion_activa ?? '') === 'egresados') ? 'active' : '' ?>" title="Red de Egresados" aria-current="<?= (($seccion_activa ?? '') === 'egresados') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Egresados</span>
                </a>
            </li>

            <?php else: ?>
            <!-- SECCIÓN USUARIO / EGRESADO -->
            <!-- 1. Dashboard -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=dashboard" class="nav-item sidebar-nav-link <?php echo (($seccion_activa ?? '') === 'dashboard') ? 'active' : ''; ?>" title="Dashboard" aria-current="<?php echo (($seccion_activa ?? '') === 'dashboard') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Dashboard</span>
                </a>
            </li>

            <!-- 2. Perfil del Egresado -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=perfil" class="nav-item sidebar-nav-link <?php echo (($seccion_activa ?? '') === 'perfil') ? 'active' : ''; ?>" title="Perfil" aria-current="<?php echo (($seccion_activa ?? '') === 'perfil') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Perfil</span>
                </a>
            </li>

            <!-- 3. Red de Egresados -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=egresados&view=panel" class="nav-item sidebar-nav-link <?php echo (($seccion_activa ?? '') === 'egresados') ? 'active' : ''; ?>" title="Egresados" aria-current="<?php echo (($seccion_activa ?? '') === 'egresados') ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Egresados</span>
                </a>
            </li>

            <!-- 4. Blog y Novedades -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=blog" class="nav-item sidebar-nav-link <?php echo in_array(($seccion_activa ?? ''), ['blog', 'noticias', 'proyectos', 'oportunidades'], true) ? 'active' : ''; ?>" title="Blog & Novedades" aria-current="<?php echo in_array(($seccion_activa ?? ''), ['blog', 'noticias', 'proyectos', 'oportunidades'], true) ? 'page' : 'false'; ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Blog & Novedades</span>
                </a>
            </li>

            <!-- 5. Mensajes Directos -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=mensajes" class="nav-item sidebar-nav-link <?= (($seccion_activa ?? '') === 'mensajes') ? 'active' : '' ?>" title="Mensajes" aria-current="<?= (($seccion_activa ?? '') === 'mensajes') ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span class="nav-label sidebar-nav-label">Mensajes</span>
                </a>
            </li>

            <!-- 6. PQRS y Soporte -->
            <li class="sidebar-nav-item">
                <a href="index.php?page=pqr" class="nav-item sidebar-nav-link <?= in_array(($seccion_activa ?? ''), ['pqr', 'pqrs', 'soporte']) ? 'active' : '' ?>" title="PQRS y Reportes" aria-current="<?= in_array(($seccion_activa ?? ''), ['pqr', 'pqrs', 'soporte']) ? 'page' : 'false' ?>">
                    <svg class="nav-icon sidebar-nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span class="nav-label sidebar-nav-label">PQRS</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Cerrar sesión -->
            <li class="sidebar-nav-item sidebar-footer-item">
                <a href="index.php?page=logout" class="nav-item nav-logout sidebar-logout-link" id="btnLogout" title="Cerrar sesión" aria-label="Cerrar sesión">
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
