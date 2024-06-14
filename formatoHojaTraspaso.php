
<?php

    date_default_timezone_set('America/La_Paz');


    require('fpdf.php');
    require('conexionmysqlipdf.inc');
    require('funciones.php');
    require('NumeroALetras.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $cod_salida_almacen = empty($_GET["cod_salida_almacen"]) ? '' : $_GET["cod_salida_almacen"];
    
    /***********************************************************************************/
    /*                          Datos Generales de la Factura                          */
    /***********************************************************************************/
    $sqlSalida = "SELECT sa.nit, 
                    sa.razon_social,
                    sa.observaciones, 
                    sa.nro_correlativo, 
                    tv.nombre_tipoventa as tipo_venta,
                    tp.nombre_tipopago as tipo_pago,
                    CONCAT(cli.nombre_cliente, ' ', cli.paterno) as cliente,
                    cli.dir_cliente,
                    CONCAT(f.nombres, ' ', f.paterno, ' ', f.materno) as funcionario,
                    sa.monto_total,
                    sa.descuento,
                    sa.monto_final,
                    CONCAT(DATE_FORMAT(sa.fecha, '%d-%m-%Y'), ' ', DATE_FORMAT(sa.hora_salida, '%r')) as fecha,
                    CONCAT('[', orig.nombre_almacen, '] ', orig.direccion) as dir_origen,
                    CONCAT('[', dest.nombre_almacen, '] ', dest.direccion) as dir_destino,
                    chof.nombre as chof_nombre,
                    chof.nro_licencia as chof_nro_licencia,
                    chof.celular as chof_celular,
                    tra.nombre as tra_nombre,
                    veh.nombre as veh_descripcion,
                    veh.placa as veh_placa,
                    tt.nombre as tipotraspaso_nombre
                FROM salida_almacenes sa
                LEFT JOIN funcionarios f ON f.codigo_funcionario = sa.cod_chofer
                LEFT JOIN clientes cli ON cli.cod_cliente = sa.cod_cliente
                LEFT JOIN tipos_venta tv ON tv.cod_tipoventa = sa.cod_tipoventa
                LEFT JOIN tipos_pago tp ON tp.cod_tipopago = sa.cod_tipopago
                LEFT JOIN tipos_docs td ON td.codigo = sa.cod_tipo_doc
                LEFT JOIN almacenes orig ON orig.cod_almacen = sa.cod_almacen
                LEFT JOIN almacenes dest ON dest.cod_almacen = sa.almacen_destino
                LEFT JOIN transportistas chof ON chof.codigo = sa.cod_transportista
                LEFT JOIN transportadoras tra ON tra.codigo = sa.cod_transportadora
                LEFT JOIN vehiculos veh ON veh.codigo = sa.cod_vehiculo
                LEFT JOIN tipos_traspaso tt ON tt.cod_tipotraspaso = sa.cod_tipotraspaso
                WHERE sa.cod_salida_almacenes = '$cod_salida_almacen'
                LIMIT 1";
    // echo $sqlSalida;
    $respSalida=mysqli_query($enlaceCon, $sqlSalida);
    while($dataSalida = mysqli_fetch_array($respSalida)){
        $cab_nit             = $dataSalida['nit'];
        $cab_razon_social    = $dataSalida['razon_social'];
        $cab_observaciones   = $dataSalida['observaciones'];
        $cab_nro_correlativo = $dataSalida['nro_correlativo'];
        $cab_tipo_venta      = $dataSalida['tipo_venta'];
        $cab_tipo_pago       = $dataSalida['tipo_pago'];
        $cab_cliente         = $dataSalida['cliente'];
        $cab_dir_cliente     = $dataSalida['dir_cliente'];
        $cab_funcionario     = $dataSalida['funcionario'];
        $cab_monto_total     = $dataSalida['monto_total'];
        $cab_descuento       = $dataSalida['descuento'];
        $cab_fecha           = $dataSalida['fecha'];
        $cab_dir_origen      = $dataSalida['dir_origen'];
        $cab_dir_destino     = $dataSalida['dir_destino'];
        $cab_chof_nombre      = $dataSalida['chof_nombre'];
        $cab_chof_nro_licencia= $dataSalida['chof_nro_licencia'];
        $cab_chof_celular     = $dataSalida['chof_celular'];
        $cab_tra_nombre       = $dataSalida['tra_nombre'];
        $cab_nro_placa        = $dataSalida['veh_placa'];
        $cab_veh_descripcion  = $dataSalida['veh_descripcion'];
        $cab_tipotraspaso_nombre = $dataSalida['tipotraspaso_nombre'];
    }
    // Tamaño Carta
    // $pdf = new FPDF($orientation='P',$unit='mm', 'Letter');
    // Media Hoja Carta
    $pdf = new FPDF($orientation='P',$unit='mm', 'Letter');
    $pdf->AddPage();
    #Establecemos los márgenes izquierda, arriba y derecha:
    $pdf->SetMargins(10, 25 , 30);


    // Posición inicial EJE X
    $ejeX = 0;
    /************************************/
    /*              TITULO              */
    /************************************/
    $pdf->SetFont('Arial','B',13);    
    $textypos = 4;
    $pdf->setY(4);$pdf->setX($ejeX + 80);
    $pdf->Cell(5, $textypos, utf8_decode("GUÍA DE REMISIÓN"));
    
    // Cambiar el color del texto a rojo
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetFont('Arial','B',12);
    $pdf->setY(8);$pdf->setX($ejeX + 98);
    $pdf->Cell(5,$textypos,utf8_decode("N° $cab_nro_correlativo"),0,0,'C');
    // Restablecer el color del texto a negro
    $pdf->SetTextColor(0, 0, 0);


    // Imagenes
    $pdf->Image('assets/imagenes/pdf_img3.png', $ejeX + 145, 5, 25, 10);
    $pdf->Image('assets/imagenes/pdf_img4.png', $ejeX + 180, 5, 25, 10);
    $pdf->Image('assets/imagenes/pdf_img5.png', $ejeX + 145, 14, 25, 10);
    $pdf->Image('assets/imagenes/pdf_img6.png', $ejeX + 180, 14, 25, 10);
    $pdf->Image('assets/imagenes/pdf_img8.png', $ejeX + 40, 14, 25, 10);
    $pdf->Image('assets/imagenes/pdf_img9.png', $ejeX + 75, 14, 25, 10);
    $pdf->Image('assets/imagenes/pdf_img2.png', $ejeX + 110, 14, 25, 10);
    

    /************************************/
    /*              LOGO                */
    /************************************/
    $pdf->setY(35);$pdf->setX($ejeX + 3);
    // $pdf->Image('pruebaImg.jpg' , 10, 20, 20, 20,'JPG', 'http://www.desarrolloweb.com');

    /********************************************************/
    /*              Datos Generales de la Factura           */
    /********************************************************/
    // Titulos
    $pdf->Image('assets/imagenes/pdf_img1.png', $ejeX + 3, 8, 40, 15);
    
    // Información
    $pdf->SetFont('Arial','B',9);    
    $pdf->setY(25);$pdf->setX($ejeX + 3);
    $pdf->Cell(30,$textypos,utf8_decode("Fecha de Emisión:"), '', 0, 'L'); 
    $pdf->SetFont('Arial','',9);    
    $pdf->setY(25);$pdf->setX($ejeX + 33);
    $pdf->Cell(170,$textypos,utf8_decode($cab_fecha), '', 0, 'L');
    $pdf->Ln();


    $pdf->SetFont('Arial','B',9);  
    $pdf->setY(29);$pdf->setX($ejeX + 3);
    $pdf->Cell(48,$textypos,utf8_decode("Domicilio del Punto de Partida:"), '', 0, 'L');
    $pdf->SetFont('Arial','',9);  
    $pdf->setY(29);$pdf->setX($ejeX + 52);
    $pdf->Cell(150,$textypos,utf8_decode($cab_dir_origen), '', 0, 'L');
    $pdf->Ln();
    
    $pdf->SetFont('Arial','B',9);  
    $pdf->setY(33);$pdf->setX($ejeX + 3);
    $pdf->Cell(200,$textypos,utf8_decode("DATOS DEL DESTINARIO"), '', 0, 'C');
    $pdf->Ln();
    
    $pdf->SetFont('Arial','B',9);  
    $pdf->setY(36);$pdf->setX($ejeX + 3);
    $pdf->Cell(22,$textypos,utf8_decode("Razón Social:"), '', 0, 'L');
    $pdf->SetFont('Arial','',9);  
    $pdf->setY(36);$pdf->setX($ejeX + 25);
    $pdf->Cell(105,$textypos,utf8_decode("Razing Trade LTDA."), '', 0, 'L');
    $pdf->SetFont('Arial','B',9);  
    $pdf->setY(36);$pdf->setX($ejeX + 130);
    $pdf->Cell(10,$textypos,utf8_decode("NIT :"), '', 0, 'L');
    $pdf->SetFont('Arial','',9);  
    $pdf->setY(36);$pdf->setX($ejeX + 140);
    $pdf->Cell(43, $textypos, '345048025', '', 0, 'L');
    $pdf->Ln();
    
    $pdf->SetFont('Arial','B',9);  
    $pdf->setY(39.5);$pdf->setX($ejeX + 3);
    $pdf->Cell(50,$textypos,utf8_decode("Domicilio del Punto de Llegada:"), '', 0, 'L');
    $pdf->SetFont('Arial','',9);  
    $pdf->setY(39.5);$pdf->setX($ejeX + 52);
    $pdf->Cell(91,$textypos,utf8_decode($cab_dir_destino), '', 0, 'L');
    $pdf->Ln();
    
    $pdf->SetFont('Arial','B',9);  
    $pdf->setY(43);$pdf->setX($ejeX + 3);
    $pdf->Cell(42,$textypos,utf8_decode("Bienes Transportados: "), '', 0, 'L');
    $pdf->SetFont('Arial','',9);  
    $pdf->setY(43);$pdf->setX($ejeX + 40);
    $pdf->Cell(160,$textypos,utf8_decode("LLANTAS"), '', 0, 'L');
    $pdf->Ln();

    // DETALLE DE ITEMS
    $header = array("CANT.", "MARCA", "DESCRIPCION");
    // Column widths
    $w = array(10, 70, 110);
    $pdf->SetFont('Arial','B',8);    
    // Header
    $add_size = 5.5;
    $pdf->setX($ejeX + 3);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i] + $add_size, 5, utf8_decode($header[$i]), 'LTRB', 0, 'C');
        $pdf->Ln();
    // Data
    $total = 0;
    $pdf->SetFont('Arial','',6);

    /********************************************/
    /*              DETALLE DE SALIDA           */
    /********************************************/
    $sqlSalidaDet = "SELECT
                        m.codigo_material,
                        sda.orden_detalle,
                        m.descripcion_material,
                        sda.precio_unitario,
                        sum(sda.cantidad_unitaria)as cantidad_unitaria,
                        sum(sda.descuento_unitario)as descuento_unitario,
                        sum(sda.monto_unitario)as monto_unitario,
                        (select pl.nombre_linea_proveedor 
                        from proveedores p, proveedores_lineas pl 
                        where p.cod_proveedor=pl.cod_proveedor 
                        and pl.cod_linea_proveedor=m.cod_linea_proveedor) as marca
                    FROM salida_detalle_almacenes sda
                    LEFT JOIN material_apoyo m ON m.codigo_material = sda.cod_material
                    WHERE m.codigo_material = sda.cod_material 
                    AND sda.cod_salida_almacen = '$cod_salida_almacen'
                    GROUP BY m.codigo_material
                    ORDER BY sda.orden_detalle DESC";
    // echo $sqlDatosVenta;
    $respSalidaDet = mysqli_query($enlaceCon, $sqlSalidaDet);
    $totalCantidad = 0;
    while($dataSalidaDet = mysqli_fetch_array($respSalidaDet)){
        $codigo_material      = $dataSalidaDet['codigo_material'];
        $orden_detalle        = $dataSalidaDet['orden_detalle'];
        $descripcion_material = $dataSalidaDet['descripcion_material'];
        $precio_unitario      = $dataSalidaDet['precio_unitario'];
        $cantidad_unitaria    = $dataSalidaDet['cantidad_unitaria'];
        $descuento_unitario   = $dataSalidaDet['descuento_unitario'];
        $monto_unitario       = $dataSalidaDet['monto_unitario'];
        $marca                = $dataSalidaDet['marca'];

        $montoCalculadoProducto=($cantidad_unitaria * $precio_unitario) - $descuento_unitario;
        
        $row_index = 1;

        $pdf->setX($ejeX + 3);
        
        $y = $pdf->getY();
        $x = $pdf->GetX();

        // * CANTIDAD
        $pdf->SetFont('Arial','',8);
        $cantidad_unitaria = intval($cantidad_unitaria);
        $pdf->multiCell(15.5, 3.5 * $row_index, $cantidad_unitaria, 1, 'B', false);
        $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
        $pdf->SetY($y); // regresar a fila anterior
        $pdf->setX($x + 15.5); // regresar a columna anterior mas espacio de la columna
        // * MARCA
        $pdf->SetFont('Arial','',8);
        $y = $pdf->getY();
        $x = $pdf->GetX();
        $pdf->multiCell(75.5, 3.5 * $row_index, utf8_decode($marca), 1, 'B', false);
        $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
        $pdf->SetY($y); // regresar a fila anterior
        $pdf->setX($x + 75.5); // regresar a columna anterior mas espacio de la columna
        // * DESCRIPCIÓN
        $pdf->SetFont('Arial','',8);
        $y = $pdf->getY();
        $x = $pdf->GetX();
        $nombreProductoX = strtoupper( substr($descripcion_material,0,40) );
        $pdf->multiCell(115.5, 3.5 * $row_index, utf8_decode($nombreProductoX), 1, 'L', false);
        // $pdf->multiCell(13, 3.5 * $row_index, utf8_decode(number_format($precio_unitario, 2, ".",",")), 1, 'R');

        // $pdf->Ln();

        $totalCantidad += $cantidad_unitaria;
    }

    /****************************************/
    /*              PIE DE PAGINA           */
    /****************************************/
    $pdf->setY($y + 3.5);
    $pdf->setX($ejeX + 3);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(15.5, 4, utf8_decode($totalCantidad), 1, 0, 'L');
    $pdf->Cell(191, 4, utf8_decode('TOTAL'), 1, 0, 'L');
    $pdf->Ln();
    // DETALLE DE MOTIVO DE TRASLADO
    $pdf->setY($y + 8);
    $pdf->setX($ejeX + 3);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(35, 4, utf8_decode('Motivo de Traslado:'), 'LB', 0, 'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(171.5, 4, utf8_decode($cab_tipotraspaso_nombre), 'BR', 0, 'L');
    $pdf->Ln();
    // DATOS TRANSPORTISTA
    $y = $pdf->getY();
    $pdf->SetFont('Arial','B',9);  
    $pdf->setX($ejeX + 3);
    $pdf->Cell(103, $textypos, utf8_decode("DATOS DEL TRANSPORTISTA"), '1', 0, 'C');
    $pdf->Cell(103.5, $textypos, utf8_decode("UNIDAD DE TRANSPORTE Y CONDUCTOR (ES)"), '1', 0, 'C');
    $pdf->Ln();
    
    $y = $pdf->getY();
    $pdf->SetFont('Arial','B',8);  
    $pdf->setX($ejeX + 3);
    $pdf->Cell(25,$textypos,utf8_decode("Nombre Chofer:"), 'LB', 0, 'L');
    $pdf->SetFont('Arial','',8);  
    $pdf->Cell(78,$textypos,utf8_decode($cab_chof_nombre), 'BR', 0, 'L');
    $pdf->SetFont('Arial','B',8);  
    $pdf->Cell(25,$textypos,utf8_decode("Transportadora:"), 'LB', 0, 'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(78.5, $textypos, utf8_decode($cab_tra_nombre), 'BR', 0, 'L');
    $pdf->Ln();
    
    $y = $pdf->getY();
    $pdf->SetFont('Arial','B',8);
    $pdf->setX($ejeX + 3);
    $pdf->Cell(25,$textypos,utf8_decode("Numero de Placa:"), 'LB', 0, 'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(78,$textypos,utf8_decode($cab_nro_placa), 'BR', 0, 'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(15,$textypos,utf8_decode("Celular:"), 'LB', 0, 'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(88.5, $textypos, utf8_decode($cab_chof_celular), 'BR', 0, 'L');
    $pdf->Ln();
    
    $y = $pdf->getY();
    $pdf->SetFont('Arial','B',8);
    $pdf->setX($ejeX + 3);
    $pdf->Cell(35,$textypos,utf8_decode("N° Licencia de Conducir:"), 'LBR', 0, 'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(68,$textypos,utf8_decode($cab_chof_nro_licencia), 'LBR', 0, 'L');
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,$textypos,utf8_decode("Marca Unidad de Tansporte:"), 'LBR', 0, 'L');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(63.5, $textypos, utf8_decode($cab_veh_descripcion), 'LBR', 0, 'L');
    $pdf->Ln();

    
    /*********************************************************/
    /*        "ENTREGUE CONFORME" y "RECIBÍ CONFORME"        */
    /*********************************************************/
    $y = $pdf->getY();
    $pdf->setY($y + 10);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->setX($ejeX + 40);
    $pdf->Cell(50, 3, utf8_decode("ENTREGUE CONFORME"), 0, 0, 'L');
    $pdf->setX($ejeX + 40);
    $pdf->Cell(35, 0, '', 'T');

    $pdf->setX($ejeX + 130);
    $pdf->Cell(50, 3, utf8_decode("RECIBÍ CONFORME"), 0, 0, 'L');
    $pdf->setX($ejeX + 128);
    $pdf->Cell(35, 0, '', 'T');
    
    $ejeX += 140;
    

    // Limpiar el buffer de salida antes de generar el PDF
    ob_end_clean();
    $fecha = date('d-m-Y').'_cotizacion.pdf';
    $pdf->Output($fecha, "I");
?>
