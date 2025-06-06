# Sistema CRUD Generado Automáticamente

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

test_generated_crud/
├── config/
│   └── Database.php          # Configuración de base de datos
├── controllers/              # Controladores MVC
│   │   ├── EspecialidadesController.php
│   ├── DoctoresController.php
│   ├── PacientesController.php
│   ├── CitasController.php
│   ├── TratamientosController.php
│   ├── RecetasController.php
│   ├── UsuariosController.php
├── models/                   # Modelos de datos
│   │   ├── Especialidades.php
│   ├── Doctores.php
│   ├── Pacientes.php
│   ├── Citas.php
│   ├── Tratamientos.php
│   ├── Recetas.php
│   ├── Usuarios.php
├── views/                    # Vistas HTML
│   ├── home.php             # Página de inicio
│   ├── layout.php           # Layout común para todas las páginas
│   │   ├── especialidades/
│   ├── doctores/
│   ├── pacientes/
│   ├── citas/
│   ├── tratamientos/
│   ├── recetas/
│   ├── usuarios/
│   │   ├── index.php        # Lista de registros
│   │   ├── create.php       # Formulario de creación
│   │   └── edit.php         # Formulario de edición
├── index.php                # Punto de entrada principal
├── login.php                # Página de inicio de sesión
├── logout.php               # Página de cierre de sesión
└── README.md                # Este archivo


## 🔧 Funcionalidades

### Tablas Gestionadas
- **Especialidades** (especialidades)
- **Doctores** (doctores)
- **Pacientes** (pacientes)
- **Citas** (citas)
- **Tratamientos** (tratamientos)
- **Recetas** (recetas)
- **Usuarios** (usuarios)

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
if(empty($_POST['campo_requerido'])) {
    $error = "Campo requerido no puede estar vacío";
    // Manejar error
}