<?php
require_once('PHPMailer/PHPMailerAutoload.php');
require_once('../setari/Database.php');
$db = new Database();
//error_reporting(0);

	// scriptul care gaseste si stocheaza in array toti sarbatoritii din ziua curenta
	$now = date('m-d');
	
	$select = "SELECT `cod_unic` FROM `inscrisi_teamexpert` WHERE `data_nasterii` LIKE '%$now' ";
	$result = $db->execute($select);
		if($db->getCount($result) > 0){
			$sarbatoriti = array();
			while($row = $db->getObject($result)){
				
				array_push($sarbatoriti, $row['cod_unic']);
			}
			
			
			// functia ce creeaza tabelul cu sarbatoriti
			function template($db, $sarbatoriti){				
				$now = date('d-M-Y');
				$template =  
				"<table id=\"sarbatoriti\" style=\"width:600px; border:1px solid black\">
					 <tr>
						 <th colspan='5' style=\"color:white; background-color:#666; text-align:center\">
							 Sarbatoritii zilei  $now
						 </th>
					 </tr>
					 <tr style=\"color:white; background-color:#666; text-align:center\">
						 <th>
							 Cod Unic
						 </th>
						 <th>
							 Nume
						 </th>
						 <th>
							 Prenume
						 </th>
						 <th>
							 Data Nasterii
						 </th>
						 <th>
							 Varsta
						 </th>
					 </tr>";
					for($i =0; $i < count($sarbatoriti); $i++){
				
					$select = "SELECT * FROM `inscrisi_teamexpert` WHERE `cod_unic` = '".$sarbatoriti[$i]."'";
					$result = $db->execute($select);
						while($row = $db->getObject($result)){
							
							$nume = $row['nume'];
							$prenume = $row['prenume'];
							
					$template .= 
					"<tr >
								 <th style=\"border: 1px solid #666\">
									 {$row['cod_unic']}
								 </th>
								 <th style=\"border: 1px solid #666\">
									 {$nume}
								 </th>
								 <th style=\"border: 1px solid #666\">
									 {$prenume}
								 </th>
								 <th style=\"border: 1px solid #666\">
									{$row['data_nasterii']}
								</th>
								 <th style=\"border: 1px solid #666\">
									 {$row['varsta']}
								 </th>
							 	</tr>";
							
							
						}
					
								
					}
					$template .=
					"</table>";
				return $template;
			}
			
			// mesajul de alerta ce este trimis 
			$mes  = "<b style=\"font-size:18px; color:red;\">Sarbatoritii zilei de azi sunt urmatorii:</b>"."\r\n";			
			$mes .= template($db, $sarbatoriti);
			
			
			// crearea unei noi instante PHPMailer
			$mail = new PHPMailer ;
			$mail->CharSet = "UTF-8";
			// setam datele contului
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'server-0159.whmpanels.com';  		  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'sportsevents@teamexpert.ro';       // SMTP username
			$mail->Password = 'rteam67#teamexpert';               // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465; 
			
			// setam From: CC: BCC:
			$mail->From = 'sportsevents@teamexpert.ro';
			$mail->FromName = 'Sistem TeamXpert';																   
			$mail->AddAddress('sportsevents@teamexpert.ro');          					 // Add a recipient
			$mail->AddReplyTo('sportsevents@teamexpert.ro', 'Office TeamXpert');	 // Add a reply address	
			$mail->AddCC('');	 	
			//$mail->AddBCC('sportsevents@teamexpert.ro');							 // Add a cc address
			
			// adaugarea mesajului
			$mail->isHTML(true);                                    // Set email format to HTML
			$mail->Subject = 'Sarbatoritii zilei';					// Set Subject
			$mail->Body    = $mes;									// Set Body message
			
			
			// trimiterea emailului
			$result = $mail->send();
			
			
			// verificarea functionarii scriptului	
			/*
			if(!$result) {
   				 echo 'Mesajul nu a putut fi trimis.';
    			 echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                 echo 'Mesajul a fost trimis cu succes';
            }
			 */  
			
			
			// trimitem mesajul catre cei ce sarbatoresc ziua de nastere in ziua curenta
			
			foreach($sarbatoriti as $cod){
				
					$select = "SELECT * FROM `inscrisi_teamexpert` WHERE `cod_unic` = '".$cod."'";
					$result = $db->execute($select);
					
					$row = $db->getAssoc($result);
					
					$nume = $row['nume'];
					$prenume = $row['prenume'];
					$varsta = $row['varsta'];
					$cod_unic = base64_encode($row['cod_unic']);
					$adresa = $row['email'];
					
					// se extrage mesajul din baza de date
					$query = "SELECT `continut_email` FROM `mesaje_email` WHERE `flag_competitie` = 'Mesaj aniversar'";
					$insert = "SET NAMES 'utf8'";
					$db->execute($insert);
					$result = $db->execute($query);
					$email = $db->getAssoc($result);
					
					$body_email = base64_decode($email['continut_email']);
					
					// se inlocuiesc variabilele din email-ul stocat in baza de date
						$variabile = array(
							'nume' => $nume,
							'prenume' => $prenume,
							'varsta' => $varsta,
							'cod_unic' => $cod_unic						
						);
						
					
						
					$body_email = preg_replace_callback('/{\$([^}]+)}/',function($i){
					
					GLOBAL $variabile;
					return $variabile[$i[1]];
					return $variabile[$i[2]];
					return $variabile[$i[3]];
					return $variabile[$i[4]];
					
					}, $body_email);
					
					
					// crearea unei noi instante PHPMailer
					$mail = new PHPMailer ;
					$mail->CharSet = "UTF-8";
					// setam datele contului
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'server-0159.whmpanels.com';  		  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = 'sportsevents@teamexpert.ro';       // SMTP username
					$mail->Password = 'rteam67#teamexpert';               // SMTP password
					$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 465; 
					
					// setam From: CC: BCC:
					$mail->From = 'sportsevents@teamexpert.ro';
					$mail->FromName = 'TeamXpert Sports Events';																   
					$mail->AddAddress($adresa);            				    					// Add a recipient
					$mail->AddReplyTo('sportsevents@teamexpert.ro', 'Office TeamXpert');		// Add Reply Address		
					//$mail->AddBCC('sportsevents@teamexpert.ro');								// Add Bcc:
					
					// adaugarea mesajului
					$mail->isHTML(true);                                    		// Set email Subiect
					$mail->Subject = 'La Multi Ani!';								// Set email body
					$mail->Body    = $body_email;
					
					// trimiterea emailului
					$result = $mail->send();
					
					// verificarea functionalitatii scriptului
					
					/*
					if(!$result) {
						 echo 'Mesajul nu a putut fi trimis.';
						 echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						 echo 'Mesajul a fost trimis cu succes';
					}
					*/
					
				
			  }
			
		}
?>