<?php
$response = array();

$con = pg_connect(getenv("DATABASE_URL"));

if (isset($_GET["email_usuario"])) {
	
	// Aqui sao obtidos os parametros
    $email = $_GET['email_usuario'];
 
	$result = pg_query($con, "SELECT * FROM compra WHERE email_usuario = '$email'");
	$response["compras"] = array();
	
	if (!empty($result)) {
        if (pg_num_rows($result) > 0) {
			while($row = pg_fetch_array($result)){
                $compra = array();
                $compra["data_hora"] = $row["data_hora"];
                $id_compra = $row["id"];

                $result1 = pg_query($con, "SELECT *  FROM compra_item WHERE id_compra = '$id_compra' LIMIT 1");
                $row1 = pg_fetch_array($result1);
                $id_item = $row1["id_item"];

                $result2 = pg_query($con, "SELECT * FROM item WHERE id = '$id_item'");
                $row2 = pg_fetch_array($result2);
                $id_mercado = $row2["id_mercado"];

                $result3 = pg_query($con, "SELECT * FROM mercado WHERE id = '$id_mercado'");
                $row3 = pg_fetch_array($result3);
                $compra["nome_mercado"] = $row3["nome"];
                $compra["img_produto"] = $row3["img"];
                $id_endereco = $row3["id_endereco"];

                $result4 = pg_query($con, "SELECT * FROM endereco WHERE id = '$id_endereco'");
                $row4 = pg_fetch_array($result4);
                $compra["cidade_mercado"] = $row4["cidade"];
                $compra["bairro_mercado"] = $row4["bairro"];

                $result5 = pg_query($con, "SELECT SUM(preco) as preco FROM compra_item INNER JOIN item ON compra_item.id_item = item.id WHERE id_compra = $id_compra");
                $row5 = pg_fetch_array($result5);

                $compra["id_compra"] = $id_compra;
                $compra["preco_total"] = $row5["preco"];
                
                array_push($response["compras"], $compra);
            }
        
 
            
            
            // Caso o compra exista no BD, o cliente 
			// recebe a chave "success" com valor 1.
            $response["success"] = 1;
			
			// Fecha a conexao com o BD
			//pg_close($con);
 
            // Converte a resposta para o formato JSON.
            echo json_encode($response);
        } else {
            // Caso o compra nao exista no BD, o cliente 
			// recebe a chave "success" com valor 0. A chave "message" indica o 
			// motivo da falha.
            $response["success"] = 0;
            $response["message"] = "compra não encontrado";
			
			
 
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