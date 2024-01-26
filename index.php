<?php

    $alert='';
    session_start();

    if(!empty($_SESSION['active'])){
        header ('location: sistema/');
    }else{  
            if((empty($_POST['usuario']) || (empty($_POST['clave'])))){
                $alert = 'Debe ingresar un usuario y clave';
            }else{
                require_once "conexion.php";
                $usuario = mysqli_real_escape_string($conection, $_POST['usuario']);
                $contra = mysqli_real_escape_string($conection, $_POST['clave']);
                
                $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$usuario' AND clave = '$contra'");
                $result = mysqli_fetch_assoc($query);
                mysqli_close($conection);
                if ($result) {
                    $_SESSION['active'] = true;
                    $_SESSION['idUser'] = $result['idusuario'];
                    $_SESSION['nombre'] = $result['nombre'];   
                    $_SESSION['email'] = $result['correo'];
                    $_SESSION['user'] = $result['usuario'];
                    $_SESSION['rol'] = $result['rol'];
                    header('location: sistema/');
                } else {
                    $alert = 'Usuario o clave incorrectos';   
                    session_destroy();
                }
            }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistema Facturacion</title>
    <link rel="stylesheet" type="text/css" href="Css/style.css">
    
</head>

<body>

    <section id="container"  style="background: #1E30A0;">
        <form action = "" method="post" style="border-radius: 5px">
            <h3 style="background: #1E30A0;">Iniciar Sección</h3>
            <img src="img/apecs.png" alt="">
            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="clave" placeholder="Password">
            <div class="alert">
                <?php echo isset($alert) ? $alert:'';?>
            </div>
            <input type="submit" value="Ingresar" style="background: #1E30A0;">
        </form>
    </section>
</body>

</html>