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
<title>Modifica mail</title>
<link rel="stylesheet" type="text/css" media="all" href="css/modifica_mail.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script src="jQueryAssets/jquery-1.8.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/functii.js"></script>

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
							echo "<td ><a href=\"admin_area.php\">Zona admin</a></td>";
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
                        <li><a href="administreaza_mailuri.php">Administrare mailuri</a></li>
                    </ul>
                    
                    <br><br>
                     
                   <div id="continut"> 
                   
                   		<table id="mesaj" cellpadding="2" cellspacing="2">
                        
                        	
                   			<?php
								if(isset($_GET['id'])){
									$id = $_GET['id'];
									
									$query = "SELECT * FROM `mesaje_email` WHERE `id` = {$id}";
									$insert = "SET NAMES 'utf8'";
									$db->execute($insert);
									$result = $db->execute($query);
									
									if($db->getCount($result) > 0){
										while($row = $db->getAssoc($result)){
									?>	
                                    	<tr>
                                        	<td style="border:none">
                                            	<?php
												// afisarea mesajelor venite prin get despre situatia update-ului
													if((isset($_GET['mesaj'])) && (($_GET['mesaj']) <> "")){
														
														if($_GET['mesaj'] == "Mesajul a fost modificat cu succes"){
															
															echo "<center><font style=\"color:green\">".$_GET['mesaj']."</font></center>";
															}
															else
															{
															echo "<center><font style=\"color:red\">".$_GET['mesaj']."</font></center>";
															}
														}
												?>
                                            </td>
                                        </tr>
                                    	<tr>
                                        	<th>Detalii mail confirmare competiție <?php echo $row['flag_competitie'] ?></th>
                                        </tr>
                                        <form action="update.php" method="post">
                                        <tr>
                                        	<td><label for="mail">Mesaj email</label></td>                         
                                        </tr>
                                        <tr>
                                        	<td><textarea name="mail"><?php echo base64_decode(htmlspecialchars($row['continut_email'])); ?></textarea></td>
                                        </tr>
                                        <tr>
                                        	<td colspan="2">
                                            	<input type="submit" name="update_email" value="Modifică email" />
                                                <input type="hidden" name="update_email_ok" value="<?php echo $row['id']; ?>" />
                                         </form>
                                            	<button onClick="animIn();">Vizualizează email-ul</button>
                                            </td>
                                        </tr>
										
                                    <?php
									 	}
									}
									else
									{
									echo "<center><font style=\"color:red;\">Nu există email cu acest ID</font></center>";	
									}
								}
							?>
                        
                        </table>
                   </div>
                    
                    
                    
               </div>
              <div id="messageDiv">
              
					<?php include('vizualizare_email.php') ?>
              </div>           
               
            </div>
            
            <div id="gray"></div> 
            
</body>
</html>
<?php
ob_flush();
?>