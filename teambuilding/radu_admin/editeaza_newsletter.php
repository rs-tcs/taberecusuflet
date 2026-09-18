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
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="60" > 
<title>Editeaza newsletter</title>
<link rel="stylesheet" type="text/css" media="all" href="css/editeaza_newsletter.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/floating-1.12.js" ></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>



			<div id="page">
        		<div id="text_top">
                	<p>Zona de administrare <b style="color:darkblue">Team</b><b style="color: #F00000">Xpert</b> Race Club</p>             	</div>
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
                    
                    
                    <?php
							// se opreste scriptul care trimite newsletter-ul in momentul cand se atinge limita maxima 
							// de abonati
							$query = "SELECT `id` FROM `inscrisi_teamexpert` WHERE `activ` = 1";
							$result_1 = $db->execute($query);
							$nrPrimitori = $db->getCount($result_1);
							
							$query = "SELECT `id` FROM `inscrisi_teamexpert` WHERE `news_activ` = 0 ";
							$result_2 = $db->execute($query);
						    $nrTrimise = $db->getCount($result_2);	
							
								
								if($nrPrimitori == $nrTrimise){
									$script = "../cron_job/trimitere_mail_newsletter.php";
									
									if(file_exists($script)){
										
			rename("../cron_job/trimitere_mail_newsletter.php", "../cron_job/pagini_inactive/trimitere_mail_newsletter_stop.php" );
									}
									else
									{
										
									}
								
								}
					
					
					?>
                    
                   <!-- editare meniu --> 
                    <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center;"><a href="baza_date.php">Home</a></td>
                        </tr>
                    	<tr>
                    		<td><a href="adauga_participant_baza.php">Adaugă participant</a></td>
                        </tr>
                        <tr>
                        	<td><a href="creeaza_newsletter1.php">Creează newsletter</a></td>
                        </tr>
                        <tr>
                    		<td style="background-color:#0F87FF"><a href="editeaza_newsletter.php">Editează newsletter</a></td>
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
							echo "<td><a href=\"admin_area.php\">Zona admin</a></td>";
						?>
                    	</tr>
                        <tr>
                        	<td style="text-align:center; background-color:#A60000"><a href="logout.php">Logout</a></td>
                    	</tr>
                    </table>
                
                </div>
                
                <div class="main_content">	
                <script type="text/javascript">
					function confirmGet(){
						
						var agree = confirm("Ești sigur că vrei să ștergi acest newsletter?");
						
						if(agree)
						return true ;
						else
						return false ;
						
						}
				
				</script>
                	
                    <?php
					
					// se sterge folderul si fisierele conexe a newsletter-ului stocat in baza de date si din folderul unde se stocheaza
					if (isset($_GET['cod'])){						
						
					
						$select = "SELECT `nume_folder` FROM `sabloane_newsletter` WHERE `id` = {$_GET['cod']}";
						$result = $db->execute($select);				
						
						while($news = $db->getObject($result)){
							
							$stiri = $news['nume_folder'];							
						
								$dir = '../newsletter/';
							
								$fisiere = glob($dir.$stiri.'/*');
								 foreach($fisiere as $file){
									 
									 if(is_file($file)){
										 unlink($file);
										 }
										 else
										 {
										rmdir($file);	 
										 }										 
									 }
								
								    rmdir($dir.$stiri);									
							}		
						
						$select = "DELETE FROM `sabloane_newsletter` WHERE `id` = {$_GET['cod']}";
						$rezultat = $db->execute($select);
						
						// truncarea tabelului daca nu mai sunt intrari
						$query = "SELECT `id` FROM `sabloane_newsletter` WHERE 1";
						$nrIntrari = $db->execute($query);
							if($db->getCount($nrIntrari) == 0){
								
								$select = "TRUNCATE TABLE `sabloane_newsletter` ";
								$db->execute($select);
							}
							else
							{
							// nu se executa truncarea tabelului	
							}
						
							if($rezultat){
								
								header("Location: editeaza_newsletter.php"); 
								
							}
								
							// sterge recursiv toate folderele si fisierele inclusiv root
						/*
						function delTree($dir) {
  									 $files = array_diff(scandir($dir), array('.','..'));
  										  foreach ($files as $file) {
											  
     									 (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  										  }
									
    								 rmdir($dir);
 								 } 
						delTree($dir);
						*/
						
						// setam calea catre folderul pe care dorim sa-l stergem					
						
						}
					
					?>
               
               
                     <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
					</div>	
                 	<br> 
                    <div id="text">
                    <?php echo "Tabel newsletter"; ?>
                    </div>
                  	<div id="mesaje">
                    <?php
							//afisarea mesajelor despre situatia bazei de date
							
                    		$query = "SELECT * FROM `inscrisi_teamexpert` WHERE `activ` = 1";
							$result = $db->execute($query);
							$num_r1 = mysqli_num_rows($result);
							if($num_r1 > 0){
								
								$query = "SELECT * FROM `inscrisi_teamexpert` WHERE  `news_activ` = 0 AND `activ` = 1 ";
								$result = $db->execute($query);
							    $num_r2 = mysqli_num_rows($result);
								
									if( $num_r2 == 0){
										
										echo "<button style=\"background-color:#27de2b; color:white; margin-right:10px; border:none !important \">Baza resetată</button>";
										
										}
										
										elseif(($num_r2 <> 0) && ($num_r1 > $num_r2))
										{
											
										echo "<button style=\"background-color:#b06ce5; color:white; margin-right:10px; border:none !important \">Se trimite</button>";	
										}
										else
										{
										echo "<a href=\"update.php?bd=inscrisi_teamexpert\"><button style=\"background-color:#fb4466; color:white; margin-right:10px; border:none !important\"><img src=\"pics/hand.png\" />Sesiune completă!</button></a>";	
										}
								}
								else
								{
									echo "<button style=\"background-color:#fd8329; color:white; margin-right:10px; border:none !important \">Baza unsubscribed</button>";	
								}
								
							// situatia bazei de date in cazul trimiterii unui newsletter	
							$query = "SELECT * FROM `inscrisi_teamexpert` WHERE `activ` = 1";
							$result = $db->execute($query);
							$num_r1 = mysqli_num_rows($result);
							echo "Eligibili"." " ."<button style=\"color:white; background-color:red; border:none !important\">{$num_r1}</button>";
								
							$query = "SELECT * FROM `inscrisi_teamexpert` WHERE  `news_activ` = 0 AND `activ` = 1 ";
							$result = $db->execute($query);
							$num_r2 = mysqli_num_rows($result);	
							echo "Trimis"." "."<button style=\"color:white; background-color:green; border:none !important\">{$num_r2}</button>&nbsp;";							
							
							// resetarea fortata a bazei de date
							echo "<a href=\"update.php?rst_fortat=inscrisi_teamexpert\"><button id=\"reset\"><img src=\"pics/hand.png\" />Resetează forțat</button></a>&nbsp;";		
						
							
							
							// butonul de trimitere a campaniei
							$caleCronJob = "../cron_job/trimitere_mail_newsletter.php";
							if(!file_exists($caleCronJob)){
							
							echo "<a href=\"{$_SERVER['PHP_SELF']}?act=porneste\"><button style=\"background-color:green; color:white\"><img src=\"pics/hand.png\" />Trimite campania</button></a>&nbsp;";
							}
							else
							{
							echo "<a href=\"{$_SERVER['PHP_SELF']}?act=opreste\"><button style=\"background-color:red; color:white\"><img src=\"pics/hand.png\" />Oprește campania</button></a>&nbsp;";	
							}
							
								if(isset($_GET['act']) && $_GET['act'] !== ''){
									
									if($_GET['act'] == 'porneste'){
									
									$cron = '/pagini_inactive/trimitere_mail_newsletter_stop.php';	
									rename("../cron_job/pagini_inactive/trimitere_mail_newsletter_stop.php","../cron_job/trimitere_mail_newsletter.php" );
									header('Location: editeaza_newsletter.php');
									exit();
									}
									
									if($_GET['act'] == 'opreste'){
									
									$cron = $caleCronJob.'/pagini_inactive/trimitere_mail_newsletter_stop.php';	
									rename("../cron_job/trimitere_mail_newsletter.php", "../cron_job/pagini_inactive/trimitere_mail_newsletter_stop.php");
									header('Location: editeaza_newsletter.php');
									exit();
									}
								}
							
							
							
							// trimitere mail newsletter test
							echo "<a href=\"trimite_newsletter_test.php\"><button><img src=\"pics/hand.png\" />Trimite test</button></a>";
							if((isset($_GET['msg'])) && ($_GET['msg'] == "ok")){
								echo "<div class=\"succes\"><img src=\"pics/tick1.png\"/>Newsletter-ul a fost trimis cu succes</div>";
								}
							if((isset($_GET['err'])) && ($_GET['err'] == "notok"))	
								{
								echo "<div class=\"eroare\"><img src=\"pics/cross1.png\"/>Un mesaj de eroare a fost trimis pe mail</div>";	
							}							
							
							
							?>
								
                    
                    </div> 
                    
                              
                   
                    <table id="main_content_data" cellpadding="2px" cellspacing="2px">
                    		<tr class="cap_tabel">
                            	<td style="width:20px">Id</td>
                                <td>Uniq Id</td>
                                <td>Nume folder</td>
                                <td>Nume șablon</td>
                               <!-- <td>Titlu</td> -->
                                <td style="width:20px">Activ<sup>*</sup></td>
                                <td>Detalii</td> 
                                <td>Vizualizează</td> 
                                <td>Update</td>
                                <td>Șterge</td>                               
                            </tr>
                    		<?php
							
							$query = "SELECT * FROM `sabloane_newsletter` WHERE 1 ";
							$rezultat = $db->execute($query);
							
							if($db->getCount($rezultat) >= 1){
								
							
							while($row = mysqli_fetch_array($rezultat)){
							
							?> 
                            
                            <tr class="celule">
                            	<td style="width:20px"><?php echo $row['id'];?></td>
                                <td><?php echo "<b>".$row['uniq_id']."</b>";?></td>
                                <td><?php echo "<b>".$row['nume_folder']."</b>";?></td>
                                <td><?php echo "<b>".$row['nume_sablon']."</b>";?></td>
                                <td style="width:20px"><?php echo $row['activ'];?></td>
                                <td><a href="modifica_newsletter.php?cod=<?php echo $row['id'];?>"><button>Detalii</button></a></td>       
                                <td><a href="vizualizare_newsletter.php?cod=<?php echo $row['id'];?>"><button>Vizualizare</button></a></td>
                                <td><a href="update_newsletter.php?uniqcod=<?php echo $row['uniq_id'];?>"><button>Update</button></a></td>
                                <td><a href="editeaza_newsletter.php?cod=<?php echo $row['id'];?>" onClick="return confirmGet()" style="color:red"><button>Șterge</button></a></td>     
                           
                    <?php
							}
							
							}
							else
								{ ?>
							<td colspan="9">	
							<?php echo "<div class=\"eroare\"><img src=\"pics/cross1.png\"/>Nu ai niciun newsletter în tabel</div>"; ?>
                            </td>	
							<?php }
							
					?>
                     	</tr>
                    </table>
                    <p style="color: #D00"><sup>*</sup>Pentru o bună funcționare a trimiterii newsletter-ului, trebuie să ai doar un singur newsletter activ (1)
                    </p>
                    </div>
                
                    
               </div>
               
 
</body>
</html>
<?php
 ob_flush();
 ?> 