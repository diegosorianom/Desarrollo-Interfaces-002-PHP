<?php
    class Vista {
        // Para indicar que un parametro es opcional, le decimos que es una array, de forma que si no ponemos ningun dato es una array vacia.
        static public function render($rutaVista, $datos = array()) { 
            require($rutaVista); // include($rutaVista); (Coge el archivo y metelo ahí)
        }
    }
?>