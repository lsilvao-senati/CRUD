<?php
require_once 'config/Database.php';

class Tratamientos {
    public $id;
    public $cita_id;
    public $nombre;
    public $duracion;
    public $observaciones;

    private $conn;
    private $table_name = "tratamientos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear registro
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET cita_id=:cita_id, nombre=:nombre, duracion=:duracion, observaciones=:observaciones";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':cita_id', $this->cita_id);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':duracion', $this->duracion);
        $stmt->bindParam(':observaciones', $this->observaciones);

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
            $this->cita_id = $row['cita_id'];
            $this->nombre = $row['nombre'];
            $this->duracion = $row['duracion'];
            $this->observaciones = $row['observaciones'];
        }
    }

    // Actualizar registro
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET cita_id = :cita_id, nombre = :nombre, duracion = :duracion, observaciones = :observaciones
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':cita_id', $this->cita_id);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':duracion', $this->duracion);
        $stmt->bindParam(':observaciones', $this->observaciones);

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

    // Obtener todos los registros de citas para dropdowns/selects
    public function get_all_citas() {
        $query = "SELECT id, nombre FROM citas ORDER BY nombre ASC"; // Asume 'nombre' para ordenamiento
        // Intenta con 'name' si 'nombre' no es común, o hacerlo configurable
        // $query = "SELECT id, name FROM citas ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


}
?>