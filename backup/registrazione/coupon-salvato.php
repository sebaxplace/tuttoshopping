<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
session_start();
$session = $_GET['email'];
$idOfferta = $_GET['idOfferta'];
$idOpzione = $_GET['idOpzione'];
$dataoggi = date('Ymd');
$dataoggi2 = date('d-m-Y H:i');
$sql = "SELECT * FROM utenti_iscritti WHERE Data_da <= ".$dataoggi." AND Abilita = 1 AND email = '".$session."'";
$stmt = sqlsrv_query($conn, $sql);
$row_count = sqlsrv_num_rows($stmt);
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
$id_utente = $row['id'];
$sql2 = "SELECT * FROM acquisti WHERE Data_da <= ".$dataoggi." AND Abilita = 1 AND mail_acquirente = '".$session."' AND Offerta = '".$idOfferta."' AND Opzione = '".$idOpzione."' ORDER BY Data_inserimento DESC";
$stmt2 = sqlsrv_query($conn, $sql2);
$row2_count = sqlsrv_num_rows($stmt2);
$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
$sql3 = "SELECT * FROM offerta WHERE Data_da <= '".$dataoggi."' AND id = '".$idOfferta."'";
$stmt3 = sqlsrv_query($conn, $sql3);
$row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
$sql4 = "SELECT * FROM sedi WHERE id = ".$row3['Sede']." and abilita = 1 and data_da <= '".$dataoggi."' ";
$stmt4 = sqlsrv_query($conn, $sql4);
$row4 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
$sql12 = "SELECT *, (SELECT COUNT(*) FROM opzione WHERE offerta = ".$idOfferta." AND id = ".$idOpzione." AND Abilita = 1) as count FROM opzione WHERE offerta = ".$idOfferta." AND id = ".$idOpzione." AND Abilita = 1 ORDER BY Prezzo_al_Pubblico ASC";
$stmt12 = sqlsrv_query($conn, $sql12);
$row12 = sqlsrv_fetch_array( $stmt12, SQLSRV_FETCH_ASSOC);
/* print_r($row);
print_r($row2);
print_r($row3);
print_r($row4);
print_r($row12);*/
	$Telefono = str_replace("+39", "", $row4['Telefono']);
	$Telefono = str_replace(".", "", $Telefono);
	$Telefono = str_replace("-", "", $Telefono);
	$Telefono = str_replace(" ", "", $Telefono);
	
	$Telefono_2 = str_replace("+39", "", $row4['Telefono_2']);
	$Telefono_2 = str_replace(".", "", $Telefono_2);
	$Telefono_2 = str_replace("-", "", $Telefono_2);
	$Telefono_2 = str_replace(" ", "", $Telefono_2);
	$Telefono_2 = str_replace("_", "", $Telefono_2);
	
	$Telefono_3 = str_replace("+39", "", $row4['Telefono_3']);
	$Telefono_3 = str_replace(".", "", $Telefono_3);
	$Telefono_3 = str_replace("-", "", $Telefono_3);
	$Telefono_3 = str_replace(" ", "", $Telefono_3);
	$Telefono_3 = str_replace("_", "", $Telefono_3);
	
	$Telefono2 = str_replace("+39", "", $row4['Telefono2']);
	$Telefono2 = str_replace(".", "", $Telefono2);
	$Telefono2 = str_replace("-", "", $Telefono2);
	$Telefono2 = str_replace(" ", "", $Telefono2);
	$Telefono2 = str_replace("_", "", $Telefono2);
	
	$Telefono2_2 = str_replace("+39", "", $row4['Telefono2_2']);
	$Telefono2_2 = str_replace(".", "", $Telefono2_2);
	$Telefono2_2 = str_replace("-", "", $Telefono2_2);
	$Telefono2_2 = str_replace(" ", "", $Telefono2_2);
	$Telefono2_2 = str_replace("_", "", $Telefono2_2);
	
	$Telefono2_3 = str_replace("+39", "", $row4['Telefono2_3']);
	$Telefono2_3 = str_replace(".", "", $Telefono2_3);
	$Telefono2_3 = str_replace("-", "", $Telefono2_3);
	$Telefono2_3 = str_replace(" ", "", $Telefono2_3);
	$Telefono2_3 = str_replace("_", "", $Telefono2_3);
	
	$Telefono3 = str_replace("+39", "", $row4['Telefono3']);
	$Telefono3 = str_replace(".", "", $Telefono3);
	$Telefono3 = str_replace("-", "", $Telefono3);
	$Telefono3 = str_replace(" ", "", $Telefono3);
	$Telefono3 = str_replace("_", "", $Telefono3);
	
	$Telefono3_2 = str_replace("+39", "", $row4['Telefono3_2']);
	$Telefono3_2 = str_replace(".", "", $Telefono3_2);
	$Telefono3_2 = str_replace("-", "", $Telefono3_2);
	$Telefono3_2 = str_replace(" ", "", $Telefono3_2);
	$Telefono3_2 = str_replace("_", "", $Telefono3_2);
	
	$Telefono3_3 = str_replace("+39", "", $row4['Telefono3_3']);
	$Telefono3_3 = str_replace(".", "", $Telefono3_3);
	$Telefono3_3 = str_replace("-", "", $Telefono3_3);
	$Telefono3_3 = str_replace(" ", "", $Telefono3_3);
	$Telefono3_3 = str_replace("_", "", $Telefono3_3);

$nome_opzione = str_replace('&euro;','',$row12['Titolo']);
$risparmio = $row12['Prezzo_di_Listino'] - $row12['Prezzo_al_Pubblico'];
	$x = $risparmio*100;
	$x = $x / $row12['Prezzo_di_Listino'];
	$g = substr($row3['Utilizzabile_entro'], -2 ); //giorno
	$a = substr($row3['Utilizzabile_entro'], 0, 4); //anno
	$m = substr($row3['Utilizzabile_entro'], -4, 2); //mese
	$my_coupon[] = array(
		'idOfferta' => $idOfferta,
		'idOpzione' => $idOpzione,
		'nome_azienda' => $row4['Nome'],
		'nome_opzione' => $nome_opzione,
		'sconto'=> round($x),
		'risparmio' => number_format($risparmio, 2),
		'descrizione_breve' => $row3['Nome'],
		'descrizione_lunga' => $row3['Descrizione'],
		'prezzo' => number_format($row12['Prezzo_al_Pubblico'], 2),
		'codice' =>$row2['Codice_Coupon'],
		'immagine' => 'http://www.tuttoshopping.com/'.$row3['Foto_1'],
		'indirizzo1' => $row4['Indirizzo'],
		'indirizzo2' => $row4['Indirizzo2'],
		'indirizzo3' => $row4['Indirizzo3'],
		'citta1' => $row4['Citta'],
		'citta2' => $row4['Citta2'],
		'citta3' => $row4['Citta3'],
		'latitudine' => $row4['Latitudine'],
		'longitudine' => $row4['Longitudine'],
		'scadenza' => $g.'-'.$m.'-'.$a,
		'telefono' => $row4['Telefono'],
		'telefono_2' => $row4['Telefono_2'],
		'telefono_3' => $row4['Telefono_3'],
		'telefonos' => $Telefono,
		'telefono2_2' => $Telefono_2,
		'telefono2_3' => $Telefono_3,
		
		'b_telefono' => $row4['Telefono2'],
		'b_telefono_2' => $row4['Telefono2_2'],
		'b_telefono_3' => $row4['Telefono2_3'],
		'b_telefono2' => $Telefono2,
		'b_telefono2_2' => $Telefono2_2,
		'b_telefono2_3' => $Telefono2_3,
		
		'c_telefono' => $row4['Telefono3'],
		'c_telefono_2' => $row4['Telefono3_2'],
		'c_telefono_3' => $row4['Telefono3_3'],
		'c_telefono2' => $Telefono3,
		'c_telefono2_2' => $Telefono3_2,
		'c_telefono2_3' => $Telefono3_3
		/*'Telefono_2' => $Telefono_2,
		'Telefono_3' => $Telefono_3,
		'Telefono2' => $Telefono2,
		'Telefono2_2' => $Telefono2_2,
		'Telefono2_3' => $Telefono2_3,
		'Telefono3' => $Telefono3,
		'Telefono3_2' => $Telefono3_2,
		'Telefono3_3' => $Telefono3_3,
		'Telefono_db' => $row4['Telefono'],
		'Telefono_2_db' => $row4['Telefono_2'],
		'Telefono_3_db' => $row4['Telefono_3'],
		'Telefono2_db' => $row4['Telefono2'],
		'Telefono2_2_db' => $row4['Telefono2_2'],
		'Telefono2_3_db' => $row4['Telefono2_3'],
		'Telefono3_db' => $row4['Telefono3'],
		'Telefono3_2_db' => $row4['Telefono3_2'],
		'Telefono3_3_db' => $row4['Telefono3_3']*/
	);
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
echo json_encode($my_coupon, JSON_PRETTY_PRINT);
?>

