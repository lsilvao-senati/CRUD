<?php
require_once 'config/Database.php';

class Doctores {
    public $id;
    public $nombre;
    public $numero_colegiatura;
    public $especialidad_id;

    private $conn;
    private $table_name = "doctores";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear registro
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET nombre=:nombre, numero_colegiatura=:numero_colegiatura, especialidad_id=:especialidad_id";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':numero_colegiatura', $this->numero_colegiatura);
        $stmt->bindParam(':especialidad_id', $this->especialidad_id);

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
            $this->numero_colegiatura = $row['numero_colegiatura'];
            $this->especialidad_id = $row['especialidad_id'];
        }
    }

    // Actualizar registro
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre = :nombre, numero_colegiatura = :numero_colegiatura, especialidad_id = :especialidad_id
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':numero_colegiatura', $this->numero_colegiatura);
        $stmt->bindParam(':especialidad_id', $this->especialidad_id);

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

    // Obtener todos los registros de especialidades para dropdowns/selects
    public function get_all_especialidades() {
        $query = "SELECT id, nombre FROM especialidades ORDER BY nombre ASC"; // Asume 'nombre' para ordenamiento
        // Intenta con 'name' si 'nombre' no es común, o hacerlo configurable
        // $query = "SELECT id, name FROM especialidades ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


}
?>