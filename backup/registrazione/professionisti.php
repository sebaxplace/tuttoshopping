<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$curl = curl_init("http://www.tuttoshopping.com/app/");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$respuesta = curl_exec($curl);
$info = curl_getinfo($curl);
if($info['http_code'] == 200){
require_once('connect.php');
$oraoggi = date('hi');
$ciudad = $_GET['zona'];
$category = $_GET['category'];

if($ciudad != 0){$consultaciudad = ' AND Zona = '.$ciudad;}else{$consultaciudad = '';}
if($category != ''){$consultacategory = 'AND Categoria = '.$category;}else{$consultacategory = '';}

if($conn){
	$sql2 = "SELECT * FROM vista_offerte WHERE (Data_da < '".$dataoggi."' OR (Data_da = '".$dataoggi."' and replace(Ora_di_Attivazione, ':','') >= '".$oraoggi."')) AND Abilita = 1 ".$consultaciudad." AND Categoria ='5'";
	if (isset($_GET['zona']) && $_GET['zona'] !== ""){
		$zona = $_GET['zona'];
		$sql2 .= " AND (zona LIKE '".$zona."' OR zona LIKE '%".$zona."' OR zona LIKE '".$zona."%' OR zona LIKE '%".$zona."%')";
	}
	$sql2 .= " Order BY Posizione ASC, data_da ASC";
	$stmt = sqlsrv_query( $conn, $sql2 );
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		
		$x = substr($row['Data_da'], -2 ); //giorno
		$y = substr($row['Data_da'], 0, 4); //anno
		$z = substr($row['Data_da'], -4, 2); //mese
		$f = str_replace(":",".",$row['Ora_di_Attivazione']); //ora
		$strtotime = $x."-".$z."-".$y." ".$row['Ora_di_Attivazione'];
		$data_inizio_offerta_completa = $x."/".$z."/".$y." ".$f;
		$data_fine_offerta_completa = date('d-m-Y H:i',strtotime(''.$strtotime.' +'.$row['Durata'].' hours'));
		$data_fine_offerta_completa_off_sospesa = date('d-m-Y H:i',strtotime(''.$data_fine_offerta_completa.' +48 hours'));
		$data_oggi = date('d-m-Y');
		$ora_oggi = date('H:i', strtotime('+1 hour'));
		$data_ora_corrente = $data_oggi." ".$ora_oggi;
		$offerta_sospesa = 0;
		if (strtotime($data_ora_corrente) <= strtotime($data_fine_offerta_completa_off_sospesa)){
			$offerta_trovata = 1;
			$sql_opzioni = "SELECT * FROM Opzione where offerta = ".$row['id']." ORDER BY Prezzo_al_Pubblico ASC";
			$stmt2 = sqlsrv_query( $conn, $sql_opzioni );
			$row_count = sqlsrv_num_rows( $stmt2 );
			$row_op = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
			if ($row_count === 0){
				$prezzo_di_listino = "";
				$prezzo_al_pubblico = "";
			}else{
				$prezzo_di_listino = $row_op['Prezzo_di_Listino'];
				$prezzo_al_pubblico = $row_op['Prezzo_al_Pubblico'];
			}				
			if (strtotime($data_ora_corrente) > strtotime($data_fine_offerta_completa)){
				$offerta_sospesa = 1;
			}
			$sql_count = "SELECT COUNT(*) as CouponStampati FROM acquisti WHERE offerta = ".$row['id']." AND Abilita = 1 AND Data_da <= '".$dataoggi."'";
			$stmt3 = sqlsrv_query($conn, $sql_count);
			$row_sql_count = sqlsrv_num_rows( $stmt3 );
			$row_count_cop = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
			if ($row_sql_count !== 0){
				$CouponStampati = $row_count_cop['CouponStampati'];
			}
			$nome = $row['Nome'];
			$titolo = $row_op['Titolo'];
			if ($offerta_sospesa === 1){
				$coupon[] = array(
					"id" 					=> $row['id'],
					"nome" 					=> $nome,
					'sospesa' 				=> $offerta_sospesa,
					'esaurita' 				=> $row['Offerta_Esaurita'],
					"foto_1"				=> 'http://www.tuttoshopping.com/'.$row['Foto_1'],
					"Offerta_Deluxe" 		=> $row['Offerta_Deluxe'],
					"prezzo_di_listino"		=> number_format($row_op['Prezzo_di_Listino'], 2, '.', ''),
					"categoria"				=> $row['Categoria'],
					"prezzo_al_pubblico"	=> number_format($row_op['Prezzo_al_Pubblico'], 2, '.', ''),
					"stampati"				=> $CouponStampati,
					"Opzioni"=>array(
						"id"				=> $row_op['id'],
						"Data_da"			=> $row_op['Data_da'],
						"Titolo"			=> $titolo,
						"Prezzo_al_Pubblico"=> $prezzo_al_pubblico,
						"Offerta" 			=> $row_op['Offerta'],
						"Prezzo_di_Listino" => $prezzo_di_listino
					)
				);
			}else if($row['Offerta_Esaurita'] === 1){
				$coupon[] = array(
					"id" 					=> $row['id'],
					"nome" 					=> $nome,
					'sospesa' 				=> $offerta_sospesa,
					'esaurita' 				=> $row['Offerta_Esaurita'],
					"foto_1"				=> 'http://www.tuttoshopping.com/'.$row['Foto_1'],
					"Offerta_Deluxe" 		=> $row['Offerta_Deluxe'],
					"prezzo_di_listino"		=> number_format($row_op['Prezzo_di_Listino'], 2, '.', ''),
					"categoria"				=> $row['Categoria'],
					"prezzo_al_pubblico"	=> number_format($row_op['Prezzo_al_Pubblico'], 2, '.', ''),
					"stampati"				=> $CouponStampati,
					"Opzioni"=>array(
						"id"				=> $row_op['id'],
						"Data_da"			=> $row_op['Data_da'],
						"Titolo"			=> $titolo,
						"Prezzo_al_Pubblico"=> $prezzo_al_pubblico,
						"Offerta" 			=> $row_op['Offerta'],
						"Prezzo_di_Listino" => $prezzo_di_listino
					)
				);
			}else{
				$coupon[] = array(
					"id" 					=> $row['id'],
					"nome" 					=> $nome,
					'sospesa' 				=> $offerta_sospesa,
					'esaurita' 				=> $row['Offerta_Esaurita'],
					"foto_1"				=> 'http://www.tuttoshopping.com/'.$row['Foto_1'],
					"Offerta_Deluxe" 		=> $row['Offerta_Deluxe'],
					"prezzo_di_listino"		=> number_format($row_op['Prezzo_di_Listino'], 2, '.', ''),
					"categoria"				=> $row['Categoria'],
					"prezzo_al_pubblico"	=> number_format($row_op['Prezzo_al_Pubblico'], 2, '.', ''),
					"stampati"				=> $CouponStampati,
					"Opzioni"=>array(
						"id"				=> $row_op['id'],
						"Data_da"			=> $row_op['Data_da'],
						"Titolo"			=> $titolo,
						"Prezzo_al_Pubblico"=> $prezzo_al_pubblico,
						"Offerta" 			=> $row_op['Offerta'],
						"Prezzo_di_Listino" => $prezzo_di_listino
					)
				);
			};
		};
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
}else{
	echo "Error ".curl_error($curl);
}
?>