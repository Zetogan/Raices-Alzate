<?php
/**
 * Raíces Alzate - Helper de Autenticación y Manejo Canónico de Sesiones
 * Plataforma: Raíces Alzate - IE Gilberto Alzate Avendaño
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

/**
 * Obtiene los datos del usuario actualmente autenticado en formato canónico.
 * 
 * Estructura canónica:
 * [
 *   'id'     => int,
 *   'nombre' => string,
 *   'email'  => string,
 *   'rol'    => string,
 *   'rol_id' => int
 * ]
 *
 * @return array|null Datos del usuario o null si no está autenticado
 */
function currentUser(): ?array {
    if (!empty($_SESSION['user']) && is_array($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
        return $_SESSION['user'];
    }

    // Compatibilidad con código previo que usaba $_SESSION['usuario']
    if (!empty($_SESSION['usuario']) && is_array($_SESSION['usuario']) && !empty($_SESSION['usuario']['id'])) {
        return [
            'id'     => (int)$_SESSION['usuario']['id'],
            'nombre' => (string)($_SESSION['usuario']['nombre'] ?? ''),
            'email'  => (string)($_SESSION['usuario']['email'] ?? ''),
            'rol'    => (string)($_SESSION['usuario']['rol'] ?? ''),
            'rol_id' => (int)($_SESSION['usuario']['rol_id'] ?? 0),
        ];
    }

    // Compatibilidad con llaves escalares sueltas
    if (!empty($_SESSION['user_id']) || !empty($_SESSION['usuario_id'])) {
        $id = (int)($_SESSION['user_id'] ?? $_SESSION['usuario_id']);
        return [
            'id'     => $id,
            'nombre' => (string)($_SESSION['nombre'] ?? ''),
            'email'  => (string)($_SESSION['email'] ?? ''),
            'rol'    => (string)($_SESSION['rol'] ?? ''),
            'rol_id' => (int)($_SESSION['rol_id'] ?? 0),
        ];
    }

    return null;
}

/**
 * Establece la sesión de usuario de forma canónica y con retrocompatibilidad completa
 * para no romper ningún componente existente del sistema.
 *
 * @param array $user Registro del usuario obtenido de la base de datos (con join a roles)
 */
function setAuthSession(array $user): void {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    $canonical = [
        'id'      => (int)($user['id'] ?? 0),
        'nombre'  => (string)($user['nombre_completo'] ?? $user['nombre'] ?? ''),
        'email'   => (string)($user['email'] ?? ''),
        'rol'     => (string)($user['rol_nombre'] ?? $user['rol'] ?? ''),
        'rol_id'  => (int)($user['rol_id'] ?? 0),
    ];

    // Estructura canónica
    $_SESSION['user'] = $canonical;

    // Variables de retrocompatibilidad identificadas en las vistas y controladores del proyecto
    $_SESSION['usuario']    = $canonical;
    $_SESSION['id']         = $canonical['id'];
    $_SESSION['user_id']    = $canonical['id'];
    $_SESSION['usuario_id'] = $canonical['id'];
    $_SESSION['nombre']     = $canonical['nombre'];
    $_SESSION['email']      = $canonical['email'];
    $_SESSION['rol']        = $canonical['rol'];
    $_SESSION['rol_id']     = $canonical['rol_id'];
    $_SESSION['autenticado']= true;
    $_SESSION['logged_in']  = true;
}

/**
 * Limpia la sesión de autenticación sin destruir otros datos temporales si los hubiera.
 */
function clearAuthSession(): void {
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    unset(
        $_SESSION['user'],
        $_SESSION['usuario'],
        $_SESSION['id'],
        $_SESSION['user_id'],
        $_SESSION['usuario_id'],
        $_SESSION['nombre'],
        $_SESSION['email'],
        $_SESSION['rol'],
        $_SESSION['rol_id'],
        $_SESSION['autenticado'],
        $_SESSION['logged_in']
    );
}

/**
 * Determina si hay una sesión de usuario válida activa.
 *
 * @return bool
 */
function isAuthenticated(): bool {
    return currentUser() !== null;
}

/**
 * Comprueba si el usuario autenticado tiene un rol específico.
 *
 * @param string $rol Nombre del rol (ej: 'administrador', 'egresado', 'estudiante')
 * @return bool
 */
function hasRole(string $rol): bool {
    $user = currentUser();
    if (!$user) {
        return false;
    }
    return strcasecmp($user['rol'], $rol) === 0;
}

/**
 * Calcula de forma fiable y consistente el porcentaje de completitud del perfil
 * a partir de los datos realmente almacenados en la base de datos.
 *
 * @param array $usuario Datos del usuario (tabla usuarios)
 * @param array $perfil Datos del perfil (tabla perfiles_egresados)
 * @param array $habilidades Array de habilidades asociadas (tabla perfil_habilidades)
 * @return int Porcentaje entre 0 y 100
 */
function calcularCompletitudPerfil(array $usuario, array $perfil, array $habilidades = []): int {
    $campos = [
        'nombre'               => !empty(trim($usuario['nombre_completo'] ?? $usuario['nombre'] ?? '')),
        'email'                => !empty(trim($usuario['email'] ?? '')),
        'bio'                  => !empty(trim($perfil['bio'] ?? '')),
        'genero'               => !empty(trim($perfil['genero'] ?? '')),
        'fecha_nacimiento'     => !empty(trim($perfil['fecha_nacimiento'] ?? '')),
        'ciudad'               => !empty(trim($perfil['ciudad'] ?? '')),
        'direccion'            => !empty(trim($perfil['direccion'] ?? '')),
        'telefono'             => !empty(trim($perfil['telefono'] ?? '')),
        'email_secundario'     => !empty(trim($perfil['email_secundario'] ?? '')),
        'titulo_institucional' => !empty(trim($perfil['titulo_institucional'] ?? '')),
        'anio_graduacion'      => !empty($perfil['anio_graduacion']),
        'programa_superior'    => !empty(trim($perfil['programa_superior'] ?? '')),
        'institucion_superior' => !empty(trim($perfil['institucion_superior'] ?? '')),
        'ocupacion'            => !empty(trim($perfil['ocupacion'] ?? '')),
        'empresa'              => !empty(trim($perfil['empresa'] ?? '')),
        'area_especialidad'    => !empty(trim($perfil['area_especialidad'] ?? '')),
        'anios_experiencia'    => !empty($perfil['anios_experiencia']),
        'habilidades'          => !empty($habilidades),
        'linkedin'             => !empty(trim($perfil['linkedin'] ?? '')),
        'github'               => !empty(trim($perfil['github'] ?? '')),
        'sitio_web'            => !empty(trim($perfil['sitio_web'] ?? '')),
        'foto_perfil'          => !empty(trim($perfil['foto_perfil'] ?? ''))
    ];

    $total = count($campos);
    $completos = count(array_filter($campos));
    return (int)round(($completos / $total) * 100);
}

