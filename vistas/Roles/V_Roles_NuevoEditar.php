<?php
    $id_rol = '';
    $nombre_rol = '';   

    if (isset($datos['rol'])) {
        extract($datos['rol']);
        $editar = 'Editar';
    } else {
        $editar = 'Nuevo';
    }
?>