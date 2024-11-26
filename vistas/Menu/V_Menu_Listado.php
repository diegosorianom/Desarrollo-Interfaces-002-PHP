<?php
//$usuarios=$datos['usuarios'];
$opcionesMenu=array();
extract($datos);

$html='';
$html.='<div class="table-responsive">
        <table class="table table-sm table-striped">';
$html.='<thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Direccion a la que apunta</th>
                <th>Orden</th>
                <th>Nivel</th>
                <th>ID padre</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>';
    foreach($opcionesMenu as $posicion=>$fila){
    $html.='<tr>
                <td>'.$fila['id'].'</td>
                <td>'.$fila['label'].'</td>
                <td>'.$fila['url'].'</td>
                <td>'.$fila['position'].'</td>
                <td>'.$fila['level'].'</td>
                <td>'.$fila['parent_id'].'</td>
                <td><Button class="btn btn-primary" onclick"obtenerVista_EditarCrear(\'Menu\', \'getVistaNuevoEditar\', \'capaEditarCrear\', \''. $fila['id'].'\')">Editar</Button></td>
                ';
}

$html.='</tbody>
</table></div>';

echo $html;
?>