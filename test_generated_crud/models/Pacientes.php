<?php
require_once 'config/Database.php';

class Pacientes {
    public $id;
    public $nombre;
    public $dni;
    public $fecha_nacimiento;
    public $direccion;
    public $telefono;

    private $conn;
    private $table_name = "pacientes";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear registro
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET nombre=:nombre, dni=:dni, fecha_nacimiento=:fecha_nacimiento, direccion=:direccion, telefono=:telefono";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);

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
            $this->dni = $row['dni'];
            $this->fecha_nacimiento = $row['fecha_nacimiento'];
            $this->direccion = $row['direccion'];
            $this->telefono = $row['telefono'];
        }
    }

    // Actualizar registro
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET nombre = :nombre, dni = :dni, fecha_nacimiento = :fecha_nacimiento, direccion = :direccion, telefono = :telefono
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':dni', $this->dni);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':telefono', $this->telefono);

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