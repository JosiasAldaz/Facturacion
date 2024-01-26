<?php

    //Control de url de Administrador
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
     }
 

    include "../conexion.php";

    if(!empty($_POST)){ 
       
        if(empty($_POST['codproveedor'])){
            // if(empty($_POST['idcliente'])){
            header('location: lista_proveedor.php');
            mysqli_close($conection);

        }

        // $idcliente=$_POST['idcliente'];
        $idproveedor=$_POST['codproveedor'];

            $query_delete=mysqli_query($conection,"UPDATE proveedor SET estatus = 0 WHERE  codproveedor   = $idproveedor");
            
                mysqli_close($conection);

            if($query_delete){
                header('location: lista_proveedor.php');
            }else{
                echo "Error al eliminar";
            }
    }

    if(empty($_REQUEST['id']) ){

       header("location: lista_proveedor.php");
       mysqli_close($conection);

    }else{
        //No cambiar este Id
        // $idcliente = $_REQUEST['id'];
        $idproveedor = $_REQUEST['id'];
        $query= mysqli_query($conection,"SELECT * FROM proveedor WHERE codproveedor ='$idproveedor' and estatus=1");

       mysqli_close($conection);

        $result = mysqli_num_rows($query);

        if($result>0){
            while($data= mysqli_fetch_array($query)){
                $proveedor=$data['proveedor'];
              //  $contacto=$data['contacto'];

              
                
                
            }
        }else{
            header('location: lista_proveedor.php');
        }
    }
    

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php" ?>
    <title>Eliminar Proveedores</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">

    <div class="data_delete">
        
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Proveedor: <span><?php echo $proveedor; ?></span></p>
            <!-- <p>Contacto del Proveedor: <span><php echo $contacto; ?></span></p> -->

            <form action="" method="post">
                <input type="hidden" name="codproveedor" value="<?php echo $idproveedor ?>">
                <a href="lista_cliente.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Eliminar" class="btn_ok">
            </form>

        </div>
    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>