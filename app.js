// Aqui es donde crearemos las funciones básicas
function obtenerVista(controlador, metodo, destino) {

    let opciones = {method: "GET",};
    let parametros = "controlador=" + controlador + "&metodo=" + metodo;

    fetch("C_Frontal.php?" + parametros, opciones) // Llamada al Controlador frontal
    .then(res => {
        // Nos devuelve si esta OK o no
        if (res.ok) {
            return res.text();
        }
        throw new Error(res.status);
    }) 
    .then(vista => {
        document.getElementById(destino).innerHTML = vista;
    })
    .catch(err => {
        console.log("Error al pedir vista", err.message);
    })
}