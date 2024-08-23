<?php

require("conexion.inc");
require("estilos_almacenes.inc");


$fecha_rptdefault=date("d/m/Y");

echo "<h1>Reporte Precios</h1><br>";

echo"<form method='post' action='rptPrecios.php' target='_blank'>";
	
	echo"<table class='texto' align='center' cellSpacing='0' width='50%'>";	

	echo "<tr><th align='left'>Marca</th>";
	echo "<td>
	<select name='rpt_marca[]' class='selectpicker form-control' data-size='10' data-style='btn btn-success'  data-live-search='true' data-actions-box='true' multiple>";
	$sql="select pl.cod_linea_proveedor, pl.nombre_linea_proveedor from proveedores_lineas pl
		INNER JOIN proveedores p ON p.cod_proveedor=pl.cod_proveedor and 
		pl.estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupo</th>";
	echo "<td>
	<select name='rpt_grupo[]' class='selectpicker form-control' data-size='10' data-style='btn btn-success'  data-live-search='true' data-actions-box='true' multiple>";
	$sql="select cod_grupo, nombre_grupo from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo"</table><br>";

	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>