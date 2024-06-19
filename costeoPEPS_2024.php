<?php
require('conexionmysqli2.inc');
require("conexion.inc");
require("funcionRecalculoCostosPEPS_2024.php");


$inicioTotal = microtime(true);

$sqlItems="SELECT DISTINCT(sd.cod_material) from salida_almacenes s, salida_detalle_almacenes sd 
    where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen=1000 and 
    s.fecha BETWEEN '2024-01-01' and '2024-12-31' and s.salida_anulada=0";
$respItems=mysqli_query($enlaceCon, $sqlItems);

while($datItems=mysqli_fetch_array($respItems)){
    $codigoProducto=$datItems[0];

    $start_time = microtime(true);
    echo "Iniciando: ".$codigoProducto."    ********";
    $funcionX=recalculaCostosPEPS2024($enlaceCon, $codigoProducto, 1000);

    $end_time = microtime(true);
    $duration=$end_time-$start_time;
    $hours = (int)($duration/60/60);
    $minutes = (int)($duration/60)-$hours*60;
    $seconds = (int)$duration-$hours*60*60-$minutes*60;

    echo "<h6>Terminado: ".$minutes." min ".$seconds." seg</h6>";

}

    $finalTimeTotal = microtime(true);
    $duration=$finalTimeTotal-$inicioTotal;
    $hours = (int)($duration/60/60);
    $minutes = (int)($duration/60)-$hours*60;
    $seconds = (int)$duration-$hours*60*60-$minutes*60;

    echo "<h6>Terminado TODO  *****************: ".$minutes." min ".$seconds." seg</h6>";



?>