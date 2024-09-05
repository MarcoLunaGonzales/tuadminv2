<?php

/*echo"<head><title>Modulo Central de Inventarios</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
require("conexionmysqlipdf.inc");
$sql="select paterno, materno, nombres from funcionarios where codigo_funcionario=$global_usuario";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$paterno=$dat[0];
$materno=$dat[1];
$nombre=$dat[2];
$nombre_completo="$paterno $materno $nombre";
$sql="select descripcion from ciudades where cod_ciudad=$global_agencia";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$agencia=$dat[0];
if($global_usuario==1062 or $global_usuario==1120)
{	$responsable_global_almacen=1061;
}
else
{	$responsable_global_almacen=$global_usuario;
}
$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia' and responsable_almacen='$responsable_global_almacen'";
$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
$dat_almacen=mysqli_fetch_array($resp_almacen);
$global_almacen=$dat_almacen[0];
$nombre_global_almacen=$dat_almacen[1];
//sacamos la fecha y la hora
$fecha_sistema=date("d-m-Y");
$hora_sistema=date("H:i");
echo "<center><table width='100%' border=1 cellspacing=0 class='linea1' bordercolor='#000000'><tr><th colspan='3'>Modulo Central de Inventarios<th></tr><tr><th>Almacen $agencia</th><th>Usuario:$nombre_completo </th><th>Almacen: $nombre_global_almacen</th></tr><tr><th colspan='2'>Fecha del Servidor: $fecha_sistema</th><th>Hora del Servidor: $hora_sistema</th></tr></table></center><br>";
*/
?>