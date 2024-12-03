<?php 
// echo json_encode($datos);

$id_Usuario = '';
$nombre = '';
$apellido_1 = '';
$apellido_2 = '';
$sexo = 'H';
$fecha_Alta = date('Y-m-d');
$mail = '';
$movil = '';
$login = '';
$pass = '';
$activo = 'S';

if (isset($datos['usuario'])) {
    extract($datos['usuario']);
    $editar = 'Editar';
} else {
    $editar = 'Nuevo';
}

$cHombre = $sexo == 'H' ? ' selected ' : '';
$cMujer = $sexo == 'M' ? ' selected ' : '';
$cOtro = $sexo == 'O' ? ' selected ' : '';
$cNoContesta = $sexo == 'N' ? ' selected ' : '';
?>

<div class="container" id="capaEditarCrear" style="max-width: 400px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
    <form id="formularioNuevoEditar" name="formularioNuevoEditar">
        <?php if ($editar == 'Editar'): ?>
            <div class="form-group" style="display: none;">
                <label for="id_Usuario">Id</label>
                <input type="text" id="id_Usuario" name="id_Usuario" class="form-control" value="<?php echo $id_Usuario; ?>">
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido_1" class="form-label">Apellido 1</label>
            <input type="text" class="form-control" id="apellido_1" name="apellido_1" value="<?php echo $apellido_1; ?>" required>
        </div>

        <div class="mb-3">
            <label for="apellido_2" class="form-label">Apellido 2</label>
            <input type="text" class="form-control" id="apellido_2" name="apellido_2" value="<?php echo $apellido_2; ?>">
        </div>

        <div class="mb-3">
            <label for="mail" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="mail" name="mail" value="<?php echo $mail; ?>" required>
        </div>

        <div class="mb-3">
            <label for="movil" class="form-label">Móvil</label>
            <input type="tel" class="form-control" id="movil" name="movil" value="<?php echo $movil; ?>" required>
        </div>

        <div class="mb-3">
            <label for="login" class="form-label">Login</label>
            <input type="text" class="form-control" id="login" name="login" value="<?php echo $login; ?>" required>
        </div>

        <div class="mb-3">
            <label for="pass" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="pass" name="pass" value="<?php echo $pass; ?>" required>
        </div>

        <div class="mb-3">
            <label for="sexo" class="form-label">Sexo</label>
            <select class="form-select" id="sexo" name="sexo">
                <option value="H" <?php echo $cHombre; ?>>Hombre</option>
                <option value="M" <?php echo $cMujer; ?>>Mujer</option>
                <option value="O" <?php echo $cOtro; ?>>Otro</option>
                <option value="N" <?php echo $cNoContesta; ?>>No Contesta</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="activo" class="form-label">Estado</label>
            <select id="activo" name="activo" class="form-select">
                <option value="S" <?php if ($activo == 'S') echo ' selected '; ?>>Activo</option>
                <option value="N" <?php if ($activo == 'N') echo ' selected '; ?>>Inactivo</option>
            </select>
        </div>

        <div class="row py-1">
            <div class="col-12">
                <button type="button" class="btn btn-success" onclick="guardarUsuario()"><?php echo $editar == 'Editar' ? 'Guardar' : 'Crear'; ?></button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('capaEditarCrear').innerHTML = '';">Cancelar</button>
                <span id="msjError" style="color:blue;"></span>
            </div>
        </div>
    </form>
</div>
