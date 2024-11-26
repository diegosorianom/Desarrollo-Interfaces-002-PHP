function guardarMenu() {
    console.log('guardando menu');

    let opciones = {method: "GET",};
    let parametros = "controlador=Menu&metodo=guardarMenu";
    parametros += '&' + new URLSearchParams(new FormData(document.getElementById('formularioNuevoEditar'))).toString();

    fetch("C_Frontal.php?" + parametros, opciones)
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
    })
    .catch(err => {
        console.log("Error al pedir la vista", err.message);
    })
}