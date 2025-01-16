// function guardarPermiso() {
//     // 1. Validar campos del formulario
//     let permiso        = document.getElementById('permiso').value;
//     let id_menu        = document.getElementById('id_menu').value;
//     let codigo_permiso = document.getElementById('codigo_permiso').value;
//     let errores = [];

//     if (!permiso) {
//         errores.push("El campo 'Permiso' es obligatorio.");
//     }
//     if (!id_menu) {
//         errores.push("El campo 'Menú ID' es obligatorio.");
//     }
//     if (!codigo_permiso) {
//         errores.push("El campo 'Código' es obligatorio.");
//     }

//     // Si hay errores, los mostramos y detenemos la ejecución
//     if (errores.length > 0) {
//         alert(errores.join("\n")); // O bien, tu contenedor de errores
//         return;
//     }

//     // 2. Construir los parámetros para la petición
//     let parametros = "controlador=Permisos&metodo=guardarPermiso";
//     let opciones = { method: 'GET' };

//     // Obtenemos todos los datos del formulario
//     parametros += "&" + new URLSearchParams(new FormData(document.getElementById('formularioPermiso'))).toString();

//     // 3. Hacemos la petición al servidor
//     fetch("C_Frontal.php?" + parametros, opciones)
//         .then(res => {
//             if (res.ok) {
//                 // Se espera una respuesta en JSON (por el echo json_encode en el controlador)
//                 return res.json();
//             }
//             throw new Error(res.status);
//         })
//         .then(resultado => {
//             if (resultado.correcto === 'S') {
//                 // Si se guardó correctamente, mostramos el mensaje o refrescamos la vista
//                 alert(resultado.msj); 
//                 // Por ejemplo, podrías recargar la lista de permisos o cerrar un modal
//                 document.getElementById('capaEditarCrear').innerHTML = resultado.msj;
//             } else {
//                 // Mostramos el mensaje de error que nos vino del servidor
//                 alert(resultado.msj);
//             }
//         })
//         .catch(err => {
//             console.error("Error al guardar el permiso:", err.message);
//         });
// }
