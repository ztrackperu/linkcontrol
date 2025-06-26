// Función simple para mostrar fecha y hora actual
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

// Inicializar cuando cargue la página
document.addEventListener("DOMContentLoaded", function () {
  updateCurrentTime();
  setInterval(updateCurrentTime, 1000); // Actualizar cada segundo
});
