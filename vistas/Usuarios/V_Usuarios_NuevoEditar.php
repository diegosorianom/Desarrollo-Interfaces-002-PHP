<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscar" name="formularioBuscar" method="POST" action="ruta_a_tu_script.php">
        <div>
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="apellido1" class="form-label">Apellido 1</label>
            <input type="text" class="form-control" id="apellido_1" name="apellido1" required>
        </div>
        <div>
            <label for="apellido2" class="form-label">Apellido 2</label>
            <input type="text" class="form-control" id="apellido_2" name="apellido2">
        </div>
        <div>
            <label for="email" class="form-label">Correo electronico</label>
            <input type="email" class="form-control" id="mail" name="email" aria-describedby="emailHelp" required>
        </div>
        <div>
            <label for="login" class="form-label">Login</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div>
            <label for="movil" class="form-label">Movil</label>
            <input type="number" class="form-control" id="movil" name="login" required>
        </div>
        <div>
            <label for="movil" class="form-label">Contraseña</label>
            <input type="number" class="form-control" id="pass" name="login" required>
        </div>
        <div>
            <select class="form-select" aria-label="Default select example">
              <option value="H">Hombre</option>
              <option value="M">Mujer</option>
              <option value="O">Otro</option>
              <option value="N">No Contesta</option>
            </select>
        </div> 
        <button type="submit" onclick="guardarUsuario()" class="btn btn-success">Guardar</button>
        <button type="submit" onclick="cancelarEdicion()" class="btn btn-danger">Cancelar</button>
    </form>
</div>
