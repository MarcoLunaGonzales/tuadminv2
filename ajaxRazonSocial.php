<?php
require("conexion.inc");
ob_clean();

$nitCliente=$_GET['nitCliente'];

$sql = "SELECT f.razon_social FROM facturas_venta f WHERE f.nit = '$nitCliente' ORDER BY f.fecha DESC LIMIT 0,1";
$resp = mysqli_query($enlaceCon, $sql);

$nombre="";
while($dat=mysqli_fetch_array($resp)){
	$nombre = $dat['razon_social'];
}
echo "<input type='text' value='$nombre' name='razonSocial' id='razonSocial' style='width: 100%;' placeholder='NOMBRE / RAZON SOCIAL' required class='custom-input'>";

?>
