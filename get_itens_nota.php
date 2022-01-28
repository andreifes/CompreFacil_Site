<?php
$response = array();

$con = pg_connect(getenv("DATABASE_URL"));

if (isset($_GET["id"])) {
	
	// Aqui sao obtidos os parametros
    $id_compra = $_GET['id'];
 
	$result = pg_query($con, "SELECT * FROM compra WHERE id = '$id_compra'");
	$response["compras"] = array();
	
	if (!empty($result)) {
        if (pg_num_rows($result) > 0) {
			while($row = pg_fetch_array($result)){

                $result10 = pg_query($con, "SELECT *FROM compra_item WHERE id_compra = '$id_compra'");
				$row10 = pg_fetch_array($result10);
				$item = array();
				$id_item = $row10["id_item"];
                
                $result11 = pg_query($con, "SELECT *FROM item WHERE id = '$id_item'");
				$row11 = pg_fetch_array($result11);
                $item["preco"] = $row11["preco"];
                $id_produto = $row11["id_produto"];

                $result12 = pg_query($con, "SELECT *FROM produto WHERE id = '$id_produto'");
				$row12 = pg_fetch_array($result12);
                $item["nome_produto"] = $row12["nome"];

				array_push($response["compras"], $item);
			}
 
            
            
            // Caso o produto exista no BD, o cliente 
			// recebe a chave "success" com valor 1.
            $response["success"] = 1;
			
			// Fecha a conexao com o BD
			//pg_close($con);
 
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