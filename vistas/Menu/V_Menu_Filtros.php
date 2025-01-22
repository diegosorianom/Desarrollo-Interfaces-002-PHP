<h2>Mantenimiento de menús</h2>
<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscarMenu" name="formularioBuscarMenu">
        <div class="row">
            <!-- Dropdown de roles -->
            <div class="form-group col-md-6 col-sm-12">
                <label for="frol">Seleccione un rol:</label>
                <select id="frol" name="frol" class="form-control">
                    <option value="">Todos los roles</option>
                    <?php
                    require_once 'controladores/C_Roles.php';
                    $controladorRoles = new C_Roles();
                    $roles = $controladorRoles->getRolesDropdown();

                    foreach ($roles as $rol) {
                        echo "<option value='{$rol['id']}'>{$rol['nombre_rol']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="fusuario">Seleccione un usuario:</label>
                <select id="fusuario" name="fusuario" class="form-control">
                    <option value="">Todos los usuarios</option>
                    <?php
                    require_once 'controladores/C_Usuarios.php';
                    $controladorUsuarios = new C_Usuarios();
                    $usuarios = $controladorUsuarios->getUsuariosDropdown();

                    foreach ($usuarios as $usuario) {
                        echo "<option value='{$usuario['id_Usuario']}'>{$usuario['nombre']} {$usuario['apellido_1']} {$usuario['apellido_2']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" onclick="buscarConsola('Menu', 'getVistaListadoMenu', 'formularioBuscarMenu', 'capaResultadoBusqueda')">Buscar</button>
                <!-- <button type="button" class="btn btn-primary" onclick="buscar('Permisos', 'getVistaListado', 'formularioBuscarMenu', 'capaResultadoBusqueda')">Buscar</button> -->
                <!-- Botón para mostrar la selección del dropdown en la consola -->
                <!-- <button type="button" class="btn btn-secondary" onclick="mostrarSeleccion()">Mostrar Selección</button> -->
                <!-- <button type="button" class="btn btn-secondary" onclick="mostrarSeleccionUsuario()">Mostrar Usuario Seleccionado</button> -->
            </div>
        </div>
    </form>
</div>
<div class="container-fluid" id="capaResultadoBusqueda"></div>
<div class="container-fluid" id="capaEditarCrear"></div>