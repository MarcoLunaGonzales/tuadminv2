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

<?php
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	require('funcion_nombres.php');
	
	echo "<h1>Listado de Productos Registrados</h1>";

	$globalAlmacen=$_POST["rpt_almacen"];
	$globalAgencia=$_COOKIE["global_agencia"];
	
	$nombreAlmacen=nombreAlmacen($globalAlmacen);
	
	echo "<h2>Almacen: $nombreAlmacen</h2>";

	
	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
	(select g.nombre_grupo from grupos g where g.cod_grupo=m.cod_grupo)as grupo,
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)linea 
		from material_apoyo m
		where m.estado='1' order by grupo, m.descripcion_material";

	$resp=mysql_query($sql);

	echo "<center><table class='texto' id='myTable'>";
	echo "<thead>";
	echo "<tr><th>Indice</th><th>Grupo</th><th>Linea</th><th>Nombre Producto</th><th>Precio</th><th>
	<table width='100%'><tr><th colspan='3' align='center'>Detalle de Costos</th></tr>
		<tr><td width='40%'>Fecha</td><td width='30%' align='right'>Cantidad</td>
			<td width='30%' align='right'>Costo</td></tr></table>
	</th></tr>";
	echo "</thead>";
	
	echo "<tbody>";

	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$nombreProd=utf8_decode($nombreProd);
		$estado=$dat[2];
		$nombreGrupo=$dat[3];
		$nombreLinea=$dat[4];
		
		$precioProducto=precioVenta($codigo,$globalAgencia);
		$precioF=formatonumeroDec($precioProducto);
		//$stockProducto=stockProducto($globalAlmacen, $codigo);
		
		$detalleRestantes="";
		$detalleRestantes.="<table border='1' class='texto' cellspacing='0' width='100%' align='center'>";
		$sqlRestantes="select i.fecha, id.cantidad_unitaria, id.cantidad_restante, id.costo_almacen from ingreso_almacenes i, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen=$globalAlmacen and 	
			i.ingreso_anulado=0 and id.cod_material=$codigo and id.cantidad_restante>0.1;"; 
		$respRestantes=mysql_query($sqlRestantes);
		while($datRestantes=mysql_fetch_array($respRestantes)){
			$fechaRest=$datRestantes[0];
			$cantidadRest=$datRestantes[1];
			$cantidadRest2=$datRestantes[2];
			$cantidadRest2F=formatonumeroDec($cantidadRest2);
			$costoAlmacenRest=$datRestantes[3];
			$costoAlmacenRestF=formatonumeroDec($costoAlmacenRest);
			$detalleRestantes.="<tr><td width='40%'>$fechaRest</td><td width='30%' align='right'>$cantidadRest2F</td>
			<td width='30%' align='right'>$costoAlmacenRestF</td></tr>";
		}
		$detalleRestantes.="</table>";
		
		echo "<tr><td align='center'>$indice_tabla</td>
		<td>$nombreGrupo</td>
		<td>$nombreLinea</td>
		<td><div class='textomedianorojo'>$nombreProd</div></td>
		<td>$precioF</td>	
		<td>$detalleRestantes</td>			
		</tr>";
		$indice_tabla++;
	}

	echo "</tbody>";
	echo "</table></center><br>";   
?>
