<?php

session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../dompdf/vendor/autoload.php';


if (
	empty($_REQUEST['cedula'])
) {
	echo "No se ha proporcionado la cédula del cliente.";
	exit;
} else {
	$cedula = $_REQUEST['cedula'];

	$query_config = mysqli_query($conection, "SELECT * FROM configuracion");
	$result_config = mysqli_num_rows($query_config);
	if ($result_config > 0) {
		$configuracion = mysqli_fetch_assoc($query_config);
	}


	$query_cliente = mysqli_query($conection, "SELECT * FROM cliente WHERE cedula = $cedula");
	$result_cliente = mysqli_num_rows($query_cliente);
	if ($result_cliente > 0) {
		$cliente = mysqli_fetch_assoc($query_cliente);
	}



	$clientequery = mysqli_query($conection, "SELECT cont.id_contrato, cont.fecha_inicio, cont.fecha_fin, cont.estado, cont.placa,v.modelo,v.num_gps, cont.clave,
		COUNT(*) AS total_items
		FROM contrato cont
		JOIN 
		vehiculo v ON cont.placa = v.placa
		LEFT JOIN 
		item_contrato i ON cont.id_contrato = i.id_contrato
		WHERE cont.cedula = '$cedula'
		GROUP BY cont.id_contrato, cont.fecha_inicio, cont.fecha_fin, cont.estado, cont.placa, v.modelo, v.num_gps, cont.clave");


	$result = mysqli_num_rows($clientequery);
	if ($result > 0) {

		ob_start();
		include(dirname('__FILE__') . '/contratoList.php');
		$html = ob_get_clean();


		// instantiate and use the dompdf class
		$options = new Options();
		$options->set('chroot', $_SERVER['DOCUMENT_ROOT']);
		$dompdf = new Dompdf($options);

		// $dompdf = new Dompdf($options);

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('contratos_' . $cedula . '.pdf', array('Attachment' => 0));
		exit;

	} else {
		echo "No se encontraron contratos para la cédula proporcionada.";

	}
}

?>