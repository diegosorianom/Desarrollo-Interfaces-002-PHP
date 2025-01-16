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

    // public function getVistaNuevoEditar($datos = array()) {
    //     if (!isset($datos['id']) || $datos['id'] == '') {
    //         Vista::render('./vistas/Permisos/V_Permisos_NuevoEditar.php');
    //     } else {
    //         $filtros['id'] = $datos['id'];
    //         $permisos = $this->modelo->buscarPermisos($filtros);
    //         Vista::render('./vistas/Permisos/V_Permisos_NuevoEditar.php', ['permiso' => $permisos[0]]);
    //     }
    // }

    // public function guardarPermiso($datos = array()) {
    //     $respuesta['correcto'] = 'S';
    //     $respuesta['msj'] = 'Guardado correctamente';

    //     $id = $this->modelo->insertarPermiso($datos);
    //     if ($id <= 0) {
    //         $respuesta['correcto'] = 'N';
    //         $respuesta['msj'] = 'Error al guardar';
    //     }

    //     echo json_encode($respuesta);
    // }

    // public function eliminarPermiso($datos = array()) {
    //     $id = $datos['id'] ?? '';
    //     $resultado = $this->modelo->eliminarPermiso($id);

    //     $respuesta['correcto'] = $resultado ? 'S' : 'N';
    //     $respuesta['msj'] = $resultado ? 'Eliminado correctamente' : 'Error al eliminar';
    //     echo json_encode($respuesta);
    // }
}

// Para probar cambiar action de mantenimiento.