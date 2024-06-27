<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">

<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<script>

    $(document).ready(function() {
        $('#myTable').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            fixedHeader: {
              header: true,
              footer: true
            }
        } );
    } );
	
</script>
<style>
	.scroll-container {
		width: 100%;
		overflow-x: scroll; /* Siempre muestra el scroll horizontal */
		max-height: 85vh;
		position: relative;
	}
	table {
		width: 80%;
		border-collapse: collapse;
	}
	th, td {
		padding: 8px 12px;
		border: 1px solid #ddd;
		text-align: left;
	}
	th {
		background-color: #f2f2f2;
		position: sticky;
		top: 0;
		z-index: 2;
		box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
	}
</style>
<?php
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	require('funcion_nombres.php');
	
	echo "<h1>Listado de Productos con Stock Actual</h1>";
				
	echo "<form method='post' action=''>";
	$sql="SELECT m.codigo_material, m.descripcion_material, m.estado, 
	(select g.nombre_grupo from grupos g where g.cod_grupo=m.cod_grupo)as grupo,
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)linea,
		m.modelo, m.medida, m.capacidad_carga_velocidad
		from material_apoyo m
		where m.estado='1' and m.medida<>'-' order by m.medida, grupo, m.descripcion_material ASC";

	$resp=mysqli_query($enlaceCon,$sql);

	echo "<center class='scroll-container'><table class='texto' id='myTable'>";
	echo "<thead>";
	echo "<tr><th>Indice</th>
	<th>Codigo</th>
	<th>Nombre Producto</th>
	<th>Marca</th>
	<th>Grupo</th>
	<th>Modelo</th>
	<th>Medida</th>
	<th>Capacidad Carga<br>Velocidad</th>
	<th>Precio</th>
	<th>Precio 2</th>
	<th>Precio 3</th>";

	$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a order by 2 asc";
	$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
	while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
		$codAlmacenX=$datAlmacenes[0];
		$nombreAlmacenX=$datAlmacenes[1];
		echo "<th>$nombreAlmacenX</th>";	
	}


	echo "</th></tr>";
	echo "</thead>";
	
	echo "<tbody>";

	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$nombreProd=utf8_decode($nombreProd);
		$estado=$dat[2];
		$nombreGrupo=$dat[3];
		$nombreLinea=$dat[4];

		$nombreModelo=$dat[5];
		$nombreMedida=$dat[6];
		$capacidadCargaVelocidad=$dat[7];

		
		$precioProducto=precioVentaN($enlaceCon, $codigo, $globalAgencia);
		$precioF=formatonumeroDec($precioProducto);

        $precioProducto2=precioVentaMayorContado($enlaceCon, $codigo, $globalAgencia);
        $precio2F=formatonumeroDec($precioProducto2);
        
        $precioProducto3=precioVentaMayorCredito($enlaceCon, $codigo, $globalAgencia);
        $precio3F=formatonumeroDec($precioProducto3);

		echo "<tr><td align='center'>$indice_tabla</td>
		<td align='center'>$codigo</td>
		<td><div class='textomedianorojo'>$nombreProd</div></td>
		<td>$nombreLinea</td>
		<td>$nombreGrupo</td>
		<td>$nombreModelo</td>
		<td>$nombreMedida</td>
		<td>$capacidadCargaVelocidad</td>
		<td align='right'>$precioF</td>
		<td align='right'>$precio2F</td>
		<td align='right'>$precio3F</td>";

			
		$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a order by 2 asc";
		$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
		while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
			$codAlmacenX=$datAlmacenes[0];
			$nombreAlmacenX=$datAlmacenes[1];
			$stockProductoX=stockProducto($codAlmacenX,$codigo);
			if($stockProductoX==0){
				$stockProductoXF="-";
			}else{
				$stockProductoXF=formatonumero($stockProductoX);
			}
			echo "<td align='center'><span style='color:blue;font-size:20px;'>$stockProductoXF</span></td>";	
		}

		echo "</tr>";
		$indice_tabla++;
	}

	echo "</tbody>";
	echo "</table></center><br>";   
?>
