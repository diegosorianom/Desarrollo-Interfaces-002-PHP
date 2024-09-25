<?php
    require_once 'controladores/Controlador.php';
    class C_Usuarios extends Controlador {
        public function __construct() {
            parent::__construct(); // Ejecutar constructor del padre
        }

        public function getPrueba($datos=array()){
            echo 'Hola de nuevo';
        }
    }
?>