<h2>Mantenimiento de menús</h2>
<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscarMenu" name="formularioBuscarMenu">
        <!-- <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="fnombre">Nombre del menú:</label>
                <input type="text" id="fnombre" name="fnombre" class="form-control" placeholder="Texto a buscar" value="" /> 
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="factivoMenu">Estado:</label>
                <select id="factivoMenu" name="factivoMenu" class="form-control">
                    <option value="" selected>Todos</option>     
                    <option value="S">Activos</option>     
                    <option value="N">No activos</option>     
                </select>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" onclick="buscar('Menu', 'getVistaListadoMenu', 'formularioBuscarMenu', 'capaResultadoBusqueda')">Buscar</button>
                <button type="button" class="btn btn-primary" onclick="buscar('Permisos', 'getVistaListado', 'formularioBuscarMenu', 'capaResultadoBusqueda')">Buscar</button>
                <!-- <button type="button" class="btn btn-secondary" onclick="obtenerVista_EditarCrear('Menu', 'getVistaNuevoEditar', 'capaEditarCrear', '')">Nuevo</button> -->
            </div>
        </div>
    </form>
</div>
<div class="container-fluid" id="capaResultadoBusqueda"></div>
<div class="container-fluid" id="capaEditarCrear"></div>
