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
                // Editar usuario existente
                $usuarioExistente = $this->modelo->buscarUsuarios(['id_Usuario' => $datos['id_Usuario']]);
                if (empty($usuarioExistente)) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['msj'] = 'El usuario a editar no existe';
                } else {
                    // Verificar si el nuevo login ya existe para otro usuario
                    $usuarioConMismoLogin = $this->modelo->buscarUsuarios(['login' => $datos['login']]);
                    if (!empty($usuarioConMismoLogin) && $usuarioConMismoLogin[0]['id_Usuario'] != $datos['id_Usuario']) {
                        $respuesta['correcto'] = 'N';
                        $respuesta['msj'] = 'El Nombre de Usuario (Login) ya existe para otro usuario';
                    }
                    // Verificar si el nuevo email ya existe para otro usuario (only if email is changed)
                    elseif (!empty($datos['mail']) && $datos['mail'] !== $usuarioExistente[0]['mail']) {
                        $usuarioConMismoEmail = $this->modelo->buscarUsuarios(['mail' => $datos['mail']]);
                        if (!empty($usuarioConMismoEmail) && $usuarioConMismoEmail[0]['id_Usuario'] != $datos['id_Usuario']) {
                            $respuesta['correcto'] = 'N';
                            $respuesta['msj'] = 'El Email ya está registrado para otro usuario';
                        }
                    }
                    // Verificar si el nuevo teléfono ya existe para otro usuario (only if phone is changed)
                    elseif (!empty($datos['movil']) && $datos['movil'] !== $usuarioExistente[0]['movil']) {
                        $usuarioConMismoTelefono = $this->modelo->buscarUsuarios(['movil' => $datos['movil']]);
                        if (!empty($usuarioConMismoTelefono) && $usuarioConMismoTelefono[0]['id_Usuario'] != $datos['id_Usuario']) {
                            $respuesta['correcto'] = 'N';
                            $respuesta['msj'] = 'El Teléfono ya está registrado para otro usuario';
                        }
                    } else {
                        // Si todo está bien, se actualiza el usuario
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
                // Crear nuevo usuario
                // Verificar si el nuevo login ya existe
                $usuarioExiste = $this->modelo->buscarUsuarios(['login' => $datos['login']]);
                if (!empty($usuarioExiste)) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['msj'] = 'El Nombre de Usuario (Login) ya existe';
                }
                // Verificar si el nuevo email ya existe
                elseif (!empty($datos['mail'])) {
                    $usuarioConMismoEmail = $this->modelo->buscarUsuarios(['mail' => $datos['mail']]);
                    if (!empty($usuarioConMismoEmail)) {
                        $respuesta['correcto'] = 'N';
                        $respuesta['msj'] = 'El Email ya está registrado';
                    }
                }
                // Verificar si el nuevo teléfono ya existe
                elseif (!empty($datos['movil'])) {
                    $usuarioConMismoTelefono = $this->modelo->buscarUsuarios(['movil' => $datos['movil']]);
                    if (!empty($usuarioConMismoTelefono)) {
                        $respuesta['correcto'] = 'N';
                        $respuesta['msj'] = 'El Teléfono ya está registrado';
                    }
                } else {
                    // Si todo está bien, se crea el nuevo usuario
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
        
    }
?>