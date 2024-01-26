<?php
session_start();
include "../conexion.php";


$clientesquery = "SELECT
    c.idcliente,
    c.nombre,
    c.cedula,
    MAX(cont.id_contrato) AS id_contrato
    FROM cliente c
    LEFT JOIN contrato cont ON c.cedula = cont.cedula
    GROUP BY c.cedula";



/* $result = mysqli_query($conection, $query); */
$resultclientesquerry = mysqli_query($conection, $clientesquery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once "includes/scripts.php"; ?>
    <title>lista_contratos</title>
    <style>
        .table-container {
            display: flex;
        }

        .table-wrapper {
            margin-right: 20px;
            /* Ajusta el margen entre las tablas según tus necesidades */
        }
    </style>

</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">
        <h1><i class="fa-solid fa-bookmark"></i>Lista de Contratos</h1>
        <div>
            <div class="filter-bar">
                <form method="get" class="form_search">
                    <input class="input_b" type="text" name="busqueda" id="busqueda" placeholder="Buscar"
                        oninput="filterTable()" />
                </form>
                <label for="state">Filtrar por Estado:</label>
                <select id="state" onchange="filterTable()" class="input_b">
                    <option value="">Todos</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Activo">Activo</option>
                    <option value="Caducado">Caducado</option>
                </select>
            </div>

            <div class="table-container">
                <div class="table-wrapper">
                    <?php

                    echo '<table id="contractsTable">';
                    echo '<tr>';
                    echo '<th>Nombre del Cliente</th>';
                    echo '<th>Cedula o RUC</th>';
                    echo '</tr>';

                    // Verificar si hay resultados
                    if ($resultclientesquerry && mysqli_num_rows($resultclientesquerry) > 0) {
                        while ($row = mysqli_fetch_assoc($resultclientesquerry)) {
                            echo '<tr>';
                            echo '<td>' . $row['nombre'] . '</td>';
                            echo '<td>' . $row['cedula'] . '</td>';
                            echo '<td>';
                            echo '<button class="btn_view ver_detalle ver_detalle"   type="button" id_contrato="' . $row['id_contrato'] . '" data-cedula="' . $row['cedula'] . '">Ver</button>';
                            echo '<button class="btn_view view_contract" type="button" cedula="' . $row['cedula'] . '"><i class="fas fa-eye"></i>VER LISTA</button>';
                            echo '<button class="btn_view ver_tabla" id="ver_tabla" type="button" cedula="' . $row['cedula'] . '"><i class="fas fa-eye"></i>VER CONTRATOS</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                        echo '<td colspan="11">No se encontraron registros de clientes.</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                    ?>
                </div>
                <div class="table-wrapper">
                    <?php


                    echo '<table id="secondTable">';
                    echo '<tr>';
                    echo '<th>Fecha de Inicio</th>';
                    echo '<th>Fecha de Finalizacion</th>';
                    echo '<th>Estado</th>';
                    echo '<th>Placa</th>';
                    echo '<th>Modelo</th>';
                    echo '<th>Numero de GPS</th>';
                    echo '<th>Items</th>';
                    echo '<th>clave</th>';
                    echo '</tr>';
                    echo '</table>';
                    ?>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $(".ver_detalle").on("click", function () {
                    // Obtener la cédula desde el atributo de datos del botón
                    var cedula = $(this).data("cedula");
                    var action = 'ver_detalle';
                    console.log(cedula);

                    $.ajax({
                        type: 'POST',
                        url: 'ajax.php',
                        data: {
                            action: action,
                            cliente: cedula
                        },
                        async: true,
                        success: function (response) {
                            console.log("Respuesta del servidor:", response);

                            try {
                                var detalles = JSON.parse(response);

                                // Limpiar la segunda tabla
                                $("#secondTable").empty();

                                // Si los datos están vacíos, mostrar un mensaje
                                if (detalles.length === 0) {
                                    var emptyRow = "<tr><td colspan='9'>No se encontraron detalles para este cliente.</td></tr>";
                                    $("#secondTable").append(emptyRow);
                                } else {
                                    // Insertar los nuevos datos en la segunda tabla
                                    var headerRow = "<tr><th>ID Contrato</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th><th>Placa</th><th>Modelo</th><th>Número GPS</th><th>Clave</th><th>Total de Items</th></tr>";


                                    $("#secondTable").append(headerRow);

                                    // Insertar los nuevos datos en la segunda tabla
                                    $.each(detalles, function (index, detalle) {

                                        var newRow = "<tr><td>" + detalle.id_contrato + "</td><td>" + detalle.fecha_inicio + "</td><td>" + detalle.fecha_fin + "</td><td>" + detalle.estado + "</td><td>" + detalle.placa + "</td><td>" + detalle.modelo + "</td><td>" +
                                            detalle.num_gps + "</td><td>" + detalle.clave + "</td><td>" + detalle.total_items + "</td>";

                                        //newRow += "<td><button class='btn_pdf' data-idcontrato='" + detalle.id_contrato + "'>Generar PDF</button></td></tr>";
                                        $("#secondTable").append(newRow);
                                    });

                                }
                            } catch (error) {
                                console.error('Error al analizar JSON:', error);
                            }
                        },
                    });
                });
            });
        </script>


        <script>
            function filterTable() {
                var busqueda = document.getElementById("busqueda").value.toLowerCase();
                var state = document.getElementById("state").value;
                var rows = document.getElementById("contractsTable").getElementsByTagName("tr");
                for (var i = 1; i < rows.length; i++) {
                    var contractNumberCell = rows[i].getElementsByTagName("td")[0];
                    var stateCell = rows[i].getElementsByTagName("td")[5];
                    var display = true;
                    if (busqueda && contractNumberCell) {
                        var contractNumber = contractNumberCell.textContent.toLowerCase();
                        if (contractNumber.indexOf(busqueda) === -1) {
                            display = false;
                        }
                    }
                    if (state && stateCell) {
                        if (state === "Activo" && stateCell.textContent !== "Activo") {
                            display = false;
                        } else if (state === "Caducado" && stateCell.textContent !== "Caducado") {
                            display = false;
                        } else if (state === "Pendiente" && stateCell.textContent !== "Pendiente") {
                            display = false;
                        }
                    }
                    rows[i].style.display = display ? "" : "none";
                }
            }

            $('.view_contract').click(function (e) {
                
                e.preventDefault();
                var cedula = $(this).attr('cedula');
                generarPDF(cedula);

            });

            $('.ver_tabla').click(function (e) {
                
                e.preventDefault();
                var cedula = $(this).attr('cedula');
                generarPDFtabla(cedula);

            });
          


            function generarPDF(cedula) {
                var ancho = 1000;
                var alto = 800;
                // Calcular posicion x,y para centrar la ventana
                var x = parseInt((window.screen.width / 2) - (ancho / 2));
                var y = parseInt((window.screen.height / 2) - (alto / 2));

                // Construir la URL con los datos proporcionados
                var url = 'contrato/generarContratoList.php?cedula=' + cedula;

                // Abrir la ventana emergente
                window.open(url, "Contrato", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho +
                    ",scrollbars=si,location=no,resizable=si,menubar=no");
            }


            function generarPDFtabla(cedula) {
                var ancho = 1000;
                var alto = 800;
                // Calcular posicion x,y para centrar la ventana
                var x = parseInt((window.screen.width / 2) - (ancho / 2));
                var y = parseInt((window.screen.height / 2) - (alto / 2));

                // Construir la URL con los datos proporcionados
                var url = 'contrato/generaContrato.php?cedula=' + cedula;

                // Abrir la ventana emergente
                window.open(url, "Contrato", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho +
                    ",scrollbars=si,location=no,resizable=si,menubar=no");
            }





            $(document).on('click', '.btn_new_contrato', function (e) {
                e.preventDefault();

                // Obtener la fila actual
                var row = $(this).closest('tr');

                // Obtener los datos de la fila
                var idContrato = row.find('td:eq(0)').text(); // ID Contrato
                var cedula = row.find('td:eq(1)').text(); // Cédula
                var nombre = row.find('td:eq(2)').text(); // Nombre del Cliente
                var fechaInicio = row.find('td:eq(3)').text(); // Fecha de Inicio
                var fechaFin = row.find('td:eq(4)').text(); // Fecha de Finalización
                var estado = row.find('td:eq(5)').text(); // Estado
                var observaciones = row.find('td:eq(6)').text(); // Observaciones
                var placa = row.find('td:eq(7)').text(); // Placa
                var clave = row.find('td:eq(8)').text(); // Clave

                // Llama a la función generarPDF con los datos de la fila
                //generarPDF(idContrato, cedula, nombre, fechaInicio, fechaFin, estado, observaciones, placa, clave);
            });


        </script>
    </section>
</body>

</html>