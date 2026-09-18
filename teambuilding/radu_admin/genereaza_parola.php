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
<title>Genereaza parola</title>
<link rel="stylesheet" type="text/css" media="all" href="css/genereaza_parola.css" />
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
                        <li class="activ"><a href="genereaza_parola.php">Parolă nouă</a></li>
                    	<li><a href="administrare_tabele.php">Administrare competiții</a></li>
                        <li><a href="administreaza_mailuri.php">Administrare mailuri</a></li>
                    </ul>
                    
                   <br /><br /> 
                   <div id="continut">                     
                   		
                    
                    	<table id="genereaza_parola" cellpadding="2px" cellspacing="2px">
                                           
                        	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                            	<tr class="cap_tabel">
                                	<td><label for"parola">Introdu în campul de mai jos parola aleasă</label></td>
                                </tr>
                                <tr class="celule">
                                	<td><input type="text" name="parola" /></td>
                                </tr>
                        		<tr>
                               	    <td style="text-align:center"><input type="submit" name="submit" value="Generează parolă nouă" />
                                    </td>
                                </tr>
                        	</form>
                        </table>
                        
                        <?php
							if(isset($_POST['submit'])){
								
								if((!empty($_POST['parola'])) && (!ctype_space($_POST['parola']))){
									
									$parola = sha1($_POST['parola']);
									}
								else
									{
									$error = "Nu ai introdus nicio parolă";	
									}
									
								if(!isset($error)){
									
									echo "<table id=\"genereaza_parola\" cellpadding=\"2px\" cellspacing=\"2px\">";
									echo "<tr class=\"celule\">";
									echo "<td>{$parola}</td>";
									echo "</tr>";
									echo "</table>";
									
									}						
								
								
								} // sfarsit isset
								
								if(isset($error)){
								
									echo "<div class=\"eroare\">";
									echo "<img src=\"pics/cross1.png\"/> {$error} <br>";
									echo "</div>";
								}
						?>
                    
                   </div>
                    
                    
                    
               </div>
               
               
            </div>
            
            
            
</body>
</html>
<?php
ob_flush();
?>