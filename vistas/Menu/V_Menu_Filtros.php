<?php
// Asegurar que $roles siempre está definido
$roles = isset($datos['roles']) ? $datos['roles'] : [];
$usuarios = isset($datos['usuarios']) ? $datos['usuarios'] : [];
?>

<h2>Mantenimiento de menús</h2>
<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscarMenu" name="formularioBuscarMenu">
        <div class="row">
            <!-- Dropdown de Roles -->
            <div class="form-group col-md-6 col-sm-12">
                <label for="frol">Selecciona un Rol:</label>
                <select id="frol" name="frol" class="form-control" onchange="habilitarBotonesRol()">
                    <option value="">Todos</option>
                    <?php
                    if (!empty($roles) && is_array($roles)) {
                        foreach ($roles as $rol) {
                            echo '<option value="' . htmlspecialchars($rol['id']) . '">' . htmlspecialchars($rol['nombre']) . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay roles disponibles</option>';
                    }
                    ?>
                </select>
            </div>
            <!-- Dropdown de Usuarios -->
            <div class="form-group col-md-6 col-sm-12">
                <label for="fusuario">Selecciona un Usuario:</label>
                <select id="fusuario" name="fusuario" class="form-control">
                    <option value="">Todos</option>
                    <?php
                    if (!empty($usuarios) && is_array($usuarios)) {
                        foreach ($usuarios as $usuario) {
                            echo '<option value="' . htmlspecialchars($usuario['id_Usuario']) . '">' . htmlspecialchars($usuario['nombre']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" onclick="buscarRol()">Buscar</button>
                <button type="button" class="btn btn-success" onclick="obtenerVista_EditarCrear('Roles', 'getVistaNuevoEditar', 'capaEditarCrear', '')">+ Crear Nuevo Rol</button>
                <button type="button" id="btnEditarRol" class="btn btn-warning" onclick="editarRolSeleccionado()" disabled>Editar Rol</button>
                <button type="button" id="btnEliminarRol" class="btn btn-danger" onclick="eliminarRolSeleccionado()" disabled>Eliminar Rol</button>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid" id="capaResultadoBusqueda"></div>
<div class="container-fluid" id="capaEditarCrear"></div>

