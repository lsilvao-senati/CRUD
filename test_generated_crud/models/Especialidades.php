<?php
require_once 'config/Database.php';

class Especialidades {
    public $id;
    public $nombre;

    private $conn;
    private $table_name = "especialidades";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear registro
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET nombre=:nombre";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':nombre', $this->nombre);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los registros
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un registro
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
                        $this->id = $row['id'];
            $this->nombre = $row['nombre'];
        }
    }

    // Actualizar registro
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre = :nombre
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nombre', $this->nombre);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar registro
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // --- Métodos para Foreign Keys ---


}
?>