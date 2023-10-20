<html>
<body>
<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
    <tr>
        <th>Nro.</th>
        <th>Nro. Doc</th>
        <th>Observaciones</th>
        <th>Fecha</th>
        <th>Monto</th>
        <th>A Cuenta</th>
        <th>Saldo</th>
        <th>Monto a Pagar</th>
        <th>Nro. Doc. Pago</th>
    </tr>
    <?php
    require("../conexion.inc");
    require("../funciones.php");

    $codProveedor = $_GET['codProveedor'];

    $sql = "SELECT
                oc.`codigo`,
                oc.`nro_correlativo`,
                oc.`fecha`,
                oc.`monto_orden`,
                oc.`monto_cancelado`,
                oc.`nro_documento`,
                oc.`observaciones` 
            FROM
                `ordenes_compra` oc 
            WHERE  oc.cod_proveedor = '$codProveedor'
                AND oc.`cod_estado` = 1
                AND oc.`monto_orden` > oc.`monto_cancelado`
            ORDER BY
                oc.`fecha`";
    // echo $sql;

    $resp = mysqli_query($enlaceCon, $sql);
    $numFilas = mysqli_num_rows($resp);

    echo "<input type='hidden' name='nroFilas' id='nroFilas' value='$numFilas'>";

    $i = 1;
    while ($dat = mysqli_fetch_array($resp)) {
        $codigo = $dat['codigo'];
        $numero = $dat['nro_correlativo'];
        $fecha = $dat['fecha'];
        $monto = $dat['monto_orden'];
        $montoCancelado = $dat['monto_cancelado'];
        $saldo = $monto - $montoCancelado;

        $nroFactura = $dat['nro_documento'];
        $observaciones = $dat['observaciones'];

        $montoV = redondear2($monto);
        $montoCanceladoV = redondear2($montoCancelado);
        $saldoV = redondear2($saldo);
	?>
		<tr>
			<input type='hidden' value='<?=$codigo;?>' name='codOrdenCompra<?=$i;?>' id='codOrdenCompra<?=$i;?>'>
			<td><?=$numero;?></td>
			<td><?=$nroFactura;?></td>
			<td><?=$observaciones;?></td>
			<td><?=$fecha;?></td>
			<td><?=$montoV;?></td>
			<td><?=$montoCanceladoV;?></td>
			<td><?=$saldoV;?></td>
			<input type='hidden' value='<?=$saldoV?>' name='saldo<?=$i;?>' id='saldo<?=$i;?>'>
            
			<td><input type='number' class='texto' name='montoPago<?=$i;?>' id='montoPago<?=$i;?>' size='10' onKeyPress='javascript:return solonumeros(event)' value='0' max='<?=$saldoV?>' min="0" step='0.01'></td>

			<td><input type='text' class='texto' name='nroDoc<?=$i;?>' id='nroDoc<?=$i;?>' size='10' onKeyPress='javascript:return solonumeros(event)' value='0'></td>
		</tr>
	<?php
        $i++;
    }

    ?>
</table>

</body>
</html>