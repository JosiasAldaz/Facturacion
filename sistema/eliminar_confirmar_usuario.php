<?php

    //Control de url de Administrador
    session_start();
    if($_SESSION['rol'] != 1){
       header("location: ./");
    }

    include "../conexion.php";

    if(!empty($_POST)){
        if($_POST['idusuario']==1){
            header('location: lista_usuario.php');
            mysqli_close($conection);
            exit;
        }
        $idusuario=$_POST['idusuario'];

        //$query_delete=mysqli_query($conection, "DELETE FROM usuario WHERE idusuario= '$idusuario'");
            $query_delete=mysqli_query($conection,"UPDATE usuario SET estatus = 0 WHERE  idusuario = $idusuario");
            
                mysqli_close($conection);

            if($query_delete){
                header('location: lista_usuario.php');
            }else{
                echo "Error al eliminar";
            }
    }

    if(empty($_REQUEST['id']) || $_REQUEST['id']==1 ){

        header("location: lista_usuario.php");
        mysqli_close($conection);

    }else{

        $idusuario= $_REQUEST['id'];
        $query= mysqli_query($conection,"SELECT u.nombre,
        u.usuario, r.rol FROM usuario u INNER JOIN rol r
        ON u.rol = r.idrol WHERE u.idusuario ='$idusuario' ");

       mysqli_close($conection);

        $result = mysqli_num_rows($query);

        if($result>0){
            while($data= mysqli_fetch_array($query)){
                $nombre=$data['nombre'];
                $usuario=$data['usuario'];
                $rol=$data['rol'];
                
            }
        }else{
            header('location: lista_usuario.php');
        }
    }
    

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	<?php include_once "includes/scripts.php" ?>
    <title>Eliminar Usuario</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">
        <!-- <h1>Eliminar Usuario</h1> -->
        <div class="data_delete "  >
        <i class="fa-solid fa-user-xmark fa-7x" style="color: #ca5574" ></i>
            <br><br>    
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>Usuario: <span><?php echo $usuario; ?></span></p>
            <p>Tipo Usuario: <span><?php echo $rol; ?></span></p>

            <form action="" method="post">
                <input type="hidden" name="idusuario" value="<?php echo $idusuario ?>">
                <a href="lista_usuario.php" class="btn_cancel">
                <i class="fa-solid fa-ban"></i> Cancelar</a>
                <!-- <input type="submit" value="Aceptar" class="btn_ok"> -->
                <!-- <i class="fa-regular fa-ban" style="color: #ea281a;"></i> -->
                <button type="submit" class="btn_ok"> <i class="fa-solid fa-trash-can"></i> Eliminar</button> 

            </form>

        </div>
    </section>

    <?php include_once "includes/footer.php"; ?>

</body>
</html>