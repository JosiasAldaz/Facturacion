<?php

    //Control de url de Administrador
    include "../conexion.php";
    session_start();
    //VARIABLES PARA VALIDAR
    

    if($_SESSION['rol'] != 1){
        header("location: ./");
    }

    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario'])|| empty($_POST['clave']) 
        || empty($_POST['rol']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            $nombre_val=$_POST['nombre'];
            $email_val=$_POST['correo'];
            $user_val=$_POST['usuario'];
            $clave_val=md5($_POST['clave']);
            $rol_val=$_POST['rol'];
            if(!preg_match("/^[a-zA-Z ]+$/", $nombre_val)){
                $alert='<p class="msg_error">EL NOMBRE DEBE TENER SOLO LETRAS</p>';
            }elseif(!filter_var($email_val, FILTER_VALIDATE_EMAIL)){
                $alert='<p class="msg_error">EL CORREO ELECTRÓNICO NO ES VÁLIDO</p>';
            }else{
            $nombre=$_POST['nombre'];
            $email=$_POST['correo'];
            $user=$_POST['usuario'];
            $clave=md5($_POST['clave']);
            $rol=$_POST['rol'];
            $query= mysqli_query($conection,"SELECT * FROM usuario WHERE usuario= '$user' OR correo= '$email'");
            //mysqli_close($conection);
            $result =mysqli_fetch_array($query);
           
            if($result>0){
                $alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
            }else{
                $query_insert=mysqli_query($conection, "INSERT INTO `usuario`(`nombre`, `correo`, `usuario`, `clave`, `rol`) 
                VALUES ('$nombre','$email','$user','$clave','$rol')");

                if($query_insert){
                    $alert='<p class="msg_save">Usuario creado correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al crear un usuario.</p>';
                }
            }
            }
            
        }
    }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Registro Usuario</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
            <h1 style="color: #1E30A0;"> 
            <i class="fa-solid fa-user-plus"></i> Registro Usuario</h1>
            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">

                <label for="correo">Email</label>
                <input type="text" name="correo" id="correo" placeholder="Email Completo">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario">

                <label for="clave">Password</label>
                <input type="password" name="clave" id="clave" placeholder="Password de Acceso">

                <label for="clave">Tipo de Usuario</label>

                <?php 
                
                    $query_rol= mysqli_query($conection, "SELECT * FROM rol");
                    $result_rol= mysqli_num_rows($query_rol);
                   
                   
                ?>

                <select name="rol" id="rol">

                    <?php

                        if($result_rol >0){
                            while($rol = mysqli_fetch_array($query_rol)){
                    ?>
                    <option value="<?php echo $rol['idrol']; ?>"><?php echo $rol['rol']; ?></option>
                    <?php

                            }
                        }
                    ?>

                </select>
                <!-- <input type="submit" value="Crear usuario" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="fa-solid fa-floppy-disk"></i>  Crear Usuario</button>
            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>