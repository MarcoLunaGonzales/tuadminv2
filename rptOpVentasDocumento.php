<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<table align='center' class='textotit'><tr><th>Reporte Ventas x Documento</th></tr></table><br>";
echo"<form method='post' action='rptVentasDocumento.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";


	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Pago</th><td>
	<select name='tipo_pago[]' id='tipo_pago' class='texto' multiple size='4' >";
	$sql="select t.cod_tipopago, t.nombre_tipopago from tipos_pago t order by 1";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Ver:</th>
	<td><select name='rpt_ver' class='texto'>";
	echo "<option value='0'>Todos</option>";
	echo "<option value='1'>Ver No Cancelados</option>";
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini'>";
    		echo"  </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_fin' name='fecha_fin' size='10'>";
    		echo"  </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>