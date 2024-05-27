<?php

require("../../conexion.inc");
require("../../estilos_almacenes.inc");

$globalAgencia=$_COOKIE["global_agencia"];
$globalAlmacen=$_COOKIE["global_almacen"];

echo "<br>";
echo "<h1>Clientes</h1>";

echo "<div class='divBotones'>
<input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
</div>";

echo "<center>";
echo "<table class='texto'>";
echo "<tr>";

echo '<th style="width: 30%;">Cliente</th>';
echo '<th style="width: 15%;">NIT</th>';
echo '<th style="width: 25%;">Dirección</th>';
echo '<th style="width: 15%;">Ciudad</th>';
echo '<th style="width: 10%;" class="text-center">Acciones</th>';

echo "</tr>";
$consulta="
    SELECT c.cod_cliente, c.nombre_cliente, c.nit_cliente, c.dir_cliente, c.cod_area_empresa, a.descripcion
    FROM clientes AS c INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad 
    ORDER BY c.nombre_cliente ASC
";
$rs=mysqli_query($enlaceCon,$consulta);
$cont=0;
while($reg=mysqli_fetch_array($rs)){
    $cont++;
    $codCliente = $reg["cod_cliente"];
    $nomCliente = $reg["nombre_cliente"];
    $nitCliente = $reg["nit_cliente"];
    $dirCliente = $reg["dir_cliente"];
    $codArea = $reg["cod_area_empresa"];
    $nomArea = $reg["descripcion"];
    echo "<tr>";
    echo "<td>$nomCliente</td>
            <td>$nitCliente</td>
            <td>$dirCliente</td>
            <td>$nomArea</td>
            <td class='text-center'>
                <button class='btn btn-sm btn-info pt-4 editarCliente' data-cod_cliente='$codCliente' title='Editar' style='padding-left: 10px; padding-right: 10px;'>
                    <i class='material-icons'>edit</i>
                </button>
                <button class='btn btn-sm btn-danger pt-4 eliminarCliente' data-cod_cliente='$codCliente' title='Eliminar' style='padding-left: 10px; padding-right: 10px;'>
                    <i class='material-icons'>delete</i>
                </button>
            </td>";
    echo "</tr>";
}
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones'>
<input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'>
</div>";

?>
