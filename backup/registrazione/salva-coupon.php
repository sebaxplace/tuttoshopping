<?php
header('Content-Type: application/json;charset=utf-8');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
$data_oggi = date('Ymd');
$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
$idOfferta = $_GET['idOfferta'];
$idOpzione = $_GET['idOpzione'];

$sql = "SELECT * FROM Utenti_Iscritti WHERE email = '".$email."' AND Abilita = 1";
$stmt = sqlsrv_query( $conn, $sql );
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
$idUtente = $row['id'];
$sql4 = "SELECT *, (SELECT COUNT(*) FROM Acquisti WHERE abilita = 1 AND data_da <= ".$data_oggi.") as count FROM Acquisti WHERE abilita = 1 AND data_da <= ".$data_oggi." ORDER BY id DESC";
$stmt4 = sqlsrv_query( $conn, $sql4 );
			/*if( $stmt === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}*/
$row4 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
$sql5 = "SELECT o.*, v.*  FROM opzione o LEFT JOIN vista_offerte v ON v.id = o.Offerta WHERE o.id = ".$idOpzione." and o.abilita = 1 and o.data_da <= ".$data_oggi."";
$stmt5 = sqlsrv_query( $conn, $sql5 );
if( $stmt5 === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
$row5 = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
$Prezzo_di_listino = $row5['Prezzo_di_Listino'];
if ($Prezzo_di_listino !== 0){
	$risparmio = $Prezzo_di_listino - $row5['Prezzo_al_Pubblico'];
	$x = $risparmio*100;
	$x = $x / $row5['Prezzo_di_Listino'];
	$nome = $row5['Titolo'];
	$titolo_offerta = $row5['Nome'];
	$Prezzo_al_Pubblico = $row5['Prezzo_al_Pubblico'];
	$Risparmio = $risparmio;
	$Sconto = $x;
}

if($row4['count'] !== 0){
	$codice_coupon = $row4['Codice_Coupon'];
}
if ($codice_coupon === ""){
	$codice_coupon = "TS10001";
}
if(strlen($codice_coupon) == 7){
	$numero_Codice_Coupon = substr($codice_coupon, 2, 6 );
} else if (strlen($codice_coupon) == 8){
	$numero_Codice_Coupon = substr($codice_coupon, 2, 7 );
} else if (strlen($codice_coupon) == 9){
	$numero_Codice_Coupon = substr($codice_coupon, 2, 8 );
} else if (strlen($codice_coupon) == 10){
	$numero_Codice_Coupon = substr($codice_coupon, 2, 9 );
}
$numero_Codice_Coupon = $numero_Codice_Coupon +1;
$codice_coupon_new = "TS".$numero_Codice_Coupon;

$registra = 1;
if ($idUtente !== ''){
	$registra = 0;
}

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
$row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
if ($row2['count'] === 0){
	$registra = 1;
} else {
	$registra = 0;
}

echo $registra;
if ($registra === 1){
	$sql3 = "INSERT INTO Acquisti (Data_da,Data_a,Utente,Data_inserimento,Posizione,Abilita,Offerta,Opzione,Mail_acquirente,Codice_Coupon) VALUES ('".$data_oggi."','','".$idUtente."','".$data_oggi."',0,1,".$idOfferta.",".$idOpzione.",'".$email."','".$codice_coupon_new."')";
	$stmt3 = sqlsrv_query( $conn, $sql3 );
		if( $stmt3 === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
				}
			}
		}
			$oggetto = "Stampa coupon offerta dal sito tuttoshopping.com";
			$oggetto2 = "Stampa coupon offerta dall'app TuttoShopping";
					$formato = 1;
					$mittente = "coupon@tuttoshopping.com";
					$mittente2 = "no-reply@tuttoshopping.com";
					$destinatario = $email;
					$destinatario2 = "coupon@tuttoshopping.com";
					$testo = '';
					$testo2 = '';
					$testo .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
					$testo .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
					$testo .= "<head>\n";
					$testo .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
					$testo .= "<title>Stampa coupon offerta dal sito tuttoshopping.com</title>\n";
					$testo .= "</head>\n";

					$testo .= "<body bgcolor=\"#E8E8E8\">\n";
					$testo .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bgcolor=\"#E8E8E8\">\n";
					$testo .= "  <tr>\n";
					$testo .= "    <td style=\"padding-top:25px;\">\n";
						  
					$testo .= "      <table width=\"635\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\" bgcolor=\"#FFFFFF\">\n";
					$testo .= "        <tr>\n";
					$testo .= "          <td width=\"615\">\n";
								
					$testo .= "            <table width=\"96%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><a href=\"\" title=\"\" target=\"_blank\">\n";
					$testo .= "                <img src=\"http://www.tuttoshopping.com/email/img/header2.jpg\" width=\"633\" height=\"85\" alt=\"img\" border=\"0\"/>\n";
					$testo .= "                </a>\n";
									
					$testo .= "                </td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\" valign=\"top\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\"><b>Gentile utente grazie per aver aderito alla nostra offerta.</b></font></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Non aspettare, recati al punto vendita indicato nel coupon e approfitta di questa straordinaria offerta.<br /><b>Non hai ancora stampato il coupon?</b> <b><a href=\"http://www.tuttoshopping.com/stampa-coupon-da-email.asp?idUtente=".$idUtente."&idOfferta=".$idOfferta."&idOpzione=".$idOpzione."\" target=\"_blank\" style=\" color:#e64f29; text-decoration:none;\">Clicca qui per stamparlo</a></b></font></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "             <tr>\n";
					$testo .= "               <td colspan=\"2\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Grazie,<br /> Lo staff di Tuttoshopping</font></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/riga.jpg\" width=\"634\" height=\"25\" alt=\"\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td width=\"82%\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\"><b style=\"color:#e64f29;\">Ti sembra una buona offerta ?</b><br />Invia subito questa offerta a tutti i tuoi amici adesso:<br /><b><a href=\"http://www.tuttoshopping.com/offerta.asp?idOff=".$idOfferta."&mess2=invitaamico\" target=\"_blank\" style=\" color:#e64f29; text-decoration:none;\">Clicca qui</a></b></font></td>\n";
					$testo .= "                <td width=\"18%\"><img src=\"http://www.tuttoshopping.com/email/img/Email-coupon-stampato.jpg\" width=\"83\" height=\"70\" alt=\"\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_003.jpg\" width=\"52\" height=\"90\" alt=\"\"/></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "              <tr>\n";
					$testo .= "                <td colspan=\"2\" align=\"center\" bgcolor=\"#f1dad4\" ><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; padding:10px; display:block\"> Hai bisogno d'aiuto? <a href=\"http://www.tuttoshopping.com/servizio_clienti.asp\" target=\"_blank\" style=\" color:#e64f29; text-decoration:none;\">Contatta il nostro servizio clienti</a></font></td>\n";
					$testo .= "              </tr>\n";
					$testo .= "            </table></td>\n";
					$testo .= "        </tr>\n";
					$testo .= "      </table></td>\n";
					$testo .= "  </tr>\n";
					$testo .= "</table>\n";
					$testo .= "</body>\n";
					$testo .= "</html>\n";
					
$testo2 .= "<html>\n";
$testo2 .= "<head>\n";
$testo2 .= "<title>Stampa coupon offerta dal sito tuttoshopping.com</title>\n";
$testo2 .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
$testo2 .= "</head>\n";
$testo2 .= "<body>\n";
$testo2 .= "Un utente ha appena aderito ad un offerta del sito TuttoShopping.com tramite l'app<br/><br/>\n";
$testo2 .= "I dati dell'offerta sono:<br/><br/>\n";
$testo2 .= "Offerta: <b>".$titolo_offerta."</b><br/>\n";
$testo2 .= "Opzione: <b>".$nome."</b><br/>\n";
$testo2 .= "Codice coupon: <b>".$codice_coupon_new."</b><br/>\n";
$testo2 .= "Email: <b>".$email."</b><br/><br/>\n";
$testo2 .= "<a href=\"http://www.tuttoshopping.com/offerta.asp?idOff=".$row5['Offerta']."\">Clicca qui</a> per vedere l'offerta in questione.<br/><br/>\n";
$testo2 .= "</body>\n";
$testo2 .= "</html>\n";

					function Invia_email ($mittente,$destinatario,$oggetto,$testo,$formato){
						require_once("phpmailer/PHPMailerAutoload.php");
						$mail = new PHPMailer();
						$mail->IsSMTP();
						$mail->SMTPAuth   = true;
						$mail->Host       = "smtpout.tuttoshopping.com";
						$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
						$mail->Username   = "smtp@tuttoshopping.com"; // SMTP account username
						$mail->Password   = "gIHahiRU";        // SMTP account password
						$mail->SMTPDebug  = 0;
						$address = $destinatario;
						$mail->SetFrom($mittente, "Tuttoshopping");
						$mail->Subject = $oggetto;
						$mail->MsgHTML($testo);
						$mail->AddAddress($destinatario, $nome." ".$cognome);
						$mail->Send();
					}
					Invia_email ($mittente,$destinatario,$oggetto,$testo,$formato);
					$status = 'ok';
					$message = "Coupon acquistato!";
					Invia_email ($mittente2,$destinatario2,$oggetto2,$testo2,$formato);
					header('Location: http://www.tuttoshopping.com/acquista-pdf.aspx?idOfferta='.$idOfferta.'&idOpzione='.$idOpzione.'&idUtente='.$idUtente.'');
	}else{
		$status = 'error';
		$message = "Coupon già acquistato";
	}

$data = array(
	'status' => $status,
	'message' => $message
);
$resultadosJson = json_encode($data);
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>