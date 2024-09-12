<?php
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
$start_time = microtime(true);

require("estilos_almacenes.inc");
require("funciones.php");
require("funcion_nombres.php");
require("funciones_inventarios.php");
require("conexionmysqlipdf.inc");

$usuarioVendedor=$_COOKIE['global_usuario'];
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

$observaciones="Salida Automatica por Despacho. $nombreVendedor $nombreVehiculo.";


$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=1009;
$tipoDoc=3;
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
$razonSocial="";
$codCliente=0;
$nitCliente=0;
$vehiculo=$codVehiculo;

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
    '$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta')";
    $sql_inserta=mysqli_query($enlaceCon,$sql_inserta);
    $contador++;
} while ($sql_inserta<>1 && $contador <= 100);

if($sql_inserta==1){
    $montoTotalVentaDetalle=0;
    $i=1;
    $sqlDetalle="SELECT d.cod_material, d.cantidad_entrega  from despacho_productosdetalle d 
        where d.cod_despachoproducto='$codigoDespacho'";
    $respDetalle=mysqli_query($enlaceCon, $sqlDetalle);
    while($datDetalle=mysqli_fetch_array($respDetalle)){
        $codigoProductoX=$datDetalle['cod_material'];
        $cantidadProductoX=$datDetalle['cantidad_entrega'];

        //VALIDAMOS QUE EL CAMPO ESTE VACIO NO INGRESA POR AHI
        $precioUnitario=0;
        $descuentoProducto=0;
        $montoMaterial=0;
        if($cantidadProductoX>0){
            //echo "PRODUCTO: ".$codProductoX." STOCK: ".$stockProductoX." CANT INSERT: ".$cantidadInsertX;
            $respuesta=descontar_inventarios($enlaceCon, $codigo, $almacenOrigen, $codigoProductoX, $cantidadProductoX, $precioUnitario, $descuentoProducto, $montoMaterial, $i);
            if($respuesta!=1){
                echo "<script>
                    alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
                </script>";
            }
            $i++;
        }
    }
    //UPDATE EN LA TABLA DESPACHO
    $sqlUpdateDespacho="update despacho_productos set nr_despacho='$codigo' where codigo='$codigoDespacho'";
    $respUpdateDespacho=mysqli_query($enlaceCon, $sqlUpdateDespacho);
    echo "<script>
            alert('Se genero la nota correctamente!');
            location.href='navegador_despachoalmacenes.php';
        </script>";      
}else{
    echo "<script>
            alert('Ocurrio un Problema. Contacte con el administrador!');
        </script>";  }


?>



