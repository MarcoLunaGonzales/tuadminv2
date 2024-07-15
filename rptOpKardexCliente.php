<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_inirptdefault=date("Y-01-01");
$fecha_rptdefault=date("Y-m-d");

echo "<h1>Reporte Kardex x Cliente</h1>";
echo"<form method='post' action='rptKardexCliente.php' target='_blank'>";

	echo"\n<center><table class='texto' width='50%'>\n";
	echo "<tr><th align='left'>Cliente</th>
	<td>
		<select name='rpt_cliente' class='selectpicker' data-style='btn btn-success' data-live-search='true'>";
	$sql="select cod_cliente, CONCAT(nombre_cliente,' ',paterno) from clientes order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td>
			<input  type='date' class='texto' value='$fecha_inirptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td>
				<input type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </td>";
	echo "</tr>";
	
	echo"\n </table></center><br>";

	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>