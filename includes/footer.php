<?php
/**
 * Layout: Cierre de página HTML y carga de scripts globales
 * Incluye componente footer institucional y script principal
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}
?>
    <?php include __DIR__ . '/../components/footer.php'; ?>

    <!-- Script principal de interacción -->
    <script src="assets/js/main.js?v=<?php echo APP_VERSION; ?>"></script>

    <!-- Scripts específicos por módulo -->
    <?php if (isset($currentPage) && in_array($currentPage, ['login', 'auth', 'registro'])): ?>
    <script src="assets/js/auth.js?v=<?php echo APP_VERSION; ?>"></script>
    <?php endif; ?>
    <?php if (!empty($extraJs)): ?>
        <?php foreach ((array)$extraJs as $jsFile): ?>
            <script src="<?php echo htmlspecialchars($jsFile); ?>?v=<?php echo APP_VERSION; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
