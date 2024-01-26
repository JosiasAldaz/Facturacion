<?php

    //Control de url de Administrador
    session_start();
    

    include "../conexion.php";

    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['placa']) || empty($_POST['anio']) || empty($_POST['modelo'])   ){
            $alert='<p class="msg_error">Los campos seleccionados son obligatorios.</p>';
        }else{
            $placa=$_POST['placa'];
            $modelo=$_POST['modelo'];
            $anio=$_POST['anio'];
            $num_gps=$_POST['num_gps'];
            $num_sim=$_POST['num_sim'];
            $cilindraje=$_POST['cilindraje'];
            $kilometraje=$_POST['kilometraje'];
            $result=0;

            /* if($placa != null){
                $query=mysqli_query($conection, "SELECT * FROM vechiculo WHERE placa='$placa'");
                if ($query) {
                    $result = mysqli_fetch_array($query);
                } else {
                }  
            } */

            if($result > 0){
                $alert='<p class="msg_error">El numero de placa ya Existe.</p>';
            }else {
                $query_insert = mysqli_query($conection, "INSERT INTO `vehiculo`(`placa`, `modelo`, `anio`, `num_gps`, `num_sim`, `cilindraje`, `kilometraje`) 
                                VALUES ('$placa','$modelo','$anio','$num_gps','$num_sim', '$cilindraje', '$kilometraje')");
            
                if ($query_insert) {
                    $alert = '<p class="msg_save">Vehiculo creado correctamente.</p>';
                } else {
                    $error_message = mysqli_error($conection); // Obtener el mensaje de error
                    $alert = '<p class="msg_error">Error al crear un Vehiculo. Detalles: ' . $error_message . '</p>';
                }
            }            
        }
        mysqli_close($conection);
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php"; ?>
    <title>Vehiculo</title>

    <script>
        function validarFormulario() {
            var anio = document.getElementById('anio').value;
            var kilometraje = document.getElementById('kilometraje').value;

            if (isNaN(anio) || isNaN(kilometraje)) {
                alert('Año y kilometraje deben ser números.');
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <?php include_once "includes/header.php"; ?>
    <section id="container">

        <div class="form_register">
        <h1 style="color: #1E30A0;"> 
            <i class="fa-solid fa fa-car"></i> Registro Vehiculo</h1>            <hr>

            <div class="alert"> <?php echo isset($alert) ? $alert : '';  ?></div>
            <form action="" method="post" onsubmit="return validarFormulario();">
                
                <label for="placa">Placa</label>
                <input type="text" name="placa" id="placa" placeholder="Placa" maxlength="10" required> 

                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" id="modelo" placeholder="Modelo del Vehiculo" required>

                <label for="anio">Año</label>
                <input type="text" name="anio" id="anio" placeholder="Año" required>

                <label for="num_gps">Numero de gps</label>
                <input type="text" name="num_gps" id="num_gps" placeholder="Numero de gps">

                <label for="num_sim">Numero de sim</label>
                <input type="text" name="num_sim" id="num_sim" placeholder="Numero de sim">

                <label for="cilindraje">Cilindraje</label>
                <input type="text" name="cilindraje" id="cilindraje" placeholder="Cilindraje">

                <label for="kilometraje">Kilometraje</label>
                <input type="text" name="kilometraje" id="kilometraje" placeholder="Kilometraje">

                <input type="submit" value="Guardar Vehiculo" class="btn_save">

                <a href="nuevo_contrato.php" class="btn_ok textcenter" style="background-color: red" id="btn_anular_venta">
                            <i class="fas fa-ban"></i> Volver
                </a>
            </form>

        </div>

    </section>

    <?php include_once "includes/footer.php"; ?>

</body>

</html>