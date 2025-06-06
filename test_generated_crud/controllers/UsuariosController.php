<?php
require_once 'models/Usuarios.php';

class UsuariosController {

    // Mostrar lista
    public function index() {
        $usuarios = new Usuarios();
        $stmt = $usuarios->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/usuarios/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $usuarios_model = new Usuarios();
        $view_data = [];
        $processed_fk_tables_for_view_data = []; // Helper to avoid redundant fetches if multiple FKs to same table

        // Cargar datos para selectores de claves foráneas
        // Iteramos sobre las columnas (definidas en Python al generar este controlador)


        // Hacer $view_data disponible para la vista (ya está en el scope)
        // y también extraer sus claves a variables individuales para conveniencia
        foreach ($view_data as $key => $value) {
            $$key = $value;
        }

        include 'views/usuarios/create.php';
    }

    // Procesar creación
    public function store() {
        if($_POST) {
            $usuarios = new Usuarios();

                        $usuarios->nombre_usuario = $_POST['nombre_usuario'] ?? null;
            $usuarios->password = $_POST['password'] ?? null;
            $usuarios->rol = $_POST['rol'] ?? null;

            if($usuarios->create()) {
                header("Location: index.php?controller=usuarios&action=index&msg=created");
            } else {
                header("Location: index.php?controller=usuarios&action=create&error=1");
            }
        }
    }

    // Mostrar formulario de edición
    public function edit() {
        $usuarios = new Usuarios(); // Model instance for the main table
        $usuarios->id = $_GET['id'] ?? 0;
        $usuarios->readOne();

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

        include 'views/usuarios/edit.php';
    }

    // Procesar actualización
    public function update() {
        if($_POST) {
            $usuarios = new Usuarios();

                        $usuarios->id = $_POST['id'] ?? null;
            $usuarios->nombre_usuario = $_POST['nombre_usuario'] ?? null;
            $usuarios->password = $_POST['password'] ?? null;
            $usuarios->rol = $_POST['rol'] ?? null;

            if($usuarios->update()) {
                header("Location: index.php?controller=usuarios&action=index&msg=updated");
            } else {
                header("Location: index.php?controller=usuarios&action=edit&id=" . $_POST['id'] . "&error=1");
            }
        }
    }

    // Eliminar registro
    public function delete() {
        $usuarios = new Usuarios();
        $usuarios->id = $_POST['id'] ?? 0;

        if($usuarios->delete()) {
            return true;
        }
        return false;
    }
}
?>