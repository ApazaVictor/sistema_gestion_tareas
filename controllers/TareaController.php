<?php
require_once __DIR__ . '/../models/Tarea.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

class TareaController {
    private $tareaModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $this->tareaModel = new Tarea($db);
    }

    public function index() {
        $tareas = $this->tareaModel->obtenerTodas();
        require_once __DIR__ . '/../views/tareas/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->tareaModel->titulo = $_POST['titulo'];
            $this->tareaModel->descripcion = $_POST['descripcion'];
            $this->tareaModel->fecha_vencimiento = $_POST['fecha_vencimiento'];
            $this->tareaModel->estado = $_POST['estado'];
            $this->tareaModel->prioridad = $_POST['prioridad'];

            if ($this->tareaModel->crear()) {
                $_SESSION['success'] = 'Tarea creada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al crear la tarea';
            }
            header('Location: ' . BASE_URL . '/tareas');
            exit;
        }
    }

    public function editar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $tarea = $this->tareaModel->obtenerPorId($id);
            if ($tarea) {
                require_once __DIR__ . '/../views/tareas/editar.php';
            } else {
                $_SESSION['error'] = 'Tarea no encontrada';
                header('Location: ' . BASE_URL . '/tareas');
                exit;
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->tareaModel->id = $id;
            $this->tareaModel->titulo = $_POST['titulo'];
            $this->tareaModel->descripcion = $_POST['descripcion'];
            $this->tareaModel->fecha_vencimiento = $_POST['fecha_vencimiento'];
            $this->tareaModel->estado = $_POST['estado'];
            $this->tareaModel->prioridad = $_POST['prioridad'];

            if ($this->tareaModel->actualizar()) {
                $_SESSION['success'] = 'Tarea actualizada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar la tarea';
            }
            header('Location: ' . BASE_URL . '/tareas');
            exit;
        }
    }

    public function eliminar($id) {
        if ($this->tareaModel->eliminar($id)) {
            $_SESSION['success'] = 'Tarea eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la tarea';
        }
        header('Location: ' . BASE_URL . '/tareas');
        exit;
    }

    public function generarPDF() {
        // Asegurarse de que no haya salida antes de generar el PDF
        ob_clean();
        
        // Obtener todas las tareas
        $tareas = $this->tareaModel->obtenerTodas();

        // Crear nuevo documento PDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configurar el documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($_SESSION['usuario_nombre']);
        $pdf->SetTitle('Reporte de Tareas');

        // Eliminar cabecera y pie de página predeterminados
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Establecer márgenes
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        // Agregar una página
        $pdf->AddPage();

        // Título del reporte
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Tareas', 0, 1, 'C');
        $pdf->Ln(5);

        // Información del usuario y fecha
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Usuario: ' . $_SESSION['usuario_nombre'], 0, 1, 'L');
        $pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
        $pdf->Ln(5);

        // Encabezados de la tabla
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(50, 7, 'Título', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Fecha Creación', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Fecha Vencimiento', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Estado', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Prioridad', 1, 1, 'C', true);

        // Contenido de la tabla
        $pdf->SetFont('helvetica', '', 9);
        foreach ($tareas as $tarea) {
            // Convertir el estado y prioridad a un formato más legible
            $estado = ucfirst(str_replace('_', ' ', $tarea['estado']));
            $prioridad = ucfirst($tarea['prioridad']);

            $pdf->Cell(50, 6, $this->acortarTexto($tarea['titulo'], 25), 1, 0, 'L');
            $pdf->Cell(40, 6, $tarea['fecha_creacion_formato'], 1, 0, 'C');
            $pdf->Cell(40, 6, date('d/m/Y', strtotime($tarea['fecha_vencimiento'])), 1, 0, 'C');
            $pdf->Cell(25, 6, $estado, 1, 0, 'C');
            $pdf->Cell(25, 6, $prioridad, 1, 1, 'C');
        }

        // Limpiar cualquier salida pendiente
        ob_end_clean();

        // Generar el PDF
        $pdf->Output('reporte_tareas.pdf', 'D');
        exit;
    }

    private function acortarTexto($texto, $longitud) {
        if (strlen($texto) > $longitud) {
            return substr($texto, 0, $longitud) . '...';
        }
        return $texto;
    }
}
