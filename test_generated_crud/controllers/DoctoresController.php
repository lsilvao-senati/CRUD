<?php
require_once 'models/Doctores.php';

class DoctoresController {

    // Mostrar lista
    public function index() {
        $doctores = new Doctores();
        $stmt = $doctores->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/doctores/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $doctores_model = new Doctores();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['especialidades'])) {
            if (method_exists(${doctores_model}, 'get_all_especialidades')) {
                $stmt = ${doctores_model}->get_all_especialidades();
                $view_data['especialidades_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['especialidades'] = true; // Mark as processed
            }
        }

        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/doctores/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $doctores = new Doctores();

                        $doctores->nombre = $_POST['nombre'] ?? null;
            $doctores->numero_colegiatura = $_POST['numero_colegiatura'] ?? null;
            $doctores->especialidad_id = $_POST['especialidad_id'] ?? null;

            if($doctores->create()) {
                header("Location: index.php?controller=doctores&action=index&msg=created");
            } else {
                header("Location: index.php?controller=doctores&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $doctores = new Doctores(); // Model instance for the main table
        $doctores->id = $_GET['id'] ?? 0;
        $doctores->readOne();

        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['especialidades'])) {
            if (method_exists(${doctores}, 'get_all_especialidades')) {
                $stmt = ${doctores}->get_all_especialidades();
                $view_data['especialidades_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['especialidades'] = true; // Mark as processed
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

        include 'views/doctores/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $doctores = new Doctores();

                        $doctores->id = $_POST['id'] ?? null;
            $doctores->nombre = $_POST['nombre'] ?? null;
            $doctores->numero_colegiatura = $_POST['numero_colegiatura'] ?? null;
            $doctores->especialidad_id = $_POST['especialidad_id'] ?? null;

            if($doctores->update()) {
                header("Location: index.php?controller=doctores&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=doctores&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $doctores = new Doctores();
        $doctores->id = $_POST['id'] ?? 0;

        if($doctores->delete()) {
            return true;
        }
        return false;
    }
}
?>