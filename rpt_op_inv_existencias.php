<?php
echo "<script language='JavaScript'>
	function verReporteConsolidado(){
		location.href='rptOpExistenciasConsolidado.php';
	}
	</script>";
require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Existencias Almacen</h1>";

echo"<form method='post' action='rpt_inv_existencias.php' target='_blank'>";
	
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

	echo "<tr><th align='left'>Marca</th>
	<td><select name='rpt_grupo[]' class='selectpicker' size='10' data-actions-box='true' data-live-search='true' multiple>";
	$sql="select p.cod_linea_proveedor, p.nombre_linea_proveedor from proveedores_lineas p order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='selectpicker' data-style='btn btn-success'>";
	echo "<option value='1'>Todo</option>";
	echo "<option value='2'>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "</tr>";
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <td bgcolor='#ffffff'><input type='date' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha'>";
    		echo"  </td>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar Por:</th>";
	echo "<td><select name='rpt_ordenar' class='selectpicker' data-style='btn btn-success'>";
	echo "<option value='2'>Marca y Producto</option>";
	echo "<option value='1'>Producto</option>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Formato:</th>";
	echo "<td><select name='rpt_formato' class='selectpicker' data-style='btn btn-success'>";
	echo "<option value='1'>Normal</option>";
	echo "<option value='2'>Para Inventario</option>";
	echo "<option value='3'>Valorado con Precio Costo</option>";
	echo "<option value='4'>Valorado con Precio Venta</option>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center>
	<input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	<input type='button' name='reporte' value='Ir a Reporte Consolidado' onclick='verReporteConsolidado();' class='boton2'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>