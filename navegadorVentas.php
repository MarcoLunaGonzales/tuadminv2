
<?php
require("conexion.inc");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

?>
<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/bootstrap.js"></script>
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
        <style>
            .txt_anulado {
                text-decoration: line-through;
                color: red;
            }
        </style>
        <script type='text/javascript' language='javascript'>
function nuevoAjax()
{   var xmlhttp=false;
    try {
            xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
    try {
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    } catch (E) {
        xmlhttp = false;
    }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}       
    
function ShowBuscar(){
    document.getElementById('divRecuadroExt').style.visibility='visible';
    document.getElementById('divProfileData').style.visibility='visible';
    document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
    document.getElementById('divRecuadroExt').style.visibility='hidden';
    document.getElementById('divProfileData').style.visibility='hidden';
    document.getElementById('divProfileDetail').style.visibility='hidden';
}
    
function funOk(codReg,funOkConfirm)
{   $.get("programas/salidas/frmConfirmarCodigoSalida.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/salidas/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introduzca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}

function ajaxBuscarVentas(f){
    var fechaIniBusqueda, fechaFinBusqueda, nroCorrelativoBusqueda, verBusqueda, global_almacen, clienteBusqueda;
    fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
    fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
    nroCorrelativoBusqueda=document.getElementById("nroCorrelativoBusqueda").value;
    verBusqueda=document.getElementById("verBusqueda").value;
    global_almacen=document.getElementById("global_almacen").value;
    clienteBusqueda=document.getElementById("clienteBusqueda").value;
    var contenedor;
    contenedor = document.getElementById('divCuerpo');
    ajax=nuevoAjax();

    ajax.open("GET", "ajaxSalidaVentas.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&verBusqueda="+verBusqueda+"&global_almacen="+global_almacen+"&clienteBusqueda="+clienteBusqueda,true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText;
            HiddenBuscar();
        }
    }
    ajax.send(null)
}

function enviar_nav()
{   location.href='registrar_salidaventas.php';
}
function editar_salida(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   if(f.fecha_sistema.value==fecha_registro)
            {
                {   
                        location.href='editarVentas.php?codigo_registro='+j_cod_registro;
                }
            }
            else
            {   funOk(j_cod_registro,function(){
                        location.href='editarVentas.php?codigo_registro='+j_cod_registro;
                    });
            }
        }
    }
}
function anular_salida(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   funOk(j_cod_registro,function() {
                        location.href='anular_venta.php?codigo_registro='+j_cod_registro;
            });
        }
    }
}

function editarTipoPago(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro.');
        }
        else{   
            funOk(j_cod_registro,function() {
                        location.href='editarTipoPago.php?codigo_registro='+j_cod_registro;
            });
        }
    }
}


    </script>
    </head>
    <body>

<?php
$txtnroingreso="";
$fecha1="";
$fecha2="";

if(isset($_GET["txtnroingreso"])){
    $txtnroingreso = $_GET["txtnroingreso"];    
}else{
    $txtnroingreso = "";
}
if(isset($_GET["fecha1"])){
    $fecha1 = $_GET["fecha1"];
}else{
    $fecha1 = "";
}
if(isset($_GET["fecha2"])){
    $fecha2 = $_GET["fecha2"];
}else{
    $fecha2="";
}

$fecha_sistema=date("Y-m-d");

$globalAdmin = $_COOKIE["global_admin_cargo"];

$BDSiat=obtenerValorConfiguracion(8);

echo "<form method='post' action=''>";

echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

echo "<h1>Listado de Ventas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='navegadorVentas2.php'><img src='imagenes/go2.png' width='15'></a>
</h1>";
echo "<table class='texto' cellspacing='0' width='90%'>
<tr><th>Leyenda:</th>
<th>Ventas Registradas</th><td bgcolor='#f9e79f' width='5%'></td>
<th>Ventas Entregadas</th><td bgcolor='#1abc9c' width='5%'></td>
<th>Ventas Anuladas</th><td bgcolor='#e74c3c' width='5%'></td>
<td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
//
echo "<div class='divBotones'>
        <input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
        <input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></td>      
        <input type='button' value='Editar TipoPago/Cliente' class='boton' onclick='editarTipoPago(this.form)'></td>";
        
if($globalAdmin==1){
    echo "<input type='button' value='Anular' class='boton2' onclick='anular_salida(this.form)'>";
}
echo "</div>";

echo "<br>";
        
echo "<div id='divCuerpo'>";
echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Tipo Documento</th><th>Nro.</th><th>Fecha/hora<br>Registro Salida</th><th>Codigo</th>
    <th>Razon Social</th><th>NIT</th><th>TipoPago</th><th>Monto</th><th>Observaciones</th><th>&nbsp;</th><th class='text-center'>Documento SIAT</th><th class='text-center'>Estado SIAT</th></tr>";
    
echo "<input type='hidden' name='global_almacen' value='$global_almacen' id='global_almacen'>";

$consulta = "
    SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
    (select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
    s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
    (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,
    (select abreviatura from tipos_docs where codigo=s.cod_tipo_doc),
    (select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=s.cod_tipopago)as tipopago, s.idTransaccion_siat, s.monto_final
    FROM salida_almacenes s, tipos_salida ts 
    WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 and s.cod_tipo_doc in (1,4)";

if($txtnroingreso!="")
   {$consulta = $consulta."AND s.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
   {$consulta = $consulta."AND '$fecha1'<=s.fecha AND s.fecha<='$fecha2' ";
   }
$consulta = $consulta."ORDER BY s.fecha desc, s.cod_tipo_doc, s.nro_correlativo DESC limit 0, 250 ";

//
$resp = mysqli_query($enlaceCon,$consulta);
    
    
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
    $nombreCliente=$dat[10];
    $codTipoDoc=$dat[11];
    $razonSocial=$dat[12];
    $nitCli=$dat[13];
    $nombreTipoDoc=$dat[14];
    $nombreTipoPago=$dat[15];
    $idTransaccion = $dat[16];
    $montoVenta = $dat[17];
    $montoVentaFormat=formatonumeroDec($montoVenta);
    
    // Estado de Factura SIAT
    $consultaSiat = "SELECT sa.cod_salida_almacenes, es.cod_estado, es.nombre_estado, es.color
                    FROM salida_almacenes sa
                    LEFT JOIN estados_salida es ON es.cod_estado = sa.estado_salida
                    WHERE sa.cod_salida_almacenes = '$idTransaccion'";
    $respSiat     = mysqli_query($enlaceConSiat, $consultaSiat);
    
    $siat_estado_nombre = "";
    $siat_estado_color  = "black";
    if ($row_siat = mysqli_fetch_assoc($respSiat)) {
        $siat_estado_nombre = $row_siat['nombre_estado'];
        $siat_estado_color  = $row_siat['color'];
    }
    $siat_estado_color  = "black";
    
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
    
    $sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
    $respEstadoColor=mysqli_query($enlaceCon,$sqlEstadoColor);
    $numFilasEstado=mysqli_num_rows($respEstadoColor);

    $txtAnulado = "";
    if($estado_almacen == 3){
        $txtAnulado  = "txt_anulado";
    }
    if($numFilasEstado>0){
        $color_fondo = mysqli_result($respEstadoColor,0,0);
    }else{
        $color_fondo="#ffffff";
    }
    $chk = "<input type='checkbox' name='codigo' value='$codigo' ".(empty($txtAnulado) ? '' : 'hidden').">";

    $salidaAnuladaSiat=0;
    $estadoSalidaAnuladaSiat=0;
    /****** ANULACION SIAT *******/
    $sqlAnuladoSiat="SELECT s.estado_salida, s.salida_anulada from salida_almacenes s where s.cod_salida_almacenes='$idTransaccion'";
    $respAnuladoSiat=mysqli_query($enlaceConSiat, $sqlAnuladoSiat);
    if($datAnuladoSiat=mysqli_fetch_array($respAnuladoSiat)){
        $estadoSalidaAnuladaSiat=$datAnuladoSiat[0];
        $salidaAnuladaSiat=$datAnuladoSiat[1];
    }
    //echo "idTrans: ".$idTransaccion." estado salida ".$estadoSalidaAnuladaSiat." salida anulada".$salidaAnuladaSiat."<br>"; 

    $colorDocumentoSIAT="";
    if($estadoSalidaAnuladaSiat==3 && $salidaAnuladaSiat==1){
        $colorDocumentoSIAT="red";
    }

    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center' class='$txtAnulado'>&nbsp;$chk</td>";
    echo "<td align='center' class='$txtAnulado'>$nombreTipoDoc</td>";
    echo "<td align='center' class='$txtAnulado'>$nro_correlativo</td>";
    echo "<td align='center' class='$txtAnulado'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td class='$txtAnulado'>$codigo</td>";
    echo "<td class='$txtAnulado'>&nbsp;$razonSocial</td>
    <td class='$txtAnulado'>&nbsp;$nitCli</td>
    <td class='$txtAnulado'>$nombreTipoPago</td>
    <td class='$txtAnulado'>$montoVentaFormat</td>
    <td class='$txtAnulado'>&nbsp;$obs_salida</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
    

    // Editar Datos
    $url_siat   = valorConfig(7);
    $urlDetalle = $url_siat."formatoFacturaTickets.php";
    if($idTransaccion>0){
        echo "<td style='background-color: $colorDocumentoSIAT' class='text-center'>
            <a href='$urlDetalle?codVenta=$idTransaccion&codProceso=$codigo' target='_BLANK' title='Imprimir Factura' class='text-dark'><i class='material-icons'>description</i></a>";
        echo "</td>";        
    }else{
        echo "<td style='background-color: $colorDocumentoSIAT' class='text-center'>
            <a href='formatoFactura.php?codVenta=$codigo' target='_BLANK' title='Imprimir Factura' class='text-dark'><i class='material-icons'>description</i></a>";
        echo "</td>";        
    }


    $urlDetalle = $url_siat."dFacturaElectronica.php";
    echo "<td  bgcolor='$colorDocumentoSIAT' class='text-center'> <a href='$urlDetalle?admin=1&codigo_salida=$idTransaccion' target='_BLANK' title='Documento SIAT'  class='text-dark'><i class='material-icons' style='color: $siat_estado_color;'>description</i></a>";
    echo "</td>";

    echo "<td align='center' bgcolor='$colorDocumentoSIAT'>$siat_estado_nombre</td>";

    echo "</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'>
        <input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
        <input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></td>      
        <input type='button' value='Editar TipoPago/Cliente' class='boton' onclick='editarTipoPago(this.form)'></td>";
        
if($globalAdmin==1){
    echo "<input type='button' value='Anular' class='boton2' onclick='anular_salida(this.form)'>";
}
echo "</div>";

    
echo "</form>";

?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px;     -moz-border-radius: 20px; visibility: hidden; z-index:2;">
    <div id="divProfileDetail" style="visibility:hidden; text-align:center">
        <h2 align='center' class='texto'>Buscar Ventas</h2>
        <table align='center' class='texto'>
            <tr>
                <td>Fecha Ini(dd/mm/aaaa)</td>
                <td>
                <input type='text' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
                </td>
            </tr>
            <tr>
                <td>Fecha Fin(dd/mm/aaaa)</td>
                <td>
                <input type='text' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
                </td>
            </tr>
            <tr>
                <td>Nro. de Documento</td>
                <td>
                <input type='text' name='nroCorrelativoBusqueda' id="nroCorrelativoBusqueda" class='texto'>
                </td>
            </tr>           
            <tr>
                <td>Cliente:</td>
                <td>
                    <select name="clienteBusqueda" class="texto" id="clienteBusqueda">
                        <option value="0">Todos</option>
                    <?php
                        $sqlClientes="select c.`cod_cliente`, c.`nombre_cliente` from clientes c order by 2";
                        $respClientes=mysqli_query($enlaceCon,$sqlClientes);
                        while($datClientes=mysqli_fetch_array($respClientes)){
                            $codCliBusqueda=$datClientes[0];
                            $nombreCliBusqueda=$datClientes[1];
                    ?>
                            <option value="<?php echo $codCliBusqueda;?>"><?php echo $nombreCliBusqueda;?></option>
                    <?php
                        }
                    ?>
                    </select>
                
                </td>
            </tr>           

            <tr>
                <td>Ver:</td>
                <td>
                <select name='verBusqueda' id='verBusqueda' class='texto' >
                    <option value='0'>Todo</option>
                    <option value='1'>No Cancelados</option>
                    <option value='2'>Anulados</option>
                </select>
                </td>
            </tr>           
        </table>    
        <center>
            <input type='button' class="boton" value='Buscar' onClick="ajaxBuscarVentas(this.form)">
            <input type='button' class="boton2" value='Cancelar' onClick="HiddenBuscar()">
            
        </center>
    </div>
</div>

        <script type='text/javascript' language='javascript'>
        </script>
        <div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
    </body>
</html>
