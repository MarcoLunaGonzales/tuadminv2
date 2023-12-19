<?php
require("conexion.inc");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$notaIngreso=$_GET['notaIngreso'];
$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];

//$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
//$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);	
//
$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso
    AND i.cod_almacen='$global_almacen'";

if($notaIngreso!="")
   {$consulta = $consulta."AND i.nro_correlativo='$notaIngreso' ";
   }
if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=i.fecha AND i.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=0){
	$consulta=$consulta." and cod_proveedor='$provBusqueda' ";
}   
$consulta = $consulta."ORDER BY i.nro_correlativo DESC";

//echo $consulta;
//
$resp = mysqli_query($enlaceCon,$consulta);
	
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Nro.Factura</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Marca</th>
<th>Observaciones</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
    $proveedor=$dat[8];
    $nroFacturaProveedor=$dat[9];

    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select * from salida_almacenes s, salida_detalle_almacenes sd, ingreso_almacenes i
        where s.cod_salida_almacenes=sd.cod_salida_almacen  and sd.cod_ingreso_almacen=i.cod_ingreso_almacen and s.salida_anulada=0 and i.cod_ingreso_almacen='$codigo'";
    //echo $sql_verifica_movimiento;
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento > 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 and $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>$nroFacturaProveedor</td>
    <td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
    <td>&nbsp;$proveedor</td>
    <td>&nbsp;$obs_ingreso</td>
    <td align='center'>
        <a target='_BLANK' href='formatoNotaIngreso.php?codigo_ingreso=$codigo'><img src='imagenes/factura1.jpg' border='0' width='30' heigth='30' title='Imprimir'></a>
    </td>   <td align='center'>
        <a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/icon_detail.png' border='0' width='30' heigth='30' title='Ver Detalles del Ingreso'></a>
    </td>
    <td align='center'>
        <a href='#' onclick='javascript:editarIngresoTipoProv($codigo)' > 
            <img src='imagenes/edit.png' border='0' width='30' heigth='30' title='Editar Tipo & Marca'>
        </a>
    </td>
        <td align='center'>
        <a  href='costosImportacionIngreso.php?codigo_ingreso=$codigo'>
            <img src='imagenes/imp9.jpg' border='0' width='50' heigth='50' title='Asociar Costos de Importacion'>
        </a>
    </td>
    <td align='center'>
        <a target='_BLANK' href='navegador_detalleingresomateriales2.php?codigo_ingreso=$codigo'>
        <img src='imagenes/documento.png' border='0' width='30' heigth='30' title='Ingreso y Costos de Importacion'></a>
    </td>
    <td align='center'>
        <a target='_BLANK' href='navegadorDetalleIngresoImpUtilidad.php?codigo_ingreso=$codigo'>
        <img src='imagenes/procesar.png' border='0' width='30' heigth='30' title='Configurar Utilidades'></a>
    </td>
    </tr>";
}
echo "</table></center><br>";


?>
