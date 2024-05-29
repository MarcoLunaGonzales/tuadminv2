<?php
//$estilosVenta=1;
require("conexion.inc");
ob_clean();

$cliente=$_GET["cliente"];
$nit=$_GET["nit"];

$sql = "SELECT f.cod_cliente, CONCAT(f.nombre_cliente, ' ', f.paterno') AS nombre, f.nombre_factura
        FROM clientes f 
        -- WHERE f.nit_cliente = '$nit' 
        ORDER BY f.cod_cliente DESC";
$resp = mysqli_query($enlaceCon, $sql);

$cod_cliente = 146; // valor por defecto
$htmlCliente = "";
$index = 0;

$htmlCliente = '<option value="146">NO REGISTRADO</option>';
while ($dat = mysqli_fetch_array($resp)) {
    $cod_cliente = $dat['cod_cliente'];
    $nombre_item = $dat['nombre'];
    $nombre_factura = $dat['nombre_factura'];
    $index++;

    // if ($cod_cliente == $cliente) {
    //     $htmlCliente .= "<option value='$cod_cliente' $selected data-nit='$nit' data-razon='$nombre_factura'>$nombre_item</option>";
    // } else {
    //     $htmlCliente .= "<option value='$cod_cliente' data-nit='$nit' data-razon='$nombre_factura'>$nombre_item</option>";
    // }
    $selected = $cod_cliente == $cliente ? 'selected' : '';
    $htmlCliente .= "<option value='$cod_cliente' $selected data-nit='$nit' data-razon='$nombre_factura'>$nombre_item</option>";
}

// if($cod_cliente==146){
// 	$htmlCliente='<option value="146" selected>NO REGISTRADO</option>';
// }
echo $htmlCliente;
