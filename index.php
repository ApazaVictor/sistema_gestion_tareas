<?php
// Cargar el archivo de configuración primero
require_once __DIR__ . '/config/config.php';

// Cargar los controladores
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/TareaController.php';
require_once __DIR__ . '/controllers/CalendarioController.php';

// Incluir el archivo de rutas
require_once __DIR__ . '/routes.php';