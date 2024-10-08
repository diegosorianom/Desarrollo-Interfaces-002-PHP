<?php
    //$usuarios = $datos['usuarios'];
    $usuarios = array();
    extract($datos);
    
    foreach($usuarios as $posicion => $fila) {
        echo '<br>';
        echo $fila['nombre'];
        echo '<br>';
    }
?>