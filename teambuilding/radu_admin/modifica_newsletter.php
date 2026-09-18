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
<title>Modifica Newsletter</title>
<link rel="stylesheet" type="text/css" media="all" href="css/modifica_newsletter.css" />
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
							echo "<td><a href=\"admin_area.php\">Zona admin</a></td>";
						?>
                    	</tr>
                        <tr>
                        	<td style="text-align:center; background-color:#A60000"><a href="logout.php">Logout</a></td>
                    	</tr>
                    </table>
                
                </div>
                
                              
                
                
                 <?php 	
				 // redirectionare la comanda anuleaza
				 if(isset($_POST['cancel'])){
					 
					 header("Location: modifica_newsletter.php?cod=".$_POST['id']);
					 
					 }
				 
						 			 
				 
				 // afisarea tabelului cu detalii despre newsletter
				if((isset($_GET['cod'])) && (!isset($_POST['trimis_editare']))){ 
					$newsletter = $_GET['cod'];
					
					$query = "SELECT * FROM `sabloane_newsletter` WHERE `id` = '".$newsletter."'";
					$rezultat = $db->execute($query);
					while($row = mysqli_fetch_array($rezultat)){
				
				
				?> 
                 <div class="tabel_date">
                 
                 <div id="admin">
                 <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
				 ?>
                 </div>
                 <br>
                 <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="editeaza_newsletter.php">Tabel newslettere </a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii newsletter</li>
                        </ul>                    
                 </div>
                 <br>
                 	<table id="tabel_date_newsletter">
                    	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        	<tr>
                            	<td id="header" colspan="2">Vizualizare detalii newsletter<?php echo "'".$row['nume_sablon']."'";?></td>
                            </tr>
                            <tr>
                        		<td class="celule"><label for="nume_folder">Numele folderului</label></td>
                           		<td class="input"><input type="text" name="nume_folder" value ="<?php echo $row['nume_folder']; ?>" disabled /></td>
                       		 </tr>
                    		<tr>
                        		<td class="celule"><label for="nume_sablon">Numele newsletter-ului</label></td>
                           		<td class="input"><input type="text" name="nume_sablon" value ="<?php echo $row['nume_sablon']; ?>" disabled /></td>
                       		 </tr>
                         	  <tr>
                        		<td class="celule"><label for="activ">Starea newsletter-ului (activ)</label></td>
                           		<td class="input"><input type="text" name="activ" value ="<?php echo $row['activ']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule" colspan="2" style="text-align:center"><label for="text3">Sablon</label></td>
                             </tr>
                             <tr>   
                           		<td class="input" colspan="2" class="textarea"><textarea name="cod_newsletter" disabled><?php echo base64_decode($row['editor']);?></textarea></td>
                       		 </tr>                 
                             <tr>
                             	
                             	<td class="input" colspan="2"><input type="submit" name="submit" value="Modifică" class="modifica"/>
                                				  <input type="hidden" name="trimis_editare" value="ok" />
                                                  <input type="hidden" name="cod" value="<?php echo $_GET['cod']; ?>"/>
                                                  
                            	</td>				  
                             </tr>
                    	</form>
                    </table>
                 
                 </div>
                <?php
					}					
					
				}
				
                
                
                
                
                 // afisarea tabelului de editare a sablonului
				 
				 if((isset($_POST['trimis_editare'])) && ($_POST['trimis_editare'] == "ok")){
					 
					 $id = $_POST['cod']; 
                     
                    $query = "SELECT * FROM `sabloane_newsletter` WHERE `id` = '".$id."'";
					$rezultat = $db->execute($query);
					while($row = mysqli_fetch_array($rezultat)){?>
					 
				 <div class="tabel_date">
                 <div id="admin">
                     <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
				     ?>
                  </div>
                  <br>
                  <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="editeaza_newsletter.php">Tabel newslettere </a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii newsletter</li>
                        </ul>                    
                 </div>
                 <br>	
                   	<table id="tabel_date_newsletter_modifica">
                    	<form action="update.php" method="post">
                        	<tr>
                            	<td id="header" colspan="2">Modificare detalii newsletter<?php echo "'".$row['nume_sablon']."'";?></td>
                            </tr>
                            <tr>
                        		<td class="celule"><label for="nume_folder">Numele folderului</label></td>
                           		<td class="input"><input type="text" name="nume_folder" value="<?php echo $row['nume_folder']; ?>"  /></td>
                       		 </tr>
                    		<tr>
                        		<td class="celule"><label for="nume_sablon">Numele newsletter-ului</label></td>
                           		<td class="input"><input type="text" name="nume_sablon" value="<?php echo $row['nume_sablon']; ?>"  /></td>
                       		 </tr>
                         	  <tr>
                        		<td class="celule"><label for="activ">Starea newsletter-ului (activ)</label></td>
                           		<td class="input"><input type="text" name="activ" value="<?php echo $row['activ']; ?>"  /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule" colspan="2" style="text-align:center"><label for="cod_sablon">Sablon</label></td>
                             </tr>
                             <tr>   
                           		<td class="input" colspan="2" class="textarea"><textarea name="cod_newsletter" ><?php echo base64_decode($row['editor']); ?></textarea></td>
                       		 </tr>                 
                             <tr>
                             	
                             	<td class="input" colspan="2"><input type="submit" name="submit" value="Update" class="modifica"/>
                                				  <input type="hidden" name="trimis_update" value="ok" />
                                                  <input type="hidden" name="id" value="<?php echo $_GET['cod']; ?>"/>
                                                  <input type="submit" name="cancel" value="Anulează" class="modifica"/>
                                                  
                                                  
                            	</td>				  
                             </tr>
                    	</form>
                    </table>
                     	
                        
					 
					 
				 </div>
					 
				<?php 	
				
				 }
				 }
				 ?>
				 
                
                
                
           </div>
</body>
</html>
<?php
ob_flush();
?>