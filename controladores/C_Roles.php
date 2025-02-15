<?php
require_once 'controladores/Controlador.php';
require_once 'vistas/Vista.php';
require_once 'modelos/M_Roles.php';

class C_Roles extends Controlador {

    private $modelo;

    public function __construct() {
        parent::__construct();

        // Verificar si la sesión ya está iniciada antes de llamarla
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->modelo = new M_Roles();
        $this->forzarRol19EnSesion(); // Asegurar que el rol 19 esté en sesión
        $this->cargarRolesYPermisosUsuario(); // Cargar roles y permisos en sesión
    }

    // Funcion para obtener la vista de roles (no se utiliza)
    public function getVistaListadoRoles($datos = array()) {
        $roles = $this->modelo->buscarRoles();
        Vista::render('Roles/V_Roles_Listado.php', array('roles' => $roles));
    }

    // Función para obtener el formulario con el cual crear o editar roles
    public function getVistaNuevoEditar($datos = array()) {
        if (!isset($datos['id']) || $datos['id'] == '') {
            // Nuevo
            Vista::render('Roles/V_Roles_NuevoEditar.php');
        } else {
            // Editando
            $filtros['id'] = $datos['id'];
            $roles = $this->modelo->buscarRoles($filtros);
            if (!empty($roles)) {
                Vista::render('Roles/V_Roles_NuevoEditar.php', array('rol' => $roles[0]));
            } else {
                echo "Rol no encontrado";
            }
        }
    }

    // Función para guardar o actualizar un rol
    public function guardarRol($datos = array()) {
        $resultado = $this->modelo->insertarActualizarRol($datos);
        if ($resultado) {
            echo json_encode(['correcto' => 'S', 'msj' => 'Rol guardado correctamente']);
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'Error al guardar el rol']);
        }
    }

    // Función para eliminar un rol
    public function eliminarRol($datos = array()) {
        if (isset($datos['id'])) {
            $resultado = $this->modelo->eliminarRol($datos['id']);
            if ($resultado) {
                echo json_encode(['correcto' => 'S', 'msj' => 'Rol eliminado correctamente']);
            } else {
                echo json_encode(['correcto' => 'N', 'msj' => 'Error al eliminar el rol']);
            }
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'ID de rol no proporcionado']);
        }
    }

    
    // Función para asignar un rol a un usuario 
    public function asignarRolAUsuario($datos = array()) {
        header('Content-Type: application/json'); // Asegura que la respuesta sea JSON
    
        if (!isset($datos['rol_id']) || !isset($datos['usuario_id'])) {
            echo json_encode(['correcto' => 'N', 'msj' => 'Datos incompletos']);
            return;
        }
    
        $rolId = $datos['rol_id'];
        $usuarioId = $datos['usuario_id'];
    
        $mensaje = $this->modelo->asignarRolAUsuario($rolId, $usuarioId);
    
        if ($mensaje === "Rol asignado correctamente") {
            echo json_encode(['correcto' => 'S', 'msj' => $mensaje]);
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => $mensaje]);
        }
    }

    // Función para desasignar un rol a un usuario
    public function desasignarRolAUsuario($datos = array()) {
        header('Content-Type: application/json'); // Asegura que la respuesta sea JSON
    
        if (!isset($datos['rol_id']) || !isset($datos['usuario_id'])) {
            echo json_encode(['correcto' => 'N', 'msj' => 'Datos incompletos']);
            return;
        }
    
        $rolId = $datos['rol_id'];
        $usuarioId = $datos['usuario_id'];
    
        $resultado = $this->modelo->desasignarRolAUsuario($rolId, $usuarioId);
    
        if ($resultado === "Rol eliminado correctamente") {
            echo json_encode(['correcto' => 'S', 'msj' => $resultado]);
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => $resultado]);
        }
    }
    
    // Función para obtener todos los roles a los que pertenece un usuario
    public function obtenerRolesDeUsuario($datos = array()) {
        header('Content-Type: application/json'); // Aseguramos respuesta JSON
    
        if (!isset($datos['usuario_id']) || $datos['usuario_id'] == '') {
            echo json_encode(['correcto' => 'N', 'msj' => 'Usuario no especificado']);
            return;
        }
    
        $usuarioId = $datos['usuario_id'];
        $rolesUsuario = $this->modelo->obtenerRolesDeUsuario($usuarioId);
    
        // Extraer solo los IDs de los roles
        $ids = [];
        if (!empty($rolesUsuario)) {
            foreach ($rolesUsuario as $role) {
                $ids[] = $role['id_rol'];
            }
        }
        echo json_encode(['correcto' => 'S', 'roles' => $ids]);
    }

    private function forzarRol19EnSesion() {
        if (!isset($_SESSION['roles']) || !is_array($_SESSION['roles'])) {
            $_SESSION['roles'] = [];
        }

        // Verificar si el rol 19 ya está presente
        $existeRol19 = array_filter($_SESSION['roles'], fn($role) => $role['id_rol'] == 19);

        if (empty($existeRol19)) {
            $_SESSION['roles'][] = ['id_rol' => 19, 'nombre' => 'Rol Desconocido'];
        }
    }

    private function cargarRolesYPermisosUsuario() {
        $todosLosRoles = $this->modelo->buscarRoles();
        $permisosUsuario = [];

        foreach ($_SESSION['roles'] as &$rol) {
            foreach ($todosLosRoles as $rolBD) {
                if ($rol['id_rol'] == $rolBD['id']) {
                    $rol['nombre'] = $rolBD['nombre']; // Asignar nombre correcto
                }
            }

            // Obtener permisos y evitar duplicados
            $permisosRol = $this->modelo->obtenerPermisosDeRol($rol['id_rol']);
            foreach ($permisosRol as $permiso) {
                $permisosUsuario[$permiso['id']] = $permiso; // Clave única para evitar repetidos
            }
        }

        // Guardar permisos en sesión
        $_SESSION['permisos'] = array_values($permisosUsuario);
    }
        
    // Fin del controlador de roles
} 

?>
