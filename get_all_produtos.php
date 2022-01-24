<?php

$response = array();

$con = pg_connect(getenv("DATABASE_URL"));

if (isset($_GET["id_mercado"])) {
	
	// Aqui sao obtidos os parametros
    $id_mercado = $_GET['id_mercado'];
 
	$result = pg_query($con, "SELECT * FROM item WHERE id_mercado = '$id_mercado'");
	$response["produtos"] = array();
	
	if (!empty($result)) {
        if (pg_num_rows($result) > 0) {
			while($row = pg_fetch_array($result)){
				$produto = array();
				$produto["preco"] = $row["preco"];
				$id_produto = $row["id_produto"];
				$result1 = pg_query($con, "SELECT *FROM produto WHERE id = '$id_produto'");
				$row1 = pg_fetch_array($result1);
				$produto["nome"] = $row1["nome"];
				$produto["img"] = $row1["img"];
				$id_categoria = $row1["id_categoria"];
				$result2 = pg_query($con, "SELECT *FROM categoria WHERE id = '$id_categoria'");
				$row2 = pg_fetch_array($result2);
				$produto["id_categoria"] = $id_categoria;
				$produto["nome_categoria"] = $row2["nome"];
				
				array_push($response["produtos"], $produto);
			}
 
            
            
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
			
			
 
            // Converte a resposta para o formato JSON.
            
        }
	}
}else {
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