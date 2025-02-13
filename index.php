<?php session_start(); 

// Si no hay sesión activa, asignar el rol de "visitante"
if (!isset($_SESSION['login'])) {
    $_SESSION['rol'] = "visitante"; 
} else {
    // Si el usuario está autenticado, el rol debe venir de la base de datos
    // (Este código es solo un ejemplo, asegúrate de extraer el rol correctamente)
    $_SESSION['rol'] = $_SESSION['rol'] ?? "usuario";
}
?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <link rel="stylesheet" href="librerias/bootstrap-5.3.3/dist/css/bootstrap.min.css">
        <script src="librerias/bootstrap-5.3.3/dist/js/bootstrap.bundle.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/app.css">
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
                    <div>
                        Bienvenido: 
                        <?php 
                            if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
                                echo $_SESSION['login'] . " (Rol: " . $_SESSION['rol'] . ")";
                                echo '<div class="mt-2">';
                                echo '<a href="logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>';
                                echo '</div>';
                            } else {
                                echo "Invitado (Rol: " . $_SESSION['rol'] . ")"; 
                                echo '<div class="mt-2">';
                                echo '<a href="Login.php" class="btn btn-primary btn-sm">Iniciar sesión</a>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'vistas/Menu/V_Menu.php'; ?>

        <div class="container-fluid" id="capaContenido">
            <div id="cajaConTexto"></div>
        </div>
        <!-- Asincrona para que lo cargue cuando pueda sin obstaculizar el resto -->
        <script src="app.js" async></script>
    </body>
</html>