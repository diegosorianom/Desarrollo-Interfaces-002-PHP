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
    })
    .catch(err => {
        console.log("Error al pedir vista", err.message);
    })
}

function actualizarEstadoVisual(usuarioId) {
    // Cambia el innerHTML del estado inmediatamente
    const estadoElemento = document.getElementById(`estado-${usuarioId}`);
    const filaElemento = estadoElemento.closest('tr'); // Encuentra la fila (tr) que contiene este elemento

    // Alterna entre "Activo" e "Inactivo"
    if (estadoElemento.innerHTML === 'Activo') {
        estadoElemento.innerHTML = 'Inactivo';
        filaElemento.classList.add('table-danger');  // Cambia la fila a rojo (inactivo)
        filaElemento.classList.remove('table-success'); // Remueve la clase activa (si la tiene)
    } else {
        estadoElemento.innerHTML = 'Activo';
        filaElemento.classList.add('table-success');  // Cambia la fila a verde (activo)
        filaElemento.classList.remove('table-danger'); // Remueve la clase inactiva (si la tiene)
    }
}

function toggleEstado(usuarioId) {
    // Llama a actualizarEstadoVisual para cambiar el estado de inmediato en la interfaz
    actualizarEstadoVisual(usuarioId);

    // Luego envía la solicitud AJAX para actualizar el estado en la base de datos
    fetch("C_Frontal.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `controlador=Usuarios&metodo=cambiarEstado&id_Usuario=${usuarioId}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error("Error:", data.error);
        }
    })
    .catch(error => console.error("Error en la solicitud:", error));
}