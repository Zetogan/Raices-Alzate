<?php
/**
 * Raíces Alzate - Conexión Centralizada a Base de Datos (PDO)
 * Plataforma Institucional - IE Gilberto Alzate Avendaño
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!function_exists('getDBConnection')) {
    function getDBConnection(): ?PDO {
        static $instance = null;
        static $failed = false;

        if ($instance instanceof PDO) {
            return $instance;
        }

        if ($failed) {
            return null;
        }

        $portsToTry = [
            defined('DB_PORT') ? (int)DB_PORT : 3310,
            3306
        ];
        $portsToTry = array_values(array_unique($portsToTry));

        foreach ($portsToTry as $port) {
            try {
                $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', DB_HOST, $port, DB_NAME);
                $instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_TIMEOUT            => 1,
                ]);
                $instance->exec("SET time_zone = '-05:00'");
                return $instance;
            } catch (PDOException $e) {
                // Probar el siguiente puerto si no se pudo conectar
                continue;
            }
        }

        $failed = true;
        error_log('Error de conexión PDO: No fue posible conectar con MySQL en los puertos especificados.');
        return null;
    }
}

// Variable global $pdo para compatibilidad total con código existente
global $pdo, $dbError;
if (!isset($pdo) || !$pdo instanceof PDO) {
    $pdo = getDBConnection();
    if ($pdo === null) {
        $dbError = 'Error de conexión con la base de datos.';
    }
}