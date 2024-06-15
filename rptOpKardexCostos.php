<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen,tipo_ingreso,fecha_ini, fecha_fin, tipo_item, rpt_item;
			rpt_almacen=f.rpt_almacen.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;
			rpt_item=f.rpt_item.value;
			window.open('rptKardexCostos.php?rpt_almacen='+rpt_almacen+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_item='+rpt_item+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}

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

		function ajaxReporteItems(f){
			var contenedor;
			contenedor=document.getElementById('divItemReporte');
			ajax=nuevoAjax();
			var codGrupo=(f.rpt_grupo.value);
			ajax.open('GET', 'ajaxReporteItems.php?codGrupo='+codGrupo,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
			ajax.send(null);
		}

		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
		
require("conexionmysqli.php");

require("estilos_almacenes.inc");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Kardex de Movimiento Costos</h1>";
echo"<form method='post' action='rptOpKardexCostos.php'>";

	echo"<center><table class='texto'>";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='selectpicker' data-style='btn btn-rose'>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
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

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo' class='texto' size='5' onChange='ajaxReporteItems(this.form);'>";
	$sql="select cod_grupo, nombre_grupo from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select></td></tr>";
	echo "</tr>";


	echo "<tr><th align='left'>Material</th><td>
	<div id='divItemReporte'>
	<select name='rpt_item' class='selectpicker' data-style='btn btn-success' data-live-search='true'>";
	$sql_item="select codigo_material, descripcion_material from material_apoyo where codigo_material<>0 order by descripcion_material";
	
	$resp=mysqli_query($enlaceCon, $sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		$nombre_item=$dat[1];

		if($rpt_item==$codigo_item)
		{	echo "<option value='$codigo_item' selected>$nombre_item</option>";
		}
		else
		{	echo "<option value='$codigo_item'>$nombre_item</option>";
		}
	}
	echo "</select></td>
	</div>
	</tr>";


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

?>