<script language='JavaScript'>
function envia_formulario(f,formato)
{	var fecha_ini, fecha_fin;
	

	var codSubGrupo=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_subcategoria.options.length-1;i++)
	{	if(f.rpt_subcategoria.options[i].selected)
		{	codSubGrupo[j]=f.rpt_subcategoria.options[i].value;
			j++;
		}
	}

	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	window.open('rptVentasPorClientesRepo.php?codTipoTerritorio='+codTipoTerritorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+''+'&codSubGrupo=0'+'&rpt_formato='+formato,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,height=800');			
	return(true);
}

</script>
<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Ranking Ventas x Clientes</h1><br>";
echo"<form method='post' action='rptVentasPorClientesRepo.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left' class='text-muted'>Sucursal</th><td><select name='rpt_territorio' data-live-search='true' title='-- Elija una sucursal --'  id='rpt_territorio' multiple data-actions-box='true' data-style='select-with-transition' data-actions-box='true' data-size='10' class='selectpicker form-control'>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c order by c.descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";


	echo "<tr class='d-none'><th align='left' class='text-muted' >Clientes:</th>
	<td><select name='rpt_subcategoria'  id='rpt_subcategoria' class='selectpicker form-control' multiple title='-- Elija un cliente --' data-live-search='true' data-actions-box='true' data-style='select-with-transition' data-size='10'>";
	$sql="SELECT cod_cliente,TRIM(CONCAT(nombre_cliente,' ',paterno)) as nombre_cliente,nit_cliente FROM clientes order by 2;";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_cat=$dat[0];
		$nombre_cat=$dat[1]." @".$dat['nit_cliente'];		
		if($codigo_cat==146){
			$nombre_cat="CLIENTE GENERICO";
		}		
		echo "<option value='$codigo_cat'>$nombre_cat</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left' class='text-muted'>Fecha inicio:</th>";
			echo" <td><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </td>";
	echo "</tr>";
	echo "<tr><th align='left' class='text-muted'>Fecha final:</th>";
			echo" <td><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </td>";
	echo "</tr>";
	
	echo"\n </table><br>";

	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton-verde'>

	</center><br>";

	echo"</form>";
	echo "</div>";

?>