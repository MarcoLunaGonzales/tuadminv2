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
	/* TEXTO PRODUCTO */
	.textomedianorojo2 {
		font-family: Verdana;
		font-size: 14pt;
		color: #e20000;
	}	
</style>
<?php
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	require('funcion_nombres.php');
    date_default_timezone_set('America/La_Paz');
	
	echo "<h1 style='margin-bottom: 2px; font-family: Arial, sans-serif; font-weight: bold'>Listado de Productos con Stock Actual</h1>";
	$fecha_hora = date('d-m-Y H:i');
	echo "<h5 style='margin-top: 2px; color: #2c3e50; font-family: Arial, sans-serif; font-weight: bold; text-align: center;'>Fecha y Hora Actual: <span style='color: #16a085;'>$fecha_hora</span></h5>";
				
	echo "<form method='post' action=''>";
	$sql="SELECT m.codigo_material, m.descripcion_material, m.estado, 
	(select g.nombre_grupo from grupos g where g.cod_grupo=m.cod_grupo)as grupo,
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)linea,
		m.modelo, m.medida, m.capacidad_carga_velocidad, ta.abreviatura
		from material_apoyo m
		LEFT JOIN tipos_aro ta ON ta.codigo=m.cod_tipoaro
		where m.estado='1' and m.medida<>'-' and ta.codigo<>1 order by ta.abreviatura, m.medida, grupo, m.descripcion_material ASC";

	$resp=mysqli_query($enlaceCon,$sql);

	echo "<center class='scroll-container'><table class='texto' id='myTable'>";
	echo "<thead>";
	echo "<tr><th>-</th>
	<th>Codigo</th>
	<th>Nombre Producto</th>
	<th>Marca</th>
	<th>Grupo</th>
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
	echo "</th>
	<th>Modelo</th>
	<th>Medida</th>
	<th>Capacidad Carga<br>Velocidad</th>
	<th>Aro</th>

	</tr>";
	echo "</thead>";
	
	echo "<tbody>";

	$indice_tabla=1;

	$sw_color_fila = 1;
	while($dat=mysqli_fetch_array($resp))
	{
		$sw_color_fila 	= $sw_color_fila == 1 ? 0 : 1;
		$color_fila 	= $sw_color_fila == 1 ? "style='background-color:#e2e2e2;color:#2f2f2f;'" : "";
		// Producto descripción
		$class_producto = $sw_color_fila == 1 ? 'textomedianorojo2' : 'textomedianorojo';

		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$nombreProd=utf8_decode($nombreProd);
		$estado=$dat[2];
		$nombreGrupo=$dat[3];
		$nombreLinea=$dat[4];

		$nombreModelo=$dat[5];
		$nombreMedida=$dat[6];
		$capacidadCargaVelocidad=$dat[7];

		$nroAro=$dat[8];

		
		$precioProducto=precioVentaN($enlaceCon, $codigo, $globalAgencia);
		$precioF=formatonumeroDec($precioProducto);

        $precioProducto2=precioVentaMayorContado($enlaceCon, $codigo, $globalAgencia);
        $precio2F=formatonumeroDec($precioProducto2);
        
        $precioProducto3=precioVentaMayorCredito($enlaceCon, $codigo, $globalAgencia);
        $precio3F=formatonumeroDec($precioProducto3);
		

			
		$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a order by 2 asc";
		$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
		$stockTotal=0;
		
		$txtDetalleFila="";
		while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
			$codAlmacenX=$datAlmacenes[0];
			$nombreAlmacenX=$datAlmacenes[1];
			$stockProductoX=stockProducto($codAlmacenX,$codigo);
			if($stockProductoX==0){
				$stockProductoXF="-";
			}else{
				$stockProductoXF=formatonumero($stockProductoX);
				$stockTotal+=$stockProductoX;
			}
			$txtDetalleFila.="<td align='center'><span style='color:blue;font-size:20px;'>$stockProductoXF</span></td>";	
		}

		if($stockTotal>0){
			echo "<tr $color_fila><td align='center'>$indice_tabla</td>
			<td align='center'>$codigo</td>
			<td><div class='textograndeazul'>$nombreProd</div></td>
			<td>$nombreLinea</td>
			<td>$nombreGrupo</td>
			<td align='right'>$precioF</td>
			<td align='right'>$precio2F</td>
			<td align='right'>$precio3F</td>";

			echo $txtDetalleFila;

			echo "
			<td>$nombreModelo</td>
			<td>$nombreMedida</td>
			<td>$capacidadCargaVelocidad</td>
			<td>$nroAro</td>
			</tr>";			
		}else{
			echo "<tr $color_fila><td align='center'>$indice_tabla</td>
			<td align='center'>$codigo</td>
			<td><div class='textomedianorojo'>$nombreProd</div></td>
			<td>$nombreLinea</td>
			<td>$nombreGrupo</td>
			<td align='right'>$precioF</td>
			<td align='right'>$precio2F</td>
			<td align='right'>$precio3F</td>";

			echo $txtDetalleFila;

			echo "
			<td>$nombreModelo</td>
			<td>$nombreMedida</td>
			<td>$capacidadCargaVelocidad</td>
			<td>$nroAro</td>
			</tr>";
		}


		
		$indice_tabla++;
	}

	echo "</tbody>";
	echo "</table></center><br>";   
?>
