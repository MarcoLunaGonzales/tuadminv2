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
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
";

echo "</form>";
?>