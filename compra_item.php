<?php

$con = pg_connect(getenv("DATABASE_URL"));
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['id_compra']) && isset($_POST['id_item'])){
 
	$id_compra = trim($_POST['id_compra']);
    $id_item = trim($_POST['id_item']);
	
		// mysql inserting a new row
		$result = pg_query($con, "INSERT INTO compra_item(id_compra, id_item) VALUES('$id_compra', '$id_item')");
	 
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