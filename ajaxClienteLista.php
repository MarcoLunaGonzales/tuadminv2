<?php
$estilosVenta=1;
require("conexion.inc");
ob_clean();

$nitCliente=$_GET['nitCliente'];

$sql = "SELECT f.cod_cliente, CONCAT(f.nombre_cliente, ' ', f.paterno, ' (CI:', f.ci_cliente, ')') AS nombre, '' AS tipo FROM clientes f 
        WHERE f.nit_cliente = '$nitCliente' ORDER BY f.cod_cliente DESC";
$resp = mysqli_query($enlaceCon, $sql);

$cod_cliente = 146; // valor por defecto
$htmlCliente = "";
$index = 0;
$tipo = 1;

while ($dat = mysqli_fetch_array($resp)) {
    $cod_cliente = $dat['cod_cliente'];
    $nombre_item = $dat['nombre'];
    $tipo = $dat['tipo'];
    $index++;

    $htmlCliente .= "<option value='$cod_cliente' selected>CLI-$index $nombre_item</option>";
}

if ($cod_cliente == 146) {
    $htmlCliente = '<option value="146" selected>NO REGISTRADO</option>';
}

echo $cod_cliente."####".$htmlCliente."####".$tipo;