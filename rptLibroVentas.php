<?php
require('estilos_reportes.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');

$codAnio=$_GET['codAnio'];
$codMes=$_GET['codMes'];

$fecha_reporte=date("d/m/Y");

echo "<h1>Libro de Ventas</h1>";

$sqlConf="select id, valor from configuracion_facturas where id=1";
$respConf=mysql_query($sqlConf);
$nombreTxt=mysql_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=9";
$respConf=mysql_query($sqlConf);
$nitTxt=mysql_result($respConf,0,1);

echo "<h3>Periodo Año: $codAnio  Mes: $codMes</h3>";
echo "<h3>Nombre o Razon Social: $nombreTxt  NIT: $nitTxt</h3>";


$sql="select f.nro_factura, DATE_FORMAT(f.fecha, '%d/%m/%Y'), f.importe, f.razon_social, f.nit, d.nro_autorizacion, e.nombre_estado, f.codigo_control
	from facturas_venta f, dosificaciones d, estados_factura e
	where f.cod_dosificacion=d.cod_dosificacion and e.cod_estado=f.cod_estado
	and YEAR(f.fecha)=$codAnio and MONTH(f.fecha)=$codMes order by f.fecha";
	
//echo $sql;
$resp=mysql_query($sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Nro.</th>
<th>FECHA FACTURA</th>
<th>NRO. FACTURA</th>
<th>NRO. AUTORIZACION</th>
<th>ESTADO</th>
<th>NIT/CI CLIENTE</th>
<th>NOMBRE O RAZON SOCIAL</th>
<th>IMPORTE TOTAL VENTA<br>A</th>
<th>IMPORTE ICE/ IEHD/ IPJ/TASAS/ OTROS NO SUJETOS AL IVA <br>B </th>
<th>EXPORTACIONES Y OPERACIONES EXENTAS <br> C </th>
<th>VENTAS GRAVADAS A TASA CERO <br> D</th>
<th>SUBTOTAL <br> E = A - B - C - D </th>
<th>DESCUENTOS, BONIFICACIONES Y REBAJAS SUJETAS AL IVA <br> F</th>
<th>IMPORTE BASE PARA DÉBITO FISCAL <br> G = E - F </th>
<th>DÉBITO FISCAL <br> H = G * 13%</th>
<th>CODIGO DE CONTROL</th>


</tr>";

$indice=1;
while($datos=mysql_fetch_array($resp)){	
	$nroFactura=$datos[0];
	$fecha=$datos[1];
	$importe=$datos[2];
	$razonSocial=$datos[3];
	$nit=$datos[4];
	$nroAutorizacion=$datos[5];
	$nombreEstado=$datos[6];
	$codigoControl=$datos[7];
	
	$montoVentaFormat=number_format($importe,2,".",",");
	$montoIVA=$importe*0.13;
	$montoIVAFormat=number_format($montoIVA,2,".",",");
	echo "<tr>
	<td>$indice</td>
	<td>$fecha</td>
	<td>$nroFactura</td>
	<td>$nroAutorizacion</td>
	<td>$nombreEstado</td>
	<td>$nit</td>
	<td>$razonSocial</td>
	<td>$montoVentaFormat</td>
	<td>0</td>
	<td>0</td>
	<td>0</td>
	<td>$montoVentaFormat</td>
	<td>0</td>
	<td>$montoVentaFormat</td>
	<td>$montoIVAFormat</td>
	<td>$codigoControl</td>
	</tr>";
}
echo "</table></br>";
?>