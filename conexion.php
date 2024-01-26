<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'po';
$conection = @mysqli_connect($host, $user, $password, $db);
if (!$conection) {
    echo "Error en la base de datos";

    // }else{
    //     echo "Exito en la base de datos";

}
$fecha_actual = date("Y-m-d");

// Actualiza el estado de los contratos según las condiciones
$sql = "UPDATE contrato SET estado = CASE
            WHEN fecha_inicio <= '$fecha_actual' AND fecha_fin >= '$fecha_actual' THEN 'Activo'
            ELSE 'Finalizado'
        END";

// if (mysqli_query($conection, $sql)) {
//     echo "Estado de los contratos actualizado con éxito.";
// } else {
//     echo "Error al actualizar el estado de los contratos: " . mysqli_error($conection);
// }

?>