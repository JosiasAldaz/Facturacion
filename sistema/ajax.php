<?php

include "../conexion.php";
session_start();

//  print_r($_POST);
//  exit;
if (!empty($_POST)) {
    // Entrar datos 
    if ($_POST['action'] == 'infoProducto') {
        $producto_id = $_POST['producto'];
        $query = mysqli_query($conection, "SELECT codproducto, descripcion, existencia, precio
            FROM producto WHERE codproducto = $producto_id AND estatus= 1");

        mysqli_close($conection);
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }

    if ($_POST['action'] == 'addProduct') {
        if (
            !empty($_POST['cantidad']) && is_numeric($_POST['cantidad']) &&
            !empty($_POST['precio']) && is_numeric($_POST['precio']) &&
            !empty($_POST['producto_id']) && is_numeric($_POST['producto_id'])
        ) {

            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $producto_id = $_POST['producto_id'];
            $usuario_id = $_SESSION['idUser'];

            $query_insert = mysqli_query($conection, "INSERT INTO entradas (codproducto, cantidad, precio, usuario_id) 
                                                          VALUES ('$producto_id', '$cantidad', '$precio', '$usuario_id')");

            if ($query_insert) {
                $query_upd = mysqli_query($conection, "CALL actualizar_precio_producto($cantidad, $precio, $producto_id)");
                $result_pro = mysqli_num_rows($query_upd);
                if ($result_pro > 0) {
                    $data = mysqli_fetch_assoc($query_upd);
                    $data['producto_id'] = $producto_id;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }
    }
    // Eliminar Producto
    if ($_POST['action'] == 'delProduct') {
        if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
            echo "error";
        } else {
            $idproducto = $_POST['producto_id'];

            $query_delete = mysqli_query($conection, "UPDATE producto SET estatus = 0 WHERE  codproducto   = $idproducto");

            mysqli_close($conection);

            if ($query_delete) {
                echo "ok";
            } else {
                echo "Error al eliminar";
            }
        }
        echo "error";
        exit;

    }


    //listar cliente
    if ($_POST['action'] == 'ver_detalle') {
        if (!empty($_POST['cliente'])) {
            $nit = $_POST['cliente'];
            // Justo antes de la ejecución de la consulta


            /*$query = mysqli_query($conection, "SELECT 
                cont.id_contrato, cont.fecha_inicio, cont.fecha_fin, cont.estado, cont.placa, v.modelo, v.num_gps, cont.clave
            FROM contrato cont
            JOIN vehiculo v ON cont.placa = v.placa
            WHERE cont.cedula = '$nit'
            ");*/

            $query = mysqli_query($conection, "SELECT 
            cont.id_contrato, cont.fecha_inicio, cont.fecha_fin, cont.estado, cont.placa,v.modelo,v.num_gps, cont.clave,
            COUNT(*) AS total_items
        FROM contrato cont
        JOIN 
        vehiculo v ON cont.placa = v.placa
        LEFT JOIN 
        item_contrato i ON cont.id_contrato = i.id_contrato
        WHERE cont.cedula = '$nit'
        GROUP BY cont.id_contrato, cont.fecha_inicio, cont.fecha_fin, cont.estado, cont.placa, v.modelo, v.num_gps, cont.clave");



            //mysqli_close($conection);

            $data = array();

            // Verificar si hay resultados antes de intentar construir el array
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $data[] = $row;
                }
            }

            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        exit;
    }


    //Buscar Cliente
    if ($_POST['action'] == 'searchCliente') {
        if (!empty($_POST['cliente'])) {

            $nit = $_POST['cliente'];
            $query = mysqli_query($conection, "SELECT * FROM cliente WHERE cedula LIKE '$nit'
                and  estatus= 1");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        exit;
    }
    //Registrar Cliente
    if ($_POST['action'] == 'addCliente') {

        $nit = $_POST['nit_cliente'];
        $nombre = $_POST['nom_cliente'];
        $telefono = $_POST['tel_cliente'];
        $direccion = $_POST['dir_cliente'];
        $usuario_id = $_SESSION['idUser'];

        $query_insert = mysqli_query($conection, "INSERT INTO `cliente`(`cedula`, `nombre`, `telefono`, `direccion`, `usuario_id`) 
            VALUES ('$nit','$nombre','$telefono','$direccion','$usuario_id')");

        if ($query_insert) {
            $codCliente = mysqli_insert_id($conection);
            $msg = $codCliente;
        } else {
            $msg = 'Error';
        }
        mysqli_close($conection);
        echo $msg;
        exit;
    }

    // Eliminar Cliente
    if ($_POST['action'] == 'eliminarCliente') {
        if (!empty($_POST['idcliente']) && is_numeric($_POST['idcliente'])) {
            $idcliente = $_POST['idcliente'];

            // Realiza la consulta para desactivar el cliente
            $query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE idcliente = $idcliente");

            mysqli_close($conection);

            if ($query_delete) {
                echo "ok";
            } else {
                echo "Error al eliminar el cliente";
            }
            exit;
        } else {
            echo "error";
        }
        exit;
    }




    //Añadir un contrato
    if ($_POST['action'] == 'addContrato') {
        // Obtén los datos del formulario
        $cedula = $_POST['nit_cliente'];
        $atendido_por = $_SESSION['nombre'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $observaciones = $_POST['observacion'];
        $placa = $_POST['nit_placa'];
        $clave = $_POST['nit_codigo'];

        // Calcular el estado en función de las fechas
        $estado = '';
        $fecha_actual = date("Y-m-d");

        if ($fecha_inicio > $fecha_actual) {
            $estado = 'Pendiente';
        } elseif ($fecha_actual >= $fecha_inicio && $fecha_actual <= $fecha_fin) {
            $estado = 'Activo';
        } else {
            $estado = 'Caducado';
        }


        // Preparar la consulta SQL para insertar el contrato
        $sql = "INSERT INTO contrato (cedula, atendido_por, fecha_inicio, fecha_fin , estado, observaciones, placa, clave)
                    VALUES ('$cedula', '$atendido_por', '$fecha_inicio', '$fecha_fin', '$estado', '$observaciones', '$placa', '$clave')";

        if (mysqli_query($conection, $sql)) {
            echo "Contrato insertado correctamente.";
        } else {
            echo "Error al insertar el contrato: " . mysqli_error($conection);
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conection);
    }

    //Añadir un item de contrato
    if ($_POST['action'] == 'addItemContrato') {
        // Obtener los datos del formulario para el ítem de contrato
        $numero = $_POST['numItem'];
        $descripcion = $_POST['descripcion'];
        $cantidad = $_POST['cantidad'];

        // Obtener el ID del último contrato existente en la tabla contrato
        $sqlGetLastContractID = "SELECT MAX(id_contrato) AS last_contract_id FROM contrato";
        $result = mysqli_query($conection, $sqlGetLastContractID);
        $row = mysqli_fetch_assoc($result);
        $lastContractID = $row['last_contract_id'];

        // Preparar la consulta SQL para insertar el ítem de contrato utilizando el último contrato obtenido
        $sqlInsertItemContrato = "INSERT INTO item_contrato (numero, descripcion, cantidad, id_contrato)
                        VALUES ( '$numero', '$descripcion', '$cantidad', '$lastContractID')";

        // Ejecutar la consulta para insertar el ítem de contrato
        if (mysqli_query($conection, $sqlInsertItemContrato)) {
            echo "Ítem de contrato insertado correctamente.";
        } else {
            echo "Error al insertar el ítem de contrato: " . mysqli_error($conection);
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conection);
    }

    //Revisar codigo
    //?En proceso
    //Agregar producto al detalle temporal    
    if ($_POST['action'] == 'addProductoDetalle') {
        if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
            echo "error";
        } else {
            $codproducto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $token = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva from configuracion");
            $result_iva = mysqli_num_rows($query_iva);
            //*Resolver
            $query_detalle_temp = mysqli_query($conection, "CALL add_detalle_temp($codproducto, $cantidad, '$token')");
            $result = mysqli_num_rows($query_detalle_temp);
            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $total = round($total + $precioTotal, 2);
                    $detalleTabla .= '<tr>
                        <td>' . $data['codproducto'] . '</td>
                        <td colspan="2">' . $data['descripcion'] . '</td>
                        <td class="textcenter">' . $data['cantidad'] . '</td>
                        <td class="textcenter">' . $data['precio_venta'] . '</td>
                        <td class="textcenter">' . $precioTotal . '</td>
                        <td>
                            <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle(' . $data['correlativo'] . ');"><i
                                    class="far fa-trash-alt"></i></a>
                        </td>
                        </tr>';
                }

                $inpuesto = round($sub_total * ($iva / 100), 2);
                $tl_siva = round($sub_total - $inpuesto, 2);
                $total = round($tl_siva + $inpuesto, 2);
                $detalleTotales = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL $.</td>
                        <td class="textright">' . $tl_siva . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . $inpuesto . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL $.</td>
                        <td class="textright">' . $total . '</td>
                    </tr>';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }
    //Revisar codigo
//!Codigo no funcional
    //! Extrae datos del detalle_temp
    if ($_POST['action'] == 'serchForDetalle') {
        if (empty($_POST['user'])) {
            echo "error";
        } else {

            $token = md5($_SESSION['idUser']);

            $query = mysqli_query($conection, "SELECT tmp.correlativo, tmp.token_user,
                tmp.cantidad, tmp.precio_venta, p.codproducto,
                p.descripcion FROM detalle_temp tmp
                INNER JOIN producto p 
                ON tmp.codproducto = p.codproducto
                where token_user = '$token'");

            $result = mysqli_num_rows($query);


            $query_iva = mysqli_query($conection, "SELECT iva from configuracion");
            $result_iva = mysqli_num_rows($query_iva);
            //!Resolver
            //   $query_detalle_temp= mysqli_query($conection, "CALL add_detalle_temp($codproducto, $cantidad, '$token')");
            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query)) {
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $total = round($total + $precioTotal, 2);
                    $detalleTabla .= '
                        <tr>
                        <td>' . $data['codproducto'] . '</td>
                        <td colspan="2">' . $data['descripcion'] . '</td>
                        <td class="textcenter">' . $data['cantidad'] . '</td>       
                        <td class="textcenter">' . $data['precio_venta'] . '</td>
                        <td class="textcenter">' . $precioTotal . '</td>
                        <td>
                            <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle(' . $data['correlativo'] . ');"><i class="far fa-trash-alt"></i></a>
                        </td>
                        </tr>';
                }

                $inpuesto = round($sub_total * ($iva / 100), 2);
                $tl_siva = round($sub_total - $inpuesto, 2);
                $total = round($tl_siva + $inpuesto, 2);

                $detalleTotales = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL $.</td>
                        <td class="textright">' . $tl_siva . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . $inpuesto . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL $.</td>
                        <td class="textright">' . $total . '</td>
                    </tr>';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;

    }
    //Revisar codigo
    if ($_POST['action'] == 'del_product_detalle') {

        if (empty($_POST['id_detalle'])) {
            echo "error";
        } else {

            $id_detalle = $_POST['id_detalle'];
            $token = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva from configuracion");
            $result_iva = mysqli_num_rows($query_iva);
            $query_detalle_temp = mysqli_query($conection, "CALL del_detalle_temp($id_detalle,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $total = round($total + $precioTotal, 2);
                    $detalleTabla .= '
                        <tr>
                        <td>' . $data['codproducto'] . '</td>
                        <td colspan="2">' . $data['descripcion'] . '</td>
                        <td class="textcenter">' . $data['cantidad'] . '</td>
                        <td class="textcenter">' . $data['precio_venta'] . '</td>
                        <td class="textcenter">' . $precioTotal . '</td>
                        <td>
                            <a href="#" class="link_delete" onclick="event.preventDefault(); del_product_detalle(' . $data['correlativo'] . ');"><i class="far fa-trash-alt"></i></a>
                        </td>
                        </tr>';
                }

                $inpuesto = round($sub_total * ($iva / 100), 2);
                $tl_siva = round($sub_total - $inpuesto, 2);
                $total = round($tl_siva + $inpuesto, 2);

                $detalleTotales = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . $tl_siva . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . $inpuesto . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . $total . '</td>
                    </tr>';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }

    //Anular Venta
    if ($_POST['action'] == 'anularVenta') {
        $token = md5($_SESSION['idUser']);
        $query_del = mysqli_query($conection, "DELETE FROM detalle_temp WHERE token_user = '$token'");

        mysqli_close($conection);
        if ($query_del) {
            echo 'ok';
        } else {
            echo 'error';
        }
        exit;
    }

    //  Procesar Venta
    if ($_POST['action'] == 'procesarVenta') {
        $codcliente = $_POST['codcliente'];
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];
        $query = mysqli_query($conection, "SELECT * FROM detalle_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            try {
                
                var_dump("usuario".$usuario."codcliente".$codcliente."token".$token);
                exit;
                $query_procesar = mysqli_query($conection, "CALL procesar_venta($usuario,$codcliente,'$token')");

            } catch (\Throwable $th) {
                console . log("dd");
            }
            $result_detalle = mysqli_num_rows($query_procesar);
            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);


            }
        } else {
            echo "Error";
        }
        mysqli_close($conection);
        exit;

    }
    //Info Factura
    if ($_POST['action'] == 'infoFactura') {
        if (!empty($_POST['nofactura'])) {
            $nofactura = $_POST['nofactura'];
            $query = mysqli_query($conection, "SELECT * FROM factura WHERE nofactura='$nofactura' AND estatus=1");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;

            }
        }
        echo "Error";
        exit;
    }
    //*Agruege recien
    if ($_POST['action'] == 'anularFactura') {
        if (!empty($_POST['noFactura'])) {
            $noFactura = $_POST['noFactura'];
            $query_anular = mysqli_query($conection, "CALL anular_factura($noFactura)");

            if ($query_anular) {
                $data = mysqli_fetch_assoc($query_anular);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo "Error al anular la factura";
                exit;
            }
        } else {
            echo "No se ha recibido información para anular la factura";
            exit;
        }
    }
    //Vehiculos
    if (isset($_POST['action']) && $_POST['action'] == 'searchVehiculo') {
        if (!empty($_POST['vehiculo'])) {
            $nit = $_POST['vehiculo'];
            $query = mysqli_query($conection, "SELECT * FROM vehiculo WHERE placa LIKE '$nit'");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}
?>