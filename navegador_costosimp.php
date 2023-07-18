<html>

<script>
function enviar_nav()
{   location.href='registrar_costoimp.php';
}
function editar_costoimp(f)
{   
	var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para editarlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para editarlo.');
        }
        else
        {	location.href='editar_costoimp.php?codigo='+j_cod_registro;
        }
    }
}


function anular_costoimp(f)
{   
	var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
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
        {	location.href='anular_costoimp.php?codigo_registro='+j_cod_registro;
        }
    }
}
        </script>
    </head>
    <body>
<form method='post' name='form1' action=''>
<?php

require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require('home_almacen.php');
require('funciones.php');

$globalCiudad=$_COOKIE["global_agencia"];

$fechaHoy=date("d/m/Y");
?>
<h1>Listado de Items de Importacion</h1>
<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Items de Importacion Anulados</th><td bgcolor='#ff8080' width='10%'></td>
</tr></table><br>
<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
<input type='button' value='Editar' class='boton' onclick='editar_costoimp(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_costoimp(this.form)'>
</div>


	<br><center>
	<table class='texto' width='60%'>
	<tr><th>&nbsp;</th><th>ID</th><th>Descripcion</th><th>Registrado Por</th><th>Modificado Por</th><th>Estado</th></tr>
	<?php
	$consulta = "SELECT cod_costoimp, nombre_costoimp, estado,created_by, modified_by, created_date,modified_date FROM costos_importacion  order by  nombre_costoimp asc ";
		
	$resp = mysqli_query($enlaceCon,$consulta);

	while ($dat = mysqli_fetch_array($resp)) {
		$codCostoimp = $dat['cod_costoimp'];
		$nombreCostoimp= $dat['nombre_costoimp'];
		$estado=$dat['estado'];
		$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora
	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
	if(!empty($created_by)){
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	}
	//////
	if(!empty($modified_by)){
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	}
	////////////


		$color_fondo = "";
		if ($estado == 0) {
			$color_fondo = "#ff8080";

		}else {
			$color_fondo = "";
			

		}
		?>
		<tr>
		<td align='left'><?php if($estado==1){?> <input type='checkbox' name='codigo' value='<?=$codCostoimp;?>'><?php } ?></td>
		<td align='left'><?=$codCostoimp;?></td>
		<td align='left'><?=$nombreCostoimp;?></td>
			<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>
		<td align='left' bgcolor='<?=$color_fondo;?>'>&nbsp;</td>
		</tr>
<?php		
	}
	
	?>	
	</table>
	</center><br>
	
<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
<input type='button' value='Editar' class='boton' onclick='editar_costoimp(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_costoimp(this.form)'>
</div>
	
	</form>
    </body>
</html>
