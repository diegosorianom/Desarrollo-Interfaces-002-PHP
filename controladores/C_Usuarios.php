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
            $usuarios = $this->modelo->buscarUsuarios($filtros);
        Vista::render('vistas/Usuarios/V_Usuarios_Listado.php', array('usuarios' => $usuarios));
        }

        public function guardarUsuario($datos = array()) {
            $respuesta['correcto'] = 'S';
            $respuesta['msj'] = 'Creado correctamente';
        
            if (!empty($datos['id_Usuario'])) {
                // Editing existing user
                $usuarioExistente = $this->modelo->buscarUsuarios(['id_Usuario' => $datos['id_Usuario']]);
                if (empty($usuarioExistente)) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['msj'] = 'El usuario a editar no existe';
                } else {
                    // Check if email is being used by another user
                    if (!empty($datos['mail']) && $this->modelo->isEmailInUse($datos['mail'], $datos['id_Usuario'])) {
                        $respuesta['correcto'] = 'N';
                        $respuesta['msj'] = 'El Email ya está registrado para otro usuario';
                    } else {
                        // Proceed to update the user
                        $id = $this->modelo->insertarUsuario($datos);
                        if ($id > 0) {
                            $respuesta['msj'] = 'Editado correctamente';
                        } else {
                            $respuesta['correcto'] = 'N';
                            $respuesta['msj'] = 'Se ha producido un error al editar';
                        }
                    }
                }
            } else {
                // Creating a new user
                if ($this->modelo->isEmailInUse($datos['mail'])) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['msj'] = 'El Email ya está registrado';
                } else {
                    // Proceed to create the user
                    $id = $this->modelo->insertarUsuario($datos);
                    if ($id > 0) {
                        $respuesta['msj'] = 'Creado correctamente';
                    } else {
                        $respuesta['correcto'] = 'N';
                        $respuesta['msj'] = 'Se ha producido un error al crear';
                    }
                }
            }
        
            echo json_encode($respuesta);
        }
        
        public function cambiarEstado($datos) {
            $id_Usuario = $datos['id_Usuario'];
            $nuevoEstado = $this->modelo->cambiarEstado($id_Usuario);
            
            if ($nuevoEstado !== false) {
                echo json_encode([
                    "success" => true,
                    "nuevoEstado" => $nuevoEstado
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "error" => "Error CF-03"
                ]);
            }
        }
        
        
    }
?>