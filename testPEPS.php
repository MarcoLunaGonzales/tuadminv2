<?php

set_time_limit(0);

require("conexion.inc");
require("funcionRecalculoCostosPEPS.php");

$inicio=$_GET["inicio"];
$fin=$_GET["fin"];


$sqlItems="SELECT m.codigo_material, m.descripcion_material, count(*)
    from ingreso_almacenes i, ingreso_detalle_almacenes id, material_apoyo m 
    where i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material=m.codigo_material 
    and i.cod_almacen=1000 and i.ingreso_anulado=0 
    GROUP BY m.codigo_material, m.descripcion_material order by 3 desc 
    limit $inicio,$fin";

echo $sqlItems;
$respItems=mysql_query($sqlItems);

$inicioTotal = microtime(true);

while($datItems=mysql_fetch_array($respItems)){
    $codItem=$datItems[0];
    $nombreItem=$datItems[1];

    $start_time = microtime(true);
    echo "Iniciando: ".$codItem." ".$nombreItem."    ********";
    $funcionX=recalculaCostosPEPS($codItem,1000);

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

    echo "<h6>Terminado: ".$minutes." min ".$seconds." seg</h6>";


?>