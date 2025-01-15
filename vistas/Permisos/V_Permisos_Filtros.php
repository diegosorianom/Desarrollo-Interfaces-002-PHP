<div class="container-fluid">
    <h2 class="text-start mb-4">Gestión de Permisos</h2>
    <form id="formularioBuscar" name="formularioBuscar">
        <div class="row mb-2">
            <div class="form-group col-md-6">
                <label for="permiso">Permiso:</label>
                <input type="text" id="permiso" name="permiso" class="form-control" placeholder="Nombre del permiso">
            </div>
            <div class="form-group col-md-6">
                <label for="codigo_permiso">Código:</label>
                <input type="text" id="codigo_permiso" name="codigo_permiso" class="form-control" placeholder="Código del permiso">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" onclick="buscar('Permisos', 'getVistaListado', 'formularioBuscar', 'capaResultadoBusqueda')">Buscar</button>
                <button type="button" class="btn btn-secondary" onclick="obtenerVista_EditarCrear('Permisos', 'getVistaNuevoEditar', 'capaEditarCrear', '')">Nuevo</button>
            </div>
        </div>
    </form>
</div>
<div id="capaEditarCrear"></div>
<div id="capaResultadoBusqueda"></div>
