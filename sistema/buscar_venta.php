<?php

    //Control de url de Administrador
    session_start();
   
    include_once "../conexion.php";

    $busqueda='';
    $fecha_de='';
    $fecha_a='';
   

    if(isset($_REQUEST['busqueda']) && $_REQUEST['busqueda']== ''){
        header("location: ventas.php");
    }

    if(isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])){
        if($_REQUEST['fecha_de']== '' || $_REQUEST['fecha_a']== ''){
            header("location: ventas.php");
        }   
    }

    if(!empty($_REQUEST['busqueda'])){
        if(!is_numeric($_REQUEST['busqueda'])){
            header("location: ventas.php");
        }
        $busqueda= strtolower($_REQUEST['busqueda']);
        $where="nofactura= $busqueda";
        $buscar="busqueda= $busqueda";
    }
   

    if(!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])){
        $fecha_de= $_REQUEST['fecha_de'];
        $fecha_a= $_REQUEST['fecha_a'];

        $buscar='';

        if($fecha_de == $fecha_a){
            header("location: ventas.php");
        }else if($fecha_de <= $fecha_a){
            $where="fecha LIKE '%$fecha_de%'";
            $buscar="fecha_de= $fecha_de&fecha_a=$fecha_a";
        }else{
            $f_de= $fecha_de. '00:00:00';
            $f_a= $fecha_a. '23:59:59';
            $where="fecha BETWEEN  '$f_de%' AND '$f_a'";
            $buscar="fecha_de= $fecha_de&fecha_a=$fecha_a";
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once "includes/scripts.php" ?>
    <title>Lista de Ventas</title>
</head>

<body>
    <?php include_once "includes/header.php" ?>
    <section id="container">
        <h1>Listas de Ventas</h1>
        <a href="nueva_venta.php" class="btn_new"><i class="fa-solid fa-plus" style="color: #ffff;"></i> Nueva
            Venta</a>

        <form action="buscar_venta.php" method="get" class="form_search">
            <input class="input_b" type="text" name="busqueda" id="busqueda" placeholder="No. Factura" value="<?php echo $busqueda; ?>"/>
            <input type="submit" value="Buscar" class="btn_search"></input>
            <!-- <button type="submit" class="btn_search"></button> -->
        </form>

        <div class="form_container">
            <form action="buscar_venta.php" method="get" class="form_search_date">
                <label>De:</label>
                <input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>" require style="display: inline-block;">
                <label>A:</label>
                <input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>"  require style="display: inline-block;">
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <br>
        <table>
            <tr>
                <th>No.</th>
                <th>Fecha/ Hora</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Estado</th>
                <th class="textright">Total Factura</th>
                <th></th>
            </tr>
            <?php

                $query_paginador=mysqli_query($conection, "SELECT count(*) as total_registro FROM factura where $where");
                $result_paginador=mysqli_fetch_array($query_paginador);
                $total_registro=$result_paginador['total_registro'];
                $por_pagina=15;

                if(empty($_GET['pagina'])){
                    $pagina=1;
                }else{
                    $pagina=$_GET['pagina'];
                }

                $desde= ($pagina-1) * $por_pagina;
                $total_paginas= ceil($total_registro / $por_pagina);

             
                $query=mysqli_query($conection, "SELECT f.nofactura, f.fecha, f.totalfactura, f.codcliente, f.estatus,
                u.nombre as vendedor,
                cl.nombre as cliente
                FROM factura f
                INNER JOIN usuario u
                ON f.usuario = u.idusuario
                INNER JOIN cliente cl
                ON f.codcliente=cl.idcliente
                WHERE $where AND f.estatus != 10 
                ORDER BY f.fecha DESC LIMIT $desde,$por_pagina");

                mysqli_close($conection);


                $result=mysqli_num_rows($query);

                if($result>0){
                    while($data= mysqli_fetch_array($query)){
                        if($data["estatus"]==1){
                            $estado= '<span class="pagada">Pagada</span>';
                        }else{
                            $estado= '<span class="anulada">Anulada</span>';
                        }
            ?>

            <tr id="row_<?php echo $data['nofactura']; ?>">
                <td><?php echo $data['nofactura']; ?></td>
                <td><?php echo $data['fecha']; ?></td>
                <td><?php echo $data['cliente']; ?></td>
                <td><?php echo $data['vendedor']; ?></td>
                <td class="estado"><?php echo $estado; ?></td>
                <td class="textright totalfactura"><span></span><?php echo "$.".$data['totalfactura']; ?></td>
                <td>
                    <div class="div_acciones">
                        <div>
                            <button class="btn_view view_factura" type="button" cl="<?php echo $data["codcliente"]; ?>"
                                f="<?php echo $data['nofactura']; ?>"><i class="fas fa-eye"></i></button>
                        </div>

                        <?php 
                        if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
                            if($data["estatus"] == 1){ 
                        ?>
                        <div class="div_factura">
                            <button class="btn_anular anular_factura" fac="<?php echo $data["nofactura"]; ?>"><i
                                    class="fas fa-ban"></i></button>
                        </div>
                        <?php 
                        }else{  
                    ?>
                        <div class="div_factura">
                            <button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
                        </div>
                        <?php 
    } 
}
?>
                    </div>
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
                <li><a href="?pagina=<?php echo 1; ?>"> </a>
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

    <script>
    function mostrarModal() {
        $('.modal').fadeIn();
    }
    // Modal para anular factura
    $('.anular_factura').click(function(e) {
        e.preventDefault();
        var nofactura = $(this).attr('fac');
        var action = 'infoFactura';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {
                action: action,
                nofactura: nofactura
            },

            success: function(response) {
                if (response != 'error') {
                    var info = JSON.parse(response);

                    $('.bodyModal').html(
                        '<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">' +
                        '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i> <br>Anular Factura</h1>' +

                        '<h2>¿Estas seguro de Anular la Factura?</h2>' +

                        '<p> <strong>NumFactura: ' + info.nofactura + '</strong></p>' +
                        '<p> <strong>Total: $' + info.totalfactura + '</strong></p>' +
                        '<p> <strong>Fecha: ' + info.fecha + '</strong></p>' +

                        '<input type="hidden" name="action" value="anularFactura">' +
                        '<input type="hidden" name="no_factura" id="no_factura" value="' + info
                        .nofactura + '" require>' +
                        '<div class="alert alertAddProducto"></div>' +
                        '<input type="submit" value="Anular" class="btn_ok">' +
                        '<a href="" class="btn_cancel closeModal" id="closeModal">Cerrar</a>' +
                        '</form>'
                    );
                }
                // console.log(response);
            },

            error: function() {
                console.log(error);

            }
        });
        mostrarModal();
    });


    function anularFactura() {
        var noFactura = $('#no_factura').val();

        var action = 'anularFactura';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {
                action: action,
                noFactura: noFactura
            },

            success: function(response) {
                console.log(response);
                if (response == 'error') {
                    $('alertAddProducto').html('<p style="color: red;">Error al anular la factura</p>');
                } else {
                    $('#row_' + noFactura + '.estado').html('<span class="anulada">Anulada</span>');
                    $('#form_anular_factura .btn_ok').remove();
                    $('#row_' + noFactura + '.div_factura').html('<span class="anulada">Anulada</span>');
                    $('#row_' + noFactura + '.estado').html(
                        '<button type="button" class="btn_anular inactive"><i calss="fas fa-ban"></i></button>'
                    );
                    $('.alertAddProducto').html('<p>Factura anulada.</p>');
                }
            },

            error: function(error) {}
        });
    }

    $('.view_factura').click(function(e) {

        e.preventDefault();
        var codCliente = $(this).attr('cl');
        var codFactura = $(this).attr('f');
        generarPDF(codCliente, codFactura);

    });

    function generarPDF(cliente, factura) {
        var ancho = 1000;
        var alto = 800;
        //Calcular posicion x,y para centrar la venta
        var x = parseInt((window.screen.width / 2) - (ancho / 2));
        var y = parseInt((window.screen.height / 2) - (alto / 2));

        var url = 'factura/generaFactura.php?cl=' + cliente + '&f=' + factura;
        window.open(url, "Factura", "left=" + x + ",top=" + y + ",height=" + alto + ",width=" + ancho +
            ",scrollbars=si,location=no,resizable=si,menubar=no");
    }



    $('#closeModal').click(function(e) {
        // e.preventDefault();
        $('.alertAddProducto').html('');
        $('#txtCantidad').val('');
        $('#txtPrecio').val('');
        $('.modal').fadeOut();
    });
    </script>

    <?php include_once "includes/footer.php"; ?>
</body>

</html>