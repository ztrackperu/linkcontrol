<?php
class AutomaticoModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getControlesAutomaticos($empresa_id)
    {
        $sql = "SELECT * FROM controles_automaticos WHERE empresa_id = $empresa_id ORDER BY id DESC";
        $res = $this->selectAll($sql);
        return $res;
    }

    public function crearControlAutomatico($datos)
    {
        $sql = "INSERT INTO controles_automaticos (tipo_control, etapa, programacion, horas, temperatura, humedad, empresa_id, estado) VALUES (?,?,?,?,?,?,?,?)";
        $array = array(
            $datos['tipo_control'],
            $datos['etapa'], 
            $datos['programacion'],
            $datos['horas'],
            $datos['temperatura'],
            $datos['humedad'],
            $datos['empresa_id'],
            1
        );
        
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }
}
?>
