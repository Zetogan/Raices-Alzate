<?php
/**
 * Componente: Barra Superior del Panel Privado (Topbar)
 * Pantalla de referencia: 03 - Dashboard - Desktop
 * Paleta Oficial:
 *   - Avatar: #D32F2F (Rojo Alzate)
 *   - Títulos y nombre de usuario: #212121
 *   - Rol / subtítulo: #757575
 *   - Bordes: #E0E0E0
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$userName = $userName ?? 'Usuario';
$userRole = $userRole ?? 'Egresado';
$userInitial = $userInitial ?? 'U';
?>
<header class="dashboard-topbar topbar-container" id="dashboardTopbar">
    <!-- Izquierda (en desktop) / Contenedor del botón hamburguesa y título -->
    <div class="topbar-left">
        <button type="button" class="dashboard-menu-toggle mobile-menu-toggle btn-menu-hamburguesa" id="dashboardMenuToggle" aria-label="Abrir menú lateral" aria-expanded="false" aria-controls="dashboardSidebar">
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
        <h1 class="topbar-title">Dashboard</h1>
    </div>

    <!-- Derecha (en desktop) / Bloque de identidad del usuario (en mobile pasa a la izquierda) -->
    <div class="topbar-right">
        <div class="topbar-user-profile dashboard-user-info topbar-user" id="topbarUserProfile">
            <div class="topbar-user-avatar dashboard-user-avatar user-avatar" aria-hidden="true" title="<?php echo htmlspecialchars($userName . ' (' . $userRole . ')'); ?>">
                <?php echo htmlspecialchars($userInitial); ?>
            </div>
            <div class="topbar-user-details dashboard-user-meta user-details">
                <span class="topbar-user-name dashboard-user-name user-name"><?php echo htmlspecialchars($userName); ?></span>
                <span class="topbar-user-role dashboard-user-role user-role"><?php echo htmlspecialchars($userRole); ?></span>
            </div>
        </div>
    </div>
</header>
