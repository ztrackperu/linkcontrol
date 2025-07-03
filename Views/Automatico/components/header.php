<!-- TÍTULO GENERAL PROFESIONAL -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg text-white bg-black bg-opacity-40">
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
                                <i class="bi bi-check-circle me-1"></i>Sistema Activo
                            </span>
                            <span class="badge bg-info bg-opacity-25 text-white px-3 py-2 fs-6">
                                <i class="bi bi-clock me-1"></i><span id="currentTime">--:--</span>
                            </span>
                        </div>
                    </div>
                </div>
                <?php include "Views/automatico/components/dashboard-stats.php"; ?>
            </div>
        </div>
    </div>
</div>
