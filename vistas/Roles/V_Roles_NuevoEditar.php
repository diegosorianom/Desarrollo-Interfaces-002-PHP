<?php
$id = '';
$nombre = '';
$descripcion = '';

if (isset($datos['rol'])) {
    extract($datos['rol']);
    $editar = 'Editar';
} else {
    $editar = 'Nuevo';
}
?>

<div class="container" id="capaEditarCrear" style="max-width: 400px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
    <form id="formularioNuevoEditar" name="formularioNuevoEditar">
        <?php if ($editar == 'Editar'): ?>
            <div class="form-group" style="display: none;">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>">
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Rol</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>
        
        <div class="row py-1">
            <div class="col-12">
                <button type="button" class="btn btn-success" onclick="guardarRol()"><?php echo $editar == 'Editar' ? 'Guardar' : 'Crear'; ?></button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('capaEditarCrear').innerHTML = '';">Cancelar</button>
                <span id="msjError" style="color:blue;"></span>
            </div>
        </div>
    </form>
</div>
