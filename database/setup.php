<?php
/**
 * Raíces Alzate - Script de Inicialización y Migración de Base de Datos
 * Ejecutable vía navegador (http://localhost/Raices-Alzate/database/setup.php) o CLI (php database/setup.php)
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

$isCli = (php_sapi_name() === 'cli');

function printMsg(string $msg, string $type = 'info'): void {
    global $isCli;
    $colors = [
        'info'    => "\033[0;34m",
        'success' => "\033[0;32m",
        'warn'    => "\033[0;33m",
        'error'   => "\033[0;31m",
        'reset'   => "\033[0m"
    ];

    if ($isCli) {
        $color = $colors[$type] ?? $colors['info'];
        echo "{$color}[{$type}] {$msg}{$colors['reset']}\n";
    } else {
        $bg = [
            'info'    => '#e3f2fd; color: #0d47a1',
            'success' => '#e8f5e9; color: #1b5e20',
            'warn'    => '#fff8e1; color: #f57f17',
            'error'   => '#ffebee; color: #b71c1c'
        ][$type] ?? '#f5f5f5; color: #333';
        echo "<div style=\"padding: 10px 14px; margin: 6px 0; border-radius: 6px; background: {$bg}; font-family: monospace;\"><strong>[{$type}]</strong> " . htmlspecialchars($msg) . "</div>";
    }
}

if (!$isCli) {
    echo "<!DOCTYPE html><html lang=\"es\"><head><meta charset=\"UTF-8\"><title>Inicialización de Base de Datos — " . htmlspecialchars(APP_NAME) . "</title></head><body style=\"max-width: 800px; margin: 40px auto; padding: 20px; font-family: sans-serif;\">";
    echo "<h1 style=\"color: #D32F2F;\">" . htmlspecialchars(APP_NAME) . " — Instalador de Base de Datos</h1>";
}

try {
    // 1. Conexión al servidor MySQL sin especificar base de datos para crearla si no existe
    $port = defined('DB_PORT') ? (int)DB_PORT : 3310;
    $dsnNoDb = 'mysql:host=' . DB_HOST . ';port=' . $port . ';charset=utf8mb4';
    $pdoRoot = new PDO($dsnNoDb, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    printMsg("Conexión con el servidor MySQL (" . DB_HOST . ") exitosa.", 'success');

    // 2. Crear base de datos si no existe
    $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    printMsg("Base de datos '" . DB_NAME . "' verificada o creada.", 'success');

    // 3. Conexión directa a la base de datos
    require_once __DIR__ . '/../config/database.php';
    if (empty($pdo)) {
        throw new RuntimeException("No se pudo obtener la conexión PDO a " . DB_NAME);
    }

    // 4. Leer y ejecutar el esquema completo
    $schemaPath = __DIR__ . '/schema.sql';
    if (!file_exists($schemaPath)) {
        throw new RuntimeException("Archivo schema.sql no encontrado en {$schemaPath}");
    }

    $schemaSql = file_get_contents($schemaPath);
    $pdo->exec($schemaSql);
    printMsg("Esquema oficial (schema.sql) aplicado exitosamente.", 'success');

    // 5. Verificar tablas requeridas
    $stmtTables = $pdo->query("SHOW TABLES");
    $tablas = $stmtTables->fetchAll(PDO::FETCH_COLUMN);

    $tablasEsperadas = [
        'roles', 'usuarios', 'perfiles_egresados', 'habilidades', 'perfil_habilidades',
        'proyectos', 'noticias', 'oportunidades', 'password_resets', 'conexiones',
        'mensajes', 'pqrs'
    ];

    printMsg("Total de tablas existentes en '" . DB_NAME . "': " . count($tablas), 'info');

    $faltantes = array_diff($tablasEsperadas, $tablas);
    if (empty($faltantes)) {
        printMsg("¡Todas las tablas requeridas (" . implode(', ', $tablasEsperadas) . ") están activas!", 'success');
    } else {
        printMsg("Tablas faltantes: " . implode(', ', $faltantes), 'warn');
    }

    // 6. Verificar y garantizar cuenta administrativa segura inicial
    $stmtAdmin = $pdo->prepare("SELECT id, email FROM usuarios WHERE rol_id = 1 LIMIT 1");
    $stmtAdmin->execute();
    $adminExistente = $stmtAdmin->fetch();

    if ($adminExistente) {
        printMsg("Administrador institucional detectado: " . $adminExistente['email'] . " (ID: " . $adminExistente['id'] . ")", 'info');
    } else {
        $adminEmail = 'admin@alzate.edu.co';
        $adminPass  = 'AdminAlzate2026!';
        $adminHash  = password_hash($adminPass, PASSWORD_DEFAULT);

        $stmtInsAdmin = $pdo->prepare("
            INSERT INTO usuarios (rol_id, nombre_completo, email, password_hash, estado_verificacion, activo, creado_en)
            VALUES (1, 'Administrador General', :email, :pass, 'aprobado', 1, NOW())
        ");
        $stmtInsAdmin->execute([
            ':email' => $adminEmail,
            ':pass'  => $adminHash
        ]);
        $newAdminId = (int)$pdo->lastInsertId();

        // Perfil base para el administrador
        $stmtInsPerfil = $pdo->prepare("
            INSERT IGNORE INTO perfiles_egresados (usuario_id, bio, ocupacion, perfil_publico, creado_en)
            VALUES (:uid, 'Administración y Coordinación de la Comunidad de Egresados', 'Administrador Institucional', 0, NOW())
        ");
        $stmtInsPerfil->execute([':uid' => $newAdminId]);

        printMsg("¡Cuenta administrativa creada de forma segura!", 'success');
        printMsg("  * Correo: {$adminEmail}", 'info');
        printMsg("  * Contraseña inicial segura: {$adminPass}", 'warn');
        printMsg("  * Recuerda cambiar la contraseña tras el primer inicio de sesión.", 'warn');
    }

} catch (Throwable $e) {
    printMsg("Error: " . $e->getMessage(), 'error');
}

if (!$isCli) {
    echo "<p style=\"margin-top: 24px;\"><a href=\"../index.php\" style=\"display: inline-block; padding: 10px 18px; background: #D32F2F; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600;\">Ir al Portal Principal</a></p>";
    echo "</body></html>";
}
