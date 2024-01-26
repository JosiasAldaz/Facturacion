<?php

    //Control de url de Administrador
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }
    include_once "../conexion.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php" ?>
    <title>Lista de usuarios</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">
        <h1><i class="fa-solid fa-users"></i> Lista de Usuario</h1>
        <a href="registro_usuario.php" class="btn_new" style="background-color: #1E30A0;"><i class="fa-solid fa-user-plus" style="color: #ffff;"></i> Crear usuario</a>

        <form action="buscar_usuario.php" method="get" class="form_search"/>
            <input class="input_b" type="text" name= "busqueda" id="busqueda" placeholder="Buscar" />
            <!-- <input type="submit" value="Buscar" class="btn_search"></input> -->
            <button type="submit" class="btn_search"  ><div style="margin:5px" ><i class="fa-solid fa-magnifying-glass" style="color: #ffffff;" ></i></div></button>
        </form>


        <br>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php

                $query_paginador=mysqli_query($conection, "SELECT count(*) as total_registro FROM usuario where estatus= 1");
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

                $query=mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol
                WHERE estatus=1 ORDER BY u.idusuario ASC LIMIT $desde, $por_pagina");

                mysqli_close($conection);


                $result=mysqli_num_rows($query);

                if($result>0){
                    while($data= mysqli_fetch_array($query)){
            ?>

            <tr>
                <td><?php echo $data['idusuario']; ?></td>
                <td><?php echo $data['nombre']; ?></td>
                <td><?php echo $data['correo']; ?></td>
                <td><?php echo $data['usuario']; ?></td>
                <td><?php echo $data['rol']; ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?php echo $data['idusuario']; ?>" class="link_edit">
                    <i class="fa-regular fa-pen-to-square" style="color: #326df5;"></i> Editar</a>

                    <?php

                    if($data['idusuario']!=17) { 
                    ?>
                    |
                    <a href="eliminar_confirmar_usuario.php?id=<?php echo $data['idusuario']; ?>"
                        class="link_delete"><i class="fa-solid fa-trash" style="color: #d70909;"></i> Eliminar</a>
                    <?php } ?>
                    <!-- <a href="" class="link_delete">Eliminar</a> -->
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
                <li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i> </a>
                </li>
                <li><a href="?pagina=<?php echo $pagina-1; ?>">
                <i class="fa-solid fa-caret-left fa-xm"></i> </a>
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

                <!-- <li<a href=""><<</li>  -->
                <!-- <li><a href="" class="pageSelected">1</a></li> -->

                <li><a href="?pagina=<?php echo $pagina+1; ?>"><i class="fa-solid fa-caret-right fa-xm"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fa-solid fa-forward-step"></i></a></li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <?php include_once "includes/footer.php"; ?>
</body>

</html>