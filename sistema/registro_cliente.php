<?php
    //Control de url de Administrador
    session_start();
    include "../conexion.php";
    include ("../validacion.php");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $cedula_val = ($_POST['cedula']);
    $nombre_val = ($_POST['nombre']);
    $teleofno_val = ($_POST['telefono']);
    $direccion_val = ($_POST['direccion']);
    $alert='';
    $resultadoVal = camposCliente($cedula_val,$nombre_val,$teleofno_val,$direccion_val);

    switch ($resultadoVal) {       
        case "cedula":
            $alert='<p class="msg_error">LA CÉDULA ES OBLIGATORIA</p>';
            break;
        case "nombre":
            $alert='<p class="msg_error">EL NOMBRE ES OBLIGATORIO</p>';
            break;
        case "telefono":
            $alert='<p class="msg_error">EL TELEFONO ES OBLIGATORIO</p>';
            break;
        case "direccion":
            $alert='<p class="msg_error">LA DIRECCION ES OBLIGATORIA</p>';
            break;
        case "correcto":
            if(!is_numeric($cedula_val)){
                $alert='<p class="msg_error">LA CÉDULA DEBE TENER SOLO NÚMEROS</p>';
            }elseif(!ctype_alpha($nombre_val)){
                $alert='<p class="msg_error">EL NOMBRE DEBE TENER SOLO LETRAS</p>';
            }elseif(!ctype_digit($teleofno_val)){
                $alert='<p class="msg_error">EL TELEFONO DEBE TENER SOLO NÚMEROS</p>';
            }elseif(strlen($cedula_val) !== 10 && strlen($cedula_val) !==13){
                $alert='<p class="msg_error">LA CÉDULA DEBE TENER 10 O 13 DÍGITOS</p>';
            }elseif(strlen($teleofno_val) !== 10){
                $alert='<p class="msg_error">EL TELÉFONO DEBE TENER 10 DÍGITOS</p>';
            }else{
            $cedula=$_POST['cedula'];
            $nombre=$_POST['nombre'];
            $telefono=$_POST['telefono'];
            $direccion=$_POST['direccion'];
            $usuario_id=$_SESSION['idUser'];

            $result=0;
            if(is_numeric($cedula) and $cedula !=0){
                $query=mysqli_query($conection, "SELECT * FROM cliente WHERE cedula='$cedula'");
                $result=mysqli_num_rows($query);

            }

            if($result > 0){
                $alert='<p class="msg_error">El numero de la cedula ya Existe.</p>';
            }else{

                $query_insert=mysqli_query($conection, "INSERT INTO `cliente`(`cedula`, `nombre`, `telefono`, `direccion`, `usuario_id`) 
                VALUES ('$cedula','$nombre','$telefono','$direccion','$usuario_id')");

                if($query_insert){
                    $alert='<p class="msg_save">Cliente creado correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al crear un Cliente.</p>';
                }
            }
            mysqli_close($conection);
            }
            break;
    } 
    }
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Registro Cliente</title>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
        <h1 style="color: #1E30A0;"> 
            <i class="fa-solid fa-user-plus"></i> Registro Cliente</h1>            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post">
                
                <label for="cedula">Cedula o RUC</label>
                <!-- <input type="text" name="cedula" id="cedula" placeholder="Numero de Cedula" require>  -->
                <input type="text" name="cedula" id="cedula" placeholder="Numero de Cedula" maxlength="13" required> 

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" require>

                <label for="telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Telefono" require>

                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa" require>
                <input type="submit" value="Guardar Cliente" class="btn_save">
            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>