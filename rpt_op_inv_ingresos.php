<?php
echo "<script language='JavaScript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxAlmacen(){
	var contenedor;
	contenedor = document.getElementById('divAlmacen');
	var codTerritorio = document.getElementById('rpt_territorio').value;
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxAlmacenes.php?codTerritorio='+codTerritorio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}else{
			contenedor.innerHTML = 'Cargando...';
		}
	}
	ajax.send(null)
}
		</script>";

require("conexionmysqli.php");
require("estilos_almacenes.inc");


$fecha_rptdefault=date("d/m/Y");
echo "<h1>Reporte Ingresos Almacen</h1>";

echo"<form method='post' action='rpt_inv_ingresos.php' target='_blank'>";
	echo"\n<table class='texto' align='center'>\n";

	echo "<tr><th align='left'>Almacen</th>
	<td>";
	$sql="SELECT cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon,$sql);

	echo "<select name='rpt_almacen' class='selectpicker' data-style='btn btn-success' id='rpt_almacen' required>";
	while($dat=mysqli_fetch_array($resp)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select>";
	echo "</td></tr>";

	echo "<tr><th align='left'>Tipo de Ingreso</th>";
	$sql_tipoingreso="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
	//echo $sql_tipoingreso;
	$resp_tipoingreso=mysqli_query($enlaceCon,$sql_tipoingreso);
	echo "<td><select name='tipo_ingreso[]' class='texto' size='5' multiple required>";
	while($datos_tipoingreso=mysqli_fetch_array($resp_tipoingreso))
	{	$codigo_tipoingreso=$datos_tipoingreso[0];
		$nombre_tipoingreso=$datos_tipoingreso[1];
		echo "<option value='$codigo_tipoingreso' selected>$nombre_tipoingreso</option>";
	}
	echo "</select></td>";
	
	echo "<tr><th align='left'>Marca</th>";
	$sqlProveedor="select cod_proveedor, nombre_proveedor from proveedores order by 2";
	//echo $sql_tipoingreso;
	$respProveedor=mysqli_query($enlaceCon,$sqlProveedor);
	echo "<td><select name='proveedor[]' class='texto' size='5' multiple required>";
	while($datosProveedor=mysqli_fetch_array($respProveedor))
	{	$codigo=$datosProveedor[0];
		$nombre=$datosProveedor[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td>";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td bgcolor='#ffffff'>
				<input type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial' required>
			</td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>
				</td>";
	echo "</tr>";

	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>