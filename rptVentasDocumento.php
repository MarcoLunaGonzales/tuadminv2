<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$fecha_ini=$_POST['exafinicial'];
$fecha_fin=$_POST['exaffinal'];

$codTipoDoc=$_POST['rpt_tipodoc'];
$codTipoDocString=implode(",", $codTipoDoc);

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rpt_territorio=$_POST['rpt_territorio'];
$rptTerritorioString=implode(",", $rpt_territorio);

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rptTerritorioString);

echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Ventas x Documento
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";


$sql="SELECT 
concat(s.`fecha`,' ',s.hora_salida)as fecha, 
(select concat(c.nombre_cliente,' ', c.paterno) from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, s.`razon_social`, s.`observaciones`, 
(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc), s.`nro_correlativo`, s.`monto_final`,
c.descripcion
from `salida_almacenes` s 
LEFT JOIN almacenes a ON a.cod_almacen=s.cod_almacen
LEFT JOIN ciudades c ON c.cod_ciudad=a.cod_ciudad
where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and c.cod_ciudad in ($rptTerritorioString) and 
s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.cod_tipo_doc in ($codTipoDocString) ";
$sql.=" order by s.fecha, s.nro_correlativo";

//echo $sql;
	
$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Sucursal</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>Documento</th>
<th>Monto</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$totalVenta=$totalVenta+$montoVenta;

	$nombreSucursal=$datos[7];
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreSucursal</td>
	<td>$nombreCliente</td>
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


?>