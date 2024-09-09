<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('funciones.php');
require('funcion_nombres.php');
require('conexionmysqli2.inc');

$rptAlmacenOrigen=$_POST["rpt_almacen"];
$rptAlmacenDestino=$_POST["rpt_almacen_destino"];
$rptFechaInicio=$_POST["fecha_inicio"];
$rptFechaFinal=$_POST["fecha_final"];

$rptTipoReporte = $_POST['tipo_reporte'];

$rptAlmacenDestinoString = implode(', ', $rptAlmacenDestino);

$nombreAlmacenOrigen=nombreAlmacen($rptAlmacenOrigen);

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

$nombre_tiporeporte="Reporte Seguimiento de Traspasos ".($rptTipoReporte == 0 ? "(DETALLADO)" : "(RESUMIDO)");

echo "<h1>$nombre_tiporeporte<br>
Fecha inicio: <strong>$rptFechaInicio</strong> Fecha final: <strong>$rptFechaFinal</strong> <br>
$txt_reporte<br>
Almacen de Origen: <strong><span class='textomedianorojo'>$nombreAlmacenOrigen</span></strong>
</h1>";

$fecha_iniconsulta=$rptFechaInicio;
$fecha_finconsulta=$rptFechaFinal;


$sql="SELECT concat(s.fecha,' ',s.hora_salida)as fecha, s.nro_correlativo, 
		(select a.nombre_almacen from almacenes a where a.cod_almacen=s.almacen_destino)as almacen_destino, 
		(select e.nombre_estado from estados_salida e where e.cod_estado=s.estado_salida) as estado, s.observaciones, sd.cod_material, m.codigo_anterior, m.descripcion_material, 
		".($rptTipoReporte == 0 ? "sd.cantidad_unitaria" : "SUM(sd.cantidad_unitaria) cantidad_unitaria").", 
		".($rptTipoReporte == 0 ? "s.cod_salida_almacenes" : "GROUP_CONCAT(s.cod_salida_almacenes ORDER BY s.cod_salida_almacenes) as cod_salida_almacenes")."
	FROM salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m 
	WHERE s.cod_salida_almacenes=sd.cod_salida_almacen and sd.cod_material=m.codigo_material and s.cod_tiposalida=1000 and
	s.cod_almacen='$rptAlmacenOrigen' and s.salida_anulada=0 and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and 
	s.almacen_destino in ($rptAlmacenDestinoString)".($rptTipoReporte == 1 ? " GROUP BY sd.cod_material" : '');

// echo $sql;

$resp=mysqli_query($enlaceCon, $sql);
echo "<center><br><table class='texto'>";
echo "<tr>
	<th>#</th>";
if($rptTipoReporte == 0){ // DETALLADO
	echo "<th>Fecha</th>
	<th>Nro.Salida</th>";
}
echo "<th>Almacen Destino</th>
	<th>Estado</th>
	<th>Observaciones</th>
	<th>Cod.Interno</th>
	<th>Producto</th>
	<th>Cantidad Traspaso</th>";
if($rptTipoReporte == 0){ // DETALLADO
	echo "<th>Fecha Ingreso</th>
		<th>Nro. Ingreso</th>";
}
echo "<th>Cantidad Ingreso</th>";
echo "</tr>";
$cantidad_registro = 1;
while($dat=mysqli_fetch_array($resp))
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
	$cantidadProductoF=formatNumberInt($cantidadProducto);
	$codSalidaAlmacen=$dat[9];

	echo "<tr>
		<td>".$cantidad_registro++."</td>";
	
	if($rptTipoReporte == 0){ // DETALLADO
		echo "<td align='center'>$fecha_salida</td>
			<td align='center'>$nro_correlativo</td>";
	}
	echo "<td>$nombreAlmacenDestino</td>
		<td>$nombreEstadoSalida</td>
		<td>$obs_salida</td>
		<td>$codInterno</td>
		<td>$nombreProducto</td>
		<td align='right'>$cantidadProductoF</td>";
	
	$sqlIngreso="SELECT CONCAT(i.fecha,' ',i.hora_ingreso)as fecha, id.cod_material, ".($rptTipoReporte == 0 ? "id.cantidad_unitaria": " SUM(id.cantidad_unitaria)").", i.nro_correlativo
				FROM ingreso_almacenes i, ingreso_detalle_almacenes id 
				WHERE i.cod_ingreso_almacen=id.cod_ingreso_almacen 
				AND id.cod_material='$codProducto' ".
				($rptTipoReporte == 0 ? " AND i.cod_salida_almacen='$codSalidaAlmacen'": " AND i.cod_salida_almacen IN ($codSalidaAlmacen)");
				
	$respIngreso=mysqli_query($enlaceCon, $sqlIngreso);
	$cantidadIngreso=0;
	$fechaIngreso="";
	if($datIngreso=mysqli_fetch_array($respIngreso)){
		$fechaIngreso=$datIngreso[0];
		$nro_correlativo_ingreso = $datIngreso[3];
		$cantidadIngreso=$datIngreso[2];
		$cantidadIngresoF=formatNumberInt($cantidadIngreso);
	}
	
	if($rptTipoReporte == 0){ // DETALLADO
		echo "<td bgcolor='#00FFA6'>$fechaIngreso</td>
			<td bgcolor='#00FFA6' align='center'>$nro_correlativo_ingreso</td>";
	}
	echo "<td bgcolor='#00FFA6' align='right'>$cantidadIngresoF</td>
		</tr>";

	}
	echo "</table></center><br>";

?>