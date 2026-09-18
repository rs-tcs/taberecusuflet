<?php
ob_start();
session_start();
require("includes/Database.php");
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
			

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Administreaza mail</title>
<link rel="stylesheet" type="text/css" media="all" href="css/administreaza_mailuri.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script src="jQueryAssets/jquery-1.8.3.min.js" type="text/javascript"></script>

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
                        <li ><a href="creeaza_competitie.php">Creează competiție</a></li>
                        <li ><a href="genereaza_parola.php">Parolă nouă</a></li>
                    	<li><a href="administrare_tabele.php">Administrare competiții</a></li>
                        <li class="activ"><a href="administreaza_mailuri.php">Administrare mailuri</a></li>
                    </ul>
                    
                    <br><br>
                     
                   <div id="continut"> 
                  		 <table id="mesaje" cellpadding="2" cellspacing="2"> 
                         	<tr>
                            	<th colspan="5">Mesaje de confirmare înscriere în competiții</th>
                            </tr>
                            <tr class="cap_tabel">
                            	<th>Nr. crt.</th>
                                <th>Flagul competiției</th>
                                <th>Mesaj arhivat</th>
                                <th>Detalii</th>
                                <th>Șterge</th>
                            </tr>  
                   		<?php 
                   		$query = "SELECT * FROM `mesaje_email` WHERE 1";
						$result = $db->execute($query);						
						if($db->getCount($result) > 0){
								while($row = $db->getObject($result)){
				   		?>
                        	<tr>
                            	<td><?php echo $row['id'];?></td>
                                <td><?php echo $row['flag_competitie'];?></td>
                                <td><?php echo substr($row['continut_email'], 0,50);?></td>
                                <td><a href="modifica_mail.php?id=<?php echo $row['id'];?>" target="_parent"><button>Modifică</button></a></td>
                                <td>
                                	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                                    	<input type="submit" name="sterge" value="Delete" onClick="return confirmDelete();"/>
                                        <input type="hidden" name="idMessage" value="<?php echo $row['id']?>" />
                                    </form>
                                </td>
                            </tr>
                            
                        <?php
								}
						}
						?>                  		            
                   			
                   		</table>
                        
                        
                        
                   		
                   </div>                  
                    
                   <button id="new_mail" onClick="newMessage()">Creează mesaj de email nou</button>
                   
                   <script type="text/javascript">
				   		function newMessage(){
							var tabel = $('#new_message');
								if(tabel.is(':hidden')){
									
									tabel.fadeToggle(500);
									
								}
								else
								{
									tabel.fadeToggle(500);
								}
							
							}					
							
						function confirmDelete(){
							var confirma = confirm("Ești sigur că dorești ștergerea acestui email?");
								
								if(confirma){
									return true;	
								}
								else
								{
									return false;	
								}
							
						}
				   </script>                    
                   
                   <?php
				   	if(isset($_POST['new_email'])){
						
						if(!empty($_POST['flag']) && (!ctype_space($_POST['flag']))){
							
						$date['flag_competitie'] = $_POST['flag'];	
						}
						else
						{
						$error[] = "Nu ai completat 'flag-ul' competiției";	
						}
						
						if(!empty($_POST['continut_mesaj']) && (!ctype_space($_POST['continut_mesaj']))){
							
						$date['continut_email'] = base64_encode(stripslashes($_POST['continut_mesaj']));	
						}
						else
						{
						$error[] = "Codul email asociat competiției, lipsește";	
						}
						
						// se verifica daca un mesaj cu acelasi flag este deja prezent
						$check = "SELECT `id` FROM `mesaje_email` WHERE `flag_competitie` LIKE '%{$date['flag_competitie']}%'";
						$result = $db->execute($check);
							if($db->getCount($result) > 0){
							$error[] = "Un email cu acest tip de 'FLAG' există deja";	
							}
						
						if(!isset($error)){
							
							if(is_array($date)){
								
								$campuri = '';
								$valori = '';
								foreach($date as $camp=>$valoare){
									$campuri .= $camp . ', ';
									$valori .= (is_numeric($valoare))? $valoare . ', ' : "'".$valoare."', ";
								}
								$campuri = substr($campuri,0,-2);
								$valori = substr($valori,0,-2);
								$insert = "INSERT INTO `mesaje_email` (".$campuri.") Values (".$valori.")";
								$query = "SET NAMES 'utf8'";
								$db->execute($query);
								$result = $db->execute($insert);
								
									if($result){
										
										$mesaj_succes = base64_encode("Un mesaj email a fost adăugat cu succes");
										header('Location: administreaza_mailuri.php?succes='.$mesaj_succes);
										exit();
									}
								
							}
						}
						
					}
					
						if(isset($error)){
							
							$mesaj_eroare = base64_encode(serialize($error));
							header('Location: administreaza_mailuri.php?eroare='.$mesaj_eroare);
							exit();
						}
						
						
						if(isset($_GET['succes'])){
							$succes = base64_decode($_GET['succes']);						
							echo "<div class=\"succes\"><img src=\"pics/tick1.png\" />$succes</div>";
						}
						if(isset($_GET['eroare'])){
							$eroare = unserialize(base64_decode($_GET['eroare']));							
							echo "<div class=\"eroare\">";
							foreach($eroare as $notok){
							echo "<img src=\"pics/cross1.png\" />$notok<br />";	
								}
							echo "</div>";
						}
						
						if(isset($_POST['sterge'])){
							
							$idMessage = $_POST['idMessage'];
							$delete = "DELETE FROM `mesaje_email` WHERE `id` = {$idMessage}";	
							$result = $db->execute($delete);
								if($result){
									$mesaj = base64_encode("Un email a fost șters cu succes");
									header('Location: administreaza_mailuri.php?succes='.$mesaj);
									exit();
								}
						}
						
				   ?>
                   
                   <div id="new_message">
                   		<table id="mail" cellpadding="2" cellspacing="2">
                        	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" >
                        		<tr>
                            		<td class="cap"><label for="flag">Flagul competiției</label></td>
                                	<td><input type="text" name="flag" required /></td>
                            	</tr>                            	
                           		 <tr>
                            		<td class="cap" colspan="2"><label for="continut_mesaj">Conținut email</label></td>                          
                            	</tr>
                            	<tr>
                            		<td colspan="2"><textarea name="continut_mesaj" required ></textarea></td>
                            	</tr>
                                <tr>
                                	<td colspan="2"><input type="submit" name="new_email" value="Salvează email" /></td>
                                </tr>
                        	</form>
                        </table>
                   
                   </div>
                    
               </div>
               
               
            </div>
            
            
            
</body>
</html>
<?php
ob_flush();
?>