// ===========================
// MÓDULO DE GESTIÓN DE FORMULARIOS
// ===========================
class FormManager {
  constructor() {
    this.bindEvents();
  }

  bindEvents() {
    const form = document.getElementById("frmControlAutomatico");
    if (form) {
      form.addEventListener("submit", (e) => this.handleSubmit(e));
    }
  }

  handleSubmit(e) {
    e.preventDefault();
    if (App.validation.validarFechaFin()) {
      this.procesarFormulario();
    }
  }

  agregarFormulario() {
    switch (STATE.tipoControlActual) {
      case CONFIG.TIPOS_CONTROL.UNICO:
        this.agregarFormularioUnico();
        break;
      case CONFIG.TIPOS_CONTROL.CICLICO:
        this.agregarFormularioCiclico();
        break;
      case CONFIG.TIPOS_CONTROL.PERIODICO:
        this.agregarFormularioPeriodico();
        break;
    }
  }

  agregarFormularioUnico() {
    STATE.contadorFormularios++;
    const template = document.getElementById("templateFormularioUnico");
    if (!template) return;

    const clone = template.firstElementChild.cloneNode(true);
    clone.id = `formulario-unico-${STATE.contadorFormularios}`;
    this.actualizarTituloFormulario(clone, "Único");
    this.mostrarBotonEliminarSiEsNecesario(clone);
    document.getElementById("contenedorFormularios").appendChild(clone);
  }

  agregarFormularioCiclico() {
    STATE.contadorFormularios++;
    const template = document.getElementById("templateFormularioCiclico");
    if (!template) return;

    const clone = template.firstElementChild.cloneNode(true);
    clone.id = `formulario-ciclico-${STATE.contadorFormularios}`;
    this.actualizarTituloFormulario(clone, "Cíclico");
    this.mostrarBotonEliminarSiEsNecesario(clone);
    document.getElementById("contenedorFormularios").appendChild(clone);
  }

  agregarFormularioPeriodico() {
    STATE.contadorFormularios++;
    const template = document.getElementById("templateFormularioPeriodico");
    if (!template) return;

    const clone = template.firstElementChild.cloneNode(true);
    clone.id = `formulario-periodico-${STATE.contadorFormularios}`;
    this.actualizarTituloFormulario(clone, "Periódico");
    this.mostrarBotonEliminarSiEsNecesario(clone);
    this.configurarValoresPorDefectoPeriodico(clone);
    document.getElementById("contenedorFormularios").appendChild(clone);
  }

  configurarValoresPorDefectoPeriodico(clone) {
    const campoFechaHora = clone.querySelector(
      'input[name="fechaHoraInicio[]"]'
    );
    // if (campoFechaHora) {
    //   const ahora = new Date();
    //   const fechaFormateada = ahora.toISOString().slice(0, 16);
    //   campoFechaHora.value = fechaFormateada;
    // }

    const campoEtapa = clone.querySelector('input[name="etapa[]"]');
    if (campoEtapa) {
      campoEtapa.value = `Etapa ${STATE.contadorFormularios}`;
    }
  }

  actualizarTituloFormulario(clone, tipo) {
    const titulo = clone.querySelector(".titulo-control");
    if (titulo) {
      titulo.textContent = `Control ${tipo} #${STATE.contadorFormularios}`;
    }
  }

  mostrarBotonEliminarSiEsNecesario(clone) {
    if (STATE.contadorFormularios > 1) {
      const btnEliminar = clone.querySelector(".btn-eliminar");
      if (btnEliminar) {
        btnEliminar.classList.remove("d-none");
      }
    }
  }

  eliminarFormulario(boton) {
    const formulario = boton.closest(".card");
    if (formulario) {
      formulario.remove();
      this.actualizarNumeracion();
    }
  }

  actualizarNumeracion() {
    const formularios = document.querySelectorAll(
      "#contenedorFormularios .titulo-control"
    );
    let tipoTexto = "Control";

    switch (STATE.tipoControlActual) {
      case CONFIG.TIPOS_CONTROL.UNICO:
        tipoTexto = "Único";
        break;
      case CONFIG.TIPOS_CONTROL.CICLICO:
        tipoTexto = "Cíclico";
        break;
      case CONFIG.TIPOS_CONTROL.PERIODICO:
        tipoTexto = "Periódico";
        break;
    }

    formularios.forEach((titulo, index) => {
      titulo.textContent = `Control ${tipoTexto} #${index + 1}`;
    });

    STATE.contadorFormularios = formularios.length;

    if (STATE.contadorFormularios === 1) {
      const btnEliminar = document.querySelector(".btn-eliminar");
      if (btnEliminar) {
        btnEliminar.classList.add("d-none");
      }
    }
  }

  mostrarFormulario() {
    const tipoControl = document.getElementById("tipoControl").value;
    const btnAgregar = document.getElementById("btnAgregar");
    const botonesFormulario = document.getElementById("botonesFormulario");
    const contenedor = document.getElementById("contenedorFormularios");
    const campoFechaFin = document.getElementById("campoFechaFin");
    const campoProcesoPeriodico = document.getElementById(
      "campoProcesoPeriodico"
    );

    contenedor.innerHTML = "";
    STATE.contadorFormularios = 0;
    STATE.tipoControlActual = tipoControl;

    if (tipoControl) {
      botonesFormulario.classList.remove("d-none");
      btnAgregar.classList.remove("d-none");

      if (tipoControl === CONFIG.TIPOS_CONTROL.PERIODICO) {
        if (campoProcesoPeriodico) {
          campoProcesoPeriodico.classList.remove("d-none");
          this.habilitarValidacionCampos(campoProcesoPeriodico, true);
        }
        if (campoFechaFin) {
          campoFechaFin.classList.add("d-none");
          this.habilitarValidacionCampos(campoFechaFin, false);
        }

        this.agregarFormulario();
        this.agregarFormulario();
        this.actualizarBotonesEliminar();
      } else {
        if (campoFechaFin) {
          campoFechaFin.classList.remove("d-none");
          this.habilitarValidacionCampos(campoFechaFin, true);
        }
        if (campoProcesoPeriodico) {
          campoProcesoPeriodico.classList.add("d-none");
          this.habilitarValidacionCampos(campoProcesoPeriodico, false);
        }
        this.agregarFormulario();
      }
    } else {
      this.resetearFormularios();
    }
  }

  habilitarValidacionCampos(contenedor, habilitar) {
    if (!contenedor) return;
    const campos = contenedor.querySelectorAll(
      "input[required], select[required], textarea[required], input, select, textarea"
    );
    campos.forEach((campo) => {
      if (habilitar) {
        campo.setAttribute("required", "");
        campo.disabled = false;
      } else {
        campo.removeAttribute("required");
        campo.disabled = true;
      }
    });
  }

  resetearFormularios() {
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
    if (campoFechaFin) campoFechaFin.classList.add("d-none");
    if (campoProcesoPeriodico) campoProcesoPeriodico.classList.add("d-none");

    STATE.contadorFormularios = 0;
    STATE.tipoControlActual = "";
  }

  limpiarFormulario() {
    const form = document.getElementById("frmControlAutomatico");
    if (form) {
      form.reset();
    }

    // Limpiar campos específicos
    const campos = [
      { selector: "#tipoControl", value: "" },
      { selector: 'input[name="nombreProcesoPeriodico"]', value: "" },
      { selector: 'input[name="horasProceso"]', value: "" },
      { selector: 'input[name="nombrep"]', value: "" },
      { selector: "#fechaHoraFin", value: "" },
    ];

    campos.forEach((campo) => {
      const elemento = document.querySelector(campo.selector);
      if (elemento) {
        elemento.value = campo.value;
      }
    });

    this.resetearFormularios();

    setTimeout(() => {
      const camposArray = document.querySelectorAll(
        'input[name*="[]"], select[name*="[]"], textarea[name*="[]"]'
      );
      camposArray.forEach((campo) => {
        campo.value = "";
      });
    }, 100);
  }

  actualizarBotonesEliminar() {
    const contenedor = document.getElementById("contenedorFormularios");
    const totalFormularios = contenedor.children.length;
    const botonesEliminar = contenedor.querySelectorAll(".btn-eliminar");

    botonesEliminar.forEach((boton) => {
      if (STATE.tipoControlActual === CONFIG.TIPOS_CONTROL.PERIODICO) {
        boton.style.display =
          totalFormularios <= CONFIG.MINIMO_FORMULARIOS_PERIODICO
            ? "none"
            : "inline-block";
      } else {
        boton.style.display = totalFormularios <= 1 ? "none" : "inline-block";
      }
    });
  }

  procesarFormulario() {
    const formData = new FormData(
      document.getElementById("frmControlAutomatico")
    );
    formData.append("tipoControlActual", STATE.tipoControlActual);
    formData.append("totalFormularios", STATE.contadorFormularios);

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
              "Condición de éxito cumplida, llamando a mostrarModalExito"
            );
            App.modals.mostrarModalExito(res.message);
            App.forms.limpiarFormulario();
            App.api.listarControl();
            App.api.listarHistorico();
            App.api.cargarContadores();
          } else {
            console.log("Condición de error, llamando a mostrarModalError");
            App.modals.mostrarModalError(res.message);
          }
        } catch (error) {
          console.error("Error al parsear JSON:", error);
          console.log("Respuesta original:", this.responseText);
          App.modals.mostrarModalError("Error en la respuesta del servidor");
        }
      }
    };
  }
}
