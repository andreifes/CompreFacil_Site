<?php
$response = array();

$con = pg_connect(getenv("DATABASE_URL"));

if (isset($_GET["id"])) {
	
	// Aqui sao obtidos os parametros
    $id_compra = $_GET['id'];
 
	//$result = pg_query($con, "SELECT * FROM compra WHERE id = '$id_compra'");
    $query = "
        SELECT produto.nome, item.preco
        FROM compra_item 
        INNER JOIN item ON id_item = item.id
        INNER JOIN produto ON item.id_produto = produto.id
        WHERE id_compra = $id_compra
    ";

    $result = pg_query($con, $query);

	$response["itensCompra"] = array();
	
	if (!empty($result)) {
        if (pg_num_rows($result) > 0) {
			while($row = pg_fetch_array($result)) {

				$item = array();
                $item["nome"] = $row["nome"];
                $item["preco"] = $row["preco"];

				array_push($response["itensCompra"], $item);
			}
            
            // Caso o produto exista no BD, o cliente 
			// recebe a chave "success" com valor 1.
            $response["success"] = 1;
            
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
// Converte a resposta para o formato JSON.
echo json_encode($response);
?>