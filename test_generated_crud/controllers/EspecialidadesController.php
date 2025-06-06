<?php
require_once 'models/Especialidades.php';

class EspecialidadesController {

    // Mostrar lista
    public function index() {
        $especialidades = new Especialidades();
        $stmt = $especialidades->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/especialidades/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $especialidades_model = new Especialidades();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)


        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/especialidades/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $especialidades = new Especialidades();

                        $especialidades->nombre = $_POST['nombre'] ?? null;

            if($especialidades->create()) {
                header("Location: index.php?controller=especialidades&action=index&msg=created");
            } else {
                header("Location: index.php?controller=especialidades&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $especialidades = new Especialidades(); // Model instance for the main table
        $especialidades->id = $_GET['id'] ?? 0;
        $especialidades->readOne();

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

        include 'views/especialidades/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $especialidades = new Especialidades();

                        $especialidades->id = $_POST['id'] ?? null;
            $especialidades->nombre = $_POST['nombre'] ?? null;

            if($especialidades->update()) {
                header("Location: index.php?controller=especialidades&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=especialidades&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $especialidades = new Especialidades();
        $especialidades->id = $_POST['id'] ?? 0;

        if($especialidades->delete()) {
            return true;
        }
        return false;
    }
}
?>