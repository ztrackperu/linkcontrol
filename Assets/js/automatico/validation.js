// ===========================
// MÓDULO DE VALIDACIÓN
// ===========================
class ValidationManager {
  validarFechaFin() {
    if (STATE.tipoControlActual === CONFIG.TIPOS_CONTROL.PERIODICO) {
      return this.validarCamposPeriodico();
    }

    const fechaFin = document.getElementById("fechaHoraFin").value;
    if (!fechaFin) {
      App.modals.mostrarModalError(
        "La fecha de finalización del proceso es obligatoria"
      );
      return false;
    }

    const fechaFinDate = new Date(fechaFin);
    const ahora = new Date();
    if (fechaFinDate <= ahora) {
      App.modals.mostrarModalError(
        "La fecha de finalización debe ser posterior a la fecha actual"
      );
      return false;
    }

    return true;
  }

  validarCamposPeriodico() {
    const nombreProceso = document.querySelector(
      'input[name="nombreProcesoPeriodico"]'
    ).value;
    const horasProceso = document.querySelector(
      'input[name="horasProceso"]'
    ).value;

    if (!nombreProceso.trim()) {
      App.modals.mostrarModalError(
        "El nombre del proceso periódico es obligatorio"
      );
      return false;
    }

    if (!horasProceso || horasProceso <= 0) {
      App.modals.mostrarModalError("Las horas del proceso deben ser mayor a 0");
      return false;
    }

    return this.validarFormulariosPeriodicos();
  }

  validarFormulariosPeriodicos() {
    const formularios = document.querySelectorAll(
      "#contenedorFormularios .card"
    );

    if (formularios.length < CONFIG.MINIMO_FORMULARIOS_PERIODICO) {
      App.modals.mostrarModalError(
        `Debe tener al menos ${CONFIG.MINIMO_FORMULARIOS_PERIODICO} etapas para un proceso periódico`
      );
      return false;
    }

    // Validar campos generales del proceso (fuera de las etapas)
    const nombreProceso = document.querySelector(
      'input[name="nombreProcesoPeriodico"]'
    );
    const horasProceso = document.querySelector('input[name="horasProceso"]');
    const fechaHoraInicioProceso = document.querySelector(
      'input[name="fechaHoraInicio"]'
    );

    if (!nombreProceso || !nombreProceso.value.trim()) {
      App.modals.mostrarModalError(
        "El nombre del proceso periódico es obligatorio"
      );
      return false;
    }

    if (
      !horasProceso ||
      !horasProceso.value ||
      parseFloat(horasProceso.value) <= 0
    ) {
      App.modals.mostrarModalError("Las horas del proceso deben ser mayor a 0");
      return false;
    }

    if (!fechaHoraInicioProceso || !fechaHoraInicioProceso.value) {
      App.modals.mostrarModalError(
        "La fecha y hora de inicio del proceso es obligatoria"
      );
      return false;
    }

    // Validar que la fecha de inicio sea posterior a la actual
    const fechaInicioProceso = new Date(fechaHoraInicioProceso.value);
    const ahora = new Date();
    if (fechaInicioProceso <= ahora) {
      App.modals.mostrarModalError(
        "La fecha de inicio del proceso debe ser posterior a la fecha actual"
      );
      return false;
    }

    // Validar cada etapa del proceso
    for (let i = 0; i < formularios.length; i++) {
      const formulario = formularios[i];
      const etapa = formulario.querySelector('input[name="etapa[]"]');
      const temperatura = formulario.querySelector(
        'input[name="temperatura[]"]'
      );
      const humedad = formulario.querySelector('input[name="humedad[]"]');
      const duracion = formulario.querySelector('input[name="duracion[]"]');

      // Validar nombre de etapa
      if (!etapa || !etapa.value.trim()) {
        App.modals.mostrarModalError(
          `El nombre de la etapa ${i + 1} es obligatorio`
        );
        return false;
      }

      // Validar temperatura
      if (
        !temperatura ||
        !temperatura.value ||
        isNaN(parseFloat(temperatura.value))
      ) {
        App.modals.mostrarModalError(
          `La temperatura de la etapa ${i + 1} debe ser un número válido`
        );
        return false;
      }

      // Validar humedad (0-100%)
      if (!humedad || !humedad.value || isNaN(parseFloat(humedad.value))) {
        App.modals.mostrarModalError(
          `La humedad de la etapa ${i + 1} debe ser un número válido`
        );
        return false;
      }

      const humedadValue = parseFloat(humedad.value);
      if (humedadValue < 0 || humedadValue > 100) {
        App.modals.mostrarModalError(
          `La humedad de la etapa ${i + 1} debe estar entre 0 y 100`
        );
        return false;
      }

      // Validar duración
      if (!duracion || !duracion.value || isNaN(parseFloat(duracion.value))) {
        App.modals.mostrarModalError(
          `La duración de la etapa ${i + 1} debe ser un número válido`
        );
        return false;
      }

      if (parseFloat(duracion.value) <= 0) {
        App.modals.mostrarModalError(
          `La duración de la etapa ${i + 1} debe ser mayor a 0`
        );
        return false;
      }
    }

    return true;
  }

  validarOrdenFechasPeriodicas(formularios) {
    const fechas = [];

    for (let i = 0; i < formularios.length; i++) {
      const fechaInicio = formularios[i].querySelector(
        'input[name="fechaHoraInicio[]"]'
      ).value;
      fechas.push({
        fecha: new Date(fechaInicio),
        etapa: i + 1,
      });
    }

    fechas.sort((a, b) => a.fecha - b.fecha);

    for (let i = 0; i < fechas.length - 1; i++) {
      if (fechas[i].fecha >= fechas[i + 1].fecha) {
        App.modals.mostrarModalError(
          "Las fechas de las etapas deben estar en orden cronológico"
        );
        return false;
      }
    }

    return true;
  }

  validarFormularioEdicion(data) {
    if (!data.nombrep || !data.nombrep.trim()) {
      App.modals.mostrarModalError("El nombre del proceso es obligatorio");
      return false;
    }

    if (data.tipo_control !== CONFIG.TIPOS_CONTROL.PERIODICO) {
      if (!data.fechaHoraFin) {
        App.modals.mostrarModalError("La fecha de finalización es obligatoria");
        return false;
      }

      const fechaFin = new Date(data.fechaHoraFin);
      const ahora = new Date();
      if (fechaFin <= ahora) {
        App.modals.mostrarModalError(
          "La fecha de finalización debe ser posterior a la fecha actual"
        );
        return false;
      }
    }

    return true;
  }
}
