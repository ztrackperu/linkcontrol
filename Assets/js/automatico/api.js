// ===========================
// MÓDULO DE GESTIÓN DE API
// ===========================
class ApiManager {
  // ===========================
  // LISTADO DE CONTROLES
  // ===========================
  listarControl() {
    fetch(base_url + "Automatico/listar", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        this.mostrarControlesEnTabla(data);
      })
      .catch((error) => {
        console.error("Error:", error);
        this.mostrarModalError("Error al cargar los controles");
      });
  }

  listarHistorico() {
    fetch(base_url + "Automatico/listarHistorico", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        this.mostrarHistoricoEnTabla(data);
      })
      .catch((error) => {
        console.error("Error:", error);
        this.mostrarModalError("Error al cargar el histórico");
      });
  }

  // ===========================
  // CREACIÓN DE CONTROLES
  // ===========================
  crearControl(formData) {
    fetch(base_url + "Automatico/crear", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((res) => {
        console.log("Respuesta recibida:", res);
        if (res.success) {
          this.mostrarModalExito(res.message);
          this.limpiarFormulario();
          this.listarControl();
          this.listarHistorico();
          this.cargarContadores();
        } else if (res.type === "control_activo") {
          // Guardar datos para manejo de control activo
          window.nuevoControlData = res.nuevo_control;
          window.idControlActivo = res.id_control_activo;
          this.mostrarModalAdvertencia(res.html_detalles);
        } else {
          this.mostrarModalError(res.message);
        }
      })
      .catch((error) => {
        console.error("Error al crear control:", error);
        this.mostrarModalError("Error en la respuesta del servidor");
      });
  }

  // ===========================
  // VISUALIZACIÓN DE CONTROLES
  // ===========================
  verControl(idControl) {
    fetch(base_url + "Automatico/verControl", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id_control=" + idControl,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          document.getElementById("contenidoVerControl").innerHTML = res.html;
          const modal = new bootstrap.Modal(
            document.getElementById("modalVerControl")
          );
          modal.show();
        } else {
          if (res.message?.includes("No se encontró el control especificado")) {
            const modalError = new bootstrap.Modal(
              document.getElementById("modalControlNoEncontrado")
            );
            modalError.show();
          } else {
            this.mostrarModalError("Error: " + res.message);
          }
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        this.mostrarModalError("Error de conexión al servidor");
      });
  }

  // ===========================
  // ELIMINACIÓN DE CONTROLES
  // ===========================
  eliminarControl(idControl) {
    // Guardar el ID en variable global o en el modal
    document.getElementById("idControlEliminar").value = idControl;

    // Mostrar modal de confirmación
    const modalConfirmacion = new bootstrap.Modal(
      document.getElementById("modalEliminarControl")
    );
    modalConfirmacion.show();
  }

  confirmarEliminacion() {
    const idControl = document.getElementById("idControlEliminar").value;

    if (!idControl) {
      this.mostrarModalError("Error: ID de control no válido");
      return;
    }

    // Cerrar modal de confirmación
    const modalConfirmacion = bootstrap.Modal.getInstance(
      document.getElementById("modalEliminarControl")
    );
    if (modalConfirmacion) {
      modalConfirmacion.hide();
    }

    // Proceder con la eliminación
    this.ejecutarEliminacion(idControl);
  }

  ejecutarEliminacion(idControl) {
    fetch(base_url + "Automatico/eliminarControl", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id_control=" + idControl,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          const modalExito = new bootstrap.Modal(
            document.getElementById("modalExitoEliminacion")
          );
          modalExito.show();

          setTimeout(() => {
            modalExito.hide();
            this.listarControl();
            this.listarHistorico();
            this.cargarContadores();
          }, 2000);
        } else {
          this.mostrarModalError("Error: " + res.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        this.mostrarModalError("Error al eliminar el control");
      });
  }

  eliminarControlActivo() {
    if (!window.idControlActivo) {
      this.mostrarModalError("Error: Datos del control no disponibles");
      return;
    }

    fetch(base_url + "Automatico/eliminarControl", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "id_control=" + window.idControlActivo,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          this.crearNuevoControlDespuesDeEliminar();
        } else {
          this.mostrarModalError(
            "Error al eliminar el control activo: " + res.message
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        this.mostrarModalError("Error en la respuesta del servidor");
      });
  }

  // ===========================
  // CONTADORES Y ESTADÍSTICAS
  // ===========================
  cargarContadores() {
    fetch(base_url + "Automatico/obtenerContadores", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          this.actualizarContadoresEnVista(res.data);
        } else {
          console.error("Error al cargar contadores:", res.message);
        }
      })
      .catch((error) => {
        console.error("Error al cargar contadores:", error);
      });
  }

  // ===========================
  // MÉTODOS AUXILIARES
  // ===========================
  crearNuevoControlDespuesDeEliminar() {
    if (!window.nuevoControlData) {
      this.mostrarModalError("Error: Datos del nuevo control no disponibles");
      return;
    }

    const formData = this.construirFormDataDesdeControl(
      window.nuevoControlData
    );

    fetch(base_url + "Automatico/crear", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          this.mostrarModalExito(
            "Control anterior eliminado y nuevo control creado exitosamente"
          );
          this.limpiarFormulario();
          this.listarControl();
          this.listarHistorico();
          this.cargarContadores();

          // Limpiar variables globales
          window.idControlActivo = null;
          window.nuevoControlData = null;
        } else {
          this.mostrarModalError(
            "Control eliminado pero error al crear el nuevo: " + res.message
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        this.mostrarModalError("Error en la respuesta del servidor");
      });
  }

  construirFormDataDesdeControl(controlData) {
    const formData = new FormData();

    // Determinar tipo de control
    let tipoControl = "unico";
    if (controlData.tipo_control_temperatura === 1) {
      tipoControl = "ciclico";
    } else if (controlData.tipo_control_temperatura === 2) {
      tipoControl = "periodico";
    }

    formData.append("tipoControl", tipoControl);

    // Agregar campos según tipo
    if (tipoControl === "periodico") {
      formData.append(
        "nombreProcesoPeriodico",
        controlData.proceso_control_temperatura
      );
      formData.append("horasProceso", controlData.horas_proceso || 24);
    } else {
      formData.append("nombrep", controlData.proceso_control_temperatura);
      formData.append(
        "fechaHoraFin",
        this.convertirFechaParaFormulario(
          controlData.hora_fin_control_temperatura
        )
      );
    }

    // Agregar etapas
    controlData.lista_control_temperatura.forEach((etapa) => {
      formData.append("etapa[]", etapa.nombre_etapa);
      formData.append("temperatura[]", etapa.temperatura_etapa);

      if (tipoControl === "unico") {
        formData.append(
          "fechaHoraInicio[]",
          this.convertirFechaParaFormulario(etapa.hora_inicio_etapa)
        );
      } else if (tipoControl === "ciclico") {
        formData.append(
          "hora[]",
          this.extraerHoraDeFecha(etapa.hora_inicio_etapa)
        );
      } else if (tipoControl === "periodico") {
        formData.append(
          "fechaHoraInicio[]",
          this.convertirFechaParaFormulario(etapa.hora_inicio_etapa)
        );
        formData.append("humedad[]", etapa.humedad_etapa || 50);
        formData.append("duracion[]", etapa.duracion_etapa || 2.0);
      }
    });

    return formData;
  }

  // ===========================
  // UTILIDADES DE FECHA
  // ===========================
  convertirFechaParaFormulario(fechaCustom) {
    if (!fechaCustom || fechaCustom === "No definida") return "";

    const regex = /(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/;
    const match = fechaCustom.match(regex);

    if (match) {
      const [, dia, mes, año, hora, minuto] = match;
      return `${año}-${mes}-${dia}T${hora}:${minuto}`;
    }
    return fechaCustom;
  }

  extraerHoraDeFecha(fechaCustom) {
    if (!fechaCustom) return "";

    const regex = /(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/;
    const match = fechaCustom.match(regex);

    if (match) {
      const [, , , , hora, minuto] = match;
      return `${hora}:${minuto}`;
    }
    return "";
  }

  // ===========================
  // MÉTODOS DE INTERFAZ
  // ===========================
  mostrarControlesEnTabla(data) {
    const tbody = document.getElementById("contenidoTabla");
    if (tbody) tbody.innerHTML = data;
  }

  mostrarHistoricoEnTabla(data) {
    const tbody = document.getElementById("contenidoTablaHistorico");
    if (tbody) tbody.innerHTML = data;
  }

  actualizarContadoresEnVista(data) {
    // Contadores generales
    this.actualizarElemento("totalCreados", data.general.creado);
    this.actualizarElemento("totalEliminados", data.general.eliminado);
    this.actualizarElemento("totalReestablecidos", data.general.reestablecido);

    // Contadores de hoy
    this.actualizarElemento("creadosHoy", data.hoy.creado);
    this.actualizarElemento("eliminadosHoy", data.hoy.eliminado);
    this.actualizarElemento("reestablecidosHoy", data.hoy.reestablecido);

    // Fechas
    this.actualizarFechas(data);
  }

  actualizarFechas(data) {
    const fechaInicio = data.created_at
      ? new Date(data.created_at).toLocaleDateString("es-PE")
      : "N/A";
    const fechaFin = data.updated_at
      ? new Date(data.updated_at).toLocaleDateString("es-PE")
      : "N/A";

    const rangoGeneral = `(${fechaInicio} - ${fechaFin})`;
    document.querySelectorAll(".fecha-general").forEach((el) => {
      el.textContent = rangoGeneral;
    });

    const fechaHoyFormateada = `(${data.fecha_actual_formateada})`;
    document.querySelectorAll(".fecha-hoy").forEach((el) => {
      el.textContent = fechaHoyFormateada;
    });
  }

  actualizarElemento(id, valor) {
    const elemento = document.getElementById(id);
    if (elemento) elemento.textContent = valor;
  }

  // ===========================
  // MÉTODOS DE MODAL
  // ===========================
  mostrarModalExito(mensaje) {
    document.getElementById("mensajeExito").textContent = mensaje;
    const modal = new bootstrap.Modal(document.getElementById("modalExito"));
    modal.show();
  }

  mostrarModalError(mensaje) {
    document.getElementById("mensajeError").textContent = mensaje;
    const modal = new bootstrap.Modal(document.getElementById("modalError"));
    modal.show();
  }

  mostrarModalAdvertencia(htmlDetalles) {
    document.getElementById("contenidoWarning").innerHTML = htmlDetalles;
    const modal = new bootstrap.Modal(document.getElementById("modalWarning"));
    modal.show();
  }

  limpiarFormulario() {
    // Delegado a FormManager - se implementará en la integración
    if (window.App?.forms?.limpiarFormulario) {
      window.App.forms.limpiarFormulario();
    }
  }
}
