<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Seguimiento de Traspasos</h1>";

echo"<form method='post' action='rpt_inv_traspasos.php' target='_blank'>";
	echo"\n<table class='texto' align='center'>\n";

	echo "<tr><th align='left'>Almacen Origen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
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

	echo "<tr><th align='left'>Almacen Destino</th><td><select name='rpt_almacen_destino[]' class='texto' size='10' multiple>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Salida</th>";
	$sql_tiposalida="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida=1000 
	 order by nombre_tiposalida";
	$resp_tiposalida=mysqli_query($enlaceCon, $sql_tiposalida);
	echo "<td><select name='tipo_salida' class='texto'>";
	while($datos_tiposalida=mysqli_fetch_array($resp_tiposalida))
	{	$codigo_tiposalida=$datos_tiposalida[0];
		$nombre_tiposalida=$datos_tiposalida[1];
		if($tipo_salida==$codigo_tiposalida)
		{	echo "<option value='$codigo_tiposalida' selected>$nombre_tiposalida</option>";
		}
		else
		{	echo "<option value='$codigo_tiposalida'>$nombre_tiposalida</option>";
		}
	}
	echo "</select></td>";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_inicio' size='10' name='fecha_inicio' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_final' size='10' name='fecha_final' required>";
    		echo"  </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";

?>