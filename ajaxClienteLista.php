<?php
$estilosVenta=1;
require("conexion.inc");
ob_clean();

$nitCliente = $_GET['nitCliente'];

$sql = "SELECT f.cod_cliente, 
                CONCAT(f.nombre_cliente, ' ', f.paterno) AS nombre, 
                '' AS tipo,
                f.nit_cliente, 
                f.nombre_factura
        FROM clientes f
        ORDER BY f.cod_cliente DESC";
$resp = mysqli_query($enlaceCon, $sql);

$cod_cliente = 146; // valor por defecto
$htmlCliente = "";
$index = 0;
$tipo = 1;

$htmlCliente = '<option value="146">NO REGISTRADO</option>';
while ($dat = mysqli_fetch_array($resp)) {
    $cod_cliente = $dat['cod_cliente'];
    $nombre_item = $dat['nombre'];
    $tipo        = $dat['tipo'];
    $nit         = $dat['nit_cliente'];
    $nombre_factura = $dat['nombre_factura'];
    $index++;

    $selected = $nit == $nitCliente ? 'selected' : '';

    $htmlCliente .= "<option value='$cod_cliente' $selected data-nit='$nit' data-razon='$nombre_factura'>$nombre_item</option>";
}

// if ($cod_cliente == 146) {
//     $htmlCliente = '<option value="146" selected>NO REGISTRADO</option>';
// }

echo $cod_cliente."####".$htmlCliente."####".$tipo;