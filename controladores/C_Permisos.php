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
    //     // Inicializar array vacío para el permiso
    //     $permiso = [];
    
    //     // Caso: Nuevo permiso para un menú específico
    //     if (isset($datos['id_menu']) && !empty($datos['id_menu'])) {
    //         $permiso['id_menu'] = $datos['id_menu']; // Asociar al menú
    //     } 
    //     // Caso: Editar permiso existente
    //     elseif (isset($datos['id']) && !empty($datos['id'])) {
    //         $filtros['id'] = $datos['id'];
    //         $permisos = $this->modelo->buscarPermisos($filtros); // Buscar el permiso en la base de datos
    //         if (!empty($permisos)) {
    //             $permiso = $permisos[0]; // Usar el primer resultado
    //         }
    //     } else {
    //         // Manejo de error: No hay información suficiente para determinar el contexto
    //         echo "<pre>Error: No se recibió id_menu o id para procesar la solicitud.</pre>";
    //         return;
    //     }
    
    //     // Debug: Mostrar información recibida y procesada
    //     echo "<pre>Debug: Datos recibidos en getVistaNuevoEditar:\n";
    //     print_r($datos);
    //     echo "</pre>";
    
    //     echo "<pre>Debug: Permiso preparado para la vista:\n";
    //     print_r($permiso);
    //     echo "</pre>";
    
    //     // Renderizar la vista con los datos del permiso
    //     Vista::render('./vistas/Permisos/V_Permisos_NuevoEditar.php', ['permiso' => $permiso]);
    // }
    
    
    
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