<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Permisos.php';
require_once 'vistas/Vista.php';

class C_Permisos extends Controlador {
    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new M_Permisos();
    }

    public function getVistaFiltros($datos = array()) {
        echo "Método getVistaFiltros invocado"; // Para verificar
        Vista::render('./vistas/Permisos/V_Permisos_Filtros.php');
        exit; // Detén la ejecución aquí para confirmar
    }    

    public function getVistaListado($filtros = array()) {
        $permisos = $this->modelo->buscarPermisos($filtros);
        Vista::render('./vistas/Permisos/V_Permisos_Listado.php', ['permisos' => $permisos]);
    }

    public function getVistaNuevoEditar($datos = array()) {
        $permiso = []; // Initialize an empty array for a new form
    
        // Case: Edit existing permission
        if (isset($datos['id']) && !empty($datos['id'])) {
            $filtros['id'] = $datos['id'];
            $permisos = $this->modelo->buscarPermisos($filtros);
    
            if (!empty($permisos)) {
                $permiso = $permisos[0];
            } else {
                echo "<pre>Error: No data found for the provided ID.</pre>";
                return;
            }
        }
    
        // Case: New permission (only id_menu is prefilled if provided)
        if (isset($datos['id_menu'])) {
            $permiso['id_menu'] = $datos['id_menu'];
        }
    
        Vista::render('./vistas/Permisos/V_Permisos_NuevoEditar.php', ['permiso' => $permiso]);
    }

    
    public function guardarPermiso($datos = array()) {
        $resultado = [
            'correcto' => 'N',
            'msj'      => 'No se pudo guardar el permiso'
        ];
    
        // Decide whether to insert or update based on the presence of an ID
        if (isset($datos['id']) && !empty($datos['id'])) {
            $resp = $this->modelo->actualizarPermiso($datos);
        } else {
            $resp = $this->modelo->insertarPermiso($datos);
        }
    
        if ($resp) {
            $resultado['correcto'] = 'S';
            $resultado['msj'] = 'Permiso guardado con éxito.';
        }
    
        echo json_encode($resultado);
    }
    
    public function eliminarPermiso($datos = array()) {
        $resultado = [
            'correcto' => 'N',
            'msj'      => 'No se pudo eliminar el permiso'
        ];
    
        // Validate that an ID is provided
        if (!isset($datos['id']) || empty($datos['id'])) {
            $resultado['msj'] = 'ID de permiso no proporcionado.';
            echo json_encode($resultado);
            return;
        }
    
        // Call the model to delete the permission
        $resp = $this->modelo->eliminarPermiso($datos['id']);
    
        if ($resp) {
            $resultado['correcto'] = 'S';
            $resultado['msj'] = 'Permiso eliminado con éxito.';
        }
    
        echo json_encode($resultado);
    }
    
    public function asignarPermisoRol($datos = array()) {
        $resultado = [
            'correcto' => 'N',
            'msj' => 'No se pudo procesar la operación'
        ];
    
        // Validación más detallada de los datos
        if (empty($datos['id_permiso'])) {
            $resultado['msj'] = 'ID de permiso no proporcionado';
            echo json_encode($resultado);
            return;
        }
    
        if (empty($datos['id_rol'])) {
            $resultado['msj'] = 'ID de rol no proporcionado';
            echo json_encode($resultado);
            return;
        }
    
        if (!isset($datos['asignar'])) {
            $resultado['msj'] = 'Acción no especificada (asignar/desasignar)';
            echo json_encode($resultado);
            return;
        }
    
        try {
            if ($datos['asignar'] === '1') {
                $resp = $this->modelo->insertarPermisoRol($datos['id_rol'], $datos['id_permiso']);
                $resultado['msj'] = 'Permiso asignado correctamente';
            } else {
                $resp = $this->modelo->eliminarPermisoRol($datos['id_rol'], $datos['id_permiso']);
                $resultado['msj'] = 'Permiso removido correctamente';
            }
    
            if ($resp) {
                $resultado['correcto'] = 'S';
            } else {
                $resultado['msj'] = 'Error en la operación de base de datos';
            }
        } catch (Exception $e) {
            $resultado['msj'] = 'Error: ' . $e->getMessage();
        }
    
        echo json_encode($resultado);
    }  
    
}

// Para probar cambiar action de mantenimiento.