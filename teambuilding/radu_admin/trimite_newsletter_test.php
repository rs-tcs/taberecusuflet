<?php
include("includes/Database.php");
$db = new Database();

$select = "SELECT `editor`,`nume_sablon` FROM `sabloane_newsletter` WHERE `activ` = 1";
$rezultat = $db->execute($select);

$row = $db->getAssoc($rezultat);
$newsletter_code = base64_decode($row['editor']);

	if($db->getCount($rezultat) == 1){
	
	$to = "dangrecu1975@gmail.com";	
	$from = "office@teamexpert.ro";
	$numele = "Newsletter Teamexpert";
	$subiect = $row['nume_sablon']; 
	
					// se reinterpreteaza variabilele din codul newsletter-ului
					
					$mail_cod = base64_encode('75GD37');
					$arrData = array(
						'cod_unic' => $mail_cod				
					);
					
					function replace($i){
						GLOBAL $arrData;
						return $arrData[$i[1]] ;
					}
					
					$newsletter_code = preg_replace_callback('/{\$([^}]+)}/', "replace", $newsletter_code );
					 
	
	$mesaj = $newsletter_code;
	
	
	$headers = "FROM: $numele <\"$from\"> \r\n";
	$headers.= "Reply-To:{$from} \r\n";
	$headers.= "CC: {$to} \r\n";
	$headers.= "Bcc: {$to} \r\n";
	$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
	$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
	$headers.= "Content-Transfer-Encoding: 8bit\n"; 
	
	
								
	$result = mail($to,$subiect,$mesaj,$headers);
	
	header("Location: editeaza_newsletter.php?msg=ok");
	exit();
	}
	else
	{
	$to = "dangrecu1975@gmail.com";	
	$from = "office@teamexpert.ro";
	$numele = "Newsletter Teamexpert";
	$subiect = "Mesaj newsletter TeamXpert"; 
	$mesaj = "Newsletter-ul nu este activ";
	
	
	$headers = "FROM: $numele <\"$from\"> \r\n";
	$headers.= "Reply-To:{$from} \r\n";
	$headers.= "CC: {$to} \r\n";
	$headers.= "Bcc: {$to} \r\n";
	$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
	$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
	$headers.= "Content-Transfer-Encoding: 8bit\n"; 
	
	
								
	$result = mail($to,$subiect,$mesaj,$headers);
	
	header("Location: editeaza_newsletter.php?err=notok");
	exit();
	}
?>