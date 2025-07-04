<!-- HEADER PROFESIONAL CON SOMBRA MÁS MARCADA -->
<div class="row mb-4">
    <div class="col-12">
        <div class="p-4 bg-white rounded shadow-lg border-start border-4 border-primary" style="box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.15);">
            <div class="row align-items-center">
                <!-- Icono + Texto -->
                <div class="col-md-8 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-cpu-fill fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Control Automático Inteligente</h2>
                        <p class="mb-0 text-muted">Sistema avanzado de gestión y programación automática</p>
                    </div>
                </div>

                <!-- Badges de estado -->
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end justify-content-start gap-2 flex-wrap">
                        <span class="badge bg-success bg-opacity-25 text-success px-3 py-2 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i>Sistema Activo
                        </span>
                        <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 fw-semibold">
                            <i class="bi bi-clock me-1"></i><span id="currentTime">--:--</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del dashboard -->
            <?php include "Views/automatico/components/dashboard-stats.php"; ?>
        </div>
    </div>
</div>
