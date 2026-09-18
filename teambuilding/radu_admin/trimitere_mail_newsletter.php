<?php
include("includes/Database.php");
$db = new Database();

$select = "SELECT `editor` FROM `sabloane_newsletter` WHERE `activ` = 1";
$rezultat = $db->execute($select);

if(mysqli_num_rows($rezultat) != 1){
	
	$to = "dangrecu1975@gmail.com";
	$from = "dan_grecu@beauty-salon.hol.es";
	$numele = "Radu Savin";
	$subiect = "Mesaj de pe site-ul TeamXpert"; 
	$mesaj = "Nu ai niciun newsletter activ";
	
	
	$headers = "FROM: $numele <\"$from\"> \r\n";
	$headers.= "Reply-To:{$from} \r\n";
	$headers.= "CC: {$to} \r\n";
	$headers.= "Bcc: {$to} \r\n";
	$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
	$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
	$headers.= "Content-Transfer-Encoding: 8bit\n"; 
	
	
									
	$result = mail($to,$subiect,$mesaj,$headers);
	exit();
}


$row = mysqli_fetch_assoc($rezultat);
$newsletter_code = base64_decode($row['editor']);

$select = "SELECT * FROM `inscrisi_teamexpert` WHERE `news_activ` = 1 AND `activ` = 1 LIMIT 30";
$rezultat = $db->execute($select);

	if(mysqli_num_rows($rezultat) > 0){
	
	while($row = mysqli_fetch_array($rezultat)){
	
	$to = $row['email'];
	$mail_cod = $row['cod_unic'];
	
	$from = "dan_grecu@beauty-salon.hol.es";
	$numele = "Radu Savin";
	$subiect = "Newsletter TeamXpert"; 
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
	
	
	$select = "UPDATE `inscrisi_teamexpert` SET `news_activ` = 0 WHERE `email` = '".$to."' LIMIT 1";
	$db->execute($select);
	
}
	}
	else
	{
	$to = "dangrecu1975@gmail.com";
	$from = "dan_grecu@beauty-salon.hol.es";
	$numele = "Radu Savin";
	$subiect = "Mesaj de pe site-ul TeamXpert"; 
	$mesaj = "Baza de date nu este resetată";
	
	
	$headers = "FROM: $numele <\"$from\"> \r\n";
	$headers.= "Reply-To:{$from} \r\n";
	$headers.= "CC: {$to} \r\n";
	$headers.= "Bcc: {$to} \r\n";
	$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
	$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
	$headers.= "Content-Transfer-Encoding: 8bit\n"; 
	
	
									
	$result = mail($to,$subiect,$mesaj,$headers);
	exit();
	}
	
	
?>