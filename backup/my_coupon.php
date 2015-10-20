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
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
$id_utente = $row['id'];
$sql2 = "SELECT * FROM acquisti WHERE Data_da <= ".$dataoggi." AND mail_acquirente = '".$session."' AND Abilita= 1  ORDER BY Data_inserimento DESC";
$stmt2 = sqlsrv_query($conn, $sql2);
$row2_count = sqlsrv_num_rows($stmt2);
while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
	$sql3 = "SELECT * FROM offerta WHERE Data_da <= '".$dataoggi."' AND id = '".$row2['Offerta']."'";
	$stmt3 = sqlsrv_query($conn, $sql3);
	$row3_count = sqlsrv_num_rows($stmt3);
	$row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
	$nome = str_replace('&euro;','',$row3['Nome']);
	$sql4 = "SELECT *,(SELECT COUNT(*) FROM opzione WHERE Data_da <= '".$dataoggi."' AND Abilita = 1 AND id = '".$row2['Offerta']."') as count FROM opzione WHERE Data_da <= '".$dataoggi."' AND Abilita = 1 AND id = '".$row2['Offerta']."'";
	$stmt4 = sqlsrv_query($conn, $sql4);
	$row4 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
	if ($row4['count'] !== 0){
		$titolo = $row3['Titolo'];
	$sql12 = "SELECT *, (SELECT COUNT(*) FROM opzione WHERE offerta = ".$row2['Offerta']." AND Abilita = 1) as count FROM opzione WHERE offerta = ".$row2['Offerta']."  AND Abilita = 1 ORDER BY Prezzo_al_Pubblico ASC";
	$stmt12 = sqlsrv_query($conn, $sql12);
	if( $stmt12 === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
			//echo 'test';
	$row12 = sqlsrv_fetch_array( $stmt12, SQLSRV_FETCH_ASSOC);
	if ($row12['count'] !== 0){
		if ($row12['Prezzo_di_Listino'] != '0' && $row12['Prezzo_di_Listino'] != 0 && $row12['Prezzo_di_Listino'] != ' ' && $row12['Prezzo_di_Listino'] != '' && $row12['Prezzo_di_Listino'] != null && $row12['Prezzo_di_Listino'] != false){
			//echo $row12['Prezzo_di_Listino'].'|'.$row12['Prezzo_al_Pubblico'].'|||';
			//echo $Risparmio.'|'.$row12['Prezzo_di_Listino'].'__';
			//echo $row12['Prezzo_al_Pubblico'];
			$Risparmio = $row12['Prezzo_di_Listino'] - $row12['Prezzo_al_Pubblico'];
			
			
			$Sconto = ($Risparmio*100)/$row12['Prezzo_di_Listino'];
			
			$Sconto = explode(".", $Sconto);
		}
	}
	}
	$x = substr($row3['Data_da'], -2 ); //giorno
	$y = substr($row3['Data_da'], 0, 4); //anno
	$z = substr($row3['Data_da'], -4, 2); //mese
	$f = str_replace(":",".",$row3['Ora_di_Attivazione']); //ora
	$strtotime = $x."-".$z."-".$y." ".$row3['Ora_di_Attivazione'];
	$data_scadenza = date('d-m-Y H:i',strtotime(''.$strtotime.' +'.$row3['Durata'].' hours'));
	$strtotime = strtotime($data_scadenza);
	$datestrtotime = strtotime($dataoggi2);
	if($dataoggi <= $row3['Utilizzabile_entro']){
	$my_coupon[] = array(
		'id' => $row2['id'],
		'nome' => $nome,
		'scadenza' => $data_scadenza,
		'sconto' => $Sconto[0],
		'prezzo_al_pubblico' => number_format($row12['Prezzo_al_Pubblico'], 2),
		"categoria"	=> $row3['Categoria'],
		'opzione' => $row2['Opzione'],
		'idOfferta'	=> $row2['Offerta'],
		'pdf'	=> "http://www.tuttoshopping.com/coupons/".$id_utente."-".$row2['Offerta']."-".$row2['Opzione'].".pdf",
		'Utilizzabile_entro' => $row3['Utilizzabile_entro']
	);
	}
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
echo json_encode($my_coupon, JSON_PRETTY_PRINT);
?>