<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select distinct(f.codigo_funcionario), f.paterno, f.materno, f.nombres, f.estado
	from funcionarios f, cargos c, funcionarios_agencias fa
	where f.cod_cargo=c.cod_cargo and f.codigo_funcionario=fa.codigo_funcionario and fa.cod_ciudad in ($codTerritorio) 
	order by f.paterno";
$resp_visitador=mysqli_query($enlaceCon,$sql_visitador);
echo "<select name='rpt_persona' id='rpt_persona' class='texto' size='10' multiple>";
while($dat_visitador=mysqli_fetch_array($resp_visitador))
{	$codigo=$dat_visitador[0];
	$nombre="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
	$estado=$dat_visitador[4];
	if($estado!=1){
		$nombreEstado="(Inactivo)";
	}else{
		$nombreEstado="";
	}
	echo "<option value='$codigo'>$nombre $nombreEstado</option>";
}
echo "</select>";
?>