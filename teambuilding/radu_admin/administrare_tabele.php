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
			

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="css/administrare_tabele.css" />
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
                    	<li class="activ"><a href="administrare_tabele.php">Administrare competiții</a></li>
                        <li><a href="administreaza_mailuri.php">Administrare mailuri</a></li>
                    </ul>
                    <br><br>
                    
                    <div id="continut">
                    				
									
								  <table id="tip_competitie" cellpadding="2px" cellspacing="2px">
                                  		<tr>
                                        	<td colspan="3" style="border:none;">
                                  <?php 
								  
									if(isset($_GET['text_tip'])){
										$mesaj = $_GET['text_tip'];
										if($mesaj == "Competiția este activă"){
											echo "<font style=\"color:green\">{$mesaj}</font>";
											}
										if($mesaj == "Competiția nu este activă"){
											echo "<font style=\"color:red\">{$mesaj}</font>";
											}
										if($mesaj == "Sunt acceptate doar valori de 0 sau 1"){
											echo "<font style=\"color:red\">{$mesaj}</font>";
											}
										}
										
									?>
                                    		</td>
                                    	</tr>
                                    	<tr>
                                        	<th colspan="3">Tip competiții</th>
                                        </tr>
                                        <tr>
                                        	<td style="background-color:#6F6F6F; color:white">Id</td>                                 
                                        	<td style="background-color:#6F6F6F; color:white">Tip Competiții</td>                    
                                        	<td style="background-color:#6F6F6F; color:white">Activ</td>
                                        </tr>
                            <?php
							$query = "SELECT * FROM `tip_competitie` WHERE 1";
							$result = $db->execute($query);
							if($result){
								while($row = mysqli_fetch_array($result)){ ?>
                    					<tr>
                                        	<td><?php echo $row['id']; ?></td>                                        
                                        	<td><?php echo $row['competitii']; ?></td>                                       
                                        	<td>
												<form action="update.php" method="post">
                                                	<input type="text" name="activ" value=" <?php echo $row['activ']; ?>" />
                                               		<input type="submit" name="tip_competitie" value="Modifică" />
                                                	<input type="hidden" name="id_tip_competitie" value="<?php echo $row['id'];?>" />
                                            	</form>
                                            </td>
                                        </tr>
                					
									
							<?php		}
								
								
								}
                    		?>
                        
                        			</table>
                                    
                                    
                                    <table id="nume_competitii" cellpadding="2px" cellspacing="2px">
                                    		<tr>
                                        		<td colspan="3" style="border:none;">
                                  <?php 
								  
									if(isset($_GET['text_nume'])){
										$mesaj = $_GET['text_nume'];
										if($mesaj == "Competiția este activă"){
											echo "<font style=\"color:green\">{$mesaj}</font>";
											}
										if($mesaj == "Competiția nu este activă"){
											echo "<font style=\"color:red\">{$mesaj}</font>";
											}
										if($mesaj == "Sunt acceptate doar valori de 0 sau 1"){
											echo "<font style=\"color:red\">{$mesaj}</font>";
											}
										}
										
									?>
                                    			</td>
                                    		</tr>
                                            <tr>
                                        	<th colspan="3">Flag competiții</th>
                                        </tr>
                                        <tr>
                                        	<td style="background-color:#6F6F6F; color:white">Id</td>                                       
                                        	<td style="background-color:#6F6F6F; color:white">Tip Competiție</td>                                 
                                        	<td style="background-color:#6F6F6F; color:white">Activ</td>
                                        </tr>
                                         <?php
							$query = "SELECT * FROM `nume_competitii` WHERE 1";
							$result = $db->execute($query);
							if($result){
								while($row = mysqli_fetch_array($result)){ ?>
                    					<tr>
                                        	<td><?php echo $row['id']; ?></td>                                        
                                        	<td><?php echo $row['nume_competitie']; ?></td>                                       
                                        	<td>
												<form action="update.php" method="post">
                                                	<input type="text" name="activ" value=" <?php echo $row['competitii_activ']; ?>" />
                                               		<input type="submit" name="nume_competitie" value="Modifică" />
                                                	<input type="hidden" name="id_nume_competitie" value="<?php echo $row['id'];?>" />
                                            	</form>
                                            </td>
                                        </tr>
                					
									
							<?php		}
								
								
								}
                    		?>
                                    </table>
                    	
                    </div>
                </div>
                
            </div>
</body>
</html>
<?php
ob_flush();
?>