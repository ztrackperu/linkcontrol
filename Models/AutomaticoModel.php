<?php
class AutomaticoModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerControles($data)
{
    $ch = curl_init();
    $data = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, urlapicontrol."/Control/listar");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    
    return $res;
}

public function crearControl($data)
{
 
   
    $ch = curl_init();
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, urlapicontrol."/Control/");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
    
    
    
    return $res;
}
public function verControl($data)
{
    
    $ch = curl_init();
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, urlapicontrol."/Control/ver");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
   
    
    return $res;
}

public function eliminarControl($data)
{
    
    $ch = curl_init();
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, urlapicontrol."/Control/eliminar");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
   
    
    return $res;
}


public function obtenerContadores($data)
{
    
    $ch = curl_init();
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, urlapicontrol."/Control/estadistica");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);
   
    
    return $res;
}




}
?>
