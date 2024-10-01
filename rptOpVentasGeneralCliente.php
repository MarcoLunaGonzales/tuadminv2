<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	rpt_territorio=f.rpt_territorio.value;
	
	var codTipoDoc=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_tipodoc.options.length-1;i++)
	{	if(f.rpt_tipodoc.options[i].selected)
		{	codTipoDoc[j]=f.rpt_tipodoc.options[i].value;
			j++;
		}
	}
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	window.open('rptVentasGeneralCliente.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&codTipoDoc='+codTipoDoc+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Ventas x Cliente (Rutas)</h1>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
		<td>
			<select name='rpt_territorio' class='selectpicker' data-style='btn btn-success'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Documento:</th>
	<td><select name='rpt_tipodoc' class='selectpicker' multiple>";
	echo "<option value='1' selected>FACTURA</option>";
	echo "<option value='2' selected>NOTA DE REMISION</option>";
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

	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
?>