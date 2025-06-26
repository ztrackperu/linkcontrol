// ===========================
// VARIABLES GLOBALES
// ===========================
let contadorFormularios = 0;

// ===========================
// INICIALIZACIÓN
// ===========================
document.addEventListener("DOMContentLoaded", function () {
  initializeDateTime();
  initializeFormControls();
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
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: true,
  });

  const timeElement = document.getElementById("currentTime");
  if (timeElement) {
    timeElement.textContent = dateTimeString;
  }
}

// ===========================
// CONTROL DE FORMULARIOS
// ===========================
function initializeFormControls() {
  const tipoControl = document.getElementById("tipoControl");

  if (tipoControl) {
    tipoControl.addEventListener("change", mostrarFormulario);
  }
}

// ===========================
// GESTIÓN DE FORMULARIOS DINÁMICOS
// ===========================
function mostrarFormulario() {
  const tipoControl = document.getElementById("tipoControl").value;
  const btnAgregarUnico = document.getElementById("btnAgregarUnico");
  const botonesFormulario = document.getElementById("botonesFormulario");
  const contenedor = document.getElementById("contenedorFormularios");

  if (tipoControl) {
    showFormButtons(botonesFormulario);

    if (tipoControl === "unico") {
      handleUnicoType(btnAgregarUnico);
    } else if (tipoControl === "ciclico") {
      handleCiclicoType(btnAgregarUnico, contenedor);
    }
  } else {
    resetearFormularios();
  }
}

function showFormButtons(botonesFormulario) {
  if (botonesFormulario) {
    botonesFormulario.classList.remove("d-none");
  }
}

function handleUnicoType(btnAgregarUnico) {
  if (btnAgregarUnico) {
    btnAgregarUnico.classList.remove("d-none");
  }

  if (contadorFormularios === 0) {
    agregarFormularioUnico();
  }
}

function handleCiclicoType(btnAgregarUnico, contenedor) {
  if (btnAgregarUnico) {
    btnAgregarUnico.classList.add("d-none");
  }

  if (contenedor) {
    contenedor.innerHTML = "";
    agregarFormularioCiclico();
  }
}

// ===========================
// CREACIÓN DE FORMULARIOS
// ===========================
function agregarFormularioUnico() {
  contadorFormularios++;
  const template = document.getElementById("templateFormularioUnico");

  if (!template) return;

  const clone = template.cloneNode(true);
  configureUnicoClone(clone);
  appendToContainer(clone);
  scrollToElement(clone);
}

function configureUnicoClone(clone) {
  clone.id = `formulario-${contadorFormularios}`;
  clone.classList.remove("d-none");

  updateCloneTitle(clone);
  showDeleteButtonIfNeeded(clone);
}

function updateCloneTitle(clone) {
  const titulo = clone.querySelector(".titulo-control");
  if (titulo) {
    titulo.textContent = `Control Único #${contadorFormularios}`;
  }
}

function showDeleteButtonIfNeeded(clone) {
  if (contadorFormularios > 1) {
    const btnEliminar = clone.querySelector(".btn-eliminar");
    if (btnEliminar) {
      btnEliminar.classList.remove("d-none");
    }
  }
}

function agregarFormularioCiclico() {
  const template = document.getElementById("templateFormularioCiclico");

  if (!template) return;

  const clone = template.cloneNode(true);
  clone.id = "formulario-ciclico";
  clone.classList.remove("d-none");

  appendToContainer(clone);
}

function appendToContainer(element) {
  const contenedor = document.getElementById("contenedorFormularios");
  if (contenedor) {
    contenedor.appendChild(element);
  }
}

function scrollToElement(element) {
  element.scrollIntoView({ behavior: "smooth", block: "center" });
}

// ===========================
// ELIMINACIÓN Y ACTUALIZACIÓN
// ===========================
function eliminarFormulario(boton) {
  const formulario = boton.closest(".formulario-unico")?.parentElement;

  if (formulario) {
    formulario.remove();
    actualizarNumeracion();
  }
}

function actualizarNumeracion() {
  const formularios = document.querySelectorAll(
    "#contenedorFormularios .titulo-control"
  );

  formularios.forEach((titulo, index) => {
    titulo.textContent = `Control Único #${index + 1}`;
  });

  contadorFormularios = formularios.length;
}

// ===========================
// LIMPIEZA Y RESET
// ===========================
function limpiarFormulario() {
  const form = document.getElementById("frmControlAutomatico");
  if (form) {
    form.reset();
  }
  resetearFormularios();
}

function resetearFormularios() {
  const contenedor = document.getElementById("contenedorFormularios");
  const btnAgregarUnico = document.getElementById("btnAgregarUnico");
  const botonesFormulario = document.getElementById("botonesFormulario");

  if (contenedor) contenedor.innerHTML = "";
  if (btnAgregarUnico) btnAgregarUnico.classList.add("d-none");
  if (botonesFormulario) botonesFormulario.classList.add("d-none");

  contadorFormularios = 0;
}
