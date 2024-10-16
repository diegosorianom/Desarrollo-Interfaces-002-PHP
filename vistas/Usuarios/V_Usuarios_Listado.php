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
        
        $estilo='';
        if ($fila['activo']=='N') {
            $activo='Inactivo';
            $estilo='color:red;';
        } else {
            $activo='';
        }

        $html.='<tr>
                    <td nowrap style="'.$estilo.'">'.$fila['apellido_1'].' '.$fila['apellido_2'].', '.$fila['nombre'].'</td>
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