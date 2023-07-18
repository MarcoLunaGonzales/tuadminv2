<?php
require("conexion.inc");

//mysqli_query($enlaceCon,"insert into ciudades values (10, 'AV. COCHABAMBA',1);");
//mysqli_query($enlaceCon,"insert into almacenes values (1010, 10, 'TIENDA AV. CBBA.',1025)");
//mysqli_query($enlaceCon,"update almacenes set responsable_almacen=1029 where cod_almacen=1010");
mysqli_query($enlaceCon,"insert into usuarios_sistema values(1029, 'tiendacbba')");
/*mysqli_query($enlaceCon,"delete from salida_almacenes;");
mysqli_query($enlaceCon,"delete from salida_detalle_almacenes;");
mysqli_query($enlaceCon,"delete from salida_detalle_ingreso;");*/

echo "creado";
?>