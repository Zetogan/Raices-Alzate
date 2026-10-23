<?php
/**
 * Raíces Alzate - Puente de compatibilidad para panel administrativo
 * Redirige o delega la petición al front controller index.php?page=administracion
 */
declare(strict_types=1);

$_GET['page'] = 'administracion';
require_once __DIR__ . '/index.php';
