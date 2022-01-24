<?php

$response = array();

$con = pg_connect(getenv("DATABASE_URL"));
 
$result = pg_query($con, "SELECT *FROM produto");

if (pg_num_rows($result) > 0) {

    $response["produto"] = array();

    while ($row = pg_fetch_array($result)) {
        $produto = array();
        $produto["id"] = $row["id"];
        $produto["nome"] = $row["nome"];
        $produto["img"] = $row["img"];
        $produto["id_categoria"] = $row["id_categoria"];

        array_push($response["produto"], $produto);
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