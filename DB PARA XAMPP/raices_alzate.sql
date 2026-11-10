-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2026 a las 19:42:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `raices_alzate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conexiones`
--

CREATE TABLE `conexiones` (
  `id` int(10) UNSIGNED NOT NULL,
  `solicitante_id` int(10) UNSIGNED NOT NULL,
  `receptor_id` int(10) UNSIGNED NOT NULL,
  `estado` enum('pendiente','aceptada','rechazada') NOT NULL DEFAULT 'pendiente',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Solicitudes y conexiones entre egresados';

--
-- Volcado de datos para la tabla `conexiones`
--

INSERT INTO `conexiones` (`id`, `solicitante_id`, `receptor_id`, `estado`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 2, 'aceptada', '2026-10-18 02:29:08', '2026-10-18 02:29:08'),
(2, 2, 3, 'aceptada', '2026-10-18 02:29:08', '2026-10-18 02:29:08'),
(3, 3, 1, 'pendiente', '2026-10-18 02:29:08', '2026-10-18 02:29:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de habilidades';

--
-- Volcado de datos para la tabla `habilidades`
--

INSERT INTO `habilidades` (`id`, `nombre`) VALUES
(14, 'Atención al Cliente'),
(5, 'Bases de Datos'),
(6, 'Diseño Gráfico'),
(12, 'Educación y Docencia'),
(10, 'Emprendimiento'),
(13, 'Finanzas'),
(9, 'Gestión de Proyectos'),
(11, 'Inglés Profesional'),
(2, 'JavaScript'),
(8, 'Liderazgo'),
(7, 'Marketing Digital'),
(3, 'PHP'),
(1, 'Programación Web'),
(4, 'Python'),
(15, 'Redacción y Comunicación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(10) UNSIGNED NOT NULL,
  `remitente_id` int(10) UNSIGNED NOT NULL,
  `destinatario_id` int(10) UNSIGNED NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Mensajes directos entre egresados conectados';

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `remitente_id`, `destinatario_id`, `mensaje`, `leido`, `creado_en`) VALUES
(1, 1, 2, '¡Hola Santiago! Te damos la bienvenida a la plataforma oficial Raíces Alzate. Tu cuenta de egresado y tu perfil han sido verificados con éxito.', 1, '2026-10-18 00:29:08'),
(2, 2, 1, '¡Muchas gracias por la bienvenida! La plataforma se encuentra completamente operativa para toda la comunidad.', 1, '2026-10-18 00:59:08'),
(3, 1, 2, '[Respuesta Oficial PQRS Radicado PQR-2026-0001]: Estimado(a) Santiago Sánchez Emitola, le confirmamos que su solicitud sobre la actualización y entrega de su certificado digital de egresado ha sido atendida y aprobada por la Secretaría Académica. El documento se encuentra listo.', 0, '2026-10-18 01:59:08'),
(4, 2, 3, '¡Hola Alejandro! Qué bueno verte conectado en la red de egresados de Raíces Alzate. ¿Cómo van tus proyectos de diseño este año?', 1, '2026-10-18 01:44:08'),
(5, 3, 2, '¡Hola Santiago! Todo marcha excelente por acá. Muy buena la plataforma, revisando la sección de oportunidades y proyectos colaborativos.', 1, '2026-10-18 02:14:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(10) UNSIGNED NOT NULL,
  `autor_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(60) NOT NULL COMMENT 'Comunidad, Eventos, Destacados, Proyectos, Institucional...',
  `portada` varchar(255) DEFAULT NULL COMMENT 'Ruta relativa en uploads/noticias/',
  `estado` enum('borrador','pendiente','publicado','rechazado','archivado') NOT NULL DEFAULT 'pendiente',
  `revisado_por` int(10) UNSIGNED DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Noticias institucionales y de la comunidad';

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `autor_id`, `titulo`, `descripcion`, `categoria`, `portada`, `estado`, `revisado_por`, `fecha_publicacion`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Lanzamiento Oficial de la Plataforma Raíces Alzate', 'La Institución Educativa Gilberto Alzate Avendaño abre las puertas virtuales de su comunidad de egresados para fortalecer los lazos de fraternidad, proyectos y oportunidades profesionales.', 'Institucional', NULL, 'publicado', 1, '2026-10-18 02:29:08', '2026-10-18 02:29:08', '2026-10-18 02:29:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oportunidades`
--

CREATE TABLE `oportunidades` (
  `id` int(10) UNSIGNED NOT NULL,
  `autor_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `empresa` varchar(150) NOT NULL COMMENT 'Entidad que ofrece la oportunidad',
  `publicado_por` varchar(150) DEFAULT NULL COMMENT 'Dependencia o aliado que la difunde',
  `categoria` varchar(40) NOT NULL COMMENT 'Empleo, Beca, Pasantía, Capacitación, Convocatoria',
  `modalidad` varchar(100) NOT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `requisitos` text DEFAULT NULL,
  `beneficios` text DEFAULT NULL,
  `enlace_postulacion` varchar(255) DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `estado` enum('borrador','pendiente','publicado','rechazado','cerrado') NOT NULL DEFAULT 'pendiente',
  `revisado_por` int(10) UNSIGNED DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Empleos, becas, pasantías y convocatorias';

--
-- Volcado de datos para la tabla `oportunidades`
--

INSERT INTO `oportunidades` (`id`, `autor_id`, `titulo`, `empresa`, `publicado_por`, `categoria`, `modalidad`, `ubicacion`, `descripcion`, `requisitos`, `beneficios`, `enlace_postulacion`, `fecha_limite`, `estado`, `revisado_por`, `fecha_publicacion`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Convocatoria de Mentorías y Empleo Joven Alzatista 2026', 'Alianza Empresarial & IE Gilberto Alzate Avendaño', 'Coordinación de Egresados', 'Empleo', 'Híbrida', 'Medellín, Antioquia', 'Invitación a egresados técnicos y profesionales para vincularse a proyectos del sector tecnológico y empresarial.', 'Ser egresado alzatista\nDisponibilidad de tiempo completo o medio tiempo\nGanas de aprender y colaborar', 'Contrato formal con prestaciones de ley\nCapacitación continua\nAmbiente colaborativo', 'https://raices-alzate.xo.je', '2026-12-31', 'publicado', 1, '2026-10-18 02:29:09', '2026-10-18 02:29:09', '2026-10-18 02:29:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expira_en` datetime NOT NULL,
  `usado_en` datetime DEFAULT NULL,
  `ip_solicitud` varchar(45) DEFAULT NULL COMMENT 'IPv4 o IPv6',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tokens de un solo uso para restablecer contraseña';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles_egresados`
--

CREATE TABLE `perfiles_egresados` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `documento` varchar(20) DEFAULT NULL COMMENT 'Documento de identidad para verificación',
  `bio` text DEFAULT NULL,
  `genero` varchar(30) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `email_secundario` varchar(255) DEFAULT NULL,
  `titulo_institucional` varchar(150) DEFAULT NULL,
  `anio_graduacion` smallint(5) UNSIGNED DEFAULT NULL,
  `programa_superior` varchar(150) DEFAULT NULL,
  `institucion_superior` varchar(150) DEFAULT NULL,
  `ocupacion` varchar(120) DEFAULT NULL,
  `empresa` varchar(120) DEFAULT NULL,
  `area_especialidad` varchar(120) DEFAULT NULL,
  `anios_experiencia` tinyint(3) UNSIGNED DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `red_adicional` varchar(255) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL COMMENT 'Ruta relativa en uploads/',
  `foto_portada` varchar(255) DEFAULT NULL COMMENT 'Ruta relativa en uploads/',
  `perfil_publico` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Volcado de datos para la tabla `perfiles_egresados`
--

INSERT INTO `perfiles_egresados` (`id`, `usuario_id`, `documento`, `bio`, `genero`, `fecha_nacimiento`, `ciudad`, `direccion`, `telefono`, `email_secundario`, `titulo_institucional`, `anio_graduacion`, `programa_superior`, `institucion_superior`, `ocupacion`, `empresa`, `area_especialidad`, `anios_experiencia`, `linkedin`, `github`, `sitio_web`, `red_adicional`, `foto_perfil`, `foto_portada`, `perfil_publico`, `creado_en`, `actualizado_en`) VALUES
(2, 2, '1017200801', 'Desarrollador de software y egresado de la IE Gilberto Alzate Avendaño. Líder del proyecto tecnológico Raíces Alzate y apasionado por la innovación y colaboración entre egresados.', 'Masculino', NULL, 'Medellín', 'Comuna 10 - La Candelaria', '3001234567', 'santiago.sanchez@ejemplo.com', 'Bachiller Académico con Énfasis en Tecnología', 2021, 'Ingeniería de Sistemas', 'Universidad de Antioquia', 'Desarrollador Full Stack', 'Alzate Tech Studio', 'Desarrollo Web y Software', 3, 'https://linkedin.com/in/santiago-emitola', 'https://github.com/emitolasanchez2008', 'https://raices-alzate.xo.je', NULL, NULL, NULL, 1, '2026-10-18 02:29:08', '2026-10-18 02:29:08'),
(3, 3, '1017200802', 'Egresado de la IE Gilberto Alzate Avendaño. Diseñador multimedia enfocado en proyectos creativos, gestión de proyectos y emprendimiento juvenil.', 'Masculino', NULL, 'Medellín', 'Medellín, Antioquia', '3109876543', 'alejandro.emitola@ejemplo.com', 'Bachiller Técnico en Multimedia', 2022, 'Diseño Digital y Comunicación', 'ITM Institución Universitaria', 'Diseñador UI/UX & Emprendedor', 'Studio Creativo Alzate', 'Diseño y Creatividad Digital', 2, 'https://linkedin.com/in/alejandro-emitola', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-18 02:29:08', '2026-10-18 02:29:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_habilidades`
--

CREATE TABLE `perfil_habilidades` (
  `perfil_id` int(10) UNSIGNED NOT NULL,
  `habilidad_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Habilidades asociadas a cada perfil';

--
-- Volcado de datos para la tabla `perfil_habilidades`
--

INSERT INTO `perfil_habilidades` (`perfil_id`, `habilidad_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 8),
(2, 9),
(3, 6),
(3, 7),
(3, 10),
(3, 14),
(3, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pqrs`
--

CREATE TABLE `pqrs` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del egresado/usuario en la plataforma si inició sesión',
  `radicado` varchar(40) NOT NULL,
  `tipo` varchar(50) NOT NULL COMMENT 'Petición, Queja, Reclamo, Sugerencia, Felicitación, Error técnico',
  `nombre` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta de captura o soporte en uploads/pqrs/',
  `estado` enum('pendiente','en_revision','respondido','cerrado') NOT NULL DEFAULT 'pendiente',
  `prioridad` enum('Baja','Media','Alta') NOT NULL DEFAULT 'Media',
  `respuesta` text DEFAULT NULL,
  `respondido_por` int(10) UNSIGNED DEFAULT NULL,
  `respondido_en` datetime DEFAULT NULL COMMENT 'Fecha y hora en que la administración respondió',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='PQRS y reportes de la comunidad educativa';

--
-- Volcado de datos para la tabla `pqrs`
--

INSERT INTO `pqrs` (`id`, `usuario_id`, `radicado`, `tipo`, `nombre`, `email`, `asunto`, `mensaje`, `imagen`, `estado`, `prioridad`, `respuesta`, `respondido_por`, `respondido_en`, `creado_en`, `actualizado_en`) VALUES
(1, 2, 'PQR-2026-0001', 'Petición', 'Santiago Sánchez Emitola', 'emitolasanchez2008@gmail.com', 'Solicitud de certificado digital de graduación', 'Buenas tardes, solicito la expedición del certificado digital de graduación de la promoción 2021 para trámites académicos.', NULL, 'respondido', 'Media', 'Estimado(a) Santiago Sánchez Emitola, le confirmamos que su solicitud sobre la actualización y entrega de su certificado digital de egresado ha sido atendida y aprobada por la Secretaría Académica. El documento se encuentra listo.', 1, '2026-10-18 02:29:08', '2026-10-17 02:29:08', '2026-10-18 02:29:08'),
(2, 3, 'PQR-2026-0002', 'Sugerencia', 'Alejandro Emitola', 'emitolaalejandro@gmail.com', 'Propuesta de feria de emprendimiento y diseño', 'Cordial saludo a las directivas. Propongo organizar una muestra virtual o presencial para egresados emprendedores de las promociones recientes.', NULL, 'respondido', 'Media', 'Solicitud atendida y resuelta por la administración institucional de Raíces Alzate.', 1, '2026-10-22 09:10:48', '2026-10-17 21:29:08', '2026-10-22 09:10:48'),
(3, NULL, 'RA-2026-7545', 'Reporte de error técnico', 'Santiago Emitola', 'emitolaalejandro@gmail.com', 'Pagina de egresados', 'Reporto que el dev no hace nada.', 'uploads/pqrs/pqr_1792597144_4575.jpeg', 'respondido', 'Alta', 'Bueno ya lo regaño', 1, '2026-10-21 11:01:05', '2026-10-21 10:39:04', '2026-10-21 11:01:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(10) UNSIGNED NOT NULL,
  `autor_id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(80) NOT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `portada` varchar(255) DEFAULT NULL COMMENT 'Ruta relativa en uploads/proyectos/',
  `estado` enum('borrador','pendiente','publicado','rechazado','archivado') NOT NULL DEFAULT 'pendiente',
  `revisado_por` int(10) UNSIGNED DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Proyectos publicados por la comunidad';

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `autor_id`, `titulo`, `descripcion`, `categoria`, `enlace`, `portada`, `estado`, `revisado_por`, `fecha_publicacion`, `creado_en`, `actualizado_en`) VALUES
(1, 2, 'Raíces Alzate - Plataforma de Gestión y Red de Egresados', 'Plataforma comunitaria diseñada para conectar a los egresados de la IE Gilberto Alzate Avendaño con oportunidades de empleo, mentorías, proyectos colaborativos y soporte institucional.', 'Tecnología e Innovación', 'https://raices-alzate.xo.je', NULL, 'publicado', 1, '2026-10-18 02:29:08', '2026-10-18 02:29:08', '2026-10-18 02:29:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(30) NOT NULL COMMENT 'Identificador interno: administrador, egresado, estudiante',
  `descripcion` varchar(255) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Roles de acceso de la plataforma';

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `creado_en`) VALUES
(1, 'administrador', 'Gestiona usuarios, aprueba registros y modera publicaciones', '2026-10-18 02:29:02'),
(2, 'egresado', 'Egresado verificado de la institución', '2026-10-18 02:29:02'),
(3, 'estudiante', 'Estudiante activo de la institución', '2026-10-18 02:29:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `rol_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `nombre_completo` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT 'Resultado de password_hash(); nunca texto plano',
  `estado_verificacion` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente' COMMENT 'Aprobación del registro desde el panel de administración',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `token_recuperacion` varchar(64) DEFAULT NULL COMMENT 'Hash SHA-256 del token de recuperación criptográfico',
  `expiracion_token` datetime DEFAULT NULL COMMENT 'Fecha y hora de expiración del token (1 hora máx)',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `nombre_completo`, `email`, `password_hash`, `estado_verificacion`, `activo`, `ultimo_acceso`, `token_recuperacion`, `expiracion_token`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'Administrador General', 'admin@alzate.edu.co', '$2y$10$LJOu47/WucddiC9uiOGBF.8SStqiBQnvEh5y5E6PkShp7glTqRKj6', 'aprobado', 1, '2026-11-05 12:45:10', NULL, NULL, '2026-10-18 02:29:07', '2026-11-05 12:45:10'),
(2, 2, 'Santiago Sánchez Emitola', 'emitolasanchez2008@gmail.com', '$2y$10$LJOu47/WucddiC9uiOGBF.8SStqiBQnvEh5y5E6PkShp7glTqRKj6', 'aprobado', 1, NULL, NULL, NULL, '2026-10-18 02:29:07', '2026-10-18 02:29:07'),
(3, 2, 'Alejandro Emitola', 'emitolaalejandro@gmail.com', '$2y$10$92JR45Ss0lhuzwBHEmPPlue.B/nde/7AwU.5yq1cEv.4aPghpcN26', 'aprobado', 1, '2026-10-22 11:11:43', NULL, NULL, '2026-10-18 02:29:07', '2026-10-22 11:11:43');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conexiones`
--
ALTER TABLE `conexiones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_conexiones_par` (`solicitante_id`,`receptor_id`),
  ADD KEY `idx_conexiones_solicitante` (`solicitante_id`,`estado`),
  ADD KEY `idx_conexiones_receptor` (`receptor_id`,`estado`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_habilidades_nombre` (`nombre`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mensajes_conversacion` (`remitente_id`,`destinatario_id`,`creado_en`),
  ADD KEY `idx_mensajes_destinatario_leido` (`destinatario_id`,`leido`),
  ADD KEY `idx_mensajes_creado` (`creado_en`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_noticias_autor` (`autor_id`),
  ADD KEY `idx_noticias_estado_fecha` (`estado`,`fecha_publicacion`),
  ADD KEY `idx_noticias_categoria` (`categoria`),
  ADD KEY `idx_noticias_revisor` (`revisado_por`);
ALTER TABLE `noticias` ADD FULLTEXT KEY `ft_noticias_busqueda` (`titulo`,`descripcion`);

--
-- Indices de la tabla `oportunidades`
--
ALTER TABLE `oportunidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_oportunidades_autor` (`autor_id`),
  ADD KEY `idx_oportunidades_estado_fecha` (`estado`,`fecha_publicacion`),
  ADD KEY `idx_oportunidades_categoria` (`categoria`),
  ADD KEY `idx_oportunidades_limite` (`fecha_limite`),
  ADD KEY `idx_oportunidades_revisor` (`revisado_por`);
ALTER TABLE `oportunidades` ADD FULLTEXT KEY `ft_oportunidades_busqueda` (`titulo`,`empresa`,`descripcion`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_password_resets_token` (`token_hash`),
  ADD KEY `idx_password_resets_usuario` (`usuario_id`,`usado_en`),
  ADD KEY `idx_password_resets_expira` (`expira_en`);

--
-- Indices de la tabla `perfiles_egresados`
--
ALTER TABLE `perfiles_egresados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_perfiles_usuario` (`usuario_id`),
  ADD UNIQUE KEY `uq_perfiles_documento` (`documento`),
  ADD KEY `idx_perfiles_anio` (`anio_graduacion`),
  ADD KEY `idx_perfiles_ciudad` (`ciudad`);

--
-- Indices de la tabla `perfil_habilidades`
--
ALTER TABLE `perfil_habilidades`
  ADD PRIMARY KEY (`perfil_id`,`habilidad_id`),
  ADD KEY `idx_perfil_habilidades_habilidad` (`habilidad_id`);

--
-- Indices de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pqrs_radicado` (`radicado`),
  ADD KEY `idx_pqrs_usuario` (`usuario_id`),
  ADD KEY `idx_pqrs_estado_prioridad` (`estado`,`prioridad`),
  ADD KEY `idx_pqrs_email` (`email`),
  ADD KEY `idx_pqrs_creado` (`creado_en`),
  ADD KEY `fk_pqrs_respondido_por` (`respondido_por`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proyectos_autor` (`autor_id`),
  ADD KEY `idx_proyectos_estado_fecha` (`estado`,`fecha_publicacion`),
  ADD KEY `idx_proyectos_categoria` (`categoria`),
  ADD KEY `idx_proyectos_revisor` (`revisado_por`);
ALTER TABLE `proyectos` ADD FULLTEXT KEY `ft_proyectos_busqueda` (`titulo`,`descripcion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_roles_nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_rol` (`rol_id`),
  ADD KEY `idx_usuarios_verificacion` (`estado_verificacion`,`activo`),
  ADD KEY `idx_usuarios_token` (`token_recuperacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conexiones`
--
ALTER TABLE `conexiones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `oportunidades`
--
ALTER TABLE `oportunidades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfiles_egresados`
--
ALTER TABLE `perfiles_egresados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pqrs`
--
ALTER TABLE `pqrs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conexiones`
--
ALTER TABLE `conexiones`
  ADD CONSTRAINT `fk_conexiones_receptor` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_conexiones_solicitante` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_mensajes_destinatario` FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mensajes_remitente` FOREIGN KEY (`remitente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `fk_noticias_autor` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_noticias_revisor` FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `oportunidades`
--
ALTER TABLE `oportunidades`
  ADD CONSTRAINT `fk_oportunidades_autor` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oportunidades_revisor` FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `perfiles_egresados`
--
ALTER TABLE `perfiles_egresados`
  ADD CONSTRAINT `fk_perfiles_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `perfil_habilidades`
--
ALTER TABLE `perfil_habilidades`
  ADD CONSTRAINT `fk_ph_habilidad` FOREIGN KEY (`habilidad_id`) REFERENCES `habilidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ph_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfiles_egresados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pqrs`
--
ALTER TABLE `pqrs`
  ADD CONSTRAINT `fk_pqrs_respondido_por` FOREIGN KEY (`respondido_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pqrs_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `fk_proyectos_autor` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proyectos_revisor` FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
