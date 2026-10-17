<?php
/**
 * Componente: Header Institucional
 * Soporte: Desktop (72px) y Mobile limpio según Figma
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$currentPage = $currentPage ?? 'inicio';

// Detección segura de usuario autenticado para mostrar foto de perfil
if (empty($pdo)) {
    @require_once __DIR__ . '/../config/database.php';
}
if (!function_exists('currentUser')) {
    @require_once __DIR__ . '/../includes/auth.php';
}

$headerUser = function_exists('currentUser') ? currentUser() : (!empty($_SESSION['user']) ? $_SESSION['user'] : (!empty($_SESSION['usuario']) ? $_SESSION['usuario'] : null));
$headerUid = (int)($headerUser['id'] ?? $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? 0);
$headerFoto = null;
$headerNombre = '';
$headerInicial = 'U';

if ($headerUid > 0) {
    $headerNombre = trim($headerUser['nombre_completo'] ?? $headerUser['nombre'] ?? $_SESSION['nombre'] ?? 'Usuario');
    $headerInicial = !empty($headerNombre) ? mb_strtoupper(mb_substr($headerNombre, 0, 1, 'UTF-8'), 'UTF-8') : 'U';
    
    if (!empty($pdo)) {
        try {
            $stmtHFoto = $pdo->prepare("SELECT foto_perfil FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
            $stmtHFoto->execute([':uid' => $headerUid]);
            $rowHFoto = $stmtHFoto->fetch(PDO::FETCH_ASSOC);
            if (!empty($rowHFoto['foto_perfil']) && file_exists(__DIR__ . '/../' . ltrim($rowHFoto['foto_perfil'], '/'))) {
                $headerFoto = $rowHFoto['foto_perfil'];
            }
        } catch (Throwable $e) {
            // Silencioso
        }
    }
}
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
                    <a href="index.php?page=proyectos" class="nav-link <?php echo ($currentPage === 'proyectos') ? 'active' : ''; ?>">Proyectos</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=egresados" class="nav-link <?php echo ($currentPage === 'egresados') ? 'active' : ''; ?>">Egresados</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=noticias" class="nav-link <?php echo ($currentPage === 'noticias') ? 'active' : ''; ?>">Noticias</a>
                </li>
            </ul>

            <!-- Acciones de autenticación dentro del drawer para mobile -->
            <?php if ($headerUid > 0): ?>
                <div class="mobile-drawer-actions" style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="index.php?page=perfil" class="btn-drawer-login" style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; overflow: hidden; background: #D32F2F; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                            <?php if (!empty($headerFoto)): ?>
                                <img src="<?php echo htmlspecialchars($headerFoto); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <?php echo htmlspecialchars($headerInicial); ?>
                            <?php endif; ?>
                        </div>
                        <span>Mi perfil</span>
                    </a>
                    <a href="index.php?page=dashboard" class="btn btn-primary btn-drawer-register">Mi Panel</a>
                </div>
            <?php else: ?>
                <div class="mobile-drawer-actions">
                    <a href="index.php?page=login" class="btn-drawer-login">Iniciar sesión</a>
                    <a href="index.php?page=registro" class="btn btn-primary btn-drawer-register">Registrarse</a>
                </div>
            <?php endif; ?>
        </nav>

        <!-- Derecha: Acciones directas de Desktop + Disparador hamburguesa -->
        <div class="header-right-group">
            <?php if ($headerUid > 0): ?>
                <div class="desktop-actions" style="display: flex; align-items: center; gap: 12px;">
                    <a href="index.php?page=perfil" class="header-profile-badge" title="Mi perfil (<?php echo htmlspecialchars($headerNombre); ?>)" style="display: flex; align-items: center; gap: 8px; text-decoration: none; padding: 4px 8px; border-radius: 20px; transition: background 0.2s ease;">
                        <div class="header-profile-avatar" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: #D32F2F; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; border: 2px solid #E0E0E0;">
                            <?php if (!empty($headerFoto)): ?>
                                <img src="<?php echo htmlspecialchars($headerFoto); ?>" alt="<?php echo htmlspecialchars($headerNombre); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;">
                            <?php else: ?>
                                <?php echo htmlspecialchars($headerInicial); ?>
                            <?php endif; ?>
                        </div>
                        <span class="header-profile-name" style="font-size: 14px; font-weight: 600; color: #212121; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars(explode(' ', $headerNombre)[0]); ?></span>
                    </a>
                    <a href="index.php?page=dashboard" class="btn btn-primary btn-sm header-dashboard-btn" style="min-width: 90px; height: 36px; padding: 0 14px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Mi Panel</a>
                </div>
            <?php else: ?>
                <div class="desktop-actions">
                    <a href="index.php?page=login" class="btn-login header-login-link">Iniciar sesión</a>
                    <a href="index.php?page=registro" class="btn btn-primary btn-sm header-register-btn">Registrarse</a>
                </div>
            <?php endif; ?>
            
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú de navegación" aria-expanded="false" aria-controls="mainNav">
                <span class="menu-toggle-bar"></span>
                <span class="menu-toggle-bar"></span>
                <span class="menu-toggle-bar"></span>
            </button>
        </div>
    </div>
</header>