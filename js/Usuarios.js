function guardarUsuario() {

    // Validación de los campos antes de enviar los datos
    let nombre = document.getElementById('nombre').value;
    let login = document.getElementById('login').value;
    let email = document.getElementById('mail').value;
    let errores = [];

    if (!nombre) {
        errores.push("El campo nombre es obligatorio.");
    } else if (nombre.length < 3) {
        errores.push("El nombre debe tener al menos 3 caracteres.");
    }

    if (!login) {
        errores.push("El campo login es obligatorio.");
    } else if (login.length < 3) {
        errores.push("El login debe tener al menos 3 caracteres.");
    }

    if (!email) {
        errores.push("El campo email es obligatorio.");
    } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
        errores.push("El formato del email es incorrecto.");
    }

    if (errores.length > 0) {
        document.getElementById('msjError').innerHTML = errores.join("<br>");
        return; // Detenemos la ejecución de guardarUsuario si hay errores
    }

    // Si no hay errores, limpiamos el mensaje de error y procedemos con la llamada al servidor
    document.getElementById('msjError').innerHTML = "";

    console.log("Guardando usuario...");

    // Parámetros y opciones para el fetch
    let parametros = "controlador=Usuarios&metodo=guardarUsuario";
    let opciones = {method: 'GET',}
    parametros += "&" + new URLSearchParams(new FormData(document.getElementById('formularioUsuario'))).toString(); // Obtenemos los datos del formulario

    // Llamamos al servidor
    fetch("C_Frontal.php?" + parametros, opciones)
        .then(res => {
            if (res.ok) {                
                return res.json();       
            } 
            throw new Error(res.status); // Si no, capturamos el error
        })
        .then(resultado=> {
            if(resultado.correcto == 'S') {
                document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
            } else {
                document.getElementById('msjError').innerHTML = resultado.msj;
            }
        })
        .catch(err => {                // Si hay un error lo mostramos 
            console.error("Error al guardar", err.message);
        });
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