<?php
require_once 'controladores/Controlador.php';
require_once 'vistas/Vista.php';
require_once 'modelos/M_Roles.php';

class C_Roles extends Controlador {

    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new M_Roles();
    }

    public function getVistaListadoRoles($datos = array()) {
        $roles = $this->modelo->buscarRoles();
        Vista::render('Roles/V_Roles_Listado.php', array('roles' => $roles));
    }

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

    public function guardarRol($datos = array()) {
        $resultado = $this->modelo->insertarActualizarRol($datos);
        if ($resultado) {
            echo json_encode(['correcto' => 'S', 'msj' => 'Rol guardado correctamente']);
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'Error al guardar el rol']);
        }
    }

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

    // 🔹 AQUÍ COLOCAMOS EL MÉTODO DENTRO DE LA CLASE 🔹
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
    
    
} // 🔹 Aquí cerramos correctamente la clase 🔹

?>
