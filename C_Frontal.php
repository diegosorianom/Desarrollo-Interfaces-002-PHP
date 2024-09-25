<!-- Controlador frontal -->
<?php
    $getPost = array_merge($_GET, $_POST, $_FILES);

    // Existe la variable del controlador??
    if(isset($getPost['controlador']) && $getPost['controlador'] != '') {
        // Recibido controlador
        $controlador = 'C_'.$getPost['controlador'];
        if ( file_exists('./controladores/'.$controlador.'.php')) {
            // Existe el controlador
            $metodo = $getPost['metodo'];
            require_once './controladores/'.$controlador.'.php';
            $objControlador = new $controlador();
            if (method_exists($objControlador, $metodo)) {
                $objControlador -> $metodo($getPost);
            } else {
                echo 'Error CF-03'; // No existe el metodo
            }
        } else {
            echo 'Error CF-02'; // No existe el fichero del controlador
        }
    } else {
        // Identificar los errores con un código 
        echo 'Error CF-01'; // No he recibido controlador
    }

    // echo 'hola';
    // require_once './controladores/C_Usuarios.php';
    // $objControlador = new C_Usuarios(); // Creamos un objeto de la clase usuarios
    // $objControlador->getPrueba();
?>

<!-- Se pueden ver los resultados en F12 // Network // Documento // Payload-Response -->