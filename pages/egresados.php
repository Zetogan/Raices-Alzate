<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantalla: Directorio de Egresados (Conectado a MySQL)
 * Versión: 0.2.1
 */

ob_start();

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/helpers.php';

$currentPage = 'egresados';
$pageTitle = 'Directorio de Egresados | ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');

// Si no está autenticado, redirigir a login o mostrar vista pública institucional
if (!isAuthenticated() && (!isset($_GET['view']) || $_GET['view'] !== 'panel')) {
    require_once __DIR__ . '/../includes/header.php';
    require_once __DIR__ . '/egresados-public.php';
    require_once __DIR__ . '/../includes/footer.php';
    ob_end_flush();
    exit;
}

// 1. Obtener datos del usuario autenticado
$currentUser   = currentUser();
$currentUserId = (int)($currentUser['id'] ?? 0);

// Promoción del usuario autenticado
$miPromocion = null;
if ($currentUserId > 0 && !empty($pdo)) {
    try {
        $stmtMiP = $pdo->prepare("SELECT anio_graduacion FROM perfiles_egresados WHERE usuario_id = :uid LIMIT 1");
        $stmtMiP->execute([':uid' => $currentUserId]);
        $miPromocion = $stmtMiP->fetchColumn() ?: null;
    } catch (PDOException $e) {
        $miPromocion = null;
    }
}
$miPromocionYear = $miPromocion ? (string)$miPromocion : '2021';

// 2. Cargar habilidades mapeadas por perfil_id
$habilidadesPorPerfil = [];
if (!empty($pdo)) {
    try {
        $stmtHabs = $pdo->query("
            SELECT ph.perfil_id, h.nombre
            FROM perfil_habilidades ph
            JOIN habilidades h ON h.id = ph.habilidad_id
            ORDER BY h.nombre ASC
        ");
        while ($row = $stmtHabs->fetch(PDO::FETCH_ASSOC)) {
            $habilidadesPorPerfil[$row['perfil_id']][] = $row['nombre'];
        }
    } catch (PDOException $e) {
        $habilidadesPorPerfil = [];
    }
}

// 3. Cargar conexiones del usuario autenticado desde MySQL
$conexionesUsuario = [];
if ($currentUserId > 0 && !empty($pdo)) {
    try {
        $stmtCon = $pdo->prepare("
            SELECT solicitante_id, receptor_id, estado
            FROM conexiones
            WHERE solicitante_id = :uid1 OR receptor_id = :uid2
        ");
        $stmtCon->execute([
            ':uid1' => $currentUserId,
            ':uid2' => $currentUserId
        ]);
        while ($cRow = $stmtCon->fetch(PDO::FETCH_ASSOC)) {
            $otroId = ((int)$cRow['solicitante_id'] === $currentUserId) ? (int)$cRow['receptor_id'] : (int)$cRow['solicitante_id'];
            $cRow['otro_id'] = $otroId;
            $conexionesUsuario[$otroId] = $cRow;
        }
    } catch (PDOException $e) {
        $conexionesUsuario = [];
    }
}

// 4. Cargar egresados reales de la base de datos
$egresadosList = [];
$promocionesDisponibles = [];
$totalEgresados = 0;
$totalMiPromocion = 0;
$totalDestacados = 0;
$totalConectados = 0;

if (!empty($pdo)) {
    try {
        $stmtEgresados = $pdo->prepare("
            SELECT 
                u.id,
                u.nombre_completo,
                u.email,
                u.creado_en,
                r.nombre AS rol_nombre,
                pe.id AS perfil_id,
                pe.documento,
                pe.bio,
                pe.genero,
                pe.ciudad,
                pe.direccion,
                pe.telefono,
                pe.email_secundario,
                pe.titulo_institucional,
                pe.anio_graduacion,
                pe.programa_superior,
                pe.institucion_superior,
                pe.ocupacion,
                pe.empresa,
                pe.area_especialidad,
                pe.anios_experiencia,
                pe.linkedin,
                pe.github,
                pe.sitio_web,
                pe.foto_perfil
            FROM usuarios u
            JOIN roles r ON r.id = u.rol_id
            LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
            WHERE (r.nombre = 'egresado' OR pe.id IS NOT NULL)
              AND u.activo = 1
              AND (pe.perfil_publico = 1 OR pe.perfil_publico IS NULL)
            ORDER BY 
                CASE WHEN u.id = :cur_id THEN 0 ELSE 1 END,
                pe.anio_graduacion DESC, 
                u.nombre_completo ASC
        ");
        $stmtEgresados->execute([':cur_id' => $currentUserId]);
        $rows = $stmtEgresados->fetchAll(PDO::FETCH_ASSOC);

        $avatarColors = [
            'egresado-avatar-blue',
            'egresado-avatar-teal',
            'egresado-avatar-purple',
            'egresado-avatar-orange',
            'egresado-avatar-red',
            'egresado-avatar-green'
        ];
        $colorIndex = 0;

        foreach ($rows as $row) {
            $uid = (int)$row['id'];
            $perfilId = (int)($row['perfil_id'] ?? 0);
            $skills = $habilidadesPorPerfil[$perfilId] ?? [];

            // Nombre e inicial
            $nombre = trim($row['nombre_completo'] ?? '');
            $inicial = !empty($nombre) ? mb_strtoupper(mb_substr($nombre, 0, 1, 'UTF-8'), 'UTF-8') : 'U';

            // Promoción
            $promo = !empty($row['anio_graduacion']) ? (string)$row['anio_graduacion'] : 'Alzatista';
            if (!empty($row['anio_graduacion']) && !in_array((int)$row['anio_graduacion'], $promocionesDisponibles)) {
                $promocionesDisponibles[] = (int)$row['anio_graduacion'];
            }

            // Carrera / Titulación
            $carrera = !empty($row['titulo_institucional']) ? $row['titulo_institucional'] : 'Bachiller Institucional';

            // Énfasis normalizado para filtros
            $enfasisLower = mb_strtolower($carrera, 'UTF-8');
            if (strpos($enfasisLower, 'sistema') !== false || strpos($enfasisLower, 'software') !== false || strpos($enfasisLower, 'informática') !== false) {
                $enfasisSlug = 'sistemas';
            } elseif (strpos($enfasisLower, 'comercial') !== false || strpos($enfasisLower, 'contab') !== false || strpos($enfasisLower, 'gestión') !== false) {
                $enfasisSlug = 'comercial';
            } else {
                $enfasisSlug = 'academico';
            }

            // Ciudad normalizada para filtros
            $ciudadRaw = trim($row['ciudad'] ?? '');
            $ciudadLower = mb_strtolower($ciudadRaw, 'UTF-8');
            if (strpos($ciudadLower, 'bogot') !== false) {
                $ciudadSlug = 'bogota';
            } elseif (strpos($ciudadLower, 'cali') !== false) {
                $ciudadSlug = 'cali';
            } elseif (strpos($ciudadLower, 'exterior') !== false || strpos($ciudadLower, 'remoto') !== false) {
                $ciudadSlug = 'exterior';
            } else {
                $ciudadSlug = 'medellin';
            }

            // Ocupación y empresa
            $ocupacion = trim($row['ocupacion'] ?? '');
            $empresa = trim($row['empresa'] ?? '');
            $ocupacionEmpresa = '';
            if (!empty($ocupacion) && !empty($empresa)) {
                $ocupacionEmpresa = $ocupacion . ' en ' . $empresa;
            } elseif (!empty($ocupacion)) {
                $ocupacionEmpresa = $ocupacion;
            } elseif (!empty($empresa)) {
                $ocupacionEmpresa = $empresa;
            } else {
                $ocupacionEmpresa = 'Egresado Alzatista';
            }

            // Ubicación completa
            $ubicacion = !empty($row['direccion']) ? $row['direccion'] : (!empty($row['ciudad']) ? $row['ciudad'] : 'Medellín, Colombia');

            // Completitud del perfil y destacado
            $porcentaje = calcularCompletitudPerfil($row, $row, $skills);
            $esDestacado = ($porcentaje >= 75);

            // Estado de conexión
            $conexionEstado = 'no_conectado';
            if ($uid === $currentUserId) {
                $conexionEstado = 'propio';
            } elseif (isset($conexionesUsuario[$uid])) {
                $conData = $conexionesUsuario[$uid];
                if ($conData['estado'] === 'aceptada') {
                    $conexionEstado = 'conectado';
                } elseif ($conData['estado'] === 'pendiente') {
                    $conexionEstado = ((int)$conData['solicitante_id'] === $currentUserId) ? 'solicitud_enviada' : 'solicitud_recibida';
                }
            }

            if ($conexionEstado === 'conectado') {
                $totalConectados++;
            }
            if ($esDestacado) {
                $totalDestacados++;
            }
            if ($promo === (string)$miPromocionYear) {
                $totalMiPromocion++;
            }

            $avatarColor = $avatarColors[$colorIndex % count($avatarColors)];
            $colorIndex++;

            $egresadosList[] = [
                'id'                => $uid,
                'perfil_id'         => $perfilId,
                'nombre'            => $nombre,
                'inicial'           => $inicial,
                'carrera'           => $carrera,
                'promocion'         => $promo,
                'enfasis'           => $enfasisSlug,
                'ciudad'            => $ciudadRaw ?: 'Medellín, Colombia',
                'ciudad_slug'       => $ciudadSlug,
                'ocupacion'         => $ocupacion,
                'empresa'           => $empresa,
                'ocupacion_empresa' => $ocupacionEmpresa,
                'ubicacion'         => $ubicacion,
                'habilidades'       => $skills,
                'foto_perfil'       => $row['foto_perfil'] ?? null,
                'porcentaje'        => $porcentaje,
                'es_destacado'      => $esDestacado,
                'conexion_estado'   => $conexionEstado,
                'avatar_color'      => $avatarColor
            ];
        }

        rsort($promocionesDisponibles);
        $totalEgresados = count($egresadosList);

    } catch (PDOException $e) {
        error_log('Error cargando egresados: ' . $e->getMessage());
    }
}

// Si la lista de promociones está vacía, proveer fallback canónico
if (empty($promocionesDisponibles)) {
    $promocionesDisponibles = [2024, 2023, 2022, 2021, 2020, 2019, 2018];
}

$seccion_activa = 'egresados';
$titulo_seccion = 'Egresados';
$titulo_pagina  = 'Directorio de Egresados — ' . (defined('APP_NAME') ? APP_NAME : 'Raíces Alzate');

require_once __DIR__ . '/../includes/egresados-privado.php';

ob_end_flush();
