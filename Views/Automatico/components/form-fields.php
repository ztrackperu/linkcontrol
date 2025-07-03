<!-- SELECT OPCIÓN -->
<div class="mb-3">
    <label for="tipoControl" class="form-label fw-bold">Opción</label>
    <select class="form-select" id="tipoControl" name="tipoControl" required onchange="mostrarFormulario()">
        <option value="">Seleccionar...</option>
        <option value="unico">Único</option>
        <option value="ciclico">Cíclico</option>
        <option value="periodico">Periódico</option>
    </select>
</div>

<!-- CAMPO FECHA FIN DEL PROCESO (Para único y cíclico) -->
<div class="mb-3 d-none" id="campoFechaFin">
    <div class="card border-warning">
        <div class="card-header bg-warning bg-opacity-10">
            <h6 class="mb-0 text-warning fw-bold">
                <i class="bi bi-calendar-check me-2"></i>Fecha y Hora de Finalización del Proceso
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nombre Proceso</label>
                    <input type="text" class="form-control" name="nombrep[]" placeholder="Nombre del proceso" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Fecha y Hora (FIN DEL PROCESO COMPLETO)</label>
                    <input type="datetime-local" class="form-control" name="fechaHoraFin" id="fechaHoraFin" required>
                    <small class="text-muted">Esta será la fecha de finalización de todo el proceso de control</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CAMPO PROCESO PERIÓDICO (Solo para periódico) -->
<div class="mb-3 d-none" id="campoProcesoPeriodico">
    <div class="card border-warning">
        <div class="card-header bg-warning bg-opacity-10">
            <h6 class="mb-0 text-warning fw-bold">
                <i class="bi bi-calendar-week me-2"></i>Configuración del Proceso Periódico
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nombre Proceso</label>
                    <input type="text" class="form-control" name="nombreProcesoPeriodico" placeholder="Nombre del proceso periódico" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Horas Proceso</label>
                    <input type="number" class="form-control" name="horasProceso" min="1" step="0.5" placeholder="Duración en horas" required>
                    <small class="text-muted">Duración total del proceso en horas</small>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Fecha y Hora (INICIO DEL PROCESO COMPLETO)</label>
                    <input type="datetime-local" class="form-control" name="fechaHoraInicio" id="fechaHoraFin" required>
                    <small class="text-muted">Esta será la fecha de inicialización de todo el proceso de control</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTENEDOR FORMULARIOS CON SCROLL INTERNO -->
<div id="contenedorFormularios" class="mb-3"></div>
