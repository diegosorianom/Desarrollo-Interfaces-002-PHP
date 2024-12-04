<div class="container-fluid" id="capaFiltrosBusqueda">
    <h2 class="text-start mb-4">Mantenimiento de usuarios</h2>
    <form id="formularioBuscar" name="formularioBuscar">
        <div class="row mb-2">
            <div class="form-group col-md-6 col-sm-12">
                <label for="ftexto">Nombre/texto:</label>
                <input type="text" id="ftexto" name="ftexto" class="form-control" placeholder="Texto a buscar" value="" /> 
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="factivo">Estado:</label>
                <select id="factivo" name="factivo" class="form-control">
                    <option value="" selected>Todos</option>     
                    <option value="S">Activos</option>     
                    <option value="N">No activos</option>     
                </select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-lg-12 d-flex">
                <button type="button" class="btn btn-primary me-2" onclick="buscar('Usuarios', 'getVistaListadoUsuarios', 'formularioBuscar', 'capaResultadoBusqueda')">Buscar</button>
                <button type="button" class="btn btn-secondary" onclick="obtenerVista_EditarCrear ('Usuarios', 'getVistaNuevoEditar', 'capaEditarCrear', '')">Nuevo</button>
            </div>
        </div>
    </form>
</div>

<div class="container-fluid mt-4" id="capaEditarCrear"></div>
<div class="container-fluid mt-4" id="capaResultadoBusqueda"></div>

