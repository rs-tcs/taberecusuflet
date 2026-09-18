<?php
include("../setari/Database.php");
$db = new Database();


// se verifica daca exista vreu newsletter activ
$select = "SELECT `editor`,`nume_sablon` FROM `sabloane_newsletter` WHERE `activ` = 1";
$rezultat = $db->execute($select);

if($db->getCount($rezultat) !== 1){
	
	$to = "radu@teamexpert.ro";
	$from = "office@teamexpert.ro";
	$numele = "Newsletter TeamXpert";
	$subiect = "Mesaj newsletter TeamXpert"; 
	$mesaj = "Nu ai niciun newsletter activ";
	
	
	$headers = "FROM: $numele {$from} \r\n";
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

// se trimite efectiv newsletter-ul in reprize de cate 30
$row = $db->getAssoc($rezultat);
$newsletter_code = base64_decode($row['editor']);
$subiect = $row['nume_sablon'];

$select = "SELECT * FROM `inscrisi_teamexpert` WHERE `news_activ` = 1 AND `activ` = 1 LIMIT 30";
$rezultat = $db->execute($select);

	if($db->getCount($rezultat) > 0){
	
			while($row1 = $db->getAssoc($rezultat)){
	
					$to = $row1['email'];
					$mail_cod = $row1['cod_unic'];
	
					$from = "office@teamexpert.ro";
					$numele = "Newsletter TeamXpert";
					
					
					$cod = base64_encode($mail_cod);
 					$arrData = array(
						'cod_unic' => $cod					
					);
					
					function replace($i){
						GLOBAL $arrData;
						return $arrData[$i[1]] ;
					}
					
					$newsletter_code = preg_replace_callback('/{\$([^}]+)}/', "replace", $newsletter_code );
					 
					$mesaj = $newsletter_code;
	
	
					$headers = "FROM: $numele {$from} \r\n";
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
	// baza de date a fost folosita la campania precedenta si nu a fost resetata
	{
	$to = "dangrecu1975@gmail.com";
	$from = "office@teamexpert.ro";
	$numele = "Radu Savin";
	$subiect = "Mesaj newsletter TeamXpert"; 
	$mesaj = "Baza de date nu este resetată";
	
	
	$headers = "FROM: $numele {$from} \r\n";
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