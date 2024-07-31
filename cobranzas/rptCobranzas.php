<?php
require('../function_formatofecha.php');
require('../conexion.inc');
require('../funcion_nombres.php');
require('../funciones.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_cliente=$_GET['rpt_cliente'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Reporte Cobranzas
	<br>Almacén: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="SELECT c.cod_cobro, c.fecha_cobro, cd.nro_doc, concat(cl.nombre_cliente,' ',cl.paterno), concat(t.abreviatura,'-',s.nro_correlativo), cd.monto_detalle, c.observaciones
	FROM cobros_cab c, cobros_detalle cd, clientes cl, salida_almacenes s, tipos_docs t
	WHERE c.cod_cobro = cd.cod_cobro 
	AND t.codigo = s.cod_tipo_doc  
	AND c.cod_cliente = cl.cod_cliente 
	AND cd.cod_venta = s.cod_salida_almacenes 
	AND c.cod_estado <> 2
	AND c.fecha_cobro BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'";
if($rpt_cliente!=0){
	$sql=$sql." and cl.cod_cliente in ($rpt_cliente)";
}
$sql=$sql." order by 1";
$resp=mysqli_query($enlaceCon, $sql);

echo "<br><table cellspacing='0' border=1 align='center' class='texto' width='100%'>
<tr>
<th width='7%'>Nro.Cob</th>
<th width='7%'>Doc.Cob</th>
<th width='15%'>Fecha</th>
<th width='20%'>Cliente</th>
<th width='10%'>Nota Venta</th>
<th width='30%'>Observaciones</th>
<th width='10%'>Monto Cobranza</th>
</tr>";

$totalCobro=0;
while($datos=mysqli_fetch_array($resp)){	
	$codCobro=$datos[0];
	$fecha=$datos[1];
	$nroCobro=$datos[2];
	$cliente=$datos[3];
	$nroVenta=$datos[4];
	$montoCobro=$datos[5];

	$totalCobro=$totalCobro+$montoCobro;

	$montoCobro=formatonumeroDec($montoCobro);
	$obs=$datos[6];
		
	echo "<tr>
	<td align='center'>$codCobro</td>
	<td align='center'>$nroCobro</td>
	<td align='center'>$fecha</td>
	<td>$cliente</td>
	<td align='center'>$nroVenta</td>
	<td>$obs</td>
	<td align='right'>$montoCobro</td>
	</tr>";
}
$totalCobro=formatonumeroDec($totalCobro);

echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td align='right'>$totalCobro</td>
</tr>";

echo "</table>";
?>
