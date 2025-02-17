document.addEventListener("change", (event) => {
  const selectRol = document.getElementById("frol")
  const selectUsuario = document.getElementById("fusuario")

  if (event.target && event.target.id === "frol") {
    console.log("🔹 Rol seleccionado:", event.target.value)

    // 🚫 Deshabilitar usuario si se selecciona un rol
  //   if (event.target.value !== "") {
  //     selectUsuario.disabled = true // ❌ Deshabilitar usuario
  //   } else {
  //     selectUsuario.disabled = false // ✅ Volver a habilitar si se quita el rol
  //   }
  }

  if (event.target && event.target.id === "fusuario") {
    console.log("🔹 Usuario seleccionado:", event.target.value)
    destacarRolesPorUsuario();

    // 🚫 Deshabilitar rol si se selecciona un usuario
  //   if (event.target.value !== "") {
  //     selectRol.disabled = true // ❌ Deshabilitar rol
  //   } else {
  //     selectRol.disabled = false // ✅ Volver a habilitar si se quita el usuario
  //   }
  }
})

function cargarUnScript(url) {
    const script = document.createElement("script")
    script.src = url
    document.head.appendChild(script)
  }
  
  // Aqui es donde crearemos las funciones básicas
  function obtenerVista(controlador, metodo, destino) {
    const parametros = "controlador=" + controlador + "&metodo=" + metodo
    const opciones = { method: "GET" }
  
    fetch("C_Frontal.php?" + parametros, opciones)
      .then((res) => {
        if (res.ok) {
          return res.text()
        }
        throw new Error(res.status)
      })
      .then((vista) => {
        document.getElementById(destino).innerHTML = vista
        cargarUnScript("js/" + controlador + ".js")
      })
      .catch((error) => {
        console.log("Error al pedir vista", error.message)
      })
  }
  
  function obtenerVista_EditarCrear(controlador, metodo, destino, id) {
    const parametros = "controlador=" + controlador + "&metodo=" + metodo + "&id=" + id // Add '=' after id
    const opciones = { method: "GET" }
  
    fetch("C_Frontal.php?" + parametros, opciones)
      .then((res) => {
        if (res.ok) {
          return res.text()
        }
        throw new Error(res.status)
      })
      .then((vista) => {
        document.getElementById(destino).innerHTML = vista
      })
      .catch((error) => {
        console.log("Error al pedir vista", error.message)
      })
  }
  
  function añadirMenu(menuId, positionType) {
    const parametros = new URLSearchParams()
    parametros.append("controlador", "Menu")
    parametros.append("metodo", "getVistaNuevoEditar")
    parametros.append("menu_id", menuId)
    parametros.append("position_type", positionType) // Parámetro para indicar que se agregará arriba
  
    const opciones = { method: "GET" }
  
    fetch("C_Frontal.php?" + parametros.toString(), opciones)
      .then((res) => {
        if (res.ok) {
          return res.text()
        }
        throw new Error(res.status)
      })
      .then((vista) => {
        document.getElementById("capaEditarCrear").innerHTML = vista
      })
      .catch((error) => {
        console.log(`Error al añadir el menú ${positionType}`, error.message)
      })
  }
  
  function añadirHijo(menuId) {
    const parametros = new URLSearchParams()
    parametros.append("controlador", "Menu")
    parametros.append("metodo", "getVistaNuevoEditar")
    parametros.append("menu_id", menuId)
    parametros.append("position_type", "child") // Especificar que se agregará un hijo
  
    const opciones = { method: "GET" }
  
    fetch("C_Frontal.php?" + parametros.toString(), opciones)
      .then((res) => {
        if (res.ok) {
          return res.text()
        }
        throw new Error(res.status)
      })
      .then((vista) => {
        document.getElementById("capaEditarCrear").innerHTML = vista
      })
      .catch((error) => {
        console.log("Error al añadir hijo", error.message)
      })
  }
  
  // Aqui es donde crearemos las funciones básicas
  function buscar(controlador, metodo, formulario, destino) {
    let parametros = "controlador=" + controlador + "&metodo=" + metodo // Fixed the parameter formatting
    const opciones = { method: "GET" }
    parametros += "&" + new URLSearchParams(new FormData(document.getElementById(formulario))).toString()
  
    fetch("C_Frontal.php?" + parametros, opciones)
      .then((res) => {
        if (res.ok) {
          return res.text()
        }
        throw new Error(res.status)
      })
      .then((vista) => {
        document.getElementById(destino).innerHTML = vista
      })
      .catch((error) => {
        console.log("Error al pedir vista", error.message) // Fixed 'err' to 'error'
      })
  }
  
  function toggleChildren(menuId, event) {
    event.stopPropagation() // Evitar activar el clic en la fila
    const children = document.getElementById(`children-${menuId}`)
    const arrow = event.target.closest(".arrow").querySelector("i") // Busca el ícono dentro del contenedor de flecha
  
    if (children.style.display === "none") {
      children.style.display = "block" // Mostrar hijos
      arrow.classList.remove("fa-chevron-right")
      arrow.classList.add("fa-chevron-down")
    } else {
      children.style.display = "none" // Ocultar hijos
      arrow.classList.remove("fa-chevron-down")
      arrow.classList.add("fa-chevron-right")
    }
  }
  
  function toggleOptions(menuId) {
    // Obtener los selects de rol y usuario
    const selectRol = document.getElementById("frol");
    const selectUsuario = document.getElementById("fusuario");
  
    // Si se ha seleccionado un rol o un usuario, no se despliegan las opciones
    if ((selectRol && selectRol.value !== "") || (selectUsuario && selectUsuario.value !== "")) {
      return; // Salir sin hacer nada
    }
  
    // Si no se ha seleccionado rol/usuario, se continúa con la lógica de despliegue
    const options = document.getElementById(`options-${menuId}`);
    const allOptions = document.querySelectorAll(".menu-options");
    allOptions.forEach((opt) => {
      if (opt !== options) opt.style.display = "none"; // Oculta las demás opciones
    });
    options.style.display = options.style.display === "none" ? "block" : "none";
  }
  
  
  function buscarRol() {
    const selectRol = document.getElementById("frol");
    const selectUsuario = document.getElementById("fusuario");
    const selectedRoleId = selectRol ? selectRol.value : "";
    const selectedUserId = selectUsuario ? selectUsuario.value : "";

    console.log("🔍 ID del Rol seleccionado:", selectedRoleId);
    console.log("🔍 ID del Usuario seleccionado:", selectedUserId);

    const parametros = new URLSearchParams();
    parametros.append("controlador", "Menu");
    parametros.append("metodo", "getVistaListadoMenu");
    parametros.append("frol", selectedRoleId);
    parametros.append("fusuario", selectedUserId);

    fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
        .then((res) => res.text())
        .then((vista) => {
            document.getElementById("capaResultadoBusqueda").innerHTML = vista;

            // 🚀 Si se seleccionó un usuario, obtener los permisos heredados por su rol
            if (selectedUserId) {
                obtenerPermisosHeredados(selectedUserId);
            }
        })
        .catch((error) => console.log("❌ Error al buscar menús y permisos:", error.message));
}

/**
 * Obtiene y muestra en consola los permisos heredados de un usuario a través de sus roles.
 * @param {number} usuarioId - ID del usuario seleccionado.
 */
function obtenerPermisosHeredados(usuarioId) {
  const parametros = new URLSearchParams();
  parametros.append("controlador", "Permisos");
  parametros.append("metodo", "getPermisosHeredadosUsuario");
  parametros.append("id_usuario", usuarioId);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
      .then((res) => res.json())
      .then((data) => {
          if (data.correcto === "S") {
              console.log("🔹 Permisos heredados del usuario por sus roles:", data.permisos_heredados);
              // Ejemplo de recorrido: cada elemento contiene 'id_permiso' y 'id_rol'
              data.permisos_heredados.forEach(item => {
                  console.log(`Permiso: ${item.id_permiso} - Rol de origen: ${item.id_rol}`);
              });
          } else {
              console.log("⚠️ Error obteniendo permisos heredados:", data.error);
          }
      })
      .catch((error) => console.log("❌ Error en la solicitud de permisos heredados:", error.message));
}

  
  function togglePermiso(permisoId) {
    const checkbox = document.querySelector(`input[data-permiso-id="${permisoId}"]`)
    const seleccionado = checkbox.checked
  
    const selectRol = document.getElementById("frol")
    const selectedRoleId = selectRol ? selectRol.value : ""
  
    const selectUsuario = document.getElementById("fusuario")
    const selectedUserId = selectUsuario ? selectUsuario.value : ""
  
    if (!selectedRoleId && !selectedUserId) {
      console.log("⚠️ No se seleccionó un rol ni un usuario.")
      return
    }
  
    const parametros = new URLSearchParams()
    parametros.append("controlador", "Permisos") // Usamos el nuevo controlador
    parametros.append("metodo", "actualizarPermiso")
    parametros.append("id_permiso", permisoId)
    parametros.append("asignado", seleccionado ? "1" : "0")
  
    if (selectedRoleId) {
      parametros.append("frol", selectedRoleId)
    }
    if (selectedUserId) {
      parametros.append("fusuario", selectedUserId)
    }
  
    console.log("🔄 Enviando solicitud de actualización:", parametros.toString())
  
    fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then((res) => res.text())
      .then((respuesta) => console.log("🔄 Respuesta del servidor:", respuesta))
      .catch((error) => console.log("❌ Error al actualizar permiso:", error.message))
  }
  
  function mostrarPermisoSeleccionado(permisoId) {
    console.log("🔹 Permiso seleccionado:", permisoId)
  }
  
  function guardarRol() {
    const formData = new FormData(document.getElementById("formularioNuevoEditar"))
    const mensajeError = document.getElementById("msjError"); // Obtener el elemento de error
  
    const opciones = {
      method: "POST",
      body: formData,
    }
  
    fetch("C_Frontal.php?controlador=Roles&metodo=guardarRol", opciones)
      .then((res) => res.json())
      .then((respuesta) => {
        if (respuesta.correcto === "S") {
          // Ocultar mensaje de error si la operación es exitosa
          mensajeError.classList.add("d-none");
          mensajeError.innerText = "";

          obtenerVista("Menu", "getVistaListadoMenu", "capaResultadoBusqueda")
          document.getElementById("capaEditarCrear").innerHTML = ""
        } else {
          // Mostrar mensaje de error
          mensajeError.innerText = "⚠️ " + respuesta.msj;
          mensajeError.classList.remove("d-none");
        }
      })
      .catch((error) => {
        console.error("Error al guardar rol:", error);
        mensajeError.innerText = "Error al procesar la solicitud.";
        mensajeError.classList.remove("d-none");
      })
}

  
//   function eliminarRol(id) {
//     if (confirm("¿Está seguro de que desea eliminar este rol?")) {
//       fetch(`C_Frontal.php?controlador=Roles&metodo=eliminarRol&id=${id}`, { method: "POST" })
//         .then((res) => res.json())
//         .then((respuesta) => {
//           if (respuesta.correcto === "S") {
//             //alert(respuesta.msj)
//             obtenerVista("Menu", "getVistaFiltros", "capaContenido");
//           } else {
//             //alert(respuesta.msj)
//           }
//         })
//         .catch((error) => {
//           console.error("Error al eliminar rol:", error)
//           //alert("Error al procesar la solicitud.")
//         })
//     }
//   }

function habilitarBotonesRol() {
  const selectRol = document.getElementById("frol")
  const btnEditarRol = document.getElementById("btnEditarRol")
  const btnEliminarRol = document.getElementById("btnEliminarRol")
  const rolSeleccionado = selectRol.value !== ""

  btnEditarRol.disabled = !rolSeleccionado
  btnEliminarRol.disabled = !rolSeleccionado
}

function eliminarRolSeleccionado() {
    const selectRol = document.getElementById("frol");
    const rolId = selectRol.value;
  
    if (rolId) {
      fetch(`C_Frontal.php?controlador=Roles&metodo=eliminarRol&id=${rolId}`, { method: "POST" })
          .then((res) => res.json())
          .then((respuesta) => {
            if (respuesta.correcto === "S") {
              //alert(respuesta.msj);
  
              // Actualizar la vista de filtros después de eliminar el rol
              obtenerVista("Menu", "getVistaFiltros", "capaContenido");
  
              // Cerrar el formulario de edición/creación
              document.getElementById("capaEditarCrear").innerHTML = "";
  
              // Opcional: Resetear el select de roles después de eliminar
              selectRol.value = "";
            } else {
              //alert(respuesta.msj);
            }
          })
          .catch((error) => {
            console.error("❌ Error al eliminar rol:", error);
            //alert("Error al procesar la solicitud.");
          });
    } else {
      //alert("Por favor, seleccione un rol para eliminar.");
    }
  }
  

// Actualizar la función editarRolSeleccionado para usar habilitarBotonesRol
function editarRolSeleccionado() {
  const selectRol = document.getElementById("frol")
  const rolId = selectRol.value
  if (rolId) {
    obtenerVista_EditarCrear("Roles", "getVistaNuevoEditar", "capaEditarCrear", rolId)
  } else {
    alert("Por favor, seleccione un rol para editar.")
  }
}

// Asegúrate de que esta función esté presente en tu archivo
function obtenerVista(controlador, metodo, destino) {
  const parametros = `controlador=${controlador}&metodo=${metodo}`
  const opciones = { method: "GET" }

  fetch(`C_Frontal.php?${parametros}`, opciones)
    .then((res) => {
      if (res.ok) {
        return res.text()
      }
      throw new Error(res.status)
    })
    .then((vista) => {
      document.getElementById(destino).innerHTML = vista
    })
    .catch((error) => {
      console.log("Error al pedir vista", error.message)
    })
}

function obtenerVista_EditarCrear(controlador, metodo, destino, id) {
  const parametros = `controlador=${controlador}&metodo=${metodo}&id=${id}`
  const opciones = { method: "GET" }

  fetch(`C_Frontal.php?${parametros}`, opciones)
    .then((res) => {
      if (res.ok) {
        return res.text()
      }
      throw new Error(res.status)
    })
    .then((vista) => {
      document.getElementById(destino).innerHTML = vista
    })
    .catch((error) => {
      console.log("Error al pedir vista", error.message)
    })
}

function habilitarBotonesRol() {
    const selectRol = document.getElementById("frol")
    const btnEditarRol = document.getElementById("btnEditarRol")
    const btnEliminarRol = document.getElementById("btnEliminarRol")
    const rolSeleccionado = selectRol.value !== ""
  
    btnEditarRol.disabled = !rolSeleccionado
    btnEliminarRol.disabled = !rolSeleccionado
  }
  
  function editarRolSeleccionado() {
    const selectRol = document.getElementById("frol")
    const rolId = selectRol.value
    if (rolId) {
      obtenerVista_EditarCrear("Roles", "getVistaNuevoEditar", "capaEditarCrear", rolId)
    } else {
      //alert("Por favor, seleccione un rol para editar.")
    }
  }
  
  function guardarRol() {
    const formData = new FormData(document.getElementById("formularioNuevoEditar"));

    const opciones = {
        method: "POST",
        body: formData,
    };

    fetch("C_Frontal.php?controlador=Roles&metodo=guardarRol", opciones)
        .then((res) => res.json())
        .then((respuesta) => {
            if (respuesta.correcto === "S") {
                //alert(respuesta.msj);
                
                // Actualizar la lista de roles después de guardar
                obtenerVista("Menu", "getVistaFiltros", "capaContenido");

                // Cerrar el formulario de edición/creación
                document.getElementById("capaEditarCrear").innerHTML = "";
            } else {
                document.getElementById("msjError").innerText = respuesta.msj;
            }
        })
        .catch((error) => {
            console.error("Error al guardar rol:", error);
            document.getElementById("msjError").innerText = "Error al procesar la solicitud.";
        });
    }  
    function asignarRolAUsuario() {
        const selectRol = document.getElementById("frol");
        const selectUsuario = document.getElementById("fusuario");
        const mensajeError = document.getElementById("mensajeError"); // Obtener el elemento de error
    
        const rolId = selectRol.value;
        const usuarioId = selectUsuario.value;
    
        if (!rolId || !usuarioId) {
            // alert("Por favor, seleccione un rol y un usuario.");
            mensajeError.innerText = "Por favor seleccione un rol y un usuario";
            mensajeError.classList.remove("d-none"); // Mostrar el mensaje
            return;
        }
    
        const parametros = new URLSearchParams();
        parametros.append("controlador", "Roles");
        parametros.append("metodo", "asignarRolAUsuario");
        parametros.append("rol_id", rolId);
        parametros.append("usuario_id", usuarioId);
    
        fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
            .then((res) => res.json()) // Convertimos la respuesta a JSON
            .then((respuesta) => {
                console.log("🔍 Respuesta del servidor:", respuesta);
                if (respuesta.correcto === "S") {
                  // Ocultar el mensaje de error si la operación es exitosa
                  mensajeError.classList.add("d-none");
                  mensajeError.innerText = "";
              } else {
                  // Mostrar el mensaje de error si hay un problema
                  mensajeError.innerText = "⚠️ " + respuesta.msj;
                  mensajeError.classList.remove("d-none");
              }
              
            })
            .catch((error) => {
                console.error("❌ Error en la solicitud:", error);
                //alert("Error al procesar la solicitud.");
            });
    }

    function desasignarRolAUsuario() {
        const selectRol = document.getElementById("frol");
        const selectUsuario = document.getElementById("fusuario");
    
        const rolId = selectRol.value;
        const usuarioId = selectUsuario.value;
    
        if (!rolId || !usuarioId) {
            //alert("Por favor, seleccione un rol y un usuario.");
            return;
        }
    
        // if (!confirm("¿Estás seguro de que deseas quitar este rol del usuario?")) {
        //     return;
        // }
    
        const parametros = new URLSearchParams();
        parametros.append("controlador", "Roles");
        parametros.append("metodo", "desasignarRolAUsuario");
        parametros.append("rol_id", rolId);
        parametros.append("usuario_id", usuarioId);
    
        fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
            .then((res) => res.json())
            .then((respuesta) => {
                console.log("🔍 Respuesta del servidor:", respuesta);
                if (respuesta.correcto === "S") {
                    //alert("✅ " + respuesta.msj);
                    mensajeError.classList.add("d-none");
                    mensajeError.innerText = "";
                } else {
                    //alert("⚠️ " + respuesta.msj);
                    mensajeError.innerText = "⚠️ " + respuesta.msj;
                    mensajeError.classList.remove("d-none");
                }
            })
            .catch((error) => {
                console.error("❌ Error en la solicitud:", error);
                //alert("Error al procesar la solicitud.");
            });
    }
    
    function destacarRolesPorUsuario() {
        const selectUsuario = document.getElementById("fusuario");
        const usuarioId = selectUsuario.value;
        const selectRol = document.getElementById("frol");
      
        // Primero removemos cualquier clase previamente aplicada en las opciones
        for (let option of selectRol.options) {
          option.classList.remove("linked-role");
        }
      
        // Si no se seleccionó usuario, no hacemos nada
        if (!usuarioId) return;
      
        // Configurar parámetros para la petición AJAX
        const parametros = new URLSearchParams();
        parametros.append("controlador", "Roles");
        parametros.append("metodo", "obtenerRolesDeUsuario");
        parametros.append("usuario_id", usuarioId);
      
        fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
          .then(response => response.json())
          .then(data => {
            if (data.correcto === "S") {
              const rolesVinculados = data.roles; // Array con IDs de roles asignados
              // Recorrer todas las opciones del select de roles
              for (let option of selectRol.options) {
                // Si el valor de la opción está en la lista, se añade la clase
                if (rolesVinculados.includes(option.value)) {
                  option.classList.add("linked-role");
                }
              }
            } else {
              console.error("Error:", data.msj);
            }
          })
          .catch(error => console.error("Error obteniendo roles del usuario:", error));
      }
      
    
    /**
 * Muestra el input para editar el nombre del permiso.
 * @param {number} permisoId 
 */
function mostrarEditarPermiso(permisoId) {
  const input = document.getElementById("permiso-edit-" + permisoId);
  if (input) {
    input.style.display = "inline-block";
    input.focus();
  }
}

/**
 * Se ejecuta cuando el input pierde el foco y se encarga de actualizar el nombre del permiso.
 * Realiza una petición AJAX para actualizar el nombre en la base de datos.
 * @param {number} permisoId 
 */
function actualizarNombrePermiso(permisoId) {
  const input = document.getElementById("permiso-edit-" + permisoId);
  const nuevoNombre = input.value;

  const parametros = new URLSearchParams();
  parametros.append("controlador", "Permisos");
  parametros.append("metodo", "actualizarNombrePermiso");
  parametros.append("id_permiso", permisoId);
  parametros.append("nuevo_nombre", nuevoNombre);

  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
    .then(response => response.text())
    .then(responseText => {
      console.log("Nombre actualizado:", responseText);
      if(responseText.trim() === "OK") {
        const span = document.getElementById("permiso-nombre-" + permisoId);
        if (span) {
          span.innerText = nuevoNombre;
        }
      }
      input.style.display = "none";
    })
    .catch(error => console.log("Error al actualizar nombre de permiso:", error));
}


/**
 * Elimina el permiso tras confirmar la acción. Realiza una petición AJAX para eliminar el permiso.
 * @param {number} permisoId 
 */
function eliminarPermiso(permisoId) {
  const parametros = new URLSearchParams();
    parametros.append("controlador", "Permisos");
    parametros.append("metodo", "eliminarPermiso");
    parametros.append("id_permiso", permisoId);

    fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then(response => response.text())
      .then(responseText => {
        console.log("Permiso eliminado:", responseText);
        if(responseText.trim() === "OK") {
          const permisoItem = document.getElementById("permiso-item-" + permisoId);
          if (permisoItem) {
            permisoItem.remove();
          }
        }
      })
      .catch(error => console.log("Error al eliminar permiso:", error));
}

/**
 * Crea un nuevo permiso para el menú indicado.
 * @param {number} menuId - El ID del menú al que se asignará el nuevo permiso.
 */
function crearPermiso(menuId) {
  const input = document.getElementById("permiso-new-" + menuId);
  const nuevoNombre = input.value;
  
  if (!nuevoNombre.trim()) {
      //alert("Por favor, ingrese un nombre para el permiso.");
      return;
  }
  
  const parametros = new URLSearchParams();
  parametros.append("controlador", "Permisos");
  parametros.append("metodo", "crearPermiso");
  parametros.append("nombre", nuevoNombre);
  parametros.append("id_menu", menuId);
  
  fetch("C_Frontal.php?" + parametros.toString(), { method: "POST" })
      .then(response => response.text())
      .then(responseText => {
          if (responseText.trim() === "OK") {
              //alert("Permiso creado correctamente.");
              
              // Limpiar el input
              input.value = "";

              // Obtener la lista de permisos de este menú (solo si no la vamos a actualizar con la función)
              const listaPermisos = document.getElementById("permiso-list-" + menuId);
              if (listaPermisos) {
                  // Crear un nuevo elemento de lista para el permiso
                  const nuevoElemento = document.createElement("li");
                  nuevoElemento.textContent = nuevoNombre; // Usamos el nombre del permiso recién creado
                  listaPermisos.appendChild(nuevoElemento);
              }
          } else {
              //alert("Error al crear permiso: " + responseText);
          }
      })
      .catch(error => console.log("Error al crear permiso:", error));
}



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