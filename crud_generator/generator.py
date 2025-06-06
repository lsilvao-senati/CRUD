import re
import os
from typing import Dict, List, Tuple

class CRUDGenerator:
    def __init__(self, sql_file_path: str, output_dir: str = "generated_crud"):
        self.sql_file_path = sql_file_path
        self.output_dir = output_dir
        self.tables = {}
        self.db_config = {
            'host': 'localhost',
            'dbname': 'tu_base_datos',
            'username': 'root',
            'password': ''
        }
    
    def parse_sql_file(self):
        """Analiza el archivo SQL y extrae información de las tablas"""
        # Intentar diferentes codificaciones
        encodings = ['utf-8', 'latin-1', 'cp1252', 'iso-8859-1']
        sql_content = ""
        
        for encoding in encodings:
            try:
                with open(self.sql_file_path, 'r', encoding=encoding) as file:
                    sql_content = file.read()
                print(f"✅ Archivo leído correctamente con codificación: {encoding}")
                break
            except UnicodeDecodeError:
                continue
        
        if not sql_content:
            raise Exception("No se pudo leer el archivo SQL con ninguna codificación compatible")
        
        # Limpiar comentarios y líneas vacías
        sql_content = re.sub(r'--[^\n]*\n|/\*.*?\*/', '', sql_content, flags=re.DOTALL | re.MULTILINE)
        sql_content = os.linesep.join([s for s in sql_content.splitlines() if s.strip()]) # Remove empty lines
        
        # Buscar todas las declaraciones CREATE TABLE (más flexible)
        table_pattern = r'CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"]?(\w+)[`"]?\s*\((.*?)\)(?:\s*ENGINE\s*=.*?)?(?:\s*DEFAULT\s+CHARSET\s*=.*?)?(?:\s*COLLATE\s*=.*?)?;'
        tables = re.findall(table_pattern, sql_content, re.DOTALL | re.IGNORECASE)
        
        if not tables:
            # Intentar patrón más simple si el anterior no funciona
            table_pattern = r'CREATE\s+TABLE\s+[`"]?(\w+)[`"]?\s*\((.*?)\);'
            tables = re.findall(table_pattern, sql_content, re.DOTALL | re.IGNORECASE)
        
        for table_name, columns_def in tables:
            self.tables[table_name] = self.parse_columns(columns_def)
            print(f"📋 Tabla encontrada: {table_name}")

    def parse_columns(self, columns_def: str) -> List[Dict]:
        """Extrae información de las columnas de una tabla"""
        columns = []
        lines = [line.strip().rstrip(',') for line in columns_def.split('\n') if line.strip()]
        for line in lines:
            line = line.strip().rstrip(',')
            if not line:
                continue
            # Ignorar constraints y keys
            if re.match(r'^\s*(?:PRIMARY\s+KEY|KEY|INDEX|CONSTRAINT|FOREIGN\s+KEY|UNIQUE)', line, re.IGNORECASE):
                continue
            # Extraer nombre y tipo de columna (más robusto)
            col_match = re.match(r'[`"]?(\w+)[`"]?\s+(\w+)(?:\((\d+)(?:,\d+)?\))?\s*(.*?)$', line, re.IGNORECASE)
            if col_match:
                col_name = col_match.group(1)
                sql_type = col_match.group(2)
                details = col_match.group(4)
                nullable = 'NOT NULL' not in details.upper()
                primary_key = 'PRIMARY KEY' in details.upper()
                auto_increment = 'AUTO_INCREMENT' in details.upper()
                
                columns.append({
                    'name': col_name,
                    'type': sql_type.lower(),
                    'nullable': nullable,
                    'primary_key': primary_key,
                    'auto_increment': auto_increment
                })
        return columns
    
    def get_php_type(self, sql_type: str) -> str:
        """Convierte tipos SQL a tipos PHP para validación"""
        type_mapping = {
            'int': 'int',
            'integer': 'int',
            'bigint': 'int',
            'smallint': 'int',
            'tinyint': 'int',
            'varchar': 'string',
            'char': 'string',
            'text': 'string',
            'longtext': 'string',
            'decimal': 'float',
            'float': 'float',
            'double': 'float',
            'date': 'string',
            'datetime': 'string',
            'timestamp': 'string',
            'boolean': 'bool',
            'bool': 'bool'
        }
        return type_mapping.get(sql_type, 'string')
    
    def get_input_type(self, sql_type: str) -> str:
        """Determina el tipo de input HTML basado en el tipo SQL"""
        if sql_type in ['int', 'integer', 'bigint', 'smallint', 'tinyint']:
            return 'number'
        elif sql_type in ['date']:
            return 'date'
        elif sql_type in ['datetime', 'timestamp']:
            return 'datetime-local'
        elif sql_type in ['text', 'longtext']:
            return 'textarea'
        elif sql_type in ['boolean', 'bool']:
            return 'checkbox'
        else:
            return 'text'
    
    def generate_config(self):
        """Genera el archivo de configuración de base de datos"""
        config_content = f"""<?php
class Database {{
    private $host = '{self.db_config['host']}';
    private $db_name = '{self.db_config['dbname']}';
    private $username = '{self.db_config['username']}';
    private $password = '{self.db_config['password']}';
    private $conn;

    public function getConnection() {{
        $this->conn = null;
        try {{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }} catch(PDOException $exception) {{
            echo "Error de conexión: " . $exception->getMessage();
        }}
        return $this->conn;
    }}
}}
?>"""
        return config_content
    
    def generate_model(self, table_name: str, columns: List[Dict]) -> str:
        """Genera el modelo para una tabla"""
        class_name = self.to_class_name(table_name)
        primary_key = next((col['name'] for col in columns if col.get('primary_key')), 'id')
        
        # Generar propiedades
        properties = []
        for col in columns:
            properties.append(f"    public ${col['name']};")
        
        # Generar métodos CRUD
        model_content = f"""<?php
require_once 'config/Database.php';

class {class_name} {{
{chr(10).join(properties)}
    
    private $conn;
    private $table_name = "{table_name}";
    
    public function __construct() {{
        $database = new Database();
        $this->conn = $database->getConnection();
    }}
    
    // Crear registro
    public function create() {{
        $query = "INSERT INTO " . $this->table_name . " 
                SET {', '.join([f"{col['name']}=:{col['name']}" for col in columns if not col.get('auto_increment')])}";
        
        $stmt = $this->conn->prepare($query);
        
        {chr(10).join([f"        $stmt->bindParam(':{col['name']}', $this->{col['name']});" for col in columns if not col.get('auto_increment')])}
        
        if($stmt->execute()) {{
            return true;
        }}
        return false;
    }}
    
    // Leer todos los registros
    public function readAll() {{
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY {primary_key} DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }}
    
    // Leer un registro
    public function readOne() {{
        $query = "SELECT * FROM " . $this->table_name . " WHERE {primary_key} = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->{primary_key});
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {{
            {chr(10).join([f"            $this->{col['name']} = $row['{col['name']}'];" for col in columns])}
        }}
    }}
    
    // Actualizar registro
    public function update() {{
        $query = "UPDATE " . $this->table_name . " 
                SET {', '.join([f"{col['name']} = :{col['name']}" for col in columns if not col.get('auto_increment')])}
                WHERE {primary_key} = :{primary_key}";
        
        $stmt = $this->conn->prepare($query);
        
        {chr(10).join([f"        $stmt->bindParam(':{col['name']}', $this->{col['name']});" for col in columns])}
        
        if($stmt->execute()) {{
            return true;
        }}
        return false;
    }}
    
    // Eliminar registro
    public function delete() {{
        $query = "DELETE FROM " . $this->table_name . " WHERE {primary_key} = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->{primary_key});
        
        if($stmt->execute()) {{
            return true;
        }}
        return false;
    }}
}}
?>"""
        return model_content
    
    def generate_controller(self, table_name: str, columns: List[Dict]) -> str:
        """Genera el controlador para una tabla"""
        class_name = self.to_class_name(table_name)
        primary_key = next((col['name'] for col in columns if col.get('primary_key')), 'id')
        
        controller_content = f"""<?php
require_once 'models/{class_name}.php';

class {class_name}Controller {{
    
    // Mostrar lista
    public function index() {{
        ${table_name} = new {class_name}();
        $stmt = ${table_name}->readAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include 'views/{table_name}/index.php';
    }}
    
    // Mostrar formulario de creación
    public function create() {{
        include 'views/{table_name}/create.php';
    }}
    
    // Procesar creación
    public function store() {{
        if($_POST) {{
            ${table_name} = new {class_name}();
            
            {chr(10).join([f"            ${table_name}->{col['name']} = $_POST['{col['name']}'] ?? null;" for col in columns if not col.get('auto_increment')])}
            
            if(${table_name}->create()) {{
                header("Location: index.php?controller={table_name}&action=index&msg=created");
            }} else {{
                header("Location: index.php?controller={table_name}&action=create&error=1");
            }}
        }}
    }}
    
    // Mostrar formulario de edición
    public function edit() {{
        ${table_name} = new {class_name}();
        ${table_name}->{primary_key} = $_GET['id'] ?? 0;
        ${table_name}->readOne();
        
        include 'views/{table_name}/edit.php';
    }}
    
    // Procesar actualización
    public function update() {{
        if($_POST) {{
            ${table_name} = new {class_name}();
            
            {chr(10).join([f"            ${table_name}->{col['name']} = $_POST['{col['name']}'] ?? null;" for col in columns])}
            
            if(${table_name}->update()) {{
                header("Location: index.php?controller={table_name}&action=index&msg=updated");
            }} else {{
                header("Location: index.php?controller={table_name}&action=edit&id=" . $_POST['{primary_key}'] . "&error=1");
            }}
        }}
    }}
    
    // Eliminar registro
    public function delete() {{
        ${table_name} = new {class_name}();
        ${table_name}->{primary_key} = $_POST['id'] ?? 0;
        
        if(${table_name}->delete()) {{
            return true;
        }}
        return false;
    }}
}}
?>"""
        return controller_content
    
    def generate_layout_file(self) -> str:
        """Genera el archivo de layout común con barra de navegación"""
        nav_links = []
        for tn in self.tables.keys():
            nav_links.append(f'<a class="nav-link" href="index.php?controller={tn}&action=index">{self.to_class_name(tn)}</a>')
        
        layout_content = f"""<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema CRUD' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema CRUD</a>
            <div class="navbar-nav">
                {chr(10).join(nav_links)}
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?= $content ?? '' ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>"""
        return layout_content
    
    def generate_view_index(self, table_name: str, columns: List[Dict]) -> str:
        """Genera la vista index para una tabla"""
        class_name = self.to_class_name(table_name)
        primary_key = next((col['name'] for col in columns if col.get('primary_key')), 'id')
        
        view_content = f"""<?php
$title = "Gestión de {class_name}";
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de {class_name}</h2>
    <a href="index.php?controller={table_name}&action=create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo {class_name}
    </a>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
            switch($_GET['msg']) {{
                case 'created': echo 'Registro creado exitosamente'; break;
                case 'updated': echo 'Registro actualizado exitosamente'; break;
                case 'deleted': echo 'Registro eliminado exitosamente'; break;
            }}
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Error al procesar la operación
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                {chr(10).join([f"                <th>{col['name'].replace('_', ' ').title()}</th>" for col in columns])}
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($data)): ?>
                <?php foreach($data as $row): ?>
                    <tr>
                        {chr(10).join([f"                        <td><?= htmlspecialchars($row['{col['name']}']) ?></td>" for col in columns])}
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller={table_name}&action=edit&id=<?= $row['{primary_key}'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="index.php?controller={table_name}&action=delete" 
                                      style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este registro?')">
                                    <input type="hidden" name="id" value="<?= $row['{primary_key}'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="{len(columns) + 1}" class="text-center">No hay registros disponibles</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include(__DIR__ . '/../layout.php');
?>"""
        return view_content
    
    def generate_view_create(self, table_name: str, columns: List[Dict]) -> str:
        """Genera la vista create para una tabla"""
        class_name = self.to_class_name(table_name)
        
        form_fields = []
        for col in columns:
            if col.get('auto_increment'):
                continue
            
            input_type = self.get_input_type(col['type'])
            required = '' if col['nullable'] else 'required'
            
            if input_type == 'textarea':
                form_fields.append(f"""
                <div class="mb-3">
                    <label for="{col['name']}" class="form-label">{col['name'].replace('_', ' ').title()}</label>
                    <textarea class="form-control" id="{col['name']}" name="{col['name']}" rows="3" {required}></textarea>
                </div>""")
            elif input_type == 'checkbox':
                form_fields.append(f"""
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="{col['name']}" name="{col['name']}" value="1">
                    <label class="form-check-label" for="{col['name']}">{col['name'].replace('_', ' ').title()}</label>
                </div>""")
            else:
                form_fields.append(f"""
                <div class="mb-3">
                    <label for="{col['name']}" class="form-label">{col['name'].replace('_', ' ').title()}</label>
                    <input type="{input_type}" class="form-control" id="{col['name']}" name="{col['name']}" {required}>
                </div>""")
        
        view_content = f"""<?php
$title = "Crear {class_name}";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Crear Nuevo {class_name}</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al crear el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller={table_name}&action=store">
                    {chr(10).join(form_fields)}
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller={table_name}&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include(__DIR__ . '/../layout.php');
?>"""
        return view_content
    
    def generate_view_edit(self, table_name: str, columns: List[Dict]) -> str:
        """Genera la vista edit para una tabla"""
        class_name = self.to_class_name(table_name)
        primary_key = next((col['name'] for col in columns if col.get('primary_key')), 'id')
        
        form_fields = []
        for col in columns:
            input_type = self.get_input_type(col['type'])
            required = '' if col['nullable'] else 'required'
            
            if col.get('auto_increment'):
                form_fields.append(f"""
                <input type="hidden" name="{col['name']}" value="<?= ${table_name}->{col['name']} ?>">""")
                continue
            
            if input_type == 'textarea':
                form_fields.append(f"""
                <div class="mb-3">
                    <label for="{col['name']}" class="form-label">{col['name'].replace('_', ' ').title()}</label>
                    <textarea class="form-control" id="{col['name']}" name="{col['name']}" rows="3" {required}><?= ${table_name}->{col['name']} ?></textarea>
                </div>""")
            elif input_type == 'checkbox':
                form_fields.append(f"""
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="{col['name']}" name="{col['name']}" value="1" 
                           <?= ${table_name}->{col['name']} ? 'checked' : '' ?>>
                    <label class="form-check-label" for="{col['name']}">{col['name'].replace('_', ' ').title()}</label>
                </div>""")
            else:
                form_fields.append(f"""
                <div class="mb-3">
                    <label for="{col['name']}" class="form-label">{col['name'].replace('_', ' ').title()}</label>
                    <input type="{input_type}" class="form-control" id="{col['name']}" name="{col['name']}" 
                           value="<?= ${table_name}->{col['name']} ?>" {required}>
                </div>""")
        
        view_content = f"""<?php
$title = "Editar {class_name}";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Editar {class_name}</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al actualizar el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller={table_name}&action=update">
                    {chr(10).join(form_fields)}
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller={table_name}&action=index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include(__DIR__ . '/../layout.php');
?>"""
        return view_content
    
    def generate_index_file(self) -> str:
        """Genera el archivo index.php principal"""
        controllers_cases = []
        for table_name in self.tables.keys():
            class_name = self.to_class_name(table_name)
            controllers_cases.append(f"""
    case '{table_name}':
        require_once 'controllers/{class_name}Controller.php';
        $controller = new {class_name}Controller();
        break;""")
        
        index_content = f"""<?php
session_start();

$action = $_GET['action'] ?? '';
if (!isset($_SESSION['usuario']) && $action !== 'login') {{
    header("Location: login.php");
    exit;
}}

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener parámetros de la URL
$controller_name = $_GET['controller'] ?? '{list(self.tables.keys())[0] if self.tables else 'home'}';
$action = $_GET['action'] ?? 'index';

// Enrutador simple
switch($controller_name) {{{chr(10).join(controllers_cases)}
    
    default:
        // Página de inicio por defecto
        include 'views/home.php';
        exit;
}}

// Ejecutar la acción del controlador
if(method_exists($controller, $action)) {{
    $controller->$action();
}} else {{
    echo "Acción no encontrada: $action";
}}
?>"""
        return index_content
    
    def generate_home_view(self) -> str:
        """Genera la vista de inicio"""
        home_content = f"""<?php
$title = "Sistema CRUD";
ob_start();
?>

<div class="row">
    <div class="col-12 text-center">
        <h1 class="display-4 text-primary">Sistema CRUD</h1>
        <p class="lead">Gestión completa de base de datos con interfaz web</p>
    </div>
</div>

<div class="row mt-5">
    {chr(10).join([f'''    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">{self.to_class_name(tn)}</h5>
                <p class="card-text">Gestionar registros de {tn}</p>
                <a href="index.php?controller={tn}&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>''' for tn in self.tables.keys()])}
</div>

<?php
$content = ob_get_clean();
include('layout.php');
?>"""
        return home_content

    def generate_login_view(self) -> str:
        """Genera la vista de login"""
        return """<?php
session_start();
require_once 'config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nombre_usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario LIMIT 1");
    $stmt->bindParam(':nombre_usuario', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password']) && $user['rol'] === 'administrador') {
        $_SESSION['usuario'] = $user['nombre_usuario'];
        $_SESSION['rol'] = $user['rol'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Acceso denegado. Verifique usuario, contraseña o permisos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center mb-3">Iniciar Sesión</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre de Usuario</label>
                    <input type="text" name="nombre_usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>"""

    def generate_logout(self) -> str:
        """Genera la página de logout"""
        return """<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
?>"""
    
    def to_class_name(self, table_name: str) -> str:
        """Convierte nombre de tabla a nombre de clase"""
        return ''.join(word.capitalize() for word in table_name.split('_'))
    
    def create_directory_structure(self):
        """Crea la estructura de directorios"""
        dirs = [
            self.output_dir,
            f"{self.output_dir}/config",
            f"{self.output_dir}/models",
            f"{self.output_dir}/views",
            f"{self.output_dir}/controllers"
        ]
        
        for table_name in self.tables.keys():
            dirs.append(f"{self.output_dir}/views/{table_name}")
        
        for dir_path in dirs:
            os.makedirs(dir_path, exist_ok=True)
    
    def generate_all_files(self):
        """Genera todos los archivos del sistema CRUD"""
        print("🚀 Iniciando generación del sistema CRUD...")
        
        # Crear estructura de directorios
        self.create_directory_structure()
        print("📁 Estructura de directorios creada")
        
        # Generar archivo de configuración
        with open(f"{self.output_dir}/config/Database.php", 'w', encoding='utf-8') as f:
            f.write(self.generate_config())
        print("⚙️ Archivo de configuración generado")
        
        # Generar archivo index principal
        with open(f"{self.output_dir}/index.php", 'w', encoding='utf-8') as f:
            f.write(self.generate_index_file())
        print("🏠 Archivo index.php generado")
        
        # Generar layout común
        with open(f"{self.output_dir}/views/layout.php", 'w', encoding='utf-8') as f:
            f.write(self.generate_layout_file())
        print("🎨 Layout común generado")
        
        # Generar login/logout
        with open(f"{self.output_dir}/login.php", 'w', encoding='utf-8') as f:
            f.write(self.generate_login_view())
        with open(f"{self.output_dir}/logout.php", 'w', encoding='utf-8') as f:
            f.write(self.generate_logout())

        # Generar vista de inicio
        with open(f"{self.output_dir}/views/home.php", 'w', encoding='utf-8') as f:
            f.write(self.generate_home_view())
        print("🏠 Vista de inicio generada")
        
        # Generar archivos para cada tabla
        for table_name, columns in self.tables.items():
            class_name = self.to_class_name(table_name)
            
            # Modelo
            with open(f"{self.output_dir}/models/{class_name}.php", 'w', encoding='utf-8') as f:
                f.write(self.generate_model(table_name, columns))
            
            # Controlador
            with open(f"{self.output_dir}/controllers/{class_name}Controller.php", 'w', encoding='utf-8') as f:
                f.write(self.generate_controller(table_name, columns))
            
            # Vistas
            with open(f"{self.output_dir}/views/{table_name}/index.php", 'w', encoding='utf-8') as f:
                f.write(self.generate_view_index(table_name, columns))
            
            with open(f"{self.output_dir}/views/{table_name}/create.php", 'w', encoding='utf-8') as f:
                f.write(self.generate_view_create(table_name, columns))
            
            with open(f"{self.output_dir}/views/{table_name}/edit.php", 'w', encoding='utf-8') as f:
                f.write(self.generate_view_edit(table_name, columns))
            
            print(f"✅ Archivos generados para tabla: {table_name}")
        
        # Generar archivo README
        self.generate_readme()
        print("📖 README generado")
        
        print(f"\n🎉 ¡Sistema CRUD generado exitosamente en: {self.output_dir}/")
        print("📋 Próximos pasos:")
        print("   1. Configura la base de datos en config/Database.php")
        print("   2. Copia los archivos a tu servidor web")
        print("   3. Importa tu archivo SQL a la base de datos")
        print("   4. Accede desde el navegador")
    
    def generate_readme(self):
        """Genera archivo README con instrucciones"""
        readme_content = f"""# Sistema CRUD Generado Automáticamente

## 📋 Descripción
Este sistema CRUD ha sido generado automáticamente con arquitectura MVC, conexión PDO y Bootstrap 5.

## 🚀 Instalación

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)

### Pasos de instalación

1. **Configurar Base de Datos**
   - Edita `config/Database.php`
   - Cambia los datos de conexión:
     ```php
     private $host = 'localhost';
     private $db_name = 'tu_base_datos';
     private $username = 'tu_usuario';
     private $password = 'tu_contraseña';
     ```

2. **Importar Base de Datos**
   - Importa tu archivo SQL original a MySQL
   - Asegúrate de que las tablas existan

3. **Configurar Servidor Web**
   - Copia todos los archivos a tu directorio web
   - Asegúrate de que PHP tenga permisos de lectura

4. **Acceder al Sistema**
   - Abre tu navegador
   - Ve a: `http://tu-servidor/ruta-del-proyecto/`

## 📁 Estructura del Proyecto

{self.output_dir}/
├── config/
│   └── Database.php          # Configuración de base de datos
├── controllers/              # Controladores MVC
│   {chr(10).join([f"│   ├── {self.to_class_name(tn)}Controller.php" for tn in self.tables.keys()])}
├── models/                   # Modelos de datos
│   {chr(10).join([f"│   ├── {self.to_class_name(tn)}.php" for tn in self.tables.keys()])}
├── views/                    # Vistas HTML
│   ├── home.php             # Página de inicio
│   ├── layout.php           # Layout común para todas las páginas
│   {chr(10).join([f"│   ├── {tn}/" for tn in self.tables.keys()])}
│   │   ├── index.php        # Lista de registros
│   │   ├── create.php       # Formulario de creación
│   │   └── edit.php         # Formulario de edición
├── index.php                # Punto de entrada principal
├── login.php                # Página de inicio de sesión
├── logout.php               # Página de cierre de sesión
└── README.md                # Este archivo


## 🔧 Funcionalidades

### Tablas Gestionadas
{chr(10).join([f"- **{self.to_class_name(tn)}** ({tn})" for tn in self.tables.keys()])}

### Operaciones CRUD
- ✅ **Create**: Crear nuevos registros
- ✅ **Read**: Listar y visualizar registros
- ✅ **Update**: Editar registros existentes
- ✅ **Delete**: Eliminar registros

### Características Técnicas
- 🏗️ **Arquitectura MVC**: Separación clara de responsabilidades
- 🔒 **PDO**: Conexiones seguras con prepared statements
- 🎨 **Bootstrap 5**: Interfaz moderna y responsiva
- 📱 **Responsive**: Compatible con dispositivos móviles
- ⚡ **Validación**: Validación automática de formularios
- 🔍 **Navegación**: Sistema de navegación intuitivo
- 🔄 **Layout Común**: Sistema de plantillas compartidas

## 🎨 Personalización

### Modificar Estilos
El sistema usa Bootstrap 5 via CDN. Para personalizar:
1. Descarga Bootstrap y modifica las variables SCSS
2. O agrega CSS personalizado en cada vista

### Agregar Validaciones
Modifica los controladores para agregar validaciones específicas:
```php
// En el método store() o update()
if(empty($_POST['campo_requerido'])) {{
    $error = "Campo requerido no puede estar vacío";
    // Manejar error
}}"""
        
        with open(f"{self.output_dir}/README.md", 'w', encoding='utf-8') as f:
            f.write(readme_content)