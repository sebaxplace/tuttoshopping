<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once('connect.php');
$idOfferta = $_GET['idOfferta'];
$sql = "SELECT * FROM vista_offerte WHERE id = ".$idOfferta." AND Data_da <= '".$dataoggi."'";
$stmt = sqlsrv_query($conn, $sql);
$row_count = sqlsrv_num_rows($stmt);
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
$offerta = array();
$x = substr($row['Data_da'], -2 ); //giorno
$y = substr($row['Data_da'], 0, 4); //anno
$z = substr($row['Data_da'], -4, 2); //mese
$f = str_replace(":",".",$row['Ora_di_Attivazione']); //ora
$strtotime = $x."-".$z."-".$y." ".$row['Ora_di_Attivazione'];
$data_inizio_offerta_completa = $x."/".$z."/".$y." ".$f;
$data_fine_offerta_completa = date('d-m-Y H:i',strtotime(''.$strtotime.' +'.$row['Durata'].' hours'));
$data_fine_offerta_completa_off_sospesa = date('d-m-Y H:i',strtotime(''.$data_fine_offerta_completa.' +48 hours'));
$data_fine_offerta_completa2 = date('Y/m/d H:i:s',strtotime(''.$strtotime.' +'.$row['Durata'].' hours'));

$data_oggi = date('d-m-Y');
$ora_oggi = date('H:i', strtotime('+1 hour'));
$data_ora_corrente = $data_oggi." ".$ora_oggi;
$offerta_sospesa = 0;
if ($row_count === 0){
	echo "Torna a visitarci nei prossimi giorni, al momento non ci sono offerte attive!";
} else {
	$sql2 = "SELECT * FROM opzione WHERE offerta = ".$row['id']." ORDER BY Prezzo_al_Pubblico ASC";
	$stmt2 = sqlsrv_query($conn, $sql2);
	$row2_count = sqlsrv_num_rows( $stmt2 );
	$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
	if ($row2_count !== 0){
		if (!empty($row2['Prezzo_di_Listino']) === true){
			$Risparmio = $row2['Prezzo_di_Listino'] - $row2['Prezzo_al_Pubblico'];
			$Sconto = ($Risparmio*100)/$row2['Prezzo_di_Listino'];
			$Sconto = explode(".", $Sconto);
			$abilita = $row2['Abilita'];
		}
	}
	$sql3 = "SELECT * FROM Sedi WHERE id = ".$row['Sede']."";
	$stmt3 = sqlsrv_query($conn, $sql3);
	$row3_count = sqlsrv_num_rows( $stmt3 );
	$row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
	if ($row3_count !== 0){
		$indirizzo_Google = $row3['Indirizzo_google'];
		$indirizzo_Google2 = $row3['Indirizzo_google2'];
		$indirizzo_Google3 = $row3['Indirizzo_google3'];

		$lat1 = $row3['Latitudine'];
		$lat2 = $row3['Latitudine2'];
		$lat3 = $row3['Latitudine3'];

		$long1 = $row3['Longitudine'];
		$long2 = $row3['Longitudine2'];
		$long3 = $row3['Longitudine3'];

		if ($indirizzo_Google !== ""){
			$indirizzo_Google = str_replace("'","",$indirizzo_Google);
		}elseif ($indirizzo_Google2 !== ""){
			$indirizzo_Google2 = str_replace("'","",$indirizzo_Google2);
		}elseif ($indirizzo_Google3 !== ""){
			$indirizzo_Google3 = str_replace("'","",$indirizzo_Google3);
		}
	}
	$sql_count = "SELECT COUNT(*) as CouponStampati FROM acquisti WHERE offerta = '".$row['id']."' AND Data_da <= '".$dataoggi."'";
	$stmt4 = sqlsrv_query($conn, $sql_count);
	$row_sql_count = sqlsrv_num_rows( $stmt4 );
	$row_count_cop = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
	if ($row_sql_count !== 0){
		$CouponStampati = $row_count_cop['CouponStampati'];
	}
	if (strtotime($data_ora_corrente) > strtotime($data_fine_offerta_completa)){
		$offerta_sospesa = 1;
	}
	if ($row['Foto_1'] !== ""){
		$nfoto= 1;
		if ($row['Foto_2'] !== ""){
			$nfoto= 2;
		}
		if ($row['Foto_3'] !== ""){
			$nfoto= 3;
		}
	}
	if ($row3['Sito_web'] !== ""){
		if (strpos($row3['Sito_web'],'http') !== false) {
			$sito_web = $row3['Sito_web'];
		} else {
			$sito_web = 'http://'.$row3['Sito_web'];
		}
	} else {
		$sito_web = "";
	}
	setlocale(LC_MONETARY, 'it_IT');
	$telefono = str_replace("+39", "", $row3['Telefono']);
	$Telefono = str_replace(".", "", $telefono);
	$Telefono = str_replace("-", "", $telefono);
	$Telefono = str_replace(" ", "", $telefono);
	
	$Telefono_2 = str_replace("+39", "", $row3['Telefono_2']);
	$Telefono_2 = str_replace(".", "", $Telefono_2);
	$Telefono_2 = str_replace("-", "", $Telefono_2);
	$Telefono_2 = str_replace(" ", "", $Telefono_2);
	$Telefono_2 = str_replace("_", "", $Telefono_2);
	
	$Telefono_3 = str_replace("+39", "", $row3['Telefono_3']);
	$Telefono_3 = str_replace(".", "", $Telefono_3);
	$Telefono_3 = str_replace("-", "", $Telefono_3);
	$Telefono_3 = str_replace(" ", "", $Telefono_3);
	$Telefono_3 = str_replace("_", "", $Telefono_3);
	
	$Telefono2 = str_replace("+39", "", $row3['Telefono2']);
	$Telefono2 = str_replace(".", "", $Telefono2);
	$Telefono2 = str_replace("-", "", $Telefono2);
	$Telefono2 = str_replace(" ", "", $Telefono2);
	$Telefono2 = str_replace("_", "", $Telefono2);
	
	$Telefono2_2 = str_replace("+39", "", $row3['Telefono2_2']);
	$Telefono2_2 = str_replace(".", "", $Telefono2_2);
	$Telefono2_2 = str_replace("-", "", $Telefono2_2);
	$Telefono2_2 = str_replace(" ", "", $Telefono2_2);
	$Telefono2_2 = str_replace("_", "", $Telefono2_2);
	
	$Telefono2_3 = str_replace("+39", "", $row3['Telefono2_3']);
	$Telefono2_3 = str_replace(".", "", $Telefono2_3);
	$Telefono2_3 = str_replace("-", "", $Telefono2_3);
	$Telefono2_3 = str_replace(" ", "", $Telefono2_3);
	$Telefono2_3 = str_replace("_", "", $Telefono2_3);
	
	$Telefono3 = str_replace("+39", "", $row3['Telefono3']);
	$Telefono3 = str_replace(".", "", $Telefono3);
	$Telefono3 = str_replace("-", "", $Telefono3);
	$Telefono3 = str_replace(" ", "", $Telefono3);
	$Telefono3 = str_replace("_", "", $Telefono3);
	
	$Telefono3_2 = str_replace("+39", "", $row3['Telefono3_2']);
	$Telefono3_2 = str_replace(".", "", $Telefono3_2);
	$Telefono3_2 = str_replace("-", "", $Telefono3_2);
	$Telefono3_2 = str_replace(" ", "", $Telefono3_2);
	$Telefono3_2 = str_replace("_", "", $Telefono3_2);
	
	$Telefono3_3 = str_replace("+39", "", $row3['Telefono3_3']);
	$Telefono3_3 = str_replace(".", "", $Telefono3_3);
	$Telefono3_3 = str_replace("-", "", $Telefono3_3);
	$Telefono3_3 = str_replace(" ", "", $Telefono3_3);
	$Telefono3_3 = str_replace("_", "", $Telefono3_3);
	
	$nome = str_replace('&euro;','',$row['Nome']);
	$offerta[] = array(
		'id' => $row['id'],
		'nfoto' => $nfoto,
		'foto' => 'http://www.tuttoshopping.com/'.$row['Foto_1'],
		'foto2' => 'http://www.tuttoshopping.com/'.$row['Foto_2'],
		'foto3' => 'http://www.tuttoshopping.com/'.$row['Foto_3'],
		'categoria' => $row['Categoria'],
		'zona'=> $row['Zona'],
		'nome'=> $nome,
		'risparmio'=> $Risparmio,
		'sconto' => $Sconto[0],
		'utilizzabile_entro' => $data_fine_offerta_completa2,
		'visite' => $row['Visite'] +1,
		'indirizzo_Google' => $indirizzo_Google,
		'indirizzo_Google2' => $indirizzo_Google2,
		'indirizzo_Google3' => $indirizzo_Google3,
		'latitudine1' => $lat1,
		'latitudine2' => $lat2,
		'latitudine3' => $lat3,
		'longitudine1' => $long1,
		'longitudine2' => $long2,
		'longitudine3' => $long3,
		'sospesa' => $offerta_sospesa,
		'esaurita' => $row['Offerta_Esaurita'],
		'prezzo_di_listino' =>  number_format($row2['Prezzo_di_Listino'], 2),
		'prezzo_al_pubblico' =>  number_format($row2['Prezzo_al_Pubblico'], 2),
		'coupon_stampati' => $CouponStampati,
		'riepilogo' => $row['Riepilogo'],
		'offerta_deluxe' => $row['Offerta_Deluxe'],
		'descrizione' => $row['Descrizione'],
		'scheda_tecnica' => $row3['Scheda_Tecnica'],
		'sito_web' => $sito_web,
		'logo' => 'http://www.tuttoshopping.com/'.$row3['Logo'],
		'nome_esercente' => $row3['Nome'],
		'indirizzo1' => $row3['Indirizzo'],
		'b_indirizzo' => $row3['Indirizzo2'],
		'c_indirizzo' => $row3['Indirizzo3'],
		'citta1' => $row3['Citta'],
		'b_citta' => $row3['Citta2'],
		'c_citta' => $row3['Citta3'],
		'telefono' => $row3['Telefono'],
		'telefono_2' => $row3['Telefono_2'],
		'telefono_3' => $row3['Telefono_3'],
		'telefono2' => $Telefono,
		'telefono2_2' => $Telefono_2,
		'telefono2_3' => $Telefono_3,
		
		'b_telefono' => $row3['Telefono2'],
		'b_telefono_2' => $row3['Telefono2_2'],
		'b_telefono_3' => $row3['Telefono2_3'],
		'b_telefono2' => $Telefono2,
		'b_telefono2_2' => $Telefono2_2,
		'b_telefono2_3' => $Telefono2_3,
		
		'c_telefono' => $row3['Telefono3'],
		'c_telefono_2' => $row3['Telefono3_2'],
		'c_telefono_3' => $row3['Telefono3_3'],
		'c_telefono2' => $Telefono3,
		'c_telefono2_2' => $Telefono3_2,
		'c_telefono2_3' => $Telefono3_3
	);
	/*
	function convert(&$value, $key){
		$value = htmlentities($value);
	}
	array_walk($offerta, 'convert');
	/*$sql2_update = "UPDATE offerta SET visite ".$row['visite'] + 1 ." WHERE id = ".$row['id']."";
	$stmt_update = sqlsrv_query($conn, $sql2_update);*/
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
utf8_encode_deep($offerta);
	echo json_encode($offerta, JSON_PRETTY_PRINT);
	
}

if (isset($_GET['zona']) && $_GET['zona'] !== ""){
	$zona = $_GET['zona'];
	$sql .= " AND (zona LIKE '".$zona."' OR zona LIKE '%".$zona."' OR zona LIKE '".$zona."%' OR zona LIKE '%".$zona."%')";
}
$sql .= " Order BY Posizione ASC, data_da ASC";
$stmt = sqlsrv_query( $conn, $sql );
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
	
}

?>