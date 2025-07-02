// ===========================
// VARIABLES GLOBALES
// ===========================
let contadorFormularios = 0;
let tipoControlActual = "";

// ===========================
// INICIALIZACIÓN
// ===========================
document.addEventListener("DOMContentLoaded", function () {
  initializeDateTime();
  initializeFormSubmit();
  ListarControl();
  ListarHistorico();
  cargarContadores();
});

// ===========================
// FUNCIONES DE FECHA Y HORA
// ===========================
function initializeDateTime() {
  updateCurrentTime();
  setInterval(updateCurrentTime, 1000);
}

function updateCurrentTime() {
  const now = new Date();
  const dateTimeString = now.toLocaleString("es-PE", {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour12: true,
  });
  const timeElement = document.getElementById("currentTime");
  if (timeElement) {
    timeElement.textContent = dateTimeString;
  }
}

// ===========================
// GESTIÓN DE FORMULARIOS DINÁMICOS
// ===========================
function mostrarFormulario() {
  const tipoControl = document.getElementById("tipoControl").value;
  const btnAgregar = document.getElementById("btnAgregar");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const contenedor = document.getElementById("contenedorFormularios");
  const campoFechaFin = document.getElementById("campoFechaFin");
  const campoProcesoPeriodico = document.getElementById(
    "campoProcesoPeriodico"
  );

  // Limpiar contenedor y resetear contador
  contenedor.innerHTML = "";
  contadorFormularios = 0;
  tipoControlActual = tipoControl;

  if (tipoControl) {
    // Mostrar elementos del formulario
    botonesFormulario.classList.remove("d-none");
    btnAgregar.classList.remove("d-none");

    // Mostrar/ocultar campos según el tipo
    if (tipoControl === "periodico") {
      if (campoProcesoPeriodico) {
        campoProcesoPeriodico.classList.remove("d-none");
        // ELIMINAR ESTA LÍNEA - NO habilitar/deshabilitar campos
        // habilitarValidacionCampos(campoProcesoPeriodico, true);
      }
      if (campoFechaFin) {
        campoFechaFin.classList.add("d-none");
        // ELIMINAR ESTA LÍNEA - NO habilitar/deshabilitar campos
        // habilitarValidacionCampos(campoFechaFin, false);
      }
      // Crear 2 formularios por defecto para periódico
      agregarFormulario();
      agregarFormulario();
      actualizarBotonesEliminar();
    } else {
      // Para único y cíclico: mostrar fecha fin
      if (campoFechaFin) {
        campoFechaFin.classList.remove("d-none");
        // ELIMINAR ESTA LÍNEA - NO habilitar/deshabilitar campos
        // habilitarValidacionCampos(campoFechaFin, true);
      }
      if (campoProcesoPeriodico) {
        campoProcesoPeriodico.classList.add("d-none");
        // ELIMINAR ESTA LÍNEA - NO habilitar/deshabilitar campos
        // habilitarValidacionCampos(campoProcesoPeriodico, false);
      }
      // Agregar primer formulario
      agregarFormulario();
    }
  } else {
    resetearFormularios();
  }
}

// NUEVA FUNCIÓN: Habilitar/deshabilitar validación de campos
function habilitarValidacionCampos(contenedor, habilitar) {
  if (!contenedor) return;

  const campos = contenedor.querySelectorAll(
    "input[required], select[required], textarea[required]"
  );
  campos.forEach((campo) => {
    if (habilitar) {
      campo.setAttribute("required", "");
      campo.disabled = true;
    } else {
      campo.removeAttribute("required");
      campo.disabled = false;
    }
  });
}

function agregarFormulario() {
  if (tipoControlActual === "unico") {
    agregarFormularioUnico();
  } else if (tipoControlActual === "ciclico") {
    agregarFormularioCiclico();
  } else if (tipoControlActual === "periodico") {
    agregarFormularioPeriodico();
  }
}

// ===========================
// CREACIÓN DE FORMULARIOS
// ===========================
function agregarFormularioUnico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioUnico");
  if (!template) return;

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = `formulario-unico-${contadorFormularios}`;
  actualizarTituloFormulario(clone, "Único");
  mostrarBotonEliminarSiEsNecesario(clone);
  document.getElementById("contenedorFormularios").appendChild(clone);
}

function agregarFormularioCiclico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioCiclico");
  if (!template) return;

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = `formulario-ciclico-${contadorFormularios}`;
  actualizarTituloFormulario(clone, "Cíclico");
  mostrarBotonEliminarSiEsNecesario(clone);
  document.getElementById("contenedorFormularios").appendChild(clone);
}

function agregarFormularioPeriodico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioPeriodico");
  if (!template) return;

  const clone = template.firstElementChild.cloneNode(true);
  clone.id = `formulario-periodico-${contadorFormularios}`;
  actualizarTituloFormulario(clone, "Periódico");
  mostrarBotonEliminarSiEsNecesario(clone);

  // Configurar valores por defecto para formulario periódico
  configurarValoresPorDefectoPeriodico(clone);

  document.getElementById("contenedorFormularios").appendChild(clone);
}

function configurarValoresPorDefectoPeriodico(clone) {
  // Configurar fecha y hora por defecto (fecha actual)
  const campoFechaHora = clone.querySelector('input[name="fechaHoraInicio[]"]');
  if (campoFechaHora) {
    const ahora = new Date();
    const fechaFormateada = ahora.toISOString().slice(0, 16);
    campoFechaHora.value = fechaFormateada;
  }

  // Configurar nombre de etapa por defecto
  const campoEtapa = clone.querySelector('input[name="etapa[]"]');
  if (campoEtapa) {
    campoEtapa.value = `Etapa ${contadorFormularios}`;
  }

  // Configurar temperatura por defecto
  const campoTemperatura = clone.querySelector('input[name="temperatura[]"]');
  if (campoTemperatura) {
    campoTemperatura.value = 20.0;
  }

  // Configurar humedad por defecto
  const campoHumedad = clone.querySelector('input[name="humedad[]"]');
  if (campoHumedad) {
    campoHumedad.value = 50;
  }

  // Configurar duración por defecto
  const campoDuracion = clone.querySelector('input[name="duracion[]"]');
  if (campoDuracion) {
    campoDuracion.value = 2.0; // 2 horas por defecto
  }
}

function actualizarTituloFormulario(clone, tipo) {
  const titulo = clone.querySelector(".titulo-control");
  if (titulo) {
    titulo.textContent = `Control ${tipo} #${contadorFormularios}`;
  }
}

function mostrarBotonEliminarSiEsNecesario(clone) {
  if (contadorFormularios > 1) {
    const btnEliminar = clone.querySelector(".btn-eliminar");
    if (btnEliminar) {
      btnEliminar.classList.remove("d-none");
    }
  }
}

// ===========================
// ELIMINACIÓN Y ACTUALIZACIÓN
// ===========================
function eliminarFormulario(boton) {
  const formulario = boton.closest(".card");
  if (formulario) {
    formulario.remove();
    actualizarNumeracion();
  }
}

function actualizarNumeracion() {
  const formularios = document.querySelectorAll(
    "#contenedorFormularios .titulo-control"
  );
  let tipoTexto = "Control";

  if (tipoControlActual === "unico") {
    tipoTexto = "Único";
  } else if (tipoControlActual === "ciclico") {
    tipoTexto = "Cíclico";
  } else if (tipoControlActual === "periodico") {
    tipoTexto = "Periódico";
  }

  formularios.forEach((titulo, index) => {
    titulo.textContent = `Control ${tipoTexto} #${index + 1}`;
  });

  contadorFormularios = formularios.length;

  // Ocultar botón eliminar si solo hay un formulario
  if (contadorFormularios === 1) {
    const btnEliminar = document.querySelector(".btn-eliminar");
    if (btnEliminar) {
      btnEliminar.classList.add("d-none");
    }
  }
}

// ===========================
// LIMPIEZA Y RESET
// ===========================
function mostrarFormulario() {
  const tipoControl = document.getElementById("tipoControl").value;
  const btnAgregar = document.getElementById("btnAgregar");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const contenedor = document.getElementById("contenedorFormularios");
  const campoFechaFin = document.getElementById("campoFechaFin");
  const campoProcesoPeriodico = document.getElementById(
    "campoProcesoPeriodico"
  );

  // Limpiar contenedor y resetear contador
  contenedor.innerHTML = "";
  contadorFormularios = 0;
  tipoControlActual = tipoControl;

  if (tipoControl) {
    // Mostrar elementos del formulario
    botonesFormulario.classList.remove("d-none");
    btnAgregar.classList.remove("d-none");

    // Mostrar/ocultar campos según el tipo
    if (tipoControl === "periodico") {
      if (campoProcesoPeriodico) {
        campoProcesoPeriodico.classList.remove("d-none");
        habilitarValidacionCampos(campoProcesoPeriodico, true);
      }
      if (campoFechaFin) {
        campoFechaFin.classList.add("d-none");
        habilitarValidacionCampos(campoFechaFin, false);
      }

      // Crear 2 formularios por defecto para periódico
      agregarFormulario();
      agregarFormulario();

      // IMPORTANTE: Después de crear los 2 formularios por defecto,
      // ocultar botones eliminar porque son el mínimo requerido
      actualizarBotonesEliminar();
    } else {
      if (campoFechaFin) {
        campoFechaFin.classList.remove("d-none");
        habilitarValidacionCampos(campoFechaFin, true);
      }
      if (campoProcesoPeriodico) {
        campoProcesoPeriodico.classList.add("d-none");
        habilitarValidacionCampos(campoProcesoPeriodico, false);
      }

      // Agregar primer formulario
      agregarFormulario();
    }
  } else {
    resetearFormularios();
  }
}

function resetearFormularios() {
  const contenedor = document.getElementById("contenedorFormularios");
  const btnAgregar = document.getElementById("btnAgregar");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const campoFechaFin = document.getElementById("campoFechaFin");
  const campoProcesoPeriodico = document.getElementById(
    "campoProcesoPeriodico"
  );

  if (contenedor) contenedor.innerHTML = "";
  if (btnAgregar) btnAgregar.classList.add("d-none");
  if (botonesFormulario) botonesFormulario.classList.add("d-none");

  if (campoFechaFin) {
    campoFechaFin.classList.add("d-none");
    // DESHABILITAR validación al resetear
    habilitarValidacionCampos(campoFechaFin, false);
  }

  if (campoProcesoPeriodico) {
    campoProcesoPeriodico.classList.add("d-none");
    // DESHABILITAR validación al resetear
    habilitarValidacionCampos(campoProcesoPeriodico, false);
  }

  contadorFormularios = 0;
  tipoControlActual = "";
}

function limpiarFormulario() {
  const form = document.getElementById("frmControlAutomatico");
  if (form) {
    form.reset();
  }

  // LIMPIAR MANUALMENTE TODOS LOS CAMPOS ESPECÍFICOS

  // Limpiar campo tipo de control
  const tipoControl = document.getElementById("tipoControl");
  if (tipoControl) {
    tipoControl.value = "";
  }

  // Limpiar campos periódicos
  const nombreProcesoPeriodico = document.querySelector(
    'input[name="nombreProcesoPeriodico"]'
  );
  if (nombreProcesoPeriodico) {
    nombreProcesoPeriodico.value = "";
  }

  const horasProceso = document.querySelector('input[name="horasProceso"]');
  if (horasProceso) {
    horasProceso.value = "";
  }

  // Limpiar campos de otros tipos
  const nombrep = document.querySelector('input[name="nombrep"]');
  if (nombrep) {
    nombrep.value = "";
  }

  const fechaHoraFin = document.getElementById("fechaHoraFin");
  if (fechaHoraFin) {
    fechaHoraFin.value = "";
  }

  // Limpiar todos los campos de arrays (etapas dinámicas)
  const camposArray = document.querySelectorAll(
    'input[name*="[]"], select[name*="[]"], textarea[name*="[]"]'
  );
  camposArray.forEach((campo) => {
    campo.value = "";
  });

  // Resetear formularios dinámicos
  resetearFormularios();
}

function resetearFormularios() {
  const contenedor = document.getElementById("contenedorFormularios");
  const btnAgregar = document.getElementById("btnAgregar");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const campoFechaFin = document.getElementById("campoFechaFin");
  const campoProcesoPeriodico = document.getElementById(
    "campoProcesoPeriodico"
  );

  if (contenedor) contenedor.innerHTML = "";
  if (btnAgregar) btnAgregar.classList.add("d-none");
  if (botonesFormulario) botonesFormulario.classList.add("d-none");

  if (campoFechaFin) {
    campoFechaFin.classList.add("d-none");
    // ELIMINAR ESTA LÍNEA - NO bloquear campos
    // habilitarValidacionCampos(campoFechaFin, false);
  }

  if (campoProcesoPeriodico) {
    campoProcesoPeriodico.classList.add("d-none");
    // ELIMINAR ESTA LÍNEA - NO bloquear campos
    // habilitarValidacionCampos(campoProcesoPeriodico, false);
  }

  contadorFormularios = 0;
  tipoControlActual = "";
}

// ===========================
// VALIDACIÓN DE FECHA FIN Y CAMPOS PERIÓDICOS
// ===========================
function validarFechaFin() {
  // Para periódico no validar fecha fin ya que se calcula automáticamente
  if (tipoControlActual === "periodico") {
    return validarCamposPeriodico();
  }

  const fechaFin = document.getElementById("fechaHoraFin").value;
  if (!fechaFin) {
    mostrarModalError("La fecha de finalización del proceso es obligatoria");
    return false;
  }

  const fechaFinDate = new Date(fechaFin);
  const ahora = new Date();

  if (fechaFinDate <= ahora) {
    mostrarModalError(
      "La fecha de finalización debe ser posterior a la fecha actual"
    );
    return false;
  }

  // Validar que la fecha fin sea posterior a todas las fechas de inicio
  const fechasInicio = document.querySelectorAll(
    'input[name="fechaHoraInicio[]"]'
  );
  for (let i = 0; i < fechasInicio.length; i++) {
    const fechaInicio = new Date(fechasInicio[i].value);
    if (fechaFinDate <= fechaInicio) {
      mostrarModalError(
        `La fecha de finalización debe ser posterior a la fecha de inicio de la etapa ${
          i + 1
        }`
      );
      return false;
    }
  }

  return true;
}

function validarCamposPeriodico() {
  // CORREGIR: Usar los name correctos del HTML
  const nombreProceso = document.querySelector(
    'input[name="nombreProcesoPeriodico"]'
  );
  if (!nombreProceso || !nombreProceso.value.trim()) {
    mostrarModalError("El nombre del proceso periódico es obligatorio");
    return false;
  }

  // Validar horas del proceso
  const horasProceso = document.querySelector('input[name="horasProceso"]');
  if (
    !horasProceso ||
    !horasProceso.value ||
    parseFloat(horasProceso.value) <= 0
  ) {
    mostrarModalError("Las horas del proceso deben ser mayor a 0");
    return false;
  }

  // Validar que todos los campos de las etapas estén completos
  const formularios = document.querySelectorAll("#contenedorFormularios .card");
  for (let i = 0; i < formularios.length; i++) {
    const formulario = formularios[i];
    const etapa = formulario.querySelector('input[name="etapa[]"]');
    const fechaHora = formulario.querySelector(
      'input[name="fechaHoraInicio[]"]'
    );
    const temperatura = formulario.querySelector('input[name="temperatura[]"]');
    const humedad = formulario.querySelector('input[name="humedad[]"]');
    const duracion = formulario.querySelector('input[name="duracion[]"]');

    if (!etapa.value.trim()) {
      mostrarModalError(`El nombre de la etapa ${i + 1} es obligatorio`);
      return false;
    }

    if (!fechaHora.value) {
      mostrarModalError(`La fecha y hora de la etapa ${i + 1} es obligatoria`);
      return false;
    }

    if (!temperatura.value || isNaN(parseFloat(temperatura.value))) {
      mostrarModalError(
        `La temperatura de la etapa ${i + 1} debe ser un número válido`
      );
      return false;
    }

    if (
      !humedad.value ||
      isNaN(parseFloat(humedad.value)) ||
      parseFloat(humedad.value) < 0 ||
      parseFloat(humedad.value) > 100
    ) {
      mostrarModalError(
        `La humedad de la etapa ${i + 1} debe estar entre 0 y 100`
      );
      return false;
    }

    if (
      !duracion.value ||
      isNaN(parseFloat(duracion.value)) ||
      parseFloat(duracion.value) <= 0
    ) {
      mostrarModalError(`La duración de la etapa ${i + 1} debe ser mayor a 0`);
      return false;
    }
  }

  return true;
}

// ===========================
// MANEJO DEL FORMULARIO
// ===========================
function initializeFormSubmit() {
  const form = document.getElementById("frmControlAutomatico");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      // Validar fecha fin antes de procesar
      if (validarFechaFin()) {
        procesarFormulario();
      }
    });
  }
}

function ListarControl() {
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/listar";
  http.open("POST", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      mostrarControlesEnTabla(res);
    }
  };
}

function mostrarControlesEnTabla(data) {
  const tbody = document.getElementById("contenidoTabla");
  tbody.innerHTML = data;
}

function procesarFormulario() {
  const formData = new FormData(
    document.getElementById("frmControlAutomatico")
  );
  // Agregar información adicional
  formData.append("tipoControlActual", tipoControlActual);
  formData.append("totalFormularios", contadorFormularios);

  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/crear";
  http.open("POST", url, true);
  http.send(formData);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Respuesta recibida:", this.responseText);
      try {
        const res = JSON.parse(this.responseText);
        console.log("JSON parseado:", res);
        if (res.success) {
          console.log(
            "Condición de éxito cumplida, llamando mostrarModalExito"
          );
          mostrarModalExito(res.message);
          limpiarFormulario();
          ListarControl();
          ListarHistorico();
        } else if (res.type === "control_activo") {
          console.log("Control activo detectado");
          // GUARDAR LAS VARIABLES GLOBALES CORRECTAMENTE
          window.nuevoControlData = res.nuevo_control;
          window.idControlActivo = res.id_control_activo;
          console.log("Variables guardadas:");
          console.log("idControlActivo:", window.idControlActivo);
          console.log("nuevoControlData:", window.nuevoControlData);
          mostrarModalAdvertencia(res.html_detalles);
        } else {
          console.log("Error detectado");
          mostrarModalError(res.message);
        }
      } catch (error) {
        console.error("Error al parsear JSON:", error);
      }
    }
  };
}

function mostrarModalExito(mensaje) {
  document.getElementById("mensajeExito").textContent = mensaje;
  const modal = new bootstrap.Modal(document.getElementById("modalExito"));
  modal.show();
}

function mostrarModalError(mensaje) {
  document.getElementById("mensajeError").textContent = mensaje;
  const modal = new bootstrap.Modal(document.getElementById("modalError"));
  modal.show();
}

function mostrarModalAdvertencia(htmlDetalles) {
  // Solo insertar el HTML construido en el controlador
  document.getElementById("contenidoWarning").innerHTML = htmlDetalles;
  // Mostrar modal
  const modal = new bootstrap.Modal(document.getElementById("modalWarning"));
  modal.show();
}

function verControl(idControl) {
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/verControl";
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  const params = "id_control=" + idControl;
  http.send(params);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res.success) {
        // Insertar el HTML en el modal
        document.getElementById("contenidoVerControl").innerHTML = res.html;
        // Mostrar el modal
        const modal = new bootstrap.Modal(
          document.getElementById("modalVerControl")
        );
        modal.show();
      } else {
        alert("Error: " + res.message);
      }
    }
  };
}

function eliminarControl(idControl) {
  // Guardar el ID en el campo oculto
  document.getElementById("idControlEliminar").value = idControl;
  // Mostrar el modal usando Bootstrap
  const modal = new bootstrap.Modal(
    document.getElementById("modalEliminarControl")
  );
  modal.show();
}

function confirmarEliminacion() {
  const idControl = document.getElementById("idControlEliminar").value;
  if (!idControl) {
    alert("Error: ID de control no válido");
    return;
  }

  // Cerrar el modal de confirmación
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalEliminarControl")
  );
  modal.hide();

  // Realizar la eliminación
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/eliminarControl";
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  const params = "id_control=" + idControl;
  http.send(params);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res.success) {
        // Mostrar modal de éxito
        const modalExito = new bootstrap.Modal(
          document.getElementById("modalExitoEliminacion")
        );
        modalExito.show();
        // Cerrar automáticamente después de 2 segundos y recargar
        setTimeout(function () {
          modalExito.hide();
          location.reload();
        }, 2000);
      } else {
        alert("Error: " + res.message);
      }
    }
  };
}

// Agregar esta función al archivo Automatico.js
function eliminarControlActivoYContinuar() {
  console.log("=== DEBUGGING eliminarControlActivoYContinuar ===");
  console.log("idControlActivo:", window.idControlActivo);
  console.log("nuevoControlData:", window.nuevoControlData);

  if (!window.idControlActivo || !window.nuevoControlData) {
    console.error("❌ Datos no disponibles");
    console.log("idControlActivo existe:", !!window.idControlActivo);
    console.log("nuevoControlData existe:", !!window.nuevoControlData);
    mostrarModalError("Error: Datos del control no disponibles");
    return;
  }

  console.log("✅ Datos disponibles, procediendo con eliminación");

  // Cerrar el modal de advertencia primero
  const modalWarning = bootstrap.Modal.getInstance(
    document.getElementById("modalWarning")
  );
  if (modalWarning) {
    modalWarning.hide();
  }

  // Eliminar el control activo
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/eliminarControl";
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  const params = "id_control=" + window.idControlActivo;
  console.log("Enviando parámetros:", params);
  http.send(params);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Respuesta eliminación:", this.responseText);
      try {
        const res = JSON.parse(this.responseText);
        if (res.success) {
          console.log("✅ Control eliminado, creando nuevo");
          // Control eliminado exitosamente, ahora crear el nuevo
          crearNuevoControlDespuesDeEliminar();
        } else {
          console.error("❌ Error al eliminar:", res.message);
          mostrarModalError(
            "Error al eliminar el control activo: " + res.message
          );
        }
      } catch (error) {
        console.error("Error al parsear respuesta de eliminación:", error);
        mostrarModalError("Error en la respuesta del servidor");
      }
    }
  };
}

function crearNuevoControlDespuesDeEliminar() {
  console.log("=== DEBUGGING crearNuevoControlDespuesDeEliminar ===");
  console.log("nuevoControlData:", window.nuevoControlData);

  if (!window.nuevoControlData) {
    mostrarModalError("Error: Datos del nuevo control no disponibles");
    return;
  }

  // Recrear el FormData con los datos del nuevo control
  const formData = new FormData();

  // Determinar el tipo de control
  let tipoControl = "unico";
  if (window.nuevoControlData.tipo_control_temperatura === 1) {
    tipoControl = "ciclico";
  } else if (window.nuevoControlData.tipo_control_temperatura === 2) {
    tipoControl = "periodico";
  }

  formData.append("tipoControl", tipoControl);

  // Agregar campos según el tipo de control
  if (tipoControl === "periodico") {
    // Para periódico: agregar nombre del proceso y horas
    formData.append(
      "nombreProcesoPeriodico",
      window.nuevoControlData.proceso_control_temperatura
    );
    formData.append(
      "horasProceso",
      window.nuevoControlData.horas_proceso || 24
    );
  } else {
    // Para único y cíclico: agregar nombre del proceso y fecha fin
    formData.append(
      "nombrep",
      window.nuevoControlData.proceso_control_temperatura
    );
    formData.append(
      "fechaHoraFin",
      convertirFechaParaFormulario(
        window.nuevoControlData.hora_fin_control_temperatura
      )
    );
  }

  // Agregar las etapas
  window.nuevoControlData.lista_control_temperatura.forEach((etapa, index) => {
    formData.append("etapa[]", etapa.nombre_etapa);
    formData.append("temperatura[]", etapa.temperatura_etapa);

    if (tipoControl === "unico") {
      // Para único, usar fechaHoraInicio
      formData.append(
        "fechaHoraInicio[]",
        convertirFechaParaFormulario(etapa.hora_inicio_etapa)
      );
    } else if (tipoControl === "ciclico") {
      // Para cíclico, usar solo hora
      const horaExtraida = extraerHoraDeFecha(etapa.hora_inicio_etapa);
      formData.append("hora[]", horaExtraida);
    } else if (tipoControl === "periodico") {
      // Para periódico, usar fechaHoraInicio, humedad y duración
      formData.append(
        "fechaHoraInicio[]",
        convertirFechaParaFormulario(etapa.hora_inicio_etapa)
      );
      formData.append("humedad[]", etapa.humedad_etapa || 50);
      formData.append("duracion[]", etapa.duracion_etapa || 2.0);
    }
  });

  console.log("FormData creado, enviando petición");

  // Enviar la petición para crear el nuevo control
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/crear";
  http.open("POST", url, true);
  http.send(formData);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Respuesta creación nuevo control:", this.responseText);
      try {
        const res = JSON.parse(this.responseText);
        if (res.success) {
          mostrarModalExito(
            "Control anterior eliminado y nuevo control creado exitosamente"
          );
          limpiarFormulario();
          ListarControl();
          // Limpiar variables globales
          window.idControlActivo = null;
          window.nuevoControlData = null;
        } else {
          mostrarModalError(
            "Control eliminado pero error al crear el nuevo: " + res.message
          );
        }
      } catch (error) {
        console.error("Error al parsear respuesta de creación:", error);
        mostrarModalError("Error en la respuesta del servidor");
      }
    }
  };
}

// Funciones auxiliares para convertir fechas
function convertirFechaParaFormulario(fechaCustom) {
  // Convertir de "25-06-2025_17-42" a "2025-06-25T17:42"
  if (!fechaCustom || fechaCustom === "No definida") {
    return "";
  }
  const regex = /(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/;
  const match = fechaCustom.match(regex);
  if (match) {
    const [, dia, mes, año, hora, minuto] = match;
    return `${año}-${mes}-${dia}T${hora}:${minuto}`;
  }
  return fechaCustom;
}

function extraerHoraDeFecha(fechaCustom) {
  // Extraer solo la hora de "25-06-2025_17-42" -> "17:42"
  if (!fechaCustom) {
    return "";
  }
  const regex = /(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/;
  const match = fechaCustom.match(regex);
  if (match) {
    const [, , , , hora, minuto] = match;
    return `${hora}:${minuto}`;
  }
  return "";
}

function ListarHistorico() {
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/listarHistorico";
  http.open("POST", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      mostrarHistoricoEnTabla(res);
    }
  };
}

function mostrarHistoricoEnTabla(data) {
  const tbody = document.getElementById("contenidoTablaHistorico");
  tbody.innerHTML = data;
}

// Agregar esta función
function cargarContadores() {
  const http = new XMLHttpRequest();
  const url = base_url + "Automatico/obtenerContadores";
  http.open("POST", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      try {
        const res = JSON.parse(this.responseText);
        if (res.success) {
          actualizarContadoresEnVista(res.data);
        } else {
          console.error("Error al cargar contadores:", res.message);
        }
      } catch (error) {
        console.error("Error al parsear contadores:", error);
      }
    }
  };
}

function actualizarContadoresEnVista(data) {
  // Actualizar contadores generales
  document.getElementById("totalCreados").textContent = data.general.creado;
  document.getElementById("totalEliminados").textContent =
    data.general.eliminado;
  document.getElementById("totalReestablecidos").textContent =
    data.general.reestablecido;

  // Actualizar contadores de hoy
  document.getElementById("creadosHoy").textContent = data.hoy.creado;
  document.getElementById("eliminadosHoy").textContent = data.hoy.eliminado;
  document.getElementById("reestablecidosHoy").textContent =
    data.hoy.reestablecido;

  // AGREGAR ESTAS LÍNEAS PARA MOSTRAR LAS FECHAS:
  // Formatear fechas desde created_at y updated_at
  const fechaInicio = data.created_at
    ? new Date(data.created_at).toLocaleDateString("es-PE")
    : "N/A";
  const fechaFin = data.updated_at
    ? new Date(data.updated_at).toLocaleDateString("es-PE")
    : "N/A";

  // Mostrar rango de fechas generales
  const rangoGeneral = `(${fechaInicio} - ${fechaFin})`;
  document.querySelectorAll(".fecha-general").forEach((el) => {
    el.textContent = rangoGeneral;
  });

  // Mostrar fecha de hoy formateada
  const fechaHoyFormateada = `(${data.fecha_actual_formateada})`;
  document.querySelectorAll(".fecha-hoy").forEach((el) => {
    el.textContent = fechaHoyFormateada;
  });
}

function actualizarBotonesEliminar() {
  const contenedor = document.getElementById("contenedorFormularios");
  const totalFormularios = contenedor.children.length;
  const botonesEliminar = contenedor.querySelectorAll(".btn-eliminar");

  botonesEliminar.forEach((boton) => {
    if (tipoControlActual === "periodico") {
      // Para periódico: ocultar botón si solo hay 2 formularios
      if (totalFormularios <= 2) {
        boton.style.display = "none";
      } else {
        boton.style.display = "inline-block";
      }
    } else {
      // Para único/cíclico: ocultar botón si solo hay 1 formulario
      if (totalFormularios <= 1) {
        boton.style.display = "none";
      } else {
        boton.style.display = "inline-block";
      }
    }
  });
}
