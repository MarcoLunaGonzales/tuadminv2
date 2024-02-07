<?php
$estilosVenta=1;
require("conexion.inc");
ob_clean();

$cliente=$_GET['cliente'];

$nombre="";

$sql = "SELECT f.nombre_factura FROM clientes f WHERE f.cod_cliente = '$cliente'";
$resp = mysqli_query($enlaceCon, $sql);
$nombre = '';

if ($dat = mysqli_fetch_array($resp)) {
    $nombre = $dat['nombre_factura'];
}


if($cliente==146){
	$nombre="SN";
}
echo "<input type='text' value='$nombre' class='form-control' name='razonSocial' id='razonSocial' required style='text-transform:uppercase;width: 100%;'  onchange='ajaxNitCliente(this.form);' onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='NOMBRE / RAZON SOCIAL' pattern='[A-Za-z0-9Ññ.& ]+'>";

?>
