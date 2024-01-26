<?php

     //Control de url de Administrador
     session_start();
    //  if($_SESSION['rol'] != 1){
    //     header("location: ./");
    //  }

    include "../conexion.php";

    if(!empty($_POST)){
        $alert='';
        $idUsuario='';
        if(empty($_POST['nombre']) || empty($_POST['direccion']) || empty($_POST['telefono']) ){

            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{

            // $iduser=$_POST['iduser'];
            $idCliente=$_POST['id'];
            $cedula=$_POST['cedula'];
            $nombre=$_POST['nombre'];
            $telefono=$_POST['telefono'];
            $direccion=$_POST['direccion'];

            $query= mysqli_query($conection,"SELECT * FROM cliente 
            WHERE (cedula= '$cedula' AND idcliente != $idCliente)");
          
            $result =mysqli_fetch_array($query);

           

                $sql_update=mysqli_query($conection, "UPDATE cliente 
                SET nombre='$nombre', telefono='$telefono', direccion ='$direccion' WHERE idcliente= $idCliente");
                if($sql_update){
                    $alert='<p class="msg_save">Cliente actualizado correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al actualizar cliente.</p>';
                }
            // }
        }
            // mysqli_close($conection);

    }
    
    // Mostrar Datos
    if(empty($_REQUEST['id'])){
        header('location: lista_cliente.php');
        mysqli_close($conection);


    }

    $idcliente=$_REQUEST['id'];

    $sql=mysqli_query($conection, "SELECT * FROM cliente where idcliente=$idcliente and estatus= 1");
                
     mysqli_close($conection);

    $result_sql= mysqli_num_rows($sql);

    if($result_sql==0){
         header('location: lista_cliente.php');

    }else{
        $option='';
        while($data= mysqli_fetch_array($sql)){
            $idcliente=$data['idcliente'];
            $cedula=$data['cedula'];
            $nombre=$data['nombre'];
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
    <title>Actualizar Cliente</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1>Actualizar Cliente</h1>
            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post">

                <input type="hidden" name="id" value="<?php echo $idcliente ?> ">

                <label for="cedula">Cedula</label>
                <!-- <input type="text" name="cedula" id="cedula" placeholder="Numero de Cedula" require>  -->
                <!-- <input type="text" name="cedula" id="cedula" placeholder="Numero de Cedula" maxlength="10" value="<php echo $cedula ?> "required> -->
                <input type="text" name="cedula" id="cedula" placeholder="Numero de Cedula" maxlength="10"
                    value="<?php echo $cedula ?>" readonly>

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo"
                    value="<?php echo $nombre ?> " require>


                <label for="telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Telefono"
                    value="<?php echo $telefono ?> ">

                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa"
                    value="<?php echo $direccion ?> ">

                <input type="submit" value="Guardar Cliente" class="btn_save">
            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>