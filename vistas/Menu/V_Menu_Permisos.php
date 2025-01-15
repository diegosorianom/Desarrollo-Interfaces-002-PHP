<?php
if (!empty($permisos)) {
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    foreach (array_keys($permisos[0]) as $column) {
        echo '<th>' . htmlspecialchars($column) . '</th>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($permisos as $permiso) {
        echo '<tr>';
        foreach ($permiso as $value) {
            echo '<td>' . htmlspecialchars($value) . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No se encontraron permisos.</p>';
}
?>
º