<?php
/**
 * Raíces Alzate - Puente de compatibilidad para perfil
 * Redirige o delega la petición al front controller index.php?page=perfil
 */
declare(strict_types=1);

$_GET['page'] = 'perfil';
require_once __DIR__ . '/index.php';
