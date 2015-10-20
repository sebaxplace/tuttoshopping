<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
session_start();
$session = $_GET['email'];
$dataoggi = date('Ymd');
$dataoggi2 = date('d-m-Y H:i');

$sql = "SELECT * FROM utenti_iscritti WHERE Data_da <= ".$dataoggi." AND Abilita = 1 AND email = '".$session."'";
$stmt = sqlsrv_query($conn, $sql);
$row_count = sqlsrv_num_rows($stmt);
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
	$results[] = $row;
}

print_r($results);
?>