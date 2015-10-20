<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');


require_once('connect.php');
$sql = "SELECT * FROM Zona_Offerte WHERE Data_da <= ".$dataoggi." AND Abilita = 1 ORDER BY Nome";
$stmt = sqlsrv_query($conn, $sql);
$row_count = sqlsrv_num_rows( $stmt );
$province = array();
if ($row_count !== 0){
	while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
		$province[] = array(
		'id' => $row['id'],
		'nome' => $row['Nome']
		);
	}
	echo json_encode($province, JSON_PRETTY_PRINT);
}

?>