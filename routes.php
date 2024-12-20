<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/TareaController.php';
require_once __DIR__ . '/controllers/CalendarioController.php';

// Obtener la ruta actual
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$route = str_replace($base_path, '', $request_uri);
$route = strtok($route, '?');

// Definir las rutas
$routes = [
    // Rutas de autenticación
    '/login' => ['controller' => 'AuthController', 'action' => 'login'],
    '/registro' => ['controller' => 'AuthController', 'action' => 'registro'],
    '/usuario/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    '/usuario/perfil' => ['controller' => 'AuthController', 'action' => 'perfil'],
    '/usuario/configuracion' => ['controller' => 'AuthController', 'action' => 'configuracion'],

    // Rutas de tareas
    '/' => ['controller' => 'TareaController', 'action' => 'index'],
    '/tareas' => ['controller' => 'TareaController', 'action' => 'index'],
    '/tareas/crear' => ['controller' => 'TareaController', 'action' => 'crear'],
    '/tareas/editar/{id}' => ['controller' => 'TareaController', 'action' => 'editar'],
    '/tareas/eliminar/{id}' => ['controller' => 'TareaController', 'action' => 'eliminar'],
    '/tareas/generar-pdf' => ['controller' => 'TareaController', 'action' => 'generarPDF'],

    // Rutas de calendario
    '/calendario' => ['controller' => 'CalendarioController', 'action' => 'index'],
];

// Función para procesar rutas con parámetros
function matchRoute($requestRoute, $definedRoutes) {
    foreach ($definedRoutes as $pattern => $handler) {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/\{([a-z]+)\}/', '([^\/]+)', $pattern);
        $pattern = '/^' . $pattern . '$/';

        if (preg_match($pattern, $requestRoute, $matches)) {
            array_shift($matches); // Eliminar la coincidencia completa
            return ['handler' => $handler, 'params' => $matches];
        }
    }
    return false;
}

// Procesar la ruta
$match = matchRoute($route, $routes);

if ($match) {
    $controller_name = $match['handler']['controller'];
    $action = $match['handler']['action'];
    $params = $match['params'];

    // Verificar autenticación excepto para rutas públicas
    if (!in_array($route, ['/login', '/registro']) && !isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    $controller = new $controller_name();
    
    if (!empty($params)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        $controller->$action();
    }
} else {
    // Ruta no encontrada
    http_response_code(404);
    require_once __DIR__ . '/views/errors/404.php';
    exit;
}
