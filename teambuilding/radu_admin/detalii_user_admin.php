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
<title>Detalii User</title>
<link rel="stylesheet" type="text/css" media="all" href="css/detalii_user_admin.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
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
                        	<td><a href="#">Compară date</a></td>
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
                	<br>
                    <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="admin_area.php">Adaugă user </a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii User/Admin</li>
                        </ul>                    
                 </div>
                 <br>
                <?php
									
					if(isset($_GET['cuniq'])){
			
						$user = $_GET['cuniq'];
					}
					
					
					$query = "SELECT * FROM `login` WHERE `uniq_id` = '".$user."'";	
					$result = $db->execute($query);
					while($row = mysqli_fetch_array($result)){ ?>
                    
                    		<table id="main_content_data" cellpadding="2px" cellspacing="2px">
                            	<form action="update.php" method="post">
                                	<tr class="cap_tabel">
                                    	<td>Campuri</td>
                                        <td>Valori</td>
                                    </tr>
                                	<tr>
                                    	<td class="celule"><label for="uniq_id">Uniq Id</label></td>
                                        <td class="input"><input type="text" name="uniq_id" value="<?php echo $row['uniq_id']; ?>" /></td>
                                    </tr>
                                    <tr >
                                    	<td class="celule"><label for="username">Username</label></td>
                                        <td class="input"><input type="text" name="username" value="<?php echo $row['username']; ?>" /></td>
                                    </tr>
                                    <tr >
                                    	<td class="celule"><label for="parola">Parola</label></td>
                                        <td class="input"><input type="text" name="parola" value="<?php echo $row['parola']; ?>" /></td>
                                    </tr>
                                    <tr >
                                    	<td class="celule"><label for="data">Data Inregistrarii</label></td>
                                        <td class="input"><input type="text" name="data" value="<?php echo $row['data_inregistrarii']; ?>" /></td>
                                    </tr>
                                     <tr >
                                    	<td class="celule"><label for="acces">Acces [Admin / User]</label></td>
                                        <td class="input" style="text-align:left; text-indent:15px">
                                        
                                        	<?php
                                        		if($row['acces'] == 1){
													
													echo "<input type=\"checkbox\" name=\"acces\" value=\"admin\" checked=\"checked\" />";
													}
													else
													{
													echo "<input type=\"checkbox\" name=\"acces\" value=\"user\"  />";	
													}
                                        	?>                                       
                                        
                                        </td>
                                    </tr>
                                    <tr >
                                    	<td colspan="2" id="butoane"><input type="submit" name="submit" value="Modifică" class="modifica"/>
                                        			<input type="hidden" name="date_user" value="<?php echo $user; ?>" />
                                                    
                                        </td>
                                    	
                                    </tr>
                            
                            	</form>
                            </table>
						
						
						
						
				<?php		
						}
						
						
						
					if(isset($_GET['uniqid'])){
			
						$user = $_GET['uniqid'];
					}
					
					
					$query = "SELECT * FROM `login_magazin` WHERE `uniq_id` = '".$user."'";	
					$result = $db->execute($query);
					while($row = mysqli_fetch_array($result)){ ?>
                    
                    		<table id="main_content_data" cellpadding="2px" cellspacing="2px">
                            	<form action="update.php" method="post">
                                	<tr class="cap_tabel">
                                    	<td>Campuri</td>
                                        <td>Valori</td>
                                    </tr>
                                	<tr>
                                    	<td class="celule"><label for="uniq_id">Uniq Id</label></td>
                                        <td class="input"><input type="text" name="uniq_id" value="<?php echo $row['uniq_id']; ?>" /></td>
                                    </tr>
                                    <tr >
                                    	<td class="celule"><label for="username">Username</label></td>
                                        <td class="input"><input type="text" name="username" value="<?php echo $row['username']; ?>" /></td>
                                    </tr>
                                    <tr >
                                    	<td class="celule"><label for="parola">Parola</label></td>
                                        <td class="input"><input type="text" name="parola" value="<?php echo $row['parola']; ?>" /></td>
                                    </tr>
                                    <tr >
                                    	<td class="celule"><label for="data">Data Inregistrarii</label></td>
                                        <td class="input"><input type="text" name="data" value="<?php echo $row['data_inregistrarii']; ?>" /></td>
                                    </tr>
                                     <tr >
                                    	<td class="celule"><label for="acces">Acces [Admin / User]</label></td>
                                        <td class="input" style="text-align:left; text-indent:15px">
                                        
                                        	<?php
                                        		if($row['acces'] == 1){
													
													echo "<input type=\"checkbox\" name=\"acces\" value=\"admin\" checked=\"checked\" />";
													}
													else
													{
													echo "<input type=\"checkbox\" name=\"acces\" value=\"user\"  />";	
													}
                                        	?>                                       
                                        
                                        </td>
                                    </tr>
                                    <tr >
                                    	<td colspan="2" id="butoane"><input type="submit" name="submit" value="Modifică" class="modifica"/>
                                        			<input type="hidden" name="user_magazin" value="<?php echo $user; ?>" />
                                                    
                                        </td>
                                    	
                                    </tr>
                            
                            	</form>
                            </table>
						
						
						
						
				<?php		
						}
						
						
						
						
						
							
				?>
                
                
                </div>
                
          </div>      

		
</body>
</html>
<?php
ob_flush();
?>