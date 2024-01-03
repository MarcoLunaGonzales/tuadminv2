<?php
    /**
     * VERIFICAR DEUDA DE CLIENTE
     */
    require("conexion.inc");
    ob_clean();
    date_default_timezone_set('America/La_Paz');

    try{
        // Modelo de Vehiculo
        $data_modelo = [];
        $sql = "SELECT sa.modelo_vehiculo
                FROM salida_almacenes sa
                GROUP BY sa.modelo_vehiculo";
        $resp             = mysqli_query($enlaceCon,$sql);
        while ($registro = mysqli_fetch_assoc($resp)) {
            $data_modelo[] = $registro['modelo_vehiculo'];
        }
        // Placa de Vehiculo
        $data_placa = [];
        $sql = "SELECT sa.placa_vehiculo
                FROM salida_almacenes sa
                GROUP BY sa.placa_vehiculo";
        $resp             = mysqli_query($enlaceCon,$sql);
        while ($registro = mysqli_fetch_assoc($resp)) {
            $data_placa[] = $registro['placa_vehiculo'];
        }
        // Conductor de Vehiculo
        $data_conductor = [];
        $sql = "SELECT sa.conductor_vehiculo
                FROM salida_almacenes sa
                GROUP BY sa.conductor_vehiculo";
        $resp             = mysqli_query($enlaceCon,$sql);
        while ($registro = mysqli_fetch_assoc($resp)) {
            $data_conductor[] = $registro['conductor_vehiculo'];
        }

        echo json_encode(array(
            'status'         => true,
            'message'        => 'Lista de campos autocompletados',
            'data_modelo'    => $data_modelo,
            'data_placa'     => $data_placa,
            'data_conductor' => $data_conductor
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'status'  => false,
            'message' => 'Error: '.$e->getMessage()
        ));
    }
    exit;