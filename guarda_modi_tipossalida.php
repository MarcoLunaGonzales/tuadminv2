<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update tipos_salida set nombre_tiposalida='$tipo_salida', obs_tiposalida='$obs_tipo_salida', tipo_almacen='$tipo_almacen' where cod_tiposalida='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_tipossalida.php';
			</script>";
?>