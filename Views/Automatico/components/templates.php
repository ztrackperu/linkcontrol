<!-- TEMPLATES OCULTOS -->
<div class="d-none">
    <!-- Template Formulario Único -->
    <div id="templateFormularioUnico" class="template-formulario">
        <div class="card mb-3 border-primary">
            <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary fw-bold">
                    <i class="bi bi-gear-fill me-2"></i>
                    <span class="titulo-control">Control Único #1</span>
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar d-none" onclick="eliminarFormulario(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <input type="text" class="form-control" name="etapa[]" placeholder="Nombre de la etapa" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Fecha y Hora (INICIO)</label>
                        <input type="datetime-local" class="form-control" name="fechaHoraInicio[]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Template Formulario Cíclico -->
    <div id="templateFormularioCiclico" class="template-formulario">
        <div class="card mb-3 border-info">
            <div class="card-header bg-info bg-opacity-10 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-info fw-bold">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    <span class="titulo-control">Control Cíclico #1</span>
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar d-none" onclick="eliminarFormulario(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <input type="text" class="form-control" name="etapa[]" placeholder="Nombre de la etapa" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Hora</label>
                        <input type="time" class="form-control" name="hora[]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Template Formulario Periódico -->
    <div id="templateFormularioPeriodico" class="template-formulario">
        <div class="card mb-3 border-warning">
            <div class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-warning fw-bold">
                    <i class="bi bi-calendar-week me-2"></i>
                    <span class="titulo-control">Control Periódico #1</span>
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar d-none" onclick="eliminarFormulario(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <input type="text" class="form-control" name="etapa[]" placeholder="Nombre de la etapa" required>
                    </div>
                    <!-- <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Fecha y Hora (INICIO)</label>
                        <input type="datetime-local" class="form-control" name="fechaHoraInicio[]" required>
                    </div> -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Temperatura (°C)</label>
                        <input type="number" class="form-control" name="temperatura[]" step="0.1" placeholder="Temp. objetivo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Humedad (%)</label>
                        <input type="number" class="form-control" name="humedad[]" min="0" max="100" step="0.1" placeholder="Humedad objetivo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Duración (Horas)</label>
                        <input type="number" placeholder="Duración en horas" step="0.5" class="form-control" name="duracion[]" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
