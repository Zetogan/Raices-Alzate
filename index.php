<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Enrutador Principal / Front Controller
 * Versión: 0.2.1
 */

declare(strict_types=1);

// 1. Iniciar sesión de forma segura antes de emitir cualquier cabecera o HTML
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// 2. Carga de configuración global, conexión a base de datos y helpers
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

// 3. Enrutamiento dinámico por parámetro $_GET['page']
$page = trim($_GET['page'] ?? 'inicio');

// Tabla de rutas canónicas hacia la carpeta /pages/
$routes = [
    'inicio'               => 'inicio.php',
    'login'                => 'login.php',
    'autenticacion'        => 'login.php',
    'registro'             => 'registro.php',
    'recuperar'            => 'recuperar-password.php',
    'recuperar-password'   => 'recuperar-password.php',
    'recuperar-contrasena' => 'recuperar-password.php',
    'reset-password'       => 'reset-password.php',
    'cambiar-password'     => 'reset-password.php',
    'reset'                => 'reset-password.php',
    'dashboard'            => 'dashboard.php',
    'perfil'               => 'perfil.php',
    'editar-perfil'        => 'editar-perfil.php',
    'egresados'            => 'egresados.php',
    'detalle-egresado'     => 'detalle-egresado.php',
    'proyectos'            => 'proyectos.php',
    'crear-proyecto'       => 'crear-proyecto.php',
    'editar-proyecto'      => 'editar-proyecto.php',
    'detalle-proyecto'     => 'detalle-proyecto.php',
    'noticias'             => 'noticias.php',
    'crear-noticia'        => 'crear-noticia.php',
    'detalle-noticia'      => 'detalle-noticia.php',
    'oportunidades'        => 'oportunidades.php',
    'crear-oportunidad'    => 'crear-oportunidad.php',
    'detalle-oportunidad'  => 'detalle-oportunidad.php',
    'mensajes'             => 'mensajes.php',
    'blog'                 => 'blog.php',
    'novedades'            => 'blog.php',
    'pqr'                  => 'pqr.php',
    'pqrs'                 => 'pqr.php',
    'soporte'              => 'pqr.php',
    'administracion'       => 'administracion.php',
    'admin'                => 'administracion.php',
    'admin-dashboard'      => 'administracion.php',
    'admin-usuarios'       => 'administracion.php',
    'admin-registros'      => 'administracion.php',
    'admin-contenidos'     => 'administracion.php',
    'admin-pqrs'           => 'administracion.php',
    'logout'               => 'logout.php',
];

// Resolución segura del archivo de vista / controlador
if (isset($routes[$page])) {
    $targetFile = __DIR__ . '/pages/' . $routes[$page];
} else {
    // Si la página existe físicamente en pages/, permitir cargarla limpiamente
    $cleanPage = preg_replace('/[^a-zA-Z0-9_-]/', '', $page);
    $candidate = __DIR__ . '/pages/' . $cleanPage . '.php';
    if ($cleanPage !== '' && file_exists($candidate)) {
        $targetFile = $candidate;
    } else {
        http_response_code(404);
        $pageTitle = 'Página no encontrada | ' . APP_NAME;
        require_once __DIR__ . '/includes/header.php';
        ?>
        <main class="main-content" style="padding: 100px 20px; text-align: center;">
            <div class="container" style="max-width: 600px; margin: 0 auto;">
                <h1 style="font-size: 5rem; font-weight: 800; color: #D32F2F; margin-bottom: 8px;">404</h1>
                <h2 style="font-size: 1.5rem; color: #212121; margin-bottom: 16px;">Página no encontrada</h2>
                <p style="color: #757575; line-height: 1.6; margin-bottom: 28px;">
                    Lo sentimos, la página que buscas no existe o ha sido movida a una nueva ubicación dentro de nuestra plataforma organizada.
                </p>
                <a href="<?php echo url('index.php'); ?>" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    Volver al Inicio
                </a>
            </div>
        </main>
        <?php
        require_once __DIR__ . '/includes/footer.php';
        exit;
    }
}

// 4. Carga y ejecución modular de la página solicitada
require $targetFile;
