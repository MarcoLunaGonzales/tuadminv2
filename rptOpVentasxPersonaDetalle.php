<script language='JavaScript'>
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
function ajaxPersonalMultiple(f){
	//var codTerritorio=document.getElementById('rpt_territorio').value;

	const rptTerritorio = document.getElementById('rpt_territorio');
    const rptTerritorioSelected = Array.from(rptTerritorio.selectedOptions).map(option => option.value);
    const rptTerritorioString = rptTerritorioSelected.join(', ');
    console.log('territorio: '+rptTerritorioString);

	var contenedor;
	contenedor = document.getElementById('divPersonal');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxPersonal.php?codTerritorio='+rptTerritorioString+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin;
	
	const rptTerritorio = document.getElementById('rpt_territorio');
    const rptTerritorioSelected = Array.from(rptTerritorio.selectedOptions).map(option => option.value);
    const rptTerritorioString = rptTerritorioSelected.join(', ');

	var rpt_persona=new Array();
	var rpt_grupo=new Array();
	var rpt_ver=f.rpt_ver.value;
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	j=0;
	for(i=0;i<=f.rpt_persona.options.length-1;i++)
	{	if(f.rpt_persona.options[i].selected)
		{	rpt_persona[j]=f.rpt_persona.options[i].value;
			j++;
		}
	}
	window.open('rptVentasxVendedorDetalle.php?rpt_territorio='+rptTerritorioString+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_persona='+rpt_persona+'&rpt_ver='+rpt_ver,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_inirptdefault=date("Y-m-01");
$fecha_rptdefault=date("Y-m-d");

echo "<h1>Reporte Ventas x Vendedor</h1>";

echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr>
		<th align='left'>Almacen</th>
		<td>
		<select name='rpt_territorio[]' id='rpt_territorio' class='selectpicker' onChange='ajaxPersonalMultiple(this.form)' data-actions-box='true' data-style='btn btn-info' multiple required>";

	$sql="SELECT cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Personal</th>";
	echo "<td><div id='divPersonal'></div>
	</td></tr>";
	
	echo "<tr><th align='left'>Ver</th>
	<td>
		<select name='rpt_ver' id='rpt_ver' class='selectpicker' data-style='btn btn-info' size='2'>";
	echo "<option value='1' selected>Resumido</option>";
	echo "<option value='2'>Detallado</option>";
	echo "</select></td></tr>";
	echo "</tr>";	
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_inirptdefault' id='exafinicial' size='10' name='exafinicial' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
?>

<script>
	ajaxPersonalMultiple(this.form);
</script>