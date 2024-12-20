<?php
// Configuración de errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de caracteres
ini_set('default_charset', 'UTF-8');

// Configuración de la zona horaria
date_default_timezone_set('America/Lima');

// URL base del proyecto
if (!defined('BASE_URL')) {
    define('BASE_URL', '/sistema_gestion_tareas');
}

// Configuración de sesión
if (session_status() === PHP_SESSION_NONE) {
    // Configuración de seguridad de sesión
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
    
    // Iniciar sesión
    session_start();
}

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_gestion_tareas');
define('DB_USER', 'root');
define('DB_PASS', '');

// Rutas de la aplicación
define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/views');
define('MODELS_PATH', ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('CONFIG_PATH', ROOT_PATH . '/config');