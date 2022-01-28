<?php

$response = array();

$con = pg_connect(getenv("DATABASE_URL"));

if (isset($_GET["email"])) {
	
	// Aqui sao obtidos os parametros
    $email = $_GET['email'];
 
	$result = pg_query($con, "SELECT * FROM usuario WHERE email = '$email'");
	$response["usuario"] = array();
	
	if (!empty($result)) {
        if (pg_num_rows($result) > 0) {
			$row = pg_fetch_array($result);
			
			$usuario = array();
			$usuario["nome"] = $row["nome"];
			$usuario["telefone"] = $row["telefone"];
			
			$response["usuario"] = $usuario;
            
           
            // Caso o produto exista no BD, o cliente 
			// recebe a chave "success" com valor 1.
            $response["success"] = 1;
			
			// Fecha a conexao com o BD
			pg_close($con);
 
            // Converte a resposta para o formato JSON.
            echo json_encode($response);
			
        } else {
            // Caso o produto nao exista no BD, o cliente 
			// recebe a chave "success" com valor 0. A chave "message" indica o 
			// motivo da falha.
            $response["success"] = 0;
            $response["message"] = "Produto não encontrado";            
        }
	}
} else {
    // Se a requisicao foi feita incorretamente, ou seja, os parametros 
	// nao foram enviados corretamente para o servidor, o cliente 
	// recebe a chave "success" com valor 0. A chave "message" indica o 
	// motivo da falha.
    $response["success"] = 0;
    $response["message"] = "Campo requerido não preenchido";
}
// Fecha a conexao com o BD
pg_close($con);
echo json_encode($response);

?>