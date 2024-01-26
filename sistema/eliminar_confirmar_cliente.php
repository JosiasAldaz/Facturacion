<?php

//Control de url de Administrador
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
}


include "../conexion.php";

if (!empty($_POST)) {

    if (empty($_POST['idcliente'])) {
        header('location: lista_cliente.php');
        mysqli_close($conection);

    }

    $idcliente = $_POST['idcliente'];

    //$query_delete=mysqli_query($conection, "DELETE FROM usuario WHERE idusuario= '$idusuario'");
    $query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE  idcliente   = $idcliente");

    mysqli_close($conection);

    if ($query_delete) {
        header('location: lista_cliente.php');
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])) {

    header("location: lista_usuario.php");
    mysqli_close($conection);

} else {

    $idcliente = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente ='$idcliente' ");

    mysqli_close($conection);

    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $cedula = $data['cedula'];
            $nombre = $data['nombre'];
            // $direccion=$data['direccion'];
            // $telefono=$data['telefono'];
            // $rol=$data['rol'];

        }
    } else {
        header('location: lista_cliente.php');
    }
}



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php" ?>
    <title>Eliminar Cliente</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">

        <div class="data_delete">

            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Cedula del Cliente: <span>
                    <?php echo $cedula; ?>
                </span></p>
            <p>Nombre del Cliente: <span>
                    <?php echo $nombre; ?>
                </span></p>

            <form action="" method="post">
                <input type="hidden" name="idcliente" value="<?php echo $idcliente ?>">
                <a href="lista_cliente.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Eliminar" class="btn_ok">
                <!-- Ejemplo en el archivo donde se muestra la lista de clientes -->
                <button class="btn_ok" onclick="eliminarCliente(<?php echo $idcliente; ?>)">Eliminar Cliente</button>

            </form>

        </div>
    </section>

    <?php include_once "includes/footer.php"; ?>

    <script>

        function eliminarCliente(idcliente) {
            var confirmar = confirm("¿Está seguro de eliminar este cliente?");

            if (confirmar) {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: { action: "eliminarCliente", idcliente: idcliente },
                    dataType: "JSON",
                    success: function (response) {
                        if (response == "ok") {
                            alert("Cliente eliminado correctamente");
                            // Puedes redirigir a la página de lista_cliente.php o realizar otras acciones según tu flujo
                            window.location.href = "lista_cliente.php";
                        } else {
                            alert("Error al eliminar el cliente");
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        }


    </script>

</body>

</html>