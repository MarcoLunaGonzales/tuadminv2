<?php
echo"<head><title>Reportes</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
require("conexion.inc");   
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
if($global_usuario==1062)
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
echo "<center>Almacen $agencia<br>Funcionario: $nombre_completo Nombre Almacen: $nombre_global_almacen</center><br>";
?>