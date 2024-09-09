<?php

require("conexion.inc");
require("estilos.inc");

$codigoCliente=$_POST['cod_cliente'];

//echo "codCliente: ".$codigoCliente."<br>";
$sqlDel="delete from clientes_precios where cod_cliente='$codigoCliente'";
$respDel=mysqli_query($enlaceCon, $sqlDel);


$precioProducto=0;
$precioCliente=0;
foreach($_POST as $nombre_campo => $valor){
  //echo $nombre_campo ." ". $valor . "<br/>"; 

  $partes = explode('|', $nombre_campo);    
  // Validar que hay al menos dos partes después de la explosión
  if (count($partes) === 2) {
      // Obtener el tipo de precio (precio_producto o precio_cliente)
      $tipo = $partes[0];
      // Obtener el valor después del símbolo '|' y eliminar los espacios adicionales
      $codigoProducto = trim($partes[1]);
	
			//echo $tipo."  -  ".$codigoProducto."  -  ".$valor."<br>";      
			$precioProducto=0;
			$precioCliente=0;
		  if ($tipo == "precio_producto") {
		      $precioProducto = $valor;
		      $precioCliente = $_POST['precio_cliente|'.$codigoProducto];
    		  $sqlInsert="insert into clientes_precios (cod_cliente, cod_producto, precio_base, precio_cliente) values 
					  ('$codigoCliente','$codigoProducto','$precioProducto','$precioCliente')";
		  		$respInsert=mysqli_query($enlaceCon, $sqlInsert);
		  } 
  }
}

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='programas/clientes/inicioClientes.php';";
echo "</script>";


?>