<?php
header('Content-Type: application/json;charset=utf-8');
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



if (isset($_GET['nome']) && isset($_GET['cognome']) && isset($_GET['provincia']) && isset($_GET['email']) && isset($_GET['password']) && isset($_GET['confpassword']) && isset($_GET['privacy']) && isset($_GET['newslettercommerciale']))
{
	$nome = $_GET['nome'];
	$cognome = $_GET['cognome'];
	$email = $_GET['email'];
	$provincia = $_GET['provincia'];
	$password = $_GET['password'];
	$confpassword = $_GET['confpassword'];
	$privacy = $_GET['privacy'];
	$newsletter = $_GET['newsletter'];
	$newslettercommerciale = $_GET['newslettercommerciale'];
	$sql = "SELECT * from Provincia WHERE id='".$provincia."'";
	$stmt = sqlsrv_query($conn, $sql);
	$row_count = sqlsrv_num_rows($stmt);
	if ($row_count !== 0){
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		$nomeProvincia = $row['Nome'];
		list($userName, $mailDomain) = split("@", $email); 
		if (checkdnsrr($mailDomain, "MX")) {
			if($newslettercommerciale === 'on'){
				$newslettercommerciale = '1';
			} else {
				$newslettercommerciale = '0';
			}
			if ($password !== $confpassword){
				$status = 'error 1';
				$message = "Le password non coincidono";
			} else {
				$nome = sanitize($nome);
				$cognome = sanitize($cognome);
				if($newsletter === 'on'){
					$newsletter = '1';
				} else {
					$newsletter = '0';
				}
				$sql2 = "SELECT ui.*, (SELECT COUNT(*) FROM utenti_iscritti WHERE email = '".$email."') as x FROM utenti_iscritti as ui WHERE ui.email = '".$email."'";
				$stmt2 = sqlsrv_query($conn, $sql2);
				$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
				if($row2['x'] === 1){
					$status = 'error 2';
					$message = "Sei già registrato";
				} else {
					$sql3 = "INSERT INTO Utenti_iscritti (data_da,data_a,Utente,data_inserimento,posizione,abilita,email,citta,provincia,nome,cognome,password,Newsletter_commerciale,Newsletter,Facebook_login) VALUES ('".$dataoggi."','',1373,'".$dataoggi."',0,0,'".$email."','',".$provincia.",'".$nome."','".$cognome."','".$password."',".$newslettercommerciale.",".$newsletter.",0)";
					$stmt3 = sqlsrv_query($conn, $sql3);
					$sql4 = "SELECT * from Utenti_iscritti order by id Desc";
					$stmt4 = sqlsrv_query($conn, $sql4);
					$row4 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
					$idUtente = $row4['id'];
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
									  $testo .= "<b>Attiva subito il tuo account e scarica i nostri coupon gratis!!!</b></font></td>\n";
								  $testo .= "</tr>\n";
								  $testo .= "<tr>\n";
									$testo .= "<td colspan=\"2\"><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
								  $testo .= "</tr>\n";
								  $testo .= "<tr>\n";
									$testo .= "<td width=\"35%\"><a href=\"http://www.tuttoshopping.com/conferma.asp?id=".$idUtente."&email=".$email."\" title=\"Attiva\" target=\"_blank\"><img src=\"http://www.tuttoshopping.com/email/img/tab_attiva.jpg\" width=\"222\" height=\"42\" alt=\"img\" border=\"0\"/></a></td>\n";
									$testo .= "<td width=\"65%\">&nbsp;</td>\n";
								  $testo .= "</tr>\n";
								  $testo .= "<tr>\n";
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


					$testo2 .= "<html>\n";
					$testo2 .= "<head>\n";
					$testo2 .= "<title>Registrazione dall'app Tuttoshopping</title>\n";
					$testo2 .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
					$testo2 .= "</head>\n";
					$testo2 .= "<body style=\"font-size:16px; font-family:Arial; color:#000;\">\n";
					$testo2 .= "Un nuovo utente si &egrave; iscritto al sito di Tuttoshopping grazie all'app di Tuttoshopping<br/>\n";
					$testo2 .= "I suoi dati sono:<br/>\n";
					$testo2 .= "Nome: <b>".$nome."</b><br/>\n";
					$testo2 .= "Cognome: <b>".$cognome."</b><br/>\n";
					$testo2 .= "Email: <b>".$email."</b><br/>\n";
					if($nomeProvincia !== ''){
						$testo2 .= "Provincia: <b>".$nomeProvincia."</b><br/>\n";
					}
					if($newsletter !== '0'){
						$testo2 .= "L'utente ha aderito alle newsletter di offerte commerciali<br/>\n";
					}
					if($newslettercommerciale !== '0'){
						$testo2 .= "L'utente ha aderito alle newsletter di aziende terze</b><br/>\n";
					}
					$testo2 .= "Accesso da Facebook: No<br/>\n";
					$testo2 .= "</body>\n";
					$testo2 .= "</html>\n";
					$mittente2 = $email;
					$mittente = "no-reply@tuttoshopping.com";
					$destinatario2 = "coupon@tuttoshopping.com";
					function Invia_email ($mittente,$destinatario,$oggetto,$testo,$formato){
						require_once("phpmailer/PHPMailerAutoload.php");
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
					Invia_email ($mittente2,$destinatario2,$oggetto,$testo2,$formato);
						$status = 'ok';
						$message = "Registrazione effettuata con successo!";
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