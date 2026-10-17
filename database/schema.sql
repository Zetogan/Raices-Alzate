-- =============================================================================
-- Raíces Alzate — Esquema de base de datos
-- Institución Educativa Gilberto Alzate Avendaño
--
-- Motor objetivo : MariaDB 10.4 (XAMPP) / MySQL 5.7+ / MySQL 8
-- Motor de tablas: InnoDB
-- Codificación   : utf8mb4 / utf8mb4_unicode_ci
--
-- Script NO destructivo:
--   * No crea ni elimina bases de datos.
--   * No contiene DROP, TRUNCATE ni DELETE.
--   * Usa CREATE TABLE IF NOT EXISTS e INSERT IGNORE, por lo que puede
--     ejecutarse varias veces sin duplicar tablas ni datos.
--
-- Contraseñas : solo se guarda el hash generado por password_hash() de PHP.
-- Recuperación: solo se guarda el hash SHA-256 del token, nunca el token.
-- =============================================================================

USE `raices_alzate`;

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 1. ROLES
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
    `id`          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre`      VARCHAR(30)      NOT NULL COMMENT 'Identificador interno: administrador, egresado, estudiante',
    `descripcion` VARCHAR(255)     NULL,
    `creado_en`   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_roles_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Roles de acceso de la plataforma';

-- Datos base (IDs fijos para poder referenciarlos desde PHP).
INSERT IGNORE INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
    (1, 'administrador', 'Gestiona usuarios, aprueba registros y modera publicaciones'),
    (2, 'egresado',      'Egresado verificado de la institución'),
    (3, 'estudiante',    'Estudiante activo de la institución');

-- -----------------------------------------------------------------------------
-- 2. USUARIOS
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id`                  INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `rol_id`              TINYINT UNSIGNED NOT NULL DEFAULT 3,
    `nombre_completo`     VARCHAR(150)     NOT NULL,
    `email`               VARCHAR(255)     NOT NULL,
    `password_hash`       VARCHAR(255)     NOT NULL COMMENT 'Resultado de password_hash(); nunca texto plano',
    `estado_verificacion` ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente'
                          COMMENT 'Aprobación del registro desde el panel de administración',
    `activo`              TINYINT(1)       NOT NULL DEFAULT 1,
    `ultimo_acceso`       DATETIME         NULL,
    `token_recuperacion`  VARCHAR(64)      NULL DEFAULT NULL COMMENT 'Hash SHA-256 del token de recuperación criptográfico',
    `expiracion_token`    DATETIME         NULL DEFAULT NULL COMMENT 'Fecha y hora de expiración del token (1 hora máx)',
    `creado_en`           DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_usuarios_email` (`email`),
    KEY `idx_usuarios_rol` (`rol_id`),
    KEY `idx_usuarios_verificacion` (`estado_verificacion`, `activo`),
    KEY `idx_usuarios_token` (`token_recuperacion`),
    CONSTRAINT `fk_usuarios_rol`
        FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_usuarios_activo` CHECK (`activo` IN (0, 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Cuentas de acceso';

-- -----------------------------------------------------------------------------
-- 3. PERFILES DE EGRESADOS (1:1 con usuarios)
--    Campos tomados de editar-perfil.php y del panel de administración.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfiles_egresados` (
    `id`                   INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    `usuario_id`           INT UNSIGNED      NOT NULL,
    `documento`            VARCHAR(20)       NULL COMMENT 'Documento de identidad para verificación',
    `bio`                  TEXT              NULL,
    `genero`               VARCHAR(30)       NULL,
    `fecha_nacimiento`     DATE              NULL,
    `ciudad`               VARCHAR(100)      NULL,
    `direccion`            VARCHAR(150)      NULL,
    `telefono`             VARCHAR(25)       NULL,
    `email_secundario`     VARCHAR(255)      NULL,
    `titulo_institucional` VARCHAR(150)      NULL,
    `anio_graduacion`      SMALLINT UNSIGNED NULL,
    `programa_superior`    VARCHAR(150)      NULL,
    `institucion_superior` VARCHAR(150)      NULL,
    `ocupacion`            VARCHAR(120)      NULL,
    `empresa`              VARCHAR(120)      NULL,
    `area_especialidad`    VARCHAR(120)      NULL,
    `anios_experiencia`    TINYINT UNSIGNED  NULL,
    `linkedin`             VARCHAR(255)      NULL,
    `github`               VARCHAR(255)      NULL,
    `sitio_web`            VARCHAR(255)      NULL,
    `red_adicional`        VARCHAR(255)      NULL,
    `foto_perfil`          VARCHAR(255)      NULL COMMENT 'Ruta relativa en uploads/',
    `foto_portada`         VARCHAR(255)      NULL COMMENT 'Ruta relativa en uploads/',
    `perfil_publico`       TINYINT(1)        NOT NULL DEFAULT 1,
    `creado_en`            DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_perfiles_usuario` (`usuario_id`),
    UNIQUE KEY `uq_perfiles_documento` (`documento`),
    KEY `idx_perfiles_anio` (`anio_graduacion`),
    KEY `idx_perfiles_ciudad` (`ciudad`),
    CONSTRAINT `fk_perfiles_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `chk_perfiles_anio` CHECK (`anio_graduacion` IS NULL OR `anio_graduacion` BETWEEN 1950 AND 2100),
    CONSTRAINT `chk_perfiles_publico` CHECK (`perfil_publico` IN (0, 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Información personal, académica y profesional del egresado';

-- -----------------------------------------------------------------------------
-- 4. HABILIDADES (catálogo) y relación N:M con perfiles
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `habilidades` (
    `id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(60)  NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_habilidades_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo de habilidades';

CREATE TABLE IF NOT EXISTS `perfil_habilidades` (
    `perfil_id`    INT UNSIGNED NOT NULL,
    `habilidad_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`perfil_id`, `habilidad_id`),
    KEY `idx_perfil_habilidades_habilidad` (`habilidad_id`),
    CONSTRAINT `fk_ph_perfil`
        FOREIGN KEY (`perfil_id`) REFERENCES `perfiles_egresados` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_ph_habilidad`
        FOREIGN KEY (`habilidad_id`) REFERENCES `habilidades` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Habilidades asociadas a cada perfil';

-- -----------------------------------------------------------------------------
-- 5. PROYECTOS
--    Campos de crear-proyecto.php / editar-proyecto.php.
--    autor_id: RESTRICT para no perder publicaciones al borrar un usuario
--    (desactivar con usuarios.activo = 0).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `proyectos` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `autor_id`          INT UNSIGNED NOT NULL,
    `titulo`            VARCHAR(150) NOT NULL,
    `descripcion`       TEXT         NOT NULL,
    `categoria`         VARCHAR(80)  NOT NULL,
    `enlace`            VARCHAR(255) NULL,
    `portada`           VARCHAR(255) NULL COMMENT 'Ruta relativa en uploads/proyectos/',
    `estado`            ENUM('borrador','pendiente','publicado','rechazado','archivado') NOT NULL DEFAULT 'pendiente',
    `revisado_por`      INT UNSIGNED NULL,
    `fecha_publicacion` DATETIME     NULL,
    `creado_en`         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_proyectos_autor` (`autor_id`),
    KEY `idx_proyectos_estado_fecha` (`estado`, `fecha_publicacion`),
    KEY `idx_proyectos_categoria` (`categoria`),
    KEY `idx_proyectos_revisor` (`revisado_por`),
    FULLTEXT KEY `ft_proyectos_busqueda` (`titulo`, `descripcion`),
    CONSTRAINT `fk_proyectos_autor`
        FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_proyectos_revisor`
        FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Proyectos publicados por la comunidad';

-- -----------------------------------------------------------------------------
-- 6. NOTICIAS
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `autor_id`          INT UNSIGNED NOT NULL,
    `titulo`            VARCHAR(180) NOT NULL,
    `descripcion`       TEXT         NOT NULL,
    `categoria`         VARCHAR(60)  NOT NULL COMMENT 'Comunidad, Eventos, Destacados, Proyectos, Institucional...',
    `portada`           VARCHAR(255) NULL COMMENT 'Ruta relativa en uploads/noticias/',
    `estado`            ENUM('borrador','pendiente','publicado','rechazado','archivado') NOT NULL DEFAULT 'pendiente',
    `revisado_por`      INT UNSIGNED NULL,
    `fecha_publicacion` DATETIME     NULL,
    `creado_en`         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_noticias_autor` (`autor_id`),
    KEY `idx_noticias_estado_fecha` (`estado`, `fecha_publicacion`),
    KEY `idx_noticias_categoria` (`categoria`),
    KEY `idx_noticias_revisor` (`revisado_por`),
    FULLTEXT KEY `ft_noticias_busqueda` (`titulo`, `descripcion`),
    CONSTRAINT `fk_noticias_autor`
        FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_noticias_revisor`
        FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Noticias institucionales y de la comunidad';

-- -----------------------------------------------------------------------------
-- 7. OPORTUNIDADES
--    Campos de oportunidades.php / detalle-oportunidad.php.
--    requisitos: un requisito por línea.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `oportunidades` (
    `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `autor_id`           INT UNSIGNED NOT NULL,
    `titulo`             VARCHAR(180) NOT NULL,
    `empresa`            VARCHAR(150) NOT NULL COMMENT 'Entidad que ofrece la oportunidad',
    `publicado_por`      VARCHAR(150) NULL     COMMENT 'Dependencia o aliado que la difunde',
    `categoria`          VARCHAR(40)  NOT NULL COMMENT 'Empleo, Beca, Pasantía, Capacitación, Convocatoria',
    `modalidad`          VARCHAR(100) NOT NULL,
    `ubicacion`          VARCHAR(150) NULL,
    `descripcion`        TEXT         NOT NULL,
    `requisitos`         TEXT         NULL,
    `beneficios`         TEXT         NULL,
    `enlace_postulacion` VARCHAR(255) NULL,
    `fecha_limite`       DATE         NULL,
    `estado`             ENUM('borrador','pendiente','publicado','rechazado','cerrado') NOT NULL DEFAULT 'pendiente',
    `revisado_por`       INT UNSIGNED NULL,
    `fecha_publicacion`  DATETIME     NULL,
    `creado_en`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_oportunidades_autor` (`autor_id`),
    KEY `idx_oportunidades_estado_fecha` (`estado`, `fecha_publicacion`),
    KEY `idx_oportunidades_categoria` (`categoria`),
    KEY `idx_oportunidades_limite` (`fecha_limite`),
    KEY `idx_oportunidades_revisor` (`revisado_por`),
    FULLTEXT KEY `ft_oportunidades_busqueda` (`titulo`, `empresa`, `descripcion`),
    CONSTRAINT `fk_oportunidades_autor`
        FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_oportunidades_revisor`
        FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Empleos, becas, pasantías y convocatorias';

-- -----------------------------------------------------------------------------
-- 8. RECUPERACIÓN DE CONTRASEÑAS
--    token_hash = hash('sha256', $tokenPlano). El token plano solo viaja por
--    correo; un token es válido si usado_en IS NULL y expira_en > NOW().
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id`   INT UNSIGNED NOT NULL,
    `token_hash`   CHAR(64)     NOT NULL,
    `expira_en`    DATETIME     NOT NULL,
    `usado_en`     DATETIME     NULL,
    `ip_solicitud` VARCHAR(45)  NULL COMMENT 'IPv4 o IPv6',
    `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_password_resets_token` (`token_hash`),
    KEY `idx_password_resets_usuario` (`usuario_id`, `usado_en`),
    KEY `idx_password_resets_expira` (`expira_en`),
    CONSTRAINT `fk_password_resets_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tokens de un solo uso para restablecer contraseña';

-- -----------------------------------------------------------------------------
-- 9. CONEXIONES (Red de contactos entre egresados)
-- -----------------------------------------------------------------------------
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

-- -----------------------------------------------------------------------------
-- 10. MENSAJES DIRECTOS
-- -----------------------------------------------------------------------------
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

-- -----------------------------------------------------------------------------
-- 11. PQRS (Peticiones, Quejas, Reclamos, Sugerencias y Reportes)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pqrs` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `radicado`       VARCHAR(40)  NOT NULL,
    `tipo`           VARCHAR(50)  NOT NULL COMMENT 'Petición, Queja, Reclamo, Sugerencia, Felicitación, Error técnico',
    `nombre`         VARCHAR(150) NOT NULL,
    `email`          VARCHAR(255) NOT NULL,
    `asunto`         VARCHAR(200) NOT NULL,
    `mensaje`        TEXT         NOT NULL,
    `imagen`         VARCHAR(255) NULL COMMENT 'Ruta de captura o soporte en uploads/pqrs/',
    `estado`         ENUM('pendiente','en_revision','respondido','cerrado') NOT NULL DEFAULT 'pendiente',
    `prioridad`      ENUM('Baja','Media','Alta') NOT NULL DEFAULT 'Media',
    `respuesta`      TEXT         NULL,
    `respondido_por` INT UNSIGNED NULL,
    `creado_en`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_pqrs_radicado` (`radicado`),
    KEY `idx_pqrs_estado_prioridad` (`estado`, `prioridad`),
    KEY `idx_pqrs_email` (`email`),
    KEY `idx_pqrs_creado` (`creado_en`),
    CONSTRAINT `fk_pqrs_respondido_por`
        FOREIGN KEY (`respondido_por`) REFERENCES `usuarios` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='PQRS y reportes de la comunidad educativa';

-- -----------------------------------------------------------------------------
-- 12. DATOS SEMILLA BÁSICOS
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `habilidades` (`nombre`) VALUES
    ('Programación Web'),
    ('JavaScript'),
    ('PHP'),
    ('Python'),
    ('Bases de Datos'),
    ('Diseño Gráfico'),
    ('Marketing Digital'),
    ('Liderazgo'),
    ('Gestión de Proyectos'),
    ('Emprendimiento'),
    ('Inglés Profesional'),
    ('Educación y Docencia'),
    ('Finanzas'),
    ('Atención al Cliente'),
    ('Redacción y Comunicación');

-- -----------------------------------------------------------------------------
-- 13. ADMINISTRADOR INICIAL DE SEGURIDAD
--     Credenciales de primer acceso:
--       * Email: admin@alzate.edu.co
--       * Clave: AdminAlzate2026! (Bcrypt seguro mediante password_hash())
--     Cambiar inmediatamente después del primer inicio de sesión.
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `usuarios` (`id`, `rol_id`, `nombre_completo`, `email`, `password_hash`, `estado_verificacion`, `activo`, `creado_en`) VALUES
    (1, 1, 'Administrador General', 'admin@alzate.edu.co', '$2y$10$YAOhz37LpaOUqrEhcBODJujoHKkNMqRCQ3/4.hvdxVEdvAOBsQXOO', 'aprobado', 1, NOW());

INSERT IGNORE INTO `perfiles_egresados` (`usuario_id`, `bio`, `ocupacion`, `perfil_publico`, `creado_en`) VALUES
    (1, 'Administración y Coordinación de la Comunidad de Egresados', 'Administrador Institucional', 0, NOW());


