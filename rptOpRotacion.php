<?php
echo "<script language='JavaScript'>
	function verReporteConsolidado(){
		location.href='rptOpExistenciasConsolidado.php';
	}
	</script>";
require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Rotacion de Productos</h1>";

echo"<form method='post' action='rptRotacion.php' target='_blank'>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";

	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' id='rpt_almacen' class='selectpicker' data-style='btn btn-success'>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
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

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo[]' class='selectpicker' size='10' multiple>";
	$sql="select p.cod_linea_proveedor, p.nombre_linea_proveedor from proveedores_lineas p order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <td bgcolor='#ffffff'><input type='date' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha' readonly>";
    		echo"  </td>";
	echo "</tr>";


	echo "<tr><th align='left'>Rotacion Inicio:</th>";
			echo" <td bgcolor='#ffffff'><input type='date' class='texto' value='$fecha_rptdefault' id='rpt_rotacionini' size='10' name='rpt_rotacionini'>";
    		echo"  </td>";
	echo "</tr>";

	echo "<tr><th align='left'>Rotacion Fin:</th>";
			echo" <td bgcolor='#ffffff'><input type='date' class='texto' value='$fecha_rptdefault' id='rpt_rotacionfin' size='10' name='rpt_rotacionfin'>";
    		echo"  </td>";
	echo "</tr>";

	
	echo"\n </table><br>";
	echo "<center>
	<input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>