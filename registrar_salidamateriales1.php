<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>

		
		
	<script type='text/javascript' language='javascript'>
		function nuevoAjax()
		{	var xmlhttp=false;
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

		function listaMateriales(f){
			var contenedor;
			// var codTipo=f.itemTipoMaterial.value;
			var codTipo		= document.getElementById('itemTipoMaterial').value;
			// var nombreItem	= f.itemNombreMaterial.value;
			var nombreItem	= document.getElementById('itemNombreMaterial').value;
			
			// var codInterno= f.codigoInterno.value;
			var codInterno= document.getElementById('codigoInterno').value;
			contenedor = document.getElementById('divListaMateriales');


			var arrayItemsUtilizados=new Array();	
			var i=0;
			for(var j=1; j<=num; j++){
				if(document.getElementById('materiales'+j)!=null){
					console.log("codmaterial: "+document.getElementById('materiales'+j).value);
					arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
					i++;
				}
			}
			
			ajax=nuevoAjax();
			ajax.open("GET", "ajaxListaMateriales.php?codTipo="+codTipo+"&nombreItem="+nombreItem+"&codInterno="+codInterno+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
			ajax.send(null)
		}

		function ajaxTipoDoc(f){
			var contenedor;
			contenedor=document.getElementById("divTipoDoc");
			ajax=nuevoAjax();
			// var codTipoSalida=(f.tipoSalida.value);
			var codTipoSalida=(document.getElementById("tipoSalida").value);
			ajax.open("GET", "ajaxTipoDoc.php?codTipoSalida="+codTipoSalida,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText;
					$('#divTipoDoc select').selectpicker({style: 'btn btn-rose'});
				}
			}
			ajax.send(null);
		}


		function ajaxPesoMaximo(codVehiculo){
			var contenedor;
			contenedor=document.getElementById("divPesoMax");
			ajax=nuevoAjax();
			var codVehiculo=codVehiculo;
			ajax.open("GET", "ajaxPesoMaximo.php?codVehiculo="+codVehiculo,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
			ajax.send(null);
		}

		function ajaxNroDoc(f){
			var contenedor;
			contenedor=document.getElementById("divNroDoc");
			ajax=nuevoAjax();
			// var codTipoDoc=(f.tipoDoc.value);
			var codTipoDoc=(document.getElementById("tipoDoc").value);
			ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
			ajax.send(null);
		}

		function actStock(indice){
			var contenedor;
			contenedor=document.getElementById("idstock"+indice);
			var codmat=document.getElementById("materiales"+indice).value;
			var codalm=document.getElementById("global_almacen").value;
			
			ajax=nuevoAjax();
			ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText;
				}
			}
			ajax.send(null);
			
			
		}

		function buscarMaterial(f, numMaterial){
			// f.materialActivo.value=numMaterial;
			document.getElementById('materialActivo').value=numMaterial;
			document.getElementById('divRecuadroExt').style.visibility='visible';
			document.getElementById('divProfileData').style.visibility='visible';
			document.getElementById('divProfileDetail').style.visibility='visible';
			document.getElementById('divboton').style.visibility='visible';
			
			document.getElementById('divListaMateriales').innerHTML='';
			document.getElementById('itemNombreMaterial').value='';	
			document.getElementById('codigoInterno').value='';	
			document.getElementById('codigoInterno').focus();	
		}
		function Hidden(){
			document.getElementById('divRecuadroExt').style.visibility='hidden';
			document.getElementById('divProfileData').style.visibility='hidden';
			document.getElementById('divProfileDetail').style.visibility='hidden';
			document.getElementById('divboton').style.visibility='hidden';

		}
		function setMateriales(f, cod, nombreMat){
			// var numRegistro=f.materialActivo.value;
			var numRegistro=document.getElementById('materialActivo').value;
			
			document.getElementById('materiales'+numRegistro).value=cod;
			document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
			
			document.getElementById('divRecuadroExt').style.visibility='hidden';
			document.getElementById('divProfileData').style.visibility='hidden';
			document.getElementById('divProfileDetail').style.visibility='hidden';
			document.getElementById('divboton').style.visibility='hidden';
			
			document.getElementById("cantidad_unitaria"+numRegistro).focus();

			actStock(numRegistro);
		}

		num=0;
		cantidad_items=0;

		function mas(obj) {
			if(num>=15){
				alert("No puede registrar mas de 15 items en una nota.");
			}else{
				//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
				var banderaItems0=0;
				for(var j=1; j<=num; j++){
					if(document.getElementById('materiales'+j)!=null){
						if(document.getElementById('materiales'+j).value==0){
							banderaItems0=1;
						}
					}
				}
				//fin validacion
				console.log("bandera: "+banderaItems0);

				if(banderaItems0==0){
					num++;
					cantidad_items++;
					console.log("num: "+num);
					console.log("cantidadItems: "+cantidad_items);
					document.getElementById('cantidad_material').value = cantidad_items; 
					fi = document.getElementById('fiel');
					contenedor = document.createElement('div');
					contenedor.id = 'div'+num;  
					fi.type="style";
					fi.appendChild(contenedor);
					var div_material;
					div_material=document.getElementById("div"+num);			
					ajax=nuevoAjax();
					ajax.open("GET","ajaxMaterialSalida.php?codigo="+num,true);
					ajax.onreadystatechange=function(){
						if (ajax.readyState==4) {
							div_material.innerHTML=ajax.responseText;
							buscarMaterial(form1, num);
						}
					}		
					ajax.send(null);
				}

			}
			
		}
				
		function menos(numero) {
			cantidad_items--;
			console.log("TOTAL ITEMS: "+num);
			console.log("NUMERO A DISMINUIR: "+numero);
			if(numero==num){
				num=parseInt(num)-1;
			}
			fi = document.getElementById('fiel');
			fi.removeChild(document.getElementById('div'+numero));
		}

		function pressEnter(e, f){
			tecla = (document.all) ? e.keyCode : e.which;
			if (tecla==13){
				document.getElementById('itemNombreMaterial').focus();
				listaMateriales(f);
				return false;
			}
		}
		function validar(f){
			
			// f.cantidad_material.value=num;
			document.getElementById('cantidad_material').value=num;
			var cantidadItems=num;
			console.log("numero de items: "+cantidadItems);
			if(cantidadItems>0){
				
				var item="";
				var cantidad="";
				var stock="";
				var descuento="";
								
				for(var i=1; i<=cantidadItems; i++){
					console.log("valor i: "+i);
					console.log("objeto materiales: "+document.getElementById("materiales"+i));
					if(document.getElementById("materiales"+i)!=null){
						item=parseFloat(document.getElementById("materiales"+i).value);
						cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
						stock=parseFloat(document.getElementById("stock"+i).value);
				
						console.log("materiales"+i+" valor: "+item);
						console.log("stock: "+stock+" cantidad: "+cantidad);

						if(item==0){
							alert("Debe escoger un item en la fila "+i);
							return(false);
						}		
						if(stock<cantidad){
							alert("No puede sacar cantidades mayores a las existencias. Fila "+i);
							return(false);
						}						
					}
				}
			}else{
				alert("El ingreso debe tener al menos 1 item.");
				return(false);
			}
		}
			
			
		var tablaBuscadorSucursales=null;
		function encontrarMaterial(numMaterial){
			fila_seleccionada = numMaterial;

			let cod_almacen_destino = $('#almacen').val();

			var cod_material = $("#materiales"+numMaterial).val();
			var parametros	 = {
							"cod_material" : cod_material,
							"cod_almacen_destino" : cod_almacen_destino
						};
			$.ajax({
				type: "GET",
				dataType: 'html',
				url: "ajax_encontrar_productos.php",
				data: parametros,
				success:  function (resp) { 
				// alert(resp);           
					$("#modalProductosCercanos").modal("show");
					$("#tabla_datosE").html(resp);   
				}
			});	
		}
	</script>

		
<?php
echo "<body>";
require("conexion.inc");
require("estilos_almacenes.inc");

$global_almacen=$_COOKIE['global_almacen'];

if(empty($fecha))
{   $fecha=date("Y-m-d");
}

//$fechaIni=date('Y-m-d',strtotime($fecha.'-90 days'));
$fechaIni=date("Y-m-d");

$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' order by cod_salida_almacenes desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}
?>
<div id="appVue" hidden>

	<div><!-- CONTIENE BASE GENERAL DEL APP -->
		<form action='guardarSalidaMaterial.php' method='POST' name='form1'>
			<h1>Registrar Salida de Almacen</h1>

			<input type="hidden" name='global_almacen' id='global_almacen' value='<?=$global_almacen;?>'>
			<table class='texto' align='center' width='90%'>
				<tr>
					<th width="20%">Tipo de Salida</th>
					<th width="20%">Tipo de Documento</th>
					<th width="20%">Nro. Salida</th>
					<th width="20%">Fecha</th>
					<th width="20%">Almacen Destino</th>
				</tr>
			<tr>
			<td align='center'>
				<select name='tipoSalida' id='tipoSalida' 
						onChange='ajaxTipoDoc(form1)' 
						required 
						class='selectpicker form-control' 
						data-style='btn btn-success'
						v-model="tipo_salida">
					<option value="">--------</option>
			<?php
				$sqlTipo="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida<>1001 order by 2";
				$respTipo=mysqli_query($enlaceCon,$sqlTipo);
				while($datTipo=mysqli_fetch_array($respTipo)){
					$codigo=$datTipo[0];
					$nombre=$datTipo[1];
			?>
					<option value='<?php echo $codigo?>'><?php echo $nombre?></option>
			<?php		
				}
			?>
				</select>
			</td>
			<td align='center'>
				<div id='divTipoDoc'>
					<select name='tipoDoc' id='tipoDoc' class='selectpicker form-control' data-style='btn btn-warning'><option value="0"></select>
				</div>
			</td>
			<td align='center'>
				<div id='divNroDoc' class='textogranderojo'>
				</div>
			</td>

			<td align='center'>
				<input type='date' class='texto' value='<?=$fecha;?>' id='fecha' name='fecha' min='<?=$fechaIni;?>' max='<?=$fecha;?>'>
			</td>

			<td align='center'>
				<select name='almacen' id='almacen' required class='selectpicker form-control' data-style='btn btn-rose'>
					<option value=''>-----</option>
			<?php
				$sql3="select cod_almacen, nombre_almacen from almacenes where cod_almacen not in ($global_almacen) order by nombre_almacen";
				$resp3=mysqli_query($enlaceCon,$sql3);
				while($dat3=mysqli_fetch_array($resp3)){
					$cod_almacen=$dat3[0];
					$nombre_almacen="$dat3[1] $dat3[2] $dat3[3]";
			?>
					<option value="<?php echo $cod_almacen?>"><?php echo $nombre_almacen?></option>
			<?php		
				}
			?>
				</select>
			</td>
			</tr>

			<tr>
				<th align='center' v-show="tipo_salida == 1000">
					<label style="color:black;">Transportadora</label>
					<div style="display: flex;">
						<select name='cod_transportadora' 
								id='cod_transportadora' 
								class='selectpicker form-control' 
								data-style="btn btn-secondary" 
								data-live-search='true'
								v-model="transportadora_seleccionada"
								@change="listaDependientes">
						</select>
						<button type="button" class="btn btn-success btn-round btn-sm text-white circle" @click="abrirModalTransportadora()">+</button>
					</div>
				</th>
				<th align='center' v-show="tipo_salida == 1000">
					<label style="color:black;">Chofer</label>
					<div style="display: flex;">
						<select name='cod_transportista' 
								id='cod_transportista' 
								class='selectpicker form-control' 
								data-style="btn btn-secondary" 
								data-live-search='true'
								v-model="chofer_seleccionado">
						</select>
						<button type="button" class="btn btn-success btn-round btn-sm text-white circle" @click="abrirModalChofer()">+</button>
					</div>
				</th>
				<th align='center' v-show="tipo_salida == 1000">
					<label style="color:black;">Vehículo</label>
					<div style="display: flex;">
						<select name='cod_vehiculo' 
								id='cod_vehiculo' 
								class='selectpicker form-control' 
								data-style="btn btn-secondary" 
								data-live-search='true'
								v-model="vehiculo_seleccionado">
						</select>
						<button type="button" class="btn btn-success btn-round btn-sm text-white circle" @click="abrirModalVehiculo()">+</button>
					</div>
				</th>
				<th align='center' v-show="tipo_salida == 1000">
					<label style="color:black;">Tipo Traspaso</label>
					<select name='cod_tipotraspaso' id='cod_tipotraspaso' required class='selectpicker form-control' data-style='btn btn-rose'>
					<?php
						$sqlTipo = "SELECT tt.cod_tipotraspaso, tt.nombre
									FROM tipos_traspaso tt
									WHERE tt.estado = 1
									ORDER BY tt.cod_tipotraspaso";
						$respTipo = mysqli_query($enlaceCon,$sqlTipo);
						while($data = mysqli_fetch_array($respTipo)){
					?>
						<option value="<?= $data['cod_tipotraspaso'] ?>"><?= $data['nombre'] ?></option>
					<?php		
						}
					?>
					</select>
				</th>
				<th align='center' v-bind:colspan="tipo_salida == 1000 ? 1 : 5">
					<label style="color:black;">Observaciones</label>
					<input type='text' class='form-control' name='observaciones' placeholder="Ingrese observaciones" style="background:white;">
				</th>
			</tr>
			</table>


			<input type='hidden' id='materialActivo' name='materialActivo' value="0">
			<input type='hidden' id='cantidad_material' name='cantidad_material' value="0">
			
			<br>

			<fieldset id="fiel" style="width:100%;border:0;">
				<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="9">
						<b>Detalle de la Transaccion   </b><input class="boton" type="button" value="Agregar (+)" onclick="mas(this)" />
					</td>
				</tr>
				<tr align="center">
					<th width="10%">-</th>
					<th width="40%">Material</th>
					<th width="20%">Stock</th>
					<th width="20%">Cantidad</th>
					<th width="10%">&nbsp;</th>
				</tr>
				</table>
			</fieldset>

			<?php

			echo "<div class='divBotones'>
				<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
				<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_salidamateriales.php\"'>
			</div>";

			echo "</div>";

			?>



			<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1000px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
			</div>

			<div id="divboton" style="position: absolute; top:20px; left:1120px;visibility:hidden; text-align:center; z-index:3">
				<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
			</div>

			<div id="divProfileData" style="background-color:#FFF; width:950px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
				<div id="divProfileDetail" style="visibility:hidden; text-align:center">
					<table align='center'>
						<tr><th>Grupo</th><th>CodInterno</th><th>Material</th><th>&nbsp;</th></tr>
						<tr>
						<td><select class="textogranderojo" name='itemTipoMaterial' id="itemTipoMaterial" style="width:300px">
						<?php
						$sqlTipo="select g.cod_grupo, g.nombre_grupo from grupos g
						where g.estado=1 order by 2;";
						$respTipo=mysqli_query($enlaceCon,$sqlTipo);
						echo "<option value='0'>--</option>";
						while($datTipo=mysqli_fetch_array($respTipo)){
							$codTipoMat=$datTipo[0];
							$nombreTipoMat=$datTipo[1];
							echo "<option value=$codTipoMat>$nombreTipoMat</option>";
						}
						?>

						</select>
						</td>
						
						<td>
							<input type='text' name='codigoInterno' id='codigoInterno' class="textomedianorojo" onkeypress="return pressEnter(event, this.form);">
						</td>
						
						<td>
							<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
						</td>
						<td>
							<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
						</td>
						</tr>
						
					</table>
					<div id="divListaMateriales">
					</div>
				
				</div>
			</div>

		</form>
		
		<!-- MODAL TRANSPORTADORA -->
		<div class="modal fade" id="modalTransportadora" tabindex="-1" role="dialog" aria-labelledby="modalTransportadora" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalTituloCambio"><b>Registro de Transportadora</b></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="formularioRegistro" method="GET" action="navegador_material.php">
							<div class="row pb-2">
								<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Nombre:</label>
								<div class="col-sm-9">
									<input class="elegant-input" type="text" id="tr_nombre" v-model="tr_nombre" placeholder="Ingresar nombre de transportadora"/>
								</div>
							</div>
							<div class="modal-footer p-0 pt-2">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary" @click="guardarTransportadora()">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL CHOFER -->
		<div class="modal fade" id="modalChofer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalTituloCambio"><b>Registro de Chofer</b></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Transportadora:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" v-model="nombre_transportadora" name="nombre_transportadora" id="nombre_transportadora" disabled/>
							</div>
						</div>
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Nombre Completo:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" placeholder="Ingresar nombre completo" v-model="ch_nombre" id="ch_nombre"/>
							</div>
						</div>
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> N° Licencia:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" placeholder="Ingresar nro. de licencia" v-model="ch_nro_licencia" id="ch_nro_licencia"/>
							</div>
						</div>
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Celular:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" placeholder="Ingresar nro. celular" v-model="ch_celular" id="ch_celular"/>
							</div>
						</div>
						<div class="modal-footer p-0 pt-2">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary" @click="guardarChofer()">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL VEHICULO -->
		<div class="modal fade" id="modalVehiculo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalTituloCambio"><b>Registro de Vehículo</b></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Transportadora:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" v-model="nombre_transportadora" name="nombre_transportadora" id="nombre_transportadora" disabled/>
							</div>
						</div>
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> Descripción:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" placeholder="Ingresar descripción del vehículo" v-model="veh_nombre" id="veh_nombre"/>
							</div>
						</div>
						<div class="row pb-2">
							<label class="col-sm-3 elegant-label"><span class="text-danger">*</span> N° Placa:</label>
							<div class="col-sm-9">
								<input class="elegant-input" type="text" placeholder="Ingresar nro. de placa" v-model="veh_placa" id="veh_placa"/>
							</div>
						</div>
						<div class="modal-footer p-0 pt-2">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary" @click="guardarVehiculo()">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


	<!-- Modal Ver Productos de otras Sucursales -->
	<div class="modal fade modal-primary" id="modalProductosCercanos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content card">
				<div class="card-header card-header-primary card-header-icon">
					<div class="card-icon">
						<i class="material-icons">place</i>
					</div>
					<h4 class="card-title text-primary font-weight-bold">Stock de Productos en Sucursales</h4>
					<button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
						<i class="material-icons">close</i>
					</button>
				</div>
				<div class="card-body">
					<div class="form-group">
						<input type="text" class="form-control pull-right" style="width:20%" id="busqueda_sucursal" placeholder="Buscar Sucursal">
					</div>
					<br>
					<table class="table table-sm table-bordered" id='tabla_sucursal'>
						<thead>
							<tr style='background: #ADADAD;color:#000;'>
								<th width='10%'>-</th>
							</tr>
						</thead>
						<tbody id="tabla_datosE">
						</tbody>
					</table>
					<br><br>
				</div>
			</div>  
		</div>
	</div>

	<style>
		/**
		 * ESTILO DE FORMULARIO
		 **/
		.elegant-label {
            font-family: 'Poppins', sans-serif;
			font-weight: bold;
			color: #6c757d;
			display: flex;
			align-items: center;
			margin-bottom: 0;
		}

		.elegant-label span.text-danger {
			margin-right: 5px;
		}
		.elegant-input {
			width: 100%;
			border: 2px solid #ced4da;
			border-radius: 5px;
			padding: 5px 5px;
			transition: all 0.3s ease;
		}

		.elegant-input:focus {
			border-color: #80bdff;
			box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
		}

		.elegant-input::placeholder {
			color: #999;
			opacity: 1;
		}
	</style>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	
	<script>
		// Inicialización de VUE
		var app = new Vue({
			el: '#appVue',
			data: {
				// Datos Generales
				tipo_salida: '',
				nombre_transportadora: '',
				// Datos de Chofer
				chofer_seleccionado: 0,
				ch_nombre: '',
				ch_nro_licencia: '',
				ch_celular: '',
				// Datos de Transportadora
				transportadora_seleccionada: 0,
				tr_nombre: '',
				// Datos de Vehiculo
				vehiculo_seleccionado: 0,
				veh_nombre: '',
				veh_placa: ''
			},
			mounted() {
				let me = this;
				me.listaTransportadora();
			},
			methods: {
				// * ABRIR MODAL TRANSPORTADORA
				abrirModalTransportadora(){
					let me = this;
					me.ch_nombre 		= '';
					me.ch_nro_licencia 	= '';
					me.ch_celular 		= '';
					$('#modalTransportadora').modal('show');
				},
				// * ABRIR MODAL CHOFER
				abrirModalChofer(){
					let me = this;
					let cod_transportadora = $('#cod_transportadora').val();
					let texto_transportadora = $('#cod_transportadora option:selected').text();
					me.nombre_transportadora = texto_transportadora;
					if (cod_transportadora == '' || cod_transportadora == '0') {
						alert('Debe seleccionar una transportadora');
					} else {
						me.ch_nombre 		= '';
						me.ch_nro_licencia 	= '';
						me.ch_celular 		= '';
						$('#modalChofer').modal('show');
					}
				},
				// * ABRIR MODAL VEHICULO
				abrirModalVehiculo(){
					let me = this;
					let cod_transportadora = $('#cod_transportadora').val();
					let texto_transportadora = $('#cod_transportadora option:selected').text();
					me.nombre_transportadora = texto_transportadora;
					if (cod_transportadora == '' || cod_transportadora == '0') {
						alert('Debe seleccionar una transportadora');
					} else {
						me.veh_nombre = '';
						me.veh_placa  = '';
						$('#modalVehiculo').modal('show');
					}
				},
				/*****************************************************************/
				listaDependientes(){
					let me = this;
					me.listaChoferes();
					me.listaVehiculos();
				},
				/*****************************************************************/
				/**
				 * ? LISTA DE CHOFERES
				 */
				listaChoferes() {
					let me = this;
					let cod_transportadora = me.transportadora_seleccionada;
                    axios.get(`transportistas/listaSelect.php?cod_transportadora=${cod_transportadora}`)
                        .then(response => {
							if (response.data.success) {
								$("#cod_transportista").html(response.data.html);		
								$("#cod_transportista").selectpicker('refresh');
								$("#cod_transportista").val(me.chofer_seleccionado).trigger('change');
								// console.log(response.data)
                            } else {
								alert(response.message);
                                console.error(response.data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener la lista de choferes:', error);
                        });
                },
				/**
				 * ? GUARDA DATOS DE CHOFER
				 */
				guardarChofer() {
					let me = this;
					const formData = new FormData();
					formData.append('nombre', me.ch_nombre);
					formData.append('nro_licencia', me.ch_nro_licencia);
					formData.append('celular', me.ch_celular);
					formData.append('cod_transportadora', me.transportadora_seleccionada);
					axios.post('transportistas/registrar.php', formData, {
						headers: { 'Content-Type': 'multipart/form-data' }
					})
					.then(response => {
						if (response.data.success) {
							Swal.fire({
								type: 'success',
								title: 'Éxito',
								text: response.data.message
							});
							me.chofer_seleccionado = response.data.codigo;
							me.listaChoferes();
							$('#modalChofer').modal('hide');
						} else {
							Swal.fire({
								type: 'warning',
								title: 'Ops!',
								text: response.data.message
							});
						}
					})
					.catch(error => {
						console.error('Error al guardar el chofer:', error);
					});
				},
				/*****************************************************************/
				/**
				 * ? LISTA DE TRANSPORTADORAS
				 */
				listaTransportadora() {
					let me = this;
                    axios.get('transportadoras/listaSelect.php')
                        .then(response => {
							if (response.data.success) {
								$("#cod_transportadora").html(response.data.html);		
								$("#cod_transportadora").selectpicker('refresh');
								$("#cod_transportadora").val(me.transportadora_seleccionada).trigger('change');
								// console.log(response.data)
                            } else {
								alert(response.message);
                                console.error(response.data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener la lista de choferes:', error);
                        });
                },
				/**
				 * ? GUARDA DATOS DE TRANSPORTADORA
				 */
				guardarTransportadora() {
					let me = this;
					const formData = new FormData();
					formData.append('nombre', me.tr_nombre);
					axios.post('transportadoras/registrar.php', formData, {
						headers: { 'Content-Type': 'multipart/form-data' }
					})
					.then(response => {
						if (response.data.success) {
							Swal.fire({
								type: 'success',
								title: 'Éxito',
								text: response.data.message
							});
							me.transportadora_seleccionada = response.data.codigo;
							me.listaTransportadora();
							$('#modalTransportadora').modal('hide');
						} else {
							Swal.fire({
								type: 'warning',
								title: 'Ops!',
								text: response.data.message
							});
						}
					})
					.catch(error => {
						console.error('Error al guardar el transportadora:', error);
					});
				},
				/*****************************************************************/
				/**
				 * ? LISTA DE VEHICULOS
				 */
				listaVehiculos() {
					let me = this;
					let cod_transportadora = me.transportadora_seleccionada;
                    axios.get(`vehiculos/listaSelect.php?cod_transportadora=${cod_transportadora}`)
                        .then(response => {
							if (response.data.success) {
								$("#cod_vehiculo").html(response.data.html);		
								$("#cod_vehiculo").selectpicker('refresh');
								$("#cod_vehiculo").val(me.vehiculo_seleccionado).trigger('change');
								// console.log(response.data)
                            } else {
								alert(response.message);
                                console.error(response.data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener la lista de choferes:', error);
                        });
                },
				/**
				 * ? GUARDA DATOS DE VEHICULO
				 */
				guardarVehiculo() {
					let me = this;
					const formData = new FormData();
					formData.append('nombre', me.veh_nombre);
					formData.append('placa', me.veh_placa);
					formData.append('cod_transportadora', me.transportadora_seleccionada);
					axios.post('vehiculos/registrar.php', formData, {
						headers: { 'Content-Type': 'multipart/form-data' }
					})
					.then(response => {
						if (response.data.success) {
							Swal.fire({
								type: 'success',
								title: 'Éxito',
								text: response.data.message
							});
							me.vehiculo_seleccionado = response.data.codigo;
							me.listaVehiculos();
							$('#modalVehiculo').modal('hide');
						} else {
							Swal.fire({
								type: 'warning',
								title: 'Ops!',
								text: response.data.message
							});
						}
					})
					.catch(error => {
						console.error('Error al guardar el chofer:', error);
					});
				},
			}
		});
	</script>
	
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			document.getElementById("appVue").removeAttribute("hidden");
		});
		// Modificación de Sucursal en lista de ITEMS
		let fila_seleccionada = 0;
		$('body').on('click', '.nueva_sucursal', function(){
			let cod_sucursal = $(this).data('cod_sucursal');
			let nombre 		 = $(this).data('nombre');
			let stock 		 = $(this).data('stock');
			
			$('#cod_sucursales'+fila_seleccionada).val(cod_sucursal);
			$('#nombreSucursal'+fila_seleccionada).html(nombre);
			$('#stock'+fila_seleccionada).val(stock);
			$('#modalProductosCercanos').modal('toggle');
		});
	</script>
</body>