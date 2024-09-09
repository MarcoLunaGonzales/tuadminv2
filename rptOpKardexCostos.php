<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen,tipo_ingreso,fecha_ini, fecha_fin, tipo_item, rpt_item;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;
			tipo_item=f.tipo_item.value;
			rpt_item=f.rpt_item.value;
			window.open('rptKardexCostos.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&tipo_item='+tipo_item+'&rpt_item='+rpt_item+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Kardex de Movimiento Costos</h1>";
echo"<form method='post' action='rptOpKardexCostos.php'>";

	echo"<center><table class='texto'>";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_territorio'  class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true' onChange='envia_select(this.form)'>";
	if($global_tipoalmacen==1)
	{	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	}
	else
	{	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia' order by descripcion";
	}
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
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='selectpicker' data-style='btn btn-warning' data-show-subtext='true' data-live-search='true'>";
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
	echo "<tr><th align='left'>Tipo de Item:</th>";
	echo "<td><select name='tipo_item' class='selectpicker' data-style='btn btn-warning' data-show-subtext='true' data-live-search='true' onChange='envia_select(this.form)'>";
	
	echo "<option value='2'>Productos</option>";
	
	echo "</tr>";
	echo "<tr><th align='left'>Producto</th><td><select name='rpt_item'  class='selectpicker' data-style='btn btn-primary' data-show-subtext='true' data-live-search='true'>";
	
	$sql_item="select codigo_material, descripcion_material from material_apoyo where codigo_material<>0 order by descripcion_material";
	
	$resp=mysqli_query($enlaceCon,$sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		if($tipo_item==1)
		{	$nombre_item="$dat[1] $dat[2]";
		}
		else
		{	$nombre_item=$dat[1];
		}
		if($rpt_item==$codigo_item)
		{	echo "<option value='$codigo_item' selected>$nombre_item</option>";
		}
		else
		{	echo "<option value='$codigo_item'>$nombre_item</option>";
		}
	}
	echo "</select></td></tr>";	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>