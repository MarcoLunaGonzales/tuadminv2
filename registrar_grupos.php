<?php

require("conexion.inc");
require("estilos.inc");
echo "<form action='guarda_grupos.php' method='post'>";

echo "<h1>Adicionar Grupo</h1>";

echo "<center><table class='texto'>";

echo "<tr><th align='left'>Nombre de Grupo</th>";
echo "<td align='center'>
	<input type='text' class='form-control' name='nombre_grupo' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td></tr>";

echo "<tr><th align='left'>Tipo Material</th>";
echo "<td align='center'>";
	$sql1="SELECT cod_tipomaterial, nombre_tipomaterial FROM tipos_material ORDER BY cod_tipomaterial ASC";
	$resp1=mysqli_query($enlaceCon,$sql1);
	echo "<select class='selectpicker form-control' name='cod_tipomaterial' data-style='btn btn-rose' data-live-search='true' id='cod_tipomaterial'>";
	while($dat=mysqli_fetch_array($resp1)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select>";
echo "</td></tr>";

echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
";

echo "</form>";
?>