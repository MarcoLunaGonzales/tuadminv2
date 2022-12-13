<?php
require("conexion.inc");
require("estilos_administracion.inc");
require("funciones.php");


$configuracionPrecioBajoMinimo=obtenerValorConfiguracion(6);
echo "<form action='guardar_configuraciones_sistema.php' method='post'>";

echo "<h1>Configuraciones del sistema</h1>";

echo "<center><table class='texto' width='40%'>";
echo "<tr><th align='center'><span align='center' class='textograndenegro'>Habilitar Ventas Bajo el Precio</span></th></tr>";
echo "<tr><td align='center'><select id='configuraciones' class='textogranderojo' name='configuraciones'>";
if($configuracionPrecioBajoMinimo==1)
{	echo "<option value='1' selected>SI</option>";
	echo "<option value='0'>NO</option>";
}
else
{	echo "<option value='1'>SI</option>";
	echo "<option value='0' selected>NO</option>";
}
echo "</select></td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar''>
<input type='button' class='boton2' value='Cancelar' onClick='javascript:location.href=\"navegador_tiposingreso.php\"'>
</div>";

echo "</form>";
?>