<?php

require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

echo "<form action='$urlSave' method='post'>";

echo "<h1>Registrar $moduleNameSingular</h1>";

echo "<center><table class='texto' width='60%'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td>";
echo "<tr><th align='left'>Numero</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='numero' size='30' required>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Peso</th>";
echo "<td align='left'>
	<input type='number' step='0.01' class='texto' name='peso' size='30' required>
</td>";
echo "</tr>";


echo "<tr><th align='left'>Precio</th>";
echo "<td align='left'>
	<input type='number' step='0.01' class='texto' name='precio' size='30' required>
	</td>";
echo "</tr>";


echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"$urlList2\"'>
";

echo "</form>";
?>