<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Pantallas Administrativas Separadas:
 *   - Dashboard: Gráficas (Registros, PQRS, Blog) y Métricas Generales
 *   - Control de Usuarios: CRUD Integral (Revisar, Modificar, Eliminar, Crear)
 *   - Aprobación de Registros: Verificación de Egresados
 *   - Moderación de Contenidos: Blog, Noticias, Proyectos y Convocatorias
 *   - Resolución de PQRS: Atención Ciudadana con Soporte Oficial por Correo
 * Paleta Institucional: #D32F2F (Rojo Alzate), #1565C0 (Azul Alzate), #212121, #757575, #E0E0E0, #F5F5F5, #FFFFFF
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/mailer.php';

// 1. Verificación estricta de autorización: solo administradores
if (!isAuthenticated() || !hasRole('administrador')) {
    header('Location: index.php?page=login');
    exit;
}

$adminUser   = currentUser();
$userName    = trim($adminUser['nombre'] ?? 'Administrador');
$userRole    = trim($adminUser['rol'] ?? 'Administrador');
$userEmail   = trim($adminUser['email'] ?? 'admin@alzate.edu.co');
$userInitial = !empty($userName) ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : 'A';
$adminId     = (int)($adminUser['id'] ?? 0);

// 2. Determinación de la pantalla solicitada
$pageParam = trim($_GET['page'] ?? '');
if ($pageParam === 'admin-usuarios') {
    $tabActiva = 'usuarios';
} elseif ($pageParam === 'admin-registros') {
    $tabActiva = 'registros';
} elseif ($pageParam === 'admin-contenidos') {
    $tabActiva = 'contenidos';
} elseif ($pageParam === 'admin-pqrs') {
    $tabActiva = 'pqrs';
} elseif ($pageParam === 'admin-dashboard') {
    $tabActiva = 'dashboard';
} else {
    $tabActiva = trim($_GET['tab'] ?? ($_GET['view'] ?? 'dashboard'));
    if (!in_array($tabActiva, ['dashboard', 'usuarios', 'registros', 'contenidos', 'pqrs'], true)) {
        $tabActiva = 'dashboard';
    }
}

// Configuración de títulos, badges y subtítulos dedicados por pantalla
$pantallasConfig = [
    'dashboard' => [
        'pill'      => 'Métricas y Rendimiento',
        'titulo'    => 'Dashboard Institucional',
        'subtitulo' => 'Supervisa en tiempo real las estadísticas de registros, atención de PQRS y actividad del blog institucional.'
    ],
    'usuarios' => [
        'pill'      => 'Directorio y Accesos',
        'titulo'    => 'Control de Usuarios',
        'subtitulo' => 'Administra todas las cuentas de la plataforma: inspecciona perfiles, modifica roles, credenciales y estados de acceso.'
    ],
    'registros' => [
        'pill'      => 'Verificación de Egresados',
        'titulo'    => 'Aprobación de Registros',
        'subtitulo' => 'Supervisa y valida las solicitudes de inscripción de nuevos egresados para autorizar su ingreso a la comunidad.'
    ],
    'contenidos' => [
        'pill'      => 'Publicaciones y Comunidad',
        'titulo'    => 'Moderación de Blog',
        'subtitulo' => 'Revisa, valida y aprueba noticias, proyectos de egresados y convocatorias antes de publicarse en el portal.'
    ],
    'pqrs' => [
        'pill'      => 'Atención Ciudadana',
        'titulo'    => 'Resolución de PQRS',
        'subtitulo' => 'Atiende, gestiona y responde oportunamente las peticiones, quejas, reclamos y reportes técnicos ciudadanos.'
    ],
];

$pantallaActual = $pantallasConfig[$tabActiva];
$seccion_activa = 'administracion';
$currentPage    = 'administracion';
$titulo_seccion = $pantallaActual['titulo'];
$topbarTitle    = $titulo_seccion;
$titulo_pagina  = $pantallaActual['titulo'] . ' — Administración Raíces Alzate';
$pageTitle      = $titulo_pagina;

$extraCss = ['dashboard.css', 'administracion.css'];
$extraJs  = ['assets/js/dashboard.js'];

// 3. Procesamiento de Acciones Backend (CRUD Usuarios, Moderación, PQRS, Registros)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
           || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
           || (isset($_POST['is_ajax']) && $_POST['is_ajax'] === '1');

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token CSRF de seguridad inválido o expirado.']);
            exit;
        }
        $_SESSION['admin_flash_error'] = 'Token de seguridad inválido. Intenta nuevamente.';
        header('Location: index.php?page=administracion&tab=' . urlencode($tabActiva));
        exit;
    }

    $action = trim($_POST['action'] ?? '');

    // =========================================================================
    // CRUD: CREAR USUARIO
    // =========================================================================
    if ($action === 'crear_usuario') {
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rolId = filter_var($_POST['rol_id'] ?? 2, FILTER_VALIDATE_INT);
        $estadoVerif = in_array($_POST['estado_verificacion'] ?? '', ['pendiente', 'aprobado', 'rechazado'], true) 
            ? $_POST['estado_verificacion'] 
            : 'aprobado';
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 1;

        if (empty($nombreCompleto) || empty($email) || empty($password)) {
            $msg = 'Todos los campos obligatorios deben ser diligenciados.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'El correo electrónico no tiene un formato válido.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }

        if (strlen($password) < 6) {
            $msg = 'La contraseña debe tener como mínimo 6 caracteres.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }

        try {
            $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
            $stmtCheck->execute([':email' => $email]);
            if ($stmtCheck->fetch()) {
                $msg = 'Ya existe una cuenta registrada con el correo ' . htmlspecialchars($email) . '.';
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(409);
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                $_SESSION['admin_flash_error'] = $msg;
                header('Location: index.php?page=administracion&tab=usuarios');
                exit;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare("
                INSERT INTO usuarios (rol_id, nombre_completo, email, password_hash, estado_verificacion, activo, creado_en, actualizado_en)
                VALUES (:rol_id, :nombre, :email, :pass, :verif, :activo, NOW(), NOW())
            ");
            $stmtInsert->execute([
                ':rol_id' => $rolId,
                ':nombre' => $nombreCompleto,
                ':email'  => $email,
                ':pass'   => $passwordHash,
                ':verif'  => $estadoVerif,
                ':activo' => $activo
            ]);
            $nuevoId = (int)$pdo->lastInsertId();

            if ($rolId === 2) {
                $stmtPerfil = $pdo->prepare("INSERT IGNORE INTO perfiles_egresados (usuario_id, creado_en, actualizado_en) VALUES (:uid, NOW(), NOW())");
                $stmtPerfil->execute([':uid' => $nuevoId]);
            }

            $msg = "Usuario '{$nombreCompleto}' creado exitosamente en el sistema.";
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'usuario_id' => $nuevoId, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_success'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        } catch (PDOException $e) {
            error_log('Error creando usuario en administración: ' . $e->getMessage());
            $msg = 'Error al registrar el nuevo usuario en base de datos.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }
    }

    // =========================================================================
    // CRUD: MODIFICAR USUARIO
    // =========================================================================
    if ($action === 'modificar_usuario') {
        $targetUserId = filter_var($_POST['usuario_id'] ?? 0, FILTER_VALIDATE_INT);
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rolId = filter_var($_POST['rol_id'] ?? 0, FILTER_VALIDATE_INT);
        $estadoVerif = in_array($_POST['estado_verificacion'] ?? '', ['pendiente', 'aprobado', 'rechazado'], true) 
            ? $_POST['estado_verificacion'] 
            : 'aprobado';
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 1;
        $password = $_POST['password'] ?? '';

        if (!$targetUserId || empty($nombreCompleto) || empty($email) || !$rolId) {
            $msg = 'Parámetros obligatorios incompletos.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }

        if ($targetUserId === $adminId) {
            $activo = 1;
            $rolId  = 1;
            $estadoVerif = 'aprobado';
        }

        try {
            $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id LIMIT 1");
            $stmtCheck->execute([':email' => $email, ':id' => $targetUserId]);
            if ($stmtCheck->fetch()) {
                $msg = 'El correo electrónico ya está registrado por otro usuario.';
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(409);
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                $_SESSION['admin_flash_error'] = $msg;
                header('Location: index.php?page=administracion&tab=usuarios');
                exit;
            }

            if (!empty($password) && strlen($password) >= 6) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmtUpd = $pdo->prepare("
                    UPDATE usuarios 
                    SET nombre_completo = :nombre, email = :email, rol_id = :rol_id, 
                        estado_verificacion = :verif, activo = :activo, password_hash = :pass, actualizado_en = NOW()
                    WHERE id = :id
                ");
                $stmtUpd->execute([
                    ':nombre' => $nombreCompleto,
                    ':email'  => $email,
                    ':rol_id' => $rolId,
                    ':verif'  => $estadoVerif,
                    ':activo' => $activo,
                    ':pass'   => $passwordHash,
                    ':id'     => $targetUserId
                ]);
            } else {
                $stmtUpd = $pdo->prepare("
                    UPDATE usuarios 
                    SET nombre_completo = :nombre, email = :email, rol_id = :rol_id, 
                        estado_verificacion = :verif, activo = :activo, actualizado_en = NOW()
                    WHERE id = :id
                ");
                $stmtUpd->execute([
                    ':nombre' => $nombreCompleto,
                    ':email'  => $email,
                    ':rol_id' => $rolId,
                    ':verif'  => $estadoVerif,
                    ':activo' => $activo,
                    ':id'     => $targetUserId
                ]);
            }

            if ($rolId === 2) {
                $pdo->prepare("INSERT IGNORE INTO perfiles_egresados (usuario_id, creado_en, actualizado_en) VALUES (:uid, NOW(), NOW())")->execute([':uid' => $targetUserId]);
            }

            $msg = "Usuario '{$nombreCompleto}' actualizado correctamente.";
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'usuario_id' => $targetUserId, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_success'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        } catch (PDOException $e) {
            error_log('Error modificando usuario en administración: ' . $e->getMessage());
            $msg = 'Error al actualizar el usuario en la base de datos.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }
    }

    // =========================================================================
    // CRUD: ELIMINAR O DESACTIVAR USUARIO
    // =========================================================================
    if ($action === 'eliminar_usuario') {
        $targetUserId = filter_var($_POST['usuario_id'] ?? 0, FILTER_VALIDATE_INT);

        if (!$targetUserId) {
            $msg = 'ID de usuario no válido.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }

        if ($targetUserId === $adminId) {
            $msg = 'No puedes eliminar tu propia cuenta de administrador en sesión.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_error'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        }

        try {
            $stmtDel = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmtDel->execute([':id' => $targetUserId]);
            $msg = "Usuario eliminado definitivamente de la plataforma.";

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'usuario_id' => $targetUserId, 'eliminado' => true, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_success'] = $msg;
            header('Location: index.php?page=administracion&tab=usuarios');
            exit;
        } catch (PDOException $e) {
            try {
                $stmtDeact = $pdo->prepare("UPDATE usuarios SET activo = 0, actualizado_en = NOW() WHERE id = :id");
                $stmtDeact->execute([':id' => $targetUserId]);
                $msg = "El usuario tiene registros históricos asociados (publicaciones, PQRS o auditoría). La cuenta ha sido desactivada con éxito.";

                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => true, 'usuario_id' => $targetUserId, 'desactivado' => true, 'message' => $msg]);
                    exit;
                }
                $_SESSION['admin_flash_success'] = $msg;
                header('Location: index.php?page=administracion&tab=usuarios');
                exit;
            } catch (PDOException $e2) {
                error_log('Error desactivando usuario: ' . $e2->getMessage());
                $msg = 'No fue posible completar la eliminación o desactivación del usuario.';
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $msg]);
                    exit;
                }
                $_SESSION['admin_flash_error'] = $msg;
                header('Location: index.php?page=administracion&tab=usuarios');
                exit;
            }
        }
    }

    // =========================================================================
    // MODERACIÓN DE CONTENIDOS DEL BLOG (NOTICIAS, PROYECTOS, OPORTUNIDADES)
    // =========================================================================
    if (in_array($action, ['aprobar_contenido', 'rechazar_contenido'], true)) {
        $contentId = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        $tipo = strtolower(trim($_POST['tipo'] ?? ''));

        if (!$contentId || !in_array($tipo, ['proyecto', 'noticia', 'oportunidad'], true)) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Parámetros de moderación incorrectos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Parámetros de moderación incorrectos.';
            header('Location: index.php?page=administracion&tab=contenidos');
            exit;
        }

        $tablas = [
            'proyecto'    => 'proyectos',
            'noticia'     => 'noticias',
            'oportunidad' => 'oportunidades'
        ];
        $tabla = $tablas[$tipo];
        $nuevoEstado = ($action === 'aprobar_contenido') ? 'publicado' : 'rechazado';

        try {
            if ($action === 'aprobar_contenido') {
                $stmt = $pdo->prepare("
                    UPDATE {$tabla} 
                    SET estado = :estado, revisado_por = :admin_id, fecha_publicacion = COALESCE(fecha_publicacion, NOW()), actualizado_en = NOW() 
                    WHERE id = :id
                ");
            } else {
                $stmt = $pdo->prepare("
                    UPDATE {$tabla} 
                    SET estado = :estado, revisado_por = :admin_id, actualizado_en = NOW() 
                    WHERE id = :id
                ");
            }
            $stmt->execute([
                ':estado'   => $nuevoEstado,
                ':admin_id' => $adminId,
                ':id'       => $contentId
            ]);

            $labelTipo = ucfirst($tipo);
            $mensajeRespuesta = ($action === 'aprobar_contenido')
                ? "{$labelTipo} aprobado y publicado exitosamente en el portal institucional."
                : "{$labelTipo} rechazado.";

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success'      => true,
                    'nuevo_estado' => ucfirst($nuevoEstado),
                    'tipo'         => $tipo,
                    'id'           => $contentId,
                    'message'      => $mensajeRespuesta
                ]);
                exit;
            }

            $_SESSION['admin_flash_success'] = $mensajeRespuesta;
            header('Location: index.php?page=administracion&tab=contenidos');
            exit;
        } catch (PDOException $e) {
            error_log("Error moderando {$tipo} en administración: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el contenido en la base de datos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Error al actualizar el contenido en la base de datos.';
            header('Location: index.php?page=administracion&tab=contenidos');
            exit;
        }
    }

    // =========================================================================
    // ATENCIÓN Y RESPUESTA DE PQRS
    // =========================================================================
    if (in_array($action, ['responder_pqrs', 'cerrar_pqrs'], true)) {
        $radicado = trim($_POST['radicado'] ?? '');
        $respuesta = trim($_POST['respuesta'] ?? '');

        try {
            if ($action === 'responder_pqrs') {
                if (empty($respuesta)) {
                    $respuesta = 'Solicitud atendida y resuelta por la administración institucional de Raíces Alzate.';
                }
                $stmt = $pdo->prepare("
                    UPDATE pqrs 
                    SET estado = 'respondido', respuesta = :respuesta, respondido_por = :admin_id, respondido_en = NOW(), actualizado_en = NOW() 
                    WHERE radicado = :radicado
                ");
                $stmt->execute([
                    ':respuesta' => $respuesta,
                    ':admin_id'  => $adminId,
                    ':radicado'  => $radicado
                ]);

                $stmtInfo = $pdo->prepare("SELECT email, nombre, asunto, tipo FROM pqrs WHERE radicado = :radicado LIMIT 1");
                $stmtInfo->execute([':radicado' => $radicado]);
                $pqrInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

                $emailEnviado = false;
                if ($pqrInfo && !empty($pqrInfo['email']) && function_exists('enviarNotificacionRespuestaPQRS')) {
                    try {
                        $emailEnviado = enviarNotificacionRespuestaPQRS(
                            (string)$pqrInfo['email'],
                            (string)$pqrInfo['nombre'],
                            $radicado,
                            (string)$pqrInfo['asunto'],
                            $respuesta,
                            (string)($pqrInfo['tipo'] ?? 'PQRS'),
                            (string)($adminUser['nombre'] ?? 'Administración Institucional')
                        );
                    } catch (\Throwable $mEx) {
                        error_log('Error al enviar correo PQRS: ' . $mEx->getMessage());
                    }
                }

                // Sincronización con módulo de Mensajes directos si el remitente tiene cuenta registrada
                $mensajeInternoEnviado = false;
                if ($pqrInfo && !empty($pqrInfo['email'])) {
                    try {
                        $stmtCheckUser = $pdo->prepare("SELECT id, nombre_completo FROM usuarios WHERE email = :email LIMIT 1");
                        $stmtCheckUser->execute([':email' => $pqrInfo['email']]);
                        $usuarioDestino = $stmtCheckUser->fetch(PDO::FETCH_ASSOC);

                        if ($usuarioDestino && (int)$usuarioDestino['id'] !== $adminId) {
                            $destId = (int)$usuarioDestino['id'];
                            
                            // Asegurar conexión aceptada entre Administrador y el Usuario
                            $stmtConn = $pdo->prepare("
                                INSERT INTO conexiones (solicitante_id, receptor_id, estado, creado_en, actualizado_en)
                                VALUES (:adm, :dst, 'aceptada', NOW(), NOW())
                                ON DUPLICATE KEY UPDATE estado = 'aceptada', actualizado_en = NOW()
                            ");
                            $stmtConn->execute([':adm' => $adminId, ':dst' => $destId]);

                            // Insertar mensaje en el hilo de mensajería interna
                            $textoMensaje = "📋 [Respuesta Oficial a Radicado {$radicado}]\n" .
                                           "Asunto: {$pqrInfo['asunto']}\n\n" .
                                           "{$respuesta}\n\n" .
                                           "— Administración Institucional Raíces Alzate";

                            $stmtMsg = $pdo->prepare("
                                INSERT INTO mensajes (remitente_id, destinatario_id, mensaje, leido, creado_en)
                                VALUES (:adm, :dst, :msg, 0, NOW())
                            ");
                            $stmtMsg->execute([
                                ':adm' => $adminId,
                                ':dst' => $destId,
                                ':msg' => $textoMensaje
                            ]);
                            $mensajeInternoEnviado = true;
                        }
                    } catch (\Throwable $msgEx) {
                        error_log('Error enviando mensaje interno por PQRS: ' . $msgEx->getMessage());
                    }
                }

                $canales = [];
                if ($emailEnviado) $canales[] = "correo electrónico a {$pqrInfo['email']}";
                if ($mensajeInternoEnviado) $canales[] = "mensajería interna del portal";
                
                $msgCanales = !empty($canales) ? ' (' . implode(' y ', $canales) . ')' : '';
                $msg = "Solicitud PQRS {$radicado} respondida con éxito.{$msgCanales}";
            } else {
                $stmt = $pdo->prepare("UPDATE pqrs SET estado = 'cerrado', actualizado_en = NOW() WHERE radicado = :radicado");
                $stmt->execute([':radicado' => $radicado]);
                $msg = "Solicitud PQRS {$radicado} cerrada.";
            }

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'message' => $msg]);
                exit;
            }
            $_SESSION['admin_flash_success'] = $msg;
            header('Location: index.php?page=administracion&tab=pqrs');
            exit;
        } catch (PDOException $e) {
            error_log('Error gestionando PQRS: ' . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar PQRS en base de datos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Error en base de datos al actualizar la solicitud.';
            header('Location: index.php?page=administracion&tab=pqrs');
            exit;
        }
    }

    // =========================================================================
    // APROBACIÓN / RECHAZO DE SOLICITUDES DE REGISTRO
    // =========================================================================
    if (in_array($action, ['aprobar_registro', 'rechazar_registro'], true)) {
        $targetUserId = filter_var($_POST['usuario_id'] ?? 0, FILTER_VALIDATE_INT);

        if (!$targetUserId) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Parámetros no válidos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Parámetros de solicitud incorrectos.';
            header('Location: index.php?page=administracion&tab=registros');
            exit;
        }

        try {
            $userQuery = $pdo->prepare("SELECT id, nombre_completo, email, estado_verificacion FROM usuarios WHERE id = :id LIMIT 1");
            $userQuery->execute([':id' => $targetUserId]);
            $targetUser = $userQuery->fetch(PDO::FETCH_ASSOC);

            if (!$targetUser) {
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'El usuario no existe en la base de datos.']);
                    exit;
                }
                $_SESSION['admin_flash_error'] = 'El usuario no existe.';
                header('Location: index.php?page=administracion&tab=registros');
                exit;
            }

            $nuevoEstado = ($action === 'aprobar_registro') ? 'aprobado' : 'rechazado';

            $updateStmt = $pdo->prepare("
                UPDATE usuarios 
                SET estado_verificacion = :estado, actualizado_en = NOW() 
                WHERE id = :id
            ");
            $updateStmt->execute([
                ':estado' => $nuevoEstado,
                ':id'     => $targetUserId
            ]);

            $correoEnviado = false;
            if (function_exists('enviarNotificacionRegistro')) {
                $correoEnviado = enviarNotificacionRegistro(
                    $targetUser['email'],
                    $targetUser['nombre_completo'],
                    $action
                );
            }

            $mensajeRespuesta = ($nuevoEstado === 'aprobado')
                ? 'Registro aprobado exitosamente. Se ha notificado al egresado por correo electrónico.'
                : 'Solicitud rechazada. Se ha enviado la notificación por correo electrónico.';

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success'        => true,
                    'nuevo_estado'   => ucfirst($nuevoEstado),
                    'usuario_id'     => $targetUserId,
                    'correo_enviado' => $correoEnviado,
                    'message'        => $mensajeRespuesta
                ]);
                exit;
            }

            $_SESSION['admin_flash_success'] = $mensajeRespuesta;
            header('Location: index.php?page=administracion&tab=registros');
            exit;
        } catch (PDOException $e) {
            error_log('Error al actualizar estado en administración: ' . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario en la base de datos.']);
                exit;
            }
            $_SESSION['admin_flash_error'] = 'Error en base de datos al actualizar el registro.';
            header('Location: index.php?page=administracion&tab=registros');
            exit;
        }
    }
}

// 4. Carga de Datos desde MySQL

// A. Consulta de todos los usuarios para CRUD y estadísticas
$coloresAvatar = ['#1565C0', '#D32F2F', '#2E7D32', '#6A1B9A', '#EF6C00', '#00838F'];
$usuariosCrud = [];
$solicitudesRegistro = [];

try {
    $stmtUsers = $pdo->query("
        SELECT 
            u.id,
            u.rol_id,
            u.nombre_completo,
            u.email,
            u.estado_verificacion,
            u.activo,
            u.ultimo_acceso,
            u.creado_en,
            r.nombre AS rol_nombre,
            pe.documento,
            pe.telefono,
            pe.ciudad,
            pe.titulo_institucional,
            pe.anio_graduacion,
            pe.ocupacion,
            pe.empresa,
            pe.foto_perfil
        FROM usuarios u
        LEFT JOIN roles r ON u.rol_id = r.id
        LEFT JOIN perfiles_egresados pe ON pe.usuario_id = u.id
        ORDER BY u.id DESC
    ");
    $usuariosDb = $stmtUsers ? $stmtUsers->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (PDOException $e) {
    error_log('Error consultando usuarios en administración: ' . $e->getMessage());
    $usuariosDb = [];
}

foreach ($usuariosDb as $idx => $row) {
    $color = $coloresAvatar[$idx % count($coloresAvatar)];
    $fechaFmt = !empty($row['creado_en']) ? date('d M Y', strtotime($row['creado_en'])) : 'Reciente';
    $ultimoAccesoFmt = !empty($row['ultimo_acceso']) ? date('d M Y, H:i', strtotime($row['ultimo_acceso'])) : 'Sin registro';

    $userItem = [
        'id'                   => (int)$row['id'],
        'rol_id'               => (int)($row['rol_id'] ?? 2),
        'rol_nombre'           => $row['rol_nombre'] ?: 'egresado',
        'nombre'               => $row['nombre_completo'],
        'email'                => $row['email'],
        'estado_verificacion'  => strtolower($row['estado_verificacion'] ?? 'pendiente'),
        'activo'               => (int)($row['activo'] ?? 1),
        'fecha'                => $fechaFmt,
        'creado_en'            => $row['creado_en'],
        'ultimo_acceso'        => $ultimoAccesoFmt,
        'avatar'               => mb_strtoupper(mb_substr($row['nombre_completo'] ?? 'U', 0, 1, 'UTF-8'), 'UTF-8'),
        'color'                => $color,
        'foto_perfil'          => $row['foto_perfil'] ?? '',
        'documento'            => $row['documento'] ?? '',
        'telefono'             => $row['telefono'] ?? '',
        'ciudad'               => $row['ciudad'] ?? '',
        'titulo_institucional' => $row['titulo_institucional'] ?? '',
        'anio_graduacion'      => $row['anio_graduacion'] ?? '',
        'ocupacion'            => $row['ocupacion'] ?? '',
        'empresa'              => $row['empresa'] ?? ''
    ];

    $usuariosCrud[] = $userItem;

    if ((int)$row['rol_id'] !== 1) {
        $solicitudesRegistro[] = [
            'id'     => $userItem['id'],
            'nombre' => $userItem['nombre'],
            'email'  => $userItem['email'],
            'fecha'  => $userItem['fecha'],
            'estado' => ucfirst($userItem['estado_verificacion']),
            'avatar' => $userItem['avatar'],
            'color'  => $color
        ];
    }
}

// B. Consulta de Contenidos para Moderación
$contenidosModeracion = [];
try {
    $proyStmt = $pdo->query("
        SELECT p.id, p.titulo, p.categoria, p.descripcion, p.enlace, p.portada, p.estado, p.creado_en,
               COALESCE(u.nombre_completo, 'Egresado') AS autor_nombre
        FROM proyectos p
        LEFT JOIN usuarios u ON p.autor_id = u.id
        ORDER BY CASE WHEN p.estado = 'pendiente' THEN 1 ELSE 2 END ASC, p.creado_en DESC
    ");
    foreach (($proyStmt ? $proyStmt->fetchAll(PDO::FETCH_ASSOC) : []) as $p) {
        $desc = $p['descripcion'] ?? '';
        $contenidosModeracion[] = [
            'id'                 => (int)$p['id'],
            'tipo'               => 'Proyecto',
            'titulo'             => $p['titulo'],
            'autor'              => $p['autor_nombre'],
            'categoria'          => $p['categoria'] ?: 'General',
            'fecha'              => !empty($p['creado_en']) ? date('d M Y', strtotime($p['creado_en'])) : 'Reciente',
            'estado'             => ucfirst($p['estado'] ?: 'pendiente'),
            'resumen'            => mb_substr(strip_tags($desc), 0, 140, 'UTF-8') . (mb_strlen($desc) > 140 ? '...' : ''),
            'contenido_completo' => $desc,
            'badge_class'        => 'badge-orange',
            'portada'            => $p['portada'] ?? null,
            'enlace'             => $p['enlace'] ?? null
        ];
    }

    $notStmt = $pdo->query("
        SELECT n.id, n.titulo, n.categoria, n.descripcion, n.portada, n.estado, n.creado_en,
               COALESCE(u.nombre_completo, 'Administración') AS autor_nombre
        FROM noticias n
        LEFT JOIN usuarios u ON n.autor_id = u.id
        ORDER BY CASE WHEN n.estado = 'pendiente' THEN 1 ELSE 2 END ASC, n.creado_en DESC
    ");
    foreach (($notStmt ? $notStmt->fetchAll(PDO::FETCH_ASSOC) : []) as $n) {
        $desc = $n['descripcion'] ?? '';
        $contenidosModeracion[] = [
            'id'                 => (int)$n['id'],
            'tipo'               => 'Noticia',
            'titulo'             => $n['titulo'],
            'autor'              => $n['autor_nombre'],
            'categoria'          => $n['categoria'] ?: 'Institucional',
            'fecha'              => !empty($n['creado_en']) ? date('d M Y', strtotime($n['creado_en'])) : 'Reciente',
            'estado'             => ucfirst($n['estado'] ?: 'pendiente'),
            'resumen'            => mb_substr(strip_tags($desc), 0, 140, 'UTF-8') . (mb_strlen($desc) > 140 ? '...' : ''),
            'contenido_completo' => $desc,
            'badge_class'        => 'badge-blue',
            'portada'            => $n['portada'] ?? null
        ];
    }

    $opoStmt = $pdo->query("
        SELECT o.id, o.titulo, o.empresa, o.categoria, o.modalidad, o.ubicacion, o.descripcion, o.requisitos,
               o.beneficios, o.enlace_postulacion, o.estado, o.creado_en,
               COALESCE(u.nombre_completo, o.empresa, 'Institucional') AS autor_nombre
        FROM oportunidades o
        LEFT JOIN usuarios u ON o.autor_id = u.id
        ORDER BY CASE WHEN o.estado = 'pendiente' THEN 1 ELSE 2 END ASC, o.creado_en DESC
    ");
    foreach (($opoStmt ? $opoStmt->fetchAll(PDO::FETCH_ASSOC) : []) as $o) {
        $desc = $o['descripcion'] ?? '';
        $contenidosModeracion[] = [
            'id'                 => (int)$o['id'],
            'tipo'               => 'Oportunidad',
            'titulo'             => $o['titulo'],
            'autor'              => $o['empresa'] ?: $o['autor_nombre'],
            'categoria'          => $o['categoria'] ?: 'Laboral',
            'fecha'              => !empty($o['creado_en']) ? date('d M Y', strtotime($o['creado_en'])) : 'Reciente',
            'estado'             => ucfirst($o['estado'] ?: 'pendiente'),
            'resumen'            => mb_substr(strip_tags($desc), 0, 140, 'UTF-8') . (mb_strlen($desc) > 140 ? '...' : ''),
            'contenido_completo' => $desc,
            'badge_class'        => 'badge-purple'
        ];
    }
} catch (PDOException $e) {
    error_log('Error consultando contenidos para moderación: ' . $e->getMessage());
}

// C. Consulta de PQRS
$pqrsLista = [];
try {
    $stmtPqrs = $pdo->query("SELECT * FROM pqrs ORDER BY creado_en DESC");
    foreach (($stmtPqrs ? $stmtPqrs->fetchAll(PDO::FETCH_ASSOC) : []) as $row) {
        $fechaFmt = !empty($row['creado_en']) ? date('d M Y', strtotime($row['creado_en'])) : 'Reciente';
        $estadoLabel = ($row['estado'] === 'respondido') ? 'Resuelto' : (($row['estado'] === 'cerrado') ? 'Cerrado' : 'Pendiente');
        $pqrsLista[] = [
            'id'          => (int)$row['id'],
            'radicado'    => $row['radicado'],
            'tipo'        => $row['tipo'],
            'remitente'   => $row['nombre'],
            'correo'      => $row['email'],
            'asunto'      => $row['asunto'],
            'fecha'       => $fechaFmt,
            'estado'      => $estadoLabel,
            'prioridad'   => $row['prioridad'] ?: 'Media',
            'descripcion' => $row['mensaje'],
            'imagen'      => $row['imagen'] ?? '',
            'respuesta'   => $row['respuesta'] ?? ''
        ];
    }
} catch (PDOException $e) {
    error_log('Error consultando tabla pqrs: ' . $e->getMessage());
}

// D. Métricas Generales (KPIs)
$solicitudesPendientesCount = count(array_filter($solicitudesRegistro, fn($s) => strtolower($s['estado']) === 'pendiente'));
$egresadosTotalesCount = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol_id = 2 AND estado_verificacion = 'aprobado'")->fetchColumn();
$contenidosRevisionCount = count(array_filter($contenidosModeracion, fn($c) => strtolower($c['estado']) === 'pendiente'));
$pqrsActivasCount = count(array_filter($pqrsLista, fn($p) => strtolower($p['estado']) === 'pendiente'));
$totalUsuariosRegistrados = count($usuariosCrud);

$metricas = [
    'usuarios_totales'       => $totalUsuariosRegistrados,
    'solicitudes_pendientes' => $solicitudesPendientesCount,
    'contenidos_revision'    => $contenidosRevisionCount,
    'pqrs_activas'           => $pqrsActivasCount,
    'egresados_totales'      => $egresadosTotalesCount,
];

// E. Agregación de Datos para las Gráficas
$chartMeses = [];
for ($i = 5; $i >= 0; $i--) {
    $keyMes = date('Y-m', strtotime("-$i months"));
    $chartMeses[$keyMes] = [
        'label' => date('M Y', strtotime("-$i months")),
        'total' => 0
    ];
}
try {
    $stmtRegHist = $pdo->query("
        SELECT DATE_FORMAT(creado_en, '%Y-%m') as mes, COUNT(*) as cant 
        FROM usuarios 
        WHERE creado_en >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY mes
    ");
    while ($r = $stmtRegHist->fetch(PDO::FETCH_ASSOC)) {
        if (isset($chartMeses[$r['mes']])) {
            $chartMeses[$r['mes']]['total'] = (int)$r['cant'];
        }
    }
} catch (PDOException $e) {
    error_log('Error agrupando registros por mes: ' . $e->getMessage());
}

$regAprobados = count(array_filter($usuariosCrud, fn($u) => $u['estado_verificacion'] === 'aprobado'));
$regPendientes = count(array_filter($usuariosCrud, fn($u) => $u['estado_verificacion'] === 'pendiente'));
$regRechazados = count(array_filter($usuariosCrud, fn($u) => $u['estado_verificacion'] === 'rechazado'));

$pqrTipos = [
    'Petición'        => 0,
    'Queja'           => 0,
    'Reclamo'         => 0,
    'Sugerencia'      => 0,
    'Reporte técnico' => 0
];
$pqrResueltos = 0;
$pqrPendientes = 0;
$pqrCerrados = 0;

foreach ($pqrsLista as $itemPqr) {
    $t = $itemPqr['tipo'];
    if (isset($pqrTipos[$t])) {
        $pqrTipos[$t]++;
    } elseif (stripos($t, 'error') !== false || stripos($t, 'técnico') !== false) {
        $pqrTipos['Reporte técnico']++;
    } else {
        $pqrTipos['Petición']++;
    }

    if ($itemPqr['estado'] === 'Resuelto') {
        $pqrResueltos++;
    } elseif ($itemPqr['estado'] === 'Cerrado') {
        $pqrCerrados++;
    } else {
        $pqrPendientes++;
    }
}
$totalPqrsContadas = count($pqrsLista);
$tasaResolucionPQRS = $totalPqrsContadas > 0 ? round(($pqrResueltos / $totalPqrsContadas) * 100) : 100;

$blogTotales = [
    'noticias'      => ['publicado' => 0, 'pendiente' => 0],
    'proyectos'     => ['publicado' => 0, 'pendiente' => 0],
    'oportunidades' => ['publicado' => 0, 'pendiente' => 0]
];
foreach ($contenidosModeracion as $c) {
    $tipoKey = strtolower($c['tipo']) . 's';
    if ($tipoKey === 'noticias') $tipoKey = 'noticias';
    if ($tipoKey === 'proyectos') $tipoKey = 'proyectos';
    if ($tipoKey === 'oportunidads' || $tipoKey === 'oportunidades') $tipoKey = 'oportunidades';

    $st = (strtolower($c['estado']) === 'publicado') ? 'publicado' : 'pendiente';
    if (isset($blogTotales[$tipoKey])) {
        $blogTotales[$tipoKey][$st]++;
    }
}

// Carga del layout compartido
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout-wrapper dashboard-layout" id="dashboardLayout">
    <!-- Barra Lateral Privada -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Área Principal de Contenido -->
    <div class="main-wrapper dashboard-main-area">
        <!-- Barra Superior (Topbar) -->
        <?php require_once __DIR__ . '/../includes/topbar.php'; ?>

        <!-- Contenido Central de la Pantalla Administrativa Activa -->
        <main class="content-area dashboard-content admin-content-area" id="mainContent" tabindex="-1">
            
            <!-- 1. Cabecera Específica con Título y Subtítulo de esta Pantalla -->
            <div class="admin-header-banner">
                <div class="admin-header-text">
                    <span class="admin-badge-pill"><?= htmlspecialchars($pantallaActual['pill']) ?></span>
                    <h1 class="admin-page-title"><?= htmlspecialchars($pantallaActual['titulo']) ?></h1>
                    <p class="admin-page-subtitle"><?= htmlspecialchars($pantallaActual['subtitulo']) ?></p>
                </div>
                <div class="admin-header-actions">
                    <?php if ($tabActiva === 'usuarios'): ?>
                        <button type="button" class="admin-btn admin-btn-primary" onclick="abrirModalCrearUsuario()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Nuevo Usuario</span>
                        </button>
                    <?php elseif ($tabActiva === 'contenidos'): ?>
                        <div class="admin-tab-buttons-group">
                            <a href="index.php?page=crear-noticia" class="admin-action-pill pill-blue">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Nueva Noticia</span>
                            </a>
                            <a href="index.php?page=crear-oportunidad" class="admin-action-pill pill-purple">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Nueva Convocatoria</span>
                            </a>
                            <a href="index.php?page=crear-proyecto" class="admin-action-pill pill-orange">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Nuevo Proyecto</span>
                            </a>
                        </div>
                    <?php elseif ($tabActiva === 'registros'): ?>
                        <span class="admin-badge-pill" style="margin: 0; background-color: #FFF3E0; color: #E65100;">
                            <?= (int)$metricas['solicitudes_pendientes'] ?> Solicitudes Pendientes
                        </span>
                    <?php elseif ($tabActiva === 'pqrs'): ?>
                        <span class="admin-badge-pill" style="margin: 0; background-color: #FFEBEE; color: #D32F2F;">
                            <?= (int)$metricas['pqrs_activas'] ?> Casos por Resolver
                        </span>
                    <?php elseif ($tabActiva === 'dashboard'): ?>
                        <span class="admin-badge-pill" style="margin: 0; background-color: #E8F5E9; color: #2E7D32;">
                            ● Plataforma Operativa
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- PANTALLA 1: DASHBOARD Y GRÁFICAS ESTADÍSTICAS                 -->
            <!-- ============================================================== -->
            <?php if ($tabActiva === 'dashboard'): ?>
                <!-- Métricas KPI Clave del Dashboard -->
                <section class="admin-kpi-grid" aria-label="Métricas del sistema">
                    <a href="index.php?page=administracion&tab=usuarios" class="admin-kpi-card kpi-blue" style="text-decoration: none;">
                        <div class="kpi-icon-wrap" aria-hidden="true">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            </svg>
                        </div>
                        <div class="kpi-info">
                            <span class="kpi-label">Usuarios Registrados</span>
                            <h2 class="kpi-value" id="kpiUsuarios"><?= number_format((int)$metricas['usuarios_totales']) ?></h2>
                            <span class="kpi-subtext">Ver directorio completo &rarr;</span>
                        </div>
                    </a>

                    <a href="index.php?page=administracion&tab=registros" class="admin-kpi-card kpi-orange" style="text-decoration: none;">
                        <div class="kpi-icon-wrap" aria-hidden="true">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                        <div class="kpi-info">
                            <span class="kpi-label">Registros Pendientes</span>
                            <h2 class="kpi-value" id="kpiRegistros"><?= (int)$metricas['solicitudes_pendientes'] ?></h2>
                            <span class="kpi-subtext kpi-subtext-alert">Requieren validación &rarr;</span>
                        </div>
                    </a>

                    <a href="index.php?page=administracion&tab=contenidos" class="admin-kpi-card kpi-purple" style="text-decoration: none;">
                        <div class="kpi-icon-wrap" aria-hidden="true">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div class="kpi-info">
                            <span class="kpi-label">Blog por Moderar</span>
                            <h2 class="kpi-value" id="kpiContenidos"><?= (int)$metricas['contenidos_revision'] ?></h2>
                            <span class="kpi-subtext">Revisar publicaciones &rarr;</span>
                        </div>
                    </a>

                    <a href="index.php?page=administracion&tab=pqrs" class="admin-kpi-card kpi-red" style="text-decoration: none;">
                        <div class="kpi-icon-wrap" aria-hidden="true">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                        <div class="kpi-info">
                            <span class="kpi-label">PQRS Activas</span>
                            <h2 class="kpi-value" id="kpiPQRS"><?= (int)$metricas['pqrs_activas'] ?></h2>
                            <span class="kpi-subtext kpi-subtext-alert">Atender casos &rarr;</span>
                        </div>
                    </a>
                </section>

                <!-- Las 3 Gráficas Estadísticas -->
                <div class="admin-charts-grid">
                    <!-- GRÁFICA 1: REGISTRO DE USUARIOS -->
                    <article class="admin-chart-card chart-span-7">
                        <div class="admin-chart-header">
                            <div class="admin-chart-title-wrap">
                                <div class="admin-chart-icon-wrap icon-blue">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                </div>
                                <div>
                                    <h3 class="admin-chart-title">Evolución de Registros de Usuarios</h3>
                                    <p class="admin-chart-sub">Inscripciones mensuales y estado de aprobación de cuentas</p>
                                </div>
                            </div>
                            <span class="admin-chart-badge" style="background-color: #E3F2FD; color: #1565C0;">Últimos 6 Meses</span>
                        </div>

                        <div class="admin-chart-canvas-wrap">
                            <canvas id="chartRegistrosCanvas"></canvas>
                        </div>

                        <div class="admin-chart-footer">
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #2E7D32;"><?= $regAprobados ?></span>
                                <span class="chart-footer-label">Aprobados</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #E65100;"><?= $regPendientes ?></span>
                                <span class="chart-footer-label">Pendientes</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #D32F2F;"><?= $regRechazados ?></span>
                                <span class="chart-footer-label">Rechazados</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #1565C0;"><?= $totalUsuariosRegistrados ?></span>
                                <span class="chart-footer-label">Total Cuentas</span>
                            </div>
                        </div>
                    </article>

                    <!-- GRÁFICA 2: ATENCIÓN DE PQRS -->
                    <article class="admin-chart-card chart-span-5">
                        <div class="admin-chart-header">
                            <div class="admin-chart-title-wrap">
                                <div class="admin-chart-icon-wrap icon-red">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                </div>
                                <div>
                                    <h3 class="admin-chart-title">Gestión y Tipología de PQRS</h3>
                                    <p class="admin-chart-sub">Casos recibidos por categoría institucional</p>
                                </div>
                            </div>
                            <span class="admin-chart-badge" style="background-color: #FFEBEE; color: #D32F2F;">Atención</span>
                        </div>

                        <div class="admin-chart-canvas-wrap">
                            <canvas id="chartPQRSCanvas"></canvas>
                        </div>

                        <div class="admin-chart-footer">
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #2E7D32;"><?= $tasaResolucionPQRS ?>%</span>
                                <span class="chart-footer-label">Tasa Resolución</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #1565C0;"><?= $pqrResueltos ?></span>
                                <span class="chart-footer-label">Resueltas</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #D32F2F;"><?= $pqrPendientes ?></span>
                                <span class="chart-footer-label">Por Atender</span>
                            </div>
                        </div>
                    </article>

                    <!-- GRÁFICA 3: ACTIVIDAD DEL BLOG -->
                    <article class="admin-chart-card chart-span-12">
                        <div class="admin-chart-header">
                            <div class="admin-chart-title-wrap">
                                <div class="admin-chart-icon-wrap icon-purple">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="admin-chart-title">Publicaciones y Contenidos del Blog</h3>
                                    <p class="admin-chart-sub">Comparativa de contenidos publicados versus pendientes en moderación</p>
                                </div>
                            </div>
                            <span class="admin-chart-badge" style="background-color: #EDE7F6; color: #6A1B9A;">Comunidad Alzate</span>
                        </div>

                        <div class="admin-chart-canvas-wrap">
                            <canvas id="chartBlogCanvas"></canvas>
                        </div>

                        <div class="admin-chart-footer">
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #1565C0;"><?= ($blogTotales['noticias']['publicado'] + $blogTotales['noticias']['pendiente']) ?></span>
                                <span class="chart-footer-label">Noticias Institucionales</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #E65100;"><?= ($blogTotales['proyectos']['publicado'] + $blogTotales['proyectos']['pendiente']) ?></span>
                                <span class="chart-footer-label">Proyectos Egresados</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #6A1B9A;"><?= ($blogTotales['oportunidades']['publicado'] + $blogTotales['oportunidades']['pendiente']) ?></span>
                                <span class="chart-footer-label">Oportunidades Laborales</span>
                            </div>
                            <div class="chart-footer-stat">
                                <span class="chart-footer-num" style="color: #2E7D32;">
                                    <?= ($blogTotales['noticias']['publicado'] + $blogTotales['proyectos']['publicado'] + $blogTotales['oportunidades']['publicado']) ?>
                                </span>
                                <span class="chart-footer-label">Total Publicados</span>
                            </div>
                        </div>
                    </article>
                </div>

            <!-- ============================================================== -->
            <!-- PANTALLA 2: CONTROL DE USUARIOS (CRUD COMPLETO)               -->
            <!-- ============================================================== -->
            <?php elseif ($tabActiva === 'usuarios'): ?>
                <div class="admin-table-toolbar">
                    <div class="admin-search-wrap">
                        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="adminSearchInput" class="admin-search-input" placeholder="Buscar por nombre, correo o ID..." oninput="filtrarTablaActual()">
                        <button type="button" id="adminClearSearch" class="search-clear-btn" style="display: none;" onclick="limpiarBusqueda()">✕</button>
                    </div>

                    <div class="admin-filter-group">
                        <label for="adminRoleFilter" class="filter-label">Rol:</label>
                        <select id="adminRoleFilter" class="admin-select" onchange="filtrarTablaActual()">
                            <option value="todos" selected>Todos los roles</option>
                            <option value="administrador">Administrador</option>
                            <option value="egresado">Egresado</option>
                            <option value="estudiante">Estudiante</option>
                        </select>

                        <label for="adminStatusFilter" class="filter-label">Estado:</label>
                        <select id="adminStatusFilter" class="admin-select" onchange="filtrarTablaActual()">
                            <option value="todos" selected>Todos los estados</option>
                            <option value="aprobado">Aprobados</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="rechazado">Rechazados</option>
                            <option value="activo">Solo Activos</option>
                            <option value="inactivo">Solo Inactivos</option>
                        </select>

                        <span class="admin-results-counter" id="adminResultsCounter">Cargando...</span>
                    </div>
                </div>

                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table admin-table-users" id="tablaUsuarios">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;">Usuario</th>
                                    <th style="min-width: 220px;">Correo Institucional</th>
                                    <th style="min-width: 120px;">Rol</th>
                                    <th style="min-width: 120px;">Verificación</th>
                                    <th style="min-width: 110px;">Estado Cuenta</th>
                                    <th style="min-width: 120px;">Fecha Registro</th>
                                    <th class="text-center" style="min-width: 280px; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuariosCrud as $u): ?>
                                <tr class="admin-table-row" id="row-usuario-<?= $u['id'] ?>"
                                    data-id="<?= $u['id'] ?>"
                                    data-user='<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>'
                                    data-nombre="<?= htmlspecialchars($u['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-rol="<?= htmlspecialchars(strtolower($u['rol_nombre']), ENT_QUOTES, 'UTF-8') ?>"
                                    data-estado="<?= htmlspecialchars($u['estado_verificacion'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-activo="<?= $u['activo'] ?>"
                                    data-fecha="<?= htmlspecialchars($u['fecha'], ENT_QUOTES, 'UTF-8') ?>">
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-avatar" style="background-color: <?= $u['color'] ?>;">
                                                <?= htmlspecialchars($u['avatar'], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                            <div class="user-cell-info">
                                                <span class="user-cell-name font-semibold-cell"><?= htmlspecialchars($u['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                                <span style="font-size: 0.72rem; color: #757575;">ID #<?= $u['id'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-cell-email" style="font-size: 0.85rem; color: #424242; font-weight: 500;"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td>
                                        <?php if ($u['rol_id'] === 1): ?>
                                            <span class="role-badge role-admin">Administrador</span>
                                        <?php elseif ($u['rol_id'] === 2): ?>
                                            <span class="role-badge role-egresado">Egresado</span>
                                        <?php else: ?>
                                            <span class="role-badge role-estudiante">Estudiante</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-pill status-<?= $u['estado_verificacion'] ?>">
                                            <?= ucfirst($u['estado_verificacion']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="account-status-badge <?= ($u['activo'] === 1) ? 'status-active' : 'status-inactive' ?>">
                                            <?= ($u['activo'] === 1) ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($u['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center" style="text-align: center;">
                                        <div class="actions-button-group user-actions-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Inspeccionar ficha detallada" onclick="abrirModalDetalleUsuario(<?= $u['id'] ?>)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                <span>Revisar</span>
                                            </button>
                                            <button type="button" class="btn-action-sm btn-action-edit" title="Modificar credenciales y rol" onclick="abrirModalEditarUsuario(<?= $u['id'] ?>)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                <span>Modificar</span>
                                            </button>
                                            <?php if ($u['id'] !== $adminId): ?>
                                            <button type="button" class="btn-action-sm btn-action-delete" title="Eliminar o suspender cuenta" onclick="abrirModalEliminarUsuario(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['nombre'])) ?>')">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <span>Eliminar</span>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="btn-action-sm btn-action-disabled" title="Cuenta de administrador institucional protegida contra eliminación" disabled>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <span>Eliminar</span>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyUsuarios" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <p>No se encontraron usuarios con el criterio de búsqueda o filtro seleccionado.</p>
                    </div>
                </div>

            <!-- ============================================================== -->
            <!-- PANTALLA 3: APROBACIÓN DE REGISTROS                           -->
            <!-- ============================================================== -->
            <?php elseif ($tabActiva === 'registros'): ?>
                <div class="admin-table-toolbar">
                    <div class="admin-search-wrap">
                        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="adminSearchInput" class="admin-search-input" placeholder="Buscar solicitud por nombre o correo..." oninput="filtrarTablaActual()">
                        <button type="button" id="adminClearSearch" class="search-clear-btn" style="display: none;" onclick="limpiarBusqueda()">✕</button>
                    </div>

                    <div class="admin-filter-group">
                        <label for="adminStatusFilter" class="filter-label">Estado:</label>
                        <select id="adminStatusFilter" class="admin-select" onchange="filtrarTablaActual()">
                            <option value="todos" selected>Todos los estados</option>
                            <option value="pendiente">Solo Pendientes</option>
                            <option value="aprobado_publicado">Aprobados</option>
                            <option value="rechazado">Rechazados</option>
                        </select>
                        <span class="admin-results-counter" id="adminResultsCounter">Cargando...</span>
                    </div>
                </div>

                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaRegistros">
                            <thead>
                                <tr>
                                    <th>Nombre del Solicitante</th>
                                    <th>Correo Electrónico</th>
                                    <th>Fecha de Solicitud</th>
                                    <th class="text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudesRegistro as $sol): ?>
                                <tr class="admin-table-row" id="row-registro-<?= $sol['id'] ?>" 
                                    data-id="<?= $sol['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($sol['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-email="<?= htmlspecialchars($sol['email'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-fecha="<?= htmlspecialchars($sol['fecha'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-estado="<?= htmlspecialchars($sol['estado'], ENT_QUOTES, 'UTF-8') ?>">
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-avatar" style="background-color: <?= $sol['color'] ?>;">
                                                <?= htmlspecialchars($sol['avatar'], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                            <div class="user-cell-info">
                                                <span class="user-cell-name"><?= htmlspecialchars($sol['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-cell-email" style="font-size: 0.85rem; color: #424242; font-weight: 500;"><?= htmlspecialchars($sol['email'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($sol['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud" onclick="abrirModalRegistro(<?= $sol['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                <span>Revisar</span>
                                            </button>
                                            <?php if (strtolower($sol['estado']) === 'aprobado'): ?>
                                                <span class="action-confirmed-tag">✓ Aprobado</span>
                                            <?php elseif (strtolower($sol['estado']) === 'rechazado'): ?>
                                                <span class="action-rejected-tag">✕ Rechazado</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyRegistros" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <p>No se encontraron solicitudes con el criterio de búsqueda seleccionado.</p>
                    </div>
                </div>

            <!-- ============================================================== -->
            <!-- PANTALLA 4: MODERACIÓN DE CONTENIDOS (BLOG)                    -->
            <!-- ============================================================== -->
            <?php elseif ($tabActiva === 'contenidos'): ?>
                <div class="admin-table-toolbar">
                    <div class="admin-search-wrap">
                        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="adminSearchInput" class="admin-search-input" placeholder="Buscar por título, autor o categoría..." oninput="filtrarTablaActual()">
                        <button type="button" id="adminClearSearch" class="search-clear-btn" style="display: none;" onclick="limpiarBusqueda()">✕</button>
                    </div>

                    <div class="admin-filter-group">
                        <label for="adminStatusFilter" class="filter-label">Estado:</label>
                        <select id="adminStatusFilter" class="admin-select" onchange="filtrarTablaActual()">
                            <option value="todos" selected>Todos los estados</option>
                            <option value="pendiente">Solo Pendientes</option>
                            <option value="aprobado_publicado">Publicados</option>
                            <option value="rechazado">Rechazados</option>
                        </select>
                        <span class="admin-results-counter" id="adminResultsCounter">Cargando...</span>
                    </div>
                </div>

                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaContenidos">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Título del Contenido</th>
                                    <th>Autor / Remitente</th>
                                    <th>Categoría</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contenidosModeracion as $item): ?>
                                <tr class="admin-table-row" id="row-contenido-<?= strtolower($item['tipo']) ?>-<?= $item['id'] ?>"
                                    data-id="<?= $item['id'] ?>"
                                    data-tipo="<?= strtolower($item['tipo']) ?>"
                                    data-tipo-label="<?= htmlspecialchars($item['tipo']) ?>"
                                    data-titulo="<?= htmlspecialchars($item['titulo']) ?>"
                                    data-autor="<?= htmlspecialchars($item['autor']) ?>"
                                    data-categoria="<?= htmlspecialchars($item['categoria']) ?>"
                                    data-resumen="<?= htmlspecialchars($item['resumen']) ?>"
                                    data-contenido="<?= htmlspecialchars($item['contenido_completo']) ?>"
                                    data-estado="<?= $item['estado'] ?>"
                                    data-fecha="<?= $item['fecha'] ?>">
                                    <td>
                                        <span class="type-pill type-<?= strtolower($item['tipo']) ?>">
                                            <?= htmlspecialchars($item['tipo']) ?>
                                        </span>
                                    </td>
                                    <td class="font-semibold-cell">
                                        <div class="content-title-cell">
                                            <span><?= htmlspecialchars($item['titulo']) ?></span>
                                            <p class="content-desc-sub"><?= htmlspecialchars($item['resumen']) ?></p>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($item['autor']) ?></td>
                                    <td><span class="category-badge"><?= htmlspecialchars($item['categoria']) ?></span></td>
                                    <td><?= htmlspecialchars($item['fecha']) ?></td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($item['estado']) ?>">
                                            <?= $item['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Examinar publicación" onclick="abrirModalContenido('<?= strtolower($item['tipo']) ?>', <?= $item['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                <span>Moderar</span>
                                            </button>
                                            <?php if (strtolower($item['estado']) === 'pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Aprobar y publicar" onclick="publicarContenidoDirecto('<?= strtolower($item['tipo']) ?>', <?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </button>
                                            <button type="button" class="btn-action-sm btn-action-reject" title="Rechazar contenido" onclick="rechazarContenidoDirecto('<?= strtolower($item['tipo']) ?>', <?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['titulo'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyContenidos" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <p>No se encontraron publicaciones con los filtros aplicados.</p>
                    </div>
                </div>

            <!-- ============================================================== -->
            <!-- PANTALLA 5: RESOLUCIÓN DE PQRS                                -->
            <!-- ============================================================== -->
            <?php elseif ($tabActiva === 'pqrs'): ?>
                <div class="admin-table-toolbar">
                    <div class="admin-search-wrap">
                        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="adminSearchInput" class="admin-search-input" placeholder="Buscar caso por radicado, remitente o asunto..." oninput="filtrarTablaActual()">
                        <button type="button" id="adminClearSearch" class="search-clear-btn" style="display: none;" onclick="limpiarBusqueda()">✕</button>
                    </div>

                    <div class="admin-filter-group">
                        <label for="adminStatusFilter" class="filter-label">Estado:</label>
                        <select id="adminStatusFilter" class="admin-select" onchange="filtrarTablaActual()">
                            <option value="todos" selected>Todos los estados</option>
                            <option value="pendiente">Solo Pendientes</option>
                            <option value="aprobado_publicado">Resueltos</option>
                            <option value="rechazado">Cerrados</option>
                        </select>
                        <span class="admin-results-counter" id="adminResultsCounter">Cargando...</span>
                    </div>
                </div>

                <div class="admin-card-table-wrapper">
                    <div class="table-responsive">
                        <table class="admin-table" id="tablaPQRS">
                            <thead>
                                <tr>
                                    <th>Radicado</th>
                                    <th>Tipo de Caso</th>
                                    <th>Remitente</th>
                                    <th>Asunto</th>
                                    <th>Fecha</th>
                                    <th>Prioridad</th>
                                    <th>Estado</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pqrsLista as $pq): ?>
                                <tr class="admin-table-row" id="row-pqrs-<?= $pq['radicado'] ?>"
                                    data-id="<?= $pq['radicado'] ?>"
                                    data-radicado="<?= htmlspecialchars($pq['radicado']) ?>"
                                    data-tipo="<?= htmlspecialchars($pq['tipo']) ?>"
                                    data-remitente="<?= htmlspecialchars($pq['remitente']) ?>"
                                    data-correo="<?= htmlspecialchars($pq['correo']) ?>"
                                    data-asunto="<?= htmlspecialchars($pq['asunto']) ?>"
                                    data-prioridad="<?= htmlspecialchars($pq['prioridad']) ?>"
                                    data-descripcion="<?= htmlspecialchars($pq['descripcion']) ?>"
                                    data-respuesta="<?= htmlspecialchars($pq['respuesta']) ?>"
                                    data-estado="<?= $pq['estado'] ?>"
                                    data-fecha="<?= $pq['fecha'] ?>"
                                    data-imagen="<?= htmlspecialchars($pq['imagen'] ?? '') ?>">
                                    <td><code class="radicado-code"><?= htmlspecialchars($pq['radicado']) ?></code></td>
                                    <td>
                                        <span class="type-pill type-<?= strtolower(str_replace(' ', '-', $pq['tipo'])) ?>">
                                            <?= htmlspecialchars($pq['tipo']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-meta-cell">
                                            <div class="user-cell-info">
                                                <span class="user-cell-name"><?= htmlspecialchars($pq['remitente']) ?></span>
                                                <span class="user-cell-email"><?= htmlspecialchars($pq['correo']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pqrs-table-asunto-col">
                                        <div class="pqrs-asunto-stack">
                                            <div class="pqrs-asunto-line">
                                                <span class="pqrs-asunto-heading" title="<?= htmlspecialchars($pq['asunto']) ?>"><?= htmlspecialchars($pq['asunto']) ?></span>
                                                <?php if (!empty($pq['imagen'])): ?>
                                                <span class="pqrs-evidence-badge" title="Contiene archivo adjunto">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                                    <span>Adjunto</span>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="pqrs-asunto-snippet" title="<?= htmlspecialchars($pq['descripcion']) ?>">
                                                <?= htmlspecialchars(mb_substr($pq['descripcion'], 0, 75)) ?><?= (mb_strlen($pq['descripcion']) > 75) ? '...' : '' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($pq['fecha']) ?></td>
                                    <td>
                                        <span class="priority-badge priority-<?= strtolower($pq['prioridad']) ?>">
                                            <?= htmlspecialchars($pq['prioridad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-pill status-<?= strtolower($pq['estado']) ?>">
                                            <?= $pq['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="actions-button-group">
                                            <button type="button" class="btn-action-sm btn-action-view" title="Atender y responder caso" onclick="abrirModalPQRS('<?= $pq['radicado'] ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                <span>Responder</span>
                                            </button>
                                            <?php if ($pq['estado'] === 'Pendiente'): ?>
                                            <button type="button" class="btn-action-sm btn-action-approve" title="Marcar como resuelto" onclick="resolverPQRSDirecto('<?= $pq['radicado'] ?>', '<?= htmlspecialchars(addslashes($pq['remitente'])) ?>')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-state-notice" id="emptyPQRS" style="display: none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <p>No se encontraron casos de PQRS con el filtro aplicado.</p>
                    </div>
                </div>
            <?php endif; ?>

        </main>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MODALES DEL SISTEMA ADMINISTRATIVO                                      -->
<!-- ======================================================================= -->

<!-- 1. Modal: Crear Nuevo Usuario -->
<div class="admin-modal-backdrop" id="modalUsuarioCrear" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalCrearTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap" style="background-color: #E3F2FD; color: #1565C0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalCrearTitle">Crear Nuevo Usuario</h3>
                <span class="admin-modal-subtitle">Registra una nueva cuenta institucional en la plataforma</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <form id="formCrearUsuario" onsubmit="ejecutarCrearUsuario(event)">
            <div class="admin-modal-body">
                <div class="admin-form-grid">
                    <div class="admin-form-group col-span-2">
                        <label for="crearNombre" class="admin-form-label">Nombre Completo <span style="color:#D32F2F;">*</span></label>
                        <input type="text" id="crearNombre" class="admin-form-input" required placeholder="Ej: Marcela Gómez Restrepo">
                    </div>

                    <div class="admin-form-group col-span-2">
                        <label for="crearEmail" class="admin-form-label">Correo Electrónico <span style="color:#D32F2F;">*</span></label>
                        <input type="email" id="crearEmail" class="admin-form-input" required placeholder="usuario@ejemplo.com">
                    </div>

                    <div class="admin-form-group col-span-2">
                        <label for="crearPassword" class="admin-form-label">Contraseña de Acceso <span style="color:#D32F2F;">*</span></label>
                        <input type="password" id="crearPassword" class="admin-form-input" required minlength="6" placeholder="Mínimo 6 caracteres">
                        <span class="admin-form-hint">Se almacenará encriptada con algoritmo Bcrypt seguro.</span>
                    </div>

                    <div class="admin-form-group">
                        <label for="crearRol" class="admin-form-label">Rol en la Plataforma <span style="color:#D32F2F;">*</span></label>
                        <select id="crearRol" class="admin-form-select">
                            <option value="2" selected>Egresado</option>
                            <option value="3">Estudiante</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label for="crearVerificacion" class="admin-form-label">Estado de Verificación</label>
                        <select id="crearVerificacion" class="admin-form-select">
                            <option value="aprobado" selected>Aprobado (Acceso inmediato)</option>
                            <option value="pendiente">Pendiente de validación</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>

                    <div class="admin-form-group col-span-2">
                        <label for="crearActivo" class="admin-form-label">Estado de la Cuenta</label>
                        <select id="crearActivo" class="admin-form-select">
                            <option value="1" selected>Cuenta Activa y Habilitada</option>
                            <option value="0">Cuenta Desactivada / Bloqueada</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="admin-modal-footer">
                <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
                <button type="submit" class="btn-modal-success" id="btnSubmitCrearUsuario">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Guardar y Crear Usuario</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Modal: Modificar Usuario -->
<div class="admin-modal-backdrop" id="modalUsuarioEditar" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalEditarTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap" style="background-color: #E3F2FD; color: #1565C0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalEditarTitle">Modificar Usuario</h3>
                <span class="admin-modal-subtitle">Actualiza la información de la cuenta y sus privilegios</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <form id="formEditarUsuario" onsubmit="ejecutarModificarUsuario(event)">
            <input type="hidden" id="editUserId">
            <div class="admin-modal-body">
                <div class="admin-form-grid">
                    <div class="admin-form-group col-span-2">
                        <label for="editNombre" class="admin-form-label">Nombre Completo <span style="color:#D32F2F;">*</span></label>
                        <input type="text" id="editNombre" class="admin-form-input" required>
                    </div>

                    <div class="admin-form-group col-span-2">
                        <label for="editEmail" class="admin-form-label">Correo Electrónico <span style="color:#D32F2F;">*</span></label>
                        <input type="email" id="editEmail" class="admin-form-input" required>
                    </div>

                    <div class="admin-form-group col-span-2">
                        <label for="editPassword" class="admin-form-label">Nueva Contraseña (Opcional)</label>
                        <input type="password" id="editPassword" class="admin-form-input" minlength="6" placeholder="Dejar en blanco para conservar la actual">
                        <span class="admin-form-hint">Escribe solo si deseas reestablecer la contraseña del usuario.</span>
                    </div>

                    <div class="admin-form-group">
                        <label for="editRol" class="admin-form-label">Rol en la Plataforma <span style="color:#D32F2F;">*</span></label>
                        <select id="editRol" class="admin-form-select">
                            <option value="2">Egresado</option>
                            <option value="3">Estudiante</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label for="editVerificacion" class="admin-form-label">Estado de Verificación</label>
                        <select id="editVerificacion" class="admin-form-select">
                            <option value="aprobado">Aprobado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>

                    <div class="admin-form-group col-span-2">
                        <label for="editActivo" class="admin-form-label">Estado de la Cuenta</label>
                        <select id="editActivo" class="admin-form-select">
                            <option value="1">Cuenta Activa (Habilitada)</option>
                            <option value="0">Cuenta Desactivada (Bloqueada)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="admin-modal-footer">
                <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
                <button type="submit" class="btn-modal-success" id="btnSubmitModificarUsuario">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Modal: Revisar Ficha Detallada de Usuario -->
<div class="admin-modal-backdrop" id="modalUsuarioDetalle" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalDetalleTitle">
    <div class="admin-modal-window modal-inspect-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap" style="background-color: #EFF6FF; color: #1D4ED8;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalDetalleTitle">Ficha de Usuario</h3>
                <span class="admin-modal-subtitle">Inspección de información de cuenta y perfil institucional</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <!-- Tarjeta Hero del Perfil -->
            <div class="user-inspect-header">
                <div class="user-inspect-avatar" id="inspectAvatar">U</div>
                <div class="user-inspect-meta">
                    <h4 class="user-inspect-name" id="inspectNombre">—</h4>
                    <div class="user-inspect-email-wrap">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="user-inspect-email" id="inspectEmail">—</span>
                    </div>
                    <div class="user-inspect-badges">
                        <span class="role-badge" id="inspectBadgeRol">Egresado</span>
                        <span class="status-pill" id="inspectBadgeVerif">Aprobado</span>
                        <span class="account-status-badge" id="inspectBadgeActivo">Activo</span>
                    </div>
                </div>
                <div class="user-inspect-header-action" id="inspectActionChatWrap">
                    <a href="#" id="inspectBtnChat" class="btn-action-chat" title="Abrir conversación directa en Mensajes">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>Enviar Mensaje</span>
                    </a>
                </div>
            </div>

            <!-- Sección: Datos de la Cuenta -->
            <div class="user-inspect-section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span>Datos de la Cuenta</span>
            </div>

            <div class="user-inspect-grid">
                <div class="inspect-stat-card">
                    <div class="stat-icon-pill icon-blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">ID de Sistema</span>
                        <span class="stat-value" id="inspectId">—</span>
                    </div>
                </div>

                <div class="inspect-stat-card">
                    <div class="stat-icon-pill icon-green">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Fecha de Registro</span>
                        <span class="stat-value" id="inspectCreado">—</span>
                    </div>
                </div>

                <div class="inspect-stat-card">
                    <div class="stat-icon-pill icon-amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Último Acceso</span>
                        <span class="stat-value" id="inspectUltimoAcceso">—</span>
                    </div>
                </div>

                <div class="inspect-stat-card">
                    <div class="stat-icon-pill icon-purple">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Rol Asignado</span>
                        <span class="stat-value" id="inspectRolTexto">—</span>
                    </div>
                </div>
            </div>

            <!-- Sección: Información de Egresado (condicional) -->
            <div id="inspectSeccionEgresado" style="display: none;">
                <div class="user-inspect-section-title" style="margin-top: 24px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    <span>Información de Egresado</span>
                </div>
                <div class="user-inspect-grid">
                    <div class="inspect-stat-card">
                        <div class="stat-icon-pill icon-teal">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"></rect><circle cx="9" cy="10" r="2"></circle><line x1="15" y1="8" x2="17" y2="8"></line><line x1="15" y1="12" x2="17" y2="12"></line></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Documento</span>
                            <span class="stat-value" id="inspectDocumento">—</span>
                        </div>
                    </div>

                    <div class="inspect-stat-card">
                        <div class="stat-icon-pill icon-blue">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Teléfono</span>
                            <span class="stat-value" id="inspectTelefono">—</span>
                        </div>
                    </div>

                    <div class="inspect-stat-card">
                        <div class="stat-icon-pill icon-green">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Ciudad</span>
                            <span class="stat-value" id="inspectCiudad">—</span>
                        </div>
                    </div>

                    <div class="inspect-stat-card">
                        <div class="stat-icon-pill icon-amber">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Año de Graduación</span>
                            <span class="stat-value" id="inspectAnio">—</span>
                        </div>
                    </div>

                    <div class="inspect-stat-card">
                        <div class="stat-icon-pill icon-purple">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Título Institucional</span>
                            <span class="stat-value" id="inspectTitulo">—</span>
                        </div>
                    </div>

                    <div class="inspect-stat-card">
                        <div class="stat-icon-pill icon-orange">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Ocupación / Empresa</span>
                            <span class="stat-value" id="inspectOcupacion">—</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cerrar</button>
            <button type="button" class="btn-modal-primary" id="btnInspectEditarDirecto" onclick="pasarDeDetalleAEditar()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <span>Modificar este Usuario</span>
            </button>
        </div>
    </div>
</div>

<!-- 4. Modal: Eliminar o Desactivar Usuario -->
<div class="admin-modal-backdrop" id="modalUsuarioEliminar" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalEliminarTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap" style="background-color: #FFEBEE; color: #D32F2F;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalEliminarTitle">Confirmar Eliminación</h3>
                <span class="admin-modal-subtitle">Acción administrativa de eliminación de cuenta</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="user-delete-confirm-box">
                <div class="user-delete-icon-wrap">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
                <h4 class="user-delete-confirm-title">¿Eliminar al usuario <span id="eliminarNombreUsuario">—</span>?</h4>
                <p class="user-delete-confirm-desc">
                    Esta operación retirará los accesos del usuario. Si el usuario cuenta con proyectos, noticias o registros vinculados, su cuenta será desactivada automáticamente para preservar el historial institucional.
                </p>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-danger" id="btnConfirmarEliminarUsuario" onclick="ejecutarEliminarUsuarioConfirmado()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                <span>Confirmar Eliminación</span>
            </button>
        </div>
    </div>
</div>

<!-- 5. Modal: Revisión de Solicitud de Registro -->
<div class="admin-modal-backdrop" id="modalRegistro" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalRegistroTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap" style="background-color: #E3F2FD; color: #1565C0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalRegistroTitle">Revisión de Solicitud de Registro</h3>
                <span class="admin-modal-subtitle">Gestiona la autorización de acceso para este egresado</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="modal-user-hero">
                <div class="modal-user-avatar" id="modalRegAvatar">U</div>
                <div class="modal-user-hero-info">
                    <h4 class="modal-user-name" id="modalRegNombre">—</h4>
                    <span class="modal-user-email" id="modalRegEmail">—</span>
                </div>
            </div>

            <div class="admin-detail-grid" style="grid-template-columns: 1fr; margin-top: 16px;">
                <div class="detail-item">
                    <span class="detail-label">Fecha de Solicitud</span>
                    <span class="detail-value" id="modalRegFecha">—</span>
                </div>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-danger" id="btnModalRechazarReg" onclick="rechazarRegistroDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                <span>Rechazar Solicitud</span>
            </button>
            <button type="button" class="btn-modal-success" id="btnModalAprobarReg" onclick="aprobarRegistroDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Aceptar y Autorizar</span>
            </button>
        </div>
    </div>
</div>

<!-- 6. Modal: Moderación de Contenido -->
<div class="admin-modal-backdrop" id="modalContenido" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalContenidoTitle">
    <div class="admin-modal-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap icon-orange">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E65100" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalContenidoTitle">Moderación de Publicación</h3>
                <span class="admin-modal-subtitle">Valida la veracidad y calidad antes de publicar en el portal público</span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body">
            <div class="modal-content-meta">
                <span class="type-pill type-noticia" id="modalContTipo">Noticia</span>
                <span class="category-badge" id="modalContCategoria">Eventos</span>
                <span class="status-pill status-pendiente" id="modalContEstado">Pendiente</span>
            </div>

            <h4 class="modal-content-title" id="modalContTitulo">—</h4>
            
            <div class="modal-author-row">
                <span class="author-label">Publicado por:</span>
                <strong id="modalContAutor">—</strong>
                <span class="author-sep">•</span>
                <span id="modalContFecha">—</span>
            </div>

            <div class="modal-content-preview-box">
                <span class="box-label">Cuerpo de la Publicación:</span>
                <p class="preview-full-text" id="modalContCompleto">—</p>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cerrar</button>
            <button type="button" class="btn-modal-danger" id="btnModalRechazarCont" onclick="rechazarContenidoDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                <span>Rechazar</span>
            </button>
            <button type="button" class="btn-modal-success" id="btnModalPublicarCont" onclick="publicarContenidoDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Aprobar y Publicar</span>
            </button>
        </div>
    </div>
</div>

<!-- 7. Modal: Resolución y Respuesta de PQRS -->
<div class="admin-modal-backdrop" id="modalPQRS" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalPQRSTitle">
    <div class="admin-modal-window modal-pqrs-window">
        <div class="admin-modal-header">
            <div class="modal-header-icon-wrap icon-red">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <div class="modal-header-text">
                <h3 class="admin-modal-title" id="modalPQRSTitle">Atención y Resolución de PQRS</h3>
                <span class="admin-modal-subtitle">Radicado Oficial: <strong id="modalPQRSRadicadoCode" class="modal-radicado-pill">—</strong></span>
            </div>
            <button type="button" class="admin-modal-close" onclick="cerrarModales()" aria-label="Cerrar modal">&times;</button>
        </div>

        <div class="admin-modal-body pqrs-modal-body">
            <div class="pqrs-detail-header-card">
                <div class="pqrs-user-profile">
                    <div class="pqrs-user-avatar" id="modalPQRSRemitenteAvatar">S</div>
                    <div class="pqrs-user-data">
                        <span class="pqrs-user-name" id="modalPQRSRemitente">—</span>
                        <span class="pqrs-user-email" id="modalPQRSCorreo">—</span>
                        <span class="pqrs-user-date">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span id="modalPQRSFecha">—</span>
                        </span>
                    </div>
                </div>
                <div class="pqrs-badges-cluster">
                    <span class="type-pill" id="modalPQRSTipo">PQRS</span>
                    <span class="priority-badge" id="modalPQRSPrioridad">Prioridad</span>
                    <span class="status-pill" id="modalPQRSEstado">Pendiente</span>
                </div>
            </div>

            <div class="pqrs-subject-container">
                <div class="pqrs-subject-header">
                    <span class="pqrs-field-kicker">ASUNTO DE LA SOLICITUD</span>
                </div>
                <h4 class="pqrs-subject-display" id="modalPQRSAsunto">—</h4>
            </div>

            <div class="pqrs-message-container">
                <div class="pqrs-message-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#757575" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <span class="pqrs-field-kicker">MENSAJE O DESCRIPCIÓN DEL CIUDADANO</span>
                </div>
                <p class="pqrs-message-text" id="modalPQRSDescripcion">—</p>
            </div>

            <div class="pqrs-attachment-card" id="modalPQRSImagenWrap" style="display: none;">
                <div class="pqrs-attachment-header">
                    <div class="pqrs-attachment-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        <strong>Evidencia o soporte adjunto</strong>
                    </div>
                    <a href="#" id="modalPQRSImagenLink" target="_blank" rel="noopener noreferrer" class="btn-attachment-open">
                        <span>Ver en tamaño original</span>
                    </a>
                </div>
                <div class="pqrs-attachment-frame">
                    <img id="modalPQRSImagenPreview" src="" alt="Evidencia adjunta de PQRS" onclick="window.open(this.src, '_blank')" title="Haz clic para ampliar">
                </div>
            </div>

            <div class="pqrs-response-section">
                <div class="pqrs-response-header">
                    <label for="adminRespuestaPQRS" class="pqrs-response-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D32F2F" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <span>Redactar Respuesta Institucional Oficial</span>
                        <span class="required-mark">*</span>
                    </label>
                    <span class="pqrs-response-hint">Se notificará automáticamente al correo del solicitante</span>
                </div>
                <textarea id="adminRespuestaPQRS" class="admin-textarea pqrs-response-textarea" rows="4" placeholder="Escribe aquí la respuesta oficial detallada que resolverá esta solicitud..."></textarea>
            </div>
        </div>

        <div class="admin-modal-footer">
            <button type="button" class="btn-modal-secondary" onclick="cerrarModales()">Cancelar</button>
            <button type="button" class="btn-modal-success" id="btnModalEnviarPQRS" onclick="enviarRespuestaPQRSDesdeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                <span>Enviar Respuesta Oficial y Resolver</span>
            </button>
        </div>
    </div>
</div>

<!-- 8. Toast de Notificaciones -->
<div class="admin-toast-notification" id="adminToast" role="alert" aria-live="assertive">
    <div class="admin-toast-icon-wrap" id="adminToastIcon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>
    <div class="admin-toast-content">
        <strong class="admin-toast-title" id="adminToastTitle">Acción completada</strong>
        <p class="admin-toast-desc" id="adminToastDesc">La operación administrativa se realizó con éxito.</p>
    </div>
    <button type="button" class="admin-toast-close" onclick="ocultarToastAdmin()" aria-label="Cerrar notificación">&times;</button>
</div>

<!-- ======================================================================= -->
<!-- SCRIPTS Y LIBRERÍA CHART.JS (SOLO SI SE ESTÁ EN EL DASHBOARD)           -->
<!-- ======================================================================= -->
<?php if ($tabActiva === 'dashboard'): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<?php endif; ?>

<script>
const CSRF_TOKEN = '<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8') ?>';
const PANTALLA_ACTIVA = '<?= $tabActiva ?>';
const ADMIN_USER_ID = <?= (int)$adminId ?>;
let currentModalEntityId = null;
let currentModalEntityType = null;
let currentDeleteUserId = null;
let toastTimeout = null;

// =============================================================================
// GRÁFICAS DEL DASHBOARD
// =============================================================================
<?php if ($tabActiva === 'dashboard'): ?>
const CHART_DATA = {
    registros: {
        labels: <?= json_encode(array_values(array_column($chartMeses, 'label'))) ?>,
        data: <?= json_encode(array_values(array_column($chartMeses, 'total'))) ?>
    },
    pqrs: {
        labels: <?= json_encode(array_keys($pqrTipos)) ?>,
        data: <?= json_encode(array_values($pqrTipos)) ?>
    },
    blog: {
        labels: ['Noticias', 'Proyectos', 'Oportunidades'],
        publicados: [
            <?= (int)$blogTotales['noticias']['publicado'] ?>,
            <?= (int)$blogTotales['proyectos']['publicado'] ?>,
            <?= (int)$blogTotales['oportunidades']['publicado'] ?>
        ],
        pendientes: [
            <?= (int)$blogTotales['noticias']['pendiente'] ?>,
            <?= (int)$blogTotales['proyectos']['pendiente'] ?>,
            <?= (int)$blogTotales['oportunidades']['pendiente'] ?>
        ]
    }
};

function inicializarGraficas() {
    if (typeof Chart === 'undefined') return;

    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.color = '#757575';

    // 1. Registros
    const ctxReg = document.getElementById('chartRegistrosCanvas');
    if (ctxReg) {
        new Chart(ctxReg, {
            type: 'line',
            data: {
                labels: CHART_DATA.registros.labels,
                datasets: [{
                    label: 'Nuevos Registros por Mes',
                    data: CHART_DATA.registros.data,
                    backgroundColor: 'rgba(21, 101, 192, 0.12)',
                    borderColor: '#1565C0',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#1565C0',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { boxWidth: 12, font: { weight: 600 } } },
                    tooltip: { backgroundColor: '#212121', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: '#F0F0F0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. PQRS
    const ctxPQRS = document.getElementById('chartPQRSCanvas');
    if (ctxPQRS) {
        new Chart(ctxPQRS, {
            type: 'doughnut',
            data: {
                labels: CHART_DATA.pqrs.labels,
                datasets: [{
                    data: CHART_DATA.pqrs.data,
                    backgroundColor: ['#1565C0', '#D32F2F', '#E65100', '#2E7D32', '#6A1B9A'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11, weight: 500 } } },
                    tooltip: { backgroundColor: '#212121', padding: 10, cornerRadius: 8 }
                },
                cutout: '65%'
            }
        });
    }

    // 3. Blog
    const ctxBlog = document.getElementById('chartBlogCanvas');
    if (ctxBlog) {
        new Chart(ctxBlog, {
            type: 'bar',
            data: {
                labels: CHART_DATA.blog.labels,
                datasets: [
                    { label: 'Publicados en Portal', data: CHART_DATA.blog.publicados, backgroundColor: '#2E7D32', borderRadius: 6 },
                    { label: 'En Moderación / Pendientes', data: CHART_DATA.blog.pendientes, backgroundColor: '#F57F17', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { weight: 600 } } },
                    tooltip: { backgroundColor: '#212121', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: '#F0F0F0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
}
<?php endif; ?>

// =============================================================================
// FILTROS EN TIEMPO REAL
// =============================================================================
function filtrarTablaActual() {
    const searchInput = document.getElementById('adminSearchInput');
    if (!searchInput) return;

    const query = searchInput.value.toLowerCase().trim();
    const statusSelect = document.getElementById('adminStatusFilter');
    const statusFilter = statusSelect ? statusSelect.value : 'todos';
    const roleSelect = document.getElementById('adminRoleFilter');
    const roleFilter = roleSelect ? roleSelect.value : 'todos';
    const clearBtn = document.getElementById('adminClearSearch');

    if (clearBtn) {
        clearBtn.style.display = query.length > 0 ? 'inline-flex' : 'none';
    }

    let currentTableId = 'tablaUsuarios';
    let emptyNoticeId = 'emptyUsuarios';

    if (PANTALLA_ACTIVA === 'registros') {
        currentTableId = 'tablaRegistros';
        emptyNoticeId = 'emptyRegistros';
    } else if (PANTALLA_ACTIVA === 'contenidos') {
        currentTableId = 'tablaContenidos';
        emptyNoticeId = 'emptyContenidos';
    } else if (PANTALLA_ACTIVA === 'pqrs') {
        currentTableId = 'tablaPQRS';
        emptyNoticeId = 'emptyPQRS';
    }

    const table = document.getElementById(currentTableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr.admin-table-row');
    let visibleCount = 0;
    const totalCount = rows.length;

    rows.forEach(row => {
        const textContent = row.textContent.toLowerCase();
        const rowStatus = (row.getAttribute('data-estado') || '').toLowerCase().trim();
        const rowRol = (row.getAttribute('data-rol') || '').toLowerCase().trim();
        const rowActivo = row.getAttribute('data-activo');

        const matchesQuery = query === '' || textContent.includes(query);

        let matchesRole = true;
        if (roleSelect && roleFilter !== 'todos') {
            matchesRole = (rowRol === roleFilter);
        }

        let matchesStatus = false;
        if (statusFilter === 'todos') {
            matchesStatus = true;
        } else if (statusFilter === 'pendiente') {
            matchesStatus = (rowStatus === 'pendiente');
        } else if (statusFilter === 'aprobado' || statusFilter === 'aprobado_publicado') {
            matchesStatus = (rowStatus === 'aprobado' || rowStatus === 'publicado' || rowStatus === 'resuelto');
        } else if (statusFilter === 'rechazado') {
            matchesStatus = (rowStatus === 'rechazado' || rowStatus === 'cerrado');
        } else if (statusFilter === 'activo') {
            matchesStatus = (rowActivo === '1');
        } else if (statusFilter === 'inactivo') {
            matchesStatus = (rowActivo === '0');
        }

        if (matchesQuery && matchesRole && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const counterEl = document.getElementById('adminResultsCounter');
    if (counterEl) {
        counterEl.textContent = `Mostrando ${visibleCount} de ${totalCount} registros`;
    }

    const emptyNotice = document.getElementById(emptyNoticeId);
    if (emptyNotice) {
        emptyNotice.style.display = visibleCount === 0 ? 'flex' : 'none';
    }
}

function limpiarBusqueda() {
    const input = document.getElementById('adminSearchInput');
    if (input) {
        input.value = '';
        filtrarTablaActual();
        input.focus();
    }
}

// =============================================================================
// MODALES Y MANIPULACIÓN
// =============================================================================
function cerrarModales() {
    document.querySelectorAll('.admin-modal-backdrop').forEach(modal => {
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
    });
    document.body.classList.remove('admin-modal-open');
    document.documentElement.classList.remove('admin-modal-open');
    currentModalEntityId = null;
    currentModalEntityType = null;
    currentDeleteUserId = null;
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModales();
});

document.querySelectorAll('.admin-modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', function(e) {
        if (e.target === this) cerrarModales();
    });
});

function abrirModalGenerico(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        document.body.classList.add('admin-modal-open');
        document.documentElement.classList.add('admin-modal-open');
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');
    }
}

// 1. Crear Usuario
function abrirModalCrearUsuario() {
    const form = document.getElementById('formCrearUsuario');
    if (form) form.reset();
    abrirModalGenerico('modalUsuarioCrear');
}

function ejecutarCrearUsuario(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitCrearUsuario');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'crear_usuario');
    formData.append('nombre_completo', document.getElementById('crearNombre').value.trim());
    formData.append('email', document.getElementById('crearEmail').value.trim());
    formData.append('password', document.getElementById('crearPassword').value);
    formData.append('rol_id', document.getElementById('crearRol').value);
    formData.append('estado_verificacion', document.getElementById('crearVerificacion').value);
    formData.append('activo', document.getElementById('crearActivo').value);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('index.php?page=administracion&tab=usuarios', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data && data.success) {
            cerrarModales();
            mostrarToastAdmin('¡Usuario Creado!', data.message);
            setTimeout(() => { window.location.href = 'index.php?page=administracion&tab=usuarios'; }, 800);
        } else {
            mostrarToastAdmin('Error al crear', (data && data.message) ? data.message : 'Error desconocido', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        console.error(err);
        mostrarToastAdmin('Error de conexión', 'No fue posible crear el usuario.', 'error');
    });
}

// 2. Ficha de Usuario
function abrirModalDetalleUsuario(userId) {
    const row = document.getElementById(`row-usuario-${userId}`);
    if (!row) return;

    let userData = null;
    try {
        userData = JSON.parse(row.getAttribute('data-user'));
    } catch (e) {
        return;
    }

    currentModalEntityId = userId;
    document.getElementById('inspectAvatar').textContent = userData.avatar || 'U';
    document.getElementById('inspectAvatar').style.backgroundColor = userData.color || '#1565C0';
    document.getElementById('inspectNombre').textContent = userData.nombre || '—';
    document.getElementById('inspectEmail').textContent = userData.email || '—';

    const badgeRol = document.getElementById('inspectBadgeRol');
    badgeRol.textContent = userData.rol_nombre.charAt(0).toUpperCase() + userData.rol_nombre.slice(1);
    badgeRol.className = 'role-badge role-' + userData.rol_nombre.toLowerCase();

    const badgeVerif = document.getElementById('inspectBadgeVerif');
    badgeVerif.textContent = userData.estado_verificacion.charAt(0).toUpperCase() + userData.estado_verificacion.slice(1);
    badgeVerif.className = 'status-pill status-' + userData.estado_verificacion;

    const badgeActivo = document.getElementById('inspectBadgeActivo');
    badgeActivo.textContent = (userData.activo === 1) ? 'Activo' : 'Inactivo';
    badgeActivo.className = 'account-status-badge ' + ((userData.activo === 1) ? 'status-active' : 'status-inactive');

    document.getElementById('inspectId').textContent = '#' + userData.id;
    document.getElementById('inspectCreado').textContent = userData.fecha;
    document.getElementById('inspectUltimoAcceso').textContent = userData.ultimo_acceso || 'Sin registro';
    document.getElementById('inspectRolTexto').textContent = userData.rol_nombre.toUpperCase();

    const secEgresado = document.getElementById('inspectSeccionEgresado');
    if (userData.rol_id === 2) {
        secEgresado.style.display = 'block';
        document.getElementById('inspectDocumento').textContent = userData.documento || 'No especificado';
        document.getElementById('inspectTelefono').textContent = userData.telefono || 'No especificado';
        document.getElementById('inspectCiudad').textContent = userData.ciudad || 'No especificada';
        document.getElementById('inspectAnio').textContent = userData.anio_graduacion ? 'Promoción ' + userData.anio_graduacion : 'No especificado';
        document.getElementById('inspectTitulo').textContent = userData.titulo_institucional || 'Bachiller Académico';
        const ocup = [userData.ocupacion, userData.empresa].filter(Boolean).join(' en ');
        document.getElementById('inspectOcupacion').textContent = ocup || 'No especificada';
    } else {
        secEgresado.style.display = 'none';
    }

    const chatWrap = document.getElementById('inspectActionChatWrap');
    const btnChat = document.getElementById('inspectBtnChat');
    if (chatWrap && btnChat) {
        if (userData.id !== ADMIN_USER_ID) {
            btnChat.href = `index.php?page=mensajes&contacto=${userData.id}`;
            chatWrap.style.display = 'block';
        } else {
            chatWrap.style.display = 'none';
        }
    }

    abrirModalGenerico('modalUsuarioDetalle');
}

function pasarDeDetalleAEditar() {
    if (!currentModalEntityId) return;
    const uid = currentModalEntityId;
    cerrarModales();
    abrirModalEditarUsuario(uid);
}

// 3. Modificar Usuario
function abrirModalEditarUsuario(userId) {
    const row = document.getElementById(`row-usuario-${userId}`);
    if (!row) return;

    let userData = null;
    try {
        userData = JSON.parse(row.getAttribute('data-user'));
    } catch (e) {
        return;
    }

    currentModalEntityId = userId;
    document.getElementById('editUserId').value = userId;
    document.getElementById('editNombre').value = userData.nombre;
    document.getElementById('editEmail').value = userData.email;
    document.getElementById('editPassword').value = '';
    document.getElementById('editRol').value = userData.rol_id;
    document.getElementById('editVerificacion').value = userData.estado_verificacion;
    document.getElementById('editActivo').value = userData.activo;

    abrirModalGenerico('modalUsuarioEditar');
}

function ejecutarModificarUsuario(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitModificarUsuario');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'modificar_usuario');
    formData.append('usuario_id', document.getElementById('editUserId').value);
    formData.append('nombre_completo', document.getElementById('editNombre').value.trim());
    formData.append('email', document.getElementById('editEmail').value.trim());
    formData.append('password', document.getElementById('editPassword').value);
    formData.append('rol_id', document.getElementById('editRol').value);
    formData.append('estado_verificacion', document.getElementById('editVerificacion').value);
    formData.append('activo', document.getElementById('editActivo').value);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('index.php?page=administracion&tab=usuarios', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data && data.success) {
            cerrarModales();
            mostrarToastAdmin('¡Usuario Actualizado!', data.message);
            setTimeout(() => { window.location.href = 'index.php?page=administracion&tab=usuarios'; }, 800);
        } else {
            mostrarToastAdmin('Error al modificar', (data && data.message) ? data.message : 'Error desconocido', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        console.error(err);
        mostrarToastAdmin('Error de conexión', 'No fue posible guardar los cambios.', 'error');
    });
}

// 4. Eliminar Usuario
function abrirModalEliminarUsuario(userId, nombre) {
    currentDeleteUserId = userId;
    document.getElementById('eliminarNombreUsuario').textContent = nombre;
    abrirModalGenerico('modalUsuarioEliminar');
}

function ejecutarEliminarUsuarioConfirmado() {
    if (!currentDeleteUserId) return;
    const btn = document.getElementById('btnConfirmarEliminarUsuario');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'eliminar_usuario');
    formData.append('usuario_id', currentDeleteUserId);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('index.php?page=administracion&tab=usuarios', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data && data.success) {
            cerrarModales();
            const row = document.getElementById(`row-usuario-${currentDeleteUserId}`);
            if (row) {
                if (data.eliminado) {
                    row.remove();
                } else {
                    row.setAttribute('data-activo', '0');
                    const badgeActivo = row.querySelector('.account-status-badge');
                    if (badgeActivo) {
                        badgeActivo.className = 'account-status-badge status-inactive';
                        badgeActivo.textContent = 'Inactivo';
                    }
                }
            }
            mostrarToastAdmin('Operación completada', data.message);
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error en la eliminación', (data && data.message) ? data.message : 'No se pudo eliminar.', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        console.error(err);
        mostrarToastAdmin('Error de conexión', 'No se pudo comunicar con el servidor.', 'error');
    });
}

// 5. Solicitudes de Registro
function abrirModalRegistro(id) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    currentModalEntityId = id;
    const nombre = row.getAttribute('data-nombre') || '';
    const email = row.getAttribute('data-email') || '';
    const fecha = row.getAttribute('data-fecha') || '';
    const estado = row.getAttribute('data-estado') || 'Pendiente';

    document.getElementById('modalRegAvatar').textContent = nombre.charAt(0).toUpperCase();
    document.getElementById('modalRegNombre').textContent = nombre;
    document.getElementById('modalRegEmail').textContent = email;
    document.getElementById('modalRegFecha').textContent = fecha;

    const btnAprobar = document.getElementById('btnModalAprobarReg');
    const btnRechazar = document.getElementById('btnModalRechazarReg');
    const isPendiente = estado.toLowerCase() === 'pendiente';

    if (btnAprobar) btnAprobar.style.display = isPendiente ? 'inline-flex' : 'none';
    if (btnRechazar) btnRechazar.style.display = isPendiente ? 'inline-flex' : 'none';

    abrirModalGenerico('modalRegistro');
}

function aprobarRegistroDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-registro-${currentModalEntityId}`);
    const nombre = row ? row.getAttribute('data-nombre') : 'Solicitante';
    ejecutarAccionRegistro(currentModalEntityId, 'aprobar_registro', nombre);
    cerrarModales();
}

function rechazarRegistroDesdeModal() {
    if (!currentModalEntityId) return;
    const row = document.getElementById(`row-registro-${currentModalEntityId}`);
    const nombre = row ? row.getAttribute('data-nombre') : 'Solicitante';
    ejecutarAccionRegistro(currentModalEntityId, 'rechazar_registro', nombre);
    cerrarModales();
}

function ejecutarAccionRegistro(id, accion, nombre) {
    const row = document.getElementById(`row-registro-${id}`);
    if (!row) return;

    const buttons = row.querySelectorAll('button');
    buttons.forEach(b => b.disabled = true);

    const formData = new FormData();
    formData.append('action', accion);
    formData.append('usuario_id', id);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('index.php?page=administracion&tab=registros', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        buttons.forEach(b => b.disabled = false);
        if (data && data.success) {
            const nuevoEstado = (accion === 'aprobar_registro') ? 'Aprobado' : 'Rechazado';
            row.setAttribute('data-estado', nuevoEstado);

            const actionsGroup = row.querySelector('.actions-button-group');
            if (actionsGroup) {
                actionsGroup.innerHTML = `
                    <button type="button" class="btn-action-sm btn-action-view" title="Revisar solicitud" onclick="abrirModalRegistro(${id})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span>Revisar</span>
                    </button>
                    <span class="${accion === 'aprobar_registro' ? 'action-confirmed-tag' : 'action-rejected-tag'}">
                        ${accion === 'aprobar_registro' ? '✓ Aprobado' : '✕ Rechazado'}
                    </span>
                `;
            }

            mostrarToastAdmin(
                (accion === 'aprobar_registro') ? '¡Registro Aceptado!' : 'Solicitud Rechazada',
                data.message || `Operación procesada para ${nombre}.`
            );
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error en la operación', (data && data.message) ? data.message : 'Error desconocido.', 'error');
        }
    })
    .catch(err => {
        buttons.forEach(b => b.disabled = false);
        console.error(err);
        mostrarToastAdmin('Error de conexión', 'No fue posible comunicar con el servidor.', 'error');
    });
}

// 6. Moderación de Contenidos
function abrirModalContenido(tipo, id) {
    const row = document.getElementById(`row-contenido-${tipo}-${id}`);
    if (!row) return;

    currentModalEntityId = id;
    currentModalEntityType = tipo;

    const tipoLabel = row.getAttribute('data-tipo-label') || tipo;
    const titulo = row.getAttribute('data-titulo') || '';
    const autor = row.getAttribute('data-autor') || '';
    const cat = row.getAttribute('data-categoria') || '';
    const fecha = row.getAttribute('data-fecha') || '';
    const estado = row.getAttribute('data-estado') || 'Pendiente';
    const comp = row.getAttribute('data-contenido') || '';

    document.getElementById('modalContTipo').textContent = tipoLabel;
    document.getElementById('modalContTipo').className = `type-pill type-${tipo.toLowerCase()}`;
    document.getElementById('modalContCategoria').textContent = cat;
    document.getElementById('modalContEstado').textContent = estado;
    document.getElementById('modalContEstado').className = `status-pill status-${estado.toLowerCase()}`;
    document.getElementById('modalContTitulo').textContent = titulo;
    document.getElementById('modalContAutor').textContent = autor;
    document.getElementById('modalContFecha').textContent = fecha;
    document.getElementById('modalContCompleto').textContent = comp;

    const btnAprobar = document.getElementById('btnModalPublicarCont');
    const btnRechazar = document.getElementById('btnModalRechazarCont');
    const isPendiente = estado.toLowerCase() === 'pendiente';
    if (btnAprobar) btnAprobar.style.display = isPendiente ? 'inline-flex' : 'none';
    if (btnRechazar) btnRechazar.style.display = isPendiente ? 'inline-flex' : 'none';

    abrirModalGenerico('modalContenido');
}

function publicarContenidoDesdeModal() {
    if (!currentModalEntityId || !currentModalEntityType) return;
    const row = document.getElementById(`row-contenido-${currentModalEntityType}-${currentModalEntityId}`);
    const titulo = row ? row.getAttribute('data-titulo') : 'Contenido';
    publicarContenidoDirecto(currentModalEntityType, currentModalEntityId, titulo);
    cerrarModales();
}

function rechazarContenidoDesdeModal() {
    if (!currentModalEntityId || !currentModalEntityType) return;
    const row = document.getElementById(`row-contenido-${currentModalEntityType}-${currentModalEntityId}`);
    const titulo = row ? row.getAttribute('data-titulo') : 'Contenido';
    rechazarContenidoDirecto(currentModalEntityType, currentModalEntityId, titulo);
    cerrarModales();
}

function publicarContenidoDirecto(tipo, id, titulo) {
    ejecutarAccionContenido(tipo, id, 'aprobar_contenido', titulo);
}

function rechazarContenidoDirecto(tipo, id, titulo) {
    ejecutarAccionContenido(tipo, id, 'rechazar_contenido', titulo);
}

function ejecutarAccionContenido(tipo, id, accion, titulo) {
    const row = document.getElementById(`row-contenido-${tipo}-${id}`);
    if (!row) return;

    const buttons = row.querySelectorAll('button');
    buttons.forEach(b => b.disabled = true);

    const formData = new FormData();
    formData.append('action', accion);
    formData.append('tipo', tipo);
    formData.append('id', id);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('index.php?page=administracion&tab=contenidos', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        buttons.forEach(b => b.disabled = false);
        if (data && data.success) {
            const nuevoEstado = (accion === 'aprobar_contenido') ? 'Publicado' : 'Rechazado';
            row.setAttribute('data-estado', nuevoEstado);

            const statusCell = row.querySelector('.status-pill');
            if (statusCell) {
                statusCell.className = `status-pill status-${nuevoEstado.toLowerCase()}`;
                statusCell.textContent = nuevoEstado;
            }

            const actionsGroup = row.querySelector('.actions-button-group');
            if (actionsGroup) {
                actionsGroup.innerHTML = `
                    <button type="button" class="btn-action-sm btn-action-view" title="Examinar y publicar" onclick="abrirModalContenido('${tipo}', ${id})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span>Examinar</span>
                    </button>
                    <span class="${accion === 'aprobar_contenido' ? 'action-confirmed-tag' : 'action-rejected-tag'}">
                        ${accion === 'aprobar_contenido' ? '✓ Publicado' : '✕ Rechazado'}
                    </span>
                `;
            }

            const tituloToast = (accion === 'aprobar_contenido') ? '¡Publicación Aprobada!' : 'Contenido Rechazado';
            mostrarToastAdmin(tituloToast, data.message || `"${titulo}" fue actualizado.`);
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error en la moderación', (data && data.message) ? data.message : 'No se pudo completar.', 'error');
        }
    })
    .catch(err => {
        buttons.forEach(b => b.disabled = false);
        console.error(err);
        mostrarToastAdmin('Error de conexión', 'No se pudo comunicar con el servidor.', 'error');
    });
}

// 7. Atención de PQRS
function abrirModalPQRS(radicado) {
    const row = document.getElementById(`row-pqrs-${radicado}`);
    if (!row) return;

    currentModalEntityId = radicado;
    const tipo = row.getAttribute('data-tipo');
    const remitente = row.getAttribute('data-remitente');
    const correo = row.getAttribute('data-correo');
    const asunto = row.getAttribute('data-asunto');
    const fecha = row.getAttribute('data-fecha');
    const prioridad = row.getAttribute('data-prioridad');
    const estado = row.getAttribute('data-estado');
    const desc = row.getAttribute('data-descripcion');
    const resp = row.getAttribute('data-respuesta');
    const imagen = row.getAttribute('data-imagen') || '';

    document.getElementById('modalPQRSRadicadoCode').textContent = radicado;
    document.getElementById('modalPQRSTipo').textContent = tipo;
    document.getElementById('modalPQRSTipo').className = `type-pill type-${tipo.toLowerCase().replace(/ /g, '-')}`;
    document.getElementById('modalPQRSPrioridad').textContent = `Prioridad ${prioridad}`;
    document.getElementById('modalPQRSPrioridad').className = `priority-badge priority-${prioridad.toLowerCase()}`;
    document.getElementById('modalPQRSEstado').textContent = estado;
    document.getElementById('modalPQRSEstado').className = `status-pill status-${estado.toLowerCase()}`;
    
    document.getElementById('modalPQRSRemitente').textContent = remitente;
    document.getElementById('modalPQRSCorreo').textContent = correo;
    document.getElementById('modalPQRSFecha').textContent = fecha;
    document.getElementById('modalPQRSAsunto').textContent = asunto;
    document.getElementById('modalPQRSDescripcion').textContent = desc;

    const imgWrap = document.getElementById('modalPQRSImagenWrap');
    const imgPreview = document.getElementById('modalPQRSImagenPreview');
    const imgLink = document.getElementById('modalPQRSImagenLink');
    if (imgWrap && imgPreview && imgLink) {
        if (imagen && imagen.trim() !== '') {
            imgPreview.src = imagen;
            imgLink.href = imagen;
            imgWrap.style.display = 'block';
        } else {
            imgPreview.src = '';
            imgLink.href = '#';
            imgWrap.style.display = 'none';
        }
    }

    document.getElementById('adminRespuestaPQRS').value = resp || '';

    const avatarEl = document.getElementById('modalPQRSRemitenteAvatar');
    if (avatarEl) {
        avatarEl.textContent = remitente ? remitente.trim().charAt(0).toUpperCase() : 'C';
    }

    abrirModalGenerico('modalPQRS');
}

function enviarRespuestaPQRSDesdeModal() {
    if (!currentModalEntityId) return;
    const respuesta = document.getElementById('adminRespuestaPQRS').value.trim();
    if (!respuesta) {
        alert('Por favor redacta la respuesta institucional oficial antes de enviar.');
        document.getElementById('adminRespuestaPQRS').focus();
        return;
    }

    const row = document.getElementById(`row-pqrs-${currentModalEntityId}`);
    const remitente = row ? row.getAttribute('data-remitente') : 'Ciudadano';

    resolverPQRSDirecto(currentModalEntityId, remitente, respuesta);
    cerrarModales();
}

function resolverPQRSDirecto(radicado, remitente, respuesta = '') {
    const row = document.getElementById(`row-pqrs-${radicado}`);
    if (!row) return;

    const formData = new FormData();
    formData.append('action', 'responder_pqrs');
    formData.append('radicado', radicado);
    formData.append('respuesta', respuesta);
    formData.append('csrf_token', CSRF_TOKEN);
    formData.append('is_ajax', '1');

    fetch('index.php?page=administracion&tab=pqrs', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            row.setAttribute('data-estado', 'Resuelto');
            row.setAttribute('data-respuesta', respuesta);
            const statusCell = row.querySelector('.status-pill');
            if (statusCell) {
                statusCell.className = 'status-pill status-resuelto';
                statusCell.textContent = 'Resuelto';
            }

            const actionsGroup = row.querySelector('.actions-button-group');
            if (actionsGroup) {
                actionsGroup.innerHTML = `
                    <button type="button" class="btn-action-sm btn-action-view" title="Atender y responder caso" onclick="abrirModalPQRS('${radicado}')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>Ver Caso</span>
                    </button>
                    <span class="action-confirmed-tag">✓ Resuelto</span>
                `;
            }

            mostrarToastAdmin('¡Caso Resuelto!', data.message || `Respuesta guardada para ${remitente}.`);
            filtrarTablaActual();
        } else {
            mostrarToastAdmin('Error al responder', (data && data.message) ? data.message : 'No se pudo guardar.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        mostrarToastAdmin('Error de conexión', 'No fue posible comunicar con el servidor.', 'error');
    });
}

// 8. Toast Helper
function mostrarToastAdmin(titulo, mensaje, tipo = 'success') {
    const toast = document.getElementById('adminToast');
    if (!toast) return;

    document.getElementById('adminToastTitle').textContent = titulo;
    document.getElementById('adminToastDesc').textContent = mensaje;

    clearTimeout(toastTimeout);
    toast.classList.add('is-visible');

    toastTimeout = setTimeout(() => {
        ocultarToastAdmin();
    }, 4000);
}

function ocultarToastAdmin() {
    const toast = document.getElementById('adminToast');
    if (toast) toast.classList.remove('is-visible');
}

// Inicialización de la pantalla al cargar
document.addEventListener('DOMContentLoaded', () => {
    if (PANTALLA_ACTIVA === 'dashboard') {
        <?php if ($tabActiva === 'dashboard'): ?>
        inicializarGraficas();
        <?php endif; ?>
    } else {
        filtrarTablaActual();
    }
});
</script>

<!-- Carga del pie de página y scripts institucionales -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
