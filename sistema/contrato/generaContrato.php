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


	$cedula = mysqli_real_escape_string($conection, $cedula);

	$clientequery = mysqli_query($conection, "SELECT 
    	cont.id_contrato, cont.fecha_inicio, cont.fecha_fin, cont.estado, cont.placa, cont.observaciones, cont.atendido_por, v.modelo, 
    	v.num_gps,v.anio, cont.clave, cont.cedula, cl.nombre AS nombre_cliente,cl.direccion AS direccion_cliente,
    	GROUP_CONCAT(ic.descripcion SEPARATOR ', ') AS descripciones
		FROM contrato cont
		JOIN vehiculo v ON cont.placa = v.placa
		JOIN cliente cl ON cont.cedula = cl.cedula
		LEFT JOIN item_contrato ic ON cont.id_contrato = ic.id_contrato
		WHERE cont.cedula = '$cedula'
		GROUP BY cont.id_contrato");


	$result = mysqli_num_rows($clientequery);
	if ($result > 0) {



		$options = new Options();
		$options->set('chroot', $_SERVER['DOCUMENT_ROOT']);
		$dompdf = new Dompdf($options);

		// Start PDF output
		$dompdf->setPaper('letter', 'portrait');

		ob_start(); // Inicia el búfer de salida para almacenar todo el HTML de los contratos

		while ($contrato = mysqli_fetch_assoc($clientequery)) {
			include(dirname(__FILE__) . '/contrato.php'); // Include the contract template
		}

		$html = ob_get_clean(); // Obtiene todo el HTML generado

		$dompdf->loadHtml($html);
		$dompdf->render();

		// Output the generated PDF with all contracts
		$dompdf->stream('contratos_' . $cedula . '.pdf', array('Attachment' => 0));
		exit;
	} else {
		echo "No se encontraron contratos para la cédula proporcionada.";

	}
}

?>