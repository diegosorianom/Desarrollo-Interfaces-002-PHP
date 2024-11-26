<?php
echo json_encode($datos);

$id = '';
$label = '';
$url = '';
$parent_id = '';
$level = '';
$is_active = '';
$action = '';

if (isset($datos['menu'])) {
    extract($datos['menu']);
    $editar = 'Editar';
} else {
    $editar = 'Nuevo';
}

?>

<div class="container" id="capaEditarCrear" style="max-width: 400px; border: 1px solid #ccc; padding: 20px; border-radius: 5px;">