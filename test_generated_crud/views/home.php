<?php
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
        <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Especialidades</h5>
                <p class="card-text">Gestionar registros de especialidades</p>
                <a href="index.php?controller=especialidades&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Doctores</h5>
                <p class="card-text">Gestionar registros de doctores</p>
                <a href="index.php?controller=doctores&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Pacientes</h5>
                <p class="card-text">Gestionar registros de pacientes</p>
                <a href="index.php?controller=pacientes&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Citas</h5>
                <p class="card-text">Gestionar registros de citas</p>
                <a href="index.php?controller=citas&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Tratamientos</h5>
                <p class="card-text">Gestionar registros de tratamientos</p>
                <a href="index.php?controller=tratamientos&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Recetas</h5>
                <p class="card-text">Gestionar registros de recetas</p>
                <a href="index.php?controller=recetas&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-table display-1 text-primary"></i>
                <h5 class="card-title mt-3">Usuarios</h5>
                <p class="card-text">Gestionar registros de usuarios</p>
                <a href="index.php?controller=usuarios&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include('layout.php');
?>