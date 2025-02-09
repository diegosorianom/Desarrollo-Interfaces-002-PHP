<?php
$id_Rol = '';
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
                <label for="id_Rol">ID</label>
                <input type="text" id="id_Rol" name="id_Rol" class="form-control" value="<?php echo $id_Rol; ?>">
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Rol</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $descripcion; ?></textarea>
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
