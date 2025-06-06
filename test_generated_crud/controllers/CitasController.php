<?php
require_once 'models/Citas.php';

class CitasController {

    // Mostrar lista
    public function index() {
        $citas = new Citas();
        $stmt = $citas->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/citas/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $citas_model = new Citas();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['pacientes'])) {
            if (method_exists(${citas_model}, 'get_all_pacientes')) {
                $stmt = ${citas_model}->get_all_pacientes();
                $view_data['pacientes_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['pacientes'] = true; // Mark as processed
            }
        }
        if (!isset($processed_fk_tables_for_view_data['doctores'])) {
            if (method_exists(${citas_model}, 'get_all_doctores')) {
                $stmt = ${citas_model}->get_all_doctores();
                $view_data['doctores_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['doctores'] = true; // Mark as processed
            }
        }

        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/citas/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $citas = new Citas();

                        $citas->paciente_id = $_POST['paciente_id'] ?? null;
            $citas->doctor_id = $_POST['doctor_id'] ?? null;
            $citas->fecha = $_POST['fecha'] ?? null;
            $citas->hora = $_POST['hora'] ?? null;
            $citas->motivo = $_POST['motivo'] ?? null;

            if($citas->create()) {
                header("Location: index.php?controller=citas&action=index&msg=created");
            } else {
                header("Location: index.php?controller=citas&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $citas = new Citas(); // Model instance for the main table
        $citas->id = $_GET['id'] ?? 0;
        $citas->readOne();

        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['pacientes'])) {
            if (method_exists(${citas}, 'get_all_pacientes')) {
                $stmt = ${citas}->get_all_pacientes();
                $view_data['pacientes_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['pacientes'] = true; // Mark as processed
            }
        }
        if (!isset($processed_fk_tables_for_view_data['doctores'])) {
            if (method_exists(${citas}, 'get_all_doctores')) {
                $stmt = ${citas}->get_all_doctores();
                $view_data['doctores_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['doctores'] = true; // Mark as processed
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

        include 'views/citas/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $citas = new Citas();

                        $citas->id = $_POST['id'] ?? null;
            $citas->paciente_id = $_POST['paciente_id'] ?? null;
            $citas->doctor_id = $_POST['doctor_id'] ?? null;
            $citas->fecha = $_POST['fecha'] ?? null;
            $citas->hora = $_POST['hora'] ?? null;
            $citas->motivo = $_POST['motivo'] ?? null;

            if($citas->update()) {
                header("Location: index.php?controller=citas&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=citas&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $citas = new Citas();
        $citas->id = $_POST['id'] ?? 0;

        if($citas->delete()) {
            return true;
        }
        return false;
    }
}
?>