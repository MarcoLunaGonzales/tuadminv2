<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_salida,fecha_ini, fecha_fin, tipo_item, rpt_linea,tipo_reporte;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			window.open('rpt_inv_obs_kardexexistencias.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&tipo_item=1','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}

		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}$fecha_rptdefault=date("d/m/Y");
echo "<h1>Reporte Diferencias Kardex y Existencias</h1>";

echo"<form method='post' action=''>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
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


	echo"\n </table><br>";

	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";

?>