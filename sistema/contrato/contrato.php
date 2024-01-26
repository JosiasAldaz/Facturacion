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
                        <span class="h3">Contrato</span>
                        <p>No. Contrato: <strong>
                                <?php echo $contrato['id_contrato']; ?>
                            </strong></p>
                        <p>Fecha Inicio:
                            <?php echo $contrato['fecha_inicio']; ?>
                        </p>
                        <p>Fecha Fin:
                            <?php echo $contrato['fecha_fin']; ?>
                        </p>
                        <p>Vendedor:
                            <?php echo $contrato['atendido_por']; ?>
                        </p>
                    </div>
                </td>
            </tr>
        </table>
        <table id="factura_cliente">
            <tr>
                <td class="info_cliente">
                    <div class="round">
                        <span class="h3">Contrato</span>
                        <table class="datos_cliente">
                            <tr>
                                <td><label>PLACA:</label>
                                    <p>
                                        <?php echo $contrato['placa']; ?>
                                    </p>
                                </td>

                                <td><label>CLAVE:</label>
                                    <p>
                                        <?php echo $contrato['clave']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td><label>NOMBRE:</label>
                                    <p>
                                        <?php echo $contrato['nombre_cliente']; ?>
                                    </p>
                                </td>
                                <td><label>DIRECCION:</label>
                                    <p>
                                        <?php echo $contrato['direccion_cliente']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td><label>CEDULA o RUC:</label>
                                    <p>
                                        <?php echo $contrato['cedula']; ?>
                                    </p>
                                </td>
                                <td><label>VEHICULO:</label>
                                    <p>
                                        <?php echo $contrato['modelo'] ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td><label>AÑO</label>
                                    <p>
                                        <?php echo $contrato['anio']; ?>
                                    </p>
                                </td>
                                <td><label>NUMERO DE GPS:</label>
                                    <p>
                                        <?php echo $contrato['num_gps'] ?>
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <td><label>ESTADO</label>
                                    <p>
                                        <?php echo $contrato['estado']; ?>
                                    </p>
                                </td>
                                <td><label>DETALLE:</label>
                                    <p>
                                        <?php echo $contrato['descripciones'] ?>
                                    </p>
                                </td>

                            </tr>
                            <tr>
                                <td><label>OBSERVACIONES:</label>
                                    <p>
                                        <?php echo $contrato['observaciones']; ?>
                                    </p>
                                </td>

                            </tr>

                        </table>
                    </div>
                </td>

            </tr>
        </table>
        <div>
            <p class="nota">Si usted tiene preguntas sobre este contrato, <br>pongase en contacto con nombre, teléfono y
                Email</p>
            <h4 class="label_gracias">¡Gracias por su compra!</h4>
        </div>

    </div>

</body>

</html>