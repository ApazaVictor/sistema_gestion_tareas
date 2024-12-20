<?php
require_once __DIR__ . '/../models/Tarea.php';
require_once __DIR__ . '/../config/Database.php';

class CalendarioController {
    private $tarea;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->db = $database->getConnection();
        $this->tarea = new Tarea($this->db);
    }

    public function index() {
        // Validar que el usuario esté autenticado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/usuario/login');
            exit;
        }

        // Obtener el mes y año actual o los proporcionados por GET
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        // Validar mes y año
        if ($mes < 1 || $mes > 12) {
            $mes = (int)date('m');
        }
        if ($anio < 1970 || $anio > 2100) {
            $anio = (int)date('Y');
        }

        // Obtener las tareas del mes
        $tareas = $this->tarea->obtenerTareasPorMes($anio, $mes);
        
        // Preparar el array de tareas por día
        $tareasPorDia = [];
        foreach ($tareas as $tarea) {
            // Usar la fecha_dia que viene de la consulta SQL
            $dia = (int)date('j', strtotime($tarea['fecha_vencimiento']));
            if (!isset($tareasPorDia[$dia])) {
                $tareasPorDia[$dia] = [];
            }
            $tareasPorDia[$dia][] = $tarea;
        }

        // Obtener información del calendario
        $primerDia = mktime(0, 0, 0, $mes, 1, $anio);
        $numeroDias = date('t', $primerDia);
        $diaSemanaInicio = date('w', $primerDia);
        
        // Nombres de los meses en español
        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Cargar la vista
        require_once __DIR__ . '/../views/calendario/index.php';
    }

    public function obtenerEventos() {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $tareas = $this->tarea->obtenerTareasPorUsuario($usuario_id);
        
        $eventos = array();
        foreach ($tareas as $tarea) {
            $color = $this->getColorByStatus($tarea['estado']);
            $eventos[] = array(
                'id' => $tarea['id'],
                'title' => $tarea['nombre'] . "\n\n" . $tarea['descripcion'],
                'start' => $tarea['fecha_creacion'],
                'end' => $tarea['fecha_actualizacion'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'description' => $tarea['descripcion'],
                'status' => $tarea['estado'],
                'allDay' => true
            );
        }

        header('Content-Type: application/json');
        echo json_encode($eventos);
    }

    private function getColorByStatus($estado) {
        switch ($estado) {
            case 'pendiente':
                return '#dc3545'; // Rojo
            case 'en progreso':
                return '#0d6efd'; // Azul
            case 'completada':
                return '#28a745'; // Verde
            default:
                return '#6c757d'; // Gris
        }
    }

    public function editarTarea() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id']) || !isset($input['nombre']) || !isset($input['descripcion']) || !isset($input['estado'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }

        // Verificar que la tarea pertenece al usuario
        $tarea = $this->tarea->obtenerPorId($input['id']);
        if (!$tarea || $tarea['usuario_id'] != $usuario_id) {
            http_response_code(403);
            echo json_encode(['error' => 'No tiene permiso para editar esta tarea']);
            exit;
        }

        $data = [
            'id' => $input['id'],
            'nombre' => $input['nombre'],
            'descripcion' => $input['descripcion'],
            'estado' => $input['estado'],
            'usuario_id' => $usuario_id
        ];

        if ($this->tarea->actualizar($data)) {
            echo json_encode(['success' => true, 'message' => 'Tarea actualizada correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar la tarea']);
        }
    }

    public function eliminarTarea() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de tarea no proporcionado']);
            exit;
        }

        // Verificar que la tarea pertenece al usuario
        $tarea = $this->tarea->obtenerPorId($input['id']);
        if (!$tarea || $tarea['usuario_id'] != $usuario_id) {
            http_response_code(403);
            echo json_encode(['error' => 'No tiene permiso para eliminar esta tarea']);
            exit;
        }

        if ($this->tarea->eliminar($input['id'])) {
            echo json_encode(['success' => true, 'message' => 'Tarea eliminada correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar la tarea']);
        }
    }
}
