<?php
session_start();
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once "includes/scripts.php"; ?>
    <title>Nueva_Venta</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <h1>Ventas</h1>
    <section id="container">
        <div class="title_page">
            <h1><i class="fa-solid fa-cash-register"></i> Nueva Venta</h1>
        </div>
        <div class="datos_cliente">
            <div class="action_cliente">
                <h4>Datos del Cliente</h4>
                <a href="#" class="btn_new btn_new_cliente" style="background: #1E30A0"><i class="fa-solid fa-user-plus"
                        style="color: #ffff;"></i> Nuevo Cliente</a>
            </div>
            <form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
                <input type="hidden" name="action" value="addCliente">
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
                    <input type="text" name="tel_cliente" id="tel_cliente" disabled required>
                </div>
                <div class="wd100">
                    <label for="">Direccion</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required>
                </div>
                <div id="div_registro_cliente" class="wd100">
                    <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i>Guardar</button>
                </div>
            </form>
        </div>
        <div class="datos_venta">
            <h4>Datos de Ventas</h4>
            <div class="datos">
                <div class="wd50">
                    <label><b>Vendedor</b> </label>
                    <p>
                        <?php echo $_SESSION['nombre'] ?>
                    </p>
                </div>
                <div class="wd50">
                    <label for="" style=""> <b>Acciones</b> </label>
                    <div id="acciones_ventas">
                        <a href="#" class="btn_ok textcenter" style="background-color: red" id="btn_anular_venta">
                            <i class="fas fa-ban" styele="background-color: red"></i> Anular</a>
                        <a href="#" class="btn_new textcenter" style="background-color: #1E30A0" id="btn_factura_venta" style="display:none;">
                            <i class="far fa-edit"></i> Procesar</a>
                    </div>
                </div>
            </div>
            <div>
                <button id="scan-btn" class="btn_new" style="background: #1E30A0">Escanear código</button>
            </div>
            <div id="result"></div>
            <div id="video-container"></div>
        </div>
        <table class="tbl_venta">
            <thead>
                <tr>
                    <th width="100px">Codigo</th>
                    <th>Descripcion</th>
                    <th>Existencia</th>
                    <th width="100px">Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio total</th>
                    <th>Acciones</th>
                </tr>
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                    <td id="txt_precio" class="textright">0.00</td>
                    <td id="txt_precio_total" class="textright">0.00</td>
                    <td> <a href="#" id="add_product_venta" class="link_add" style="color: green"><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
                <tr>
                    <th>Codigo</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio Total</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody id="detalle_venta">
            </tbody>
            <tfoot id="detalle_totales">
            </tfoot>
        </table>
    </section>
 
    <script>
        let scanner = null;
        let videoElement = null;
        let resultElement = null;
        function startScanner() {
            const videoContainer = document.getElementById('video-container');
            // Obtener las cámaras disponibles
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    // Obtener la primera cámara disponible
                    const camera = cameras[0];
                    // Crear el elemento de video
                    videoElement = document.createElement('video');
                    videoElement.id = 'video';
                    videoElement.width = 640;
                    videoElement.height = 480;
                    videoContainer.appendChild(videoElement);
                    // Configurar el scanner
                    scanner = new Instascan.Scanner({ video: videoElement });
                    // Escuchar el evento de escaneo
                    scanner.addListener('scan', function (content) {
                        // Obtener referencia al campo de entrada del código de producto
                        const codProductoInput = document.getElementById('txt_cod_producto');
                        // Asignar el contenido del código QR escaneado al campo de entrada
                        codProductoInput.value = content;
                        // Crear y despachar un evento de teclado simulando "Enter"
                        const enterEvent = new KeyboardEvent('keydown', {
                            key: 'Enter',
                            code: 'Enter',
                            keyCode: 13,
                            which: 13,
                            bubbles: true
                        });
                        codProductoInput.dispatchEvent(enterEvent);
                        // Cerrar la cámara
                        closeCamera();
                    });
                    // Iniciar el scanner con la cámara seleccionada
                    scanner.start(camera);
                } else {
                    console.error('No se encontraron cámaras en el dispositivo.');
                }
            }).catch(function (error) {
                console.error(error);
            });
        }
        function closeCamera() {
            if (scanner !== null) {
                scanner.stop();
                scanner = null;
            }
            if (videoElement !== null) {
                videoElement.srcObject.getVideoTracks().forEach(track => track.stop());
                videoElement.parentNode.removeChild(videoElement);
                videoElement = null;
            }
        }
        // Obtener referencia al botón "Escanear código"
        const scanBtn = document.getElementById('scan-btn');
        // Agregar evento de clic al botón
        scanBtn.addEventListener('click', function () {
            // Verificar si el scanner ya se ha iniciado
            if (scanner === null) {
                startScanner();
            }
        });
    </script>

<script>
    $('.btn_new_cliente').click(function (e) {
        e.preventDefault();
        $('#nom_cliente').removeAttr('disabled');
        $('#tel_cliente').removeAttr('disabled');
        $('#dir_cliente').removeAttr('disabled');
        $('#div_registro_cliente').slideDown();
    });
    
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
                    // Mostrar Boton de Agregar
                    $('.btn_new_cliente').slideDown();
                } else {
                    // console.log(response);
                    var data = $.parseJSON(response);
                    $('#idcliente').val(data.idcliente);
                    $('#nom_cliente').val(data.nombre);
                    $('#tel_cliente').val(data.telefono);
                    $('#dir_cliente').val(data.direccion);
                    // Ocultar Botton de Agregar
                    $('.btn_new_cliente').slideUp();
                    //Bloques de Campos
                    $('#nom_cliente').attr('disabled', 'disabled');
                    $('#tel_cliente').attr('disabled', 'disabled');
                    $('#dir_cliente').attr('disabled', 'disabled');
                    //Ocultar Boton de Agregar
                    $('#div_registro_cliente').slideUp();
                }
            },
            error: function (error) {
            }
        });
    });
    //Crear Cliente
    $('#form_new_cliente_venta').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_new_cliente_venta').serialize(),
            success: function (response) {
                if (response != 'error') {
                    // Agregar Id
                    $('idcliente').val(response);
                    //Bloque de Campos
                    $('#nom_cliente').attr('disabled', 'disabled');
                    $('#tel_cliente').attr('disabled', 'disabled');
                    $('#dir_cliente').attr('disabled', 'disabled');
                    //Ocultar botton de Agregar
                    $('.btn_new_cliente').slideUp();
                    //Ocultar botton de guardar
                    $('#div_registro_cliente').slideUp();
                }
            },
            error: function (error) {
            }
        });
    });
    //Buscar Producto
    $('#txt_cod_producto').keyup(function (e) {
        e.preventDefault();
        var producto = $(this).val();
        var action = "infoProducto";
        if (producto != '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {
                    action: action,
                    producto: producto
                },
                success: function (response) {
                    //console.log(response);
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        $('#txt_descripcion').html(info.descripcion);
                        $('#txt_existencia').html(info.existencia);
                        $('#txt_cant_producto').val('1');
                        $('#txt_precio').html(info.precio);
                        $('#txt_precio_total').html(info.precio);
                        //Activar Cantidad
                        $('#txt_cant_producto').removeAttr('disabled');
                        //Muestra boton Agregar
                        $('#add_product_venta').slideDown();
                    } else {
                        // console.log(response);
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');;
                        //Bloquear cantidad
                        $('#txt_cant_producto').attr('disabled', 'disabled');
                        //Ocultar boton Agregar
                        $('#add_product_venta').slideUp();
                    }
                },
                error: function (error) {
                }
            });
        }
    });
    $('#txt_cant_producto').keyup(function (e) {
        e.preventDefault();
        var cantidad = $(this).val();
        var existencia = parseInt($('#txt_existencia').html());
        if (($.isNumeric(cantidad) && cantidad >= 1) && cantidad <= existencia) {
            var precio_total = cantidad * parseFloat($('#txt_precio').html());
            $('#txt_precio_total').html(precio_total.toFixed(2));
            $('#add_product_venta').slideDown();
        } else {
            $('#txt_precio_total').html('0.00');
            $('#add_product_venta').slideUp();
        }
    });
    //!Cambios qu he agregado y no funcionan bien
    // ?Agregar producto al detalle
    $("#add_product_venta").click(function (e) {
        e.preventDefault();
        if ($('#txt_cant_producto').val() > 0) {
            var codproducto = $('#txt_cod_producto').val();
            var cantidad = $('#txt_cant_producto').val();
            var action = 'addProductoDetalle';
            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {
                    action: action,
                    producto: codproducto,
                    cantidad: cantidad
                },
                success: function (response) {
                    // console.log(response);
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        //    console.log(info);
                        $('#detalle_venta').html(info.detalle);
                        $('#detalle_totales').html(info.totales);
                        $('#txt_cod_producto').val('');
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');
                        //Bloquear Campo
                        $('#txt_cant_producto').attr('disabled', 'disabled');
                        //Agregar Boton de Agregar
                        $('#add_product_venta').slideUp();
                        //  location.reload();
                    } else {
                        console.log('NO DATA');
                    }
                    viewProcesar();
                },
                error: function (error) {
                }
            });
        }
    });
    //*Esto nose si funcione pero esto hay que arreglar
    $(document).ready(function () {
        var usuarioid = '<?php echo $_SESSION['idUser']; ?>';
        serchForDetalle(usuarioid);
    });
    //!Anular Venta codigo no funcional
    $('#btn_anular_venta').click(function (e) {
        e.preventDefault();
        var rows = $('#detalle_venta tr').length;
        if (rows > 0) {
            var action = 'anularVenta';
            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {
                    action: action
                },
                success: function (response) {
                    if (response != 'error') {
                        location.reload();
                    }
                    viewProcesar();
                },
                error: function (error) {
                },
            });
        }
    });
    //!Facturar Venta
    $('#btn_factura_venta').click(function (e) {
        e.preventDefault();
        var rows = $('#detalle_venta tr').length;
        if (rows > 0) {
            var action = 'procesarVenta';
            var codcliente = $('#idcliente').val();
            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {
                    action: action,
                    codcliente: codcliente
                },
                success: function (response) {
                    console.log("response:" +response);
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        console.log(info);
                        //Est Agregue
                        generarPDF(info.codcliente, info.nofactura)
                        location.reload();
                    } else {
                        console.log("No data");
                    }
                    // viewProcesar();
                },
                error: function (error) {
                },
            });
        }
    });
    function generarPDF(cliente, factura) {
        var ancho = 1000;
        var alto = 800;
        //Calcular posicion x,y para centrar la venta
        var x = parseInt((window.screen.width / 2) - (ancho / 2));
        var y = parseInt((window.screen.height / 2) - (alto / 2));
        var url = 'factura/generaFactura.php?cl=' + cliente + '&f=' + factura;
        window.open(url, "Factura", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho +
            ",scrollbars=si,location=no,resizable=si,menubar=no");
    }
    function del_product_detalle(correlativo) {
        var action = 'del_product_detalle';
        var id_detalle = correlativo;
        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {
                action: action,
                id_detalle: id_detalle
            },
            success: function (response) {
                // console.log(response);
                if (response != 'error') {
                    var info = JSON.parse(response);
                    console.log(response);
                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);
                    $('#txt_cod_producto').val('');
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');
                    //Bloquear Campo
                    $('#txt_cant_producto').attr('disabled', 'disabled');
                    //Agregar Boton de Agregar
                    $('#add_product_venta').slideUp();

                } else {
                    $('#detalle_venta').html('');
                    $('#detalle_totales').html('');
                }
                viewProcesar();
            },
            error: function (error) {
            }
        });
    }
    function viewProcesar() {
        if ($('#detalle_venta tr').length > 0) {
            $('#btn_factura_venta').show();
        } else {
            $('#btn_factura_venta').hide();
        }
    }
    function serchForDetalle(id) {
        var action = 'serchForDetalle';
        var user = id;
        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {
                action: action,
                user: user
            },
            success: function (response) {
                //console.log(response);
                if (response != 'error') {
                    var info = JSON.parse(response);
                    console.log(info);
                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);
                } else {
                    console.log('NO DATA');
                }
                viewProcesar();
            },
            error: function (error) {
            }
        });
    }
</script>
</body>
</html>
<?php include_once "includes/footer.php"; ?>
