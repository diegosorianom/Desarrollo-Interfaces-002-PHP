<?php
    require_once './controladores/Controlador.php';
    class C_Usuarios extends Controlador {
        public function __construct() {
            parent::__construct(); // Ejecutar constructor del padre
        }

        public function getVistaFiltros($datos=array()){
            // echo 'Hola de nuevo';
            require_once './vistas/Vista.php';
            // Vista::render('./vistas/Usuarios/V_Usuarios_Filtros.php');
            Vista::render('./vistas/Usuarios/V_Usuarios_Filtros.php');
        }
    }
?>