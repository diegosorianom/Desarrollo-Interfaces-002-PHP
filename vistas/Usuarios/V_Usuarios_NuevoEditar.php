<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioNuevoEditar" name="formulario-NuevoEditar">
        <div>
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="apellido_1" class="form-label">Apellido 1</label>
            <input type="text" class="form-control" id="apellido_1" name="apellido_1" required>
        </div>
        <div>
            <label for="apellido_2" class="form-label">Apellido 2</label>
            <input type="text" class="form-control" id="apellido_2" name="apellido_2">
        </div>
        <div>
            <label for="mail" class="form-label">Correo electronico</label>
            <input type="email" class="form-control" id="mail" name="mail" aria-describedby="emailHelp" required>
        </div>
        <div>
            <label for="login" class="form-label">Login</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div>
            <label for="movil" class="form-label">Movil</label>
            <input type="number" class="form-control" id="movil" name="movil" required>
        </div>
        <div>
            <label for="movil" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>
        <div>
            <select class="form-select" aria-label="Default select example">
              <option value="H">Hombre</option>
              <option value="M">Mujer</option>
              <option value="O">Otro</option>
              <option value="N">No Contesta</option>
            </select>
        </div> 
        <div class="row py-1">
            <div class = "form-group col-lg-12 col-sm12 col-sx-12">
            <button type="submit" onclick="guardarUsuario()" class="btn btn-success">Guardar</button>
            <button type="submit" onclick="document.getElementById('capaEditarCrear').innerHTML='';" class="btn btn-danger">Cancelar</button>
            <span id="msjError" name="msjError" style="color:blue;"></span>
            </div>
        </div>
    </form>
</div>
