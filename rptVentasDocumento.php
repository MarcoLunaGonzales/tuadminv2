<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];
$rpt_ver=$_POST['rpt_ver'];
$codTipoPago=$_POST['tipo_pago'];

$stringTipoPago=implode(",",$codTipoPago);
$stringNombreTipoPago=nombreTipoPago($stringTipoPago);


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_POST['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Ventas x Documento
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte
	<br>Tipo de Pago: $stringNombreTipoPago</tr></table>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`,
	(select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago) as tipopago
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.cod_tipopago in ($stringTipoPago)";

if($rpt_ver==1){
	$sql.=" and s.estado_salida=4 ";
}

$sql.=" order by s.fecha, s.nro_correlativo";
	
	//echo $sql;

$resp=mysql_query($sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Tipo de Pago</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>Documento</th>
<th>Monto</th>
</tr>";

$totalVenta=0;
while($datos=mysql_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	
	$totalVenta=$totalVenta+$montoVenta;

	$nombreTipoPago=$datos[7];

	$montoVentaFormat=number_format($montoVenta,2,".",",");
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$nombreTipoPago</td>
	<td>$razonSocial</td>
	<td>$obsVenta</td>
	<td>$datosDoc</td>
	<td>$montoVentaFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalVentaFormat</td>
<tr>";

echo "</table></br>";
include("imprimirInc.php");
?>