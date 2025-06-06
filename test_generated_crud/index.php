<?php
session_start();

$action = $_GET['action'] ?? '';
if (!isset($_SESSION['usuario']) && $action !== 'login') {
    header("Location: login.php");
    exit;
}

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener parámetros de la URL
$controller_name = $_GET['controller'] ?? 'especialidades';
$action = $_GET['action'] ?? 'index';

// Enrutador simple
switch($controller_name) {
    case 'especialidades':
        require_once 'controllers/EspecialidadesController.php';
        $controller = new EspecialidadesController();
        break;

    case 'doctores':
        require_once 'controllers/DoctoresController.php';
        $controller = new DoctoresController();
        break;

    case 'pacientes':
        require_once 'controllers/PacientesController.php';
        $controller = new PacientesController();
        break;

    case 'citas':
        require_once 'controllers/CitasController.php';
        $controller = new CitasController();
        break;

    case 'tratamientos':
        require_once 'controllers/TratamientosController.php';
        $controller = new TratamientosController();
        break;

    case 'recetas':
        require_once 'controllers/RecetasController.php';
        $controller = new RecetasController();
        break;

    case 'usuarios':
        require_once 'controllers/UsuariosController.php';
        $controller = new UsuariosController();
        break;

    default:
        // Página de inicio por defecto
        include 'views/home.php';
        exit;
}

// Ejecutar la acción del controlador
if(method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "Acción no encontrada: $action";
}
?>