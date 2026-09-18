<?php
ob_start();
session_start();
include("../setari/Database.php");
require_once('../cron_job/PHPMailer/PHPMailerAutoload.php');
$db = new Database();


			// verificarea datelor ce vin din pagina "inscriere_competitie" optiunea Maraton Tabere cu Suflet
			 if(isset($_POST['maraton_tabere'])){				
				 
				 				// se verifica daca o competitie este selectata
							   if((isset($_POST['competitia'])) && ($_POST['competitia'] <> "")){
								   
								 		 $tabel = $_POST['competitia'];
							   }
								else
								{
								   		 $error[] = "Selectați competiția";	
								   
								}
								
								// se verifica daca un traseu a fost selectat  
							   if(($_POST['traseu']) !== ""){
								   
								   		$date['traseu'] = $_POST['traseu'];
								}
							   else
							    {
								  		$error[] = "Vă rog să alegeți traseul";
									   
							    }
								
								// se verifica daca optiunea vip a fost selectata				  
								if((isset($_POST['vip'])) == "vip"){
									  
										$date['pachet_vip'] = "vip";
								 }
								 else
								{
										$date['pachet_vip'] = "";	
								}
								
								// se verifica daca in cazul optiunii vip marimea tricoului este selectata
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
								 
								/*	 
								if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
									 
								  $date['print_vip'] = $_POST["print_vip"];
									 }
									 else
									 {
								  $date['print_vip'] = ""; 
									 }
								*/
								
								
								// verificam daca optiune duatlon a fost selecta
								/******
								 if(isset($_POST['duatlon']) == "duatlon"){
											$date['duatlon'] = "duatlon";
								 }
								 else
								 {
											$date['duatlon'] = "";	
								 }
								 ***/
								 
								 
								// prelucrarea taxei daca optiunea confirma plata este selectata					
								if(isset($_POST['taxa']) == "taxa"){
									
									if($_POST['modalitate'] <> ""){
										
										$date['modalitate_plata'] = $_POST['modalitate'];
									}
									else
									{
											
										$error[] = "Nu ați selectat metoda de plată";	
									}
							  
							  	// se verifica daca suma este completata
								if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
									
									  $date['suma'] = $_POST['suma'];
									  }
									  else
									  {
									  $error[] = "Nu ați completat suma achitată într-un format acceptat ";	
									  }
								// se verifica daca nr. citantei/OP a fost completat
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
						  
					
					
								// procesarea datelor specifice curieratului
								if(isset($_POST['curier']) == "curier"){
									
										// se verifica daca este selectat optiunea localitate de livrare
										if(($_POST['localitate']) != ''){
											
											$curier['localitate'] = $_POST['localitate'];
										}
										else
										{
											$error[] = "Nu ați selectat zona de livrare";	
										}
										
										// se verifica daca adresa de livrare a fost completata
										if(!empty($_POST['adresaCurier']) && (!ctype_space($_POST['adresaCurier']))){
											
											$curier['adresa'] = $_POST['adresaCurier'];
										}
										else
										{
											$error[] = "Adresa de livrare pentru curier trebuie completată";
										}
										// setam celelalte variabile pentru transfer in tabelulul ce stocheaza optiunea de trimitere curier
											$curier['competitie'] = $_POST['competitia']; // setam varibila ce stocheaza numele competitiei
											$curier['cod_unic'] = $_SESSION['cod_unic'];  // setam variabila cod unic 
											$curier['an'] = date('Y');					  // setam variabila an specifica competitiei
											$curier['procesat'] = "neprocesata";          // setam variabila de intrare a starii comenzii
								}
								else
								{
									
								 // daca nu este setat informatia nu se proceseaza	
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
								 $email = $row_baza['email'];				 
								 $data_nasterii = $row_baza['data_nasterii'];
								 $telefon = $row_baza['telefon'];
								 
								 
										// setam nume si prenume doar daca este bifat checkbox-ul pentru livrare curier
										if(isset($_POST['curier']) == 'curier'){ 
										   // setarea unor variabile din array-ul curier
										   $curier['nume'] = $row_baza['nume']; // numele participantului
										   $curier['prenume'] = $row_baza['prenume']; // prenumele participantului
										}
								 
								 
								 }
								 elseif($db->getCount($result) > 1)
								 {
									 
								 $error[] = "O eroare de procesare, un cod duplicat a fost găsit în baza de date";	
										 
								 }
					 
					 
								 // se verifica daca varsta declarata la inregistrare permite abordarea unui traseu
								 
								 $crosul_copiilor = array('piticoti','pitici','copii','spiridusi','uriasi');
								 $cros = array ('tineri','adulti','seniori','forever_young');
								 $semimaraton = array('adulti','seniori','forever_young');
								 $maraton = array('adulti','seniori','forever_young');
								 
								 switch ($date['traseu']){
									 
									 case "Crosul Copiilor":
									 if(in_array($categorie_varsta, $crosul_copiilor)){
										$date['traseu'] = "Crosul Copiilor"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 case "Cros":
									 if(in_array($categorie_varsta, $cros)){
										$date['traseu'] = "Cros"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 case "Semimaraton":
									 if(in_array($categorie_varsta, $semimaraton)){
										$date['traseu'] = "Semimaraton"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 case "Maraton":
									 if(in_array($categorie_varsta, $maraton)){
										$date['traseu'] = "Maraton"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
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
					 
					 
					 
					 
					 
					 // daca nu sunt erori se incepe la procesarea datelor catre tabelului competitiei
					 if(!isset($error)){
						 						 
						$date['cod_unic_participant'] = $cod_unic;
						$date['numele'] = $numele;
						$date['prenumele'] = $prenumele;
						$date['varsta'] = $varsta;	
						$date['cat_varsta'] = $categorie_varsta;					
						$date['sex'] = $sex;
						$date['telefon'] = $telefon;
						$date['email'] = $email;
						
						// daca este bifat optiunea mod plata
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
						
						// update in tabel competitie duatlon daca este bifata aceasta optiune
						
						/*********
						if(($date['duatlon']) == "duatlon"){
									
									// se extrag din array campurile 'traseu' si 'duatlon'
									unset($date['traseu']);
									unset($date['duatlon']);
									// se reintroduce campul 'traseu_bicicleta'
									$date['traseu_alergare'] = $_POST['traseu'];
									
									$select = "SELECT `nume_competitie` FROM `tabel_competitii` WHERE `tip_competitie` = 'Duatlon' AND `activ` = 1 ";
									$result = $db->execute($select);
										
										if($db->getCount($result) == 1){
										
											$nume = $db->getAssoc($result);
											$tabel_duatlon = $nume['nume_competitie'];											
										 	$select1 = "SELECT `id` FROM `".$tabel_duatlon."` WHERE `cod_unic_participant` = '".$cod."'";
											$result = $db->execute($select1);
											$linii = $db->getCount($result);
												if($linii == 0){
												
												$campuri = "";
												$valori = "";
													foreach($date as $key=>$valoare){
										
													$campuri .= $key. ", ";
													$valori .= (is_numeric($valoare)) ? $valoare . " , " : "'".$valoare."', "; 							
										
													}
												$campuri = substr($campuri,0,-2);
												$valori = substr($valori,0,-2);
										
							        			$query = "INSERT INTO `".$tabel_duatlon."` (".$campuri.") VALUES (".$valori.")";
												$select = "SET NAMES 'utf8'";
												$db->execute($select);
							 					$db->execute($query);
												}
												
												
												if($linii == 1)
												{
												
												$update = "UPDATE `".$tabel_duatlon."` 
												SET `traseu_alergare` = '".$date['traseu_alergare']."' 
												WHERE `cod_unic_participant` = '".$cod."'";
												$db->execute($update);
												
												
												}
										
											}
											else
											{
											$error[] = "Eroare update Duatlon";	
											}
									}
							****/
								
								  // introducerea informatiilor in tabelul cu informatii destinat curierului
								  if(isset($curier)){
									  if(is_array($curier)){
										  
										  $campuri = '';
										  $valori = '';
										  
										  foreach($curier as $camp=>$val){
											  
											  $campuri .= $camp. ', ';
											  $valori .= (is_numeric($val))? $val . ', ' : "'" . $val. "', ";
											  
										  }
											  $campuri = substr($campuri,0,-2);
											  $valori = substr($valori,0,-2);
											  
										  $insert = "INSERT INTO `livrare_curier` (".$campuri.") VALUES (".$valori.")";
										  $select = "SET NAMES 'utf8'";
										  $db->execute($select);
										  $result = $db->execute($insert);
									  }
								  }
								
								
								
						 		// introducerea informatiilor in tabelul competitiei						
						 		if(is_array($date)){
									
									
									// se extrag din array campurile 'traseu' 
									unset($date['traseu_alergare']);
									
									// se reintroduc campul 'traseu' si 'duatlon'
									$date['traseu'] = $_POST['traseu'];
									
									// daca optiunea duatlon este selectata definim variabila
									if(isset($_POST['duatlon']) ){	
										$date['duatlon'] = "duatlon";
									}
										
									
										
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
												$traseu = $date['traseu'];
												$val_taxa = $_POST['valoare'];// valoarea taxei 
												
												$arrData = array(
														
														'cod'=>$cod,
														'an'=>$an,
														'comp'=>$comp,
														'traseu'=>$traseu,														
														'numele'=>$numele,
														'prenumele'=>$prenumele,
														'data_nasterii'=>$data_nasterii,
														'cod_unic'=>$cod_unic,
														'val_taxa'=>$val_taxa
												);												
												
												
												
												$query = "SELECT `continut_email` FROM `mesaje_email` WHERE `flag_competitie` LIKE '{$_POST['flag']}'";
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
																							
												}, $body_message);										
											
											  // crearea unei noi instante PHPMailer
											  $mail = new PHPMailer ;
											  $mail->CharSet = "UTF-8";							    // Set Charset
											  // setam datele contului
											  $mail->isSMTP();                                      // Set mailer to use SMTP
											  $mail->Host = 'server-0159.whmpanels.com';  		    // Specify main and backup SMTP servers
											  $mail->SMTPAuth = true;                               // Enable SMTP authentication
											  $mail->Username = 'sportsevents@teamexpert.ro';       // SMTP username
											  $mail->Password = 'rteam67#teamexpert';               // SMTP password
											  $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
											  $mail->Port = 465; 
											  
											  // setam From: CC: BCC:
											  $mail->From = 'sportsevents@teamexpert.ro';
											  $mail->FromName = $numeSender;																   
											  $mail->AddAddress($to);            				   				// Add a recipient
											  $mail->AddReplyTo('sportsevents@teamexpert.ro', $numeSender);	    // Add Reply Address		
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
											
					     					header("Location: ../tabel_inscriere_competitie_maraton.php?succes=".$mesaj."&tip_comp=Maraton Tabere cu Suflet");
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
						
					header("Location: ../tabel_inscriere_competitie_maraton.php?eroare=".$err."&tip_comp=Maraton Tabere cu Suflet");
						
						}
			

ob_flush();
?>