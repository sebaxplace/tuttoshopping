<?php
header('Content-Type: application/json;charset=utf-8');
header("Access-Control-Allow-Origin: *");

require_once('connect.php');



if($conn){
		$cat = '';
		$zl = '';
		$zl_order = ' ORDER BY Posizione ASC, Data_da DESC';
		$zo = '';
	if (isset($_GET['q']) && $_GET['q'] !== "undefined"){
		$categoria = $_GET['q'];
		if($categoria === '0'){
			$cat = " ";
		} else {
			$cat = " AND (Categoria LIKE '".$categoria."' OR Categoria LIKE '%".$categoria."' OR Categoria LIKE '".$categoria."%' OR Categoria LIKE '%".$categoria."%') ";
		}
	}
	if (isset($_GET['p']) && $_GET['p'] !== ""){
			
		$zona = $_GET['p'];
  		$contador_caracter = strlen($zona);

  		if($contador_caracter >9 && $contador_caracter <24){
  			$zl_order = '';
  			$divisor = explode('/',$zona);
  			$latp = $divisor[0];
  			$longp = $divisor[1];
			$zl = ", ( 6371 * acos( cos( radians('".$latp."') ) * cos( radians( Sedi.Latitudine ) ) * cos( radians( Sedi.Longitudine ) - radians('".$longp."') ) + sin( radians('".$latp."') ) * sin( radians( Sedi.Latitudine ) ) ) ) AS distance";
			$stmt3 = sqlsrv_query( $conn, $sql3 );
			while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC) ) {
				//print_r($row3);
			}
			
		}
  		if($contador_caracter ==1){
			$zo = " AND (zona LIKE '".$zona."' OR zona LIKE '%".$zona."' OR zona LIKE '".$zona."%' OR zona LIKE '%".$zona."%')";
		}
	}
	$sql2 = "SELECT vista_offerte.*, Sedi.Latitudine, Sedi.Longitudine ".$zl."
			FROM vista_offerte 
			LEFT JOIN Sedi ON Sedi.id = vista_offerte.Sede 
			WHERE vista_offerte.Abilita = 1 AND vista_offerte.Offerta_Esaurita=0 ".$cat.$zo.$zl_order."";
	
	
	$stmt = sqlsrv_query( $conn, $sql2 );
			if( $stmt === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
		//echo $sql2;
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		//print_r($row);
		$x = substr($row['Data_da'], -2 ); //giorno
		$y = substr($row['Data_da'], 0, 4); //anno
		$z = substr($row['Data_da'], -4, 2); //mese
		$f = str_replace(":",".",$row['Ora_di_Attivazione']); //ora
		$f2 = str_replace(":",":",$row['Ora_di_Attivazione']); //ora
		$strtotime = $x."-".$z."-".$y." ".$row['Ora_di_Attivazione'];
		$data_inizio = strtotime($strtotime);
		$data_inizio_offerta_completa = $x."-".$z."-".$y." ".$f2;
		$data_fine_offerta_completa = date('d-m-Y H:i',strtotime(''.$strtotime.' +'.$row['Durata'].' hours'));
		$data_fine_offerta_completa_off_sospesa = date('d-m-Y H:i',strtotime(''.$data_fine_offerta_completa.' +0 hours'));
		$data_oggi = date('d-m-Y');
		$data_oggi2 = date('Ymd');
		$ora_oggi = date('H:i', strtotime('+1 hour'));
		$ora_oggi2 = date('H:i');
		$oraoggi = date('Hi', strtotime('+2 hour')); 
		$data_ora_corrente = $data_oggi." ".$oraoggi;
		$offerta_sospesa = 0;
		
		 
		$g = str_replace(":","",$row['Ora_di_Attivazione']);
		$data_ora_corrente2 = $data_ora_corrente;
		$data_ora_corrente = strtotime($data_ora_corrente);
		$data_fine_offerta_completa_off_sospesa = strtotime($data_fine_offerta_completa_off_sospesa);
		if ($data_fine_offerta_completa_off_sospesa >= $data_ora_corrente){
			if($data_inizio <= $data_ora_corrente){
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
			$sql_count = "SELECT COUNT(*) as CouponStampati FROM acquisti WHERE offerta = ".$row['id']." AND Abilita = 1 AND Data_da <= '".$dataoggi."'";
			$stmt3 = sqlsrv_query($conn, $sql_count);
			$row_sql_count = sqlsrv_num_rows( $stmt3 );
			$row_count_cop = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
			if ($row_sql_count !== 0){
				$CouponStampati = $row_count_cop['CouponStampati'];
			}
			$nome = str_replace('&euro;','',$row['Nome']);
			$titolo = $row_op['Titolo'];
				$coupon[] = array(
					"id" 					=> $row['id'],
					"nome" 					=> $nome,
					'sospesa' 				=> $offerta_sospesa,
					'esaurita' 				=> $row['Offerta_Esaurita'],
					"foto_1"				=> 'http://www.tuttoshopping.com/'.$row['Foto_1'],
					"Offerta_Deluxe" 		=> $row['Offerta_Deluxe'],
					"prezzo_di_listino"		=> number_format($row_op['Prezzo_di_Listino'], 2, '.', ''),
					"categoria"				=> $row['Categoria'],
					"data_da"				=> $row['Data_da'],
					"data_da2"				=> $data_oggi2,
					"Posizione"				=> $row['Posizione'],
					"prezzo_al_pubblico"	=> number_format($row_op['Prezzo_al_Pubblico'], 2, '.', ''),
					"zona"					=> $row['Zona'],
					"stampati"				=> $CouponStampati,
					"oraoggi"				=> $oraoggi,
					"g"						=> $g,
					"data_inizio"		=> $strtotime,
					"data_ora_corrente"	=> $data_ora_corrente2,
					"conto"					=> $oraoggi - $g
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
	//print_r($coupon);
	 
		echo json_encode($coupon, JSON_PRETTY_PRINT);
	

	// $structure is now utf8 encoded*/
	

 
}else{
	echo "Connection could not be established.<br />";
	die( print_r( sqlsrv_errors(), true));
}

?>