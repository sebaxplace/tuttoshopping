<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');

if(isset($_GET['usuario']) && isset($_GET['password'])){
	if ($_GET['usuario'] !== '' && $_GET['password'] !== ''){
		$sql = "SELECT *, (SELECT COUNT(*) from Utenti_iscritti where  email = '".$_GET['usuario']."' and password = '".$_GET['password']."') as count from Utenti_iscritti where  email = '".$_GET['usuario']."' and password = '".$_GET['password']."'";
		$stmt = sqlsrv_query($conn, $sql);
		
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		if ($row['count'] === 1){
			$status = 'ok';
			$message = 'Accesso effettuato';  
		} else {
			$status = 'error';
			$message = "Errore durante il login";
		}
	} else {
		$status = 'error';
		$message = "Errore durante il login";
	}
} else {
	$status = 'error';
	$message = "Errore durante il login";
}
$data = array('validacion' => $status);
$resultadosJson = json_encode($data);
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
?>