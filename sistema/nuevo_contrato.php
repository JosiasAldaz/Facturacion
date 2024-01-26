<?php
session_start();
include "../conexion.php";
// echo md5($_SESSION['idUser']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once "includes/scripts.php"; ?>
    <title>Nuevo_contrato</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>

    <h1>Ventas</h1>
    <section id="container">
        <div class="title_page">
            <h1><i class="fa-solid fa-cash-register"></i> Nuevo Contrato</h1>
            <label><b>Vendedor</b> </label>
            <p>
                <?php echo $_SESSION['nombre'] ?>
            </p>
        </div>
        <div class="datos_venta">
            <h4>Datos de contratos</h4>
            <div class="datos_cliente">
                <form name="form_new_contrato" id="form_new_contrato" class="datos" method="post">
                    <input type="hidden" name="action" value="addContrato">
                    <input type="hidden" id="idcliente" name="idcliente" value="" required>
                    <div class="wd30">
                        <label for="">Cedula</label>
                        <input type="text" name="nit_cliente" id="nit_cliente">
                    </div>

                    <div class="wd30">
                        <label for="">Nombre</label>
                        <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
                    </div>
                    <div class="wd30">
                        <label for="">Telefono</label>
                        <input type="number" name="tel_cliente" id="tel_cliente" disabled required>
                    </div>
                    <div class="wd100">
                        <label for="">Direccion</label>
                        <input type="text" name="dir_cliente" id="dir_cliente" disabled required>
                    </div>
                    <input type="hidden" id="id_contrato" name="id_contrato"> <!-- Agregado -->
                    <div id="div_registro_cliente" class="wd100">
                        <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i>Guardar</button>
                    </div>
                    <div class="wd30">
                        <label for="fecha_inicio">Fecha de inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio">
                    </div>
                    <div class="wd30">
                        <label for="duracion" style="display: inline-block; margin-left: 10px;">Duración:</label>
                        <select id="duracion" style="display: inline-block;">
                            <option value="1">1 año</option>
                            <option value="2">2 años</option>
                            <option value="3">3 años</option>
                        </select>
                    </div>
                    <div class="wd30">
                        <label for="fecha_fin" style="display: inline-block; margin-left: 10px;">Fecha de
                            finalización:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin">
                    </div>
                    <div class="wd30">
                        <label for="">Codigo</label>
                        <input type="text" name="nit_codigo" id="nit_codigo">
                    </div>
                    <div class="wd30">
                        <label for="">Placa</label>
                        <input type="text" name="nit_placa" id="nit_placa">
                    </div>
                    <div class="wd30">
                        <label for="">Modelo</label>
                        <input type="text" name="modelo" id="modelo" disabled required>
                    </div>
                    <div class="wd30">
                        <label for="">Observaciones</label>
                        <input type="text" name="observacion" id="observacion">
                    </div>
                    <div>
                        <a href="registro_vehiculo.php" class="btn_ok textcenter" style="background-color: green"
                            id="btn_registro_vehiculo">
                            <i class="fa fa-handshake-o"></i> Añadir Vehiculo
                        </a>
                        <a href="#" class="btn_new textcenter" style="background-color: #1E30A0"
                            id="btn_nuevo_contrato">
                            <i class="far fa-edit"></i> Procesar</a>
                    </div>
                </form>
                <br>
                <table class="tbl_items">
                    <thead>
                        <tr>
                            <th width="100px">Número</th>
                            <th>Descripcion</th>
                            <th width="100px">Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                        <tr>
                            <td><input id="num_item" value="1" disabled></td>
                            <td><input type="text" name="txt_descripcion" class="txt_field" id="txt_descripcion"></td>
                            <td><input type="text" name="txt_cant" class="txt_field" id="txt_cant"></td>
                            <td><a id="add_item" class="link_add" style="color: green"><i
                                        class="fas fa-plus"></i>Agregar</a></td>
                        </tr>
                        <tr>
                            <th width="100px">Número</th>
                            <th>Descripcion</th>
                            <th width="100px">Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas de datos se agregarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
<script>
    $(document).ready(function () {
        $('#btn_nuevo_contrato').click(function (e) {
            e.preventDefault();

            // Validar campos obligatorios antes de enviar el formulario
            const cedula = $('#nit_cliente').val().trim();
            const nombre = $('#nom_cliente').val().trim();
            const telefono = $('#tel_cliente').val().trim();
            const direccion = $('#dir_cliente').val().trim();
            const fechaInicio = $('#fecha_inicio').val().trim();
            const duracion = $('#duracion').val().trim();
            const fechaFin = $('#fecha_fin').val().trim();
            const placa = $('#nit_placa').val().trim();
            const modelo = $('#modelo').val().trim();
            const codigo = $('#nit_codigo').val().trim();
            const observacion = $('#observacion').val().trim();

            // Verificar campos obligatorios
            if (!cedula || !nombre || !telefono || !direccion || !fechaInicio || !duracion || !fechaFin || !placa || !modelo || !codigo || !observacion) {
                alert("Por favor, complete todos los campos obligatorios.");
                return;
            }

            //clase nuevo contrato: tipos de datos que sean correcto, numero de cedula, arreglar la fecha de entrada.
            // Verificar si hay al menos una fila en la tabla de ítems
            if ($('.tbl_items tbody tr').length < 1) {
                alert('Debes agregar al menos una fila de ítem antes de insertar el contrato.');
                return;
            }
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: $('#form_new_contrato').serialize(),
                success: function (response) {
                    if (response != 'error') {
                        // Agregar el ID del contrato al campo de texto
                        $('#id_contrato').val(response);

                        // Bloquear campos
                        $('#nit_cliente').attr('disabled', 'disabled');
                        $('#fecha_inicio').attr('disabled', 'disabled');
                        $('#fecha_fin').attr('disabled', 'disabled');
                        $('#observacion').attr('disabled', 'disabled');
                        $('#nit_placa#nit_codigo').attr('disabled', 'disabled');
                        $('#nit_codigo').attr('disabled', 'disabled');

                        // Obtener los valores de cada fila de ítem y enviarlos para insertar en item_contrato
                        $('.tbl_items tbody tr').each(function () {
                            var numItem = $(this).find('input[name="txt_num"]').val();
                            var descripcion = $(this).find('input[name="txt_descripcion"]').val();
                            var cantidad = $(this).find('input[name="txt_cant"]').val();

                            var action = 'addItemContrato';
                            // Realizar una inserción de item_contrato para cada fila de ítem
                            $.ajax({
                                url: 'ajax.php', // Ajusta la URL a tu archivo PHP de inserción de ítem
                                type: 'POST',
                                async: true,
                                data: { action: action, numItem: numItem, descripcion: descripcion, cantidad: cantidad, id_contrato: response },
                                success: function (itemResponse) {
                                    // Puedes manejar la respuesta de la inserción del ítem aquí
                                    if (itemResponse != 'error') {
                                        alert('Contrato insertado correctamente.');
                                    } else {
                                        alert('Uno o varios campos son incorrectos.');
                                    }
                                },
                                error: function (itemError) {
                                    console.log('Fallo interno ' + error);
                                }
                            });
                        });

                        $('#nit_cliente').val('');
                        $('#fecha_inicio').val('');
                        $('#fecha_fin').val('');
                        $('#observacion').val('');
                        $('#nit_placa').val('');
                        $('#nit_codigo').val('');
                        $('.tbl_items tbody').empty(); // Limpiar la tabla de ítems

                        // Restablecer los campos bloqueados
                        $('#nit_cliente').removeAttr('disabled');
                        $('#fecha_inicio').removeAttr('disabled');
                        $('#fecha_fin').removeAttr('disabled');
                        $('#observacion').removeAttr('disabled');
                        $('#nit_placa').removeAttr('disabled');
                        $('#nit_codigo').removeAttr('disabled');

                        // Restablecer el número de ítem
                        numItem = 1;
                        $('#num_item').val(numItem);
                    }
                },
                error: function (error) {
                    console.log('Fallo externo ' + error);
                }
            });
        });
    });
</script>
<!--Este Script sirve para las filas-->
<script>
    $(document).ready(function () {
        // Inicializa numItem en 1
        var numItem = 1;

        $('#add_item').click(function () {
            // Obtiene los valores de los campos actuales
            var descripcion = $('#txt_descripcion').val();
            var cantidad = $('#txt_cant').val();

            // Agrega una fila deshabilitada debajo de la tabla y restaura los valores
            var newRow = '<tr>' +
                '<td><input type="number" name="txt_num" value="' + numItem + '" disabled></td>' +
                '<td><input type="text" name="txt_descripcion" class="txt_field" disabled value="' + descripcion + '"></td>' +
                '<td><input type="text" name="txt_cant" class="txt_field" disabled value="' + cantidad + '"></td>' +
                '<td><a id="add_item" class="delete-row" style="color: red"><i class="fas fa-ban"></a></td>' +
                '</tr>';

            $('.tbl_items tbody').append(newRow);

            // Incrementa el número en la fila superior (encabezado de la tabla)
            numItem++;
            $('#num_item').val(numItem);

            // Borra los valores anteriores en "Descripción" y "Cantidad"
            $('#txt_descripcion').val('');
            $('#txt_cant').val('');
        });

        // Agregar evento para eliminar fila
        $('.tbl_items').on('click', '.delete-row', function () {
            var row = $(this).closest('tr');
            var rowNumber = parseInt(row.find('input[type="text"]').first().val());
            row.remove();

            // Reduce el número en la fila superior (encabezado de la tabla)
            numItem--;
            $('#num_item').val(numItem);

            // Actualiza los números de las filas debajo de la eliminada
            $('.tbl_items tbody tr').each(function () {
                var currentNumber = parseInt($(this).find('input[type="text"]').first().val());
                if (currentNumber > rowNumber) {
                    $(this).find('input[type="text"]').first().val(currentNumber - 1);
                }
            });
        });

    });
</script>

<!--validaciones-->
<script>
    // Evento para verificar si se ha ingresado una cédula válida
    document.getElementById("nit_cliente").addEventListener("keyup", function () {
        const cedula = this.value.trim();
        const nombreCliente = document.getElementById("nom_cliente").value.trim();
        const telefonoCliente = document.getElementById("tel_cliente").value.trim();
        const direccionCliente = document.getElementById("dir_cliente").value.trim();

        // Expresión regular para permitir solo números en la cédula
        const regexNumeros = /^[0-9]+$/;

        if (!regexNumeros.test(cedula)) {
            alert("Por favor, ingrese solo números en el campo de cédula.");
            this.value = ''; // Limpiar el campo si se ingresa un valor no válido
        }

        // Resto del código para validar otros campos si es necesario
    });

    // Evento para verificar si se ha ingresado un valor numérico en el campo de cantidad
    document.getElementById("txt_cant").addEventListener("keyup", function () {
        const cantidad = this.value.trim();

        // Expresión regular para permitir solo números en la cantidad
        const regexNumeros = /^[0-9]+$/;

        if (!regexNumeros.test(cantidad)) {
            alert("Por favor, ingrese solo números en el campo de cantidad.");
            this.value = ''; // Limpiar el campo si se ingresa un valor no válido
        }
    });
</script>



<script>
    const duracionSelect = document.getElementById("duracion");
    const fechaInicioInput = document.getElementById("fecha_inicio");
    const fechaFinInput = document.getElementById("fecha_fin");

    // Resto del script JavaScript para el funcionamiento del formulario y las acciones en la página

    // Función para calcular la fecha de finalización
    /* function calcularFechaFin() {
         console.log("Fecha de fin calculada: " + fechaInicioInput.value);
         const fechaInicio = new Date(fechaInicioInput.value);
         const duracion = parseInt(duracionSelect.value);
 
         if (!isNaN(fechaInicio.getTime()) && !isNaN(duracion)) {
             const fechaFin = new Date(fechaInicio);
             fechaFin.setFullYear(fechaFin.getFullYear() + duracion);
             fechaFinInput.valueAsDate = fechaFin;
             console.log("Fecha de fin calculada: " + fechaFin);
         }
     }*/

    function calcularFechaFin() {
        console.log("Fecha de inicio: " + fechaInicioInput.value);
        // Obtener la fecha de inicio como cadena "YYYY-MM-DD"
        const fechaInicioStr = fechaInicioInput.value;

        // Separar la fecha en año, mes y día
        const partesFechaInicio = fechaInicioStr.split("-");
        const añoInicio = parseInt(partesFechaInicio[0]);
        const mesInicio = parseInt(partesFechaInicio[1]) - 1; // Restar 1 para el mes (los meses comienzan en 0)
        const diaInicio = parseInt(partesFechaInicio[2]);

        const duracion = parseInt(duracionSelect.value);

        // Crear una fecha de inicio ajustada sin zona horaria
        const fechaInicio = new Date(añoInicio, mesInicio, diaInicio);

        if (!isNaN(fechaInicio.getTime()) && !isNaN(duracion)) {
            const fechaFin = new Date(fechaInicio);
            fechaFin.setFullYear(fechaFin.getFullYear() + duracion);

            // Formatear la fecha de fin en "YYYY-MM-DD"
            const dia = fechaFin.getDate();
            const mes = fechaFin.getMonth() + 1; // Sumamos 1 porque los meses comienzan en 0
            const año = fechaFin.getFullYear();
            const fechaFinFormateada = `${año}-${mes < 10 ? '0' : ''}${mes}-${dia < 10 ? '0' : ''}${dia}`;

            fechaFinInput.value = fechaFinFormateada;
            $('#fecha_fin').val(fechaFinFormateada);

            console.log("Fecha de fin calculada: " + fechaFinFormateada);
        }
    }


    // Evento para calcular la fecha de finalización cuando se cambia la duración
    duracionSelect.addEventListener("change", calcularFechaFin);

    // Evento para calcular la fecha de finalización cuando se cambia la fecha de inicio
    fechaInicioInput.addEventListener("change", calcularFechaFin);

    //Buscar Cliente
    $('#nit_cliente').keyup(function (e) {
        e.preventDefault();
        var cl = $(this).val();
        var action = 'searchCliente'
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {
                action: action,
                cliente: cl
            },
            success: function (response) {
                if (response == 0) {
                    $('#idcliente').val('');
                    $('#nom_cliente').val('');
                    $('#tel_cliente').val('');
                    $('#dir_cliente').val('');
                } else {
                    // console.log(response);
                    var data = $.parseJSON(response);
                    $('#idcliente').val(data.idcliente);
                    $('#nom_cliente').val(data.nombre);
                    $('#tel_cliente').val(data.telefono);
                    $('#dir_cliente').val(data.direccion);
                    //Bloques de Campos
                    $('#nom_cliente').attr('disabled', 'disabled');
                    $('#tel_cliente').attr('disabled', 'disabled');
                    $('#dir_cliente').attr('disabled', 'disabled');
                }
            },
            error: function (error) {
            }
        });
    });

    //Buscar Vehiculo
    $('#nit_placa').keyup(function (e) {
        e.preventDefault();
        var cl = $(this).val();
        var action = 'searchVehiculo'
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {
                action: action,
                vehiculo: cl
            },
            success: function (response) {
                if (response == 0) {
                    $('#placa').val('');
                    $('#modelo').val('');
                } else {
                    var data = $.parseJSON(response);
                    $('#placa').val(data.placa);
                    $('#modelo').val(data.modelo);
                    //Bloques de Campos
                    $('#modelo').attr('disabled', 'disabled');
                }
            },
            error: function (error) {
                console.log("Ha ocurrido un error en la solicitud Ajax: ", error);
                // Puedes mostrar un mensaje de error al usuario o tomar otras acciones necesarias.
            }
        });
    });

    // Evento para verificar si se ha ingresado una cédula válida
    document.getElementById("nit_cliente").addEventListener("keyup", function () {
        const cedula = this.value.trim();
        const nombreCliente = document.getElementById("nom_cliente").value.trim();
        const telefonoCliente = document.getElementById("tel_cliente").value.trim();
        const direccionCliente = document.getElementById("dir_cliente").value.trim();

        //bloque de codigo para validar la cedula y la placa
        ////////////

    });
</script>

<?php include_once "includes/footer.php"; ?>
</body>

</html>