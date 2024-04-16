<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_material_apoyo.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminaci�n.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_material_apoyo.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un material de apoyo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un material de apoyo para editar sus datos.');
				}
				else
				{
					location.href='editar_material_apoyo.php?cod_material='+j_ciclo+'';
				}
			}
		}
		function cambiar_vista(f)
		{
			var modo_vista;
			var modo_orden;
			var grupo;
			modo_vista=f.vista.value;
			modo_orden=f.vista_ordenar.value;
			grupo=f.grupo.value;
			location.href='navegador_material.php?vista='+modo_vista+'&vista_ordenar='+modo_orden+'&grupo='+grupo;
		}
		function duplicar(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para duplicarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para duplicarlo.');
				}
				else
				{
					location.href='duplicarProducto.php?cod_material='+j_ciclo+'&tipo=1';
				}
			}
		}
		
		</script>";
		
	require("conexion.inc");
	require('estilos.inc');
	require("funciones.php");
	
	$vista_ordenar=$_GET['vista_ordenar'];
	$vista=$_GET['vista'];
	$globalAgencia=$_COOKIE['global_agencia'];
	$grupo=$_GET['grupo'];

	echo "<h1>Registro de Productos</h1>";

	echo "<form method='post' action=''>";
	$sql="SELECT m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_grupo from grupos e where e.cod_grupo=m.cod_grupo), 
		(select t.nombre_tipomaterial from tipos_material t where t.cod_tipomaterial=m.cod_tipomaterial), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor), 
		m.observaciones, imagen, m.modelo, m.medida, m.capacidad_carga_velocidad, pp.nombre, g.nombre_grupo
		from material_apoyo m
		LEFT JOIN pais_procedencia pp ON pp.codigo = m.cod_pais_procedencia
		LEFT JOIN grupos g ON g.cod_grupo = m.cod_grupo
		where m.estado='1' ";
	if($vista==1)
	{	$sql="SELECT m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_grupo from grupos e where e.cod_grupo=m.cod_grupo), 
		(select t.nombre_tipomaterial from tipos_material t where t.cod_tipomaterial=m.cod_tipomaterial), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones, imagen, m.modelo, m.medida, m.capacidad_carga_velocidad, pp.nombre, g.nombre_grupo
		LEFT JOIN pais_procedencia pp ON pp.codigo = m.cod_pais_procedencia
		LEFT JOIN grupos g ON g.cod_grupo = m.cod_grupo
		from material_apoyo m
		where m.estado='0' ";
	}
	if($grupo!=0){
		$sql.=" and m.cod_grupo in ($grupo) ";
	}
	// Filtro
	$filtro_nombre_producto = empty($_GET['filtro_nombre_producto']) ? '' : $_GET['filtro_nombre_producto'];
	if(!empty($filtro_nombre_producto)){
		$sql.=" AND m.descripcion_material LIKE '%$filtro_nombre_producto%' ";
	}
	$filtro_codLinea = empty($_GET['filtro_codLinea']) ? '' : $_GET['filtro_codLinea'];
	if(!empty($filtro_codLinea)){
		$sql.=" AND m.cod_linea_proveedor = '$filtro_codLinea' ";
	}
	$filtro_codGrupo = empty($_GET['filtro_codGrupo']) ? '' : $_GET['filtro_codGrupo'];
	if(!empty($filtro_codGrupo)){
		$sql.=" AND m.cod_grupo = '$filtro_codGrupo' ";
	}
	$filtro_medida = empty($_GET['filtro_medida']) ? '' : $_GET['filtro_medida'];
	if(!empty($filtro_medida)){
		$sql.=" AND m.medida = '$filtro_medida' ";
	}
	$filtro_modelo = empty($_GET['filtro_modelo']) ? '' : $_GET['filtro_modelo'];
	if(!empty($filtro_modelo)){
		$sql.=" AND m.modelo = '$filtro_modelo' ";
	}

	// Orden
	if($vista_ordenar==0){
		$sql=$sql." order by 4,2";
	}
	if($vista_ordenar==1){
		$sql=$sql." order by 2";	
	}
	if($vista_ordenar==2){
		$sql=$sql." order by 6,2";	
	}
	
	$sql=$sql." limit 0,200";
	
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "<table align='center' class='texto'><tr><th>Ver Productos:
	<select name='vista' class='texto' onChange='cambiar_vista(this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	echo "</select>
	</th>
	
	<th>Filtrar Grupo:
	<select name='grupo' class='texto' onChange='cambiar_vista(this.form)'>";
	echo "<option value='0'>-</option>";
	$sqlGrupo="select cod_grupo, nombre_grupo from grupos where estado=1 order by 2";
	$respGrupo=mysqli_query($enlaceCon,$sqlGrupo);
	while($datGrupo=mysqli_fetch_array($respGrupo)){
		$codGrupoX=$datGrupo[0];
		$nombreGrupoX=$datGrupo[1];
		if($codGrupoX==$grupo){
			echo "<option value='$codGrupoX' selected>$nombreGrupoX</option>";
		}else{
			echo "<option value='$codGrupoX'>$nombreGrupoX</option>";
		}
	}
	echo "</select>
	</th>
	
	<th>
	Ordenar por:
	<select name='vista_ordenar' class='texto' onChange='cambiar_vista(this.form)'>";
	if($vista_ordenar==0)	echo "<option value='0' selected>Por Grupo y Producto</option><option value='1'>Por Producto</option><option value='2'>Por Linea y Producto</option>";
	if($vista_ordenar==1)	echo "<option value='0'>Por Grupo y Producto</option><option value='1' selected>Por Producto</option><option value='2'>Por Linea y Producto</option>";
	if($vista_ordenar==2)	echo "<option value='0'>Por Grupo y Producto</option><option value='1'>Por Producto</option><option value='2' selected>Por Linea y Producto</option>";
	echo "</select>
	</th>
	</tr></table><br>";
	
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton-verde' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton-verde' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='Eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton-verde' onclick='duplicar(this.form)'>
		<type='button' name='abrirModalFiltro' class='boton-image' id='abrirModalFiltro'><i class='material-icons'>manage_search</i></button>
		</div>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>&nbsp;</th>
	<th>Nombre Producto</th>
	<th>Marca</th>
	<th>Medida</th>
	<th>Modelo</th>
	<th>Grupo</th>
	<th>Capacidad de Carga y<br>Código de Velocidad</th>
	<th>País Origen</th>
	</tr>";
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$grupo=$dat[3];
		$tipoMaterial=$dat[4];
		$nombreLinea=$dat[5];
		$observaciones=$dat[6];
		$imagen=$dat[7];
		$precioVenta=precioVenta($codigo,$globalAgencia);
		$precioVenta=$precioVenta;
		
		$modelo=$dat[8];
		$medida=$dat[9];
		$capacidad_carga_velocidad=$dat[10];
		$pais_procedencia=$dat[11];
		$nombreGrupo=$dat[12];

		if($imagen=='default.png'){
			$tamanioImagen=80;
		}else{
			$tamanioImagen=100;
		}
		echo "<tr><td align='center'>$codigo</td><td align='center'>
		<input type='checkbox' name='codigo' value='$codigo'></td>
		<td class='modalUpdNombre' data-codigo='$codigo' data-nombre_prod='$nombreProd'>$nombreProd</td>
		<td>$nombreLinea</td>
		<td>$medida</td>
		<td>$modelo</td>
		<td>$nombreGrupo</td>
		<td>$capacidad_carga_velocidad</td>
		<td>$pais_procedencia</td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
		echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton-verde' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton-verde' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='Eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton-verde' onclick='duplicar(this.form)'>
		<type='button' name='abrirModalFiltro' class='boton-image' id='abrirModalFiltro'><i class='material-icons'>manage_search</i></button>
		</div>";
		
	echo "</form>";
?>

<!-- MODAL FILTRO -->
<div class="modal fade" id="modalControlVersion" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTituloCambio">Filtro de Productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<form id="formularioRegistro" method="GET" action="navegador_material.php">
					<input type="hidden" name="vista" value="<?=$_GET['vista']?>">
					<input type="hidden" name="vista_ordenar" value="<?=$_GET['vista_ordenar']?>">
					<input type="hidden" name="grupo" value="<?=$_GET['grupo']?>">
                    <div class="row pb-2">
                        <label class="col-sm-3"><span class="text-danger">*</span> Nombre Producto :</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="filtro_nombre_producto" id="filtro_nombre_producto" placeholder="Agregar nombre del producto"/>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <label class="col-sm-3"><span class="text-danger">*</span> Marca :</label>
                        <div class="col-sm-9">
							<select name='filtro_codLinea' id='filtro_codLinea' class="selectpicker" data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
								<option value='' selected>Seleccione una Marca</option>
								<?php
									$sql1="SELECT pl.cod_linea_proveedor, pl.nombre_linea_proveedor 
											FROM proveedores p, proveedores_lineas pl 
											WHERE p.cod_proveedor = pl.cod_proveedor 
											AND pl.estado = 1 
											ORDER BY 2";
									$resp1=mysqli_query($enlaceCon,$sql1);
									while($dat1=mysqli_fetch_array($resp1))
									{	$codTipo	= $dat1[0];
										$nombreTipo = $dat1[1];
										echo "<option value='$codTipo'>$nombreTipo</option>";
									}
								?>
							</select>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <label class="col-sm-3"><span class="text-danger">*</span> Grupo :</label>
                        <div class="col-sm-9">
							<select name='filtro_codGrupo' id='filtro_codGrupo' class="selectpicker" data-style='btn btn-danger' data-show-subtext='true' data-live-search='true'>
								<option value='' selected>Seleccione una Marca</option>
								<?php
									$sql1="SELECT g.cod_grupo, g.nombre_grupo 
											FROM grupos g 
											WHERE g.estado = 1 
											ORDER BY 2";
									$resp1=mysqli_query($enlaceCon,$sql1);
									while($dat1=mysqli_fetch_array($resp1))
									{	$codigo	= $dat1[0];
										$nombre = $dat1[1];
										echo "<option value='$codigo'>$nombre</option>";
									}
								?>
							</select>
                        </div>
                    </div>					
                    <div class="row pb-2">
                        <label class="col-sm-3"><span class="text-danger">*</span> Medida :</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="filtro_medida" id="filtro_medida" placeholder="Agregar medida"/>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <label class="col-sm-3"><span class="text-danger">*</span> Modelo :</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="filtro_modelo" id="filtro_modelo" placeholder="Agregar modelo"/>
                        </div>
                    </div>
					<div class="modal-footer p-0 pt-2">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="submit" id="formGuardarControl" class="btn btn-primary">Filtrar</button>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODAL FILTRO -->
<div class="modal fade" id="modalActualizaNombre" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTituloCambio">Filtro de Productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<form method="GET" action="actualiza_nombre_material.php">
					<input type="hidden" name="upd_cod_material" id="upd_cod_material">
                    <div class="row pb-2">
                        <label class="col-sm-3"><span class="text-danger">*</span> Nombre Producto :</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="upd_nombre" id="upd_nombre" placeholder="Agregar nombre del producto" autocomplete="off"/>
                        </div>
                    </div>
					<div class="modal-footer p-0 pt-2">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="submit" id="formGuardarControl" class="btn btn-primary">Actualizar</button>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		// Agregar evento click al botón para abrir el modal
		$('#abrirModalFiltro').click(function(){
			// Mostrar el modal
			$('#modalControlVersion').modal('show');
		});
	});

	// Abre modal para modificar Nombre de ITEM
	$('body').on('click', '.modalUpdNombre', function(){
		let codigo	   = $(this).data('codigo');
		let nombreProd = $(this).data('nombre_prod');
		$('#upd_cod_material').val(codigo);
		$('#upd_nombre').val(nombreProd);
		$('#modalActualizaNombre').modal('show');
	});
</script>
<style>
	.modalUpdNombre {
		cursor: pointer;
	}
	.modalUpdNombre:hover {
		background-color: #007bff;
		color: white;
	}
</style>