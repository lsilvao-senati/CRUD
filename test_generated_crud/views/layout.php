<!DOCTYPE html>
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
                <a class="nav-link" href="index.php?controller=especialidades&action=index">Especialidades</a>
<a class="nav-link" href="index.php?controller=doctores&action=index">Doctores</a>
<a class="nav-link" href="index.php?controller=pacientes&action=index">Pacientes</a>
<a class="nav-link" href="index.php?controller=citas&action=index">Citas</a>
<a class="nav-link" href="index.php?controller=tratamientos&action=index">Tratamientos</a>
<a class="nav-link" href="index.php?controller=recetas&action=index">Recetas</a>
<a class="nav-link" href="index.php?controller=usuarios&action=index">Usuarios</a>
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
</html>