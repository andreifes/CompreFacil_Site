<?php
$con = pg_connect(getenv("DATABASE_URL"));
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['email_usuario'])) {
 
	$usuarioEmail = trim($_POST['email_usuario']);
		
	$usuario_existe = pg_query($con, "SELECT email_usuario FROM compra WHERE email='$newEmail'");
	// check for empty result
	
		// mysql inserting a new row
		$result = pg_query($con, "INSERT INTO compra(email_usuario) VALUES('$usuarioEmail')");
	 
		if ($result) {
			$response["success"] = 1;
		}
		else {
			$response["success"] = 0;
			$response["error"] = "Error BD: ".pg_last_error($con);
		}
}
else {
    $response["success"] = 0;
	$response["error"] = "faltam parametros";
}

pg_close($con);
echo json_encode($response);
?>
?>