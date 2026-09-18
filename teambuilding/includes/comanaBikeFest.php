<?php
ob_start();
session_start();
include("../setari/Database.php");
require_once('../cron_job/PHPMailer/PHPMailerAutoload.php');
$db = new Database();



	if(isset($_POST['comana_bike_fest'])){	
					
							
				 // verificarea datelor ce vin din pagina "inscriere_competitie" optiunea "mountainbike"
				 
				 
				 	// se verifica daca o competitie este selectata
					 if((isset($_POST['competitia'])) && ($_POST['competitia'] <> "")){
					 
							$tabel = $_POST['competitia'];
					 }
				 	else
					{
					 		$error[] = "Selectați competiția";	
					 
					}
					
					// se verifica daca un traseu este selectat
				 	if(($_POST['traseu']) !== ""){
					 
				     		$date['traseu'] = $_POST['traseu'];
					 }
				 	 else
					 {
							$error[] = "Vă rog să alegeți traseul";
					 	 
					 }
					 
					 // se verifica daca in cazul traseului copiilor optiunea family este bifata					
				 	 if((isset($_POST['insotitor'])) == "insotitor"){
					   
				     		$date['insotitor'] = "da";
					 }
					 else
					 {
					  		$date['insotitor'] = "";   
					 }
					 
					 
					 
					 
					 // se verifica daca optiunea vip este selectata
				 	 if((isset($_POST['vip'])) && ($_POST['vip'] == "vip")){
						
				     		$date['pachet_vip'] = "vip";
					 }
					 else
					 {
					  		$date['pachet_vip'] = "";	
					 }
					 
					 
					 
						
					// se verifica daca marimea tricoului este selectata atunci cand optiunea vip este selectata (obligatoriu)
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
					 
					 
						
					 // se verifica daca optiunea de personalizare a numarului 
					if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
						   
							$date['print_vip'] = $_POST["print_vip"];
					 }
					else
					 {
							$date['print_vip'] = ""; 
					 }
					
					
					// se verifica data limita la care se poate face procesarea datelor pentru curier
					$azi = date("Y-m-d");
					$select = "SELECT `dataLimitaCurier` 
					FROM `tabel_competitii`  
					WHERE `activ` = 1 
					AND `tip_competitie` = 'Ciclism' 
					AND `nume_competitie` = '".$_POST['competitia']."' 
					Limit 0,1";
					$result = $db->execute($select);
					$dataCurier = $db->getAssoc($result);	
					
						
					
						
					// procesarea datelor specifice curieratului
					if(isset($_POST['vip']) && ($_POST['vip'] == "vip")){
						
						// daca data limita nu este atinsa datele nu se proceseaza
						if($dataCurier['dataLimitaCurier'] >= $azi){						
						
								if(!empty($_POST['adresaCurier']) && (!ctype_space($_POST['adresaCurier']))){
									
									$curier['adresa'] = $_POST['adresaCurier'];
								}
								else
								{
									$error[] = "Adresa de livrare pentru curier trebuie completată";
								}
								
								
								if(!empty($_POST['telCurier']) && (!ctype_space($_POST['telCurier'])) && (strlen($_POST['telCurier']) === 10) && ($_POST['telCurier'] <> 0000000000)){
									
									$curier['telefon'] = $_POST['telCurier'];
								}
								else
								{
									$curier['telefon'] = '';
								}
								
								if(!empty($_POST['mailCurier']) && (!ctype_space($_POST['mailCurier'])) && (filter_var($_POST['mailCurier'], FILTER_VALIDATE_EMAIL))){
									
									$curier['email'] = $_POST['mailCurier'];
								}
								else
								{
									$curier['email'] = '';
								}
								
						
						// setam celelalte variabile pentru transfer in tabelulul ce stocheaza optiunea de trimitere curier
							$curier['competitie'] = $_POST['competitia']; // setam varibila ce stocheaza numele competitiei
							$curier['cod_unic'] = $_SESSION['cod_unic'];  // setam variabila cod unic 
							$curier['an'] = date('Y');					  // setam variabila an specifica competitiei
							$curier['procesat'] = "neprocesata";          // setam variabila de intrare a starii comenzii
						
							}
							else
							{
								
							// informatiile nu sunt procesate daca data limita este depasita	
							}
						
					}
					else
					{
						
					 // daca nu este setat vip nu se proceseaza	
					}
					
					
						 
					// daca flag-ul competitiei este "Cupa Tabere cu Suflet" procesam si campul duatlon
					/****
					if($_POST['flag'] == "Cupa Tabere cu Suflet"){
						  
						  // se verifica daca optiunea duatlon este bifata
						  if(isset($_POST['duatlon']) == "duatlon"){
							  
							  $date['duatlon'] = "duatlon";
							  }
							  else
							  {
							  $date['duatlon'] = "";	
							  }
					  }
					****/
						
					// prelucrarea datelor daca optiunea confirma plata este activ					
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
						 
						 if($db->getCount($result) >= 1){
							 
									 
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
							 
									
									// setam nume si prenume doar daca este bifat checkbox-ul pentru livrare curier
									if(isset($_POST['vip']) && ($_POST['vip'] == "vip")){ 
										
										if($dataCurier['dataLimitaCurier'] >= $azi){
									
											 // setarea unor variabile din array-ul curier
											 $curier['nume'] = $row_baza['nume'];
											 $curier['prenume'] = $row_baza['prenume'];
										}
										else
										{
											
											// variabilele nu se seteaza
										}
									}
							 
							 }
							 elseif($db->getCount($result) > 1)
							 {
								 
							 $error[] = "O eroare de procesare, un cod duplicat a fost găsit în baza de date";	
									 
							 }
							 
					 
						 					 
						 // pentru Cupa Veseliei
						 if($_POST['flag'] == "Cupa Veseliei"){
							 
							 	// se stabilesc vectorii cu permisiuni
								$copii = array('piticoti','pitici','copii','spiridusi','uriasi');
								$adulti = array('tineri','adulti','seniori','forever_young');
						
								switch($date['traseu']){
									
									  case "Copiilor":
									  if(in_array($categorie_varsta, $copii)){
										  $date['traseu'] = "Copiilor"; 						  
									  }
									  else
									  {
										  $error[] = "Vârsta nu îți permite să participi la acest traseu";
									  }
									  break; 
									  
									  case "Veseliei":
									  if(in_array($categorie_varsta, $adulti)){
										  $date['traseu'] = "Veseliei"; 						  
									  }
									  else
									  {
										  $error[] = "Vârsta nu îți permite să participi la acest traseu";
									  }
									  break; 
									   
									  default:
									 
								}
						  
						 }
						 // pentru cupa tabere cu suflet
						 else if($_POST['flag'] == "Cupa Tabere cu Suflet")
						 {
							 	
								// se stabilesc vectorii cu permisiuni
								$teamexpert = array('tineri','adulti','seniori','forever_young');
						 		$narciselor = array ('tineri','adulti','seniori','forever_young');
						 		$tabere = array('pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young');
								$triciclete = array('pitici','piticoti');
								 
								 // verificarea permisiunilor pentru noua competitie MTB de la Azuga
								 switch ($date['traseu']){
									 
									 case "TeamXpert":
									 if(in_array($categorie_varsta, $teamexpert)){
										$date['traseu'] = "TeamXpert"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									 case "Narciselor":
									 if(in_array($categorie_varsta, $narciselor)){
										$date['traseu'] = "Narciselor"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									 case "Tabere cu Suflet":
									 if(in_array($categorie_varsta, $tabere)){
										$date['traseu'] = "Tabere cu Suflet"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									  case "Piticilor":
									 if(in_array($categorie_varsta, $triciclete)){
										$date['traseu'] = "Piticilor"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									 default:
								}
						 }
						 //pentru restul competitiilor
						 else
						 {
							 
							 	// se stabilesc vectorii cu permisiuni
							 	$teamexpert = array('adulti','seniori','forever_young');
						 		$bujori = array ('tineri','adulti','seniori','forever_young');
						 		$copii = array('piticoti','pitici','copii','spiridusi','uriasi');
								
								 
								 // Pentru celelalte competitii tip BikeFest
								 switch ($date['traseu']){
									 
									 case "TeamXpert":
									 if(in_array($categorie_varsta, $teamexpert)){
										$date['traseu'] = "TeamXpert"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									 case "Bujorilor":
									 if(in_array($categorie_varsta, $bujori)){
										$date['traseu'] = "Bujorilor"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									 case "Copiilor":
									 if(in_array($categorie_varsta, $copii)){
										$date['traseu'] = "Copiilor"; 
										 }
										 else
										 {
											 $error[] = "Vârsta nu îți permite să participi la acest traseu";
										 }
									 break;
									 
									 default:
									  
								}
							 
						  }
						 
						 
					 	
					   // se verifica daca un cod duplicat exista inscris deja in tabelul aferent competitiei					 				
					  $query = "SELECT `id` FROM `".$tabel."` WHERE `cod_unic_participant` = '".$cod."'";
					  $result = $db->execute($query);
  
					   if($db->getCount($result) >= 1){
						   
						   $error[] = "O persoană cu acest cod este deja înregistrată în competiție,
							dacă nu sunteți dumneavoastră vă rog să vă adresați organizatorului";
					   }
						 
					 
					 
					 // daca nu sunt erori se procedeaza la introducerea informatiilor in tabel si afisarea mesajului de succes
					 // precum si trimiterea mesajului de confirmare
					 if(!isset($error)){
						 	
							
							// se introduc variabilele extrase din baza de date in array-ul final 					 
							$date['cod_unic_participant'] = $cod_unic;
							$date['numele'] = $numele;
							$date['prenumele'] = $prenumele;
							$date['varsta'] = $varsta;	
							$date['cat_varsta'] = $categorie_varsta;					
							$date['sex'] = $sex;
							$date['telefon'] = $telefon;
							$date['email'] = $email; 
							
							// daca este bifat taxa se salveaza ca si achitat altfel nu
							if($_POST['flag'] == "Cupa Veseliei" || (($_POST['flag'] == "Cupa Tabere cu Suflet") && ($date['traseu'] == 'Piticilor'))){
								
									  // in cazul Cupei Veseliei se verifica daca sunt copii
									  $copii = array('piticoti','pitici','copii','spiridusi','uriasi');
									  if(in_array($date['cat_varsta'], $copii) && (($_POST['insotitor'] == '') && ($_POST['vip']== ''))){								
										  $date['taxa'] = "gratuit";
									  }
									  else
									  {
										  
									  	 // pe aceasta ramura participantii nu sunt copii si deci se califica la taxa
										  if(isset($_POST['taxa'])){
											  $date['taxa'] = "achitata";	
										  }
										  else
										  {							
											  $date['taxa'] = "neachitata";
										  }
										  
									  }
							}
							else
							{
								// aceasta ramura se aplica celorlalte competitii tip bikefest
								if(isset($_POST['taxa'])){
									$date['taxa'] = "achitata";	
								}
								else
								{							
									$date['taxa'] = "neachitata";
								}
							}
							
							
							
							
							// daca este bifat traseul Tricicletelor, insotitor are valoarea "da"
							if($date['traseu'] == "Piticilor"){
								$date['insotitor'] = "da";	
							}
							
								
							
							// extragem data competitiei
							$select = "SELECT `data_competitiei` FROM `tabel_competitii` WHERE `nume_competitie` = '".$tabel."'";
							$result = $db->execute($select);
							$row = $db->getAssoc($result);
							$date['data_competitiei'] = $row['data_competitiei'];
							
						
						
							// update in tabel competitie duatlon daca este bifata aceasta optiune
							/*******
							if(isset($date['duatlon']) && ($date['duatlon']) == "duatlon"){
										
										// se extrag din array campurile 'traseu' si 'duatlon'
										unset($date['traseu']);
										unset($date['duatlon']);
										
										// se reintroduce campul 'traseu_bicicleta'
										$date['traseu_bicicleta'] = $_POST['traseu'];
										
										$select = "SELECT `nume_competitie` FROM `tabel_competitii` WHERE `tip_competitie` = 'Duatlon' AND `activ` = 1 ";
										$result = $db->execute($select);
											
											if($db->getCount($result) == 1){
											
												$nume = $db->getAssoc($result);
												$tabel_duatlon = $nume['nume_competitie'];											
												$select1 = "SELECT `id` FROM `".$tabel_duatlon."` WHERE `cod_unic_participant` = '".$cod."'";
												$result = $db->execute($select1);
												$linii = mysqli_num_rows($result);
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
													
													
													else if($linii == 1)
													{
													
													$update = "UPDATE `".$tabel_duatlon."` 
													SET `traseu_bicicleta` = '".$date['traseu_bicicleta']."' 
													WHERE `cod_unic_participant` = '".$cod."'";
													$db->execute($update);
													
													
													}
											
												}
												else
												{
												$error[] = "Eroare update Duatlon";	
												}
										}
								******/	
								
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
									
									// se extrage din array campul 'traseu' 
									unset($date['traseu_bicicleta']);
									
									// se reintroduc campul 'traseu' si 'duatlon'
									$date['traseu'] = $_POST['traseu'];
									
									if(isset($_POST['duatlon']) ){	
										$date['duatlon'] = "duatlon";
									}
									
									// se extrage taxa din array pentru Cupa 1 iunie
									if($_POST['flag'] == 'Cupa 1 Iunie'){
										unset($date['taxa']);
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
											
													  // trimit mail personalizat pentru Cupa 1 Iunie sau Cupa Veseliei
													  if(($_POST['flag'] == "Cupa 1 Iunie" ) || ($_POST['flag'] == "Cupa Veseliei" )){
														  
													 	 //formez un vector ce contine variabilele ce trebuiesc inlocuite in mesajul email-ului						  
														  // variabile ce se gasesc in textul mail-ului din tabelul din db
														  $comp = $_POST['flag'];
														  $traseu = $date['traseu'];
														  $val_taxa = $_POST['valoare'];
														  
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
														  
													  
													  }
													  
													  // se trimite mail de confirmare pentru celelalte flag-uri procesate
													  else 
													  {
														  
														  
													      //formez un array ce contine variabilele ce trebuiesc inlocuite in mesajul email-ului						  
														  // variabile ce se gasesc in textul mail-ului din tabelul din db
														  $comp = $_POST['flag'];
														  $traseu = $date['traseu'];
														  $val_taxa = $_POST['valoare'];
														  
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
																									  
														  }, $body_message);
														  
													  }
											
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
										
											// daca mesajul de email s-a trimis cu succes trimitem mesajul de succes altfel un mesaj de eroare este generat	
											if($result_mail){
													
												$mesaj = "Te-ai înscris cu succes în competiția"." ".ucfirst(str_replace("_"," ",$tabel));
													
												$mesaj = base64_encode($mesaj);
												header("Location: ../tabel_inscriere_competitie_bikefest.php?succes=".$mesaj."&tip_comp=".$_POST['flag']);									
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
						  
						  header("Location: ../tabel_inscriere_competitie_bikefest.php?eroare=".$err."&tip_comp=".$_POST['flag']);
						  
					  }
			

?>