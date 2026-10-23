<?php
/**
 * Raíces Alzate - Puente de compatibilidad para radicación de PQRS
 * Redirige o delega la petición al front controller index.php?page=pqr
 */
declare(strict_types=1);

$_GET['page'] = 'pqr';
require_once __DIR__ . '/index.php';
