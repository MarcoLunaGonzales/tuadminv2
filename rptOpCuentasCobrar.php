<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	rpt_territorio=f.rpt_territorio.value;
	fecha_fin=f.exaffinal.value;
	fecha_ini=f.exafinicial.value;
	
	window.open('rptCuentasCobrar.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php

require("conexion.inc");
require("estilos_almacenes.inc");


$fecha_ini_default=date("2020-01-01");
$fecha_rptdefault=date("d/m/Y");

echo "<table align='center' class='textotit'><tr><th>Reporte Cuentas x Cobrar</th></tr></table><br>";
echo"<form method='post' action='rptOpKardexCostos.php'>";

	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>De fecha:</th>";
	echo" <td><INPUT  type='date' class='texto' value='$fecha_ini_default' id='exafinicial' size='10' name='exafinicial'>";
	echo" </td>";
	echo "</tr>";

	echo "<tr><th align='left'>A fecha:</th>";
	echo" <td bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
	echo"  </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>