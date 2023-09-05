<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Salidas Almacen</h1>";

echo"<form method='post' action='rpt_inv_salidas.php' target='_blank'>";
	echo"\n<table class='texto' align='center'>\n";

	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen)
		{	echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		}
		else
		{	echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Salida</th>";
	$sql_tiposalida="select cod_tiposalida, nombre_tiposalida from tipos_salida 
	 order by nombre_tiposalida";
	$resp_tiposalida=mysql_query($sql_tiposalida);
	echo "<td><select name='tipo_salida[]' id='tipo_salida' multiple size='10' class='texto'>";
	while($datos_tiposalida=mysql_fetch_array($resp_tiposalida))
	{	$codigo_tiposalida=$datos_tiposalida[0];
		$nombre_tiposalida=$datos_tiposalida[1];
		echo "<option value='$codigo_tiposalida' selected>$nombre_tiposalida</option>";
	}
	echo "</select></td>";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>