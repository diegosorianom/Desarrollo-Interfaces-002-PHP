<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Roles.php';
require_once 'vistas/Vista.php';

class C_Roles extends Controlador {
    private $modelo;

    public function __construct() {
        parent::__construct(); // Ejecutar constructor del padre
        $this->modelo = new M_Roles();
    }

    public function getVistaFiltros($datos = array()) {
        Vista::render('./vistas/Roles/V_Roles_Filtros.php');
    }

    public function getVistaNuevoEditar($datos = array()) {
        if (!isset($datos['id']) || $datos['id'] == '') {
            // Nuevo
            Vista::render('./vistas/Roles/V_Roles_NuevoEditar.php');
        } else {
            // Editando
            $filtros['id'] = $datos['id'];
            $roles = $this->modelo->buscarRoles($filtros);
            Vista::render('./vistas/Roles/V_Roles_NuevoEditar.php', array('rol' => $roles[0]));
        }
    }

    public function getVistaListadoRoles($filtros = array()) {
        $roles = $this->modelo->buscarRoles($filtros);
        Vista::render('vistas/Roles/V_Roles_Listado.php', array('roles' => $roles));
    }

    public function guardarRol($datos = array()) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        try {
            // Agregar logs de depuración
            error_log("Datos recibidos: " . print_r($datos, true));

            $respuesta = ['correcto' => 'S', 'msj' => 'Creado correctamente'];
        
            if (!empty($datos['id'])) {
                $rolExistente = $this->modelo->buscarRoles(['id' => $datos['id']]);
                if (empty($rolExistente)) {
                    $respuesta = ['correcto' => 'N', 'msj' => 'El rol a editar no existe'];
                } else {
                    if (!empty($datos['nombre']) && $this->modelo->isNombreEnUso($datos['nombre'], $datos['id'])) {
                        $respuesta = ['correcto' => 'N', 'msj' => 'El nombre del rol ya está registrado'];
                    } else {
                        $id = $this->modelo->insertarRol($datos);
                        $respuesta['msj'] = ($id > 0) ? 'Editado correctamente' : 'Error al editar';
                        $respuesta['correcto'] = ($id > 0) ? 'S' : 'N';
                    }
                }
            } else {
                if ($this->modelo->isNombreEnUso($datos['nombre'])) {
                    $respuesta = ['correcto' => 'N', 'msj' => 'El nombre del rol ya está registrado'];
                } else {
                    $id = $this->modelo->insertarRol($datos);
                    $respuesta['msj'] = ($id > 0) ? 'Creado correctamente' : 'Error al crear';
                    $respuesta['correcto'] = ($id > 0) ? 'S' : 'N';
                }
            }

            // Agregar log de la respuesta
            error_log("Respuesta: " . print_r($respuesta, true));

        } catch (Exception $e) {
            error_log("Excepción capturada: " . $e->getMessage());
            $respuesta = ['correcto' => 'N', 'msj' => 'Error en el servidor: ' . $e->getMessage()];
        }
    
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    }
    
}
?>
