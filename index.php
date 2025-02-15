<?php
session_start();

// Forzar el rol 19 para todos los usuarios
if (!isset($_SESSION['roles']) || !is_array($_SESSION['roles'])) {
    $_SESSION['roles'] = [];
}

// Verificar si el rol 19 ya está en la sesión
$hasRole19 = false;
foreach ($_SESSION['roles'] as $role) {
    if ($role['id_rol'] == 19) {
        $hasRole19 = true;
        break;
    }
}

// Si el rol 19 no está en la sesión, añadirlo
if (!$hasRole19) {
    $_SESSION['roles'][] = ['id_rol' => 19, 'nombre' => 'Rol Desconocido'];
}

// Obtener los roles y permisos
require_once 'modelos/M_Roles.php';
$modeloRoles = new M_Roles();
$todosLosRoles = $modeloRoles->buscarRoles();

// Variables para almacenar el nombre del rol 19 y permisos del usuario
$nombreRol19 = 'Rol Desconocido';
$permisosUsuario = [];

// Obtener los nombres correctos de los roles asignados y sus permisos
foreach ($_SESSION['roles'] as &$rol) {
    foreach ($todosLosRoles as $rolBD) {
        if ($rol['id_rol'] == $rolBD['id']) {
            $rol['nombre'] = $rolBD['nombre']; // Actualiza el nombre del rol si existe en la BD
            if ($rol['id_rol'] == 19) {
                $nombreRol19 = $rolBD['nombre']; // Guarda el nombre del rol 19
            }
        }
    }

    // Obtener los permisos de cada rol y evitar duplicados
    $permisosRol = $modeloRoles->obtenerPermisosDeRol($rol['id_rol']);
    foreach ($permisosRol as $permiso) {
        $permisosUsuario[$permiso['id']] = $permiso; // Usa el ID del permiso como clave para evitar duplicados
    }
}

// Guardar todos los permisos en la sesión
$_SESSION['permisos'] = array_values($permisosUsuario);

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



