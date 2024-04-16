<?php

require("conexion.inc");
require("estilos.inc");
echo "<form action='guarda_grupos.php' method='post'>";

echo "<h1>Adicionar Grupo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre de Grupo</th>";
echo "<td align='center'>
	<input type='text' class='texto' name='nombre_grupo' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td></tr>";

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
				echo "<option value='$codigo'>$nombre</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
";

echo "</form>";
?>