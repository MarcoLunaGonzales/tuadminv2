<?php
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
$start_time = microtime(true);

require("estilos_almacenes.inc");
require("funciones.php");
require("funcion_nombres.php");
require("funciones_inventarios.php");
require("conexionmysqlipdf.inc");

//$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

$codigoDespacho=$_GET['codigo'];

$sqlDespacho = "SELECT dp.codigo, dp.cod_vehiculo, dp.cod_funcionario
             FROM despacho_productos dp 
             WHERE dp.codigo='$codigoDespacho' ";
$respDespacho  = mysqli_query($enlaceCon, $sqlDespacho);
                $index = 1;
while ($datDespacho = mysqli_fetch_array($respDespacho)){
    $codVehiculo=$datDespacho['cod_vehiculo'];
    $codVendedor=$datDespacho['cod_funcionario'];
}

$nombreVendedor=nombreFuncionario($codVendedor);
$nombreVehiculo=nombreVehiculo($codVehiculo);    


$observacionesAjuste="";

$observaciones="Salida Automatica por Venta. $nombreVendedor $nombreVehiculo.";


$usuarioVendedor=$codVendedor;

$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=1001;/*VENTAS*/
$tipoDoc=2;
$almacenDestino=0;
$almacenOrigen=$global_almacen;

$cod_tipopreciogeneral=0;
$cod_tipoVenta2=1;
$cod_tipodelivery=0;
$monto_bs=0;
$monto_usd=0;
$tipo_cambio=0;
$tipoVenta=0;
$totalVenta=0; 
$descuentoVenta=0; 
$totalFinal=0; 
$totalEfectivo=0;    
$totalCambio=0;  
$complemento=0;  
$totalFinalRedondeado=0;
$razonSocial="SN";
$codCliente=0;
$nitCliente=123;
$vehiculo=$codVehiculo;

$codTipoPago=1;

$fecha=date("Y-m-d");
$hora=date("H:i:s");

$contador=0;
do {
    $anio=date("Y");
    $created_at=date("Y-m-d H:i:s");
    $sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
    $resp=mysqli_query($enlaceCon,$sql);
    // $codigo=mysqli_result($resp,0,0);
    $datCodSalida=mysqli_fetch_array($resp);
    $codigo=$datCodSalida[0];

    $vectorNroCorrelativo=numeroCorrelativo($tipoDoc);
    $nro_correlativo=$vectorNroCorrelativo[0];
    $cod_dosificacion=0;

    
    $sql_inserta="INSERT INTO salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
    cod_tipo_doc, fecha, hora_salida, territorio_destino, almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada, 
    cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago)
    values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
    '$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$codTipoPago')";
    $sql_inserta=mysqli_query($enlaceCon,$sql_inserta);
    $contador++;
} while ($sql_inserta<>1 && $contador <= 100);

if($sql_inserta==1){
    $montoTotalVentaDetalle=0;
    $i=1;
    $sqlDetalle="SELECT d.cod_material, d.cantidad_venta, d.precio_producto, d.monto_venta, d.cod_cliente from despacho_productosdetalle_devolucion d 
        where d.cod_despachoproducto='$codigoDespacho'";
    $respDetalle=mysqli_query($enlaceCon, $sqlDetalle);

    $cantidadTotalVendida=0;
    while($datDetalle=mysqli_fetch_array($respDetalle)){
        $codigoProductoX=$datDetalle['cod_material'];
        $cantidadProductoX=$datDetalle['cantidad_venta'];
        $precioProductoX=$datDetalle['precio_producto'];
        $montoProductoX=$datDetalle['monto_venta'];

        $codClienteX=$datDetalle['cod_cliente'];
        $nombreClienteX=nombreCliente($codClienteX);

        $montoTotalVentaDetalle+=$montoProductoX;
        //VALIDAMOS QUE EL CAMPO ESTE VACIO NO INGRESA POR AHI
        $descuentoProducto=0;
        if($cantidadProductoX>0){
            //echo "PRODUCTO: ".$codProductoX." STOCK: ".$stockProductoX." CANT INSERT: ".$cantidadInsertX;
            $respuesta=descontar_inventarios($enlaceCon, $codigo, $almacenOrigen, $codigoProductoX, $cantidadProductoX, $precioProductoX, $descuentoProducto, $montoProductoX, $i, $nombreClienteX, $codClienteX);
            if($respuesta!=1){
                echo "<script>
                    alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
                </script>";
            }
            $i++;
        }
        $cantidadTotalVendida+=$cantidadProductoX;
    }
    $sqlUpdVenta="UPDATE salida_almacenes set monto_total='$montoTotalVentaDetalle', monto_final='$montoTotalVentaDetalle' where cod_salida_almacenes='$codigo'";
    $respUpdVenta=mysqli_query($enlaceCon, $sqlUpdVenta);
    //UPDATE EN LA TABLA DESPACHO
    $sqlUpdateDespacho="UPDATE despacho_productos set nr_venta='$codigo' where codigo='$codigoDespacho'";
    $respUpdateDespacho=mysqli_query($enlaceCon, $sqlUpdateDespacho);
}else{
    echo "<script>
            alert('Ocurrio un Problema. Contacte con el administrador!');
        </script>";  
}



//Aca Verificamos que haya salido el total y de no ser asi realizamos el ingreso por devolucion. 
$sqlTotalEntregado="SELECT d.cantidad_entrega FROM despacho_productosdetalle d
    WHERE d.cod_despachoproducto='$codigoDespacho'";
$respTotalEntregado=mysqli_query($enlaceCon, $sqlTotalEntregado);
$cantidadEntregada=0;
if($datTotalEntregado=mysqli_fetch_array()){
    $cantidadEntregada=$datTotalEntregado['cantidad_entrega'];
}


if($cantidadEntregada>$cantidadTotalVendida){
    echo "se debe devolver ".$cantidadEntregada-$cantidadTotalVendida;
}


/*ESTA PARTE ES PARA LOS INGRESOS*/
$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat = mysqli_fetch_array($resp);
$codigo=$dat[0];

$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat = mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];

$hora_sistema = date("H:i:s");
// $tipo_ingreso=1002;
$tipo_ingreso=1003;
$nota_entrega=0;
$nro_factura=0;
$observaciones="Ingreso por ajuste de Linea. Linea $nombreLineaProveedorX";
$proveedor=0;

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");
$fecha_real=date("Y-m-d");

$consulta="INSERT into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date) 
values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones','0','$nota_entrega','$nro_correlativo',0,0,0,$nro_factura,0,0,'$proveedor','$createdBy','0','$createdDate','')";
$sql_inserta = mysqli_query($enlaceCon,$consulta);

if($sql_inserta==1){
    $i=1;
    $cantidad_ingreso = 0; // Control de cantidad de registros exitosos
    foreach($_POST as $nombre_campo => $valor){ 
        $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
        $cadenaBuscar='producto';
        $posicion = strpos($nombre_campo, $cadenaBuscar);
        if ($posicion === false) {
        }else{
            list($nombreCampoX, $codProductoX)=explode("|",$nombre_campo);  
            $codigoProductoX=$codProductoX;
            $cantidadProductoX=$_POST["producto|".$codProductoX];

            //VALIDAMOS QUE EL CAMPO ESTE VACIO NO INGRESA POR AHI
            if($cantidadProductoX!=""){
                $stockProductoX=$_POST["stock|".$codProductoX];
                $cantidadInsertX=$stockProductoX-$cantidadProductoX;
                $precioUnitario=0;
                $descuentoProducto=0;
                $montoMaterial=0;
                if($cantidadInsertX<0){
                    //echo "PRODUCTO: ".$codProductoX." STOCK: ".$stockProductoX." CANT INSERT: ".$cantidadInsertX;
                    $cantidadInsertX=$cantidadInsertX*(-1);
                    $lote="LA01";
                    $fechaVencimiento="2024-08-30";
                    $precioUnitario=0;
                    $costo=0;
                    $consulta="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
                    precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) 
                    values($codigo,'$codigoProductoX',$cantidadInsertX,$cantidadInsertX,'$lote','$fechaVencimiento',$precioUnitario,$precioUnitario,$costo,$costo,$costo,$costo)";
                    $respuesta = mysqli_query($enlaceCon,$consulta);
                    $cantidad_ingreso++;
                
                    if($respuesta!=1){
                        echo "<h2>Ocurrio un error en la transaccion. Contacte con el administrador del sistema.</h2>";
                    }
                    $i++;
                }
            }
        }
    }
}    



    // echo "<script>
    //         alert('Se genero la nota correctamente!');
    //         location.href='navegador_despachoalmacenes.php';
    //     </script>";  

?>



