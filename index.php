<?php
session_start();

// Si no hay roles en la sesión, asignamos el rol 19 (Invitado)
if (!isset($_SESSION['roles']) || empty($_SESSION['roles'])) {
    $_SESSION['roles'] = [['id_rol' => 19, 'nombre' => 'Invitado']];
}

// Si no hay permisos en la sesión, inicializar un array vacío
if (!isset($_SESSION['permisos'])) {
    $_SESSION['permisos'] = [];
}

// Si no hay usuario en la sesión, ponerlo como "Invitado"
if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = "Invitado";
}

// Cargar el controlador de roles para aplicar la lógica
require_once 'controladores/C_Roles.php';
$controladorRoles = new C_Roles();
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
                                echo $_SESSION['login']; // Muestra solo el usuario sin el rol
                                echo '<div class="mt-2">';
                                echo '<a href="logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>';
                                echo '</div>';
                            } else {
                                echo "Invitado"; // Sin texto de rol
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

<script>
    var usuario = <?php echo json_encode($_SESSION['usuario'] ?? ''); ?>;
    var idUsuario = <?php echo json_encode($_SESSION['id_Usuario'] ?? ''); ?>;
    var roles = <?php echo json_encode($_SESSION['roles'] ?? []); ?>;
    var permisos = <?php echo json_encode($_SESSION['permisos'] ?? []); ?>;

    console.log("🔹 Usuario:", usuario);
    console.log("🔹 ID Usuario:", idUsuario);
    console.log("🔹 Roles del Usuario:", roles);
    roles.forEach(role => console.log(`- Rol ID: ${role.id_rol}, Nombre: ${role.nombre}`));
    console.log("🔹 Permisos del Usuario:", permisos);
</script>



