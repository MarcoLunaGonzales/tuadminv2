
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
                    CONCAT(DATE_FORMAT(sa.fecha, '%d-%m-%Y'), ' ', DATE_FORMAT(sa.hora_salida, '%r')) as fecha
                FROM salida_almacenes sa
                LEFT JOIN funcionarios f ON f.codigo_funcionario = sa.cod_chofer
                LEFT JOIN clientes cli ON cli.cod_cliente = sa.cod_cliente
                LEFT JOIN tipos_venta tv ON tv.cod_tipoventa = sa.cod_tipoventa
                LEFT JOIN tipos_pago tp ON tp.cod_tipopago = sa.cod_tipopago
                LEFT JOIN tipos_docs td ON td.codigo = sa.cod_tipo_doc
                WHERE sa.cod_salida_almacenes = '$cod_salida_almacen'
                LIMIT 1";
    // echo $sqlDatosVenta;
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
    }
    // Media Hoja Oficio
    $pdf = new FPDF($orientation='L', 'mm', array(330, 216));
    $pdf->AddPage();
    #Establecemos los márgenes izquierda, arriba y derecha:
    $pdf->SetMargins(10, 25 , 30);


    // Posición inicial EJE X
    $ejeX = 3;
    for ($col = 1; $col <= 3; $col++) {
        /************************************/
        /*              TITULO              */
        /************************************/
        $pdf->SetFont('Arial','B',13);    
        $textypos = 5;
        $pdf->setY(5);$pdf->setX($ejeX + 35);
        $pdf->Cell(5, $textypos, utf8_decode("NOTA DE VENTA"));
        
        // Cambiar el color del texto a rojo
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetFont('Arial','B',12);
        $pdf->setY(10);$pdf->setX($ejeX + 52);
        $pdf->Cell(5,$textypos,utf8_decode("N° $cab_nro_correlativo"),0,0,'C');
        // Restablecer el color del texto a negro
        $pdf->SetTextColor(0, 0, 0);


        // Imagenes
        $pdf->Image('assets/imagenes/pdf_img3.png', $ejeX + 63, 9, 19, 7);
        $pdf->Image('assets/imagenes/pdf_img4.png', $ejeX + 84, 9, 19, 7);
        $pdf->Image('assets/imagenes/pdf_img5.png', $ejeX + 63, 15, 19, 7);
        $pdf->Image('assets/imagenes/pdf_img6.png', $ejeX + 84, 15, 19, 7);
        $pdf->Image('assets/imagenes/pdf_img8.png', $ejeX + 42, 22, 19, 7);
        $pdf->Image('assets/imagenes/pdf_img9.png', $ejeX + 84, 22, 19, 7);
        $pdf->Image('assets/imagenes/pdf_img2.png', $ejeX + 63, 22, 19, 7);
        

        /************************************/
        /*              LOGO                */
        /************************************/
        $pdf->setY(35);$pdf->setX($ejeX + 5);
        // $pdf->Image('pruebaImg.jpg' , 10, 20, 20, 20,'JPG', 'http://www.desarrolloweb.com');

        /********************************************************/
        /*              Datos Generales de la Factura           */
        /********************************************************/
        // Titulos
        $pdf->Image('assets/imagenes/pdf_img1.png', $ejeX + 3, 8, 26, 8);
        $pdf->SetFont('Arial','B',7);    
        $pdf->setY(15); $pdf->setX($ejeX + 3);
        $pdf->Cell(5, $textypos, utf8_decode("Av. 6 de Marzo Nro. 250")); 
        $pdf->setY(18); $pdf->setX($ejeX + 3);
        $pdf->Cell(5, $textypos,utf8_decode("Cel.: 74276636 - 75800783"));
        $pdf->setY(21); $pdf->setX($ejeX + 3);
        $pdf->Cell(5, $textypos,utf8_decode("El Alto, La Paz, Bolivia"));
        // Información
        $pdf->SetFont('Arial','B',7);    
        $pdf->setY(25);$pdf->setX($ejeX + 3);
        $pdf->Cell(20,$textypos,utf8_decode("Fecha:"), '', 0, 'L');
        $pdf->SetFont('Arial','',7);    
        $pdf->setY(25);$pdf->setX($ejeX + 12);
        $pdf->Cell(55,$textypos,utf8_decode($cab_fecha), '', 0, 'L');
        
        // Información
        $pdf->SetFont('Arial','B',7);    
        $pdf->setY(30);$pdf->setX($ejeX + 3);
        $pdf->Cell(13,$textypos,utf8_decode("Señor(es):"), 'TBL', 0, 'L'); 
        $pdf->setY(35);$pdf->setX($ejeX + 3);
        $pdf->Cell(13,$textypos,utf8_decode("Vendedor:"), 'TBL', 0, 'L');

        $pdf->SetFont('Arial','',7);    
        $pdf->setY(30);$pdf->setX($ejeX + 16);
        $pdf->Cell(45,$textypos,utf8_decode($cab_cliente), 'TBR', 1, 'L');
        $pdf->setY(35);$pdf->setX($ejeX + 16);
        $pdf->Cell(45,$textypos,utf8_decode($cab_funcionario), 'TBR', 1, 'L');
        // $pdf->MultiCell(40, 3, utf8_decode($cab_funcionario), 0, 'L');
        
        $pdf->SetFont('Arial','B',7);    
        $pdf->setY(30);$pdf->setX($ejeX + 61);
        $pdf->Cell(15,$textypos,utf8_decode("Dirección:"), 'TBL', 0, 'L');
        $pdf->setY(35);$pdf->setX($ejeX + 61);
        $pdf->Cell(15,$textypos,utf8_decode("Tipo Venta :"), 'TBL', 0, 'L');
        
        $pdf->SetFont('Arial','',6);    
        $pdf->setY(30); $pdf->setX($ejeX + 76);
        $pdf->Cell(30, $textypos, $cab_dir_cliente, 'TBR', 0, 'L');
        // $pdf->MultiCell(27.5, 1, utf8_decode($cab_dir_cliente), 0, 'L');
        $pdf->setY(35);$pdf->setX($ejeX + 76);
        $pdf->Cell(30, $textypos, $cab_tipo_venta, 'TBR', 0, 'L');
        $pdf->Ln();

        // DETALLE DE ITEMS
        $header = array("CANT", "DETALLE", "P.U.", "SUB-TOTAL");
        // Column widths
        $w = array(5, 60, 8, 8);
        $pdf->SetFont('Arial','B',6);    
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
                            sum(sda.monto_unitario)as monto_unitario 
                        FROM salida_detalle_almacenes sda
                        LEFT JOIN material_apoyo m ON m.codigo_material = sda.cod_material
                        WHERE m.codigo_material = sda.cod_material 
                        AND sda.cod_salida_almacen = '$cod_salida_almacen'
                        GROUP BY m.codigo_material, sda.precio_unitario
                        ORDER BY sda.orden_detalle DESC";
        // echo $sqlDatosVenta;
        $respSalidaDet = mysqli_query($enlaceCon, $sqlSalidaDet);
        $montoTotal = 0;
        while($dataSalidaDet = mysqli_fetch_array($respSalidaDet)){
            $codigo_material      = $dataSalidaDet['codigo_material'];
            $orden_detalle        = $dataSalidaDet['orden_detalle'];
            $descripcion_material = $dataSalidaDet['descripcion_material'];
            $precio_unitario      = $dataSalidaDet['precio_unitario'];
            $cantidad_unitaria    = $dataSalidaDet['cantidad_unitaria'];
            $descuento_unitario   = $dataSalidaDet['descuento_unitario'];
            $monto_unitario       = $dataSalidaDet['monto_unitario'];

            $montoCalculadoProducto=($cantidad_unitaria * $precio_unitario) - $descuento_unitario;
            
            $row_index = 1;

            $pdf->setX($ejeX + 3);
            
            $y = $pdf->getY();
            $x = $pdf->GetX();

            // * DESCRIPCIÓN
            $nombreProductoX = strtoupper( $descripcion_material );
            // Determinar el tamaño de la fuente en función de la longitud del texto
            if (strlen($nombreProductoX) > 50) {
                $fontSize = 5;
            } else {
                $fontSize = 6;
            }
            $pdf->SetY($y);
            $pdf->setX($ejeX + 13.5);
            $pdf->SetFont('Arial', '', $fontSize);
            // Medir la altura necesaria para la celda de descripción
            $start_y = $pdf->GetY();
            $pdf->multiCell(65.5, 3.5 * $row_index, utf8_decode($nombreProductoX), 1, 'LTRB', false);
            $end_y = $pdf->GetY();
            $descripcion_height = $end_y - $start_y;
            $max_y = max($max_y, $end_y);

            // * CANTIDAD
            $pdf->SetY($y);
            $pdf->setX($x);
            $pdf->SetFont('Arial','',6.5);
            $cantidad_unitaria = intval($cantidad_unitaria);
            $pdf->multiCell(10.5, $descripcion_height / $row_index, $cantidad_unitaria, 'LTRB', false);
            $max_y = $pdf->getY();
        
            // * PRECIO UNITARIO
            $pdf->SetY($y);
            $pdf->setX($ejeX + 79);
            $pdf->SetFont('Arial', '', 7);
            $pdf->multiCell(13.5, $descripcion_height / $row_index, utf8_decode(number_format($precio_unitario, 2, ".", ",")), 1, 'LTRB');
            $max_y = max($max_y, $pdf->getY());
        
            // * SUBTOTAL
            $pdf->SetXY($x + 89.5, $y);
            $pdf->SetFont('Arial', '', 7);
            $pdf->multiCell(13.5, $descripcion_height / $row_index, utf8_decode(number_format($montoCalculadoProducto, 2, ".", ",")), 1, 'LTRB');
            $max_y = max($max_y, $pdf->getY());

            // Ajustar la posición para la siguiente fila
            $pdf->SetY($max_y);
            // $pdf->Ln();

            $montoTotal += $montoCalculadoProducto;
        }

        /****************************************/
        /*              PIE DE PAGINA           */
        /****************************************/
        $y = $pdf->getY();
        $montoFinal=number_format($montoTotal,2,'.','');
        $arrayDecimal=explode('.', $montoFinal);
        if(count($arrayDecimal)>1){
            list($montoEntero, $montoDecimal) = explode('.', $montoFinal);
        }else{
            list($montoEntero,$montoDecimal)=array($montoFinal,0);
        }

        if($montoDecimal==""){
            $montoDecimal="00";
        }
        $txtMonto=NumeroALetras::convertir($montoEntero);
        $pdf->setY($y);
        $pdf->SetFont('Arial','B',6);  
        $pdf->setX($ejeX + 3);
        $pdf->MultiCell(65.5, 3, utf8_decode("Son:".$txtMonto." ".$montoDecimal."/100 Bolivianos"), 1, 'L');
        // $pdf->Cell(62.5, 6, ("Son:".$txtMonto." ".$montoDecimal."/100 Bolivianos"), 1, 0, 'L');

        
        $pdf->setY($y);
        $pdf->setX($ejeX + 68.5);
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(24, 6, utf8_decode("SubTotal Bs:"), 1, 0, 'R');
        $pdf->Cell(13.5, 6, number_format($montoTotal, 2, ".",","), 1, 0, 'R');
        $pdf->Ln();

        
        /*********************************************************/
        /*        "ENTREGUE CONFORME" y "RECIBÍ CONFORME"        */
        /*********************************************************/
        $y = $pdf->getY();
        $pdf->setY($y + 15);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->setX($ejeX + 10);
        $pdf->Cell(50, 10, utf8_decode("ENTREGUE CONFORME"), 0, 0, 'L');
        $pdf->setX($ejeX + 10);
        $pdf->Cell(35, 0, '', 'T');

        $pdf->setX($ejeX + 68);
        $pdf->Cell(50, 10, utf8_decode("RECIBÍ CONFORME"), 0, 0, 'L');
        $pdf->setX($ejeX + 65);
        $pdf->Cell(35, 0, '', 'T');
        
        $ejeX += 108;
    }

    

    // Limpiar el buffer de salida antes de generar el PDF
    ob_end_clean();
    $fecha = date('d-m-Y').'_cotizacion.pdf';
    $pdf->Output($fecha, "I");
?>
