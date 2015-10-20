<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$data_oggi = date('d-m-Y');
require_once('connect.php');
$sql = "SELECT TOP 1 * FROM banner_offerte where abilita = 1 and data_da <= '".$dataoggi."' AND Immagine != '' ORDER BY Presenze ASC";
$stmt = sqlsrv_query($conn, $sql);

while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
	$banner[] = $row;
	$presenza = $row['Presenze'] + 1;
	$sql_update = "UPDATE banner_offerte SET Presenze = '".$presenza."' WHERE id = ".$row['id']."";
	$stmt_update = sqlsrv_query($conn, $sql_update);
}
$stmt_update = sqlsrv_query($conn, $sql2_update);
echo json_encode($banner, JSON_PRETTY_PRINT);
?>