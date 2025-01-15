function guardarPermiso() {
    let parametros = "controlador=Permisos&metodo=guardarPermiso";
    parametros += "&" + new URLSearchParams(new FormData(document.getElementById('formularioPermiso'))).toString();

    fetch("C_Frontal.php?" + parametros, { method: 'GET' })
        .then(res => res.json())
        .then(data => {
            alert(data.msj);
            if (data.correcto === 'S') {
                obtenerVista('Permisos', 'getVistaListado', 'capaResultadoBusqueda');
            }
        })
        .catch(err => console.error("Error:", err));
}

function eliminarPermiso(id) {
    if (confirm("¿Estás seguro de eliminar este permiso?")) {
        let parametros = "controlador=Permisos&metodo=eliminarPermiso&id=" + id;

        fetch("C_Frontal.php?" + parametros, { method: 'GET' })
            .then(res => res.json())
            .then(data => {
                alert(data.msj);
                if (data.correcto === 'S') {
                    obtenerVista('Permisos', 'getVistaListado', 'capaResultadoBusqueda');
                }
            })
            .catch(err => console.error("Error:", err));
    }
}
