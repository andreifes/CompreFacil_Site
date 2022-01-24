<?php

$response = array();

$con = pg_connect(getenv("DATABASE_URL"));
 
$result = pg_query($con, "SELECT *FROM mercado");

if (pg_num_rows($result) > 0) {

    $response["mercados"] = array();

    while ($row = pg_fetch_array($result)) {
        $mercado = array();
        $mercado["id"] = $row["id"];
        $mercado["nome"] = $row["nome"];
        $mercado["img"] = $row["img"];
		$id_endereco = $row["id_endereco"];
		$result1 = pg_query($con, "SELECT * FROM endereco WHERE endereco.id = '$id_endereco'");
		$row1 = pg_fetch_array($result1);
        $mercado["cidade"] = $row1["cidade"];
		$mercado["bairro"] = $row1["bairro"];

        array_push($response["mercados"], $mercado);
    }

    $response["success"] = 1;
	
	pg_close($con);
 
    echo json_encode($response);

} else {
    $response["success"] = 0;
    $response["message"] = "Nao ha produtos";
	
	pg_close($con);

    echo json_encode($response);
}

?>