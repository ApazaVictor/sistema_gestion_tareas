<?php
class Informe {
    private $conn;

    // Propiedades
    public $id;
    public $usuario_id;
    public $ruta_informe;
    public $fecha_generacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar nuevo informe
    public function registrar() {
        $query = "INSERT INTO informes (usuario_id, ruta_informe) 
                 VALUES (:usuario_id, :ruta_informe)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':ruta_informe', $this->ruta_informe);

        return $stmt->execute();
    }

    // Obtener informes de un usuario
    public function obtenerInformesPorUsuario($usuario_id) {
        $query = "SELECT * FROM informes 
                 WHERE usuario_id = :usuario_id 
                 ORDER BY fecha_generacion DESC";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un informe específico
    public function obtenerPorId($id, $usuario_id) {
        $query = "SELECT * FROM informes 
                 WHERE id = :id AND usuario_id = :usuario_id";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar informe
    public function eliminar($id, $usuario_id) {
        // Primero obtenemos la ruta del archivo para eliminarlo
        $informe = $this->obtenerPorId($id, $usuario_id);
        if ($informe && file_exists($informe['ruta_informe'])) {
            unlink($informe['ruta_informe']); // Eliminar el archivo físico
        }

        // Luego eliminamos el registro de la base de datos
        $query = "DELETE FROM informes 
                 WHERE id = :id AND usuario_id = :usuario_id";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);

        return $stmt->execute();
    }
}
