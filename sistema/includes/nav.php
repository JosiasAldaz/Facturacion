<nav>
            <ul>
                <li><a href="../"> <i class="fa-solid fa-house-chimney" style="color: #ffff;"></i>  Inicio</a></li>
                <?php  if($_SESSION['rol']== 1){?>

                <li class="principal">
                    <a href="#"><i class="fa-solid fa-users" style="color: #ffff;"></i>  Usuarios</a>
                    <ul>
                        <li><a href="registro_usuario.php"><i class="fa-solid fa-user-plus" style="color: #ffff;"></i>  Nuevo Usuario</a></li>
                        <li><a href="lista_usuario.php"> <i class="fa-solid fa-users" style="color: #ffff;"></i>  Lista de Usuarios</a></li>
                    </ul>
                </li>
                <?php }?>
                <li class="principal">

                    <a href="#"><i class="fa-solid fa-user-plus" style="color: #ffff;"></i> Clientes</a>
                    <ul>
                        <li><a href="registro_cliente.php"><i class="fa-solid fa-user-plus" style="color: #ffff;"></i> Nuevo Cliente</a></li>
                        <li><a href="lista_cliente.php"><i class="fa-solid fa-users" style="color: #ffff;"></i> Clientes</a></li>
                    </ul>
                </li>

                <?php  if($_SESSION['rol']== 1 || $_SESSION['rol']== 2 ) {?>

                <li class="principal">
                    <a href="#"><i class="far fa-building"></i> Proveedores</a>
                    <ul>
                        <li><a href="registro_proveedor.php"><i class="fa-solid fa-circle-plus " style="color: #ffff;"></i> Nuevo Proveedor</a></li>
                        <li><a href="lista_proveedor.php"> <i class="far fa-building" style="color: #ffff;"></i> Proveedores</a></li>
                    </ul>
                </li>
                <?php }?>

                <li class="principal">
                    <a href="#"><i class="fa-solid fa-box"></i> Productos</a>
                    <ul>
                    <?php  if($_SESSION['rol']== 1 || $_SESSION['rol']== 2 ) {?>

                        <li><a href="registro_producto.php"><i class="fa-solid fa-circle-plus " style="color: #ffff;"></i> Nuevo Producto</a></li>
                        <?php }?>

                        <li><a href="lista_producto.php"><i class="fa-solid fa-box"></i> Bodega</a></li>
                    </ul>
                </li>

                <li class="principal">
                    <a href="#"><i class="fa-solid fa-cash-register"></i> Facturas</a>
                    <ul>
                        <li><a href="nueva_venta.php"><i class="fa-solid fa-cash-register"></i> Nueva Venta</a> </li>
                        <li><a href="ventas.php"><i class="fa-solid fa-print"></i> Ventas</a></li>
                    </ul>
                </li>
                <li class="principal">
                    <a href="#"><i class="fa-solid fa-cash-register"></i> Contratos</a>
                    <ul>
                        <li><a href="nuevo_contrato.php"><i class="fa-solid fa-circle-plus"></i> Nuevo contrato</a> </li>
                        <li><a href="lista_contratos.php"><i class="fa-solid fa-print"></i> Lista de contratos</a></li>
                    </ul>
                </li>
            </ul>
        </nav>