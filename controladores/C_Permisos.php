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
        // Prepara una respuesta por defecto
        $resultado = [
            'correcto' => 'N',
            'msj'      => 'No se pudo guardar el permiso'
        ];

        // Llamamos al método del modelo para guardar
        $resp = $this->modelo->guardarPermiso($datos);
        
        // Si el modelo responde satisfactoriamente, modificamos el resultado
        if ($resp) {
            $resultado['correcto'] = 'S';
            $resultado['msj'] = 'Permiso guardado con éxito.';
        }

        // Retornamos la respuesta en formato JSON
        echo json_encode($resultado);
    }
}

// Para probar cambiar action de mantenimiento.