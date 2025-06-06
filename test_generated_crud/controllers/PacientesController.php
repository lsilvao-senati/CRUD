<?php
require_once 'models/Pacientes.php';

class PacientesController {

    // Mostrar lista
    public function index() {
        $pacientes = new Pacientes();
        $stmt = $pacientes->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/pacientes/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $pacientes_model = new Pacientes();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)


        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/pacientes/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $pacientes = new Pacientes();

                        $pacientes->nombre = $_POST['nombre'] ?? null;
            $pacientes->dni = $_POST['dni'] ?? null;
            $pacientes->fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $pacientes->direccion = $_POST['direccion'] ?? null;
            $pacientes->telefono = $_POST['telefono'] ?? null;

            if($pacientes->create()) {
                header("Location: index.php?controller=pacientes&action=index&msg=created");
            } else {
                header("Location: index.php?controller=pacientes&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $pacientes = new Pacientes(); // Model instance for the main table
        $pacientes->id = $_GET['id'] ?? 0;
        $pacientes->readOne();

        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)


        // Hacer $view_data disponible para la vista
        foreach ($view_data as $key => $value) {
            $$key = $value;
                }
            }
        }
        // Hacer $view_data disponible para la vista
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/pacientes/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $pacientes = new Pacientes();

                        $pacientes->id = $_POST['id'] ?? null;
            $pacientes->nombre = $_POST['nombre'] ?? null;
            $pacientes->dni = $_POST['dni'] ?? null;
            $pacientes->fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $pacientes->direccion = $_POST['direccion'] ?? null;
            $pacientes->telefono = $_POST['telefono'] ?? null;

            if($pacientes->update()) {
                header("Location: index.php?controller=pacientes&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=pacientes&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $pacientes = new Pacientes();
        $pacientes->id = $_POST['id'] ?? 0;

        if($pacientes->delete()) {
            return true;
        }
        return false;
    }
}
?>