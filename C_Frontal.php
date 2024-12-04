<?php
    $getPost = array_merge($_GET, $_POST, $_FILES);

    if(isset($getPost['controlador']) && $getPost['controlador'] != '') {
        $controlador = 'C_'.$getPost['controlador'];
        if(file_exists('controladores/'.$controlador.'.php')) {
            $metodo = $getPost['metodo'];
            require_once './controladores/'.$controlador.'.php';
            $objControlador = new $controlador();
            if(method_exists($objControlador, $metodo)) {
                $objControlador -> $metodo($getPost);
            } else {
                echo 'Error CF-03';
            }
        } else {
            echo 'Error CF-02';
        }
    } else {
        echo 'Error Cf-01';
    }
?>