<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../config/database.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $db = $database->getConnection();
        $this->usuarioModel = new Usuario($db);
    }

    public function index() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Obtener los datos del usuario actual
        $usuario = $this->usuarioModel->obtenerPorId($_SESSION['id']);
        require_once __DIR__ . '/../views/usuario/index.php';
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

        require_once __DIR__ . '/../views/usuario/perfil.php';
    }

    public function cambiarPassword() {
        if (!isset($_SESSION['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password_actual = $_POST['password_actual'] ?? '';
            $password_nuevo = $_POST['password_nuevo'] ?? '';
            $password_confirmar = $_POST['password_confirmar'] ?? '';

            if (empty($password_actual) || empty($password_nuevo) || empty($password_confirmar)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios';
                header('Location: ' . BASE_URL . '/usuario/cambiar-password');
                exit;
            }

            if ($password_nuevo !== $password_confirmar) {
                $_SESSION['error'] = 'Las contraseñas nuevas no coinciden';
                header('Location: ' . BASE_URL . '/usuario/cambiar-password');
                exit;
            }

            // Verificar la contraseña actual
            $usuario = $this->usuarioModel->obtenerPorId($_SESSION['id']);
            if (!password_verify($password_actual, $usuario['password'])) {
                $_SESSION['error'] = 'La contraseña actual es incorrecta';
                header('Location: ' . BASE_URL . '/usuario/cambiar-password');
                exit;
            }

            // Actualizar la contraseña
            if ($this->usuarioModel->actualizarPassword($_SESSION['id'], $password_nuevo)) {
                $_SESSION['success'] = 'Contraseña actualizada exitosamente';
                header('Location: ' . BASE_URL . '/usuario/perfil');
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar la contraseña';
                header('Location: ' . BASE_URL . '/usuario/cambiar-password');
                exit;
            }
        }

        require_once __DIR__ . '/../views/usuario/cambiar_password.php';
    }

    public function configuracion() {
        if (!isset($_SESSION['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Aquí puedes agregar la lógica para guardar la configuración del usuario
            $_SESSION['success'] = 'Configuración actualizada exitosamente';
            header('Location: ' . BASE_URL . '/usuario/configuracion');
            exit;
        }

        require_once __DIR__ . '/../views/usuario/configuracion.php';
    }

    public function eliminarCuenta() {
        if (!isset($_SESSION['id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';

            if (empty($password)) {
                $_SESSION['error'] = 'Debes confirmar tu contraseña';
                header('Location: ' . BASE_URL . '/usuario/eliminar-cuenta');
                exit;
            }

            // Verificar la contraseña
            $usuario = $this->usuarioModel->obtenerPorId($_SESSION['id']);
            if (!password_verify($password, $usuario['password'])) {
                $_SESSION['error'] = 'Contraseña incorrecta';
                header('Location: ' . BASE_URL . '/usuario/eliminar-cuenta');
                exit;
            }

            // Eliminar la cuenta
            if ($this->usuarioModel->eliminar($_SESSION['id'])) {
                session_destroy();
                header('Location: ' . BASE_URL . '/login?mensaje=cuenta_eliminada');
                exit;
            } else {
                $_SESSION['error'] = 'Error al eliminar la cuenta';
                header('Location: ' . BASE_URL . '/usuario/eliminar-cuenta');
                exit;
            }
        }

        require_once __DIR__ . '/../views/usuario/eliminar_cuenta.php';
    }
}
