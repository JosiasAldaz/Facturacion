<?php
  //  session_start();

    if(empty($_SESSION['active'])){

        header ('location: ../');

    }

?>

<header>
    <div class="header">

    <h1>COORDENADA  </h1>
        <div class="optionsBar">
            <p>Cuenca, <?php echo fechaC(); ?></p>
            <!-- <p>Ecuador, 2002</p> -->
            <span>|</span>

            <span class="user">
                <?php 

                if($_SESSION['rol']==1){
                    echo $_SESSION['user'].' | '.' Administrador';  
                }elseif($_SESSION['rol']==2){
                    echo $_SESSION['user'].' | '.' Supervisor';  
                }elseif($_SESSION['rol']==3){
                    echo $_SESSION['user'].' | '.' Vendedor';  
                }
            ?>
            </span>

            <!-- <span class="user"> <?php echo $_SESSION['rol'];  ?></span> -->
            <!-- <span class="user"> Ibarra</span> -->
            <img class="photouser" src="img/apecs.png" alt="Usuario" style="border-radius: 10px">
            <a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
        </div>
    </div>
    <?php include_once "nav.php" ?>

    
</header>

<div class="modal" id="modal">
    <did class="bodyModal">
    </did>
</div>

