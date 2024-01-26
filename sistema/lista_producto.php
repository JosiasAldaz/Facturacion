<?php
//Control de url de Administrador
session_start();
include_once "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php" ?>
    <title>Lista de Producto</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">
        <h1><i class="fa-solid fa-box"></i> Lista de Producto</h1>
        <a href="registro_producto.php" class="btn_new" style="background-color: #1E30A0;"><i
                class="fa-solid fa-circle-plus" style="color: #ffff;"></i>
            Crear Producto</a>

        <form action="buscar_producto.php" method="get" class="form_search" />
        <input class="input_b" type="text" name="busqueda" id="busqueda" placeholder="Buscar" />
        <!-- <input type="submit" value="Buscar" class="btn_search"></input> -->
        <!-- <button type="submit" class="btn_search"></button> -->
        <button type="submit" value="Buscar" class="btn_search">
            <div style="margin:5px"><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;"></i></div>
        </button>

        </form>
        <br>
        <table>
            <tr>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>
                    Proveedores
                </th>
                <th>Foto</th>
                <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>

                    <th>Acciones</th>
                <?php } ?>
                <th>
                    Generar QR
                </th>

            </tr>

            <!-- Paginador -->
            <?php

            $query_paginador = mysqli_query($conection, "SELECT count(*) as total_registro FROM producto where estatus= 1");
            $result_paginador = mysqli_fetch_array($query_paginador);
            $total_registro = $result_paginador['total_registro'];
            $por_pagina = 5;

            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);
            // Finaliza Paginador
            
            $query = mysqli_query($conection, "SELECT p.codproducto, p.descripcion, p.precio,
                p.existencia, pr.proveedor, p.foto 
                FROM producto p
                INNER JOIN proveedor pr
                ON p.proveedor = pr.codproveedor
                WHERE p.estatus=1 ORDER BY p.codproducto  DESC LIMIT $desde, $por_pagina");

            mysqli_close($conection);


            $result = mysqli_num_rows($query);

            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    if ($data['foto'] != 'img_producto.jpg') {
                        $foto = 'img/uploads/' . $data['foto'];
                    } else {
                        $foto = 'img/' . $data['foto'];
                    }

                    // Generar el contenido del código QR
                    $contenidoQR = $data['codproducto'];

                    // Generar el archivo de imagen del código QR
                    require_once('phpqrcode/phpqrcode.php'); // Asegúrate de tener la biblioteca PHP QR Code en tu servidor y actualiza la ruta
                    $rutaArchivoQR = 'img/' . $data['codproducto'] . '_qr.png'; // Actualiza la ruta a tu directorio de archivos QR
            
                    // Configurar los parámetros para generar el código QR
                    $tamaño = 10; // Tamaño del punto del código QR
                    $nivelCorrección = 'L'; // Nivel de corrección de errores (L, M, Q, H)
            
                    // Generar el código QR
                    QRcode::png($contenidoQR, $rutaArchivoQR, $nivelCorrección, $tamaño);

                    ?>
                    <tr class="row<?php echo $data['codproducto']; ?>">
                        <td>
                            <?php echo $data['codproducto']; ?>
                        </td>
                        <td>
                            <?php echo $data['descripcion']; ?>
                        </td>
                        <td class="celPrecio">
                            <?php echo $data['precio']; ?>
                        </td>
                        <td class="celExistencia">
                            <?php echo $data['existencia']; ?>
                        </td>
                        <td>
                            <?php echo $data['proveedor']; ?>
                        </td>
                        <td class="img_producto">
                            <img src="<?php echo $foto ?>" alt="<?php echo $data['descripcion']; ?>">
                        </td>
                        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                            <td>
                                <a class="link_agregar add_product" product="<?php echo $data['codproducto']; ?>" href="#">
                                    <i class="fa-solid fa-circle-plus"></i> Agregar
                                </a>
                                | <a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="link_edit"
                                    style="color: #1E30A0;">
                                    <i class="fa-regular fa-pen-to-square"></i> Editar
                                </a>
                                | <a href="#" product="<?php echo $data['codproducto']; ?>" class="link_delete del_product"
                                    style="color: red;">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </a>
                            </td>
                        <?php } ?>
                        <td class="img_producto">
                            <a href="<?php echo $rutaArchivoQR; ?>" download="<?php echo $data['codproducto']; ?>_qr.png">
                                <img src="<?php echo $rutaArchivoQR; ?>" alt="Generar QR">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            }



            ?>

        </table>
        <div class="paginador">
            <ul>

                <?php
                if ($pagina != 1) {

                    ?>
                    <li><a href="?pagina=<?php echo 1; ?>">|< </a>
                    </li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>">
                            << </a>
                    </li>

                    <?php
                }

                for ($i = 1; $i <= $total_paginas; $i++) {

                    if ($i == $pagina) {
                        echo '<li class="pageSelected">' . $i . '</> </li>';

                    } else {
                        echo '<li> <a href="?pagina=' . $i . '">' . $i . '</a> </li>';

                    }
                }
                if ($pagina != $total_paginas) {


                    ?>

                    <li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
                    <li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
                <?php } ?>
            </ul>
        </div>
    </section>

    <script>
        function mostrarModal() {
            $('.modal').fadeIn();
        }

        $('.add_product').click(function (e) {
            e.preventDefault();
            var producto = $(this).attr('product');
            var action = 'infoProducto';

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {
                    action: action,
                    producto: producto
                },

                success: function (response) {
                    if (response != 'error') {
                        var info = JSON.parse(response);
                        // $('#producto_id').val(info.codproducto);
                        // $('.nameProducto').html(info.descripcion);
                        $('.bodyModal').html(
                            '<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendData();">' +
                            '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i> <br>Agregar Producto</h1>' +
                            '<h2 class="nameProducto">' + info.descripcion + '</h2><br>' +
                            '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" required> <br>' +
                            '<input type="text" name="precio" id="txtPrecio" placeholder="Precio del producto" required>' +
                            '<input type="hidden" name="producto_id" id="producto_id" value="' +
                            info.codproducto + '">' +
                            '<input type="hidden" name="action" value="addProduct">' +
                            '<div class="alert alertAddProducto"></div>' +
                            '<button type="submit" class="btn_new">' +
                            '<i class="fas fa-plus"></i> Agregar' +
                            '</button>' +
                            '<a href="" class="btn_ok closeModal" id="closeModal">' +
                            '<i class="fas fa-ban"></i> Cerrar </a>' +
                            '</form>'
                        );
                    }
                    console.log(response);
                },

                error: function () {
                    console.log(error);

                }
            });
            mostrarModal();
        });
        // Modal parav eliminar producto Producto
        $('.del_product').click(function (e) {
            e.preventDefault();
            var producto = $(this).attr('product');
            var action = 'infoProducto';

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {
                    action: action,
                    producto: producto
                },

                success: function (response) {
                    if (response != 'error') {
                        var info = JSON.parse(response);

                        $('.bodyModal').html(
                            '<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">' +
                            '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i> <br>Eliminiar Producto</h1>' +

                            '<h2>¿Estas seguro de eliminar este registro?</h2>' +

                            '<h2 class="nameProducto">' + info.descripcion + '</h2><br>' +

                            '<input type="hidden" name="producto_id" id="producto_id" value="' +
                            info.codproducto + '">' +
                            '<input type="hidden" name="action" value="delProduct">' +
                            '<div class="alert alertAddProducto"></div>' +

                            '<a href="" class="btn_cancel closeModal" id="closeModal">Cerrar</a>' +

                            '<input type="submit" value="Eliminar" class="btn_ok">' +


                            '</form>'
                        );
                    }
                    console.log(response);
                },

                error: function () {
                    console.log(error);

                }
            });
            mostrarModal();
        });

        $('#search_proveedor').change(function (e) {
            e.preventDefault();
            var sistema = getUrl();
            //alert(sistema);
            location.href = sistema + 'buscar_producto.php?proveedor=' + $(this).val();
        });

        function getUrl() {
            var loc = window.location;
            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
            return loc.href.substring(0, loc.href.length - (loc.pathname + loc.search + loc.hash).length - pathName.length);
        }


        function sendData() {
            $('.alertAddProducto').html('');


            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: $('#form_add_product').serialize(),

                success: function (response) {


                    // console.log(response);
                    if (response == 'error') {
                        $('.alertAddProducto').html('<p style="color:red;">Error al engresar un producto</p>');

                    } else {
                        var info = JSON.parse(response);
                        $('.row' + info.producto_id + '.celPrecio').html(info.nuevo_precio);
                        $('row' + info.producto_id + '.celExistencia').html(info.nueva_existencia);
                        $('#txtCantidad').val('');
                        $('#txtPrecio').val('');
                        $('.alertAddProducto').html('<p>Se guardo correctamente el producto.</p>');

                    }

                },

                error: function () {
                    console.log(error);
                }

            });
        }
        //Funcion Eliminar Producto
        function delProduct() {
            var pr = $("#producto_id").val();
            $('.alertAddProducto').html('');


            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: $('#form_del_product').serialize(),

                success: function (response) {
                    console.log(response);
                    if (response == 'error') {
                        $('.alertAddProducto').html('<p style="color:red;">Error al eliminar un producto</p>');

                    } else {

                        $('.row' + pr).remove();
                        $("#form_del_product .btn_ok").remove();

                        $('.alertAddProducto').html('<p>Producto Eliminado.</p>');

                    }

                },

                error: function () {
                    console.log(error);
                }

            });
        }


        $('#closeModal').click(function (e) {
            // e.preventDefault();
            $('.alertAddProducto').html('');
            $('#txtCantidad').val('');
            $('#txtPrecio').val('');
            $('.modal').fadeOut();
        });
    </script>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>