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

// Garantizar disponibilidad de base de datos y autenticación
if (empty($pdo)) {
    @require_once __DIR__ . '/../config/database.php';
}
if (!function_exists('currentUser')) {
    @require_once __DIR__ . '/auth.php';
}

$cUser = function_exists('currentUser') ? currentUser() : (!empty($_SESSION['user']) ? $_SESSION['user'] : (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : null));
$topbarUid = $userId ?? $currentUserId ?? (int)($cUser['id'] ?? $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? 0);

if (empty($userName) || $userName === 'Usuario') {
    $realName = trim($cUser['nombre_completo'] ?? $cUser['nombre'] ?? $_SESSION['nombre'] ?? '');
    if (!empty($realName)) {
        $userName = $realName;
    }
}
$userName = $userName ?? 'Usuario';
$userRole = !empty($userRole) && $userRole !== 'Egresado' ? $userRole : trim($cUser['rol_nombre'] ?? $cUser['rol'] ?? $_SESSION['rol'] ?? 'Egresado');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';
$topbarTitle = $titulo_seccion ?? $topbarTitle ?? 'Mi perfil';

if (empty($userFoto) && !empty($pdo) && $topbarUid > 0) {
    try {
        $stmtFotoTopbar = $pdo->prepare("SELECT foto_perfil FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
        $stmtFotoTopbar->execute([':uid' => $topbarUid]);
        $rowFotoTopbar = $stmtFotoTopbar->fetch(PDO::FETCH_ASSOC);
        if (!empty($rowFotoTopbar['foto_perfil'])) {
            $userFoto = $rowFotoTopbar['foto_perfil'];
        }
    } catch (Throwable $e) {
        // Silencioso
    }
}
$userFoto = $userFoto ?? null;
?>
<header class="dashboard-topbar topbar-container" id="dashboardTopbar">
    <!-- Izquierda (en desktop) / Contenedor del botón hamburguesa y título -->
    <div class="topbar-left">
        <button type="button" class="dashboard-menu-toggle mobile-menu-toggle btn-menu-hamburguesa" id="dashboardMenuToggle" aria-label="Abrir menú lateral" aria-expanded="false" aria-controls="dashboardSidebar">
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
        <h1 class="topbar-title"><?php echo htmlspecialchars($topbarTitle); ?></h1>
    </div>

    <!-- Derecha (en desktop) / Bloque de identidad del usuario (en mobile pasa a la izquierda) -->
    <div class="topbar-right">
        <div class="topbar-user-profile dashboard-user-info topbar-user" id="topbarUserProfile">
            <div class="topbar-user-avatar dashboard-user-avatar user-avatar" aria-hidden="true" title="<?php echo htmlspecialchars($userName . ' (' . $userRole . ')'); ?>" style="overflow:hidden;padding:0;">
                <?php if (!empty($userFoto) && file_exists(__DIR__ . '/../' . ltrim($userFoto, '/'))): ?>
                    <img src="<?php echo htmlspecialchars($userFoto); ?>" alt="<?php echo htmlspecialchars($userName); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
                <?php else: ?>
                    <?php echo htmlspecialchars($userInitial); ?>
                <?php endif; ?>
            </div>
            <div class="topbar-user-details dashboard-user-meta user-details">
                <span class="topbar-user-name dashboard-user-name user-name"><?php echo htmlspecialchars($userName); ?></span>
                <span class="topbar-user-role dashboard-user-role user-role"><?php echo htmlspecialchars($userRole); ?></span>
            </div>
        </div>
    </div>
</header>
