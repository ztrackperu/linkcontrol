<?php include "Views/templates/navbar.php"; ?>
<div class="px-2 py-2">
    <!-- TÍTULO GENERAL PROFESIONAL -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-cpu-fill fs-2 text-white"></i>
                                </div>
                                <div>
                                    <h1 class="mb-1 fw-bold">Control Automático Inteligente</h1>
                                    <p class="mb-0 opacity-75">Sistema avanzado de gestión y programación automática</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <span class="badge bg-success bg-opacity-25 text-white px-3 py-2 fs-6">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Sistema Activo
                                </span>
                                <span class="badge bg-info bg-opacity-25 text-white px-3 py-2 fs-6">
                                    <i class="bi bi-clock me-1"></i>
                                    <span id="currentTime">--:--</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Indicadores de estado -->
                    <div class="row mt-4 g-3">
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold" id="totalControles">0</div>
                                <small class="opacity-75">Controles Activos</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold text-warning" id="controlesEjecutandose">0</div>
                                <small class="opacity-75">En Ejecución</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold text-info" id="controlesProgramados">0</div>
                                <small class="opacity-75">Programados</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center bg-white bg-opacity-10 rounded p-3">
                                <div class="h3 mb-1 fw-bold text-danger" id="alertasActivas">0</div>
                                <small class="opacity-75">Alertas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="row">
            <!-- FORMULARIO CREAR CONTROL -->
            <div class="col-12 col-lg-6">
                <div class="card shadow border-0">
                    <!-- resto del código... -->

                    <!-- resto del código... -->

                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Crear Control Automático</h5>
                    </div>
                    <div class="card-body">
                        <form id="frmControlAutomatico">
                            <!-- SELECT OPCIÓN -->
                            <div class="mb-3">
                                <label for="tipoControl" class="form-label">Opción</label>
                                <select class="form-select" id="tipoControl" name="tipoControl" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="unico">Único</option>
                                    <option value="ciclico">Cíclico</option>
                                </select>
                            </div>

                            <!-- FORMULARIO DINÁMICO -->
                            <div id="formularioDinamico" style="display: none;">
                                <!-- ETAPA -->
                                <div class="mb-3">
                                    <label for="etapa" class="form-label">Etapa</label>
                                    <input type="text" class="form-control" id="etapa" name="etapa" placeholder="Nombre de la etapa" required>
                                </div>

                                <!-- FECHA Y HORA (Solo para Único) -->
                                <div class="mb-3" id="campoFechaHora" style="display: none;">
                                    <label for="fechaHora" class="form-label">Fecha y Hora</label>
                                    <input type="datetime-local" class="form-control" id="fechaHora" name="fechaHora">
                                </div>

                                <!-- HORA (Solo para Cíclico) -->
                                <div class="mb-3" id="campoHora" style="display: none;">
                                    <label for="hora" class="form-label">Hora</label>
                                    <input type="time" class="form-control" id="hora" name="hora">
                                </div>

                                <!-- HORAS -->
                                <div class="mb-3">
                                    <label for="horas" class="form-label">Horas</label>
                                    <input type="number" class="form-control" id="horas" name="horas" min="1" max="24" placeholder="Duración en horas" required>
                                </div>

                                <!-- TEMPERATURA -->
                                <div class="mb-3">
                                    <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                    <input type="number" class="form-control" id="temperatura" name="temperatura" step="0.1" placeholder="Temperatura objetivo" required>
                                </div>

                                <!-- HUMEDAD (OCULTO) -->
                                <input type="hidden" id="humedad" name="humedad" value="50">

                                <!-- BOTONES -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Limpiar</button>
                                    <button type="submit" class="btn btn-success">Crear Control</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TABLA CONTROLES CREADOS -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Controles Automáticos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaControles">
                                <thead>
                                    <tr>
                                        <th>Etapa</th>
                                        <th>Tipo</th>
                                        <th>Programación</th>
                                        <th>Temp</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="contenidoTabla">
                                    <!-- Contenido dinámico -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoControl = document.getElementById('tipoControl');
    const formularioDinamico = document.getElementById('formularioDinamico');
    const campoFechaHora = document.getElementById('campoFechaHora');
    const campoHora = document.getElementById('campoHora');
    const fechaHoraInput = document.getElementById('fechaHora');
    const horaInput = document.getElementById('hora');

    // Mostrar/ocultar formulario según selección
    tipoControl.addEventListener('change', function() {
        const valor = this.value;
        
        if (valor) {
            formularioDinamico.style.display = 'block';
            
            if (valor === 'unico') {
                campoFechaHora.style.display = 'block';
                campoHora.style.display = 'none';
                fechaHoraInput.required = true;
                horaInput.required = false;
            } else if (valor === 'ciclico') {
                campoFechaHora.style.display = 'none';
                campoHora.style.display = 'block';
                fechaHoraInput.required = false;
                horaInput.required = true;
            }
        } else {
            formularioDinamico.style.display = 'none';
        }
    });
});

function limpiarFormulario() {
    document.getElementById('frmControlAutomatico').reset();
    document.getElementById('formularioDinamico').style.display = 'none';
    document.getElementById('campoFechaHora').style.display = 'none';
    document.getElementById('campoHora').style.display = 'none';
}
</script>

<?php include "Views/templates/footer.php"; ?>
