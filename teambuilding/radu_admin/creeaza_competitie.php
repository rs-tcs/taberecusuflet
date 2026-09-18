<?php
ob_start();
session_start();
require("includes/Database.php");
require("includes/constante.php");
$db = new Database();
	if(!$_SESSION['username']){
		
		header("Location: index.php");
		}
	else
	{
		$user = $_SESSION['username'];
		
	}
	
	$query = "SELECT `acces` FROM `login` WHERE `uniq_id` = '".$_SESSION['uniq_id']."'";
	$result = $db->execute($query);
	
	$row = mysqli_fetch_array($result);
	
	
		if($row['acces'] <> 1){
			
			header("Location: baza_date.php");
			
			}
			
error_reporting(0);
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Creeaza competitie</title>
<link rel="stylesheet" type="text/css" media="all" href="css/creeaza_competitie.css" />
<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.datepicker.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script src="jQueryAssets/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="jQueryAssets/jquery-ui-1.9.2.datepicker.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/functii.js" ></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
			
            <div id="page">
        		<div id="text_top">
                		<p>Zona de administrare <b style="color:darkblue">Team</b><b style="color: #F00000">Xpert</b> Race Club</p>
                </div>
                 <?php
                $select = "SELECT `acces` FROM `login` WHERE username = '".$_SESSION['username']."'";
					$acces = $db->execute($select);
					
					$row = mysqli_fetch_array($acces);
										
					$user = $row['acces'];
						if($user == 1){
							$text = "Admin :";
							}
							else
							{
							$text = "User :";
							}           
				
                	?>
                    
                  <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center; "><a href="baza_date.php">Home</a></td>
                        </tr>
                    	<tr>
                    		<td><a href="adauga_participant_baza.php">Adaugă participant</a></td>
                        </tr>
                        <tr>
                        	<td><a href="creeaza_newsletter1.php">Creează newsletter</a></td>
                        </tr>
                        <tr>
                    		<td><a href="editeaza_newsletter.php">Editează newsletter</a></td>
                        </tr>
                        <tr>
                        	<td><a href="retrimite_codul_unic.php">Retrimite codul</a></td>
                        </tr>
                        <tr>
                   		  <td><a href="tabele_competitii.php">Tabele competiții</a></td>
                        </tr>
                        <tr>
                        	<td><a href="clasament_general.php">Clasament general</a></td>
                        </tr>
                        <tr>
						<?php
							if($user <> 1) 
                        	echo "<td style=\"color:white; background-color:red\">Zona admin</td>";
							else
							echo "<td style=\"background-color:#0F87FF\"><a href=\"admin_area.php\">Zona admin</a></td>";
						?>
                    	</tr>
                        <tr>
                        	<td style="text-align:center; background-color:#A60000"><a href="logout.php">Logout</a></td>
                    	</tr>
                    </table>
                
                </div>
                
                
  				<div class="main_content">
                
                 <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
					</div>
                    <br><br>	
                    
                    
                    <ul>
                    	<li><a href="admin_area.php" >Adaugă User/Admin</a></li>
                        <li class="activ"><a href="creeaza_competitie.php">Creează competiție</a></li>
                        <li><a href="genereaza_parola.php">Parolă nouă</a></li>
                    	<li><a href="administrare_tabele.php">Administrare competiții</a></li>
                        <li><a href="administreaza_mailuri.php">Administrare mailuri</a></li>
                    </ul>
                   
                    <br /><br />
                    
                   <div id="continut">
                   
                   		<script type="text/javascript">
								function vizibilitate() {
      							 var e = $("#tabel_competitii");
      							 if(e.is(":hidden"))
         							 e.fadeIn('slow');
									 
      							 else
         							 e.fadeOut('slow');
  								  }
						
						</script>
                   
               		 <div id="button"><button onClick="vizibilitate();">Arată tabel competiții</button></div>
                   			
                   		<div id="tabel_competitii">
                        
                        	<table id="competitii" cellpadding="2px" cellspacing="2px">
                            
                            	<tr class="cap_tabel">
                                	<td >Id</td>
                                	<td>Nume competiție</td>
                                    <td>Data competiției</td>
                                    <td>Data adăugării competiției</td>
                                </tr>
                        
                        	<?php
								
								$query = "SELECT * FROM `tabel_competitii` WHERE 1";
								$result = $db->execute($query);
								if($db->getCount($result) <> 0){
								
								while($row = mysqli_fetch_array($result)){ ?>
									
                                    <tr class="celule">
                                    	<td><?php echo $row['id'] ;?></td>
                                        <td><?php echo $row['nume_competitie'] ;?></td>
                                        <td><?php echo $row['data_competitiei'] ;?></td>
                                        <td><?php echo $row['data_adaugarii_competitiei'] ;?></td>
                                    </tr>								
									
									
							<?php	} // sfarsit while
								}// sfarsit while
								else
								{
									echo "<tr class=\"celule\">";
									echo "<td colspan=\"4\" style=\"color:red; font-size:14px\">Nu ai creat nicio competiție până acum</td>";	
									echo "</tr>";
								}
							?>                        
                        	</table>
                        
                        </div>
                        <br><br>
                        
                     <div id="adauga_competitie">
                        
                        	<div id="text">Adaugă competiție nouă</div>
                            
                      <br>
                      
                      <?php
					  		if((isset($_POST['competitie']) && ($_POST['competitie'] == "ok"))){
								
								if((!empty($_POST['nume_competitie'])) && (!ctype_space($_POST['nume_competitie']))){
									include("php/functii.php");
									$data['nume_competitie'] = $_POST['nume_competitie'];
									}
									else
									{
									$error[] = "Nu ai completat numele competiției";	
									}
								
								if((!empty($_POST['data_competitiei'])) && (!ctype_space($_POST['data_competitiei']))){
									$pubDate = str_replace("/" ,"", $_POST['data_competitiei']);
									$pubDate = substr($pubDate,4,4)."-".substr($pubDate,0,2)."-".substr($pubDate,2,2);
									//$pubDate = implode(array_reverse(explode("/" , $_POST['data_competitiei'])), "-");
									$data['data_competitiei'] = $pubDate;
									}
									else
									{
									$error[] = "Nu ai completat data competiției";	
									}
								if((!empty($_POST['data_limita_personalizare'])) && (!ctype_space($_POST['data_limita_personalizare']))){
									$pubDate = str_replace("-" ,"", $_POST['data_limita_personalizare']);
									$pubDate = substr($pubDate,4,4)."-".substr($pubDate,0,2)."-".substr($pubDate,2,2);
									//$pubDate = implode(array_reverse(explode("/" , $_POST['data_competitiei'])), "-");
									$data['dataLimitaPersonalizare'] = $pubDate;
									}
									else
									{
									$error[] = "Nu ai completat data limită de personalizare a numărului";	
									}
								if(!$_POST['tip_competitie'] == ""){
									$data['tip_competitie'] = $_POST['tip_competitie'];
									}
									else
									{
									$error[] = "Nu ai ales tipul competiției";	
									}
								if(!$_POST['flag'] == ""){
									$data['flag'] = $_POST['flag'];
									}
									else
									{
									$error[] = "Nu ai ales flag-ul competiției";	
									}		
								$data['activ'] = 1;
								$data['data_adaugarii_competitiei'] = date('Y-m-d');	
									
								// se verifica daca nu avem erori
								
							if(!isset($error)){
								
								
								switch ($_POST['flag']){
									
									// generarea tabelului tip Bike Fest
									case "Comana Bike Fest":									
									case "Cupa Veseliei":									
										
									 $tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	`cat_varsta` 
								 enum('piticoti','pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `traseu` varchar(255) NOT NULL,
									 `insotitor` varchar(255) NOT NULL,
									 `pachet_vip` varchar(255) NOT NULL,
  									 `tricou` varchar(10) NOT NULL,
									 `print_vip` varchar (255) NOT NULL,
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp` varchar(255) NOT NULL,
									 `ture` int(2) NOT NULL,
  									 `numar_concurs` varchar(10) NOT NULL,
									 `modalitate_plata` varchar(20) NOT NULL,
									 `suma` varchar(20) NOT NULL,
									 `nr_chitanta` varchar(25) NOT NULL,
									 `confirmare` varchar(5) NOT NULL,
									 `taxa` varchar(255) NOT NULL,
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";	
									 break;	
									 	
									
									// generarea tabelului Cupa 1 Iunie
									case "Cupa 1 Iunie" : 									
									
									$tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	`cat_varsta` 
								 enum('piticoti','pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `traseu` varchar(255) NOT NULL,
									 `insotitor` varchar(255) NOT NULL,									 
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp` varchar(255) NOT NULL,
  									 `numar_concurs` varchar(10) NOT NULL,									 
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";	
									 break;										 
									
									
									
									 // generez tabel tip Sinaia On Top									 
									 case "On Top of the World":
										
									 $tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	`cat_varsta` 
								 enum('piticoti','pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `traseu` varchar(255) NOT NULL,
									 `pachet_vip` varchar(255) NOT NULL,
  									 `tricou` varchar(10) NOT NULL,
									 `print_vip` varchar (255) NOT NULL,
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp` varchar(255) NOT NULL,									 
  									 `numar_concurs` varchar(10) NOT NULL,
									 `modalitate_plata` varchar(20) NOT NULL,
									 `suma` varchar(20) NOT NULL,
									 `nr_chitanta` varchar(25) NOT NULL,
									 `confirmare` varchar(5) NOT NULL,
									 `taxa` varchar(255) NOT NULL,
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";										
									break;
								
									
								    // generez tabel tip "Cupa Tabere cu Suflet"									
									
									case "Cupa Tabere cu Suflet":
										
									$tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	`cat_varsta` 
								 enum('piticoti','pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `traseu` varchar(255) NOT NULL,
									 `insotitor` varchar(255) NOT NULL,
									 `pachet_vip` varchar(255) NOT NULL,
  									 `tricou` varchar(10) NOT NULL,
									 `print_vip` varchar (255) NOT NULL,
									 `duatlon` varchar (255) NOT NULL,
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp` varchar(255) NOT NULL,
									 `ture` int(2) NOT NULL,
  									 `numar_concurs` varchar(10) NOT NULL,
									 `modalitate_plata` varchar(20) NOT NULL,
									 `suma` varchar(20) NOT NULL,
									 `nr_chitanta` varchar(25) NOT NULL,
									 `confirmare` varchar(5) NOT NULL,
									 `taxa` varchar(255) NOT NULL,
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";		
									
									break;
									
									// generez tabel Maraton Tabere cu Suflet
									case "Maraton Tabere cu Suflet": 									
										
									$tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	`cat_varsta` 
								 enum('piticoti','pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `traseu` varchar(255) NOT NULL,
									 `pachet_vip` varchar(255) NOT NULL,
  									 `tricou` varchar(10) NOT NULL,
									 `print_vip` varchar (255) NOT NULL,
									 `duatlon` varchar (255) NOT NULL,
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp` varchar(255) NOT NULL,									 
									 `ture` int(2) NOT NULL,
  									 `numar_concurs` varchar(10) NOT NULL,
									 `modalitate_plata` varchar(20) NOT NULL,
									 `suma` varchar(20) NOT NULL,
									 `nr_chitanta` varchar(25) NOT NULL,
									 `confirmare` varchar(5) NOT NULL,
									 `taxa` varchar(255) NOT NULL,
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";							
										
								
									 break;
									
									// generez tabel Cupa Malinului
									 case "Cupa Malinului":
									
									 $tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	`cat_varsta` 
								 enum('piticoti','pitici','copii','spiridusi','uriasi','tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `categoria` varchar(255) NOT NULL,
									 `experienta` varchar(255) NOT NULL,
									 `adresa` varchar(255) NOT NULL,
									 `pachet_vip` varchar(255) NOT NULL,
  									 `tricou` varchar(10) NOT NULL,
									 `print_vip` varchar (255) NOT NULL,
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp` varchar(255) NOT NULL,									 
  									 `numar_concurs` varchar(10) NOT NULL,
									 `modalitate_plata` varchar(20) NOT NULL,
									 `suma` varchar(20) NOT NULL,
									 `nr_chitanta` varchar(25) NOT NULL,
									 `confirmare` varchar(5) NOT NULL,
									 `taxa` varchar(255) NOT NULL,
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";										
									break;
									
									// generez tabel Duatlon Tabere cu Suflet
									
									case "Duatlon Tabere cu Suflet":
									
									   $tabel_competitie = "CREATE TABLE IF NOT EXISTS `".$data['nume_competitie']."` (
 									 `id` int(11) NOT NULL AUTO_INCREMENT,
 									 `cod_unic_participant` char(20) NOT NULL,
 									 `numele` varchar(255) NOT NULL,
 									 `prenumele` varchar(255) NOT NULL,
 									 `varsta` int(3) NOT NULL,
								 	 `cat_varsta` enum('piticoti','pitici','copii','spiridusi','uriasi',
								     'tineri','adulti','seniori','forever_young') NOT NULL,
 									 `sex` varchar(255) NOT NULL,
  									 `traseu_alergare` varchar(255) NOT NULL,
									 `traseu_bicicleta` varchar(255) NOT NULL,
									 `insotitor` varchar(255) NOT NULL,
									 `pachet_vip` varchar(255) NOT NULL,
  									 `tricou` varchar(10) NOT NULL,
									 `print_vip` varchar (255) NOT NULL,
									 `locul` int(2) NOT NULL,
									 `puncte` int(5) NOT NULL,
 									 `timp_bicicleta` varchar(255) NOT NULL,
									 `timp_alergare` varchar(255) NOT NULL,
									 `timp` varchar(255) NOT NULL,
									 `dificultate` varchar(255) NOT NULL,
									 `ture_bicicleta` int(2) NOT NULL,
									 `ture_alergare` int(2) NOT NULL,
  									 `numar_concurs` varchar(10) NOT NULL,
									 `modalitate_plata` varchar(20) NOT NULL,
									 `suma` varchar(20) NOT NULL,
									 `nr_chitanta` varchar(25) NOT NULL,
									 `confirmare` varchar(5) NOT NULL,
									 `taxa` varchar(255) NOT NULL,
									 `email` varchar(255) NOT NULL,
									 `telefon` int(10) unsigned zerofill NOT NULL,
  									 `data_competitiei` text NOT NULL,
  									 PRIMARY KEY (`id`)
									 ) ENGINE=InnoDB DEFAULT CHARSET=utf8";	
									 break;			
									
									// de continuat
									}						
								
															
								
								
								
									
								
									// verificam daca datele au formatul necesar
									
									if(is_array($data)){
										
											// introducem datele in baza de date
											
																				 
									if(mysqli_num_rows($db->execute("SHOW TABLES LIKE '". $_POST['nume_competitie'] ."'")) == 1){
												 
												 $error[] = "O competiție cu același nume deja există";	 
												 
												 }
												 else
												 
												 
												 {
													
												// verific daca conditia este adevarata pentru crearea tabelului	
													if($tabel_competitie){
													 
													$db->execute($tabel_competitie); 	
																										
																									
													$tabel = "tabel_competitii";
													$campuri = "";
													$valori = "";
													
													foreach($data as $key=>$valoare){
														
														$campuri .= $key . ", ";
														$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'". $valoare ."', ";
													} // sfarsit foreach
													$campuri = substr($campuri,0,-2);
													$valori = substr($valori,0,-2);
													
											     	$query = "INSERT INTO " . $tabel . "(". $campuri .") VALUES (" . $valori .")";
													$result = $db->execute($query);				
													
													
													
													
													 	if($result){
													
													// competitia creeata devine activa in pagina inscriere_competitie daca a fost
													// inactivata la o data precedenta		
													$query = "UPDATE `nume_competitii` SET `competitii_activ` = 1 WHERE `nume_competitie` = '".$_POST['flag']."'";
													$db->execute($query);
														  
														  echo "<div class=\"succes\"><p>Crearea competiției  
														  \"{$_POST['nume_competitie']}\" a fost executată cu succes</p></div>";
														  echo "<br><br>";
														 echo '<META HTTP-EQUIV="Refresh" Content="3; URL='.$_SERVER['PHP_SELF'].'">';
														  exit();	
														  }
														  else
														  {
															echo "Eroare";  
														  }
													
													}
													else
													{
													$error[] = "Nu pot crea competiția";	
													}
													
												 } // sfarsit esle verificare existenta tabel
											 
											 
											 
										
										}
										else
										{
										$error[] = "Date invalide";	
										}
									
									
									}
								
								
								}
								
								// se verifica erorile
								
								if(isset($error)){
									echo "<div class=\"eroare\">";
										foreach($error as $eroare){
											
											echo "<img src=\"pics/cross1.png\"/> {$eroare} <br>";
											}
									
									echo "</div>";
									}
					  
					  ?>
                        
                   	   <table id="competitie_noua">
                            
                            	<form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
                                	<tr>
                                    	<td class="celula1"><label for="nume_competitie">Numele competiției</label></td>
                                        <td class="celula2"><input type="text" name="nume_competitie" required /></td>
                                    </tr>
                                    <tr>
                                    	<td class="celula1"><label for="data_competitiei">Data competiției</label></td>
                                        <td class="celula2"><input type="text" name="data_competitiei" id="Datepicker1" required/></td>
                                    </tr>
                                    <tr>
                                    	<td class="celula1"><label for="data_limita_personalizare">Data limită personalizare număr</label></td>
                                        <td class="celula2"><input type="text" name="data_limita_personalizare" value="0000-00-00"/></td>
                                    </tr>
                                    <tr>
                                      <td class="celula1"><label for="tip_competitie">Tipul competiției</label></td>
                                      <td class="celula2">
                                      <select name="tip_competitie" id="tip_competitie" required>
                                      <option value="">Alege tipul competiției</option>
                                      	<?php
											$query = "SELECT `competitii` FROM `tip_competitie` WHERE 1";
											$result = $db->execute($query) ;
											while($row = mysqli_fetch_array($result)){
												
												echo "<option value=\"{$row['competitii']}\" onClick=\"hideOption(this.value);\">{$row['competitii']}</option>";
												}
                                      	?>
                                      </select>
                                      </td>
                                    </tr>
                                    <tr>
                                    	<td class="celula1"><label for="flag">Flag-ul competiției</label></td>
                                        <td class="celula2">
                                        	<select name="flag" id="flag" required>
                                            	<option value="">Alege flag-ul competiției</option>
                                        	<?php
												$query = "SELECT `nume_competitie` FROM `nume_competitii` WHERE 1";
												$result = $db->execute($query);
												while($row = mysqli_fetch_array($result)){
												echo "<option value=\"{$row['nume_competitie']}\">{$row['nume_competitie']}</option>";	
													}
											?>
                                             </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2"><input type="submit" name="submit" value="Creează competiție" />
                                        				<input type="hidden" name="competitie" value="ok" />
                                        </td>
                                        
                                    </tr>
                                
                                </form>                            
                            
                            </table>
                        
                        
                        </div>
                        <br><br>
                   
                   </div>
                   
                   
                   
       		  </div>


		</div>
<script type="text/javascript">
$(function() {
	$( "#Datepicker1" ).datepicker(); 
});
</script>
</body>
</html>
<?php
ob_flush();
?>