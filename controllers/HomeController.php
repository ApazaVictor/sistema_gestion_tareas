<?php
class HomeController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/usuario/login');
            exit;
        }
        require_once __DIR__ . '/../views/home/index.php';
    }

    public function acerca()
    {
        require_once __DIR__ . '/../views/home/acerca.php';
    }
}