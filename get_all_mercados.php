<?php

$response = array();

$con = pg_connect(getenv("DATABASE_URL"));
 
$result = pg_query($con, "SELECT *FROM mercado");

if (pg_num_rows($result) > 0) {

    $response["mercado"] = array();

    while ($row = pg_fetch_array($result)) {
        $mercado = array();
        $mercado["id"] = $row["id"];
        $mercado["nome"] = $row["nome"];
        $mercado["img"] = $row["img"];
        $mercado["id_endereco"] = $row["id_endereco"];

        array_push($response["mercado"], $mercado);
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