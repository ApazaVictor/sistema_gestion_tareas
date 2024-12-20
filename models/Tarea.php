<?php
class Tarea {
    private $conn;
    private $table_name = "tareas";

    public $id;
    public $titulo;
    public $descripcion;
    public $fecha_vencimiento;
    public $fecha_creacion;
    public $estado;
    public $prioridad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodas() {
        $query = "SELECT *, DATE_FORMAT(fecha_creacion, '%d/%m/%Y %H:%i') as fecha_creacion_formato 
                 FROM " . $this->table_name . " 
                 ORDER BY fecha_vencimiento ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTareasPorFecha($fecha) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE DATE(fecha_vencimiento) = :fecha 
                 ORDER BY fecha_vencimiento ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTareasPorMes($anio, $mes) {
        // Asegurarnos de que el mes tenga dos dígitos
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        
        // Construir las fechas de inicio y fin del mes
        $fechaInicio = sprintf('%04d-%02d-01', $anio, $mes);
        $fechaFin = date('Y-m-t', strtotime($fechaInicio));
        
        // Construir la consulta SQL usando BETWEEN para mayor precisión
        $query = "SELECT *, 
                 DATE(fecha_vencimiento) as fecha_dia,
                 DATE_FORMAT(fecha_creacion, '%d/%m/%Y %H:%i') as fecha_creacion_formato 
                 FROM " . $this->table_name . " 
                 WHERE fecha_vencimiento BETWEEN :fecha_inicio AND :fecha_fin 
                 ORDER BY fecha_vencimiento ASC, fecha_creacion DESC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":fecha_inicio", $fechaInicio);
            $stmt->bindParam(":fecha_fin", $fechaFin);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                (titulo, descripcion, fecha_vencimiento, fecha_creacion, estado, prioridad) 
                VALUES 
                (:titulo, :descripcion, :fecha_vencimiento, NOW(), :estado, :prioridad)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha_vencimiento = htmlspecialchars(strip_tags($this->fecha_vencimiento));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->prioridad = htmlspecialchars(strip_tags($this->prioridad));

        // Vincular valores
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha_vencimiento", $this->fecha_vencimiento);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":prioridad", $this->prioridad);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function obtenerPorId($id) {
        $query = "SELECT *, DATE_FORMAT(fecha_creacion, '%d/%m/%Y %H:%i') as fecha_creacion_formato 
                 FROM " . $this->table_name . " 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                SET titulo = :titulo,
                    descripcion = :descripcion,
                    fecha_vencimiento = :fecha_vencimiento,
                    estado = :estado,
                    prioridad = :prioridad
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fecha_vencimiento = htmlspecialchars(strip_tags($this->fecha_vencimiento));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->prioridad = htmlspecialchars(strip_tags($this->prioridad));

        // Vincular valores
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fecha_vencimiento", $this->fecha_vencimiento);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":prioridad", $this->prioridad);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
