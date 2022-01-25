<?php
 
/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 
// connecting to db
$con = pg_connect(getenv("DATABASE_URL"));
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['email']) && isset($_POST['nome']) && isset($_POST['telefone']) && isset($_POST['senha'])) {
 
	$newNome = trim($_POST['nome']);
	$newEmail = trim($_POST['email']);
	$newTelefone = trim($_POST['telefone']);
	$newSenha = trim($_POST['senha']);
		
	$usuario_existe = pg_query($con, "SELECT email FROM usuario WHERE email='$newEmail'");
	// check for empty result
	if (pg_num_rows($usuario_existe) > 0) {
		$response["success"] = 0;
		$response["error"] = "usuario ja cadastrado";
	}
	else {
		// mysql inserting a new row
		$result = pg_query($con, "INSERT INTO usuario(email, nome, telefone, senha) VALUES('$newEmail', '$newNome', '$newTelefone', '$newSenha')");
	 
		if ($result) {
			$response["success"] = 1;
		}
		else {
			$response["success"] = 0;
			$response["error"] = "Error BD: ".pg_last_error($con);
		}
	}
}
else {
    $response["success"] = 0;
	$response["error"] = "faltam parametros";
}

pg_close($con);
echo json_encode($response);
?>