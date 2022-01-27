<?php
$con = pg_connect(getenv("DATABASE_URL"));
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['email_usuario'])) {
 
	$usuarioEmail = trim($_POST['email_usuario']);
	
		// mysql inserting a new row
		$result = pg_query($con, "INSERT INTO compra(email_usuario) VALUES('$usuarioEmail') RETURNING id");
	 
		if ($result) {
			$row = pg_fetch_array($result);
			$response["id"] = $row["id"];
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