<?php
ob_start();
session_start();
include("../setari/Database.php");
require_once('../cron_job/PHPMailer/PHPMailerAutoload.php');
$db = new Database();

		// verificarea datelor ce vin din pagina "inscriere_competitie" optiunea "duatlon"			
		if(isset($_POST['duatlon_tabere'])){			 
				
				 
				 		// se verifica daca competitia este selectata
					   if((isset($_POST['competitia'])) && ($_POST['competitia'] <> "")){
						   
						   $tabel = $_POST['competitia'];
					   }
					   else
					   {
						   $error[] = "Selectați competiția";	
						   
					   }
					   
					   // se verifica daca traseul pentru proba de alergare a fost selectat
					   if(($_POST['traseu_running']) !== ""){
						   
						   $date['traseu_alergare'] = $_POST['traseu_running'];
					   }
					   else
					   {
						   $error[] = "Vă rog să alegeți traseul pentru proba de \"Alergare\"";
							   
					   }					
						
						// se verifica daca traseul pentru bicicleta a fost selectat  
					   if(($_POST['traseu']) !== ""){
						   
						   $date['traseu_bicicleta'] = $_POST['traseu'];
					   }
					   else
					   {
						   $error[] = "Vă rog să alegeți traseul pentru proba de \"Ciclism\"";
							   
					   }
					   	
						// se verifica daca optiunea family a fost selectata				
						if((isset($_POST['insotitor'])) == "insotitor"){
							 
						   $date['insotitor'] = "da";
						}
					   else
						{
						   $date['insotitor'] = "";   
					    }
						
						// se verifica daca optiunea vip a fost selectata
						if((isset($_POST['vip'])) == "vip"){
							  
							$date['pachet_vip'] = "vip";
						}
						else
						{
							$date['pachet_vip'] = "";	
						}
						
						// se verifica daca daca marimea tricoului a fost selectata daca optiunea vip este activa	  
						if($date['pachet_vip'] == "vip"){
								
							   if(($_POST['tricou']) !== ""){
								   
							   $date['tricou'] = $_POST['tricou'];
							   }	
							   else
							   {
								$error[] = "Nu ați ales mărimea tricoului";
							   }
						 }
						else
						 {
						  $date['tricou'] = "";	   
						 }
						
						// se verifica daca personalizarea a fost completata atunci cand este activa	   
						if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							   
							$date['print_vip'] = $_POST["print_vip"];
						 }
						else
						 {
							$date['print_vip'] = ""; 
						 }
						  
					   // prelucrarea datelor daca checkbox-ul confirma plata este activ
						  
						  if(isset($_POST['taxa']) == "taxa"){
							  
							  if($_POST['modalitate'] <> ""){
								  
								  $date['modalitate_plata'] = $_POST['modalitate'];
								  }
								  else
								  {
									  
								  $error[] = "Nu ați selectat metoda de plată";	
								  }
							  
							  if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
								  
								  $date['suma'] = $_POST['suma'];
								  }
								  else
								  {
								  $error[] = "Nu ați completat suma achitată într-un format acceptat ";	
								  }
							  
							  if((!empty($_POST['chitanta'])) && (!ctype_space($_POST['chitanta']))){
								  
								  $date['nr_chitanta'] = $_POST['chitanta'];
								  }
								  else
								  {
								  $error[] = "Nu ați completat seria sau numărul chitanței";	
								  }
							  // setam campul confirmare cu flag-ul TBC "to be confirmed"
								  $date['confirmare'] = 'TBC';
							  
						  }
							   
											
						   // preluarea datelor din baza de date in functie de codul unic trimis prin sesiune
						   $cod = $_SESSION['cod_unic'];
						   $query = "SELECT * FROM `inscrisi_teamexpert` WHERE `cod_unic` = '".$cod."'";
						   $result = $db->execute($query);
						   
						   
				 
						  if($db->getCount($result) == 1){
							   
									   
							   $row_baza = $db->getAssoc($result);
							   
							   $cod_unic = $row_baza['cod_unic']; // coloana 2
							   $numele   = $row_baza['nume'];   // coloana 3
							   $prenumele  = $row_baza['prenume'];   // coloana 4
							   $varsta   = $row_baza['varsta'];   // coloana 5
							   $categorie_varsta  = $row_baza['categorie_varsta'];  // coloana 6
							   $sex = $row_baza['sex'];  // coloana 7
							   $email = $row_baza['email']; //email-ul				 
							   $data_nasterii = $row_baza['data_nasterii'];
							   $telefon = $row_baza['telefon'];
						  }
						  elseif($db->getCount($result) > 1)
						  {
								   
							   $error[] = "O eroare de procesare, un cod duplicat a fost găsit în baza de date";	
									   
						  }
					 
					 
						 // se verifica daca varsta declarata la inregistrare permite abordarea unui traseu de bicicleta
						 
						 $teamexpert = array('adulti','seniori','forever_young');
						 $bujori = array ('tineri','adulti','seniori','forever_young');
						 $copii = array('piticoti','pitici','copii','spiridusi','uriasi');
						 $piticoti = array('piticoti');
						 
					 
							 switch ($date['traseu_bicicleta']){
								 
								 case "TeamXpert":
								 if(in_array($categorie_varsta, $teamexpert)){
									$date['traseu_bicicleta'] = "TeamXpert"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul TeamXpert";
									 }
								 break;
								 case "Bujorilor":
								 if(in_array($categorie_varsta, $bujori)){
									$date['traseu_bicicleta'] = "Bujorilor"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Bujorilor";
									 }
								 break;
								 case "Copiilor":
								 if(in_array($categorie_varsta, $copii)){
									$date['traseu_bicicleta'] = "Copiilor"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Copiilor";
									 }
								 break;
								 case "Tricicletelor":
								 if(in_array($categorie_varsta, $piticoti)){
									$date['traseu_bicicleta'] = "Tricicletelor"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Copiilor";
									 }
								 break;
								 }
								 
						 
							// se verifica daca varsta declarata la inregistrare permite abordarea unui traseu de alergare	
							 $crosul_copiilor = array('piticoti','pitici','copii','spiridusi','uriasi');
							 $cros = array ('tineri','adulti','seniori','forever_young');
							 $semimaraton = array('adulti','seniori','forever_young');
							 $maraton = array('adulti','seniori','forever_young');
					 
							 switch ($date['traseu_alergare']){
								 
								 case "Crosul Copiilor":
								 if(in_array($categorie_varsta, $crosul_copiilor)){
									$date['traseu_alergare'] = "Crosul Copiilor"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Crosul Copiilor";
									 }
								 break;
								 case "Cros":
								 if(in_array($categorie_varsta, $cros)){
									$date['traseu_alergare'] = "Cros"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Cros";
									 }
								 break;
								 case "Semimaraton":
								 if(in_array($categorie_varsta, $semimaraton)){
									$date['traseu_alergare'] = "Semimaraton"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Semimaraton";
									 }
								 break;
								 case "Maraton":
								 if(in_array($categorie_varsta, $maraton)){
									$date['traseu_alergare'] = "Maraton"; 
									 }
									 else
									 {
										 $error[] = "Vârsta nu îți permite să participi la traseul Maraton";
									 }
								 break;
							}
								
					
					 	
							 // se verifica daca un cod duplicat exista inscris deja in tabelul aferent competitiei					 				
							$query = "SELECT `id` FROM `".$tabel."` WHERE `cod_unic_participant` = '".$cod."'";
							$result = $db->execute($query);
		
							 if($db->getCount($result) >= 1){
								 
								 $error[] = "O persoană cu acest cod este deja înregistrată în competiție,
								  dacă nu sunteți dumneavoastră vă rog să vă adresați organizatorului";
							 }
						 
					 
					 
					 // mesajul de succes plus efectuarea update-ului tabelului competitiei
					 if(!isset($error)){
						 						 
						$date['cod_unic_participant'] = $cod_unic;
						$date['numele'] = $numele;
						$date['prenumele'] = $prenumele;
						$date['varsta'] = $varsta;	
						$date['cat_varsta'] = $categorie_varsta;					
						$date['sex'] = $sex;
						$date['telefon'] = $telefon;
						$date['email'] = $email; //email-ul	
						
						if(isset($_POST['taxa'])){
							$date['taxa'] = "achitata";	
						}
						else
						{
							$date['taxa'] = "neachitata";
						}
						
						// extragem data competitiei
						$select = "SELECT `data_competitiei` FROM `tabel_competitii` WHERE `nume_competitie` = '".$tabel."'";
						$result = $db->execute($select);
						$row = $db->getAssoc($result);
						$date['data_competitiei'] = $row['data_competitiei'];
						
						 								
						 		if(is_array($date)){
									
										
										$campuri = "";
										$valori = "";
										
										
											  foreach($date as $key=>$valoare){
												  
												  $campuri .= $key. ", ";
												  $valori .= (is_numeric($valoare)) ? $valoare . " , " : "'".$valoare."', "; 							
												  
											  }
											  
										$campuri = substr($campuri,0,-2);
										$valori = substr($valori,0,-2);
										
							        	$query = "INSERT INTO `".$tabel."` (".$campuri.") VALUES (".$valori.")";
										$select = "SET NAMES 'utf8'";
										$db->execute($select);
							 			$result = $db->execute($query);
										
										if($result){
											
											$an = date("Y");
											$cod_unic = base64_encode($cod_unic);
											$to = $email;	
											$numeSender = "TeamXpert Sports Events";											
											$subject = "Te-ai inscris cu succes la competitia"." ".$_POST['flag'];
											
											//formez un vector ce contine variabilele ce trebuiesc inlocuite in mesajul email-ului	
												
												// variabile ce se gasesc in textul mail-ului din tabelul din db
												$comp = $_POST['flag'];
												$traseuBike = $date['traseu_bicicleta'];
												$traseuRun = $date['traseu_alergare'];
												$val_taxa = $_POST['valoare'];
												
												$arrData = array(
														
														'cod'=>$cod,
														'an'=>$an,
														'comp'=>$comp,
														'traseuBike'=>$traseuBike,
														'traseuRun'=>$traseuRun,
														'numele'=>$numele,
														'prenumele'=>$prenumele,
														'data_nasterii'=>$data_nasterii,
														'cod_unic'=>$cod_unic,
														'val_taxa'=>$val_taxa
												);												
												
												
												
												$query = "SELECT `continut_email` FROM `mesaje_email` WHERE `flag_competitie` LIKE '%{$_POST['flag']}%'";
												$insert = "SET NAMES 'utf8'";
												$db->execute($insert);
												$result = $db->execute($query);
												$mail = $db->getAssoc($result);
												
												// variabila ce contine mail-ul din baza de date
												$body_message = base64_decode($mail['continut_email']);
												
												// inlocuirea variabilelor din email-ul extras din baza de date
												
											   $body_message = preg_replace_callback('/{\$([^}]+)}/',function($i) {
													
  												GLOBAL $arrData;
  												return $arrData[$i[1]];
												return $arrData[$i[2]];
												return $arrData[$i[3]];
												return $arrData[$i[4]];
												return $arrData[$i[5]];
												return $arrData[$i[6]];
												return $arrData[$i[7]];
												return $arrData[$i[8]];	
												return $arrData[$i[9]];	
												return $arrData[$i[10]];										
												}, $body_message);
												
											  // crearea unei noi instante PHPMailer
											  $mail = new PHPMailer ;
											  $mail->CharSet = "UTF-8";							  // Set Charset
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
											  $mail->FromName = $numeSender;																   
											  $mail->AddAddress($to);            				   				// Add a recipient
											  $mail->AddReplyTo('sportsevents@teamexpert.ro', $numeSender);	// Add Reply Address		
											  //$mail->AddBCC('sportsevents@teamexpert.ro');					// Add Bcc:
											  
											  // adaugarea mesajului
											  $mail->isHTML(true);                                    		
											  $mail->Subject = $subject; 										// Set email Subiect							
											  $mail->Body    = $body_message;									// Set email body
											  
											  // trimiterea emailului
											  $result_mail = $mail->send();
											
                                            
											 if($result_mail){
													  
													  $mesaj = "Te-ai înscris cu succes în competiția"." ".ucfirst(str_replace("_"," ",$tabel));
													  
													  $mesaj = base64_encode($mesaj);
											  header("Location: ../tabel_inscriere_competitie_duatlon.php?succes=".$mesaj."&tip_comp=".$_POST['flag']);									
											  }
											  else
											  {
													$error[] = "Eroare trimitere mesaj de confirmare";	
											  }
											
									}
									else
									{
									$error[] = "Eroare update";	
									}
								
							}
							else
							{
							$error[] = "Eroare procesare date";	
							}					 
				 
				 
				 	}				 
					 
			  }
			  		// procesarea erorilor
			  		if(isset($error)){
						
						$err = serialize($error);
						$err = htmlspecialchars(base64_encode($err));
						
						header("Location: ../tabel_inscriere_competitie_duatlon.php?eroare=".$err."&tip_comp=".$_POST['flag']);
						
					}

ob_flush()
?>