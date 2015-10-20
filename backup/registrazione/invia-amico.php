<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once('connect.php');
$dataoggi = date('dmY');
$email_amico = $_GET['email_amico'];
$id_offerta = $_GET['id_offerta'];
$messaggio = $_GET['messaggio'];
$id_utente = $_GET['id_utente'];

$sql = "SELECT *, (SELECT COUNT(*) FROM utenti_iscritti WHERE  Abilita = 1 AND Email = '".$id_utente."') as count FROM utenti_iscritti WHERE  Abilita = 1 AND Email = '".$id_utente."'";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
if ($row['count'] !== 0){
	$email = $row['Email'];
	$id = $row['id'];
	//echo $email;
	if($email_amico !== ''){
		$array_amici =  explode(";", $email_amico);
		foreach ($array_amici as $k){
			if (strpos($k,'@') !== false && strpos($k,'.') !== false) {
				$oggetto = "Invito a visitare il sito tuttoshopping.com";
				$formato = 1;
				$mittente = $email;
$testo = "";
$testo .= "<html>\n";
$testo .= "<head>\n";
$testo .= "<title>Invito a visitare il sito tuttoshopping.com</title>\n";
$testo .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
$testo .= "</head>\n";
$testo .= "<body style=\"background:#E8E8E8; font-size:16px; font-family:Arial; color:#000;\">\n";
$testo .= "<table style=\"background:#fff; width:655px; height:544px; margin-left:auto; margin-right:auto;margin-top:30px;\" cellpadding=\"10\"  border=\"0\" cellspacing=\"0\" align=\"center\">\n";
$testo .= "<tr height=\"100\">\n";
$testo .= "<td align=\"center\"><img src=\"http://www.tuttoshopping.com/email/img/header.jpg\" /></td>";
$testo .= "</tr>\n";
$testo .= "<tr>\n";
$testo .= "<td width=\"75%\">Un tuo amico ha trovato un'offerta che potrebbe interessarti sul sito tuttoshopping<br/><a href=\"http://www.tuttoshopping.com/offerta.asp?idOff=".$id_offerta."\" >Guarda subito l'offerta!</a><br/><br/></td>\n";
$testo .= "</tr>\n";

$testo .= "<tr>\n";
$testo .= "<td>\n";
$testo .= "<table>\n";
$testo .= "<tr>\n";
$testo .= "<td width=\"75%\"><b>I suoi dati sono:</b><br/>\n";
$testo .= "<b>Email</b>: ".$email."<br/>\n";
if ($messaggio !== ""){
	$testo .= "<b>Messaggio</b>: ".rawurldecode($messaggio)."<br/>\n";
}
$testo .= "</td>\n";
$testo .= "</tr>\n";
$testo .= "</table>\n";
$testo .= "</td>\n";
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
				Invia_email ($mittente,$email_amico,$oggetto,$testo,$formato);

				$status = 'ok';
				$message = "Messaggio inviato!";
			}
		}

	}
}else{
	$status = 'error';
	$message = "Messaggio non inviato!";
}

$data = array(
	'status' => $status,
	'message' => $message
);
$resultadosJson = json_encode($data);
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>