<?php
    /**
     * VERIFICAR DEUDA DE CLIENTE
     */
    require("conexion.inc");
    ob_clean();
    date_default_timezone_set('America/La_Paz');

    try{
        $cod_cliente = $_POST['cod_cliente'];
        $sql = "SELECT COALESCE(
                (SELECT DATEDIFF(CURDATE(), DATE_ADD(sa.fecha, INTERVAL c.dias_credito DAY)) > 0 AS expirado
                FROM salida_almacenes sa
                LEFT JOIN clientes c ON c.cod_cliente = sa.cod_cliente
                WHERE sa.cod_cliente = '$cod_cliente'
                AND sa.cod_tipopago = 4
                    HAVING expirado = 1
                    LIMIT 1),
                0
            ) AS credito_expirado";
        //echo $sql;
        $resp             = mysqli_query($enlaceCon,$sql);
        $registro         = mysqli_fetch_assoc($resp);
        $credito_expirado = $registro['credito_expirado'];

        echo json_encode(array(
            'status'  => $credito_expirado ? true : false,
            'message' => $credito_expirado ? 'El cliente tiene deuda pendiente' : 'El cliente no tiene deuda pendiente'
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'status'  => false,
            'message' => 'Error: '.$e->getMessage()
        ));
    }
    exit;