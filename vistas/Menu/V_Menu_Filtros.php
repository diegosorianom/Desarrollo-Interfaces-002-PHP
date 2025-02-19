<?php
session_start();

// Obtener los permisos del usuario desde la sesión
$permisosUsuario = $_SESSION['permisos'] ?? [];

// Verificar si el usuario tiene el permiso 21 (Modificar Dashboard)
$tienePermisoModificar = in_array(21, array_column($permisosUsuario, 'id'));

// Verificar si el usuario tiene el permiso 17 (Acceder Dashboard)
$tienePermisoAcceder = in_array(17, array_column($permisosUsuario, 'id'));

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
        <div class="row mt-3">
            <div class="col-lg-12">
                <!-- Botón de búsqueda -->
                <button type="button" class="btn btn-primary" onclick="buscarRol()">Buscar</button>
                <!-- Dropdown para acciones de Roles -->
                <div class="dropdown d-inline">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownAcciones"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        <?php echo (!$tienePermisoModificar) ? 'disabled' : ''; ?>>
                        Mantenimiento de roles
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownAcciones">
                        <li>
                            <button class="dropdown-item" type="button"
                                onclick="obtenerVista_EditarCrear('Roles', 'getVistaNuevoEditar', 'capaEditarCrear', '')"
                                <?php echo (!$tienePermisoModificar) ? 'disabled' : ''; ?>>
                                + Crear Nuevo Rol
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" id="btnEditarRol"
                                onclick="editarRolSeleccionado()" disabled>
                                Editar Rol
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" id="btnEliminarRol"
                                onclick="eliminarRolSeleccionado()" disabled>
                                Eliminar Rol
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button"
                                onclick="asignarRolAUsuario()"
                                <?php echo (!$tienePermisoModificar) ? 'disabled' : ''; ?>>
                                Asignar Rol a Usuario
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button"
                                onclick="desasignarRolAUsuario()"
                                <?php echo (!$tienePermisoModificar) ? 'disabled' : ''; ?>>
                                Quitar Rol
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid">
                    <div id="mensajeError" class="alert alert-danger d-none"></div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid" id="capaEditarCrear"></div>
<div class="container-fluid" id="capaResultadoBusqueda"></div>
