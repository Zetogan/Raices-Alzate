<?php
/**
 * Layout: Cierre de página HTML y carga de scripts globales
 * Incluye componente footer institucional y script principal
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}

$isDashboardView = !empty($seccion_activa) || (isset($currentPage) && in_array($currentPage, ['dashboard', 'perfil', 'editar-perfil']));
?>
    <?php if (!$isDashboardView): ?>
        <?php include __DIR__ . '/../components/footer.php'; ?>
    <?php endif; ?>

<?php
if (!function_exists('assetJsUrl')) {
    function assetJsUrl($filename) {
        $cleanPath = ltrim($filename, '/');
        $fullPath = __DIR__ . '/../' . $cleanPath;
        $time = file_exists($fullPath) ? filemtime($fullPath) : time();
        return htmlspecialchars($cleanPath, ENT_QUOTES, 'UTF-8') . '?v=' . APP_VERSION . '&amp;t=' . $time;
    }
}
?>
    <!-- Script principal de interacción -->
    <script src="<?php echo assetJsUrl('assets/js/main.js'); ?>"></script>

    <!-- Scripts específicos por módulo -->
    <?php if (isset($currentPage) && in_array($currentPage, ['login', 'auth', 'registro', 'recuperar', 'recuperar-password', 'reset-password', 'cambiar-password', 'reset'])): ?>
    <script src="<?php echo assetJsUrl('assets/js/auth.js'); ?>"></script>
    <?php endif; ?>
    <?php if (!empty($extraJs)): ?>
        <?php foreach ((array)$extraJs as $jsFile): ?>
            <script src="<?php echo assetJsUrl($jsFile); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Script global del Page Loader (desvanecimiento y desmontaje) -->
    <script src="<?php echo assetJsUrl('assets/js/loader.js'); ?>"></script>
</body>
</html>
