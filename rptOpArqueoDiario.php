<script language='JavaScript'>
function envia_formulario(f, variableAdmin)
{	var fecha_ini;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	
	fecha_ini=f.exafinicial.value;
	window.open('rptArqueoDiario.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&variableAdmin='+variableAdmin,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}

$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];

echo "<table align='center' class='textotit'><tr><th>Reporte Arqueo Diario de Caja</th></tr></table><br>";
echo"<form method='post' action='rptArqueoDiario.php'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($codigo_ciudad==$globalCiudad){
			echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";			
		}else{
			echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form,$variableAdmin)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>