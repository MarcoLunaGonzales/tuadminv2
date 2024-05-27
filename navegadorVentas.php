<?php

require("conexion.inc");
require("funciones.php");

$tipoDocumentoDefault=obtenerValorConfiguracion(1);

if($tipoDocumentoDefault==1){
    header("location:navegadorVentasF.php");
}else{
    header("location:navegadorVentas2.php");
}


?>