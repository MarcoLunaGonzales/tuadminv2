<?php

require("conexionmysqli.php");
require('funciones.php');
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$global_admin_cargo=$_COOKIE["global_admin_cargo"];

?>
<html>
    <head>
        <title>Lista Cotizaciones</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type='text/javascript' language='javascript'>
   
        </script>
    </head>
    <body>
        <h1>Listado de Cotizaciones</h1>
        <div class="container-fluid m-0">
            <table class="table table-hover" style="font-size: 12px;">
                <thead class="thead-light">
                    <tr>
                        <th>COD</th>
                        <th>Cliente</th>
                        <th>Fecha Registro</th>
                        <th>Vendedor</th>
                        <th>Tipo Pago</th>
                        <th>Razón Social</th>
                        <th>NIT</th>
                        <th>Monto</th>
                        <th>Observaciones</th>
                        <th class="text-center">Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $consulta = "SELECT
                        CONCAT( c.nombre_cliente ) AS cliente,
                        p.codigo,
                        a.nombre_almacen,
                        td.nombre AS tipo_doc,
                        DATE_FORMAT( p.created_at, '%d-%m-%Y %H:%i:%s' ) AS fecha,
                        p.observaciones,
                        p.estado,
                        p.nro_correlativo,
                        c.nombre_cliente,
                        p.monto_total,
                        p.descuento,
                        p.monto_final,
                        p.razon_social,
                        p.nit,
                        CONCAT( f.nombres, ' ', f.paterno, ' ', f.materno ) AS vendedor,
                        tp.nombre_tipopago AS tipopago,
                        p.created_by,
                        p.created_at,
                        IFNULL(( SELECT 1 FROM salida_almacenes WHERE cod_cotizacion = p.codigo ), 0 ) AS proceso_salida 
                    FROM
                        cotizaciones p
                        LEFT JOIN almacenes a ON a.cod_almacen = p.cod_almacen
                        LEFT JOIN tipos_docs td ON td.codigo = p.cod_tipo_doc
                        LEFT JOIN clientes c ON c.cod_cliente = p.cod_cliente
                        LEFT JOIN funcionarios f ON f.codigo_funcionario = p.created_by
                        LEFT JOIN tipos_pago tp ON tp.cod_tipopago = p.cod_tipopago
                    WHERE
                        p.cod_almacen = '$global_almacen' 
                    ORDER BY p.created_at 
                    DESC LIMIT 0, 100";

                        $resp = mysqli_query($enlaceCon, $consulta);

                        if(mysqli_num_rows($resp) > 0) {
                            $resultados = mysqli_fetch_all($resp, MYSQLI_ASSOC);

                            foreach($resultados as $dat) {
                    ?>
                        <tr 
                        <?php
                            if($dat['estado'] == 2){
                                echo 'style="background-color: #ffebee; text-decoration: line-through;"';
                            }else if($dat['proceso_salida'] == 1){
                                echo 'style="background-color: #eaf7ea;"';
                            }
                        ?>>
                            <td class="pt-2 pb-2"><?=$dat['codigo']?></td>
                            <td class="pt-2 pb-2"><?=$dat['cliente']?></td>
                            <td class="pt-2 pb-2"><?=$dat['fecha']?></td>
                            <td class="pt-2 pb-2"><?=$dat['vendedor']?></td>
                            <td class="pt-2 pb-2"><?=$dat['tipopago']?></td>
                            <td class="pt-2 pb-2"><?=$dat['razon_social']?></td>
                            <td class="pt-2 pb-2"><?=$dat['nit']?></td>
                            <td class="pt-2 pb-2"><?=$dat['monto_final']?></td>
                            <td class="pt-2 pb-2"><?=$dat['observaciones']?></td>
                            <td class="pt-2 pb-2 text-center">
                                <?php
                                    if($dat['estado'] == 2){
                                ?>
                                <span class="badge badge-danger"><i class="material-icons small">cancel</i> Anulado</span>
                                <?php
                                    }else if($dat['proceso_salida'] == 1){
                                ?>
                                <span class="badge badge-success"><i class="material-icons small">check_circle</i> Procesado</span>
                                <?php
                                    }else{
                                ?>
                                <span class="badge badge-warning"><i class="material-icons small">warning</i> Sin procesar</span>
                                <?php
                                    }
                                ?>
                            </td>
                            <td class="pt-2 pb-2">
                                <a href="formatoHojaCotizacion.php?cod_cotizacion=<?=$dat['codigo']?>" 
                                    class="btn btn-sm btn-warning pt-4" 
                                    title="Imprimir cotización"
                                    style="padding-left: 10px; padding-right: 10px;">
                                    <i class="material-icons">print</i>
                                </a>
                                <?php
                                if($global_admin_cargo==1){
                                    if($dat['proceso_salida'] == 0 && $dat['estado'] == 1){
                                ?>
                                <a href="registrar_salidaventas.php?cod_cotizacion=<?=$dat['codigo']?>" 
                                    class="btn btn-sm btn-info pt-4" 
                                    title="Generar Venta"
                                    style="padding-left: 10px; padding-right: 10px;">
                                    <i class="material-icons">description</i>
                                </a>
                                    <?php
                                        if($dat['estado'] == 1){
                                    ?>
                                    <button class="btn btn-sm btn-danger pt-4 anular-pedido" 
                                            data-cod-pedido="<?=$dat['codigo']?>" 
                                            title="Anular Pedido"
                                            style="padding-left: 10px; padding-right: 10px;">
                                        <i class="material-icons">cancel</i>
                                    </button>
                                    <?php
                                        }
                                    ?>
                                <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function(){
                $('.anular-pedido').click(function(e){
                    e.preventDefault();
                    var codPedido = $(this).data('cod-pedido');
                    Swal.fire({
                        title: '¿Está seguro de anular cotización?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, anular',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            location.href = 'guardarCotizacionAnulado.php?cod_cotizacion=' + codPedido;
                        }
                    });
                });
            });
        </script>
    </body>
</html>


