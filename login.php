<?php session_start();
    $usuario='';
    $pass='';
    extract($_POST);

    if($usuario=='' || $pass== '') {
        $msj = 'Debes completar los campos.';
    } else {
        if ($usuario == 'javier' && $pass='123') {
            $_SESSION['login'] = $usuario;
            header('Location: index.php'); //Saltar a esta página (no puede haber pintado nada antes)
        } else {
            $msj='La información no es correcta';
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <link rel="stylesheet" href="librerias/bootstrap-5.3.3/dist/css/bootstrap.min.css">
        <script src="librerias/bootstrap-5.3.3/dist/js/bootstrap.bundle.js"></script>
        <link rel="stylesheet" href="css/app.css">
        <!-- USAR DOCS DE LA 5.0!!! -->
    </head>
    <body>
        <div class="container-fluid" id="capaEncabezado">
            <div class="row">
                <!-- La suma de columnas tiene que dar 12 -->
                <div class="col-md-2 col-sm-9 d-none d-sm-block">
                    <img src="iconos/logo.png" style="height:5rem">
                </div>
                <div class="col-md-8 d-none d-md-block divTitulo">
                    Diego Soriano - DI 2024
                </div>
                <div class="col-md-2 col-sm-3 d-none d-md-block">
                    login
                </div>
            </div>
        </div>

        <div class="container-fluid" id="capaMenu">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Navbar</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                            </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown link
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" onclick="obtenerVista('Usuarios', 'getVistaFiltros', 'capaContenido')">Usuario</a></li>
                                <li><a class="dropdown-item" href="login.php">Login</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container-fluid" id="capaContenido">
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-md-4">
                    <div id="cajaConTexto" class="border p-4 rounded">
                        <h3 class="text-center">Iniciar Sesión</h3>
                        <form id="formularioLogin" method="post" action="login.php">
                            <div class="form-group p-2">
                                <label for="usuario">Username</label>
                                <input name="usuario" type="text" class="form-control" id="usuario" placeholder="Ingrese su nombre de usuario" value="<?php echo $usuario ?>" required>
                            </div>
                            <div class="form-group p-2">
                                <label for="password">Contraseña</label>
                                <input name="pass" type="password" class="form-control" id="password" placeholder="Ingrese su contraseña" value="<?php echo $pass ?>" required>
                            </div>
                            <span id="msj" class="msj"><?php echo $msj; ?></span>
                            <button type="submit" id="aceptar" class="btn btn-primary btn-block m-2">Iniciar Sesión</button>
                        </form>
                        <div class="text-center mt-3 p-2">
                            <a href="#">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Asincrona para que lo cargue cuando pueda sin obstaculizar el resto -->
        <script src="app.js" async></script>
    </body>
</html>