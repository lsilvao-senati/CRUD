<?php
require_once 'config/Database.php';

class Citas {
    public $id;
    public $paciente_id;
    public $doctor_id;
    public $fecha;
    public $hora;
    public $motivo;

    private $conn;
    private $table_name = "citas";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear registro
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET paciente_id=:paciente_id, doctor_id=:doctor_id, fecha=:fecha, hora=:hora, motivo=:motivo";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':paciente_id', $this->paciente_id);
        $stmt->bindParam(':doctor_id', $this->doctor_id);
        $stmt->bindParam(':fecha', $this->fecha);
        $stmt->bindParam(':hora', $this->hora);
        $stmt->bindParam(':motivo', $this->motivo);

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
            $this->paciente_id = $row['paciente_id'];
            $this->doctor_id = $row['doctor_id'];
            $this->fecha = $row['fecha'];
            $this->hora = $row['hora'];
            $this->motivo = $row['motivo'];
        }
    }

    // Actualizar registro
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET paciente_id = :paciente_id, doctor_id = :doctor_id, fecha = :fecha, hora = :hora, motivo = :motivo
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':paciente_id', $this->paciente_id);
        $stmt->bindParam(':doctor_id', $this->doctor_id);
        $stmt->bindParam(':fecha', $this->fecha);
        $stmt->bindParam(':hora', $this->hora);
        $stmt->bindParam(':motivo', $this->motivo);

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

    // Obtener todos los registros de pacientes para dropdowns/selects
    public function get_all_pacientes() {
        $query = "SELECT id, nombre FROM pacientes ORDER BY nombre ASC"; // Asume 'nombre' para ordenamiento
        // Intenta con 'name' si 'nombre' no es común, o hacerlo configurable
        // $query = "SELECT id, name FROM pacientes ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    // Obtener todos los registros de doctores para dropdowns/selects
    public function get_all_doctores() {
        $query = "SELECT id, nombre FROM doctores ORDER BY nombre ASC"; // Asume 'nombre' para ordenamiento
        // Intenta con 'name' si 'nombre' no es común, o hacerlo configurable
        // $query = "SELECT id, name FROM doctores ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


}
?>