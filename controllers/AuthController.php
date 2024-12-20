<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $db = $database->getConnection();
        $this->usuarioModel = new Usuario($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($password)) {
                $_SESSION['error'] = 'Por favor complete todos los campos';
                header('Location: ' . BASE_URL . '/login');
                exit;
            }

            $usuario = $this->usuarioModel->login($nombre, $password);
            if ($usuario) {
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];
                
                header('Location: ' . BASE_URL . '/tareas');
                exit;
            } else {
                $_SESSION['error'] = 'Usuario o contraseña incorrectos';
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($nombre) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Por favor complete todos los campos';
                header('Location: ' . BASE_URL . '/registro');
                exit;
            }

            if ($this->usuarioModel->crear($nombre, $email, $password)) {
                $_SESSION['success'] = 'Usuario registrado exitosamente';
                header('Location: ' . BASE_URL . '/login');
                exit;
            } else {
                $_SESSION['error'] = 'Error al registrar el usuario';
                header('Location: ' . BASE_URL . '/registro');
                exit;
            }
        }
        require_once __DIR__ . '/../views/auth/registro.php';
    }

    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Destruir la sesión
        session_destroy();

        // Redireccionar al login
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    public function perfil() {
        if (!isset($_SESSION['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $usuario = $this->usuarioModel->obtenerPorId($_SESSION['id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            if (empty($nombre) || empty($email)) {
                $_SESSION['error'] = 'El nombre y email son obligatorios';
                header('Location: ' . BASE_URL . '/usuario/perfil');
                exit;
            }

            if ($this->usuarioModel->actualizar($_SESSION['id'], $nombre, $email, $password)) {
                $_SESSION['nombre'] = $nombre;
                $_SESSION['email'] = $email;
                $_SESSION['success'] = 'Perfil actualizado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
            }
            header('Location: ' . BASE_URL . '/usuario/perfil');
            exit;
        }

        require_once __DIR__ . '/../views/auth/perfil.php';
    }

    public function configuracion() {
        if (!isset($_SESSION['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Aquí irá la lógica para actualizar la configuración
            $_SESSION['success'] = 'Configuración actualizada exitosamente';
            header('Location: ' . BASE_URL . '/usuario/configuracion');
            exit;
        }

        require_once __DIR__ . '/../views/auth/configuracion.php';
    }
}