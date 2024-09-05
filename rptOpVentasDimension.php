<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");


$fecha_rptdefault=date("Y-m-01");
$fecha_rptdefault2=date("Y-m-d");
echo "<h1>Estadisticos de Venta</h1>";
echo"<form method='post' action='rptVentasDimension.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Almacen</th><td>
	<select name='rpt_territorio[]' class='selectpicker form-control' data-style='btn btn-success' multiple size='10'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini'>";
    		echo"  </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault2' id='fecha_fin' size='10' name='fecha_fin'>";
    		echo"  </td>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar por:</th><td><select name='rpt_ordenar' class='selectpicker' data-style='btn btn-warning' required>";
	echo "<option value='1'>Monto</option>";
	echo "<option value='2'>Cantidad</option>";
	echo "</select></td></tr>";

	
	echo"\n </table><br>";
	echo "<center>
			<input type='submit' name='reporte' value='Reporte x Grupo'  formaction='rptVentasDimensionGrupo.php' class='boton-azul'>
			<input type='submit' name='reporte' value='Reporte x Tipo de Aro'  formaction='rptVentasDimensionTipoAro.php' class='boton-verde'>
			<input type='submit' name='reporte' value='Reporte x Marca'  formaction='rptVentasDimensionMarca.php' class='boton2'>
			<input type='submit' name='reporte' value='Reporte x País de Procedencia'  formaction='rptVentasDimension.php' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>