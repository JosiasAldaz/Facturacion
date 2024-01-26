<?php

//Control de url de Administrador
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {
    $alert = '';
    if (
        empty($_POST['proveedor']) || empty($_POST['producto']) ||
        empty($_POST['precio']) || empty($_POST['cantidad'])
    ) {

        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {

        $proveedor = $_POST['proveedor'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $usuario_id = $_SESSION['idUser'];

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type = $foto['type'];
        $url_temp = $foto['tmp_name'];

        //  $imagenProducto='img_producto.jpg';

        // if($nombre_foto != ''){
        //     $destino='img/uploads/';
        //     $img_nombre='img_'.md5(date('d-m-Y H:m:s'));
        //     $imgProducto= $img_nombre.'.jpg';
        //     $src=$destino.$imgProducto;
        //     move_uploaded_file($url_temp, $src); // se mueve la imagen de la ubicación temporal a la de destino
        // }


        //     $query_insert=mysqli_query($conection, "INSERT INTO `producto`(`proveedor`, `descripcion`, `precio`, `existencia`, `usuario_id`, `foto`) 
        //     VALUES ('$proveedor','$producto','$precio','$cantidad','$usuario_id','$imgProducto')");


        $imagenProducto = 'img_producto.jpg';
        $imagenPredeterminada = 'img_producto.jpg';

        if ($nombre_foto != '') {
            $destino = 'img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
            move_uploaded_file($url_temp, $src); // se mueve la imagen de la ubicación temporal a la de destino
        } else {
            $imgProducto = $imagenPredeterminada;
        }
        // if ($nombre_foto != '') {
        //     $destino = 'img/uploads/';
        //     $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
        //     $imgProducto = $img_nombre.'.jpg';
        //     $src = $url_temp;
        //     $dest = $destino.$imgProducto;
        //     file_put_contents($dest, file_get_contents($src)); // descargar y guardar la imagen directamente en la ubicación de destino
        // } else {
        //     $imgProducto = $imagenPredeterminada;
        // }


        $query_insert = mysqli_query($conection, "INSERT INTO `producto`(`proveedor`, `descripcion`, `precio`, `existencia`, `usuario_id`, `foto`) 
                VALUES ('$proveedor','$producto','$precio','$cantidad','$usuario_id','$imgProducto')");

        if ($query_insert) {
            $alert = '<p class="msg_save">Producto creado correctamente.</p>';
        } else {
            $alert = '<p class="msg_error">Error al crear un Producto.</p>';
        }
        // }
    }
    // mysqli_close($conection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Registro Producto</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 style="color: #1E30A0;"><i class="fa-solid fa-box"></i> Registro Producto</h1>
            <hr>

            <div class="alert">
                <?php echo isset($alert) ? $alert : ''; ?>
            </div>
            <form action="" method="post" enctype="multipart/form-data">

                <label for="proveedor">Proveedor</label>
                <?php

                $query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus= 1 ORDER BY proveedor ASC");
                $result_proveedor = mysqli_num_rows($query_proveedor);
                mysqli_close($conection);

                ?>
                <select name="proveedor" id="proveedor">

                    <?php
                    if ($result_proveedor > 0) {
                        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                            ?>
                            <option value="<?php echo $proveedor['codproveedor']; ?>">
                                <?php echo $proveedor['proveedor']; ?>
                            </option>

                        <?php
                        }
                    }
                    ?>


                </select>



                <label for="producto">Producto</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del Producto" require>

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del Producto">

                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad del producto">

                <!-- Code imagen -->
                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i> Crear Producto</button>

            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>