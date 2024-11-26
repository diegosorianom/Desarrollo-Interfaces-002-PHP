<?php
echo json_encode($datos);

$id = '';
$label = '';
$url = '';
$parent_id = '';
$position = '';
$level = '';
$is_active = '';
$action = '';

if (isset($datos['opcionMenu'])) {
    extract($datos['opcionMenu']);
    $editar = 'Editar';
} else {
    $editar = 'Nuevo';
}
?>

<div class="container" id="capaEditarCrear" style="max-width: 400px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
    <form id="formularioNuevoEditar" name="formularioNuevoEditar">
        <?php if ($editar == 'Editar'): ?>
            <div class="form-group" style="display: none;">
                <label for="id">Id</label>
                <input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>">
            </div>
        <?php endif; ?>

        <div class="mb-3"></div>
            <label for="label" class="form-label">Label</label>
            <input type="text" class="form-control" id="label" name="label" value="<?php echo $label; ?>" required>
        </div>

        <div class="mb-3"></div>
            <label for="url" class="form-label">Url</label>
            <input type="text" class="form-control" id="url" name="url" value="<?php echo $url; ?>" required>
        </div>

        <div class="mb-3"></div>
            <label for="parent_id" class="form-label">Padre</label>
            <input type="text" class="form-control" id="parent_id" name="parent_id" value="<?php echo $parent_id; ?>">
        </div>

        <div class="mb-3"></div>
            <label for="position" class="form-label">Posicion</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo $position; ?>">
        </div>

        <div class="mb-3"></div>
            <label for="level" class="form-label">Nivel</label>
            <input type="text" class="form-control" id="level" name="level" value="<?php echo $level; ?>">
        </div>

        <div class="mb-3"></div>
            <label for="is_active" class="form-label">Activo</label>
            <input type="text" class="form-control" id="is_active" name="is_active" value="<?php echo $is_active; ?>">        
        </div>

        <div class="mb-3">
            <label for="action" class="form-label">Acción</label>
            <input type="text" class="form-control" id="action" name="action" value="<?php echo $action; ?>">
        </div>

        <div class="row py-1">
            <div class="col-12">
                <button type="button" class="btn btn-success" onclick="guardarMenu()"><?php echo $editar == 'Editar' ? 'Guardar' : 'Crear'; ?></button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('capaEditarCrear').innerHTML = '';">Cancelar</button>
                <span id="msjError" style="color:blue;"></span>
            </div>
        </div>
    </form>
</div>