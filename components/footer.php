<?php
/**
 * Componente: Footer Institucional
 * Especificación Figma: 01 - Inicio - Desktop (Fondo: #212121 | Padding: 48px 0 32px)
 */

if (!defined('APP_NAME')) {
    exit('Acceso directo no permitido.');
}
?>
<footer class="site-footer footer">
    <div class="container footer-container">
        <!-- Bloque Superior: 3 Columnas -->
        <div class="footer-inner footer-main">
            <!-- Columna 1: Marca y lema institucional -->
            <div class="footer-brand footer-col">
                <div class="footer-brand-header">
                    <img src="assets/icons/logo sin fondo raices alzate.ico" alt="Logo <?php echo htmlspecialchars(APP_NAME); ?>" class="footer-brand-logo" width="32" height="32">
                    <h4 class="footer-brand-title footer-title"><?php echo htmlspecialchars(APP_NAME); ?></h4>
                </div>
                <p class="footer-desc">
                    Conectando generaciones, fortaleciendo nuestra comunidad.
                </p>
            </div>

            <!-- Columna 2: Navegación -->
            <div class="footer-column footer-col">
                <h4 class="footer-title">Navegación</h4>
                <ul class="footer-links footer-nav-list">
                    <li><a href="index.php" class="footer-link">Inicio</a></li>
                    <li><a href="index.php?page=proyectos" class="footer-link">Proyectos</a></li>
                    <li><a href="index.php?page=egresados" class="footer-link">Egresados</a></li>
                    <li><a href="index.php?page=noticias" class="footer-link">Noticias</a></li>
                    <li><a href="index.php?page=pqr" class="footer-link">PQRS y Soporte</a></li>
                </ul>
            </div>

            <!-- Columna 3: Institución -->
            <div class="footer-column footer-col">
                <h4 class="footer-title">Institución</h4>
                <ul class="footer-links footer-nav-list">
                    <li><span class="footer-text footer-desc">Institución Educativa Gilberto Alzate Avendaño</span></li>
                </ul>
            </div>
        </div>

        <!-- Línea divisoria y Copyright inferior con margen de respiración -->
        <div class="footer-bottom">
            <p class="copyright footer-copyright">&copy; 2025–2026 <?php echo htmlspecialchars(APP_NAME); ?>. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
