<?php
require_once 'models/Tratamientos.php';

class TratamientosController {

    // Mostrar lista
    public function index() {
        $tratamientos = new Tratamientos();
        $stmt = $tratamientos->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/tratamientos/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $tratamientos_model = new Tratamientos();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['citas'])) {
            if (method_exists(${tratamientos_model}, 'get_all_citas')) {
                $stmt = ${tratamientos_model}->get_all_citas();
                $view_data['citas_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['citas'] = true; // Mark as processed
            }
        }

        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/tratamientos/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $tratamientos = new Tratamientos();

                        $tratamientos->cita_id = $_POST['cita_id'] ?? null;
            $tratamientos->nombre = $_POST['nombre'] ?? null;
            $tratamientos->duracion = $_POST['duracion'] ?? null;
            $tratamientos->observaciones = $_POST['observaciones'] ?? null;

            if($tratamientos->create()) {
                header("Location: index.php?controller=tratamientos&action=index&msg=created");
            } else {
                header("Location: index.php?controller=tratamientos&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $tratamientos = new Tratamientos(); // Model instance for the main table
        $tratamientos->id = $_GET['id'] ?? 0;
        $tratamientos->readOne();

        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['citas'])) {
            if (method_exists(${tratamientos}, 'get_all_citas')) {
                $stmt = ${tratamientos}->get_all_citas();
                $view_data['citas_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['citas'] = true; // Mark as processed
            }
        }

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

        include 'views/tratamientos/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $tratamientos = new Tratamientos();

                        $tratamientos->id = $_POST['id'] ?? null;
            $tratamientos->cita_id = $_POST['cita_id'] ?? null;
            $tratamientos->nombre = $_POST['nombre'] ?? null;
            $tratamientos->duracion = $_POST['duracion'] ?? null;
            $tratamientos->observaciones = $_POST['observaciones'] ?? null;

            if($tratamientos->update()) {
                header("Location: index.php?controller=tratamientos&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=tratamientos&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $tratamientos = new Tratamientos();
        $tratamientos->id = $_POST['id'] ?? 0;

        if($tratamientos->delete()) {
            return true;
        }
        return false;
    }
}
?>