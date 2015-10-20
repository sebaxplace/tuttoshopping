<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
$data_oggi = date('dmY');
$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);

$sql = "SELECT * FROM Utenti_Iscritti WHERE Email = '".$email."' AND Abilita = 1";
$stmt = sqlsrv_query( $conn, $sql );
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
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
$todo = array($row);
utf8_encode_deep($todo);
echo json_encode($todo, JSON_PRETTY_PRINT);
?>