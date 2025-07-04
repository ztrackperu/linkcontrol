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
            "id_usuario" => $_SESSION['id_ztrack'] 
            
        );
        
        $response = $this->model->obtenerControles($body);
        $data = json_decode($response, true);
        
        // Inicializar $html AQUÍ
        $html = '';
        
        if (empty($data['data']['resultado'])) {
            $html = '<tr><td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mb-0 mt-2">No hay controles Activos</p>
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

    public function listarHistorico()
{
    $body = array(
        "especifico" => 0,
        "id_usuario" =>  $_SESSION['id_ztrack'],
        "tipo_usuario" =>  $_SESSION['permiso_ztrack']  // Cambio aquí para obtener histórico
    );
    
    $response = $this->model->obtenerControles($body);
    $data = json_decode($response, true);
    
    // Inicializar $html AQUÍ
    $html = '';
    
    if (empty($data['data']['resultado'])) {
        $html = '<tr><td colspan="8" class="text-center text-muted py-4">
                    <i class="bi bi-archive fs-1"></i>
                    <p class="mb-0 mt-2">No hay controles en el histórico</p>
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
            
            // Formatear fecha de creación
            $fechaCreacion = isset($control['created_at']) ? 
                date('d/m/Y H:i', strtotime($control['created_at'])) : 'N/A';
            
            $html .= '<tr>
                <td class="fw-semibold">Control ' . $index . '</td>
                <td><span class="badge ' . $tipoBadge . '">' . $tipoTexto . '</span></td>
                <td class="small">
                    <i class="bi bi-device-hdd me-1"></i>' . $control['imei_control_temperatura'] . '
                </td>
                <td><span class="badge bg-primary text-white">' . $control['total_control_temperatura'] . '</span></td>
                <td><span class="badge ' . $estadoBadge . '">' . $estadoTexto . '</span></td>
                <td><span class="badge ' . $condicionBadge . '">' . $condicionTexto . '</span></td>
                <td class="small text-muted">' . $fechaCreacion . '</td>
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
        
        // Determinar el tipo numérico
        $tipoNumerico = 0; // Por defecto único
        if ($tipoControl === "unico") {
            $tipoNumerico = 0;
        } else if ($tipoControl === "ciclico") {
            $tipoNumerico = 1;
        } else if ($tipoControl === "periodico") {
            $tipoNumerico = 0; // CAMBIADO: Periódico también es tipo 0
        }
        
        // Construir el JSON según el formato requerido
        $jsonData = array(
            "proceso_control_temperatura" => "",
            "tipo_control_temperatura" => $tipoNumerico,
            "user_c" => $_SESSION['id_ztrack'],
            "lista_control_temperatura" => array()
        );
        
        if ($tipoControl === "unico") {
            // Procesar formularios únicos
            $etapas = $_POST['etapa'];
            $fechasInicio = $_POST['fechaHoraInicio'];
            $temperaturas = $_POST['temperatura'];
            
            // Nombre del proceso para único
            $jsonData['proceso_control_temperatura'] = $_POST['nombrep'] ?? 'Proceso Único';
            
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
                    // Único no tiene humedad
                );
            }
            
        } else if ($tipoControl === "periodico") {
            // Procesar formularios periódicos con lógica de ciclos
            $etapas = $_POST['etapa'];
            $fechasHorasInicio = $_POST['fechaHoraInicio']; // Fechas y horas de inicio de cada etapa template
            $temperaturas = $_POST['temperatura'];
            $humedades = $_POST['humedad'];
            $duraciones = $_POST['duracion']; // Duración de cada etapa en horas
            
            // Nombre del proceso para periódico
            $jsonData['proceso_control_temperatura'] = $_POST['nombreProcesoPeriodico'] ?? 'Proceso Periódico';
            
            // Horas totales del proceso
            $horasProceso = floatval($_POST['horasProceso'] ?? 24);
            
            // Fecha y hora de inicio del proceso completo (tomar la primera fecha como referencia)
            $fechaHoraInicioProceso = !empty($fechasHorasInicio[0]) ? $fechasHorasInicio[0] : date('Y-m-d\TH:i');
            
            // Agregar campos específicos para periódico según el JSON de ejemplo
            $jsonData['Hora inicio'] = $this->convertirFechaFormato($fechaHoraInicioProceso);
            $jsonData['Horas_proceso'] = $horasProceso;
            
            // Calcular fecha fin basada en horas del proceso
            $fechaInicio = DateTime::createFromFormat('Y-m-d\TH:i', $fechaHoraInicioProceso);
            if (!$fechaInicio) {
                $fechaInicio = new DateTime();
            }
            $fechaFin = clone $fechaInicio;
            $fechaFin->add(new DateInterval('PT' . intval($horasProceso) . 'H'));
            $jsonData['hora_fin_control_temperatura'] = $fechaFin->format('d-m-Y_H-i');
            
            // Generar el ciclo automático de etapas hasta completar las horas totales
            $etapasCiclo = $this->generarCicloPeriodico(
                $etapas, 
                $fechasHorasInicio, 
                $temperaturas, 
                $humedades, 
                $duraciones, 
                $horasProceso, 
                $fechaHoraInicioProceso
            );
            
            $jsonData['lista_control_temperatura'] = $etapasCiclo;
            
        } else if ($tipoControl === "ciclico") {
            // Procesar formularios cíclicos
            $etapas = $_POST['etapa'];
            $horas = $_POST['hora'];
            $temperaturas = $_POST['temperatura'];
            
            // Nombre del proceso para cíclico
            $jsonData['proceso_control_temperatura'] = $_POST['nombrep'] ?? 'Proceso Cíclico';
            
            $jsonData['hora_fin_control_temperatura'] = 'No tiene hora de finalización, se repetirá indefinidamente';
            
            for ($i = 0; $i < count($etapas); $i++) {
                // Para cíclico, usar solo la hora (sin fecha específica)
                $fechaHoy = date('Y-m-d');
                $horaCompleta = $fechaHoy . 'T' . $horas[$i];
                
                $jsonData['lista_control_temperatura'][] = array(
                    "hora_inicio_etapa" => $this->convertirFechaFormato($horaCompleta),
                    "nombre_etapa" => $etapas[$i],
                    "temperatura_etapa" => floatval($temperaturas[$i])
                    // Cíclico no tiene humedad
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
                "id_usuario" => $_SESSION['id_ztrack']
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
    
    /**
     * Genera el ciclo periódico de etapas hasta completar las horas totales
     * Basado en el JSON de ejemplo proporcionado
     */
    private function generarCicloPeriodico($etapas, $fechasHorasInicio, $temperaturas, $humedades, $duraciones, $horasTotales, $fechaHoraInicioProceso)
    {
        $etapasCiclo = array();
        $horasAcumuladas = 0;
        $indiceEtapa = 0;
        $contadorCiclo = 0;
        
        // Convertir fecha de inicio del proceso a DateTime
        $fechaActual = DateTime::createFromFormat('Y-m-d\TH:i', $fechaHoraInicioProceso);
        if (!$fechaActual) {
            // Si no se puede parsear, usar la primera fecha del formulario
            $fechaActual = DateTime::createFromFormat('Y-m-d\TH:i', $fechasHorasInicio[0]);
            if (!$fechaActual) {
                $fechaActual = new DateTime(); // Fallback a fecha actual
            }
        }
        
        while ($horasAcumuladas < $horasTotales) {
            // Obtener datos de la etapa actual del ciclo
            $nombreEtapa = $etapas[$indiceEtapa];
            $temperatura = floatval($temperaturas[$indiceEtapa]);
            $humedad = $humedades[$indiceEtapa];
            $duracion = floatval($duraciones[$indiceEtapa]);
            
            // Verificar si agregar esta etapa completa excedería las horas totales
            if (($horasAcumuladas + $duracion) > $horasTotales) {
                // Ajustar la duración para no exceder las horas totales
                $duracion = $horasTotales - $horasAcumuladas;
            }
            
            // Agregar la etapa al ciclo con el formato del JSON de ejemplo
            $etapasCiclo[] = array(
                "hora_inicio_etapa" => $fechaActual->format('d-m-Y_H-i'),
                "nombre_etapa" => $nombreEtapa,
                "temperatura_etapa" => $temperatura,
                "humedad" => $humedad . "%",
                "duracion" => $duracion
            );
            
            // Actualizar contadores y fecha para la siguiente etapa
            $horasAcumuladas += $duracion;
            
            // Calcular la fecha de inicio de la siguiente etapa
            $minutosAgregar = intval($duracion * 60); // Convertir horas a minutos
            $fechaActual->add(new DateInterval('PT' . $minutosAgregar . 'M'));
            
            // Avanzar al siguiente índice de etapa (ciclo circular)
            $indiceEtapa = ($indiceEtapa + 1) % count($etapas);
            $contadorCiclo++;
            
            // Protección contra bucle infinito
            if ($contadorCiclo > 1000) {
                break;
            }
        }
        
        return $etapasCiclo;
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
        try {
            $zonaLima = new DateTimeZone('America/Lima');
    
            // Intentar parsear formato HTML5 datetime-local
            $fecha = DateTime::createFromFormat('Y-m-d\TH:i', $fechaHtml, $zonaLima);
            if ($fecha) {
                return $fecha->format('d-m-Y_H-i');
            }
    
            // Intentar otros formatos comunes
            $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $fechaHtml, $zonaLima);
            if ($fecha) {
                return $fecha->format('d-m-Y_H-i');
            }
    
            $fecha = DateTime::createFromFormat('Y-m-d H:i', $fechaHtml, $zonaLima);
            if ($fecha) {
                return $fecha->format('d-m-Y_H-i');
            }
    
        } catch (Exception $e) {
            // Si hay error, continuar con fallback
        }
    
        // Fallback: retornar fecha actual en zona Lima
        return (new DateTime('now', new DateTimeZone('America/Lima')))->format('d-m-Y_H-i');
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
        "id_usuario" =>  $_SESSION['id_ztrack'],
        "tipo_usuario" => $_SESSION['permiso_ztrack']
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
        "id_usuario" =>  $_SESSION['id_ztrack'],
        "tipo_usuario" => $_SESSION['permiso_ztrack']
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


public function obtenerContadores()
{

    $body = array(
        "especifico" => 0,
        "id_usuario" => $_SESSION['id_ztrack'],
        "tipo_usuario" => $_SESSION['permiso_ztrack'] 
    );

    $response = $this->model->obtenerContadores($body);
    $result = json_decode($response, true);
    
    if ($result && $result['code'] == 200 && isset($result['data'])) {
        $data = $result['data'];
        
        // Obtener fecha de hoy en formato dd_mm_yyyy
        $fechaHoy = date('d_m_Y');
        
        // Buscar todas las fechas disponibles (excluyendo campos especiales)
        $fechasDisponibles = array();
        $camposEspeciales = ['general', 'modulo', 'created_at', 'updated_at'];
        
        foreach ($data as $key => $value) {
            if (!in_array($key, $camposEspeciales) && is_array($value)) {
                $fechasDisponibles[] = $key;
            }
        }
        
        // Ordenar fechas (más reciente primero)
        rsort($fechasDisponibles);
        
        // Preparar respuesta con contadores
        $contadores = array(
            'general' => array(
                'creado' => $data['general']['creado'] ?? 0,
                'eliminado' => $data['general']['eliminado'] ?? 0,
                'reestablecido' => $data['general']['reestablecido'] ?? 0
            ),
            'hoy' => array(
                'creado' => $data[$fechaHoy]['creado'] ?? 0,
                'eliminado' => $data[$fechaHoy]['eliminado'] ?? 0,
                'reestablecido' => $data[$fechaHoy]['reestablecido'] ?? 0
            ),
            'fecha_actual' => $fechaHoy,
            'fecha_actual_formateada' => $this->formatearFechaCustom(str_replace('_', '-', $fechaHoy) . '_00-00'),
            'fechas_disponibles' => $fechasDisponibles,
            'fechas_formateadas' => $this->formatearFechasArray($fechasDisponibles),
            'todas_las_fechas' => $this->obtenerDatosPorFechas($data, $fechasDisponibles),
            'updated_at' => $data['updated_at'] ?? null,
            'created_at' => $data['created_at'] ?? null
        );
        
        echo json_encode(array(
            'success' => true,
            'data' => $contadores
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Error al obtener contadores'
        ));
    }
    die();
}

private function formatearFechasArray($fechas)
{
    $fechasFormateadas = array();
    foreach ($fechas as $fecha) {
        // Usar tu función existente formatearFechaCustom
        // Convertir dd_mm_yyyy a dd-mm-yyyy_00-00 para que funcione con tu función
        $fechaConvertida = str_replace('_', '-', $fecha) . '_00-00';
        $fechasFormateadas[$fecha] = $this->formatearFechaCustom($fechaConvertida);
    }
    return $fechasFormateadas;
}

private function obtenerDatosPorFechas($data, $fechas)
{
    $datosPorFechas = array();
    foreach ($fechas as $fecha) {
        if (isset($data[$fecha])) {
            // Convertir dd_mm_yyyy a dd-mm-yyyy_00-00 para que funcione con tu función
            $fechaConvertida = str_replace('_', '-', $fecha) . '_00-00';
            $datosPorFechas[$fecha] = array(
                'creado' => $data[$fecha]['creado'] ?? 0,
                'eliminado' => $data[$fecha]['eliminado'] ?? 0,
                'reestablecido' => $data[$fecha]['reestablecido'] ?? 0,
                'fecha_formateada' => $this->formatearFechaCustom($fechaConvertida)
            );
        }
    }
    return $datosPorFechas;
}



}
?>
