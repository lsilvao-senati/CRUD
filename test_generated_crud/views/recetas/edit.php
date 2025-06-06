<?php
$title = "Editar Recetas";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Editar Recetas</h4>
            </div>
            <div class="card-body">
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Error al actualizar el registro. Por favor, verifique los datos.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=recetas&action=update">

                <input type="hidden" name="id" value="<?= $recetas->id ?>">

                <div class="mb-3">
                    <label for="cita_id" class="form-label">Cita Id</label>
                    <select class="form-select" id="cita_id" name="cita_id" >
                        <option value="">-- Seleccione Cita Id --</option>
                        <?php
                        /* TODO: Populate options from database for table citas.
                           The value for this field is <?= $recetas->cita_id ?>.
                           You would typically loop through results from a query to the 'citas' table
                           and if a row's ID matches <?= $recetas->cita_id ?>, you add 'selected' to its <option>.

                           Example:
                           // Assuming $fk_options is an array of [value => text] for the dropdown
                           // and $current_fk_value holds the current foreign key value for this field.
                           // $pk_col_name would be the primary key column name of the foreign_key_to_table (e.g. 'id')
                           // $display_col_name would be the column name to display from the foreign_key_to_table (e.g. 'nombre')

                           // foreach ($fk_options as $option_row) { // Assuming $fk_options is fetched in controller
                           //    $selected_attr = ($current_fk_value == $option_row[$pk_col_name]) ? 'selected' : '';
                           //    echo "<option value='" . $option_row[$pk_col_name] . "' {$selected_attr}>" . $option_row[$display_col_name] . "</option>";
                           // }
                        */
                        ?>
                        <?php if (isset($recetas->cita_id)): ?>
                            <option value="<?= htmlspecialchars($recetas->cita_id) ?>" selected >
                                <?= htmlspecialchars($recetas->cita_id) ?> (Current Value - Needs Options Population)
                            </option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="medicamento" class="form-label">Medicamento</label>
                    <input type="text" class="form-control" id="medicamento" name="medicamento"
                           value="<?= $recetas->medicamento ?>" >
                </div>

                <div class="mb-3">
                    <label for="dosis" class="form-label">Dosis</label>
                    <input type="text" class="form-control" id="dosis" name="dosis"
                           value="<?= $recetas->dosis ?>" >
                </div>

                <div class="mb-3">
                    <label for="frecuencia" class="form-label">Frecuencia</label>
                    <input type="text" class="form-control" id="frecuencia" name="frecuencia"
                           value="<?= $recetas->frecuencia ?>" >
                </div>

                <div class="mb-3">
                    <label for="duracion" class="form-label">Duracion</label>
                    <input type="text" class="form-control" id="duracion" name="duracion"
                           value="<?= $recetas->duracion ?>" >
                </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=recetas&action=index" class="btn btn-secondary">
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
?>