<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contrato</title>
    <link rel="stylesheet" href="stylee.css">
    <style>
        @import url('fonts/BrixSansRegular.css');
        @import url('fonts/BrixSansBlack.css');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        p,
        label,
        span,
        table {
            font-family: 'BrixSansRegular';
            font-size: 9pt;
        }

        .h2 {
            font-family: 'BrixSansBlack';
            font-size: 16pt;
        }

        .h3 {
            font-family: 'BrixSansBlack';
            font-size: 12pt;
            display: block;
            background: #0a4661;
            color: #FFF;
            text-align: center;
            padding: 3px;
            margin-bottom: 5px;
        }

        #page_pdf {
            width: 95%;
            margin: 15px auto 10px auto;
        }

        #factura_head,
        #factura_cliente,
        #factura_detalle {
            width: 100%;
            margin-bottom: 10px;
        }

        /* .logo_factura{
        width: 25%;

    } */

        .logo_factura {
            width: 25%;

        }

        .info_empresa {
            width: 50%;
            text-align: center;
        }

        .info_factura {
            width: 25%;
        }

        .info_cliente {
            width: 100%;
        }

        .datos_cliente {
            width: 100%;
        }

        .datos_cliente tr td {
            width: 50%;
        }

        .datos_cliente {
            padding: 10px 10px 0 10px;
        }

        .datos_cliente label {
            width: 75px;
            display: inline-block;
        }

        .datos_cliente p {
            display: inline-block;
        }

        .textright {
            text-align: right;
        }

        .textleft {
            text-align: left;
        }

        .textcenter {
            text-align: center;
        }

        .round {
            border-radius: 10px;
            border: 1px solid #0a4661;
            overflow: hidden;
            padding-bottom: 15px;
        }

        .round p {
            padding: 0 15px;
        }

        #factura_detalle {
            border-collapse: collapse;
        }

        #factura_detalle thead th {
            background: #70bead;
            color: #FFF;
            padding: 5px;
        }

        #detalle_productos tr:nth-child(even) {
            background: #ededed;
        }

        #detalle_totales span {
            font-family: 'BrixSansBlack';
        }

        .nota {
            font-size: 8pt;
        }

        .label_gracias {
            font-family: verdana;
            font-weight: bold;
            font-style: italic;
            text-align: center;
            margin-top: 20px;
        }

        .anulada {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
        }
    </style>
</head>

<body>
    <div id="page_pdf">
        <table id="factura_head">
            <!-- <td class="logo_factura"> -->
            <div>
                <img src="LogoCoordenadas.png" />
            </div>
            <!-- </td> -->
            <tr>
                <td class="info_empresa">

                    <div>
                        <span class="h2">
                            <?php echo strtoupper($configuracion['nombre']); ?>
                        </span>
                        <p>
                            <?php echo $configuracion['razon_social']; ?>
                        </p>
                        <p>
                            <?php echo $configuracion['direccion']; ?>
                        </p>
                        <p>Ruc:
                            <?php echo $configuracion['nit']; ?>
                        </p>
                        <p>Teléfono:
                            <?php echo $configuracion['telefono']; ?>
                        </p>
                        <p>Email:
                            <?php echo $configuracion['email']; ?>
                        </p>
                    </div>

                </td>
                <td class="info_factura">
                    <div class="round">
                        <span class="h3">DETALLES CLIENTE</span>
                        <p>CLIENTE: <strong>
                                <?php echo $cliente['nombre']; ?>
                            </strong></p>
                        <p>CEDULA o RUC:
                            <?php echo $cliente['cedula']; ?>
                        </p>
                        <p>DIRECCION:
                            <?php echo $cliente['direccion']; ?>
                        </p>
                        <p>CELULAR:
                            <?php echo $cliente['telefono']; ?>
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <div class="round">
            <span class="h3">Lista Contratos</span>
            <table class="datos_cliente">
                <thead>
                    <tr>
                        <th>Contrato ID</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Número de GPS</th>
                        <th>Clave</th>
                        <th>Total de Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($clientequery)) {
                        echo "<tr>";
                        echo "<td>" . $row['id_contrato'] . "</td>";
                        echo "<td>" . $row['fecha_inicio'] . "</td>";
                        echo "<td>" . $row['fecha_fin'] . "</td>";
                        echo "<td>" . $row['estado'] . "</td>";
                        echo "<td>" . $row['placa'] . "</td>";
                        echo "<td>" . $row['modelo'] . "</td>";
                        echo "<td>" . $row['num_gps'] . "</td>";
                        echo "<td>" . $row['clave'] . "</td>";
                        echo "<td>" . $row['total_items'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div>
                <p class="nota">Si usted tiene preguntas sobre este contrato, <br>pongase en contacto con nombre,
                    teléfono y
                    Email</p>
                <h4 class="label_gracias">¡Gracias por su compra!</h4>
            </div>

        </div>

</body>

</html>