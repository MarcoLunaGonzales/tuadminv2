<html>

<script>
function guardar(f)
{  var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
						
                       /* if(j==0)
                        {       alert('Debe seleccionar al menos un Item de Importacion.');
                        }
                        else
                        {	*/
                                if(confirm('Los Items de Importacion seleccionados se asociaran al Ingreso, desea continuar?.'))
                                { 	f.datos_itemsImp.value=datos;                                        
                                }
                                else
                                {
                                        return(false);
                                }
								f.submit();
                      //  }
}




        </script>
    </head>
    <body>

<form action="guardaCostosImportacionIngreso.php" method="post" name='form1' >

<input type="hidden" id="datos_itemsImp" name="datos_itemsImp">

<?php

require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require('home_almacen.php');
require('funciones.php');

$codIngreso=$_GET['codigo_ingreso'];

$global_almacen=$_COOKIE["global_almacen"];


$fechaHoy=date("d/m/Y");
$consulta = " select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor, i.nro_factura_proveedor
    FROM ingreso_almacenes i
 left join 	tipos_ingreso ti on( i.cod_tipoingreso=ti.cod_tipoingreso)
    WHERE  i.cod_almacen='".$global_almacen."' and i.cod_ingreso_almacen='".$codIngreso."'";

$resp = mysql_query($consulta);
while ($dat = mysql_fetch_array($resp)) {
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
}
	
   
   
?>
<h1>Asociar  Items de Importacion a Ingreso</h1>
	<table border='0' class='texto' align='center'>
	<tr><th>Nro. de Ingreso</th><th>Proveedor</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>

	<tr><td align='center'><?=$nro_correlativo;?></td>
	<td ><?=$proveedor;?></td>
	<td align='center'><?=$fecha_ingreso_mostrar;?></td>
	<td><?=$nombre_tipoingreso;?></td>
	<td>&nbsp;<?=$obs_ingreso;?></td>
	</tr>
	</table>


	<br><center>
	<table class='texto' width='60%'>
	<tr><th>&nbsp;</th><th>Item de Importacion</th><th>Tipo de Calculo</th><th>Monto</th></tr>
	<?php
	$consulta = "SELECT cod_costoimp, nombre_costoimp, estado,created_by, modified_by, created_date,modified_date FROM costos_importacion  where estado=1 order by  nombre_costoimp asc ";
		
	$resp = mysql_query($consulta);

	while ($dat = mysql_fetch_array($resp)) {
		$codCostoimp = $dat['cod_costoimp'];
		$nombreCostoimp= $dat['nombre_costoimp'];
		$estado=$dat['estado'];
		$created_by= $dat['created_by'];
		$modified_by= $dat['modified_by'];
		$created_date= $dat['created_date'];
		$modified_date= $dat['modified_date'];
		$created_date_mostrar="";
	  
	  ////////////////
	  	$sqlItemsImpIngreso="select cod_costoimp,tipo_calculo,monto from costos_importacion_ingreso 
		where cod_almacen='".$global_almacen."' and cod_ingreso_almacen='".$codIngreso."' and cod_costoimp='".$codCostoimp."'";
		$respItemsImpIngreso = mysql_query($sqlItemsImpIngreso);

		$cod_costoimp=0;
		$tipo_calculo=1;
		$monto=0; 

		while ($datItemsImpIngreso = mysql_fetch_array($respItemsImpIngreso)) {
			$cod_costoimp=$datItemsImpIngreso['cod_costoimp'];
			$tipo_calculo=$datItemsImpIngreso['tipo_calculo'];
			$monto=$datItemsImpIngreso['monto']; 

		}
	  //////////////////


	
?>
		<tr>
		<td align='left'><?php if($estado==1){?> <input type='checkbox' name="codigo<?=$codCostoimp;?>" id="codigo<?=$codCostoimp;?>"  value='<?=$codCostoimp;?>' <?php if($codCostoimp==$cod_costoimp){?> checked <?php } ?>><?php } ?></td>

		<td align='left'><?=$nombreCostoimp;?></td>
		<td>
		<select name="tipoCalculo<?=$codCostoimp;?>" id="tipoCalculo<?=$codCostoimp;?>" class="texto"  >
		<option value="1" <?php if($tipo_calculo==1){ echo "selected"; } ?> >Por Nro de Items</option>
		<option value="2" <?php if($tipo_calculo==2){echo "selected"; } ?> >Por Participacion</option>
		<option value="3" <?php if($tipo_calculo==3){echo "selected"; } ?> >Por Cubicaje</option>
		</select>
	</td>
	    <td><input type="number" step="0.01"  class="texto" name="monto<?=$codCostoimp;?>"  id="monto<?=$codCostoimp;?>" value="<?=$monto;?>" required></td>

		</tr>
<?php		
	}
	
	?>	
	</table>
	</center><br>
	<input type="hidden" id="codIngreso" name="codIngreso" value="<?=$codIngreso;?>">
<div class='divBotones'>
    <input class="boton" type="button" value="Guardar" onclick="guardar(this.form);" />
    <input class="boton2" type="button" value="Cancelar" onclick="location.href='navegador_ingresomateriales.php'" />
</div>
	
	</form>
    </body>
</html>
