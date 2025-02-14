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
        
        // Función para loguear a un usuario
        public function validarUsuario($datos=array()){
            $id_Usuario=$this->modelo->login($datos);
            return $id_Usuario;
        }

        // Funcion para pintar la vista de filtros de usuarios
        public function getVistaFiltros($datos=array()){
            Vista::render('./vistas/Usuarios/V_Usuarios_Filtros.php');
        }
        
        // Funcion para pintar el formulario de crear / editar usuarios
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
        
        // Funcion para pintar la vista de listado
        public function getVistaListadoUsuarios($filtros=array()) {
            $usuarios = $this->modelo->buscarUsuarios($filtros);
        Vista::render('vistas/Usuarios/V_Usuarios_Listado.php', array('usuarios' => $usuarios));
        }

        // Funcion para guardar un usuario (crear o editar)
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
        
        // Funcion para cambiar el estado de un usuario (activo / no activo)
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
        
        // Fin del controlador de usuarios
    }
?>