<?php 
session_start();
require_once 'modelos/M_Usuarios.php';

$usuario = '';
$pass = '';
$msj='';
extract($_POST);


    if ($usuario === '' || $pass === '') {
        // $msj = 'Debes completar los campos.';
    } else {
        //Verificar usuario y pass contra la BD
        require_once 'controladores/C_Usuarios.php';
        $objCont = new C_Usuarios;
        $id_Usuario=$objCont->validarUsuario(array('usuario'=>$usuario, 'pass'=>$pass));

        if($id_Usuario!=''){
            //saltar a esta página (no puede haber pintado nada antes)
            header('Location: index.php');

        }else{
            unset($_SESSION['login']);
            unset($_SESSION['id_Usuario']);
            $msj='Credenciales incorrectas';
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="librerias/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <script src="librerias/bootstrap-5.3.3/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="container-fluid" id="capaContenido">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-4">
                <div id="cajaConTexto" class="border p-4 rounded">
                    <h3 class="text-center">Iniciar Sesión</h3>
                    <form id="formularioLogin" method="post" action="login.php">
                        <div class="form-group p-2">
                            <label for="usuario">Username</label>
                            <input name="usuario" type="text" class="form-control" id="usuario" placeholder="Ingrese su nombre de usuario" value="<?php echo htmlspecialchars($usuario); ?>" required>
                        </div>
                        <div class="form-group p-2">
                            <label for="password">Contraseña</label>
                            <input name="pass" type="password" class="form-control" id="password" placeholder="Ingrese su contraseña" required>
                        </div>
                        <button type="submit" id="aceptar" class="btn btn-primary btn-block m-2">Iniciar Sesión</button>
                    </form>
                    <div class="text-center mt-3 p-2 d-flex flex-column align-items-center">
                        <span id="msj" class="msj text-danger"><?php echo htmlspecialchars($msj); ?></span>
                        <a href="#">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="app.js" async></script>
</body>
</html>