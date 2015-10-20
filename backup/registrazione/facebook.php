<?php
require_once('connect.php');
$data_oggi = date('Ymd');
$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
$nome = $_GET['nome'];
$cognome = $_GET['cognome'];
$sql = "SELECT *, (SELECT COUNT(*) FROM Utenti_Iscritti WHERE email = '".$email."' ) as x FROM Utenti_Iscritti WHERE email = '".$email."'";
$stmt = sqlsrv_query( $conn, $sql );

$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
if($row['x'] === 1){
} else {
	$sql3 = "INSERT INTO Utenti_iscritti (data_da,data_a,Utente,data_inserimento,posizione,abilita,email,citta,provincia,nome,cognome,password,Newsletter_commerciale,Newsletter,Facebook_login) VALUES ('".$data_oggi."','',1373,'".$data_oggi."',0,1,'".$email."','','','".$nome."','".$cognome."','', 0, 1,1)";
	$stmt3 = sqlsrv_query($conn, $sql3);
	if( $stmt3 === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
	$oggetto = "Registrazione al sito tuttoshopping.com dall'app";
	$formato = 1;
	$mittente = "coupon@tuttoshopping.com";
	$destinatario = $email;
	$testo = '';
	$testo2 = '';
	$testo .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	$testo .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	$testo .= "<head>\n";
	$testo .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
	$testo .= "<title>Registrazione account</title>\n";
	$testo .= "</head>\n";
	
	$testo .= "<body bgcolor=\"#E8E8E8\">\n";
	$testo .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bgcolor=\"#E8E8E8\">\n";
	  $testo .= "<tr>\n";
		$testo .= "<td style=\"padding-top:25px;\">\n";
		  
		  $testo .= "<table width=\"635\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\" bgcolor=\"#FFFFFF\">\n";
			$testo .= "<tr>\n";
			  $testo .= "<td width=\"615\">\n";
				
				$testo .= "<table width=\"96%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><a href=\"\" title=\"\" target=\"_blank\">\n";
					$testo .= "<img src=\"http://www.tuttoshopping.com/email/img/header.jpg\" width=\"633\" height=\"85\" alt=\"img\" border=\"0\"/></a></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Ciao, ti sei registrato al sito di Tuttoshopping con il seguente indirizzo mail:<br />\n";
					  $testo .= "<b>".$email."</b>.<br/>\n";
					  if ($newsletter !== "0"){
						$testo .= "Da questo momento riceverai le offerte sempre aggiornate  su Shopping, Benessere, Ristoranti, Tempo Libero, Professionisti....<br />\n";
					  }
					  if ($newslettercommerciale !== "0"){
						$testo .= "Da questo momento riceverai informazioni riguardo i nuovi prodotti ed eventi di TuttoShopping e informazioni commerciali di aziende terze selezionate direttamente da TuttoShopping o da società partner<br />\n";
					  }								  
					  $testo .= "<b>Scarica i nostri coupon gratis!!!</b></font></td>\n";
				  $testo .= "</tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_002.jpg\" width=\"56\" height=\"55\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Grazie,<br />\n";
					  $testo .= "Lo staff di Tuttoshopping</font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\" bgcolor=\"#f1dad4\" align=\"center\" ><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; padding:10px; display:block\"> Hai bisogno d'aiuto? <a href=\"http://www.tuttoshopping.com/servizio_clienti.asp\" target=\"_blank\" style=\" color:#e64f29; text-decoration:none;\">Contatta il nostro servizio clienti</a></font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_003.jpg\" width=\"52\" height=\"90\"/></td>\n";
				  $testo .= "</tr>\n";
				$testo .= "</table></td>\n";
			$testo .= "</tr>\n";
		  $testo .= "</table></td>\n";
	  $testo .= "</tr>\n";
	$testo .= "</table>\n";
	$testo .= "</body>\n";
	$testo .= "</html>\n";
					function Invia_email ($mittente,$destinatario,$oggetto,$testo,$formato){
						require("phpmailer/PHPMailerAutoload.php");
						$mail = new PHPMailer();
						$mail->CharSet = 'UTF-8';
						$mail->IsSMTP();
						$mail->Host       = "195.96.216.100";
						$mail->SMTPDebug  = 0;
						$address = $destinatario;
						$mail->SetFrom($mittente, "Tuttoshopping");
						$mail->Subject = $oggetto;
						$mail->MsgHTML($testo);
						$mail->AddAddress($destinatario, $nome." ".$cognome);
						$mail->Send();
					}
					Invia_email ($mittente,$email,$oggetto,$testo,$formato);
}
?>