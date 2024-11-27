function guardarMenu() {
    console.log('guardando');

    let opciones = {method: "GET",};
    let parametros = "controlador=Menu&metodo=guardarMenu";
    parametros += '&' + new URLSearchParams(new FormData(document.getElementById('formularioNuevoEditar'))).toString();

    fetch("C_Frontal.php?" + parametros, opciones) // Llamada al Controlador frontal
    .then(res => {
        if (res.ok) {
            return res.json();
        }
        throw new Error(res.status);
    })
    .then(resultado => {
        if (resultado.correcto == 'S') {
            document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
        } else {
            document.getElementById('msjError').innerHTML = resultado.msj;
        }
    })
    .catch (err => {
        console.log("Error al pedir vista", err.message);
    })
}