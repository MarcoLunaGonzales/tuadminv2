<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_salida,fecha_ini, fecha_fin, tipo_item, rpt_linea,tipo_reporte;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			tipo_salida=f.tipo_salida.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;
			tipo_reporte=f.tipo_reporte.value;
			window.open('rpt_inv_salidas.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&tipo_salida='+tipo_salida+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&tipo_reporte='+tipo_reporte+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");

require("estilos_almacenes.inc");

$fecha_rptdefault=date("d/m/Y");
echo "<h1>Reporte Salidas Almacen</h1>";

echo"<form method='post' action=''>";
	echo"\n<table class='texto' align='center'>\n";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
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
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$rpt_territorio'";
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

	echo "<tr><th align='left'>Tipo de Salida</th>";
	$sql_tiposalida="select cod_tiposalida, nombre_tiposalida from tipos_salida 
	 order by nombre_tiposalida";
	$resp_tiposalida=mysqli_query($enlaceCon,$sql_tiposalida);
	echo "<td><select name='tipo_salida' class='texto'>";
	echo "<option value=''>Todos los tipos</option>";
	while($datos_tiposalida=mysqli_fetch_array($resp_tiposalida))
	{	$codigo_tiposalida=$datos_tiposalida[0];
		$nombre_tiposalida=$datos_tiposalida[1];
		if($tipo_salida==$codigo_tiposalida)
		{	echo "<option value='$codigo_tiposalida' selected>$nombre_tiposalida</option>";
		}
		else
		{	echo "<option value='$codigo_tiposalida'>$nombre_tiposalida</option>";
		}
	}
	echo "</select></td>";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Ver Reporte Por:</th>";
	echo "<td><select name='tipo_reporte' class='texto'>";
	echo "<option value='0'>Nro. de Salida</option>";
	//echo "<option value='1'>Producto</option>";
	echo "</tr>";

	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>