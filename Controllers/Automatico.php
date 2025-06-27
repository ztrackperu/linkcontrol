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
            "id_usuario" => 1,
             "tipo_usuario" => 1
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
                
                $condicionTexto = $control['condicion_control_temperatura'] == 1 ? 'En proceso' : 'Completado';
                $condicionBadge = $control['condicion_control_temperatura'] == 1 ? 'bg-warning' : 'bg-success';
                // Obtener el ID real del control
                $idControl = $control['id_control_temperatura'];
                
                $html .= '<tr>
                    <td class="fw-semibold">Control ' . $index . '</td>
                    <td><span class="badge ' . $tipoBadge . '">' . $tipoTexto . '</span></td>
                    <td class="small">
                        <i class="bi bi-device-hdd me-1"></i>' . $control['imei_control_temperatura'] . '
                    </td>
                    <td><span class="badge bg-primary text-white">' . $control['total_control_temperatura'] . '</span></td>
                    <td><span class="badge ' . $estadoBadge . '">' . $estadoTexto . '</span></td>
                     <td><span class="badge ' . $condicionBadge . '">' . $condicionTexto . '</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm" onclick="verControl(' . $idControl . ')">
                                <i class="bi bi-search"></i>
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
            // Procesar formularios únicos
            $etapas = $_POST['etapa'];
            $fechasInicio = $_POST['fechaHoraInicio'];
            $temperaturas = $_POST['temperatura'];
            
            // Usar la fecha fin ingresada por el usuario
            if (!empty($_POST['fechaHoraFin'])) {
                $jsonData['hora_fin_control_temperatura'] = $this->convertirFechaFormato($_POST['fechaHoraFin']);
            } else {
                $jsonData['hora_fin_control_temperatura'] = '27-06-2025_09-00'; // Fallback
            }
            
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
            $jsonData['hora_fin_control_temperatura'] = 'No tiene hora de finalización, se repetirá indefinidamente';
            
          
            
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
        
        // VERIFICAR PRIMERO SI HAY CONTROL ACTIVO (ANTES DE VERIFICAR ÉXITO)
        if (is_string($result['data']) && strpos($result['data'], "FAIL_ACTIVO") !== false) {
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
                    'id_control_activo' => $id_control,
                    'nuevo_control' => $jsonData
                ));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Error al obtener detalles del control activo'));
            }
        } 
        // DESPUÉS VERIFICAR SI ES ÉXITO
        else if ($result && $result['code'] == 200 && $result['message'] == "ok") {
            // Si hay data con id_control_temperatura, es éxito
            if (isset($result['data']['id_control_temperatura'])) {
                echo json_encode(array('success' => true, 'message' => 'Control creado exitosamente'));
            } else {
                echo json_encode(array('success' => true, 'message' => 'Control creado exitosamente'));
            }
        } else if (is_string($result['data']) && $result['data'] == "DUPLICADO") {
            echo json_encode(array('success' => false, 'message' => 'Control ya existe, no se puede crear otro con el mismo nombre.'));
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




public function verControl($id_control = null)
{
    $id_control = $_POST['id_control'] ?? 0;
    
    if ($id_control == 0) {
        echo json_encode(array(
            'success' => false,
            'message' => 'ID de control no válido'
        ));
        die();
    }
    
    $body = array(
        "especifico" => $id_control,
        "id_usuario" => 0,
        "tipo_usuario" => 0
    );
    
    $response = $this->model->verControl($body);
    $result = json_decode($response, true);
    
    if ($result && isset($result['data']) && !empty($result['data']) && $result['code'] == 200) {
        $controlData = $result['data'];
        
        $htmlDetalles = $this->construirHtmlDetallesControl($controlData);
        
        echo json_encode(array(
            'success' => true,
            'data' => $controlData,
            'html' => $htmlDetalles
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'No se encontró el control especificado'
        ));
    }
    die();
}



private function construirHtmlDetallesControl($controlData)
{
    // Procesar los datos
    $tipo = $controlData['tipo_control_temperatura'] == 0 ? 'Único' : 'Cíclico';
    $tipoBadge = $controlData['tipo_control_temperatura'] == 0 ? 'bg-primary' : 'bg-info';
    
    // Condición: 1 = En proceso, 2 = Completado, 0 = Inactivo
    $condicionTexto = '';
    $condicionBadge = '';
    switch ($controlData['condicion_control_temperatura']) {
        case 1:
            $condicionTexto = 'En Proceso';
            $condicionBadge = 'bg-warning';
            break;
        case 2:
            $condicionTexto = 'Completado';
            $condicionBadge = 'bg-success';
            break;
        default:
            $condicionTexto = 'Inactivo';
            $condicionBadge = 'bg-secondary';
    }
    
    $estado = $controlData['estado_control_temperatura'] == 1 ? 'Activo' : 'Inactivo';
    $estadoBadge = $controlData['estado_control_temperatura'] == 1 ? 'bg-success' : 'bg-danger';
    
    // Formatear fechas
    $fechaCreacion = isset($controlData['created_at']) ? 
        date('d/m/Y H:i:s', strtotime($controlData['created_at'])) : 'No disponible';
    
    $fechaModificacion = isset($controlData['updated_at']) && $controlData['updated_at'] !== null ? 
        date('d/m/Y H:i:s', strtotime($controlData['updated_at'])) : 'No modificado';
    
    // Formatear hora fin
    $horaFin = $controlData['hora_fin_control_temperatura'] ?? 'No definida';
    if ($horaFin !== 'No definida') {
        // Convertir formato 26-06-2025_17-57 a fecha legible
        $horaFinFormatted = $this->formatearFechaCustom($horaFin);
    } else {
        $horaFinFormatted = $horaFin;
    }
    
    // Usuarios
    $usuarioCreador = $controlData['user_c_nombre'] ?? 'No disponible';
    $usuarioModificador = $controlData['user_m_nombre'] ?? 'No modificado';
    
    $html = '
    <div class="row">
        <div class="col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-gear-fill me-1"></i>Información General</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Proceso:</strong><br>
                        <span class="text-muted">' . htmlspecialchars($controlData['proceso_control_temperatura']) . '</span>
                    </div>
                    <div class="mb-3">
                        <strong>IMEI Dispositivo:</strong><br>
                        <span class="badge bg-dark"><i class="bi bi-device-hdd me-1"></i>' . htmlspecialchars($controlData['imei_control_temperatura']) . '</span>
                    </div>
                    <div class="mb-3">
                        <strong>Tipo de Control:</strong><br>
                        <span class="badge ' . $tipoBadge . '">' . $tipo . '</span>
                    </div>
                    <div class="mb-3">
                        <strong>Total de Etapas:</strong><br>
                        <span class="badge bg-primary text-white fs-6">' . $controlData['total_control_temperatura'] . ' etapas</span>
                    </div>
                    <div class="mb-3">
                        <strong>Estado y Condición:</strong><br>
                        <span class="badge ' . $estadoBadge . '">' . $estado . '</span>
                        <span class="badge ' . $condicionBadge . ' ms-1">' . $condicionTexto . '</span>
                    </div>
                    <div class="mb-0">
                        <strong>Hora de Finalización:</strong><br>
                        <span class="text-muted"><i class="bi bi-clock me-1"></i>' . $horaFinFormatted . '</span>
                    </div>
                </div>
            </div>
            
            <div class="card border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-person-fill me-1"></i>Información de Usuarios</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Creado por:</strong><br>
                        <span class="text-muted"><i class="bi bi-person-plus me-1"></i>' . htmlspecialchars($usuarioCreador) . '</span><br>
                        <small class="text-muted">' . $fechaCreacion . '</small>
                    </div>
                    <div class="mb-0">
                        <strong>Modificado por:</strong><br>
                        <span class="text-muted"><i class="bi bi-person-gear me-1"></i>' . htmlspecialchars($usuarioModificador) . '</span><br>
                        <small class="text-muted">' . $fechaModificacion . '</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-list-ol me-1"></i>Etapas Programadas</h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">';
    
    if (isset($controlData['lista_control_temperatura']) && !empty($controlData['lista_control_temperatura'])) {
        foreach ($controlData['lista_control_temperatura'] as $index => $etapa) {
            // Formatear hora de inicio
            $horaInicio = $etapa['hora_inicio_etapa'];
            $horaInicioFormatted = $this->formatearFechaCustom($horaInicio);
            
            // Verificar si tiene humedad
            $humedadInfo = '';
            if (isset($etapa['humedad_etapa']) && $etapa['humedad_etapa'] !== null) {
                $humedadInfo = '<div class="small text-info">
                    <i class="bi bi-droplet me-1"></i>Humedad: ' . $etapa['humedad_etapa'] . '%
                </div>';
            }
            
            $html .= '
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary fs-6">Etapa ' . ($index + 1) . '</span>
                            <span class="badge bg-danger text-white fs-6">
                                <i class="bi bi-thermometer-half me-1"></i>' . $etapa['temperatura_etapa'] . '°C
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong class="text-primary">' . htmlspecialchars($etapa['nombre_etapa']) . '</strong>
                        </div>
                        <div class="small text-muted mb-1">
                            <i class="bi bi-clock me-1"></i>Inicio: ' . $horaInicioFormatted . '
                        </div>
                        ' . $humedadInfo . '
                    </div>';
        }
    } else {
        $html .= '
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-info-circle fs-1"></i>
                        <p class="mb-0 mt-2">No hay etapas programadas</p>
                    </div>';
    }
    
    $html .= '
                </div>
            </div>
        </div>
    </div>';
    
    return $html;
}

private function formatearFechaCustom($fechaString) {
    if (empty($fechaString) || $fechaString === 'No definida') {
        return 'No definida';
    }
    
    // Formato: 26-06-2025_17-57
    if (preg_match('/(\d{2})-(\d{2})-(\d{4})_(\d{2})-(\d{2})/', $fechaString, $matches)) {
        $dia = $matches[1];
        $mes = $matches[2]; 
        $año = $matches[3];
        $hora = $matches[4];
        $minuto = $matches[5];
        
        
        return "$dia/$mes/$año $hora:$minuto";
    }
    
    return $fechaString;
}

public function eliminarControl()
{
    $id_control = $_POST['id_control'] ?? 0;
    
    if ($id_control == 0) {
        echo json_encode(array(
            'success' => false,
            'message' => 'ID de control no válido'
        ));
        die();
    }
    
    $body = array(
        "especifico" => $id_control,
        "id_usuario" => 0,
        "tipo_usuario" => 0
    );
    
    $response = $this->model->eliminarControl($body);
    $result = json_decode($response, true);
    
    if ($result && $result['code'] == 200) {
        echo json_encode(array(
            'success' => true,
            'message' => 'Control eliminado exitosamente'
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Error al eliminar el control'
        ));
    }
    die();
}


}
?>
