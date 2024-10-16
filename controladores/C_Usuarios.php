<?php
    require_once 'controladores/Controlador.php';
    require_once 'modelos/M_Usuarios.php';
    require_once 'vistas/Vista.php';

    class C_Usuarios extends Controlador {
        private $modelo;

        public function __construct() {
            parent::__construct(); // Ejecutar constructor del padre
            $this -> modelo = new M_Usuarios();
        }

        public function getVistaFiltros($datos=array()){
            Vista::render('./vistas/Usuarios/V_Usuarios_Filtros.php');
        }
        
        public function getVistaNuevoEditar($datos=array()){
            Vista::render('./vistas/Usuarios/V_Usuarios_NuevoEditar.php');
        }
        
        public function getVistaListadoUsuarios($filtros=array()) {
            var_dump($filtros);
            $usuarios = $this -> modelo -> buscarUsuarios($filtros);
            Vista::render('./vistas/Usuarios/V_Usuarios_Listado.php', array('usuarios'=>$usuarios));
        }
    }
?>