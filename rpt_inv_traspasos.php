<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('funciones.php');
require('conexion.inc');

$rptAlmacenOrigen=$_POST["rpt_almacen"];
$rptAlmacenDestino=$_POST["rpt_almacen_destino"];
$rptFechaInicio=$_POST["fecha_inicio"];
$rptFechaFinal=$_POST["fecha_final"];

$rptAlmacenDestinoString = implode(', ', $rptAlmacenDestino);

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

$nombre_tiporeporte="Reporte Seguimiento de Traspasos";

echo "<h1>$nombre_tiporeporte<br>$nombre_tiposalidamostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong> <br>$txt_reporte</th></tr></table>";

$fecha_iniconsulta=$rptFechaInicio;
$fecha_finconsulta=$rptFechaFinal;


$sql="select concat(s.fecha,' ',s.hora_salida)as fecha, s.nro_correlativo, 
(select a.nombre_almacen from almacenes a where a.cod_almacen=s.almacen_destino)as almacen_destino, 
(select e.nombre_estado from estados_salida e where e.cod_estado=s.estado_salida) as estado, s.observaciones, sd.cod_material, m.codigo_anterior, m.descripcion_material, sd.cantidad_unitaria, s.cod_salida_almacenes
 from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m 
 where s.cod_salida_almacenes=sd.cod_salida_almacen and sd.cod_material=m.codigo_material and s.cod_tiposalida=1000 and
s.cod_almacen='$rptAlmacenOrigen' and s.salida_anulada=0 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and 
s.almacen_destino in ($rptAlmacenDestinoString)";

//echo $sql;

$resp=mysql_query($sql);
echo "<center><br><table class='texto'>";
echo "<tr><th>Fecha</th><th>Nro.Salida</th><th>Almacen Destino</th><th>Estado</th><th>Observaciones</th><th>Cod.Interno</th>
<th>Producto</th><th>Cantidad Traspaso</th><th>Fecha Ingreso</th><th>Nro. Ingreso</th><th>Cantidad Ingreso</th></tr>";
while($dat=mysql_fetch_array($resp))
{
	$fecha_salida=$dat[0];
	$nro_correlativo=$dat[1];
	$nombreAlmacenDestino=$dat[2];
	$nombreEstadoSalida=$dat[3];
	$obs_salida=$dat[4];
	
	$codProducto=$dat[5];
	$codInterno=$dat[6];
	$nombreProducto=$dat[7];
	$cantidadProducto=$dat[8];
	$codSalidaAlmacen=$dat[9];

	$sqlIngreso="select CONCAT(i.fecha,' ',i.hora_ingreso)as fecha, id.cod_material, id.cantidad_unitaria, i.nro_correlativo
	from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material='$codProducto' and i.cod_salida_almacen='$codSalidaAlmacen'";
	$respIngreso=mysql_query($sqlIngreso);
	$cantidadIngreso=0;
	$fechaIngreso="";
	if($datIngreso=mysql_fetch_array($respIngreso)){
		$fechaIngreso=$datIngreso[0];
		$cantidadIngreso=$datIngreso[2];
		$nroIngreso=$datIngreso[3];
	}

	echo "<tr><td align='center'>$fecha_salida</td><td align='center'>$nro_correlativo</td>
	<td>$nombreAlmacenDestino</td><td>$nombreEstadoSalida</td><td>$obs_salida</td><td>$codInterno</td>
	<td>$nombreProducto</td><td>$cantidadProducto</td>
	<td bgcolor='#00FFA6'>$fechaIngreso</td><td bgcolor='#00FFA6'>$nroIngreso</td><td bgcolor='#00FFA6'>$cantidadIngreso</td>
	</tr>";

	}
	echo "</table></center><br>";

?>