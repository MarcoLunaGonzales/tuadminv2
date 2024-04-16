<?php

require("conexion.inc");
require("estilos.inc");

$sql=mysqli_query($enlaceCon,"select nombre_grupo, cod_moneda from grupos where cod_grupo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombreGrupo = $dat[0];
$codMoneda	 = $dat[1];

echo "<form action='guarda_editargrupo.php' method='post'>";

echo "<h1>Editar Grupo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre de Grupo</th>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='texto' name='nombre_grupo' value='$nombreGrupo' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";


echo "<tr><th>Tipo Moneda</th>";
$sql1="SELECT m.codigo, m.nombre, m.abreviatura
		FROM monedas m
		WHERE m.estado = 1
		ORDER BY m.codigo ASC";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_moneda' id='cod_moneda' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo = $dat1[0];
				$nombre = $dat1[1];
				$selected = $codMoneda == $codigo ? 'selected' : '';
				echo "<option value='$codigo' $selected>$nombre</option>";
			}
			echo "</select>
</td>";
echo "</tr>";


echo "<tr><th align='left'>Estado</th>";
echo "<td align='center'>
<select name='estado'>
	<option value='1'>Activo</option>
	<option value='2'>Inactivo</option>
</select></td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
</div>";

echo "</form>";
?>