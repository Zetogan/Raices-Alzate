<?php
require_once __DIR__ . '/../config/database.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS `conexiones` (
        `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `solicitante_id` INT UNSIGNED NOT NULL,
        `receptor_id`    INT UNSIGNED NOT NULL,
        `estado`         ENUM('pendiente','aceptada','rechazada') NOT NULL DEFAULT 'pendiente',
        `creado_en`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uq_conexiones_par` (`solicitante_id`, `receptor_id`),
        KEY `idx_conexiones_solicitante` (`solicitante_id`, `estado`),
        KEY `idx_conexiones_receptor` (`receptor_id`, `estado`),
        CONSTRAINT `fk_conexiones_solicitante`
            FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`)
            ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT `fk_conexiones_receptor`
            FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      COMMENT='Solicitudes y conexiones entre egresados';
    ";
    
    $pdo->exec($sql);
    echo "Tabla conexiones creada o verificada exitosamente.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
