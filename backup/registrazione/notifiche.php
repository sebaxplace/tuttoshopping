<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require_once('connect.php');
$data_oggi = date('Ymd');
$data_ieri = date('Ymd',(strtotime ( '-5 day' , strtotime ( $data_oggi) ) ));


if($conn){
	$sql = "SELECT COUNT(id) FROM vista_offerte WHERE vista_offerte.Abilita = 1 AND vista_offerte.Data_inserimento >= $data_ieri";
	$stmt = sqlsrv_query( $conn, $sql);
	if( $stmt === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		$coupon["count"] = $row;
	};
		function utf8_encode_deep(&$input) {
	    if (is_string($input)) {
	        $input = utf8_encode($input);
	    } else if (is_array($input)) {
	        foreach ($input as &$value) {
	            utf8_encode_deep($value);
	        }

	        unset($value);
	    } else if (is_object($input)) {
	        $vars = array_keys(get_object_vars($input));

	        foreach ($vars as $var) {
	            utf8_encode_deep($input->$var);
	        }
	    }
	}
	utf8_encode_deep($coupon);
	echo json_encode($coupon, JSON_PRETTY_PRINT);
}else{
	echo "Connection could not be established.<br />";
	die( print_r( sqlsrv_errors(), true));
}
?>