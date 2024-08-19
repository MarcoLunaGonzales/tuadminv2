<?php
require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");
?>

<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	rpt_territorio=f.rpt_territorio.value;
	fecha_fin=f.exaffinal.value;
	fecha_ini=f.exafinicial.value;

	rpt_cod_vendedor = f.rpt_cod_vendedor.value;
	
	window.open('rptCuentasCobrar.php?rpt_territorio='+rpt_territorio+'&cod_vendedor='+rpt_cod_vendedor+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php


$fecha_rptdefault_ini=date("Y-m-01");
$fecha_rptdefault=date("Y-m-d");

echo "<table align='center' class='textotit'><tr><th>Reporte Cuentas x Cobrar</th></tr></table><br>";
echo"<form method='post' action='rptOpKardexCostos.php'>";

	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Sucursal</th><td><select name='rpt_territorio' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	// VENDEDORES
	echo "<tr><th align='left'>Vendedor</th><td><select name='rpt_cod_vendedor' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>";
	$sql="SELECT f.`codigo_funcionario`,
		concat(f.`paterno`,' ', f.`nombres`) as nombre 
		FROM `funcionarios` f, funcionarios_agencias fa 
		WHERE fa.codigo_funcionario=f.codigo_funcionario
		AND estado = 1
		GROUP BY codigo_funcionario 
		ORDER BY 2";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value='0'>TODOS</option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	

	echo "<tr><th align='left'>De fecha:</th>";
	echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault_ini' id='exafinicial' size='10' name='exafinicial'>";
	echo"  </TD>";
	echo "</tr>";

	echo "<tr><th align='left'>A fecha:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>