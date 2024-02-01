<?php

	require("conexion.inc");
	require("estilos_almacenes.inc");
	require("funciones.php");
	require("funcion_nombres.php");
	
	$global_almacen=$_COOKIE["global_almacen"];
	
	$global_agencia=$_POST['rpt_territorio'];
	$rpt_grupo=$_POST['rpt_grupo'];

	$rptVer=$_POST['rpt_ver'];

	$rptGrupoX=implode(",", $rpt_grupo);
	
	$nombreAgencia=nombreTerritorio($global_agencia);
	$nombreGrupoX=nombreGrupo($rptGrupoX);

	echo "<h1>Reporte de Precios</h1>";
	echo "<h2>Agencia: $nombreAgencia</h2>";
	echo "<h2>Grupo: $nombreGrupoX</h2>";
	
	
	echo "<div id='divCuerpo'>";
	$sql="select codigo_material, descripcion_material, (select g.nombre_grupo from grupos g where g.cod_grupo=ma.cod_grupo)grupo from material_apoyo ma
			where ma.estado=1 and ma.cod_grupo in ($rptGrupoX) order by 3,2";
	$resp=mysql_query($sql);
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Material</th>
	<th>Grupo</th>
	<th>Existencias</th>
	<th>Costo</th>
	<th>Precio</th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreGrupo=$dat[2];
		
		//sacamos existencias
		$rpt_fecha=date("Y-m-d");
		$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$global_almacen'
		and id.cod_material='$codigo' and i.ingreso_anulado=0";
		$resp_ingresos=mysql_query($sql_ingresos);
		$dat_ingresos=mysql_fetch_array($resp_ingresos);
		$cant_ingresos=$dat_ingresos[0];
		$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$global_almacen'
		and sd.cod_material='$codigo' and s.salida_anulada=0";
		$resp_salidas=mysql_query($sql_salidas);
		$dat_salidas=mysql_fetch_array($resp_salidas);
		$cant_salidas=$dat_salidas[0];
		$stock2=$cant_ingresos-$cant_salidas;

		//COSTO DEL ITEM
		$precio0=costoVenta($codigo,$global_agencia);	
		
		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo and p.cod_ciudad='$global_agencia'";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio1=mysql_result($respPrecio,0,0);
			$precio1=redondear2($precio1);
		}else{
			$precio1=0;
			$precio1=redondear2($precio1);
		}

		$indice++;

		if($rptVer==1){	
			echo "<tr><td>$nombreMaterial </td><td>$nombreGrupo</td>";
			echo "<td align='center'>$stock2</td>";
			echo "<td align='center'>$precio0</td>";		
			echo "<td align='center'><div id='1$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio1, 1)';>$precio1</div></td>";
			echo "</tr>";		
		}if($rptVer==2 and $stock2>0){	
			echo "<tr><td>$nombreMaterial </td><td>$nombreGrupo</td>";
			echo "<td align='center'>$stock2</td>";
			echo "<td align='center'>$precio0</td>";		
			echo "<td align='center'><div id='1$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio1, 1)';>$precio1</div></td>";
			echo "</tr>";			
		}if($rptVer==3 and $stock2==0){	
			echo "<tr><td>$nombreMaterial </td><td>$nombreGrupo</td>";
			echo "<td align='center'>$stock2</td>";
			echo "<td align='center'>$precio0</td>";		
			echo "<td align='center'><div id='1$codigo' onDblClick='cambiaPrecio(this.form, this.id, $codigo, $precio1, 1)';>$precio1</div></td>";
			echo "</tr>";
		}

	}
	echo "</table></center><br>";
	echo "</div>";
	
?>
</form>

