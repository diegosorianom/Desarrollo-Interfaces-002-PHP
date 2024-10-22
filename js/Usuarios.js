function guardarUsuario() {
    console.log('guardando');

    let opciones = {method: "GET",};
    let parametros = "controlador=Usuarios&metodo=guardarUsuario";
    parametros += '&' + new URLSearchParams(new FormData(document.getElementById('formularioNuevoEditar'))).toString();

    fetch("C_Frontal.php?" + parametros, opciones) // Llamada al Controlador frontal
    .then(res => {
        // Nos devuelve si esta OK o no
        if (res.ok) {
            return res.json();
        }
        throw new Error(res.status);
    }) 
    .then(resultado => {
        if (resultado.correcto=='S'){
            document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
        } else {
            document.getElementById('msjError').innerHTML = resultado.msj;
        }
        // document.getElementById(destino).innerHTML = vista;
    })
    .catch(err => {
        console.log("Error al pedir vista", err.message);
    })
}