<?php
/**
 * Raíces Alzate - Plataforma de Egresados
 * Institución Educativa Gilberto Alzate Avendaño
 * Archivo de entrada principal (Front Controller)
 * Versión: 0.1.1
 */

// Carga de configuración global (sin conexión a base de datos en esta etapa)
require_once __DIR__ . '/config/config.php';

// Enrutamiento básico por parámetro
$page = $_GET['page'] ?? 'inicio';

switch ($page) {
    case 'login':
    case 'autenticacion':
        $pageTitle = 'Iniciar sesión | ' . APP_NAME;
        $currentPage = 'login';
        $viewFile = __DIR__ . '/pages/login.php';
        break;
    case 'registro':
        $pageTitle = 'Crear una cuenta | ' . APP_NAME;
        $currentPage = 'registro';
        $viewFile = __DIR__ . '/pages/registro.php';
        break;
    case 'recuperar':
    case 'recuperar-password':
        $pageTitle = 'Recuperar contraseña | ' . APP_NAME;
        $currentPage = 'recuperar';
        $viewFile = __DIR__ . '/pages/recuperar-password.php';
        break;
    case 'proyectos':
        $pageTitle = 'Proyectos | ' . APP_NAME;
        $currentPage = 'proyectos';
        $viewFile = __DIR__ . '/pages/proyectos.php';
        break;
    case 'egresados':
        $pageTitle = 'Egresados | ' . APP_NAME;
        $currentPage = 'egresados';
        $viewFile = __DIR__ . '/pages/egresados.php';
        break;
    case 'noticias':
        $pageTitle = 'Noticias | ' . APP_NAME;
        $currentPage = 'noticias';
        $viewFile = __DIR__ . '/pages/noticias.php';
        break;
    case 'dashboard':
        header('Location: dashboard.php');
        exit;
    case 'inicio':
    default:
        $pageTitle = APP_NAME . ' | IE Gilberto Alzate Avendaño';
        $currentPage = 'inicio';
        $viewFile = __DIR__ . '/pages/inicio.php';
        break;
}

// Renderizado modular de la interfaz
require_once __DIR__ . '/includes/header.php';
require_once $viewFile;
require_once __DIR__ . '/includes/footer.php';
