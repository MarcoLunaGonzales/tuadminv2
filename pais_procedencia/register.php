<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

echo "<form action='$urlSave' method='post'>";

echo "<h1>Registrar $moduleNameSingular</h1>";

echo "<center><table class='texto' width='60%'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td>";
echo "<tr><th align='left'>Descuento</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='abreviatura' size='30' required>
</td>";
echo "</tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"$urlList2\"'>
";

echo "</form>";
?>