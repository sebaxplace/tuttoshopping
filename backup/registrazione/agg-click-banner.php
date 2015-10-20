<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$data_oggi = date('d-m-Y');
require_once('connect.php');
$tabella = $_GET["tabella"];
$id = $_GET["id"];
$sql = "SELECT *, (SELECT COUNT(*) FROM ".$tabella." WHERE id = '".$id."' ) as count FROM ".$tabella." where id <= '".$id."'";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
if($row['count'] === 1){
	$link= $row['link'];
	$click = $row['Click_app'];
	$click2 = ++$click;
	echo $click2;
	$stmt2 = sqlsrv_query($conn, "UPDATE ".$tabella." SET Click_app = ".$click2." WHERE id = ".$id."");
} else {
	exit;
}
?>