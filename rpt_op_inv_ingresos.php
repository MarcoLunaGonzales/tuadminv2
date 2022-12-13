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
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}$fecha_rptdefault=date("d/m/Y");
echo "<h1>Reporte Ingresos Almacen</h1>";

echo"<form method='post' action='rpt_inv_ingresos.php' target='_blank'>";
	echo"\n<table class='texto' align='center'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' id='rpt_territorio' class='texto' onChange='ajaxAlmacen(this);' required>";
	if($global_tipoalmacen==1)
	{	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	}
	else
	{	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia' order by descripcion";
	}
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Almacen</th><td>
	<div id='divAlmacen'></div>
	</td></tr>";

	echo "<tr><th align='left'>Tipo de Ingreso</th>";
	$sql_tipoingreso="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
	//echo $sql_tipoingreso;
	$resp_tipoingreso=mysql_query($sql_tipoingreso);
	echo "<td><select name='tipo_ingreso[]' class='texto' size='5' multiple required>";
	while($datos_tipoingreso=mysql_fetch_array($resp_tipoingreso))
	{	$codigo_tipoingreso=$datos_tipoingreso[0];
		$nombre_tipoingreso=$datos_tipoingreso[1];
		echo "<option value='$codigo_tipoingreso' selected>$nombre_tipoingreso</option>";
	}
	echo "</select></td>";
	
	echo "<tr><th align='left'>Proveedor</th>";
	$sqlProveedor="select cod_proveedor, nombre_proveedor from proveedores order by 2";
	//echo $sql_tipoingreso;
	$respProveedor=mysql_query($sqlProveedor);
	echo "<td><select name='proveedor[]' class='texto' size='5' multiple required>";
	while($datosProveedor=mysql_fetch_array($respProveedor))
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