<?php
$permisos = array();
extract($datos);

// Inicio del contenedor
$html = '<div class="listado-permisos">';

// Recorrido de los permisos para generar las líneas
foreach ($permisos as $permiso) {
    $html .= '[' . htmlspecialchars($permiso['codigo_permiso']) . '] - [' . htmlspecialchars($permiso['permiso']) . ']<br>';
}

// Cierre del contenedor
$html .= '</div>';

// Añadimos una línea para mostrar toda la información de los permisos
$html .= '<pre>' . print_r($permisos, true) . '</pre>';

echo $html;
?>
