<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Utilidades x Documento e Item</h1>";
echo"<form method='post' action='rptUtilidadesDocItem.php' target='_blank'>";

	echo"<center><table class='texto'>\n";
	echo "<tr>
	<th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo[]' id='rpt_grupo' class='texto' size='5' onChange='ajaxReporteItems(this.form);' multiple>";
	$sql="select cod_grupo, nombre_grupo from grupos where estado=1 order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td>
			<input  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' name='exafinicial' required>";
    		echo"  </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td>
			<input type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' name='exaffinal' required>";
    		echo" </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>