<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
        /**
         * Cambio de vista
         */
        function cambiar_vista(ruta)
        {
            location.href=ruta;
        }
        </script>
    </head>

    <?php
require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

// codigo ciudad
$global_agencia = $_COOKIE["global_agencia"];

?>
<style>
    .texto tbody tr.fila-verde {
        background-color: #e0ffe0;
    }
</style>
<h1>Listado de Despacho de Productos</h1>

<div class='divBotones'>
    <input type='button' value='Registrar' name='adicionar' class='boton' onclick="cambiar_vista('registrar_despachoalmacenes.php')">
</div>

<div id='divCuerpo'>
    <center>
        <table class='texto'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha Despacho</th>
                    <th>Funcionario</th>
                    <th>Vehiculo</th>
                    <th>Fecha Recepción</th>
                    <th>Observaciones</th>
                    <!--th>Funcionario Origen</th-->
                    <th>NR Despacho</th>
                    <th>NR Venta</th>
                    <th>NR Devolución</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $consulta = "SELECT dp.codigo, DATE_FORMAT(dp.fecha_entrega,'%d-%m-%Y %H:%i:%s') as fecha_entrega, CONCAT(f.nombres, ' ', f.paterno, ' ', f.materno) as funcionario, dp.fecha_recepcion, dp.observaciones, CONCAT(fs.nombres, ' ', fs.paterno, ' ', fs.materno) as created_funcionario, dp.created_at, concat(v.nombre,' ',v.abreviatura)as vehiculo, dp.nr_despacho, dp.nr_venta, dp.nr_devolucion
                            FROM despacho_productos dp
                            LEFT JOIN funcionarios f ON f.codigo_funcionario = dp.cod_funcionario
                            LEFT JOIN funcionarios fs ON fs.codigo_funcionario = dp.created_by
                            LEFT JOIN vehiculos2 v ON v.codigo=dp.cod_vehiculo
                            ORDER BY dp.codigo DESC";
                $resp  = mysqli_query($enlaceCon, $consulta);
                $index = 1;
                while ($dat = mysqli_fetch_array($resp)) :
                    $codigoDespacho=$dat['codigo'];
                    $nrDespacho=$dat['nr_despacho'];
                    $nrVenta=$dat['nr_venta'];
                    $nrDevolucion=$dat['nr_devolucion'];

                    $txtDespacho="";
                    if($nrDespacho>0){
                        $txtDespacho="<a href='formatoNotaSalida.php?codSalida=$nrDespacho'>
                            <img src='imagenes/detalle.png' width='30'></a>";
                    }else{
                        $txtDespacho="<a href='generarNotaDespacho.php?codigo=$codigoDespacho'>
                        <img src='imagenes/ejecutar.png' width='40' title='Ejecutar Nota Despacho'>
                        </a>";
                    }

                    $txtVenta="";
                    if(!empty($dat['fecha_recepcion']) && $nrVenta>0){
                        $txtVenta="<a href='formatoNotaRemision.php?codVenta=$nrVenta'>
                            <img src='imagenes/detalle.png' width='30'></a>";
                    }elseif(!empty($dat['fecha_recepcion'])){
                        $txtVenta="<a href='generarNotaVenta.php?codigo=$codigoDespacho'>
                        <img src='imagenes/ejecutar.png' width='40' title='Ejecutar Venta'>
                        </a>";
                    }else{
                        $txtVenta="-";
                    }
                ?>
                    <tr class="<?= (empty($dat['fecha_recepcion'])) ? 'fila-verde' : '' ?>">
                        <td><?= $index++ ?></td>
                        <td><?= $dat['fecha_entrega'] ?></td>
                        <td><?= $dat['funcionario'] ?></td>
                        <td><?= $dat['vehiculo'] ?></td>
                        <td><?= empty($dat['fecha_recepcion']) ? '-' : $dat['fecha_recepcion'] ?></td>
                        <td><?= $dat['observaciones'] ?></td>
                        <!--td><?= $dat['created_funcionario'] ?></td-->
                        <td align="center"><?=$txtDespacho;?></td>
                        <td align="center"><?=$txtVenta;?></td>
                        <td>-</td>
                        <td>
                            <?php if(empty($dat['fecha_recepcion'])){ ?>
                            <a href="registrar_despachoentrega.php?codigo=<?= $dat['codigo'] ?>" title="Registrar Entrega"><img src="imagenes/entrega2.png" width="50" alt="Modificar Despacho"></a>
                            <?php } ?>
                            <?php if(!empty($dat['fecha_entrega']) && !empty($dat['fecha_recepcion'])){ ?>
                            <a href="registrar_despachoalmacenes.php?codigo=<?= $dat['codigo'] ?>" title="Detalle de Despacho"><img src="imagenes/detalle.png" width="30" alt="Detalle de Despacho"></a>
                            <?php } ?>
                        </td>                    </tr>
                <?php
                    endwhile;
                ?>
            </tbody>
        </table>
    </center>
    <br>
</div>

<div class='divBotones'>
    <input type='button' value='Registrar' name='adicionar' class='boton' onclick="cambiar_vista('registrar_despachoalmacenes.php')">
</div>
</body>
</html>
