<?php

require("estilos_almacenes.inc");

$globalAgencia=$_COOKIE['global_agencia'];


echo "<h1>Reporte Existencias por Lote</h1>";

echo"<form method='post' action='rptInvExistenciasLote.php' target='_blank'>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysql_query($sql);
	echo "<option value='0'>Todos</option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($globalAgencia==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";


	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$globalAgencia'";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
	}
	echo "</select></td></tr>";


	echo "<tr><th align='left'>Producto</th>
	<td>
	<select name='rpt_productos[]' id='rpt_productos' class='texto' size='10' multiple>";
	$sql="SELECT m.codigo_material, m.descripcion_material, g.nombre_grupo FROM material_apoyo m 
		LEFT JOIN grupos g ON g.cod_grupo=m.cod_grupo
		where m.cod_tipomanejo=2 and m.estado=1";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1]." [".$dat[2]."]";
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";


	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>