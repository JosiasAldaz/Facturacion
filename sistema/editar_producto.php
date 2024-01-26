<?php

    //Control de url de Administrador
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    }

    include "../conexion.php";

    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) ||
            empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual'])
             || empty($_POST['foto_remove'])   ){

            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            $codproducto=$_POST['id'];
            $proveedor=$_POST['proveedor'];
            $producto=$_POST['producto'];
            $precio=$_POST['precio'];
            $imgProducto=$_POST['foto_actual'];
            $imgremove=$_POST['foto_remove'];
           // $usuario_id=$_SESSION['idUser'];

            $foto=$_FILES['foto'];
            $nombre_foto=$foto['name'];
            $type= $foto['type'];
            $url_temp= $foto['tmp_name'];

            

               // $imagenProducto = 'img_producto.jpg';
               // $imagenPredeterminada = 'img_predeterminada.jpg';
              // $imagenProducto = 'img_predeterminada.jpg';

                $foto= $_FILES['foto'];
                $nombre_foto=$foto['name'];
                $type= $foto['type'];
                $url_temp= $foto['tmp_name'];
                $upd='';

              
                if ($nombre_foto != '') {
                    $destino = 'img/uploads/';
                    $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                    $imgProducto = $img_nombre.'.jpg';
                    $src = $destino.$imgProducto;
                   // $dest = $destino.$imgProducto;
                    //file_put_contents($dest, file_get_contents($src)); // descargar y guardar la imagen directamente en la ubicación de destino
                } else {
                    if($_POST['foto_actual'] != $_POST['foto_remove']){
                        $imgProducto = 'img_predeterminada.jpg';

                    }
                   // $imgProducto = $imagenPredeterminada;
                }
                

                $query_update = mysqli_query($conection, "UPDATE producto set descripcion= '$producto', proveedor= $proveedor, precio = $precio, 
                foto= '$imgProducto' WHERE codproducto= $codproducto");

                if($query_update){

                    if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.jpg')) ||
                    ($_POST['foto_actual'] != $_POST['foto_remove'])){
                        // Arreglar
                          //  unlink('img/uploads/'.$_POST['foto_actual']);
                       
                    }

                    if($nombre_foto != ''){
                        move_uploaded_file($url_temp, $src);
                    }


                    $alert='<p class="msg_save">Producto modificad correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al modificar un Producto.</p>';
                }
            // }
        }
        // mysqli_close($conection);
        

    }
    //Validar Product
    if(empty($_REQUEST['id'])){
        header('location: lista_producto.php');
    }else{
        $id_producto = $_REQUEST['id'];
        if(!is_numeric($id_producto)){
            header('location: lista_producto');
        }
        $query_producto= mysqli_query($conection, "SELECT p.codproducto, p.descripcion, p.precio, p.foto, pr.codproveedor, pr.proveedor FROM producto p
    INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor
    WHERE p.codproducto= '".$id_producto."' AND p.estatus= 1");
    
    $result_product=mysqli_num_rows($query_producto);
        $foto='';
        $classRemove='notBlock';
        if($result_product > 0){
            $data_producto=mysqli_fetch_assoc($query_producto);
            // print_r($data_producto);
            if($data_producto['foto'] != 'img_predeterminada.png'){
                $classRemove ='';
                $foto= '<img id="img" src="img/uploads/'.$data_producto['foto'].'" alt="Producto">';
            }
            
        }else{
            header('location: lista_producto');

        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Editar Producto</title>
    <style>
    option:first-child {
        display: none;
    }
    </style>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1><i class="far fa-building"></i> Editar Producto</h1>
            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo $data_producto['codproducto'] ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">

                <label for="proveedor">Proveedor</label>
                <?php 

                    $query_proveedor=mysqli_query($conection,"SELECT codproveedor, proveedor FROM proveedor WHERE estatus= 1 ORDER BY proveedor ASC");
                    $result_proveedor=mysqli_num_rows($query_proveedor);
                    mysqli_close($conection);

                ?>
                <select name="proveedor" id="proveedor" class="notItemOne">
                    <option value=" <?php echo $data_producto['codproveedor']; ?>">
                        <?php echo $data_producto['proveedor']; ?></option>

                    <?php 
                        if($result_proveedor>0){
                            while($proveedor= mysqli_fetch_array($query_proveedor )){

                            
                        
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
                <input type="text" name="producto" id="producto" placeholder="Nombre del Producto"
                    value="<?php echo $data_producto['descripcion']; ?>" require>

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del Producto"
                    value="<?php echo $data_producto['precio']; ?>">

                <!-- Code imagen -->
                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <!-- <span class="delPhoto  <?php echo $classRemove; ?>">X</span> -->
                        <label for="foto"></label>
                        <?php echo $foto; ?>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i>Actualizar
                    Producto</button>

            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>
    <script>
    $('.delPhoto').click(function() {
        $('#foto').val('');
        $(".delPhoto").addClass('notBlock');
        $("#img").remove();

        if ($("#foto_actual") && $("#foto_remove")) {
            $("#foto_remove").val('img_predeterminada.png');
        }

    });
    </script>

</body>

</html>