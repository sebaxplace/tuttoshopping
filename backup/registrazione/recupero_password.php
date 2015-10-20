<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
$dataoggi = date('dmY');
$email = $_GET['email'];

function randomPassword() {
    $alphabet = "0123456789ABCDEFGH";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
$sql = "SELECT *, (SELECT COUNT(*) FROM Utenti_iscritti where Email = '".$email."') as count FROM Utenti_iscritti where Email = '".$email."'";
$stmt = sqlsrv_query($conn, $sql);

$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
if ($row['count'] !== 0){
	$password = $row['password'];
	if ($password !== ""){
		$rndpsw = randomPassword();
		$sql = "UPDATE Utenti_iscritti SET Password = '".$rndpsw."',Newsletter=1 WHERE Email = '".$email."'";
		$stmt = sqlsrv_query($conn, $sql);
	$oggetto = "Recupero dati di accesso al sito tuttoshopping.com";
	$formato = 1;
	$mittente = "coupon@tuttoshopping.com";
	$destinatario = $email;
	$testo = '';
	$testo .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	$testo .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	$testo .= "<head>\n";
	$testo .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
	$testo .= "<title>Richiesta password</title>\n";
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
					$testo .= "<td><a href=\"\" title=\"\" target=\"_blank\"><img src=\"http://www.tuttoshopping.com/email/img/header2.jpg\" width=\"633\" height=\"85\" alt=\"img\" border=\"0\"/></a></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td valign=\"top\"><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\"><b>Richiesta password</b></font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Gentile utente di seguito i suoi dati di accesso per accedere a Tuttoshopping</font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#e64f29; padding:10px 10px 10px 0px; display:block\"><b>Email: ".$email."</b></font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#e64f29; padding:10px 10px 10px 0px; display:block\"><b>Password: ".$rndpsw."</b></font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><font face=\"Arial, sans-serif\" style=\"font-size:16px; padding:10px 10px 10px 0px; display:block\"><b>La password che ti abbiamo assegnato &egrave; complicata?</b><br/>Nessun problema. <a href=\"http://www.tuttoshopping.com/my_account.asp\" target=\"_blank\">Clicca qui</a> per modificarla come preferisci.</font></td>\n";
				  $testo .= "</tr>\n";
					$testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; line-height:25px;\">Grazie,<br />Lo staff di Tuttoshopping</font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_001.jpg\" width=\"40\" height=\"35\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td bgcolor=\"#f1dad4\" align=\"center\" ><font face=\"Arial, sans-serif\" style=\"font-size:16px; color:#2B2B2B; padding:10px; display:block\"> Hai bisogno d'aiuto? <a href=\"http://www.tuttoshopping.com/servizio_clienti.asp\" target=\"_blank\" style=\" color:#e64f29; text-decoration:none;\">Contatta il nostro servizio clienti</a></font></td>\n";
				  $testo .= "</tr>\n";
				  $testo .= "<tr>\n";
					$testo .= "<td><img src=\"http://www.tuttoshopping.com/email/img/spazio_003.jpg\" width=\"52\" height=\"90\" alt=\"img\"/></td>\n";
				  $testo .= "</tr>\n";
				$testo .= "</table></td>\n";
			$testo .= "</tr>\n";
		  $testo .= "</table></td>\n";
	  $testo .= "</tr>\n";
	$testo .= "</table>\n";
	$testo .= "</body>\n";
	$testo .= "</html>\n";
					function Invia_email ($mittente,$email_amico,$oggetto,$testo,$formato){
					require("phpmailer/PHPMailerAutoload.php");
					$mail = new PHPMailer();
					$mail->CharSet = 'UTF-8';
					$mail->IsSMTP();
					$mail->Host       = "195.96.216.100";
					$mail->SMTPDebug  = 0;
					$address = $email_amico;
					$mail->SetFrom($mittente, "Tuttoshopping");
					$mail->Subject = $oggetto;
					$mail->MsgHTML($testo);
					$mail->AddAddress($email_amico, $nome." ".$cognome);
					$mail->Send();
				}
				Invia_email ($mittente,$destinatario,$oggetto,$testo,$formato);
				$status = 'ok';
				$message = "ok!";
	}
} else {
	$status = "Errore";
	$message = "La mail non esiste";
}

$data = array(
	'status' => $status,
	'message' => $message
);

$resultadosJson = json_encode($data);
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';
?>