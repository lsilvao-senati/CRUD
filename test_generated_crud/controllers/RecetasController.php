<?php
require_once 'models/Recetas.php';

class RecetasController {

    // Mostrar lista
    public function index() {
        $recetas = new Recetas();
        $stmt = $recetas->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/recetas/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $recetas_model = new Recetas();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['citas'])) {
            if (method_exists(${recetas_model}, 'get_all_citas')) {
                $stmt = ${recetas_model}->get_all_citas();
                $view_data['citas_options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $processed_fk_tables_for_view_data['citas'] = true; // Mark as processed
            }
        }

        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/recetas/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $recetas = new Recetas();

                        $recetas->cita_id = $_POST['cita_id'] ?? null;
            $recetas->medicamento = $_POST['medicamento'] ?? null;
            $recetas->dosis = $_POST['dosis'] ?? null;
            $recetas->frecuencia = $_POST['frecuencia'] ?? null;
            $recetas->duracion = $_POST['duracion'] ?? null;

            if($recetas->create()) {
                header("Location: index.php?controller=recetas&action=index&msg=created");
            } else {
                header("Location: index.php?controller=recetas&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $recetas = new Recetas(); // Model instance for the main table
        $recetas->id = $_GET['id'] ?? 0;
        $recetas->readOne();

        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)

        if (!isset($processed_fk_tables_for_view_data['citas'])) {
            if (method_exists(${recetas}, 'get_all_citas')) {
                $stmt = ${recetas}->get_all_citas();
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

        include 'views/recetas/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $recetas = new Recetas();

                        $recetas->id = $_POST['id'] ?? null;
            $recetas->cita_id = $_POST['cita_id'] ?? null;
            $recetas->medicamento = $_POST['medicamento'] ?? null;
            $recetas->dosis = $_POST['dosis'] ?? null;
            $recetas->frecuencia = $_POST['frecuencia'] ?? null;
            $recetas->duracion = $_POST['duracion'] ?? null;

            if($recetas->update()) {
                header("Location: index.php?controller=recetas&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=recetas&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $recetas = new Recetas();
        $recetas->id = $_POST['id'] ?? 0;

        if($recetas->delete()) {
            return true;
        }
        return false;
    }
}
?>