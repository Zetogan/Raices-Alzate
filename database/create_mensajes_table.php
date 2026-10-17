<?php
/**
 * Raíces Alzate - Migración: Crear tabla 'mensajes'
 */

require_once __DIR__ . '/../config/database.php';

if (empty($pdo)) {
    die("ERROR: No hay conexión con la base de datos MySQL.\n");
}

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `mensajes` (
        `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `remitente_id`    INT UNSIGNED NOT NULL,
        `destinatario_id` INT UNSIGNED NOT NULL,
        `mensaje`         TEXT NOT NULL,
        `leido`           TINYINT(1) NOT NULL DEFAULT 0,
        `creado_en`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_mensajes_conversacion` (`remitente_id`, `destinatario_id`, `creado_en`),
        KEY `idx_mensajes_destinatario_leido` (`destinatario_id`, `leido`),
        KEY `idx_mensajes_creado` (`creado_en`),
        CONSTRAINT `fk_mensajes_remitente`
            FOREIGN KEY (`remitente_id`) REFERENCES `usuarios` (`id`)
            ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT `fk_mensajes_destinatario`
            FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      COMMENT='Mensajes directos entre egresados conectados';
    ";

    $pdo->exec($sql);
    echo "¡Tabla 'mensajes' creada o verificada exitosamente en MySQL!\n";

} catch (PDOException $e) {
    die("Error al crear la tabla 'mensajes': " . $e->getMessage() . "\n");
}
