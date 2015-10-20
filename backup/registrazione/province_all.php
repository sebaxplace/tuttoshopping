<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
require_once('connect.php');
$dataoggi = date('dmY');
$sql = "SELECT * FROM Provincia WHERE Abilita = 1 ORDER BY Nome";
$stmt = sqlsrv_query($conn, $sql);
$province = array();
	while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
		$province[] = array(
		'id' => $row['id'],
		'nome' => $row['Nome']
		);
	}
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
	utf8_encode_deep($province);
	echo json_encode($province, JSON_PRETTY_PRINT);

?>