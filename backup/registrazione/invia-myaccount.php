<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');

function sanitize ($data) {
	return htmlentities($data);
}
function myCheckDNSRR($hostName, $recType = '') 
{ 
  if(!empty($hostName)) { 
    if( $recType == '' ) $recType = "MX"; 
    exec("nslookup -type=$recType $hostName", $result); 
    // check each line to find the one that starts with the host 
    // name. If it exists then the function succeeded. 
    foreach ($result as $line) { 
      if(eregi("^$hostName",$line)) { 
        return true; 
      } 
    } 
    // otherwise there was no mail handler for the domain 
    return false; 
  }
  return false; 
}
$nome = $_GET['nome'];
$cognome = $_GET['cognome'];
$provincia = $_GET['provincia'];
$password = $_GET['password'];
$passwordnew = $_GET['passwordnew'];
$passwordnewconf = $_GET['passwordnewconf'];
$privacy = $_GET['privacy'];
$newsletter = $_GET['newsletter'];
$newslettercommerciale = $_GET['newslettercommerciale'];
$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);


if (isset($_GET['nome']) && isset($_GET['cognome']) && isset($_GET['provincia']) && isset($_GET['email']) && isset($_GET['password']) && isset($_GET['newsletter']) && isset($_GET['passwordnew']) && isset($_GET['newslettercommerciale']))
{
	$nome = $_GET['nome'];
	$cognome = $_GET['cognome'];
	$provincia = $_GET['provincia'];
	$password = $_GET['password'];
	$passwordnew = $_GET['passwordnew'];
	$passwordnewconf = $_GET['passwordnewconf'];
	$privacy = $_GET['privacy'];
	$newsletter = $_GET['newsletter'];
	$newslettercommerciale = $_GET['newslettercommerciale'];
	$email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
	$sql = "SELECT * from Provincia WHERE id='".$provincia."'";
	$stmt = sqlsrv_query($conn, $sql);
	$row_count = sqlsrv_num_rows($stmt);
	if ($row_count !== 0){
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		$nomeProvincia = $row['Nome'];
		list($userName, $mailDomain) = split("@", $email); 
		if (checkdnsrr($mailDomain, "MX")) {
			if($newslettercommerciale === 'on'){
				$newslettercommerciale = 1;
			} else {
				$newslettercommerciale = 0;
			}
			if ($passwordnew !== $passwordnewconf){
				$status = 'error 1';
				$message = "Le password non coincidono";
			} else {
				$nome = sanitize($nome);
				$cognome = sanitize($cognome);
				if($newsletter === 'on'){
					$newsletter = 1;
				} else {
					$newsletter = 0;
				}
				$sql2 = "SELECT *,(SELECT COUNT(*) FROM utenti_iscritti WHERE email = '".$email."') as x FROM utenti_iscritti WHERE email = '".$email."'";
				$stmt2 = sqlsrv_query($conn, $sql2);
				if( $stmt2 === false ) {
					if( ($errors = sqlsrv_errors() ) != null) {
						foreach( $errors as $error ) {
							echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
							echo "code: ".$error[ 'code']."<br />";
							echo "message: ".$error[ 'message']."<br />";
						}
					}
				}
				$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
				if($row2['x'] !== 1){
					$status = 'error 2';
					$message = "Non sei registrato";
				} else {
					$passworddb = $row2['Password'];
					$stato_newsletter_attuale = $row2['Newsletter'];
					if ($stato_newsletter_attuale === 1){
						$stato_newsletter_attuale = 1;
					} else {
						$stato_newsletter_attuale = 0;
					}
					
					$stato_newsletter_commerciale_attuale = $row2['Newsletter_commerciale'];
					
					if ($stato_newsletter_commerciale_attuale === 1){
						$stato_newsletter_commerciale_attuale = 1;
					} else {
						$stato_newsletter_commerciale_attuale = 0;
					}
					if ($passworddb === $password){
						if ($passwordnew === ''){
							$passwordnew = $passworddb;
						}
					} else {
						$status = 'error 6';
						$message = "Le password non coincidono";
					}
					$sql3 = "UPDATE Utenti_iscritti SET Nome = '".$nome."',Cognome = '".$cognome."',provincia = ".$provincia.",password='".$passwordnew."',newsletter = ".$newsletter.",newsletter_commerciale = ".$newslettercommerciale." WHERE email = '".$email."'";
					$stmt3 = sqlsrv_query($conn, $sql3);
					$oggetto = "Aggiornamento dati sul sito tuttoshopping.com";
	$oggetto2 = "Notifiche iscrizioni newsletter app";
	$formato = 1;
	$mittente = "coupon@tuttoshopping.com";
	$destinatario = $email;
			
	$mittente2 = $email;
	$testo = '';
	$testo2 = '';
	$testo .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	$testo .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	$testo .= "<head>\n";
	$testo .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
	$testo .= "<title>Aggiornamento dati</title>\n";
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
					$testo .= "<td colspan=\"2\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Ciao, hai appena aggiornato i dati del tuo accout:<br />\n";
					  $testo .= "Nome:<b>".$nome."</b>.<br/>\n";
					  $testo .= "Cognome:<b>".$cognome."</b>.<br/>\n";
					  $testo .= "Email:<b>".$email."</b>.<br/>\n";
					  $testo .= "Provincia:<b>".$nomeProvincia."</b>.<br/>\n";
					  $testo .= "Password:<b>".$passwordnew."</b>.<br/>\n";
					  if ($newsletter === 1){
						  $testo .= "Da questo momento riceverai le offerte sempre aggiornate  su Shopping, Benessere, Ristoranti, Tempo Libero, Professionisti....<br />\n";	
					  }
					  if ($newslettercommerciale === 1){
						  $testo .= "Da questo momento riceverai informazioni riguardo i nuovi prodotti ed eventi di TuttoShopping e informazioni commerciali di aziende terze selezionate direttamente da TuttoShopping o da societ&agrave; partner<br />\n";
					  }
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				 
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_002.jpg\" width=\"56\" height=\"55\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Grazie,<br />\n";
					  $testo .= "Lo staff di Tuttoshopping</font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\" bgcolor=\"#f1dad4\" align=\"center\" ><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; padding:10px; display:block\"> Hai bisogno d'aiuto? <a href=\"http://www.tuttoshopping.com/servizio_clienti.asp\" target=\"_blank\" style=\" color:#e64f29; text-decoration:none;\">Contatta il nostro servizio clienti</a></font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_003.jpg\" width=\"52\" height=\"90\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				$testo .= "</table></td>\n";
			$testo .= "</tr>\n";
		  $testo .= "</table></td>\n";
	  $testo .= "</tr>\n";
	$testo .= "</table>\n";
	$testo .= "</body>\n";
	$testo .= "</html>\n";
					function Invia_email ($mittente,$destinatario,$oggetto,$x,$formato){
						require_once("phpmailer/PHPMailerAutoload.php");
						$mail = new PHPMailer();
						$mail->CharSet = 'UTF-8';
						$mail->IsSMTP();
						$mail->Host       = "195.96.216.100";
						$mail->SMTPDebug  = 0;
						$address = $destinatario;
						$mail->SetFrom($mittente, "Tuttoshopping");
						$mail->Subject = $oggetto;
						$mail->MsgHTML($x);
						$mail->AddAddress($destinatario, $nome." ".$cognome);
						$mail->Send();
					}
						if($stato_newsletter_commerciale_attuale != $newslettercommerciale || $stato_newsletter_attuale != $newsletter){
							$testo2 .= "<html>\n";
							$testo2 .= "<head>\n";
							$testo2 .= "<title>Registrazione newsletter al sito tuttoshopping.com tramite app</title>\n";
							$testo2 .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
							$testo2 .= "</head>\n";
							$testo2 .= "<body style=\"font-size:16px; font-family:Arial; color:#000;\">\n";
							if ($stato_newsletter_attuale !== $newsletter || $stato_newsletter_commerciale_attuale != $newslettercommerciale){
								if($newsletter === 0){
									$testo2 .= "L'utente con email ".$email." si &egrave; cancellato dalla newsletter di TuttoShopping<br/>\n";
								} else if ($newsletter === 1){
									$testo2 .= "L'utente con email ".$email." si &egrave; iscritto alla newsletter di TuttoShopping<br/>\n";
								}
								if($newslettercommerciale === 0){
									$testo2 .= "L'utente con email ".$email." si &egrave; cancellato dalla newsletter di aziende terze<br/>\n";
								} else if ($newslettercommerciale === 1){
									$testo2 .= "L'utente con email ".$email." si &egrave; iscritto alla newsletter di aziende terze<br/>\n";
								} 
							$testo2 .= "</body>\n";
							$testo2 .= "</html>\n";
						$destinatario2 = "coupon@tuttoshopping.com";
						Invia_email ($mittente2,$destinatario2,$oggetto2,$testo2,$formato);
							}
						}
						Invia_email ($mittente,$email,$oggetto,$testo,$formato);
						$status = 'ok';
						$message = "Dati Modificati!";
				}
			}
		} else {
			$status = 'error 3';
			$message = "Questa mail non esiste!";
		}
	} else {
		$status = 'error 4';
		$message = "La provincia non esiste";
	}
} else{
	$status = 'error 5';
	$message = "Tutti i campi devono essere compilati";
}
$data = array(
	'status' => $status,
	'message' => $message
);
$resultadosJson = json_encode($data);
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
?>