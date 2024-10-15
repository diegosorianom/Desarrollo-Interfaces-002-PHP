<?php
    //$usuarios = $datos['usuarios'];
    $usuarios = array();
    extract($datos);
    
    $html='';
    $html.='<div class="table-responsive">
                <table class="table table-sm table-striped">
    ';
    $html.='<thead>
                <tr>
                    <th>Apellidos, Nombre</th>
                    <th>Mail</th>
                    <th>Login</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';
    foreach($usuarios as $posicion => $fila) {
        // echo '<br>';
        // echo $fila['nombre'] . ' ' . $fila['apellido_1'] . ' ' . $fila['apellido_2'];
        // echo '<br>';
        
        if ($fila['activo']=='N') {
            $activo='Inactivo';
        } else {
            $activo='';
        }

        $html.='<tr>
                    <td nowrap>'.$fila['apellido_1'].' '.$fila['apellido_2'].', '.$fila['nombre'].'</td>
                    <td>'. $fila['mail'].'</td>
                    <td>'. $fila['login'].'</td>
                    <td>'. $activo.'</td>
                </tr>';
    }
    $html.='</tbody>
        </table>
        </div>';

    echo $html;
?>