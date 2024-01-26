<?php

     //Control de url de Administrador
     session_start();
     if($_SESSION['rol'] != 1){
        header("location: ./");
     }

    include "../conexion.php";

    if(!empty($_POST)){
        $alert='';
        $idUsuario='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])
        || empty($_POST['rol']) ){

            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{

            // $iduser=$_POST['iduser'];
            $iduser=$_POST['id'];
            $nombre=$_POST['nombre'];
            $email=$_POST['correo'];
            $user=$_POST['usuario'];
            $clave=md5($_POST['clave']);
            $rol=$_POST['rol'];

            $query= mysqli_query($conection,"SELECT * FROM usuario 
            WHERE (usuario= '$user' AND idusuario != $iduser)
            OR (correo= '$email' AND idusuario != $iduser)");
          
            $result =mysqli_fetch_array($query);

            if($result>0){
                $alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
            }else{

                if(empty($_POST['clave'])){
                    $sql_update=mysqli_query($conection, "UPDATE usuario 
                    SET nombre='$nombre', correo='$email', usuario 
                    ='$user',  rol ='$rol' WHERE idusuario= '$iduser'");
                }else{
                    $sql_update=mysqli_query($conection, "UPDATE usuario 
                    SET nombre='$nombre', correo='$email', usuario 
                    ='$user', clave='$clave', rol ='$rol' WHERE idusuario= '$iduser'");
                }

                // $query_insert=mysqli_query($conection, "INSERT INTO `usuario`(`nombre`, `correo`, `usuario`, `clave`, `rol`) 
                // VALUES ('$nombre','$email','$user','$clave','$rol')");

                if($sql_update){
                    $alert='<p class="msg_save">Usuario actualizado correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al actualizar usuario.</p>';
                }
            }
        }
            // mysqli_close($conection);

    }
    // Mostrar Datos
    if(empty($_REQUEST['id'])){
        header('location: lista_usuario.php');
        mysqli_close($conection);


    }
    $iduser=$_REQUEST['id'];
    $sql=mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM 
    usuario u INNER JOIN rol r ON u.rol = r.idrol where idusuario='$iduser' and estatus= 1");
                
      mysqli_close($conection);

    $result_sql= mysqli_num_rows($sql);
    if($result_sql==0){
        header('location: lista_usuario.php');

    }else{
        $option='';
        while($data= mysqli_fetch_array($sql)){
            $iduser=$data['idusuario'];
            $nombre=$data['nombre'];
            $correo=$data['correo'];
            $usuario=$data['usuario'];
            $idrol=$data['idrol'];
            $rol=$data['rol'];

            if($idrol==1){
               $option= '<option value="'.$idrol.'" select> '.$rol.' </option>';
            }else if($idrol==2){
                $option= '<option value="'.$idrol.'" select> '.$rol.' </option>';
            }else if($idrol==3){
                $option= '<option value="'.$idrol.'" select> '.$rol.' </option>';
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Actualizar Usuario</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1> <i class="fa-regular fa-pen-to-square" style="color: #326df5;"></i> Actualizar Usuario</h1>
            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post">

                <input type="hidden" name="id" id="idUsuario" value="<?php echo $iduser; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo"
                    value="<?php echo $nombre; ?>">

                <label for="correo">Email</label>
                <input type="text" name="correo" id="correo" placeholder="Email Completo"
                    value="<?php echo $correo; ?>">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">

                <label for="clave">Password</label>
                <input type="password" name="clave" id="clave" placeholder="Password de Acceso">

                <label for="clave">Tipo de Usuario</label>

                <?php 
                
                    include "../conexion.php";
                    $query_rol= mysqli_query($conection, "SELECT * FROM rol");
                    mysqli_close($conection);
                    $result_rol= mysqli_num_rows($query_rol);
                   
                   
                ?>

                <select name="rol" id="rol" class="notItemOne">

                    <?php
                        echo $option;
                        if($result_rol >0){
                            while($rol = mysqli_fetch_array($query_rol)){
                    ?>
                    <option value="<?php echo $rol['idrol']; ?>"><?php echo $rol['rol']; ?></option>
                    <?php

                            }
                        }
                    ?>
                </select>
                <button type="submit" class="btn_save"><i class="fa-regular fa-pen-to-square"
                        style="color: #326df5;"></i> Actualizar usuario</button>
            </form>
        </div>
    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>