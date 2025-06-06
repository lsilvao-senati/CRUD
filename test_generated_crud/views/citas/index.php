<?php
$title = "Gestión de Citas";
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Citas</h2>
    <a href="index.php?controller=citas&action=create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nuevo Citas
    </a>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
            switch($_GET['msg']) {
                case 'created': echo 'Registro creado exitosamente'; break;
                case 'updated': echo 'Registro actualizado exitosamente'; break;
                case 'deleted': echo 'Registro eliminado exitosamente'; break;
            }
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
                                <th>Id</th>
                <th>Paciente Id</th>
                <th>Doctor Id</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($data)): ?>
                <?php foreach($data as $row): ?>
                    <tr>
                                                <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['paciente_id']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_id']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['hora']) ?></td>
                        <td><?= htmlspecialchars($row['motivo']) ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="index.php?controller=citas&action=edit&id=<?= $row['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="index.php?controller=citas&action=delete"
                                      style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este registro?')">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
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
                    <td colspan="7" class="text-center">No hay registros disponibles</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include(__DIR__ . '/../layout.php');
?>