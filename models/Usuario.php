<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($nombre, $password) {
        $query = "SELECT id, nombre, email, password FROM " . $this->table_name . " WHERE nombre = :nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($password, $row['password'])) {
            unset($row['password']); // No devolver el password
            return $row;
        }
        
        return false;
    }

    public function crear($nombre, $email, $password) {
        $query = "INSERT INTO " . $this->table_name . " 
                (nombre, email, password, created_at, updated_at) 
                VALUES 
                (:nombre, :email, :password, NOW(), NOW())";
                
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $nombre = htmlspecialchars(strip_tags($nombre));
        $email = htmlspecialchars(strip_tags($email));
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Vincular valores
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $query = "SELECT id, nombre, email, created_at, updated_at 
                 FROM " . $this->table_name . " 
                 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $nombre, $email, $password = null) {
        if ($password) {
            $query = "UPDATE " . $this->table_name . " 
                     SET nombre = :nombre, 
                         email = :email, 
                         password = :password,
                         updated_at = NOW() 
                     WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                     SET nombre = :nombre, 
                         email = :email,
                         updated_at = NOW() 
                     WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $nombre = htmlspecialchars(strip_tags($nombre));
        $email = htmlspecialchars(strip_tags($email));

        // Vincular valores
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        
        if ($password) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $password);
        }

        return $stmt->execute();
    }
}