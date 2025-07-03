// ===========================
// MÓDULO DE FECHA Y HORA
// ===========================
class DateTimeManager {
  constructor() {
    this.intervalId = null;
  }

  initialize() {
    this.updateCurrentTime();
    this.intervalId = setInterval(
      () => this.updateCurrentTime(),
      CONFIG.TIEMPO_ACTUALIZACION
    );
  }

  updateCurrentTime() {
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

  destroy() {
    if (this.intervalId) {
      clearInterval(this.intervalId);
      this.intervalId = null;
    }
  }
}

// Funciones utilitarias de fecha
const DateUtils = {
  convertirFechaParaFormulario(fechaCustom) {
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
  },

  extraerHoraDeFecha(fechaCustom) {
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
  },
};
