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
</body>
</html>
