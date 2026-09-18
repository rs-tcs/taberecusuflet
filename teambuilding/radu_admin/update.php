<?php
ob_start();
session_start();
include("includes/Database.php");
$db = new Database();

if(!$_SESSION['username']){
		
		header("Location: index.php");
		}

			//  1 procesez informatia ce vine din pagina detalii.php
		if(isset($_POST['modifica_date'])) {
			
			 $cod = $_POST['trimis_detalii_user'];
			
				// se verifica datele venite prin post si executarea update-ului
				if((!empty($_POST['nume'])) && (!ctype_space($_POST['nume']))){
					
					$data['nume'] = $_POST['nume'];
					
					}
					else
					{
					$data['nume'] = "";	
					}
					
				if((!empty($_POST['prenume'])) && (!ctype_space($_POST['prenume']))){
					
					$data['prenume'] = $_POST['prenume'];
					
					}
					else
					{
					$data['prenume'] = "";	
					}
					
				if((!empty($_POST['sex'])) && (!ctype_space($_POST['sex']))){
					
					$data['sex'] = $_POST['sex'];
					
					}
					else
					{
					$data['sex'] = "";	
					}			
					
				if((!empty($_POST['data_nasterii'])) && (!ctype_space($_POST['data_nasterii']))){
					
					$data['data_nasterii'] = $_POST['data_nasterii'];
					
					}
					else
					{
					$data['data_nasterii'] = "";	
					}
					
				if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
					
					$data['varsta'] = $_POST['varsta'];
					
					}
					else
					{
					$data['varsta'] = "";	
					}
					
				if((!empty($_POST['categorie_varsta'])) && (!ctype_space($_POST['categorie_varsta']))){
					
					$data['categorie_varsta'] = $_POST['categorie_varsta'];
					
					}
					else
					{
					$data['categorie_varsta'] = "";	
					}	
				
					
				if((!empty($_POST['telefon'])) && (!ctype_space($_POST['telefon']))){
					
					$data['telefon'] = $_POST['telefon'];
					
					}
					else
					{
					$data['telefon'] = "";	
					}
					
				if((!empty($_POST['email'])) && (!ctype_space($_POST['email']))){
					
					$data['email'] = $_POST['email'];
					
					}
					else
					{
					$data['email'] = "";	
					}
					
				if((!empty($_POST['activ'])) && (!ctype_space($_POST['activ']))){
					
					$data['activ'] = $_POST['activ'];
					
					}
					else
					{
					$data['activ'] = "";	
					}
				if((!empty($_POST['news_activ'])) && (!ctype_space($_POST['news_activ']))){
					
					$data['news_activ'] = $_POST['news_activ'];
					
					}
					else
					{
					$data['news_activ'] = "";	
					}
					
				
				if((!empty($_POST['oras'])) && (!ctype_space($_POST['oras']))){
					
					$data['oras'] = $_POST['oras'];
					
					}
					else
					{
					$data['oras'] = "";	
					}
					
				if((!empty($_POST['club'])) && (!ctype_space($_POST['club']))){
					
					$data['club'] = $_POST['club'];
					
					}
					else
					{
					$data['club'] = "";	
					}
					
				if((!empty($_POST['descriere'])) && (!ctype_space($_POST['descriere']))){
					
					$data['descriere'] = $_POST['descriere'];
					
					}
					else
					{
					$data['descriere'] = "";	
					}
					
				if((!empty($_POST['data_inscriere'])) && (!ctype_space($_POST['data_inscriere']))){
					
					$data['data_inscriere'] = $_POST['data_inscriere'];
					
					}
					else
					{
					$data['data_inscriere'] = "";	
					}
					
				if((!empty($_POST['ip'])) && (!ctype_space($_POST['ip']))){
					
					$data['ip'] = $_POST['ip'];
					
					}
					else
					{
					$data['ip'] = "";	
					}
					
				
			
					$query = "UPDATE `inscrisi_teamexpert` SET ";
					
					foreach($data as $camp=>$valoare){
						
						$query .= $camp . " = ";
						$query .= (is_numeric($valoare)) ? $valoare . " , " :  "'".$valoare."'" . " , "; 
						
						}
					$query = substr($query,0,-2);
					$query .= " WHERE `cod_unic` = '" .$cod. "'";
					$select = "SET NAMES 'utf8'";
					$db->execute($select);
					$rezultat = $db->execute($query);
					
					if($rezultat){
					
						header("Location: detalii.php?cod=".$cod);
						exit(); 
					}
					else
					{
					echo "Update nereusit";	
					}
			
			}
			
			// 2 procesez comanda cancel detalii inscris 
			
			if((isset($_POST['anuleaza_update'])) && ($_POST['anuleaza_update'] == "true")) {
				
				header("Location: detalii.php?cod=".$_POST['trimis_detalii_user']);
				exit();
				} 
			
			
			// 3 procesez comanda cancel newsletter
			 if(isset($_POST['cancel'])){
					 
					header("Location: modifica_newsletter.php?cod=".$_POST['id']);
					exit(); 
					 }
			
			
			
			
			// 4 procesez datele ce vin din pagina modifica_newsletter.php
			if((isset($_POST['trimis_update'])) && ($_POST['trimis_update'] == "ok")){
				
				 $id_newsletter = $_POST['id'];
				
					// se verifica datele venite prin post si apoi procesam update-ul
					
					if((!empty($_POST['nume_folder'])) && (!ctype_space($_POST['nume_folder']))){
						
						$data['nume_folder'] = $_POST['nume_folder'];
						}
						else
						{
						$data['nume_folder'] = "";	
						}
					if((!empty($_POST['nume_sablon'])) && (!ctype_space($_POST['nume_sablon']))){
						
						$data['nume_sablon'] = $_POST['nume_sablon'];
						}
						else
						{
						$data['nume_sablon'] = "";	
						}
						
					if((!empty($_POST['activ'])) && (!ctype_space($_POST['activ']))){
						
						$data['activ'] = $_POST['activ'];
						}
						else
						{
						$data['activ'] = "";	
						}
						
					if((!empty($_POST['cod_newsletter'])) && (!ctype_space($_POST['cod_newsletter']))){
						
						$data['editor'] = base64_encode(stripslashes($_POST['cod_newsletter']));
						}
						else
						{
						$data['editor'] = "";	
						}
						
						$query = "UPDATE `sabloane_newsletter` SET ";
						
						foreach($data as $campuri=>$valori){
							
							$query .= $campuri . " = ";
							$query .= (is_numeric($valori)) ? $valori . " , " : "'" . $valori . "'" . " , ";							
							}
						$query = substr($query,0,-2);
						$query .= " WHERE `id` = '" . $id_newsletter ."'";
						$rezultat = $db->execute($query);
						
						if($rezultat){
							
							header("Location: modifica_newsletter.php?cod=".$id_newsletter);
							exit();
							}
						
				
				}
				
					// 5 procesez update admin_user
					if(isset($_POST['date_user'])){
						
						$user = $_POST['date_user'];
						
						if((!empty($_POST['uniq_id'])) && !ctype_space($_POST['uniq_id'])){
							
						$date['uniq_id'] = $_POST['uniq_id'];	
						}
						else
						{
						$date['uniq_id'] = "";
						}
						
						if((!empty($_POST['username'])) && !ctype_space($_POST['username'])){
							
						$date['username'] = $_POST['username'];	
						}
						else
						{
						$date['username'] = "";
						}
						
						if((!empty($_POST['parola'])) && !ctype_space($_POST['parola'])){
							
						$date['parola'] = $_POST['parola'];	
						}
						else
						{
						$date['parola'] = "";
						}
						
						if((!empty($_POST['data'])) && !ctype_space($_POST['data'])){
							
						$date['data_inregistrarii'] = $_POST['data'];	
						}
						else
						{
						$date['data_inregistrarii'] = "";
						}
						
						if((isset($_POST['acces'])) == "admin") {
							
						$date['acces'] = 1;	
						}
						if((isset($_POST['acces'])) == "")
						{
						    $query = "SELECT `uniq_id` FROM `login` WHERE `uniq_id` = '".$user."' AND `acces` = 1";
							$result = $db->execute($query);
							$row = mysqli_fetch_array($result);
							if($row['uniq_id'] == $_SESSION['uniq_id']){
								$mesaj = base64_encode("Nu poți să-ți schimbi singur accesul în timpul sesiunii");
								header("Location: admin_area.php?eroare=".$mesaj);
								exit();
								//$date['acces'] = 1;
								
								}
								elseif($row['uniq_id'] == "5339fd7b4f6e8"){
								$mesaj = base64_encode("Acest cont este protejat, nu poate fi modificat");
								header("Location: admin_area.php?eroare=".$mesaj);
								exit();	
								}
								else
								{
								$date['acces'] = 0;	
								}
						
						
						}
									
						$query = "UPDATE `login` SET ";
						foreach($date as $camp=>$valori){
							
							$query .= $camp . " = ";
							$query .= (is_numeric($valori)) ? $valori . " , " : "'" . $valori . "'". " , " ;
							}
							$query = substr($query,0,-2);
							$query .= " WHERE `uniq_id` = '".$user."'";
							
							
						$result = $db->execute($query);
						$mesaj = "Datele de acces au fost modificate cu succes";
						$mesaj= base64_encode($mesaj);
						if($result){
							
							header("Location: admin_area.php?succes=".$mesaj);
							exit();
							}
							
						
						
						
					}	
					
					
					
					// 5' procesez update user magazin
					if(isset($_POST['user_magazin'])){
						
						$user = $_POST['user_magazin'];
						
						if((!empty($_POST['uniq_id'])) && !ctype_space($_POST['uniq_id'])){
							
						$date['uniq_id'] = $_POST['uniq_id'];	
						}
						else
						{
						$date['uniq_id'] = "";
						}
						
						if((!empty($_POST['username'])) && !ctype_space($_POST['username'])){
							
						$date['username'] = $_POST['username'];	
						}
						else
						{
						$date['username'] = "";
						}
						
						if((!empty($_POST['parola'])) && !ctype_space($_POST['parola'])){
							
						$date['parola'] = $_POST['parola'];	
						}
						else
						{
						$date['parola'] = "";
						}
						
						if((!empty($_POST['data'])) && !ctype_space($_POST['data'])){
							
						$date['data_inregistrarii'] = $_POST['data'];	
						}
						else
						{
						$date['data_inregistrarii'] = "";
						}
						
						if((isset($_POST['acces'])) == "admin") {
							
						$date['acces'] = 1;	
						}					
						else
						{
						$date['acces'] = 0;	
						}
						
						
					
									
						$query = "UPDATE `login_magazin` SET ";
						foreach($date as $camp=>$valori){
							
							$query .= $camp . " = ";
							$query .= (is_numeric($valori)) ? $valori . " , " : "'" . $valori . "'". " , " ;
							}
							$query = substr($query,0,-2);
							$query .= " WHERE `uniq_id` = '".$user."'";
							
							
						$result = $db->execute($query);
						$mesaj = "Datele de acces au fost modificate cu succes";
						$mesaj= base64_encode($mesaj);
						if($result){
							
							header("Location: admin_area.php?succes=".$mesaj);
							exit();
							}
							
						
						
						
					}										
																
							
						
					// 6 sterge user_admin
					if((isset($_GET['duniq'])) && (isset($_SESSION['uniq_id']))){
						
						$user = $_GET['duniq'];					
						
												
						if($user == $_SESSION['uniq_id']){
							
							$mesaj = "Nu te poți șterge pe tine însuți";
							$mesaj = base64_encode($mesaj);
							header('Location: admin_area.php?eroare='.$mesaj);
							exit();
							}
							
							else
							
							{							
						
						$query = "DELETE FROM `login` WHERE `uniq_id` = '". $user."'";
						$result = $db->execute($query);
						
						if($result){
							header("Location: admin_area.php");	
							exit();
						}
						}
					}
						
						
					// 6' sterge user magazin
					if(isset($_GET['uniqid'])) {
						
						$user = $_GET['uniqid'];					
						
												
						if($user == $_SESSION['uniq_id']){
							
							$mesaj = "Nu te poți șterge pe tine însuți";
							$mesaj = base64_encode($mesaj);
							header('Location: admin_area.php?eroare='.$mesaj);
							exit();
							}
							
							else
							
							{							
						
						$query = "DELETE FROM `login_magazin` WHERE `uniq_id` = '". $user."'";
						$result = $db->execute($query);
						
						if($result){
							header("Location: admin_area.php");	
							exit();
						}
						}
					}
					
					
					
					// 7 sterge tabel competitii
					if((isset($_POST['tbl_id'])) && (isset($_POST['tabel']))){
						
						
						// stergerea inregistrarii competitiei in tabelul `tabel_competitii`
						$tabel = $_POST['tbl_id'];
							
						$query = "DELETE FROM `tabel_competitii` WHERE `id` = '". $tabel ."'";
						$result = $db->execute($query);
						
						// stergerea tabelului ce contine datele competitiei						
						$nume = $_POST['tabel'];
							
						$query = "DROP TABLE IF EXISTS  `". $nume ."`";
						$result = $db->execute($query);
						
						// se modifica in tabelul `nume_competitii` starea optiunii tabelului
						$update = "UPDATE `nume_competitii` SET `competitii_activ` = 0
						 WHERE `nume_competitie` = '".$_POST['flag']."'";
						$result = $db->execute($update);
					
							
						header("Location: tabele_competitii.php");	
						exit();
						
					}
					
					// 8 update modificare date concurent din tabel tip BikeFest
					if((isset($_POST['modifica_date_concurent'])) && (isset($_POST['nume_tabel'])) && (isset($_POST['id_concurent']))){
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$date['cod_unic_participant'] = $_POST['cod_unic'];
							}
						 	else
							{
							$date['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$date['numele'] = $_POST['numele'];
							}
						 	else
							{
							$date['numele'] = "";	
							}
							
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$date['prenumele'] = $_POST['prenumele'];
							}
						 	else
							{
							$date['prenumele'] = "";	
							}
						 if((!empty($_POST['varsta'])) && (is_numeric($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$date['varsta'] = $_POST['varsta'];
							}
						 	else
							{
							$date['varsta'] = "";	
							}
						 if((!empty($_POST['cat_varsta'])) && (!ctype_space($_POST['cat_varsta']))){
							
							$date['cat_varsta'] = $_POST['cat_varsta'];
							}
						 	else
							{
							$date['cat_varsta'] = "";	
							}
						 if((!empty($_POST['sex'])) && (!ctype_space($_POST['sex']))){
							
							$date['sex'] = $_POST['sex'];
							}
						 	else
							{
							$date['sex'] = "";	
							}
						 if((!empty($_POST['traseu'])) && (!ctype_space($_POST['traseu']))){
							
							$date['traseu'] = $_POST['traseu'];
							}
						 	else
							{
							$date['traseu'] = "";	
							}
						 if(isset($_POST['insotitor'])){
							
							$date['insotitor'] = "da";
							}
						 	else
							{
							$date['insotitor'] = '';	
							}
						if((isset($_POST['flag'])) && ($_POST['flag'] == "Cupa 1 Iunie")){
							// nu sunt procesate informatiile vip pentru Cupa 1 Iunie
						   }
						   else
						   {
						
						 if(isset($_POST['vip'])){
							
							$date['pachet_vip'] = 'vip';
							}
						 	else
							{
							$date['pachet_vip'] = '';	
							}
						 // daca vip este bifat se verifica daca marimea tricoului este aleasa	
						 if($date['pachet_vip'] == "vip"){
						 if(!empty($_POST['tricou'])){
							
							$date['tricou'] = $_POST['tricou'];
							}
						 	else
							{
							$err = "Nu ai ales mărimea tricoului pentru pachetul vip";	
							}
						 }
						 else
						 {
						  $date['tricou']  = "";
						 }
						 // daca se debifeaza vip print personalizat revine la ""
						 if($date['pachet_vip'] == "vip"){
						 if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							
							$date['print_vip'] = $_POST['print_vip'];
							}
						 }
						 else
						 {
						 $date['print_vip'] = "";	
						 }
						 
						 }
						 
						if((isset($_POST['flag'])) && ($_POST['flag'] == "Cupa Tabere cu Suflet")){
								if(isset($_POST['duatlon']) == "duatlon"){
									
									$date['duatlon'] = "duatlon";
									}
									else
									{
									$date['duatlon'] = "";	
									}
							
							}
						
						if((!empty($_POST['locul'])) && (!ctype_space($_POST['locul'])) && (is_numeric($_POST['locul']))){
							
							$date['locul'] = $_POST['locul'];
							}
						 	else
							{
							$date['locul'] = "";	
							}
						
						if((!empty($_POST['puncte'])) && (!ctype_space($_POST['puncte'])) && (is_numeric($_POST['puncte']))){
							
							$date['puncte'] = $_POST['puncte'];
							}
						 	else
							{
							$date['puncte'] = "";	
							}
						 if((!empty($_POST['timp'])) && (!ctype_space($_POST['timp'])) && (is_string($_POST['timp']))){
							
							$date['timp'] = $_POST['timp'];
							}
						 	else
							{
							$date['timp'] = "";	
							}	
												
						if(isset($_POST['flag']) && ($_POST['flag'] == "Cupa 1 Iunie")){
							
							// pentru celelalte competitii nu se proceseaza
							
						}
						else
						{
								if(!empty($_POST['ture']) && (!ctype_space($_POST['ture']))){
							
									$date['ture'] = $_POST['ture'];
								}
						 		else
								{
									$date['ture'] = "";	
								}
							
						}		
							
							
						 if((!empty($_POST['numar'])) && (is_numeric($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$date['numar_concurs'] = $_POST['numar'];
							}
						 	else
							{
							$date['numar_concurs'] = "";	
							}
						 if((!empty($_POST['email'])) && (!ctype_space($_POST['email']))){
							 
							 $date['email'] = $_POST['email'];
							 }
							 else
							 {
							 $date['email'] = "";	 
							 }
							 
						 if((!empty($_POST['telefon'])) && (!ctype_space($_POST['telefon']))){
							 
							 $date['telefon'] = $_POST['telefon'];
							 }
							 else
							 {
							 $date['telefon'] = "";	 
							 }
							 
						if((isset($_POST['flag'])) && ($_POST['flag'] == "Cupa 1 Iunie")){
							
						// nu se proceseaza informatiile de taxa pentru Cupa 1 Iunie	
						}
						else
						{
							 
						 if(!empty($_POST['modalitate'])){
							 
							 $date['modalitate_plata'] = $_POST['modalitate'];
							 }
							 else
							 {
							 $date['modalitate_plata'] = "";	 
							 }
						 if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
							 
							 $date['suma'] = $_POST['suma'];
							 }
							 else
							 {
							 $date['suma'] = "";	 
							 }
						 if((!empty($_POST['chitanta'])) && (!ctype_space($_POST['chitanta']))){
							 
							 $date['nr_chitanta'] = $_POST['chitanta'];
							 }
							 else
							 {
							 $date['nr_chitanta'] = "";	 
							 }
						 if((!empty($_POST['confirma'])) && (!ctype_space($_POST['confirma']))){
							 
							 $date['confirmare'] = $_POST['confirma'];
							 }
							 else
							 {
							 $date['confirmare'] = "";	 
							 }
							 
						 
						 if((!empty($_POST['taxa'])) && (!ctype_space($_POST['taxa']))){
							
							$date['taxa'] = $_POST['taxa'];
							}
						 	else
							{
							$date['taxa'] = "";	
							}
						}	
						
							
						   if(!isset($err)){	
							
						   $query = "UPDATE `". $_POST['nume_tabel'] ."` SET ";
							foreach($date as $camp=>$valoare){
								
								$query .= $camp . " = ";
								$query .= (is_numeric($valoare)) ? $valoare . " , " : "'". $valoare ."'". " , ";
								}
							$query = substr($query,0,-2);
							$query .= " WHERE id = ".$_POST['id_concurent'];							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);																				
							$result = $db->execute($query);
							
							
																									
							if($result){
								
								if(isset($_POST['flag'])){
									
									header("Location: detalii_tabel_competitie_bikefest.php?tabelid=".$_POST['nume_tabel']."&flag=".$_POST['flag']);
									exit();		
									
									}
									else
									{
									header("Location: detalii_tabel_competitie_bikefest.php?tabelid=".$_POST['nume_tabel']);
									exit();	
									}
								
								}
								else
								{
									
								if(isset($_POST['flag'])){
									
								
								$err = base64_encode("Eroare update");								
								header("Location: modificare_date_concurent.php?table_name=".$_POST['nume_tabel'].'&eroare='.$err."&flag=".$_POST['flag'].'&comp_id='.$_POST['id_concurent']);	
								exit();		
								 }
								else
								 {
								$err = base64_encode("Eroare update");
								header("Location: modificare_date_concurent.php?table_name=".$_POST['nume_tabel'].'&eroare='.$err.'&comp_id='.$_POST['id_concurent']);
								exit();	
								}
								
								}
						
						   }
								
						}
						
							if(isset($err)){
								
								$err = base64_encode($err);
								if(isset($_POST['flag'])){
									
								header("Location: modificare_date_concurent.php?table_name=".$_POST['nume_tabel']."&eroare=".$err."&flag=".$_POST['flag']."&comp_id=".$_POST['id_concurent']);	
								}
								else
								{
								header("Location: modificare_date_concurent.php?table_name=".$_POST['nume_tabel'].'&eroare='.$err.'&comp_id='.$_POST['id_concurent']);	
								}
							}
						
						
					
						// 8' update detalii modificare date concurent maraton
					if((isset($_POST['modifica_date_concurent_maraton'])) && (isset($_POST['nume_tabel'])) && (isset($_POST['id_concurent']))){
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$date['cod_unic_participant'] = $_POST['cod_unic'];
							}
						 	else
							{
							$date['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$date['numele'] = $_POST['numele'];
							}
						 	else
							{
							$date['numele'] = "";	
							}
							
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$date['prenumele'] = $_POST['prenumele'];
							}
						 	else
							{
							$date['prenumele'] = "";	
							}
						 if((!empty($_POST['varsta'])) && (is_numeric($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$date['varsta'] = $_POST['varsta'];
							}
						 	else
							{
							$date['varsta'] = "";	
							}
						 if((!empty($_POST['cat_varsta'])) && (!ctype_space($_POST['cat_varsta']))){
							
							$date['cat_varsta'] = $_POST['cat_varsta'];
							}
						 	else
							{
							$date['cat_varsta'] = "";	
							}
						 if((!empty($_POST['sex'])) && (!ctype_space($_POST['sex']))){
							
							$date['sex'] = $_POST['sex'];
							}
						 	else
							{
							$date['sex'] = "";	
							}
						 if((!empty($_POST['traseu'])) && (!ctype_space($_POST['traseu']))){
							
							$date['traseu'] = $_POST['traseu'];
							}
						 	else
							{
							$date['traseu'] = "";	
							}						 
						 if(isset($_POST['vip'])){
							
							$date['pachet_vip'] = 'vip';
							}
						 	else
							{
							$date['pachet_vip'] = '';	
							}
						 if($date['pachet_vip'] == "vip"){
								 if(!empty($_POST['tricou'])) {
							
								 $date['tricou'] = $_POST['tricou'];
								 }
						 		 else
								 {
								 $errno = "Nu ai ales mărimea tricoului pentru pachetul vip";	
								 }
						 }
						 else
						 {
						 $date['tricou'] = "";	 
						 }
						 
						 if($date['pachet_vip'] == 'vip'){
						 if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							
							$date['print_vip'] = $_POST['print_vip'];
							}						 	
						 }
						 else
						 {
						 $date['print_vip'] = "";	 
						 }
							
						 if((!empty($_POST['duatlon'])) && (!ctype_space($_POST['duatlon']))){
							$date['duatlon'] = "duatlon"; 
							 }
							else
							{
							$date['duatlon'] = "";	
							}
						
						if((!empty($_POST['locul'])) && (!ctype_space($_POST['locul'])) && (is_numeric($_POST['locul']))){
							
							$date['locul'] = $_POST['locul'];
							}
						 	else
							{
							$date['locul'] = "";	
							}
						
						if((!empty($_POST['puncte'])) && (!ctype_space($_POST['puncte'])) && (is_numeric($_POST['puncte']))){
							
							$date['puncte'] = $_POST['puncte'];
							}
						 	else
							{
							$date['puncte'] = "";	
							}
						 if((!empty($_POST['timp'])) && (!ctype_space($_POST['timp'])) && (is_string($_POST['timp']))){
							
							$date['timp'] = $_POST['timp'];
							}
						 	else
							{
							$date['timp'] = "";	
							}
						if((!empty($_POST['ture'])) && (!ctype_space($_POST['ture'])) && (is_numeric($_POST['ture']))){
							
							$date['ture'] = $_POST['ture'];
							}
						 	else
							{
							$date['ture'] = "";	
							}
						 if((!empty($_POST['numar'])) && (is_numeric($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$date['numar_concurs'] = $_POST['numar'];
							}
						 	else
							{
							$date['numar_concurs'] = "";	
							}
						  if((!empty($_POST['email'])) && (!ctype_space($_POST['email']))){
							 
							 $date['email'] = $_POST['email'];
							 }
							 else
							 {
							 $date['email'] = "";	 
							 }
						  if((!empty($_POST['telefon'])) && (!ctype_space($_POST['telefon']))){
							 
							 $date['telefon'] = $_POST['telefon'];
							 }
							 else
							 {
							 $date['telefon'] = "";	 
							 }
						 if(!empty($_POST['modalitate'])){
							 
							 $date['modalitate_plata'] = $_POST['modalitate'];
							 }
							 else
							 {
							 $date['modalitate_plata'] = "";	 
							 }
						 if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
							 
							 $date['suma'] = $_POST['suma'];
							 }
							 else
							 {
							 $date['suma'] = "";	 
							 }
						 if((!empty($_POST['chitanta'])) && (!ctype_space($_POST['chitanta']))){
							 
							 $date['nr_chitanta'] = $_POST['chitanta'];
							 }
							 else
							 {
							 $date['nr_chitanta'] = "";	 
							 }
						 if((!empty($_POST['confirma'])) && (!ctype_space($_POST['confirma']))){
							 
							 $date['confirmare'] = $_POST['confirma'];
							 }
							 else
							 {
							 $date['confirmare'] = "";	 
							 }
						 if((!empty($_POST['taxa'])) && (!ctype_space($_POST['taxa']))){
							
							$date['taxa'] = $_POST['taxa'];
							}
						 	else
							{
							$date['taxa'] = "";	
							}	
							
							
							if(!isset($errno)){
							
						   $query = "UPDATE `". $_POST['nume_tabel'] ."` SET ";
							foreach($date as $camp=>$valoare){
								
								$query .= $camp . " = ";
								$query .= (is_numeric($valoare)) ? $valoare . " , " : "'". $valoare ."'". " , ";
								}
							$query = substr($query,0,-2);
							$query .= " WHERE id = ".$_POST['id_concurent'];
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
																						
							$result = $db->execute($query);
							
																									
							if($result){
								
								
								header("Location: detalii_tabel_competitie_maraton.php?tabelid=".$_POST['nume_tabel']."&flag=".$_POST['flag']);
								exit();
								}
								else
								{
								$err = base64_encode("Eroare update");
								header("Location: modificare_date_concurent_maraton.php?table_name=".$_POST['nume_tabel']."&err=".$err."e&comp_id=".$_POST['id_concurent']."&flag=".$_GET['flag']);	
								}
							}
								
						}
						
						if(isset($errno)){
						
						$err = base64_encode($errno);	
						header("Location: modificare_date_concurent_maraton.php?table_name=".$_POST['nume_tabel'].'&err='.$err.'&comp_id='.$_POST['id_concurent']."&flag=".$_GET['flag']);
									exit();		
							}
						
						
						// 8'' update detalii modificare date concurent sinaia
					if((isset($_POST['modifica_date_concurent_sinaia'])) && (isset($_POST['nume_tabel'])) && (isset($_POST['id_concurent']))){
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$date['cod_unic_participant'] = $_POST['cod_unic'];
							}
						 	else
							{
							$date['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$date['numele'] = $_POST['numele'];
							}
						 	else
							{
							$date['numele'] = "";	
							}
							
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$date['prenumele'] = $_POST['prenumele'];
							}
						 	else
							{
							$date['prenumele'] = "";	
							}
						 if((!empty($_POST['varsta'])) && (is_numeric($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$date['varsta'] = $_POST['varsta'];
							}
						 	else
							{
							$date['varsta'] = "";	
							}
						 if((!empty($_POST['cat_varsta'])) && (!ctype_space($_POST['cat_varsta']))){
							
							$date['cat_varsta'] = $_POST['cat_varsta'];
							}
						 	else
							{
							$date['cat_varsta'] = "";	
							}
						 if((!empty($_POST['sex'])) && (!ctype_space($_POST['sex']))){
							
							$date['sex'] = $_POST['sex'];
							}
						 	else
							{
							$date['sex'] = "";	
							}
						 if((!empty($_POST['traseu'])) && (!ctype_space($_POST['traseu']))){
							
							$date['traseu'] = $_POST['traseu'];
							}
						 	else
							{
							$date['traseu'] = "";	
							}						 
						 if(isset($_POST['vip'])){
							
							$date['pachet_vip'] = 'vip';
							}
						 	else
							{
							$date['pachet_vip'] = '';	
							}
							
						 if($date['pachet_vip'] == 'vip'){
						 	if(!empty($_POST['tricou'])) {
							
							$date['tricou'] = $_POST['tricou'];
							}
						 	else
							{
							$notok = "Nu ai ales mărimea tricoului pentru pachetul vip";	
							}
						 }
						 else
						 {
						 $date['tricou'] = "";	 
						 }
						
						 if($date['pachet_vip'] == 'vip'){	
						 if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							
							$date['print_vip'] = $_POST['print_vip'];
							}
						 	
						 }
						 else
						 {
						  $date['print_vip'] = ""; 
						 }
						if((!empty($_POST['locul'])) && (!ctype_space($_POST['locul'])) && (is_numeric($_POST['locul']))){
							
							$date['locul'] = $_POST['locul'];
							}
						 	else
							{
							$date['locul'] = "";	
							}
						
						if((!empty($_POST['puncte'])) && (!ctype_space($_POST['puncte'])) && (is_numeric($_POST['puncte']))){
							
							$date['puncte'] = $_POST['puncte'];
							}
						 	else
							{
							$date['puncte'] = "";	
							}
						 if((!empty($_POST['timp'])) && (!ctype_space($_POST['timp'])) && (is_string($_POST['timp']))){
							
							$date['timp'] = $_POST['timp'];
							}
						 	else
							{
							$date['timp'] = "";	
							}
						 if((!empty($_POST['numar'])) && (is_numeric($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$date['numar_concurs'] = $_POST['numar'];
							}
						 	else
							{
							$date['numar_concurs'] = "";	
							}
						 if((!empty($_POST['email'])) && (!ctype_space($_POST['email']))){
							 
							 $date['email'] = $_POST['email'];
							 }
							 else
							 {
							 $date['email'] = "";	 
							 }
						  if((!empty($_POST['telefon'])) && (!ctype_space($_POST['telefon']))){
							 
							 $date['telefon'] = $_POST['telefon'];
							 }
							 else
							 {
							 $date['telefon'] = "";	 
							 }
						 if(!empty($_POST['modalitate'])){
							 
							 $date['modalitate_plata'] = $_POST['modalitate'];
							 }
							 else
							 {
							 $date['modalitate_plata'] = "";	 
							 }
						 if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
							 
							 $date['suma'] = $_POST['suma'];
							 }
							 else
							 {
							 $date['suma'] = "";	 
							 }
						 if((!empty($_POST['chitanta'])) && (!ctype_space($_POST['chitanta']))){
							 
							 $date['nr_chitanta'] = $_POST['chitanta'];
							 }
							 else
							 {
							 $date['nr_chitanta'] = "";	 
							 }
						 if((!empty($_POST['confirma'])) && (!ctype_space($_POST['confirma']))){
							 
							 $date['confirmare'] = $_POST['confirma'];
							 }
							 else
							 {
							 $date['confirmare'] = "";	 
							 }
						 if((!empty($_POST['taxa'])) && (!ctype_space($_POST['taxa']))){
							
							$date['taxa'] = $_POST['taxa'];
							}
						 	else
							{
							$date['taxa'] = "";	
							}	
							
							if(!isset($notok)){
							
						   $query = "UPDATE `". $_POST['nume_tabel'] ."` SET ";
							foreach($date as $camp=>$valoare){
								
								$query .= $camp . " = ";
								$query .= (is_numeric($valoare)) ? $valoare . " , " : "'". $valoare ."'". " , ";
								}
							$query = substr($query,0,-2);
							$query .= " WHERE id = ".$_POST['id_concurent'];
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
																						
							$result = $db->execute($query);
							
																									
							if($result){
								
								
								header("Location: detalii_tabel_competitie_sinaia.php?tabelid=".$_POST['nume_tabel'].'&flag='.$_POST['flag']);
								exit();
								}
								else
								{
								$err = base64_encode("Eroare update");
								header("Location: modificare_date_concurent_sinaia.php?table_name=".$_POST['nume_tabel']."&err=".$err."&comp_id=".$_POST['id_concurent'].'&flag='.$_POST['flag']);
								exit();
								}
							}
								
						}
					
						if(isset($notok)){
							
							$err = base64_encode($notok);
							header("Location: modificare_date_concurent_sinaia.php?table_name=".$_POST['nume_tabel']."&err=".$err."&comp_id=".$_POST['id_concurent'].'&flag='.$_POST['flag']);
								exit();
							}					
					
					
					
										
						// 8'''update detalii modificare date concurent freeride
					if((isset($_POST['modifica_date_concurent_freeride'])) && (isset($_POST['nume_tabel'])) && (isset($_POST['id_concurent']))){
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$date['cod_unic_participant'] = $_POST['cod_unic'];
							}
						 	else
							{
							$date['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$date['numele'] = $_POST['numele'];
							}
						 	else
							{
							$date['numele'] = "";	
							}
							
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$date['prenumele'] = $_POST['prenumele'];
							}
						 	else
							{
							$date['prenumele'] = "";	
							}
						 if((!empty($_POST['varsta'])) && (is_numeric($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$date['varsta'] = $_POST['varsta'];
							}
						 	else
							{
							$date['varsta'] = "";	
							}
						 if(!empty($_POST['cat_varsta'])){
							
							$date['cat_varsta'] = $_POST['cat_varsta'];
							}
						 	else
							{
							$date['cat_varsta'] = "";	
							}
						 if(!empty($_POST['sex'])){
							
							$date['sex'] = $_POST['sex'];
							}
						 	else
							{
							$date['sex'] = "";	
							}
						 if(!empty($_POST['categoria'])){
							
							$date['categoria'] = $_POST['categoria'];
							}
						 	else
							{
							$date['categoria'] = "";	
							}
						 if((!empty($_POST['adresa'])) && (!ctype_space($_POST['adresa']))){
							 
							 $date['adresa'] = $_POST['adresa'];
							 }
							 else
							 {
							 $date['adresa'] = "";	 
							 }
						 if(isset($_POST['experienta']) <> ""){
							 
							 $date['experienta'] = $_POST['experienta'];
							 }
							 else
							 {
							 $nok[] = "Nu ai menționat experiența";	 
							 }
													 
						 if(isset($_POST['vip'])){
							
							$date['pachet_vip'] = 'vip';
							}
						 	else
							{
							$date['pachet_vip'] = '';	
							}
							
						 if($date['pachet_vip'] == 'vip'){
						 	if(!empty($_POST['tricou'])) {
							
							$date['tricou'] = $_POST['tricou'];
							}
						 	else
							{
							$nok[] = "Nu ai ales mărimea tricoului pentru pachetul vip";	
							}
						 }
						 else
						 {
						 $date['tricou'] = "";	 
						 }
						
						 if($date['pachet_vip'] == 'vip'){	
						 if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							
							$date['print_vip'] = $_POST['print_vip'];
							}
						 	
						 }
						 else
						 {
						  $date['print_vip'] = ""; 
						 }
						if((!empty($_POST['locul'])) && (!ctype_space($_POST['locul'])) && (is_numeric($_POST['locul']))){
							
							$date['locul'] = $_POST['locul'];
							}
						 	else
							{
							$date['locul'] = "";	
							}
						
						if((!empty($_POST['puncte'])) && (!ctype_space($_POST['puncte'])) && (is_numeric($_POST['puncte']))){
							
							$date['puncte'] = $_POST['puncte'];
							}
						 	else
							{
							$date['puncte'] = "";	
							}
						if((!empty($_POST['total'])) && (!ctype_space($_POST['total'])) && (is_numeric($_POST['total']))){
							
							$date['total'] = $_POST['total'];
							}
						 	else
							{
							$date['total'] = "";	
							}
						 if((!empty($_POST['timp'])) && (!ctype_space($_POST['timp'])) && (is_string($_POST['timp']))){
							
							$date['timp'] = $_POST['timp'];
							}
						 	else
							{
							$date['timp'] = "";	
							}
						 if((!empty($_POST['numar'])) && (is_numeric($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$date['numar_concurs'] = $_POST['numar'];
							}
						 	else
							{
							$date['numar_concurs'] = "";	
							}
						 if((!empty($_POST['email'])) && (!ctype_space($_POST['email']))){
							 
							 $date['email'] = $_POST['email'];
							 }
							 else
							 {
							 $date['email'] = "";	 
							 }
						  if((!empty($_POST['telefon'])) && (!ctype_space($_POST['telefon']))){
							 
							 $date['telefon'] = $_POST['telefon'];
							 }
							 else
							 {
							 $date['telefon'] = "";	 
							 }
						 if(!empty($_POST['modalitate'])){
							 
							 $date['modalitate_plata'] = $_POST['modalitate'];
							 }
							 else
							 {
							 $date['modalitate_plata'] = "";	 
							 }
						 if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
							 
							 $date['suma'] = $_POST['suma'];
							 }
							 else
							 {
							 $date['suma'] = "";	 
							 }
						 if((!empty($_POST['chitanta'])) && (!ctype_space($_POST['chitanta']))){
							 
							 $date['nr_chitanta'] = $_POST['chitanta'];
							 }
							 else
							 {
							 $date['nr_chitanta'] = "";	 
							 }
						 if((!empty($_POST['confirma'])) && (!ctype_space($_POST['confirma']))){
							 
							 $date['confirmare'] = $_POST['confirma'];
							 }
							 else
							 {
							 $date['confirmare'] = "";	 
							 }
						 if((!empty($_POST['taxa'])) && (!ctype_space($_POST['taxa']))){
							
							$date['taxa'] = $_POST['taxa'];
							}
						 	else
							{
							$date['taxa'] = "";	
							}	
							
							if(!isset($nok)){
							
						   $query = "UPDATE `". $_POST['nume_tabel'] ."` SET ";
							foreach($date as $camp=>$valoare){
								
								$query .= $camp . " = ";
								$query .= (is_numeric($valoare)) ? $valoare . " , " : "'". $valoare ."'". " , ";
								}
							$query = substr($query,0,-2);
							$query .= " WHERE id = ".$_POST['id_concurent'];
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
																						
							$result = $db->execute($query);
							
																									
							if($result){
								
								
								header("Location: detalii_tabel_competitie_freeride.php?tabelid=".$_POST['nume_tabel'].'&flag='.$_POST['flag']);
								exit();
								}
								else
								{
								$err = base64_encode("Eroare update");
								header("Location: modificare_date_concurent_freeride.php?table_name=".$_POST['nume_tabel']."&err=".$err."&comp_id=".$_POST['id_concurent'].'&flag='.$_POST['flag']);
								exit();
								}
							}
								
						}
					
						if(isset($nok)){
							
							$err = base64_encode(serialize($nok));
							header("Location: modificare_date_concurent_freeride.php?table_name=".$_POST['nume_tabel']."&err=".$err."&comp_id=".$_POST['id_concurent'].'&flag='.$_POST['flag']);
								exit();
							}	
					
					
					
					// 8'''' update detalii modificare date concurent duatlon
					if((isset($_POST['modifica_date_concurent_duatlon'])) && (isset($_POST['nume_tabel'])) && (isset($_POST['id_concurent']))){
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$date['cod_unic_participant'] = $_POST['cod_unic'];
							}
						 	else
							{
							$date['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$date['numele'] = $_POST['numele'];
							}
						 	else
							{
							$date['numele'] = "";	
							}
							
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$date['prenumele'] = $_POST['prenumele'];
							}
						 	else
							{
							$date['prenumele'] = "";	
							}
						 if((!empty($_POST['varsta'])) && (is_numeric($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$date['varsta'] = $_POST['varsta'];
							}
						 	else
							{
							$date['varsta'] = "";	
							}
						 if((!empty($_POST['cat_varsta'])) && (!ctype_space($_POST['cat_varsta']))){
							
							$date['cat_varsta'] = $_POST['cat_varsta'];
							}
						 	else
							{
							$date['cat_varsta'] = "";	
							}
						 if((!empty($_POST['sex'])) && (!ctype_space($_POST['sex']))){
							
							$date['sex'] = $_POST['sex'];
							}
						 	else
							{
							$date['sex'] = "";	
							}
						 if(!empty($_POST['traseu_bicicleta'])) {
							
							$date['traseu_bicicleta'] = $_POST['traseu_bicicleta'];
							}
						 	else
							{
							$date['traseu_bicicleta'] = "";	
							}
							
						 if(!empty($_POST['traseu_alergare'])) {
							
							$date['traseu_alergare'] = $_POST['traseu_alergare'];
							}
						 	else
							{
							$date['traseu_alergare'] = "";	
							}
						 if(isset($_POST['insotitor'])){
							$date['insotitor'] = "da";
							 }
							else
							{
							$date['insotitor'] = "";	 
							}	
												 						 
						 if(isset($_POST['vip'])){
							
							$date['pachet_vip'] = 'vip';
							}
						 	else
							{
							$date['pachet_vip'] = '';	
							}
						 if($date['pachet_vip'] == "vip"){
								 if(!empty($_POST['tricou'])) {
							
								 $date['tricou'] = $_POST['tricou'];
								 }
						 		 else
								 {
								 $no = "Nu ai ales mărimea tricoului pentru pachetul vip";	
								 }
						 }
						 else
						 {
						 $date['tricou'] = "";	 
						 }
						 
						 if($date['pachet_vip'] == 'vip'){
						 if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							
							$date['print_vip'] = $_POST['print_vip'];
							}						 	
						 }
						 else
						 {
						 $date['print_vip'] = "";	 
						 }							
						
						if((!empty($_POST['locul'])) && (!ctype_space($_POST['locul'])) && (is_numeric($_POST['locul']))){
							
							$date['locul'] = $_POST['locul'];
							}
						 	else
							{
							$date['locul'] = "";	
							}
						
						if((!empty($_POST['puncte'])) && (!ctype_space($_POST['puncte'])) && (is_numeric($_POST['puncte']))){
							
							$date['puncte'] = $_POST['puncte'];
							}
						 	else
							{
							$date['puncte'] = "";	
							}
						 if((!empty($_POST['timp_bicicleta'])) && (!ctype_space($_POST['timp_bicicleta']))){
							
							$date['timp_bicicleta'] = $_POST['timp_bicicleta'];
							}
						 	else
							{
							$date['timp_bicicleta'] = "";	
							}
						if((!empty($_POST['timp_alergare'])) && (!ctype_space($_POST['timp_alergare']))){
							
							$date['timp_alergare'] = $_POST['timp_alergare'];
							}
						 	else
							{
							$date['timp_alergare'] = "";	
							}
						if((!empty($_POST['timp'])) && (!ctype_space($_POST['timp']))){
							
							$date['timp'] = $_POST['timp'];
							}
						 	else
							{
							$date['timp'] = "";	
							}
						if((!empty($_POST['dificultate'])) && (!ctype_space($_POST['dificultate']))){
							
							$date['dificultate'] = $_POST['dificultate'];
							}
						 	else
							{
							$date['dificultate'] = "";	
							}
						 if((!empty($_POST['numar'])) && (is_numeric($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$date['numar_concurs'] = $_POST['numar'];
							}
						 	else
							{
							$date['numar_concurs'] = "";	
							}
						  if((!empty($_POST['email'])) && (!ctype_space($_POST['email']))){
							 
							 $date['email'] = $_POST['email'];
							 }
							 else
							 {
							 $date['email'] = "";	 
							 }
						  if((!empty($_POST['telefon'])) && (!ctype_space($_POST['telefon']))){
							 
							 $date['telefon'] = $_POST['telefon'];
							 }
							 else
							 {
							 $date['telefon'] = "";	 
							 }
						 if(!empty($_POST['modalitate'])){
							 
							 $date['modalitate_plata'] = $_POST['modalitate'];
							 }
							 else
							 {
							 $date['modalitate_plata'] = "";	 
							 }
						 if((!empty($_POST['suma'])) && (!ctype_space($_POST['suma'])) && (is_numeric($_POST['suma']))){
							 
							 $date['suma'] = $_POST['suma'];
							 }
							 else
							 {
							 $date['suma'] = "";	 
							 }
						 if((!empty($_POST['chitanta'])) && (!ctype_space($_POST['chitanta']))){
							 
							 $date['nr_chitanta'] = $_POST['chitanta'];
							 }
							 else
							 {
							 $date['nr_chitanta'] = "";	 
							 }
						 if((!empty($_POST['confirma'])) && (!ctype_space($_POST['confirma']))){
							 
							 $date['confirmare'] = $_POST['confirma'];
							 }
							 else
							 {
							 $date['confirmare'] = "";	 
							 }
						 if((!empty($_POST['taxa'])) && (!ctype_space($_POST['taxa']))){
							
							$date['taxa'] = $_POST['taxa'];
							}
						 	else
							{
							$date['taxa'] = "";	
							}	
							
							
							if(!isset($no)){
							
						   $query = "UPDATE `". $_POST['nume_tabel'] ."` SET ";
							foreach($date as $camp=>$valoare){
								
								$query .= $camp . " = ";
								$query .= (is_numeric($valoare)) ? $valoare . " , " : "'". $valoare ."'". " , ";
								}
							$query = substr($query,0,-2);
							$query .= " WHERE id = ".$_POST['id_concurent'];
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
																						
							$result = $db->execute($query);
							
																									
							if($result){
								
								
								header("Location: detalii_tabel_competitie_duatlon.php?tabelid=".$_POST['nume_tabel'].'&flag='.$_POST['flag']);
								exit();
								}
								else
								{
								$er = base64_encode("Eroare update");
								header("Location: modificare_date_concurent_duatlon.php?table_name=".$_POST['nume_tabel']."&err=".$er."e&comp_id=".$_POST['id_concurent'].'&flag='.$_POST['flag']);	
								}
							}
								
						}
						
						if(isset($no)){
						
						$er = base64_encode($no);	
						header("Location: modificare_date_concurent_duatlon.php?table_name=".$_POST['nume_tabel'].'&err='.$er.'&comp_id='.$_POST['id_concurent'].'&flag='.$_POST['flag']);
									exit();		
						}
						
					
					
					
						
					// 9 stergere concurent din competitie
					if((isset($_POST['sterge_concurent'])) && (isset($_POST['id_concurent'])) && (isset($_POST['tabel']))){
						
						$id =  $_POST['id_concurent'];
						$tabel = $_POST['tabel'];
						
						$query = "DELETE FROM `". $tabel ."` WHERE `id` = {$id}";
						$result = $db->execute($query);
						
							if($result){
							$mesaj = "Un participant a fost șters cu succes din competiție";
							$mesaj = base64_encode($mesaj);	
							$pagina = $_SERVER['HTTP_REFERER'];
							header('Location: '.$pagina.'&succes='.$mesaj."&flag=".$_POST['flag']);
								}
						
						}
					
						
					// 10 update tabel competitie tip Bike Fest 
					if((isset($_POST['modifica_competitie'])) && ($_POST['modifica_competitie'] == "ok")){
						
						$query = "SELECT * FROM `".$_POST['modifica_competitie_tabel']."` WHERE 1 LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";
						
						$result = $db->execute($query);
						
						$count = $db->getCount($result);
						
						
						for($i=0; $i < $count; $i++ ){
							
							if($_POST['flag'] == "Cupa Tabere cu Suflet"){
							
			 				 $query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`traseu` = '".$_POST['traseu'][$i]."',
							`insotitor` = '".$_POST['insotitor'][$i]."',
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',
							`duatlon` = '".$_POST['duatlon'][$i]."',
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."', 							 
							`taxa` = '".$_POST['taxa'][$i]."'
							 WHERE `id` = {$_POST['id'][$i]}";
							
							}
							else if ($_POST['flag'] == "Cupa 1 Iunie")
							{
								
							$query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`traseu` = '".$_POST['traseu'][$i]."',
							`insotitor` = '".$_POST['insotitor'][$i]."',
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',							
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."'													
							 WHERE `id` = {$_POST['id'][$i]}";
								
							}
							else
							{
							
							$query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`traseu` = '".$_POST['traseu'][$i]."',
							`insotitor` = '".$_POST['insotitor'][$i]."',
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',							
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."',							  
							`taxa` = '".$_POST['taxa'][$i]."'
							 WHERE `id` = {$_POST['id'][$i]}";	
								
							}
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$update = 	$db->execute($query1);	
						}
						
						$pagina = $_SERVER['HTTP_REFERER'];
						if(isset($update)){
							
							
							if(isset($_POST['flag'])){								
								
						// redirectez catre pagina modificare tabel competitie 		
						
						header("Location: ".$pagina."?tbl_name=".$_POST['modifica_competitie_tabel']."&coloana=".$_POST['coloana']."&pagina=".$_POST['pagina']."&flag=".$_POST['flag']);
						exit();
							}
							
						}	
						else 
						{
						$mesaj = "Tabelul nu conține nicio informație";
						$pagina = $_SERVER['HTTP_REFERER'];
						header("Location: $pagina&err=".$mesaj."&flag=".$_POST['flag']);	
						}					
						
						}
						
						
					// 10 ' update tabel competitie tip On Top of the World	
					
					if((isset($_POST['modifica_competitie_sinaia'])) && ($_POST['modifica_competitie_sinaia'] == "ok")){
						
						$query = "SELECT * FROM `".$_POST['modifica_competitie_tabel']."` WHERE 1 LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";
						
						$result = $db->execute($query);
						
						$count = $db->getCount($result);
						
						
						for($i=0; $i < $count; $i++ ){
							
			 			 $query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`traseu` = '".$_POST['traseu'][$i]."',							
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."', 							
							`taxa` = '".$_POST['taxa'][$i]."'
							  WHERE `id` = {$_POST['id'][$i]}";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$update = 	$db->execute($query1);	
							
							}
						
						$pagina = $_SERVER['HTTP_REFERER'];
						if(isset($update)){
						
						header("Location: ".$pagina."?tbl_name=".$_POST['modifica_competitie_tabel']."&coloana=".$_POST['coloana']."&pagina=".$_POST['pagina'].'&flag='.$_POST['flag']);
						
						}	
						else 
						{
						$mesaj = "Tabelul nu conține nicio informație";
						$pagina = $_SERVER['HTTP_REFERER'];
						header("Location: $pagina&err=".$mesaj.'&flag='.$_POST['flag']);	
						}
						
						}
						
						
						
						// 10 '' update tabel competitie tip Maraton Tabere cu Suflet	
					
					if((isset($_POST['modifica_competitie_maraton'])) && ($_POST['modifica_competitie_maraton'] == "ok")){
						
						$query = "SELECT * FROM `".$_POST['modifica_competitie_tabel']."` WHERE 1 LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";
						
						$result = $db->execute($query);
						
						$count = $db->getCount($result);
						
						
						for($i=0; $i < $count; $i++ ){
							
			 			 $query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`traseu` = '".$_POST['traseu'][$i]."',							
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."', 							 
							`taxa` = '".$_POST['taxa'][$i]."'
							  WHERE `id` = {$_POST['id'][$i]}";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$update = 	$db->execute($query1);	
							
							}
						
						//$pagina = $_SERVER['HTTP_REFERER'];
						if(isset($update)){
						
						header("Location: modificare_tabel_competitie_maraton.php?tbl_name=".$_POST['modifica_competitie_tabel']."&coloana=".$_POST['coloana']."&pagina=".$_POST['pagina']."&flag=".$_POST['flag']);
						
						}	
						else 
						{
						$mesaj = "Tabelul nu conține nicio informație";
						$pagina = $_SERVER['HTTP_REFERER'];
						header("Location: $pagina&err=".$mesaj."&flag=".$_POST['flag']);	
						}
						
						}
						
						
						
						
					// 10 ''' update tabel competitie tip Cupa Malinului (freeride)	
					
					if((isset($_POST['modifica_competitie_freeride'])) && ($_POST['modifica_competitie_freeride'] == "ok")){
						
						$query = "SELECT * FROM `".$_POST['modifica_competitie_tabel']."` WHERE 1 LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";
						
						$result = $db->execute($query);
						
						$count = $db->getCount($result);
						
						
						for($i=0; $i < $count; $i++ ){
							
			 			 $query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`categoria` = '".$_POST['categoria'][$i]."',
							`experienta` = '".$_POST['experienta'][$i]."',
							`adresa` = '".$_POST['adresa'][$i]."',							
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."', 							 
							`taxa` = '".$_POST['taxa'][$i]."'
							  WHERE `id` = {$_POST['id'][$i]}";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$update = 	$db->execute($query1);	
							
							}
						
						$pagina = $_SERVER['HTTP_REFERER'];
						if(isset($update)){
						
						header("Location: ".$pagina."?tbl_name=".$_POST['modifica_competitie_tabel']."&coloana=".$_POST['coloana']."&pagina=".$_POST['pagina'].'&flag='.$_POST['flag']);
						
						}	
						else 
						{
						$mesaj = "Tabelul nu conține nicio informație";
						$pagina = $_SERVER['HTTP_REFERER'];
						header("Location: $pagina&err=".$mesaj."&flag=".$_POST['flag']);	
						}
						
						}
						
						
					// 10 '''' update tabel competitie tip Duatlon Tabere cu Suflet	
					
					if((isset($_POST['modifica_competitie_duatlon'])) && ($_POST['modifica_competitie_duatlon'] == "ok")){
						
						$query = "SELECT * FROM `".$_POST['modifica_competitie_tabel']."` WHERE 1 LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";
						
						$result = $db->execute($query);
						
						$count = $db->getCount($result);
						
						
						for($i=0; $i < $count; $i++ ){
							
			 			 $query1 = "UPDATE `".$_POST['modifica_competitie_tabel']."` SET 
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',
							`varsta` = '".$_POST['varsta'][$i]."',
							`cat_varsta` = '".$_POST['cat_varsta'][$i]."',
							`sex` = '".$_POST['sex'][$i]."',
							`traseu_bicicleta` = '".$_POST['traseu_bicicleta'][$i]."',	
							`traseu_alergare` = '".$_POST['traseu_alergare'][$i]."',
							`insotitor` = '".$_POST['insotitor'][$i]."',								
							`pachet_vip` = '".$_POST['pachet_vip'][$i]."',
							`tricou` = '".$_POST['tricou'][$i]."',
							`print_vip` = '".$_POST['print_vip'][$i]."',
							`locul` = '".$_POST['locul'][$i]."',
							`puncte` = '".$_POST['puncte'][$i]."',
							`timp_bicicleta` = '".$_POST['timp_bicicleta'][$i]."',
							`timp_alergare` = '".$_POST['timp_alergare'][$i]."',
							`timp` = '".$_POST['timp'][$i]."',
							`dificultate` = '".$_POST['dificultate'][$i]."',
							`numar_concurs` = '".$_POST['numar'][$i]."', 							
							`taxa` = '".$_POST['taxa'][$i]."'
							  WHERE `id` = {$_POST['id'][$i]}";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$update = 	$db->execute($query1);	
							
							}
						
						$pagina = $_SERVER['HTTP_REFERER'];
						if(isset($update)){
						
						header("Location: ".$pagina."?tbl_name=".$_POST['modifica_competitie_tabel']."&coloana=".$_POST['coloana']."&pagina=".$_POST['pagina'].'&flag='.$_POST['flag']);
						
						}	
						else 
						{
						$mesaj = "Tabelul nu conține nicio informație";
						$pagina = $_SERVER['HTTP_REFERER'];
						header("Location: $pagina&err=".$mesaj.'&flag='.$_POST['flag']);	
						}
						
						}
						
						
						
						
						
						
						
					
					// 11 reset baza de date pentru o noua campanie de newsletter
					if(isset($_GET['bd'])) {
						
						$tabel = $_GET['bd'];
						$query = "UPDATE `".$tabel."` SET `news_activ` = 1 WHERE `news_activ` = 0";
						$result = $db->execute($query);
						if($result){
							header("Location: editeaza_newsletter.php");
							exit();
							}
						}
						
					// 12 reset fortat baza de date
					if(isset($_GET['rst_fortat'])){
						
						$tabel = $_GET['rst_fortat'];
						$query = "UPDATE `".$tabel."` SET `news_activ` = 1 WHERE `news_activ` = 0";
						$result = $db->execute($query);
						if($result){
							header("Location: editeaza_newsletter.php");
							exit();
							}
						
						
						}
						
						

					// 13 adauga participant competitie Bikefest
					if(isset($_POST['tabel_competitie'])){
						
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$cod_unic = $_POST['cod_unic'];
							$query = "SELECT `cod_unic_participant` FROM `".$_POST['tabel_competitie']."` WHERE `cod_unic_participant` = '".$cod_unic."'";
							$rezultat = $db->execute($query);
							$num_rows = $db->getCount($rezultat);
								if($num_rows == 0){
									
									$data['cod_unic_participant'] = $_POST['cod_unic'];
									}
									else
									{
									$eroare[] = "Un participant cu acest cod există în competiție";	
									}
							}
							else
							{
							$data['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$data['numele'] = $_POST['numele'];
							}
							else
							{
								
							$eroare[] = "Nu ai completat numele";								
							}
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$data['prenumele'] = $_POST['prenumele'];
							}
							else
							{								
							$eroare[] = "Nu ai completat prenumele";							
							}
						if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$data['varsta'] = $_POST['varsta'];
							}
							else
							{
							$eroare[] = "Nu ai completat vârsta";					
							}	
						if(!$_POST['cat_varsta'] == ""){
							
							$data['cat_varsta'] = $_POST['cat_varsta'];
							}
							else
							{									
							$eroare[] = "Nu ai completat categoria de varstă";						
							}
							
						if(!$_POST['sex'] == ""){
							
							$data['sex'] = $_POST['sex'];
							}
							else
							{
							$eroare[] = "Nu ai ales sexul";	
							}		
							
						if($_POST['traseu'] <> ""){
							
							$data['traseu'] = $_POST['traseu'];
							}
							else
							{
							$eroare[] = "Nu ai selectat traseul";	
							}
						if(isset($_POST['insotitor'])){
							
							$data['insotitor'] = "da";
							}
							else
							{
							$data['insotitor'] = "";	
							}
						if(isset($_POST['vip'])){
							
							$data['pachet_vip'] = "vip";
							}
							else
							{
							$data['pachet_vip'] = "";	
							}		
						if($data['pachet_vip'] == "vip"){
							
							if($_POST['tricou'] <> ""){
							
								$data['tricou'] = $_POST['tricou'];
								}
								else
								{
								$eroare[] = "Nu ai selectat mărimea tricoului pentru opțiunea vip";	
								}
							}
							else
							{
							$data['tricou'] = "";	
							}
						if((!empty($_POST['print_vip'])) && (!ctype_space($_POST['print_vip']))){
							$data['print_vip'] = $_POST['print_vip'];
							}
							else
							{
							$data['print_vip'] = "";	
							}
						// se proceseaza duatlon pentru cazul cand este setat flag-ul "Tabere cu Suflet"
						if((isset($_POST['flag'])) && ($_POST['flag'] == "Cupa Tabere cu Suflet")){
						if(isset($_POST['duatlon']) == "duatlon"){
							$data['duatlon'] = "duatlon";
							}
							else
							{
							$data['duatlon'] = "";	
							}
							}
						if((!empty($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$data['numar_concurs'] = $_POST['numar'];
							}
							else
							{									
							$data['numar_concurs'] = "";		 
							}
							
						if((!empty($_POST['email'])) && (!ctype_space($_POST['email'])) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
							
							$data['email'] = $_POST['email'];
							}
							else
							{									
							$eroare[] = "Adresa de email invalidă";		 
							}  
							
						if(isset($_POST['flag']) && ($_POST['flag'] == "Cupa 1 Iunie")){
							
							// nu se proceseaza plata taxei pentru Cupa 1 Iunie
							}	
							else
							{
								if((!empty($_POST['taxa'])) && (($_POST['taxa'] <> ""))){
							
									$data['taxa'] = $_POST['taxa'];
									}
								else
								{
									$eroare[] = "Nu ai specificat starea taxei";	
								}
							
							}
							
						if(!$eroare){		
							if(is_array($data)){
								
							$tabel = $_POST['tabel_competitie'];
							$campuri = "";
							$valori = "";
							foreach($data as $camp=>$valoare){
								$campuri .= $camp . ", ";
								$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'". $valoare ."', ";						
								
								} // sfarsit foreach
							$campuri = substr($campuri,0,-2);
							$valori = substr($valori,0,-2);
							$select = "SET NAMES 'utf8'";
							$db->execute($select);	
						    $query = "INSERT INTO `". $tabel ."` (". $campuri .") VALUES (". $valori .")";
							$rezultat = $db->execute($query);
							
							if($rezultat){
								
								$mesaj = "Un participant a fost adăugat cu succes în competiție";
								$mesaj = base64_encode($mesaj);
								
								// se redirecteaza mesajul catre pagina de adaugare participant cu flagul "Tabere cu Suflet" pentru a 									putea incarca tabelul specific competitiei
								if(isset($_POST['flag'])){
									
									
								header('Location: detalii_tabel_competitie_bikefest.php?succes='.$mesaj.'&tabelid='.$_POST['tabel_competitie']."&flag=".$_POST['flag']);
								exit();	
								
								}
								else
								{
									
								header('Location: detalii_tabel_competitie_bikefest.php?succes='.$mesaj.'&tabelid='.$_POST['tabel_competitie']);
								exit();	
								}
								
								
							} // sfarsit rezultat
								
							
							} // sfarsit is_array
							
							} // sfarsit !$eroare
							
							} // sfarsit isset POST['tabel_competitie']
						
							if(isset($eroare)){
								
								$error = base64_encode(serialize($eroare));
								
								// se redirecteaza erorile catre pagina de adaugare participant cu flagul "Tabere cu Suflet" pentru a 									putea incarca tabelul specific competitiei
								if(isset($_POST['flag'])){									
												
								header('Location: adauga_participant_competitie.php?mesaj='.$error.'&tbl_name='.$_POST['tabel_competitie']."&flag=".$_POST['flag']);	
								exit();	
									}
									else
									{
									//$tabere = "";
								header('Location: adauga_participant_competitie.php?mesaj='.$error.'&tbl_name='.$_POST['tabel_competitie']);	
								exit();			
									}		
								
								}
						
						
						
						// 13' adauga participant competitie maraton
						
						if(isset($_POST['tabel_competitie_maraton'])){
						
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$cod_unic = $_POST['cod_unic'];
							$query = "SELECT `cod_unic_participant` FROM `".$_POST['tabel_competitie_maraton']."` WHERE `cod_unic_participant` = '".$cod_unic."'";
							$rezultat = $db->execute($query);
							$num_rows = $db->getCount($rezultat);
								if($num_rows == 0){
									
									$data['cod_unic_participant'] = $_POST['cod_unic'];
									}
									else
									{
									$erori[] = "Un participant cu acest cod există în competiție";	
									}
							}
							else
							{
							$data['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$data['numele'] = $_POST['numele'];
							}
							else
							{
								
							$erori[] = "Nu ai completat numele";								
							}
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$data['prenumele'] = $_POST['prenumele'];
							}
							else
							{								
							$erori[] = "Nu ai completat prenumele";							
							}
						if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$data['varsta'] = $_POST['varsta'];
							}
							else
							{
							$erori[] = "Nu ai completat vârsta";					
							}	
						if(!$_POST['cat_varsta'] == ""){
							
							$data['cat_varsta'] = $_POST['cat_varsta'];
							}
							else
							{									
							$erori[] = "Nu ai completat categoria de varstă";						
							}
							
						if(!$_POST['sex'] == ""){
							
							$data['sex'] = $_POST['sex'];
							}
							else
							{
							$erori[] = "Nu ai ales sexul";	
							}		
							
						if($_POST['traseu'] <> ""){
							
							$data['traseu'] = $_POST['traseu'];
							}
							else
							{
							$erori[] = "Nu ai ales traseul";	
							}
						
						if(isset($_POST['vip'])){
							
							$data['pachet_vip'] = "vip";
							}
							else
							{
							$data['pachet_vip'] = "";	
							}		
						if($data['pachet_vip'] == "vip"){
							
							if($_POST['tricou'] <> ""){
							
								$data['tricou'] = $_POST['tricou'];
								}
								else
								{
								$erori[] = "Nu ai ales măsura tricoului pentru opțiunea vip";	
								}
							}
							else
							{
							$data['tricou'] = "";	
							}
						if((!empty($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$data['numar_concurs'] = $_POST['numar'];
							}
							else
							{
							$data['numar_concurs'] = "";
							}
						if((!empty($_POST['email'])) && (!ctype_space($_POST['email'])) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
							
							$data['email'] = $_POST['email'];
							}
							else
							{									
							$erori[] = "Adresa de email invalidă";		 
							}  	
							
						if((!empty($_POST['taxa'])) && (($_POST['taxa'] <> ""))){
							
							$data['taxa'] = $_POST['taxa'];
							}
							else
							{
							$data['taxa'] = "";	
							}
							
						if(!$erori){		
							if(is_array($data)){
								
							$tabel = $_POST['tabel_competitie_maraton'];
							$campuri = "";
							$valori = "";
							foreach($data as $camp=>$valoare){
								$campuri .= $camp . ", ";
								$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'". $valoare ."', ";						
								
								} // sfarsit foreach
							$campuri = substr($campuri,0,-2);
							$valori = substr($valori,0,-2);
							$select = "SET NAMES 'utf8'";
							$db->execute($select);	
						    $query = "INSERT INTO `". $tabel ."` (". $campuri .") VALUES (". $valori .")";
							$rezultat = $db->execute($query);
							
							if($rezultat){
								
								$mesaj = "Un participant a fost adăugat cu succes în competiție";
								$mesaj = base64_encode($mesaj);
								header('Location: detalii_tabel_competitie_maraton.php?succes='.$mesaj.'&tabelid='.$_POST['tabel_competitie_maraton']."&flag=".$_POST['flag']);
								exit();
								
								} // sfarsit rezultat
								
							
							} // sfarsit is_array
							
							} // sfarsit !$eroare
							
							} // sfarsit isset POST['tabel_competitie_maraton']
						
							if(isset($erori)){
								
								$error = base64_encode(serialize($erori));
								
																
				header('Location: adauga_participant_competitie_maraton.php?mesaj='.$error.'&tbl_name='.$_POST['tabel_competitie_maraton']."&flag=".$_POST['flag']);	
							exit();			
								}
								
						
						
						// 13'' adauga participant competitie sinaia
						
						if(isset($_POST['tabel_competitie_sinaia'])){
						
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$cod_unic = $_POST['cod_unic'];
							$query = "SELECT `cod_unic_participant` FROM `".$_POST['tabel_competitie_sinaia']."` WHERE `cod_unic_participant` = '".$cod_unic."'";
							$rezultat = $db->execute($query);
							$num_rows = $db->getCount($rezultat);
								if($num_rows == 0){
									
									$data['cod_unic_participant'] = $_POST['cod_unic'];
									}
									else
									{
									$error[] = "Un participant cu acest cod există în competiție";	
									}
							}
							else
							{
							$data['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$data['numele'] = $_POST['numele'];
							}
							else
							{
								
							$error[] = "Nu ai completat numele";								
							}
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$data['prenumele'] = $_POST['prenumele'];
							}
							else
							{								
							$error[] = "Nu ai completat prenumele";							
							}
						if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$data['varsta'] = $_POST['varsta'];
							}
							else
							{
							$error[] = "Nu ai completat vârsta";					
							}	
						if(!$_POST['cat_varsta'] == ""){
							
							$data['cat_varsta'] = $_POST['cat_varsta'];
							}
							else
							{									
							$error[] = "Nu ai completat categoria de varstă";						
							}
							
						if(!$_POST['sex'] == ""){
							
							$data['sex'] = $_POST['sex'];
							}
							else
							{
							$error[] = "Nu ai ales sexul";	
							}		
							
						if($_POST['traseu'] <> ""){
							
							$data['traseu'] = $_POST['traseu'];
							}
							else
							{
							$error[] = "Nu ai ales traseul";	
							}
						
						if(isset($_POST['vip'])){
							
							$data['pachet_vip'] = "vip";
							}
							else
							{
							$data['pachet_vip'] = "";	
							}		
						if($data['pachet_vip'] == "vip"){
							
							if($_POST['tricou'] <> ""){
							
								$data['tricou'] = $_POST['tricou'];
								}
								else
								{
								$error[] = "Nu ai ales măsura tricoului pentru opțiunea vip";	
								}
							}
							else
							{
							$data['tricou'] = "";	
							}
						if((!empty($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$data['numar_concurs'] = $_POST['numar'];
							}
							else
							{
							$data['numar_concurs'] = "";
							}
						if((!empty($_POST['email'])) && (!ctype_space($_POST['email'])) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
							
							$data['email'] = $_POST['email'];
							}
							else
							{									
							$error[] = "Adresa de email invalidă";		 
							}  	
							
						if((!empty($_POST['taxa'])) && (($_POST['taxa'] <> ""))){
							
							$data['taxa'] = $_POST['taxa'];
							}
							else
							{
							$data['taxa'] = "";	
							}
							
						if(!$error){		
							if(is_array($data)){
								
							$tabel = $_POST['tabel_competitie_sinaia'];
							$campuri = "";
							$valori = "";
							foreach($data as $camp=>$valoare){
								$campuri .= $camp . ", ";
								$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'". $valoare ."', ";						
								
								} // sfarsit foreach
							$campuri = substr($campuri,0,-2);
							$valori = substr($valori,0,-2);
							$select = "SET NAMES 'utf8'";
							$db->execute($select);	
						    $query = "INSERT INTO `". $tabel ."` (". $campuri .") VALUES (". $valori .")";
							$rezultat = $db->execute($query);
							
							if($rezultat){
								
								$mesaj = "Un participant a fost adăugat cu succes în competiție";
								$mesaj = base64_encode($mesaj);
								header('Location: detalii_tabel_competitie_sinaia.php?succes='.$mesaj.'&tabelid='.$_POST['tabel_competitie_sinaia'].'&flag='.$_POST['flag']);
								exit();
								
								} // sfarsit rezultat
								
							
							} // sfarsit is_array
							
							} // sfarsit !$eroare
							
							} // sfarsit isset POST['tabel_competitie_maraton']
						
							if(isset($error)){
								
								$errors = base64_encode(serialize($error));
								
																
				header('Location: adauga_participant_competitie_sinaia.php?mesaj='.$errors.'&tbl_name='.$_POST['tabel_competitie_sinaia'].'&flag='.$_POST['flag']);	
							exit();			
								}
					
						
						
						// 13''' adauga participant competitie freeride
						
						if(isset($_POST['tabel_competitie_freeride'])){
						
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$cod_unic = $_POST['cod_unic'];
							$query = "SELECT `cod_unic_participant` FROM `".$_POST['tabel_competitie_freeride']."` WHERE `cod_unic_participant` = '".$cod_unic."'";
							$rezultat = $db->execute($query);
							$num_rows = $db->getCount($rezultat);
								if($num_rows == 0){
									
									$data['cod_unic_participant'] = $_POST['cod_unic'];
									}
									else
									{
									$not[] = "Un participant cu acest cod există în competiție";	
									}
							}
							else
							{
							$data['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$data['numele'] = $_POST['numele'];
							}
							else
							{
								
							$not[] = "Nu ai completat numele";								
							}
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$data['prenumele'] = $_POST['prenumele'];
							}
							else
							{								
							$not[] = "Nu ai completat prenumele";							
							}
						if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$data['varsta'] = $_POST['varsta'];
							}
							else
							{
							$not[] = "Nu ai completat vârsta";					
							}	
						if(!$_POST['cat_varsta'] == ""){
							
							$data['cat_varsta'] = $_POST['cat_varsta'];
							}
							else
							{									
							$not[] = "Nu ai completat categoria de varstă";						
							}
							
						if(!$_POST['sex'] == ""){
							
							$data['sex'] = $_POST['sex'];
							}
							else
							{
							$not[] = "Nu ai ales sexul";	
							}		
							
						if(!$_POST['categoria'] == ""){
							
							$data['categoria'] = $_POST['categoria'];
							}
							else
							{
							$not[] = "Nu ai ales categoria concurs";	
							}
						if((isset($_POST['experienta'])) && ($_POST['experienta'] <> "")){
							
							$data['experienta'] = $_POST['experienta'];
							}
							else
							{
							$not[] = "Nu ai selectat experiența freeride";	
							}
						if((!empty($_POST['adresa'])) && (!ctype_space($_POST['adresa']))){
							
							$data['adresa'] = $_POST['adresa'];
							}
							else
							{
							$not[] = "Nu ai completat datele persoanei de contact";	
							}
						
						if(isset($_POST['vip'])){
							
							$data['pachet_vip'] = "vip";
							}
							else
							{
							$data['pachet_vip'] = "";	
							}		
						if($data['pachet_vip'] == "vip"){
							
							if($_POST['tricou'] <> ""){
							
								$data['tricou'] = $_POST['tricou'];
								}
								else
								{
								$not[] = "Nu ai ales măsura tricoului pentru opțiunea vip";	
								}
							}
							else
							{
							$data['tricou'] = "";	
							}
						if((!empty($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$data['numar_concurs'] = $_POST['numar'];
							}
							else
							{
							$data['numar_concurs'] = "";
							}
						if((!empty($_POST['email'])) && (!ctype_space($_POST['email'])) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
							
							$data['email'] = $_POST['email'];
							}
							else
							{									
							$not[] = "Adresa de email invalidă";		 
							}  	
							
						if((!empty($_POST['taxa'])) && (($_POST['taxa'] <> ""))){
							
							$data['taxa'] = $_POST['taxa'];
							}
							else
							{
							$data['taxa'] = "";	
							}
							
						if(!$not){		
							if(is_array($data)){
								
							$tabel = $_POST['tabel_competitie_freeride'];
							$campuri = "";
							$valori = "";
							foreach($data as $camp=>$valoare){
								$campuri .= $camp . ", ";
								$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'". $valoare ."', ";						
								
								} // sfarsit foreach
							$campuri = substr($campuri,0,-2);
							$valori = substr($valori,0,-2);
							$select = "SET NAMES 'utf8'";
							$db->execute($select);	
						    $query = "INSERT INTO `". $tabel ."` (". $campuri .") VALUES (". $valori .")";
							$rezultat = $db->execute($query);
							
							if($rezultat){
								
								$mesaj = "Un participant a fost adăugat cu succes în competiție";
								$mesaj = base64_encode($mesaj);
								header('Location: detalii_tabel_competitie_freeride.php?succes='.$mesaj.'&tabelid='.$_POST['tabel_competitie_freeride'].'&flag='.$_POST['flag']);
								exit();
								
								} // sfarsit rezultat
								
							
							} // sfarsit is_array
							
							} // sfarsit !$eroare
							
							} // sfarsit isset POST['tabel_competitie_freeride']
						
							if(isset($not)){
								
								$errors = base64_encode(serialize($not));
								
																
				header('Location: adauga_participant_competitie_freeride.php?mesaj='.$errors.'&tbl_name='.$_POST['tabel_competitie_freeride'].'&flag='.$_POST['flag']);	
							exit();			
								}
					
						
						
						// 13'''' adauga participant competitie duatlon
						
						if(isset($_POST['tabel_competitie_duatlon'])){
						
						if((!empty($_POST['cod_unic'])) && (!ctype_space($_POST['cod_unic']))){
							
							$cod_unic = $_POST['cod_unic'];
							$query = "SELECT `cod_unic_participant` FROM `".$_POST['tabel_competitie_duatlon']."` WHERE `cod_unic_participant` = '".$cod_unic."'";
							$rezultat = $db->execute($query);
							$num_rows = $db->getCount($rezultat);
								if($num_rows == 0){
									
									$data['cod_unic_participant'] = $_POST['cod_unic'];
									}
									else
									{
									$notk[] = "Un participant cu acest cod există în competiție";	
									}
							}
							else
							{
							$data['cod_unic_participant'] = "";	
							}
						if((!empty($_POST['numele'])) && (!ctype_space($_POST['numele']))){
							
							$data['numele'] = $_POST['numele'];
							}
							else
							{
								
							$notk[] = "Nu ai completat numele";								
							}
						if((!empty($_POST['prenumele'])) && (!ctype_space($_POST['prenumele']))){
							
							$data['prenumele'] = $_POST['prenumele'];
							}
							else
							{								
							$notk[] = "Nu ai completat prenumele";							
							}
						if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
							$data['varsta'] = $_POST['varsta'];
							}
							else
							{
							$notk[] = "Nu ai completat vârsta";					
							}	
						if(!$_POST['cat_varsta'] == ""){
							
							$data['cat_varsta'] = $_POST['cat_varsta'];
							}
							else
							{									
							$notk[] = "Nu ai completat categoria de varstă";						
							}
							
						if(!$_POST['sex'] == ""){
							
							$data['sex'] = $_POST['sex'];
							}
							else
							{
							$notk[] = "Nu ai ales sexul";	
							}		
							
						if($_POST['traseu_alergare'] <> ""){
							
							$data['traseu_alergare'] = $_POST['traseu_alergare'];
							}
							else
							{
							$notk[] = "Nu ai ales traseul pentru alergare";	
							}
							
						if($_POST['traseu_bicicleta'] <> ""){
							
							$data['traseu_bicicleta'] = $_POST['traseu_bicicleta'];
							}
							else
							{
							$notk[] = "Nu ai ales traseul pentru bicicletă";	
							}
						if(isset($_POST['insotitor'])){
							
							$data['insotitor'] = "da";
							}
							else
							{
							$data['insotitor'] = "";	
							}
						
						if(isset($_POST['vip'])){
							
							$data['pachet_vip'] = "vip";
							}
							else
							{
							$data['pachet_vip'] = "";	
							}		
						if($data['pachet_vip'] == "vip"){
							
							if($_POST['tricou'] <> ""){
							
								$data['tricou'] = $_POST['tricou'];
								}
								else
								{
								$notk[] = "Nu ai ales măsura tricoului pentru opțiunea vip";	
								}
							}
							else
							{
							$data['tricou'] = "";	
							}
						if((!empty($_POST['numar'])) && (!ctype_space($_POST['numar']))){
							
							$data['numar_concurs'] = $_POST['numar'];
							}
							else
							{
							$data['numar_concurs'] = "";
							}
						if((!empty($_POST['email'])) && (!ctype_space($_POST['email'])) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
							
							$data['email'] = $_POST['email'];
							}
							else
							{									
							$notk[] = "Adresa de email invalidă";		 
							}  	
							
						if((!empty($_POST['taxa'])) && (($_POST['taxa'] <> ""))){
							
							$data['taxa'] = $_POST['taxa'];
							}
							else
							{
							$data['taxa'] = "";	
							}
							
						if(!$notk){		
							if(is_array($data)){
								
							$tabel = $_POST['tabel_competitie_duatlon'];
							$campuri = "";
							$valori = "";
							foreach($data as $camp=>$valoare){
								$campuri .= $camp . ", ";
								$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'". $valoare ."', ";						
								
								} // sfarsit foreach
							$campuri = substr($campuri,0,-2);
							$valori = substr($valori,0,-2);
							$select = "SET NAMES 'utf8'";
							$db->execute($select);	
						    $query = "INSERT INTO `". $tabel ."` (". $campuri .") VALUES (". $valori .")";
							$rezultat = $db->execute($query);
							
							if($rezultat){
								
								$mesaj = "Un participant a fost adăugat cu succes în competiție";
								$mesaj = base64_encode($mesaj);
								header('Location: detalii_tabel_competitie_duatlon.php?succes='.$mesaj.'&tabelid='.$_POST['tabel_competitie_duatlon'].'&flag='.$_POST['flag']);
								exit();
								
								} // sfarsit rezultat
								
							
							} // sfarsit is_array
							
							} // sfarsit !$eroare
							
							} // sfarsit isset POST['tabel_competitie_maraton']
						
							if(isset($notk)){
								
								$error = base64_encode(serialize($notk));
								
																
				header('Location: adauga_participant_competitie_duatlon.php?mesaj='.$error.'&tbl_name='.$_POST['tabel_competitie_duatlon'].'&flag='.$_POST['flag']);	
							exit();			
								}
								
						
						
						
					
					
						// 14 update stare tabel competitie
						
						if(isset($_POST['schimba_starea'])){
							
			     		$query = "UPDATE `tabel_competitii` SET `activ` = '".$_POST['number']."' WHERE `id` = {$_POST['id_tabel']}";
						
						
						if($_POST['number'] == 1){
							$result = $db->execute($query);	
							
							// se updateaza la inactiv competitiile din tabelul nume_competitii pentru 
							//a nu mai fi afisate in pagina inscriere_competitie
			
						$query = "UPDATE `nume_competitii` LEFT JOIN `tabel_competitii` 
						ON `tabel_competitii`.flag = `nume_competitii`.nume_competitie 
						SET `nume_competitii`.competitii_activ = 1 WHERE `tabel_competitii`.activ = 1 
						AND `tabel_competitii`.flag = `nume_competitii`.nume_competitie";
						$result = $db->execute($query);	
								 
							header('Location: tabele_competitii.php?mesaj=Competiția este activă&tip=1');
							exit();
							 }
						if($_POST['number'] == 0){
							$result = $db->execute($query);	
							
							// se updateaza la inactiv competitiile din tabelul nume_competitii pentru 
							//a nu mai fi afisate in pagina inscriere_competitie
			
						$query = "UPDATE `nume_competitii` LEFT JOIN `tabel_competitii` 
						ON `tabel_competitii`.flag = `nume_competitii`.nume_competitie 
						SET `nume_competitii`.competitii_activ = 0 WHERE `tabel_competitii`.activ = 0 
						AND `tabel_competitii`.flag = `nume_competitii`.nume_competitie";
						$result = $db->execute($query);			 
							header('Location: tabele_competitii.php?mesaj=Competiția nu este activă&tip=0');
							exit();
							 }	 
						if(($_POST['number'] <> 0) || ($_POST['number'] <> 1)){
							
							header('Location: tabele_competitii.php?mesaj=Sunt acceptate doar valori de 0 sau 1&tip=0');
							exit();
							 }
						
							
							}
							
							
					// 14' update data_competitiei din tabel competitii	
					if(isset($_POST['dataCompetitiei'])){
							
			     		$query = "UPDATE `tabel_competitii` SET `data_competitiei` = '".$_POST['data_competitiei']."' WHERE `id` = {$_POST['id_tabel']}";						
												
						$result = $db->execute($query);							
						if($result){		 
							header('Location: tabele_competitii.php?mesaj=Data competiției a fost modificată&tip=1');
							exit();
						}
					}
						
							
					// 14'' update data_competitiei din tabel competitii	
					if(isset($_POST['limita_personalizare'])){
							
			     		$query = "UPDATE `tabel_competitii` SET `dataLimitaPersonalizare` = '".$_POST['dataLimitaPersonalizare']."' WHERE `id` = {$_POST['id_tabel']}";						
												
						$result = $db->execute($query);							
						if($result){		 
						header('Location: tabele_competitii.php?mesaj=Data limită pentru personalizarea numărului a fost modificată&tip=1');
							exit();
						}
					}
									
						 
							 
					// 15 update varsta si categoria_varsta din baza de date
					
					if (isset($_GET['updateCatVarst'])){
						include("php/functii.php");
							$date = date("Y");
							
							$select = "SELECT * FROM `inscrisi_teamexpert` WHERE 1";
							$result = $db->execute($select);
													
							if($result){								
								
								
								$id = "";
								$nume = '';
								$prenume ='';
								while($row = $db->getObject($result)){
									
									$id[] = $row['id'];
									$nume[] = $row['nume'];
									$prenume[] = $row['prenume'];
									}								
								
								foreach($id as $number){
									
									$idcol = $number;									
									
									
									$select = "SELECT * FROM `inscrisi_teamexpert` WHERE `id` = $idcol";
									$result = $db->execute($select);
									
									$row = mysqli_fetch_assoc($result);
									
									$data_nasterii = $row['data_nasterii'];
									$varsta = $row['varsta'];
									$categorie_varsta = $row['categorie_varsta'];
									
									// se recalculeaza varsta
									$an = str_replace("-","",$data_nasterii);
									$an = substr($an,0,4);
									$varsta = ($date-$an);
									
									// se reintegreaza in categoria de varsta
									$categorie = categorie($varsta);
									
									// se procedeaza la update
									$query = "UPDATE `inscrisi_teamexpert` SET `varsta` = '".$varsta."', `categorie_varsta` = '".$categorie."' WHERE id = $idcol";
									
									$result = $db->execute($query);	
									
										
									}				
									
									
									
								$mesaj = "Baza de date a fost actualizată cu succes";	
								$mesaj = base64_encode($mesaj);									
								header("Location: baza_date.php?mesaj=".$mesaj);
								}
							}
							
								 
						// 16 update stare competitii din tabelul tip_competitie pagina "administrare tabele"
							
							if (isset($_POST['id_tip_competitie'])){
								$query = "UPDATE `tip_competitie` SET `activ` = '".$_POST['activ']."' WHERE `id` = {$_POST['id_tip_competitie']}";
								
						
								if($_POST['activ'] == 1){
								
								$result = $db->execute($query);	
							
								header('Location: administrare_tabele.php?text_tip=Competiția este activă');
								exit();
							 	}
								if($_POST['activ'] == 0){
								$result = $db->execute($query);	
								 
								header('Location: administrare_tabele.php?text_tip=Competiția nu este activă');
								exit();
								 }								 
								  
								if(($_POST['activ'] <> 0) || ($_POST['activ'] <> 1)){
							
								header('Location: administrare_tabele.php?text_tip=Sunt acceptate doar valori de 0 sau 1');
								exit();
								 }	 
								
								}
								
						
								
								
						// 17 update stare competitii din tabelul nume_competitii pagina "administrare tabele"
							
							if (isset($_POST['id_nume_competitie'])){
								$query = "UPDATE `nume_competitii` SET `competitii_activ` = '".$_POST['activ']."' WHERE `id` = {$_POST['id_nume_competitie']}";
								
						
								if($_POST['activ'] == 1){
								
								$result = $db->execute($query);	
							
								header('Location: administrare_tabele.php?text_nume=Competiția este activă');
								exit();
							 	}
								if($_POST['activ'] == 0){
								$result = $db->execute($query);	
								 
								header('Location: administrare_tabele.php?text_nume=Competiția nu este activă');
								exit();
								 }								 
								  
								if(($_POST['activ'] <> 0) || ($_POST['activ'] <> 1)){
							
								header('Location: administrare_tabele.php?text_nume=Sunt acceptate doar valori de 0 sau 1');
								exit();
								 }	 
								
								}
								
								
						// 18 update continut mail de confirmare
						
						if(isset($_POST['update_email_ok'])){
							$id = $_POST['update_email_ok'];
							$email = base64_encode(stripslashes($_POST['mail']));
							$query = "UPDATE `mesaje_email` SET `continut_email` = '$email' WHERE `id` = {$id}";
							// ne asiguram ca diacriticile sunt salvate
							$insert = "SET NAMES 'utf8'";
							$db->execute($insert);
							$result = $db->execute($query);
							
							if($result){
								$succes = "Mesajul a fost modificat cu succes";
								header("Location: modifica_mail.php?id=".$id."&mesaj=".$succes);
								exit();
							}
							else
							{
								$error = "Mesajul nu a putut fi modificat";
								//header("Location: modifica_mail.php?id=".$id."&mesaj=".$error);
								exit();
							}
						}
						
						
						// 19 sterge tabel `clasament general`
						if(isset($_POST['deleteGeneral'])){
							
							$numeTabel = $_POST['numeTabel'];
							$idTabel = $_POST['idTabel'];
							
							 $checkTabel = "SHOW TABLES LIKE '".$numeTabel."'";	
								if($db->execute($checkTabel)){
								
								$delete = "DROP TABLE IF EXISTS `".$numeTabel."` ";
								$result = $db->execute($delete);
									if($result){
										
										$delete = "DELETE  FROM `clasament_general` WHERE `id` = {$idTabel}";
										$result = $db->execute($delete);
											if($result){
											$succes = base64_encode("Tabelul a fost șters cu succes");
											header('Location: clasament_general.php?mesaj='.$succes.'&ok=1');	
											}
									}
									else
									{
									$error = base64_encode("Tabelul nu există");
									header('Location: clasament_general.php?mesaj='.$error.'ok=0');	
									}
								}
						}
					
					
						// 20 update date concurent clasament final
					
						if(isset($_POST['modifica_date_clasament'])){
							
							$numeTabel = $_POST['numeTabel'];
							$idConcurent = $_POST['idConcurent'];
							
							if(!empty($_POST['id'])){
								$date['id'] = $_POST['id'];
								
								}
							if(!empty($_POST['cod_unic'])){
								$date['cod_unic'] = $_POST['cod_unic'];
								
								}
							if(!empty($_POST['numele']) && (!ctype_space($_POST['numele']))){
								$date['numele'] = $_POST['numele'];								
								}
								else
								{
								$no[] = "Numele nu este completat";	
								}
							if(!empty($_POST['prenumele']) && (!ctype_space($_POST['prenumele']))){
								$date['prenumele'] = $_POST['prenumele'];								
								}
								else
								{
								$no[] = "Prenumele nu este completat";	
								}
							if(!empty($_POST['cat_varsta'])){
								$date['categoria'] = $_POST['cat_varsta'];								
								}
								else
								{
								$no[] = "Categoria de vârstă nu este completată";	
								}
							if(!empty($_POST['sex'])){
								$date['sex'] = $_POST['sex'];								
								}
								else
								{
								$no[] = "Completează sexul";	
								}
							if(!empty($_POST['locul']) && (is_numeric($_POST['locul']) !== 0)){
								$date['locul'] = $_POST['locul'];								
								}
								else
								{
								$no[] = "Locul nu poate fi nul";	
								}
							if(!empty($_POST['rezultat']) && (is_numeric($_POST['rezultat']) !== 0)){
								$date['rezultat'] = $_POST['rezultat'];								
								}
								else
								{
								$no[] = "Punctajul nu poate fi nul";	
								}
							if(!empty($_POST['competitii']) && (!ctype_space($_POST['competitii']))){
								$date['competitii'] = $_POST['competitii'];								
								}
								else
								{
								$no[] = "Competitiile nu sunt completate";	
								}
							 if(!empty($_POST['participari']) && (is_numeric($_POST['participari']))){
								$date['participari'] = $_POST['participari'];								
								}
								else
								{
								$no[] = "Eroare număr participări";	
								}
								
								if(!isset($no)){
									
									if(is_array($date)){
									
									$query = "UPDATE `".$numeTabel."` SET ";
									
									foreach($date as $campuri=>$valori){
										
									$query .= $campuri. " = ";	
									$query .= (is_numeric($valori))? $valori. ", ": "'".$valori."', ";	
										
									}
									$query = substr($query,0,-2);	
									
									$query .= " WHERE `id` = {$idConcurent}";
									$insert = "SET NAMES 'utf8'";
									$db->execute($insert);
																		
									$result = $db->execute($query);								
										
										if($result){
											
											$mesaj = base64_encode('Un participant a fost updatat cu succes');
											header('Location: detalii_clasament_general.php?tabel='.$numeTabel.'&succes='.$mesaj.'&gen='.$_POST['gen']);
										}
									}
								}
								
							}		
								
		         if(isset($no)){
	
								$no = base64_encode(serialize($no));
								header('Location: modifica_date_concurent_clasament.php?id='.$idConcurent.'&tabel='.$numeTabel.'&eroare='.$no.'&gen='.$_POST['gen']);
							 }
							 
							 
							 
							 
							 
							 // 21 Update tabel Clasament General
							 
							 if(isset($_POST['modifica_clasament_general'])){
								 
								 if(!isset($_POST['sex'])){
								 
							$query = "SELECT * FROM `".$_POST['modifica_clasament_tabel']."` WHERE 1 LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";
								 }
								 else
								 {
									 
							echo $query = "SELECT * FROM `".$_POST['modifica_clasament_tabel']."` WHERE `sex` = '".$_POST['sex'][0]."' LIMIT {$_POST['start']}, {$_POST['afisari_pagina']}";		 
								 }
								 
								 
						$result = $db->execute($query);
						
						$count = $db->getCount($result);
						
						
						for($i=0; $i < $count; $i++ ){
							
			 			 $query = "UPDATE `".$_POST['modifica_clasament_tabel']."` SET 
							`id` = '".$_POST['id'][$i]."',
							`cod_unic` = '".$_POST['cod_unic'][$i]."',
							`numele` = '".$_POST['numele'][$i]."', 
							`prenumele` = '".$_POST['prenumele'][$i]."',							
							`sex` = '".$_POST['sex'][$i]."',
							`categoria` = '".$_POST['categoria'][$i]."',							
							`locul` = '".$_POST['locul'][$i]."',	
							`rezultat` = '".$_POST['rezultat'][$i]."',
							`competitii` = '".$_POST['competitii'][$i]."',								
							`participari` = '".$_POST['participari'][$i]."'							
							  WHERE `id` = {$_POST['id'][$i]}";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$update = 	$db->execute($query);	
							
							}
						
						$pagina = $_SERVER['HTTP_REFERER'];
						if(isset($update)){
						
						header("Location: $pagina");
						
						}	
						else 
						{
						$mesaj = "Tabelul nu conține nicio informație";
						$pagina = $_SERVER['HTTP_REFERER'];
						header("Location: $pagina&err=".$mesaj);	
						} 
								 
								 
							} 
							
					// 22 update tabel competitie cu informatii despre loc, timp, puncte obtinute
					
										
					if(isset($_POST['uploadTabel'])){						
						
						$numeTabel = $_POST['numeCompetitie'];					
						
						$flagTabel = $_POST['flagCompetitie'];
						
							// se ferifica daca fisierul a fost uploadat
						if ($_FILES['fisierUpload']['error'] > 0) {
								
							$retry = "Nu ai selectat niciun fișier pentru upload";	
  								
						} else {
							
								// se stabilec numele si tipul fisierului
 								$nume = $_FILES["fisierUpload"]["name"] ;
  								$tipFisier = $_FILES["fisierUpload"]["type"];
  								
  								$cale = "../salvari/";
  								move_uploaded_file($_FILES['fisierUpload']['tmp_name'], $cale.$_FILES['fisierUpload']['name']);
							
						
						
						}						
						
						// se verifica daca variabila retry este setata	
						if(!isset($retry)){
							
							// se verifica daca eztensia fisierului este suportata
							
							if($_FILES['fisierUpload']['type'] !== "application/vnd.ms-excel"){
								
							echo "<p style='color:red; font-size:18px; margin:auto; text-align:center'>Tipul fisierului nu este suportat!<br />";
				echo "<button style='color:black; font-size:14px; margin:auto; width:auto' onClick=\"history.go(-1)\">Mergi inapoi</button></p>";		
								
							}
							else
							{
							
							$select = "SHOW TABLES LIKE 'Tabel Update'";
							$result = $db->execute($select);
								
								if($db->getCount($result) == 0){
									
									switch($flagTabel){
										
									case "Comana Bike Fest":
									case "Cupa Tabere cu Suflet":
									case "Cupa Veseliei" :
									
									$create = "CREATE TABLE IF NOT EXISTS `Tabel Update`(
									`id` int(5) NOT NULL AUTO_INCREMENT,
									`cod_unic` char(10) NOT NULL,
									`nume` char(50) NOT NULL,
									`prenume` char(50) NOT NULL,
									`nrConcurs` int(5) NOT NULL,
									`loc` int(5) NOT NULL,
									`timp` text NOT NULL,
									`puncte` int(5) NOT NULL,
									`ture` tinyint(1) NOT NULL,
									PRIMARY KEY (`id`)
									)ENGINE=InnoDB DEFAULT CHARSET=utf8";
									
									break;
									
									
									case "Cupa 1 Iunie":									
									case "On Top of the World":
									case "Maraton Tabere cu Suflet":
									
									$create = "CREATE TABLE IF NOT EXISTS `Tabel Update`(
									`id` int(5) NOT NULL AUTO_INCREMENT,
									`cod_unic` char(10) NOT NULL,
									`nume` char(50) NOT NULL,
									`prenume` char(50) NOT NULL,
									`nrConcurs` int(5) NOT NULL,
									`loc` int(5) NOT NULL,
									`timp` text NOT NULL,
									`puncte` int(5) NOT NULL,
									PRIMARY KEY (`id`)
									)ENGINE=InnoDB DEFAULT CHARSET=utf8";
									
									break;
									
									case "Cupa Malinului":
									
									$create = "CREATE TABLE IF NOT EXISTS `Tabel Update`(
									`id` int(5) NOT NULL AUTO_INCREMENT,
									`cod_unic` char(10) NOT NULL,
									`nume` char(50) NOT NULL,
									`prenume` char(50) NOT NULL,
									`nrConcurs` int(5) NOT NULL,
									`loc` int(5) NOT NULL,
									`total` float NOT NULL,
									`puncte` int(5) NOT NULL,
									PRIMARY KEY (`id`)
									)ENGINE=InnoDB DEFAULT CHARSET=utf8";
									
									break;
									
									case "Duatlon Tabere cu Suflet":
									
									$create = "CREATE TABLE IF NOT EXISTS `Tabel Update`(
									`id` int(5) NOT NULL AUTO_INCREMENT,
									`cod_unic` char(10) NOT NULL,
									`nume` char(50) NOT NULL,
									`prenume` char(50) NOT NULL,									
									`loc` int(5) NOT NULL,
									`timp_bicicleta` text NOT NULL,
									`timp_alergare` text NOT NULL,
									`timp` text NOT NULL,
									`dificultate` text NOT NULL,
									PRIMARY KEY (`id`)
									)ENGINE=InnoDB DEFAULT CHARSET=utf8";
									
									break;
									
									default :
									break;
									}
									
									
									
											if($db->execute($create)){									
											
											
												$caleFisier = "../salvari/".$nume;
												
												// se verifica existenta fisierului
												if(!file_exists($caleFisier)){
													
												$delete = "DROP TABLE `Tabel Update`";
												$db->execute($delete);		
											echo "<p style='color:red; font-size:18px; margin:auto; text-align:center'>Fisierul nu exista. Urca fisierul pe server!<br />";
				echo "<button style='color:black; font-size:14px; margin:auto; width:auto' onClick=\"history.go(-1)\">Mergi inapoi</button></p>";	
												}
												else
												{
													
													
												
												// se introduc datele din fisierul csv in array
												$data = array();

												$fisier = fopen($caleFisier,'r');

													while(!feof($fisier)){
	
														array_push($data, fgetcsv($fisier));
													}
													
												fclose($fisier);
													
													// se transforma string-ul fiecarei linii de text in array 
													foreach($data as $sir){
	
														$element = explode(';',$sir[0]);
															
															// se formateaza fiecare element string sau int
															$info = "";
															foreach($element as $informatie){
		
																$info .= (!is_numeric($informatie))? "'".$informatie."' ," 
																: $informatie." ,";
															}	
															$info = substr($info,0,-2);
															
															
															switch($flagTabel){
																
																case "Comana Bike Fest":
																case "Cupa Tabere cu Suflet":
																case "Cupa Veseliei" :
																
														// se introduc datele in tabel
														$insert = "INSERT INTO `Tabel Update` 
														(`id`,`cod_unic`,`nume`,`prenume`,`nrConcurs`,`loc`,`timp`,`puncte`,`ture`)
														 VALUES	(".$info.")";
														$result = $db->execute($insert);
														
																break;
																
																
																case "Cupa 1 Iunie":																
																case "On Top of the World":
																case "Maraton Tabere cu Suflet":
																
														// se introduc datele in tabel
														$insert = "INSERT INTO `Tabel Update` 
														(`id`,`cod_unic`,`nume`,`prenume`,`nrConcurs`,`loc`,`timp`,`puncte`)
														 VALUES	(".$info.")";
														$result = $db->execute($insert);
																break;
																
																case "Cupa Malinului":
																
														// se introduc datele in tabel
														$insert = "INSERT INTO `Tabel Update` 
														(`id`,`cod_unic`,`nume`,`prenume`,`nrConcurs`,`loc`,`total`,`puncte`)
														 VALUES	(".$info.")";
														$result = $db->execute($insert);
																break;
																
																case "Duatlon Tabere cu Suflet":
																
														// se introduc datele in tabel
														$insert = "INSERT INTO `Tabel Update` 
														(`id`,`cod_unic`,`nume`,`prenume`,`loc`,`timp_bicicleta`, `timp_alergare`, `timp`, `dificultate`)
														 VALUES	(".$info.")";
														$result = $db->execute($insert);
																break;
																
																default:
																break;
																
														
															}
	
													}
											
											/*
											// codul functioneaza doar cu variabila local_infile activata
												
											$newLine = 'FIELDS TERMINATED BY \';\' ENCLOSED BY \'"\' LINES TERMINATED BY \'\n\'';
									        $import = "LOAD DATA INFILE 'C:/wamp/www/teamexpert/salvari/{$fisierUpload}' INTO TABLE `Tabel Update` {$newLine} ";
											*/
											
												$id = $db->execute("SELECT `id` FROM `Tabel Update` WHERE 1");
												if($db->getCount($id) > 0){
													
													switch($flagTabel){
														
														case "Comana Bike Fest":
														case "Cupa Tabere cu Suflet":
														case "Cupa Veseliei" :
														
												// se execute update pentru competitiile Comana BikeFest, Cupa Tabere cu Suflet	
												$update = "UPDATE `".$numeTabel."` LEFT JOIN `Tabel Update` ON `".$numeTabel."`.cod_unic_participant = `Tabel Update`.cod_unic SET `".$numeTabel."`.locul = `Tabel Update`.loc, `".$numeTabel."`.timp = `Tabel Update`.timp, `".$numeTabel."`.puncte = `Tabel Update`.puncte, `".$numeTabel."`.numar_concurs = `Tabel Update`.nrConcurs, `".$numeTabel."`.ture = `Tabel Update`.ture WHERE 1";
														break;
														
												// se execute update pentru competitiile Cupa 1 Iunie, On Top, Maraton		
														case "Cupa 1 Iunie":														
														case "On Top of the World":
														case "Maraton Tabere cu Suflet":
														
												$update = "UPDATE `".$numeTabel."` LEFT JOIN `Tabel Update` ON `".$numeTabel."`.cod_unic_participant = `Tabel Update`.cod_unic SET `".$numeTabel."`.locul = `Tabel Update`.loc, `".$numeTabel."`.timp = `Tabel Update`.timp, `".$numeTabel."`.puncte = `Tabel Update`.puncte, `".$numeTabel."`.numar_concurs = `Tabel Update`.nrConcurs WHERE 1";
														
														break;
												
														
														// se execute update pentru competitiile Cupa Malinului			
														case "Cupa Malinului":
														
												$update = "UPDATE `".$numeTabel."` LEFT JOIN `Tabel Update` ON `".$numeTabel."`.cod_unic_participant = `Tabel Update`.cod_unic SET `".$numeTabel."`.locul = `Tabel Update`.loc, `".$numeTabel."`.total = `Tabel Update`.total, `".$numeTabel."`.puncte = `Tabel Update`.puncte, `".$numeTabel."`.numar_concurs = `Tabel Update`.nrConcurs WHERE 1";
												
														break;
														
														// se execute update pentru competitiile Duatlon Tabere cu Suflet		
														case "Cupa Malinului":
														
												$update = "UPDATE `".$numeTabel."` LEFT JOIN `Tabel Update` ON `".$numeTabel."`.cod_unic_participant = `Tabel Update`.cod_unic SET `".$numeTabel."`.locul = `Tabel Update`.loc, `".$numeTabel."`.timp_bicicleta = `Tabel Update`.timp_bicicleta, `".$numeTabel."`.timp_alergare = `Tabel Update`.timp_alergare, `".$numeTabel."`.dificultate = `Tabel Update`.dificultate, `".$numeTabel."`.timp = `Tabel Update`.timp WHERE 1";
												
														break;
														
														default:
														break;
													}
													
													if($db->execute($update)){
														
														$delete = "DROP TABLE `Tabel Update`";
														$db->execute($delete);
														
														if(!strstr($_SERVER['HTTP_REFERER'], '&eroare=')){							
								
								
															$pagina = $_SERVER['HTTP_REFERER'];
															$succes = base64_encode('Tabelul competiției a fost updatat cu succes');
															header('Location: '.$pagina.'&succes='.$succes);
														
														}
														else
														{
															// redirectare fara variabila eroare
															$pagina =  substr($_SERVER['HTTP_REFERER'],0,-68);
															$succes = base64_encode('Tabelul competiției a fost updatat cu succes');
															header('Location: '.$pagina.'&succes='.$succes);		
									
														}
														
														
													}
														
											 }
											 else
											{
											$delete = "DROP TABLE `Tabel Update`";
											$db->execute($delete);		
											echo "<p style='color:red; font-size:18px; margin:auto; text-align:center'>Eroare import fisier!<br />";
				echo "<button style='color:black; font-size:14px; margin:auto; width:auto' onClick=\"history.go(-1)\">Mergi inapoi</button></p>";
													 
											}
												 
										}
											
										} // file_exists
									}// sfarsit executie si verificare creare tabel
							} // sfarsit verificare tip fisier
							
						}	// sfarsit (!isset($retry))
					  
					} // sfarsit uploadTabel
					
					
					if(isset($retry)){
						
						$retry = base64_encode($retry);
						$pagina = $_SERVER['HTTP_REFERER'];
						header('Location: '.$pagina.'&eroare='.$retry);
					
					}
					
							               			                         
ob_flush();	                                
?>