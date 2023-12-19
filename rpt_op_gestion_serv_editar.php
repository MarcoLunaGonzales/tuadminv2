<?php

require("conexion.inc");
require("estilos_almacenes.inc");

?>
<style>
    .formulario {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .formulario th {
        text-align: center;
        padding-right: 10px;
        font-weight: bold;
        vertical-align: top;
    }

    .formulario input[type="text"],
    .formulario select {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Campo enfocado */
    .formulario input:focus,
    .formulario select:focus {
        border-color: #007BFF;
    }

    /* Campos requeridos */
    .requerido-label::after {
        content: " *"; 
        color: #FF0000;
    }

    .btn-primario,
    .btn-danger {
        background-color: #007BFF;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        margin-right: 10px;
        transition: background-color 0.3s;
    }

    .btn-danger {
        background-color: #FF0000;
    }

    .btn-primario:hover,
    .btn-danger:hover {
        background-color: #0056b3;
    }

    input[type="date"] {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        background-color: #FFFFFF;
        color: #333;
    }

</style>
<?php 
    $codigo   = $_GET['codigo']; 
    $consulta = "SELECT oc.codigo,
                        oc.cod_proveedor,
                        oc.observaciones, 
                        oc.fecha_factura_proveedor,
                        oc.nro_documento,
                        oc.monto_orden,
                        oc.dias_credito
                FROM ordenes_compra oc
                WHERE oc.codigo = '$codigo'";
    $rs = mysqli_query($enlaceCon,$consulta);
    
    $form_codigo        = "";
    $form_cod_proveedor = "";
    $form_observaciones = "";
    $form_fecha_factura_proveedor         = "";
    $form_nro_documento = "";
    $form_monto_orden   = "";
    $form_dias_credito  = "";
    if ($rs) {
        $datos = mysqli_fetch_assoc($rs);
        // Verifica si se obtuvieron resultados
        if ($datos) {
            $form_codigo        = $datos['codigo'];
            $form_cod_proveedor = $datos['cod_proveedor'];
            $form_observaciones = $datos['observaciones'];
            $form_fecha_factura_proveedor         = $datos['fecha_factura_proveedor'];
            $form_nro_documento = $datos['nro_documento'];
            $form_monto_orden   = $datos['monto_orden'];
            $form_dias_credito  = $datos['dias_credito'];
        }
    }
?>
<div class="formulario">
    <center>
        <br/>
        <h1>Editar - Servicios por Pagar</h1>
        <table class="texto">
            <input type="hidden" id="codigo" value="<?=$form_codigo?>">
            <tr>
                <th>Marca</th>
                <th>Observaciones</th>
                <th>Fecha Documento Marca</th>
            </tr>
            <tr>
                <td>
                    <select name="cod_proveedor" id="cod_proveedor">
                        <?php 
                        $consulta="SELECT p.cod_proveedor, p.nombre_proveedor 
                                    FROM proveedores p 
                                    ORDER BY p.cod_proveedor DESC";
                        $rs = mysqli_query($enlaceCon,$consulta);
                        while($reg=mysqli_fetch_array($rs)){
                            $codigo = $reg["cod_proveedor"];
                            $nombre = $reg["nombre_proveedor"];
                        
                        ?>
                            <option value="<?=$codigo?>" <?= $form_codigo == $codigo ? 'selected' : ''?>><?=$nombre?></option>
                        <?php 
                        }
                        ?>
                    </select>
                </td>
                <td><input type="text" id="observaciones" value="<?=$form_observaciones?>" /></td>
                <td><input type="date" id="fecha_factura_proveedor" value="<?=$form_fecha_factura_proveedor?>" /></td>
            </tr>
            <tr>
                <th>Nro. Documento</th>
                <th>Monto Orden</th>
                <th>Días Crédito</th>
            </tr>
            <tr>
                <td><input type="number" id="nro_documento" value="<?=$form_nro_documento?>" /></td>
                <td><input type="number" id="monto_orden" value="<?=$form_monto_orden?>" step="0.02"/></td>
                <td><input type="number" id="dias_credito" value="<?=$form_dias_credito?>" /></td>
            </tr>
        </table>
    </center>
    <div class="divBotones">
        <input class="btn-primario" type="button" value="Actualizar" id="actualizar_form"/>
        <input class="btn-danger" type="button" value="Cancelar" id="lista"/>
    </div>
</div>
<script>
    // Volver a la lista de registros
    $("#lista").click(function() {
        window.location.href = "rpt_op_gestion_serv.php";
    });
    // Registro de datos
    $("#actualizar_form").click(function () {
        var formData = new FormData();
        formData.append("codigo", $("#codigo").val());
        formData.append("cod_proveedor", $("#cod_proveedor").val());
        formData.append("observaciones", $("#observaciones").val());
        formData.append("fecha_factura_proveedor", $("#fecha_factura_proveedor").val());
        formData.append("nro_documento", $("#nro_documento").val());
        formData.append("monto_orden", $("#monto_orden").val());
        formData.append("dias_credito", $("#dias_credito").val());
        
        $.ajax({
            type: "POST",
            url: "rpt_op_gestion_serv_actualizar.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    window.location.href = "rpt_op_gestion_serv.php";
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("Error en la solicitud AJAX");
            }
        });
    });
</script>