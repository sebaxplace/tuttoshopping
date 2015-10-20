<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
	require_once('connect.php');
	$idOfferta = $_GET['idOfferta'];
	$opzioni = array();
	
	$idUtente = $row['id'];
	$sql2 = "SELECT * FROM opzione WHERE Offerta = ".$idOfferta." AND Abilita = 1 ORDER BY Prezzo_al_Pubblico ASC";
	$stmt2 = sqlsrv_query( $conn, $sql2 );

	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {
		$sql3 = "SELECT * FROM offerta WHERE id = '".$row2['Offerta']."'";
		$stmt3 = sqlsrv_query($conn, $sql3);
		$row3_count = sqlsrv_num_rows($stmt3);
		$row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
		$x = substr($row3['Data_da'], -2 ); //giorno
		$y = substr($row3['Data_da'], 0, 4); //anno
		$z = substr($row3['Data_da'], -4, 2); //mese
		$f = str_replace(":",".",$row3['Ora_di_Attivazione']); //ora
		$strtotime = $x."-".$z."-".$y." ".$row3['Ora_di_Attivazione'];
		$data_scadenza = date('d-m-Y H:i',strtotime(''.$strtotime.' +'.$row3['Durata'].' hours'));
		if (!empty($row2['Prezzo_di_Listino']) === true){
			$risparmio = $row2['Prezzo_di_Listino'] - $row2['Prezzo_al_Pubblico'];
			if ($risparmio !== 0 && $risparmio !== '' && $risparmio !== NULL){
			$x = $risparmio*100;
			$x = $x / $row2['Prezzo_di_Listino'];
			$opzioni[] = array(
				"Titolo" => str_replace('&euro;','',$row2['Titolo']),
				"idOpzione"	=> $row2['id'],
				"idOfferta"	=> $row2['Offerta'],
				'scadenza' => $data_scadenza,
				"Prezzo"	=> number_format($row2['Prezzo_al_Pubblico'], 2),
				"Risparmio" => number_format($risparmio, 2),
				"Sconto" => round($x)
			);
			}
		} else {
			$opzioni[] = array(
				"Titolo" => str_replace('&euro;','',$row2['Titolo']),
				"idOpzione"	=> $row2['id'],
				"idOfferta"	=> $row2['Offerta'],
				'scadenza' => $data_scadenza,
				"Prezzo"	=> 0,
				"Risparmio" => 0,
				"Sconto" => 0
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
echo json_encode($opzioni, JSON_PRETTY_PRINT);
/*}*/
?> 