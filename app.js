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
    const options = document.getElementById(`options-${menuId}`)
    const allOptions = document.querySelectorAll(".menu-options")
    allOptions.forEach((opt) => {
      if (opt !== options) opt.style.display = "none" // Oculta otras opciones
    })
    options.style.display = options.style.display === "none" ? "block" : "none"
  }
  
  function buscarRol() {
    const selectRol = document.getElementById("frol")
    const selectedRoleId = selectRol ? selectRol.value : ""
  
    const selectUsuario = document.getElementById("fusuario")
    const selectedUserId = selectUsuario ? selectUsuario.value : ""
  
    console.log("🔍 ID del Rol seleccionado:", selectedRoleId)
    console.log("🔍 ID del Usuario seleccionado:", selectedUserId)
  
    const parametros = new URLSearchParams()
    parametros.append("controlador", "Menu")
    parametros.append("metodo", "getVistaListadoMenu")
    parametros.append("frol", selectedRoleId)
    parametros.append("fusuario", selectedUserId)
  
    const opciones = { method: "GET" }
  
    fetch("C_Frontal.php?" + parametros.toString(), opciones)
      .then((res) => res.text())
      .then((vista) => {
        document.getElementById("capaResultadoBusqueda").innerHTML = vista
      })
      .catch((error) => console.log("❌ Error al buscar menús y permisos:", error.message))
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
  
      // 🚫 Deshabilitar rol si se selecciona un usuario
    //   if (event.target.value !== "") {
    //     selectRol.disabled = true // ❌ Deshabilitar rol
    //   } else {
    //     selectRol.disabled = false // ✅ Volver a habilitar si se quita el usuario
    //   }
    }
  })
  
  function guardarRol() {
    const formData = new FormData(document.getElementById("formularioNuevoEditar"))
  
    const opciones = {
      method: "POST",
      body: formData,
    }
  
    fetch("C_Frontal.php?controlador=Roles&metodo=guardarRol", opciones)
      .then((res) => res.json())
      .then((respuesta) => {
        if (respuesta.correcto === "S") {
          alert(respuesta.msj)
          obtenerVista("Menu", "getVistaListadoMenu", "capaResultadoBusqueda")
          document.getElementById("capaEditarCrear").innerHTML = ""
        } else {
          document.getElementById("msjError").innerText = respuesta.msj
        }
      })
      .catch((error) => {
        console.error("Error al guardar rol:", error)
        document.getElementById("msjError").innerText = "Error al procesar la solicitud."
      })
  }
  
//   function eliminarRol(id) {
//     if (confirm("¿Está seguro de que desea eliminar este rol?")) {
//       fetch(`C_Frontal.php?controlador=Roles&metodo=eliminarRol&id=${id}`, { method: "POST" })
//         .then((res) => res.json())
//         .then((respuesta) => {
//           if (respuesta.correcto === "S") {
//             alert(respuesta.msj)
//             obtenerVista("Menu", "getVistaFiltros", "capaContenido");
//           } else {
//             alert(respuesta.msj)
//           }
//         })
//         .catch((error) => {
//           console.error("Error al eliminar rol:", error)
//           alert("Error al procesar la solicitud.")
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
      if (confirm("¿Está seguro de que desea eliminar este rol?")) {
        fetch(`C_Frontal.php?controlador=Roles&metodo=eliminarRol&id=${rolId}`, { method: "POST" })
          .then((res) => res.json())
          .then((respuesta) => {
            if (respuesta.correcto === "S") {
              alert(respuesta.msj);
  
              // Actualizar la vista de filtros después de eliminar el rol
              obtenerVista("Menu", "getVistaFiltros", "capaContenido");
  
              // Cerrar el formulario de edición/creación
              document.getElementById("capaEditarCrear").innerHTML = "";
  
              // Opcional: Resetear el select de roles después de eliminar
              selectRol.value = "";
            } else {
              alert(respuesta.msj);
            }
          })
          .catch((error) => {
            console.error("❌ Error al eliminar rol:", error);
            alert("Error al procesar la solicitud.");
          });
      }
    } else {
      alert("Por favor, seleccione un rol para eliminar.");
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
      alert("Por favor, seleccione un rol para editar.")
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
                alert(respuesta.msj);
                
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
    
        const rolId = selectRol.value;
        const usuarioId = selectUsuario.value;
    
        if (!rolId || !usuarioId) {
            alert("Por favor, seleccione un rol y un usuario.");
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
                    alert("✅ " + respuesta.msj);
                } else {
                    alert("⚠️ " + respuesta.msj);
                }
            })
            .catch((error) => {
                console.error("❌ Error en la solicitud:", error);
                alert("Error al procesar la solicitud.");
            });
    }

    function desasignarRolAUsuario() {
        const selectRol = document.getElementById("frol");
        const selectUsuario = document.getElementById("fusuario");
    
        const rolId = selectRol.value;
        const usuarioId = selectUsuario.value;
    
        if (!rolId || !usuarioId) {
            alert("Por favor, seleccione un rol y un usuario.");
            return;
        }
    
        if (!confirm("¿Estás seguro de que deseas quitar este rol del usuario?")) {
            return;
        }
    
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
                    alert("✅ " + respuesta.msj);
                } else {
                    alert("⚠️ " + respuesta.msj);
                }
            })
            .catch((error) => {
                console.error("❌ Error en la solicitud:", error);
                alert("Error al procesar la solicitud.");
            });
    }
    
    
    
    