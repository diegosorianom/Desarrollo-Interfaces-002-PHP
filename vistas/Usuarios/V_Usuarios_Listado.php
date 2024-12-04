<?php
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
                    <th>Correo electrónico</th>
                    <th>Login</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                </tr>
            </thead>
            <tbody>';
    foreach($usuarios as $posicion => $fila) {        
        $estilo='';
        if ($fila['activo']=='N') { 
            $activo='Inactivo';
            $rowClass = 'table-danger';  // Cambia el texto a rojo
        } else {
            $activo='Activo';
            $rowClass = '';  // Sin clase especial para filas activas
        }

        $html.='<tr class="'.$rowClass.'">
                    <td><Button class="btn btn-primary" onclick="obtenerVista_EditarCrear(\'Usuarios\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \''. $fila['id_Usuario'].'\')">Editar</Button></td>
                    <td nowrap style="'.$estilo.'">'.$fila['apellido_1'].' '.$fila['apellido_2'].', '.$fila['nombre'].'</td>
                    <td>'. $fila['mail'].'</td>
                    <td>'. $fila['login'].'</td>
                    <td id="estado-' . $fila['id_Usuario'] . '">'. $activo.'</td>
                    <td><Button class="btn btn-primary" onclick="toggleEstado(\'' . $fila['id_Usuario'] . '\')">Editar estado</Button></td>
                </tr>';
    }
    $html.='</tbody>
        </table>
        </div>';

    echo $html;
?>
