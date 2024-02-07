<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");
?>

<script language='JavaScript'>
</script>

<?php
$fecha_rptdefault=date("Y-m-d");

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}
echo "<h1>Reporte Ventas x Sucursal y Tipo de Pago</h1><br>";

echo"<form method='post' action='rptVentasSucursalTipoPago.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	
	echo "<tr><th align='left'>Sucursal</th>
		<td>
			<select name='rpt_sucursal[]' id='rpt_sucursal' size='10' multiple required>";

	$globalAgencia=$_COOKIE["global_agencia"];
   
   	$sql="select c.cod_ciudad, c.descripcion from ciudades c where cod_estado=1 order by 2;";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigoCiudad=$dat[0];
		$nombreCiudad=$dat[1];
	   	echo "<option value='$codigoCiudad' selected>$nombreCiudad</option>";	
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left' class='text-muted'>Fecha inicio:</th>";
			echo" <td><input type='date' class='form-control' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini' required >";
    		echo"  </th>";
	echo "</tr>";
	echo "<tr><th align='left' class='text-muted'>Fecha final:</th>";
			echo"<td><input type='date' class='form-control' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin' required>";
    		echo"  </th>";
	echo "</tr>";
	
	echo"\n </table><br>";

	echo "<center>
			<input type='submit' name='reporte_detalle'  class='boton-verde' value='Ver Reporte x Mes' class='btn btn-success'>
	</center><br>";
	echo"</form>";
	echo "</div>";
?>