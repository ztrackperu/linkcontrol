<?php
class Automatico extends Controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    public function index()
    {
        $this->views->getView($this, "index");
    }


    public function listar()
    {
        $body = array(
            "especifico" => 0,
            "id_usuario" => 0
        );
        
        $response = $this->model->obtenerControles($body);
        $data = json_decode($response, true);
        
        // Inicializar $html AQUÍ
        $html = '';
        
        if (empty($data['data']['resultado'])) {
            $html = '<tr><td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mb-0 mt-2">No hay controles registrados</p>
                     </td></tr>';
        } else {
            $index = 1;
            foreach ($data['data']['resultado'] as $control) {
                $tipoTexto = $control['tipo_control_temperatura'] == 0 ? 'Único' : 'Cíclico';
                $tipoBadge = $control['tipo_control_temperatura'] == 0 ? 'bg-primary' : 'bg-info';
                $estadoTexto = $control['estado_control_temperatura'] == 1 ? 'Activo' : 'Inactivo';
                $estadoBadge = $control['estado_control_temperatura'] == 1 ? 'bg-success' : 'bg-danger';
                
                // Obtener el ID real del control
                $idControl = $control['id_control_temperatura'];
                
                $html .= '<tr>
                    <td class="fw-semibold">Control ' . $index . '</td>
                    <td><span class="badge ' . $tipoBadge . '">' . $tipoTexto . '</span></td>
                    <td class="small">
                        <i class="bi bi-device-hdd me-1"></i>' . $control['imei_control_temperatura'] . '
                    </td>
                    <td><span class="badge bg-warning text-dark">' . $control['total_control_temperatura'] . 'h</span></td>
                    <td><span class="badge ' . $estadoBadge . '">' . $estadoTexto . '</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" onclick="editarControl(' . $idControl . ')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="eliminarControl(' . $idControl . ')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>';
                $index++;
            }
        }
        
        echo json_encode($html, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function crear()
{
   
    $tipoControl = $_POST['tipoControl'];
    
    // Construir el JSON según el formato requerido
    $jsonData = array(
        "proceso_control_temperatura" => $_POST['nombrep'][0] ?? $_POST['nombrep'],
        "tipo_control_temperatura" => $tipoControl === "unico" ? 0 : 1,
        "user_c" => 0,
        "lista_control_temperatura" => array()
    );
    
    if ($tipoControl === "unico") {
        // Para único: agregar hora_fin_control_temperatura
        // $fechasFin = $_POST['fechaHoraFin'];
        // // if (!empty($fechasFin)) {
        // //     $jsonData['hora_fin_control_temperatura'] = $this->convertirFechaFormato(end($fechasFin));
        // // }
        
        // Procesar formularios únicos
        $etapas = $_POST['etapa'];
        $fechasInicio = $_POST['fechaHoraInicio'];
        $temperaturas = $_POST['temperatura'];
        $jsonData['hora_fin_control_temperatura'] = '27-06-2025_09-00'; // Asignar una fecha de finalización por defecto
        
        for ($i = 0; $i < count($etapas); $i++) {
            $jsonData['lista_control_temperatura'][] = array(
                "hora_inicio_etapa" => $this->convertirFechaFormato($fechasInicio[$i]),
                "nombre_etapa" => $etapas[$i],
                "temperatura_etapa" => floatval($temperaturas[$i])
            );
        }
        
    } else if ($tipoControl === "ciclico") {
        // Procesar formularios cíclicos
        $etapas = $_POST['etapa'];
        $horas = $_POST['hora'];
        $temperaturas = $_POST['temperatura'];
        
        for ($i = 0; $i < count($etapas); $i++) {
            // Para cíclico, usar solo la hora (sin fecha específica)
            $fechaHoy = date('Y-m-d');
            $horaCompleta = $fechaHoy . 'T' . $horas[$i];
            
            $jsonData['lista_control_temperatura'][] = array(
                "hora_inicio_etapa" => $this->convertirFechaFormato($horaCompleta),
                "nombre_etapa" => $etapas[$i],
                "temperatura_etapa" => floatval($temperaturas[$i])
            );
        }
    }
    
    // Calcular total_control_temperatura
    $jsonData['total_control_temperatura'] = count($jsonData['lista_control_temperatura']);
    
    // Enviar al modelo
    $response = $this->model->crearControl($jsonData);
    $result = json_decode($response, true);
    
    if (empty($result['data']) || $result['data'] == array()) {
        echo json_encode(array('success' => true, 'message' => 'Control creado exitosamente'));
    } else if (strpos($result['data'], "FAIL_ACTIVO") !== false) {
        // Extraer el ID del control activo
        $partes = explode(":", $result['data']);
        $id_control = trim($partes[1]);
        
        // Obtener detalles del control activo
        $body = array(
            "especifico" => $id_control,
            "id_usuario" => 0
        );
        
        $detallesResponse = $this->model->verControl($body);
        $detallesResult = json_decode($detallesResponse, true);
        
        if ($detallesResult && isset($detallesResult['data'])) {
            $controlActivo = $detallesResult['data'];
            
            // Construir HTML de los detalles
            $htmlDetalles = $this->construirHtmlControlActivo($controlActivo);
            
            echo json_encode(array(
                'success' => false, 
                'type' => 'control_activo',
                'message' => 'Hay un control activo en proceso',
                'html_detalles' => $htmlDetalles,
                'nuevo_control' => $jsonData
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al obtener detalles del control activo'));
        }
    } else if ($result['data'] == "DUPLICADO") {
        echo json_encode(array('success' => false, 'message' => 'Control ya existe, no se puede crear otro con el mismo nombre.'));
    } else if ((empty($result['data']) || $result['data'] == array() || $result['data'] == new stdClass()) && $result['code'] == 200 && $result['message'] == "ok") {
        echo json_encode(array('success' => true, 'message' => 'Control creado exitosamente'));

    } else {
        echo json_encode(array('success' => false, 'message' => 'Error desconocido al crear el control.'));
    }
    die();
}
    
    private function construirHtmlControlActivo($controlActivo)
    {
        $tipo = $controlActivo['tipo_control_temperatura'] == 0 ? 'Único' : 'Cíclico';
        $condicion = $controlActivo['condicion_control_temperatura'] == 1 ? 'En Proceso' : 'Completado';
        $estado = $controlActivo['estado_control_temperatura'] == 1 ? 'Activo' : 'Inactivo';
        $fechaCreacion = date('d/m/Y H:i:s', strtotime($controlActivo['created_at']));
        $horaFin = $controlActivo['hora_fin_control_temperatura'] ?? 'No definida';
        
        $html = '
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            <div>
                <strong>Atención:</strong> Ya existe un control en proceso. Revisa los detalles a continuación.
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-gear-fill me-1"></i>Información General</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Proceso:</strong> ' . htmlspecialchars($controlActivo['proceso_control_temperatura']) . '</p>
                        <p><strong>Máquina:</strong> ' . htmlspecialchars($controlActivo['imei_control_temperatura']) . '</p>
                        <p><strong>Tipo:</strong> ' . $tipo . '</p>
                        <p><strong>Total Etapas:</strong> ' . $controlActivo['total_control_temperatura'] . '</p>
                        <p><strong>Estado:</strong> <span class="badge bg-success">' . $condicion . '</span> <span class="badge bg-primary">' . $estado . '</span></p>
                        <p><strong>Creado:</strong> ' . $fechaCreacion . '</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-clock-fill me-1"></i>Programación</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Hora Fin:</strong> ' . $horaFin . '</p>
                        <div class="mt-2"><strong>Etapas:</strong></div>';
        
        foreach ($controlActivo['lista_control_temperatura'] as $index => $etapa) {
            $html .= '
                        <div class="border rounded p-2 mb-2 bg-light">
                            <small class="text-muted">Etapa ' . ($index + 1) . '</small>
                            <div><strong>' . htmlspecialchars($etapa['nombre_etapa']) . '</strong></div>
                            <div class="small">
                                <i class="bi bi-clock me-1"></i>' . $etapa['hora_inicio_etapa'] . '
                                <i class="bi bi-thermometer-half ms-2 me-1"></i>' . $etapa['temperatura_etapa'] . '°C
                            </div>
                        </div>';
        }
        
        $html .= '
                    </div>
                </div>
            </div>
        </div>';
        
        return $html;
    }


private function convertirFechaFormato($fechaHtml)
{
    // Convertir de "2025-06-25T17:42" a "25-06-2025_17-42"
    $fecha = new DateTime($fechaHtml);
    return $fecha->format('d-m-Y_H-i');
}


private function eliminarControl($id_control)
{
    $body = array(
        "especifico" => $id_control,
        "id_usuario" => 0
    );
    
    $response = $this->model->eliminarControl($body);
    $result = json_decode($response, true);

}
}
?>
