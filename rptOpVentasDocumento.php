<?php

require("conexion.inc");
require("estilos_almacenes.inc");


$fecha_inirptdefault=date("Y-01-01");
$fecha_rptdefault=date("Y-m-d");

echo "<h1>Reporte Ventas x Documento</h1>";

echo"<form method='post' action='rptVentasDocumento.php' target='_BLANK'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td>
		<select name='rpt_territorio[]' class='selectpicker' data-style='btn btn-success' data-live-search='true' multiple>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Documento:</th>
	<td><select name='rpt_tipodoc[]' id='rpt_tipodoc' class='texto' multiple>";
	echo "<option value='1' selected>FACTURA</option>";
	echo "<option value='2' selected>NOTA DE REMISION</option>";
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td>
			<input  type='date' class='texto' value='$fecha_inirptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </td>";
	echo "</tr>";

	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td>
			<input  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>