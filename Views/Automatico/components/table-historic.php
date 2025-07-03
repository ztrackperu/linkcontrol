<!-- Histórico de Controles -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-archive me-2"></i>Histórico de Controles
                </h5>
                <button type="button" class="btn btn-light btn-sm" onclick="ListarHistorico()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover mb-0" id="tablaHistorico">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Nombre Proceso</th>
                                <th>Tipo</th>
                                <th>IMEI</th>
                                <th>Etapas</th>
                                <th>Estado</th>
                                <th>Condición</th>
                                <th>Fecha Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="contenidoTablaHistorico"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
