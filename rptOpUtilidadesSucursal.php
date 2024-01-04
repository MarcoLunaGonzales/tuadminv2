<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");

$fechaini_rptdefault=date("Y-m-01");
$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Utilidades x Sucursal x Periodo</h1><br>";

echo"<form method='post' action='rptUtilidadesSucursalResumido.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left' class='text-muted'>Sucursal</th>
	<td>
	<select name='rpt_territorio[]' id='rpt_territorio' multiple size='10' class='form-control' required>";
	$globalAgencia=$_COOKIE["global_agencia"];
   
   $sql="select cod_ciudad, descripcion from ciudades where cod_ciudad>0 order by descripcion";    

	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($codigo_ciudad==$globalAgencia){
           echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}else{
		   echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";	
		}		
	}
	echo "</select></td></tr>";

	

	?>
	<?php

	echo "<tr><th align='left' class='text-muted'>Fecha inicio:</th>";
			echo" <td>
			<INPUT  type='date' class='form-control' value='$fechaini_rptdefault' id='exafinicial' size='10' name='exafinicial' required >";
    		echo"</td>";
	echo "</tr>";
	echo "<tr><th align='left' class='text-muted'>Fecha final:</th>";
			echo" <td bgcolor='#ffffff'><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>";
    		echo"  </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	//<input type='button' name='reporte' class='boton-verde' value='Ver Reporte X Meses' onClick='envia_formulario(this.form)' class='btn btn-primary'>
	echo "<center>
	<input type='submit' class='boton-verde' value='Ver Reporte' class='btn btn-success'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>