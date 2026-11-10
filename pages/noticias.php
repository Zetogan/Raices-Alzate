<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Noticias (Integrado en el Blog Unificado)
 */

declare(strict_types=1);

if (!isset($_GET['tipo'])) {
    $_GET['tipo'] = 'noticias';
}

require __DIR__ . '/blog.php';
