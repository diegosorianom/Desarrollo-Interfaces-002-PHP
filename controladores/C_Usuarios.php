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

        public function validarUsuario($datos=array()){
            $id_Usuario=$this->modelo->login($datos);
            return $id_Usuario;
        }

        public function getVistaFiltros($datos=array()){
            Vista::render('./vistas/Usuarios/V_Usuarios_Filtros.php');
        }
        
        public function getVistaNuevoEditar($datos=array()){
            if (!isset($datos['id']) || $datos['id']=='') {
                // Nuevo
                Vista::render('./vistas/Usuarios/V_Usuarios_NuevoEditar.php');
            } else {
                // editando
                $filtros['id_Usuario']=$datos['id'];
                $usuarios = $this -> modelo -> buscarUsuarios($filtros);
                Vista::render('./vistas/Usuarios/V_Usuarios_NuevoEditar.php', array('usuario'=>$usuarios[0]));
            }

        }
        
        public function getVistaListadoUsuarios($filtros=array()) {
            var_dump($filtros);
            $usuarios = $this -> modelo -> buscarUsuarios($filtros);
            Vista::render('./vistas/Usuarios/V_Usuarios_Listado.php', array('usuarios'=>$usuarios));
        }

        public function guardarUsuario($datos = array()) {
            $respuesta['correcto']='S';
            $respuesta['msj']='Creador correctamente.';
            
            $id=$this->modelo->insertarUsuario($datos);
            if($id>0) {
                // nada, ok
            } else {
                $respuesta['correcto']='N';
                $respuesta['msj']='Error al crear';
            }
            echo json_encode($respuesta);
        }

        public function cambiarEstado($datos = array()) {
            header('Content-Type: application/json'); // Agregar esto para asegurar que la salida es JSON
        
            if (isset($datos['id_Usuario'])) {
                $nuevoEstado = $this->modelo->cambiarEstado($datos['id_Usuario']);
                
                if ($nuevoEstado !== false) {
                    echo json_encode(['success' => true, 'nuevoEstado' => $nuevoEstado]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'ID de usuario no especificado']);
            }
        }
        
    }
?>