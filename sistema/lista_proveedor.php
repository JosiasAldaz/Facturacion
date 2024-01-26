<?php

    //Control de url de Administrador
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    }

   
    include_once "../conexion.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php" ?>
    <title>Lista de Proveedores</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">
        <h1><i class="far fa-building"></i> Lista de Proveedores</h1>
        <a href="registro_proveedor.php" class="btn_new"style="background-color: #1E30A0;"><i class="fa-solid fa-circle-plus" style="color: #ffff;"></i> Crear Proveedor</a>

        <form action="buscar_proveedor.php" method="get" class="form_search" />
        <input class="input_b" type="text" name="busqueda" id="busqueda" placeholder="Buscar" />
        <!-- <input type="submit" value="Buscar" class="btn_search"></input>
        <button type="submit" class="btn_search"></button> -->
        <button type="submit" value="Buscar" class="btn_search"><div style="margin:5px" ><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;" ></i></div></button>

        </form>
        <br>
        <table>
            <tr>
                <!-- <th>ID</th>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Direccion</th>
                <th>Acciones</th> -->


                <th>ID</th>
                <th>Proveedor</th>
                <th>Contacto</th>
                <th>Telefono</th>
                <th>Direccion</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php

                $query_paginador=mysqli_query($conection, "SELECT count(*) as total_registro FROM proveedor where estatus= 1");
                $result_paginador=mysqli_fetch_array($query_paginador);
                $total_registro=$result_paginador['total_registro'];
                $por_pagina=5;

                if(empty($_GET['pagina'])){
                    $pagina=1;
                }else{
                    $pagina=$_GET['pagina'];
                }

                $desde= ($pagina-1) * $por_pagina;
                $total_paginas= ceil($total_registro / $por_pagina);

                $query=mysqli_query($conection, "SELECT * FROM proveedor 
                WHERE estatus=1 ORDER BY codproveedor  ASC LIMIT $desde, $por_pagina");

                mysqli_close($conection);

                $result=mysqli_num_rows($query);

                if($result>0){
                    while($data= mysqli_fetch_array($query)){
                        $formato='Y-m-d H:i:s';
                        $fecha=DateTime::createFromFormat($formato, $data["date_add"]);
            ?>

            <tr>
                <!-- <td><php echo $data['idcliente']; ?></td> -->
                <!-- <td><php echo $data['cedula']; ?></td> -->
                <!-- <td><php echo $data['nombre']; ?></td> -->
                <!-- <td><php echo $data['telefono']; ?></td> -->
                <!-- <td><php echo $data['direccion']; ?></td> -->

                <td><?php echo $data['codproveedor']; ?></td>
                <td><?php echo $data['proveedor']; ?></td>
                <td><?php echo $data['contacto']; ?></td>
                <td><?php echo "0".$data['telefono']; ?></td>
                <td><?php echo $data['direccion']; ?></td>
                <td><?php echo $fecha->format('d/m/Y'); ?></td>
                <!-- <td><php echo $data['date_add']; ?></td> -->

                <td>

                    <a href="editar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>" class="link_edit">
                    <i class="fa-regular fa-pen-to-square" style="color: #326df5;"></i> Editar</a> 

                    <!-- <a href="editar_cliente.php">Editar</a> -->
|
                    <a href="eliminar_confirmar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>"
                        class="link_delete"><i class="fa-solid fa-trash" style="color: #d70909;"></i> Eliminar</a>

                </td>
            </tr>
            <?php
                    }
                }

            ?>

        </table>
        <div class="paginador">
            <ul>

                <?php 
                    if($pagina != 1){

                ?>
                <li><a href="?pagina=<?php echo 1; ?>">|< </a>
                </li>
                <li><a href="?pagina=<?php echo $pagina-1; ?>">
                        << </a>
                </li>

                <?php 
                    } 
                 
                    for($i=1; $i <= $total_paginas; $i++){

                        if($i == $pagina){
                            echo '<li class="pageSelected">'.$i.'</> </li>';

                        }else{
                            echo '<li> <a href="?pagina='.$i.'">'.$i.'</a> </li>';

                        }
                    }
                    if($pagina != $total_paginas){

                    
                 ?>

                <li><a href="?pagina=<?php echo $pagina+1; ?>">>></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <?php include_once "includes/footer.php"; ?>
</body>

</html>