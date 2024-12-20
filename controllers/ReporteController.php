<?php
// controllers/ReporteController.php
require_once __DIR__ . '/../models/Tarea.php';
require_once __DIR__ . '/../config/Database.php';

class ReporteController {
    private $tareaModel;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->tareaModel = new Tarea($this->db);
    }

    public function index() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/usuario/login');
            exit;
        }

        // Obtener las tareas del usuario
        $usuario_id = $_SESSION['usuario_id'];
        $tareas = $this->tareaModel->obtenerTareasPorUsuario($usuario_id);

        // Cargar la vista de reportes
        require_once __DIR__ . '/../views/reportes/index.php';
    }

    public function generarPDF() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/usuario/login');
            exit;
        }

        // Obtener las tareas del usuario
        $usuario_id = $_SESSION['usuario_id'];
        $tareas = $this->tareaModel->obtenerTareasPorUsuario($usuario_id);

        // Generar el contenido HTML para el PDF
        ob_start();
        require_once __DIR__ . '/../views/reportes/pdf_template.php';
        $html = ob_get_clean();

        // Crear instancia de Dompdf
        require_once __DIR__ . '/../vendor/autoload.php';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        // Configurar el papel y orientación
        $dompdf->setPaper('A4', 'portrati');

        // Renderizar el PDF
        $dompdf->render();

        // Generar nombre del archivo
        $filename = 'reporte_tareas_' . date('Y-m-d_H-i-s') . '.pdf';

        // Enviar el PDF al navegador
        $dompdf->stream($filename, array('Attachment' => true));
        exit;
    }
}