<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
$data_oggi = date('Ymd');
$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

$sql = "SELECT COUNT(*) as count FROM Acquisti WHERE  abilita = 1 and data_da <= '".$data_oggi."' and data_inserimento = '".$data_oggi."' and mail_acquirente = '".$email."'";
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
if ($row['count'] === 0){
	$status = 'ok';
	$message = "validato";
} else {
	$status = 'error';
	$message = "non validato";
}
$data = array(
	'status' => $status,
	'message' => $message
);
$resultadosJson = json_encode($data);
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
?>