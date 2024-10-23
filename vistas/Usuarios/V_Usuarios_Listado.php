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
                    <th>Editar</th>
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
                    <td><Button class="btn btn-primary" onclick="obtenerVista_EditarCrear(\'Usuarios\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \''. $fila['id_Usuario'].'\')">Editar</Button></td>
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