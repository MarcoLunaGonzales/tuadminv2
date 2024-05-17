<?php
session_start();
require("../conexionmysqli.php");

$fecha=date("Y-m-d");
$codigo=$_GET["codigo"];
$valor=$_GET['valor'];
if($valor!=0 || $valor!=""){
     $sql  = "INSERT INTO tipo_cambiomonedas (cod_moneda,fecha,valor)VALUES ('$codigo','$fecha','$valor');";
     $resp = mysqli_query($enlaceCon,$sql);
}

?>
