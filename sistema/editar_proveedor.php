<?php

     //Control de url de Administrador
     session_start();
     if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    }


    include "../conexion.php";

    if(!empty($_POST)){
        $alert='';
        $idUsuario='';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']) ){

            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{

            // $iduser=$_POST['iduser'];
            // $idCliente=$_POST['id'];
            // $cedula=$_POST['cedula'];
            // $nombre=$_POST['nombre'];
            // $telefono=$_POST['telefono'];
            // $direccion=$_POST['direccion'];

            $codproveedor=$_POST['id'];
            $proveedor=$_POST['proveedor'];
            $contacto=$_POST['contacto'];
            $telefono=$_POST['telefono'];
            $direccion=$_POST['direccion'];

            // $query= mysqli_query($conection,"SELECT * FROM proveedor 
            // WHERE (proveedor= '$proveedor' AND codproveedor != $codproveedor)");
          
 //           $result =mysqli_fetch_array($query);

           

                $sql_update=mysqli_query($conection, "UPDATE proveedor 
                SET proveedor='$proveedor', contacto='$contacto', telefono ='$telefono',
                direccion ='$direccion' WHERE codproveedor= $codproveedor");
                if($sql_update){
                    $alert='<p class="msg_save">Proveedor actualizado correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al actualizar Proveedor.</p>';
                }
            // }
        }
            // mysqli_close($conection);

    }
    
    // Mostrar Datos
    if(empty($_REQUEST['id'])){
        header('location: lista_proveedor.php');
        mysqli_close($conection);


    }

    $idproveedor=$_REQUEST['id'];

    $sql=mysqli_query($conection, "SELECT * FROM proveedor where codproveedor=$idproveedor and estatus= 1");
                
     mysqli_close($conection);

    $result_sql= mysqli_num_rows($sql);

    if($result_sql==0){
        header('location: lista_proveedor.php');

    }else{
        $option='';
        while($data= mysqli_fetch_array($sql)){
            $idproveedor=$data['codproveedor'];
            $proveedor=$data['proveedor'];
            $contacto=$data['contacto'];
            $telefono=$data['telefono'];
            $direccion=$data['direccion'];

           
        }
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Actualizar Proveedor</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1>Actualizar Proveedor</h1>
            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $idproveedor ?>">
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor" 
                value="<?php echo $proveedor ?>" required> 

                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Nombre del Contacto" 
                value="<?php echo $contacto ?>" require>

                <label for="telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Telefono"
                value="<?php echo $telefono ?>">

                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa"
                value="<?php echo $direccion ?>">

                <!-- <input type="submit" value="Guardar Cliente" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i>  Crear Proveedor</button>

            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>