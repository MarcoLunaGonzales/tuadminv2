<?php
    /**
     * VERIFICAR DEUDA DE CLIENTE
     */
    require("conexion.inc");
    ob_clean();
    date_default_timezone_set('America/La_Paz');

    try{
        $cod_cliente = $_POST['cod_cliente'];
        // $sql = "SELECT COALESCE(
        //         (SELECT DATEDIFF(CURDATE(), DATE_ADD(sa.fecha, INTERVAL c.dias_credito DAY)) > 0 AS expirado
        //         FROM salida_almacenes sa
        //         LEFT JOIN clientes c ON c.cod_cliente = sa.cod_cliente
        //         WHERE sa.cod_cliente = '$cod_cliente'
        //         AND sa.cod_tipopago = 4
        //             HAVING expirado = 1
        //             LIMIT 1),
        //         0
        //     ) AS credito_expirado";
        $sql = "SELECT COUNT(*) as credito_pendiente
                FROM salida_almacenes 
                WHERE cod_cliente = '$cod_cliente' 
                AND cod_tipoventa = 2 
                AND monto_cancelado < monto_final
                LIMIT 1";
        //echo $sql;
        $resp              = mysqli_query($enlaceCon,$sql);
        $registro          = mysqli_fetch_assoc($resp);
        $credito_pendiente = $registro['credito_pendiente'];

        echo json_encode(array(
            'status'  => $credito_pendiente > 0 ? true : false,
            'message' => $credito_pendiente > 0 ? 'El cliente tiene deuda una pendiente' : 'El cliente NO tiene deuda pendiente'
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'status'  => false,
            'message' => 'Error: '.$e->getMessage()
        ));
    }
    exit;