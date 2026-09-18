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
<title>Admin area</title>
<link rel="stylesheet" type="text/css" media="all" href="css/zona_admin.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/functii.js"></script>
<script type="text/javascript" src="js/floating-1.12.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<script type="text/javascript">
	function confirmPost()
		{
			var agree = confirm("Ești sigur că vrei să ștergi acest user?");
				if (agree)
				return true ;
		else
				return false ;
		}


</script>

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
                    <script type="text/javascript">
                $(document).ready(function(){

  						  //run once
   					 var el=$('#meniu_stanga');
   					 var originalelpos=el.offset().top; // memoreaza pozitia actuala din pagina

   						 //run on scroll
   				  $(window).scroll(function(){
     				   var el = $('#meniu_stanga'); // important! (local)
       				   var elpos = el.offset().top; // take current situation
                       var windowpos = $(window).scrollTop();
                       var finaldestination = windowpos+originalelpos;
                       el.stop().animate({'top':finaldestination},500);
                        });

                });
				</script>
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
                    	<li class="activ"><a href="admin_area.php" >Adaugă User/Admin</a></li>
                        <li><a href="creeaza_competitie.php">Creează competiție</a></li>
                        <li><a href="genereaza_parola.php">Parolă nouă</a></li>
                    	<li><a href="administrare_tabele.php">Administrare competiții</a></li>
                        <li><a href="administreaza_mailuri.php">Administrare mailuri</a></li>
                    </ul>
                    <br><br>
                    
                   <div id="continut">                  
                                    
                   
                   	<div class="text">Useri administrare</div>
                    <br>                                        
                                
                             <div id="tabel_useri">
                             <?php
							 	if(isset($_GET['eroare'])){
									
									$mesaj = base64_decode($_GET['eroare']);
									
									echo "<div class=\"eroare\"><img src=\"pics/cross1.png\" />&nbsp;".$mesaj."</div>";
									
									}
									
								if(isset($_GET['succes'])){
									
									$mesaj = base64_decode($_GET['succes']);
									
									echo "<div class=\"succes\"><img src=\"pics/tick1.png\" />&nbsp;".$mesaj."</div>";
									
									}
								
							 
							 ?>
                           	<br>
                             	<table class="useri" cellpadding="2px" cellspacing="2px">
                                	<tr class="cap_tabel">
                                    	<td>Uniq ID</td>
                                    	<td>Username</td>
                                        <td>Parola</td>
                                        <td>Data inregistrarii</td>
                                        <td>Nivel acces</td>
                                        <td>Modifica</td>
                                        <td>Șterge user</td>
                                    </tr>
                                    
                                    <?php
										$query = "SELECT * FROM `login` WHERE 1";
										$rezultat = $db->execute($query);
										
										if($db->getCount($rezultat) >= 1){
								
							
											while($row = mysqli_fetch_array($rezultat)){
									?>
                                    
                                    <tr class="celule">
                                    	<td><?php echo $row['uniq_id'] ; ?></td>
                                        <td><?php echo $row['username'] ; ?></td>
                                        <td><?php echo $row['parola'] ; ?></td>
                                        <td><?php echo $row['data_inregistrarii'] ; ?></td>
                                        <td><?php echo $row['acces'] ; ?></td>
                                        <td>
                                        <?php
										// protejeaza de modificarea datelor contului admin
										if(($row['uniq_id'] == "5339fd7b4f6e8") && ($_SESSION['uniq_id'] <> "5339fd7b4f6e8"
											))
											{ 
											echo "";
											}
											else
											{
											?>
										 <a href="detalii_user_admin.php?cuniq=<?php echo $row['uniq_id']; ?>"><button>Modifică</button></a>		
										<?php
											}
										?>                                       
                                        </td>
                                        <td><a href="update.php?duniq=<?php echo $row['uniq_id']; ?>" style="text-decoration:none">
                                        <form action="<?php $_SERVER['PHP_SELF'] ;?>" method="post">
                                        	<?php 
											// protejeaza de stergerea contului admin
											if(($row['uniq_id'] == "5339fd7b4f6e8") && ($_SESSION['uniq_id'] <> "5339fd7b4f6e8"
											)){ 
                                            echo "";
                                         	
											}
											else
											{
											?>
                                        <input type="button" onClick="return confirmPost();" value="Șterge User" />
											<?php
											}
											?>
                                       
                                        </form>
                                         </a>
                                        </td>
                                    </tr>
                                    <?php
											}
										}
										else
										{
									?>
                                	<tr>
                                    	<td colspan="5">
                                    <?php echo "<div class=\"eroare\"><img src=\"pics/cross1.png\"/>Nu sunt useri activi</div>";  ?>    
                                        </td>
                                    </tr>
                                
                                	<?php 
										}
									?>	
                                </table>
                                <br />
                                <div class="text">Useri magazin</div>
                                <br />
                                <table class="useri" cellpadding="2px" cellspacing="2px">
                                	<tr class="cap_tabel">
                                    	<td>Uniq ID</td>
                                    	<td>Username</td>
                                        <td>Parola</td>
                                        <td>Data inregistrarii</td>
                                        <td>Nivel acces</td>
                                        <td>Modifica</td>
                                        <td>Șterge user</td>
                                    </tr>
                                    
                                    <?php
										$query = "SELECT * FROM `login_magazin` WHERE 1";
										$rezultat = $db->execute($query);
										
										if($db->getCount($rezultat) >= 1){
								
							
											while($row = mysqli_fetch_array($rezultat)){
									?>
                                    
                                    <tr class="celule">
                                    	<td><?php echo $row['uniq_id'] ; ?></td>
                                        <td><?php echo $row['username'] ; ?></td>
                                        <td><?php echo $row['parola'] ; ?></td>
                                        <td><?php echo $row['data_inregistrarii'] ; ?></td>
                                        <td><?php echo $row['acces'] ; ?></td>
                                        <td><a href="detalii_user_admin.php?uniqid=<?php echo $row['uniq_id']; ?>"><button>Modifică</button></a></td>
                                        <td><a href="update.php?uniqid=<?php echo $row['uniq_id']; ?>" style="text-decoration:none">
                                        <form action="<?php $_SERVER['PHP_SELF'] ;?>" method="post">
                                        	
                                        <input type="button" onClick="return confirmPost();" value="Șterge User" />
											
                                        
                                        </form>
                                        </a>
                                        </td>
                                    </tr>
                                    <?php
											}
										}
										else
										{
									?>
                                	<tr>
                                    	<td colspan="5">
                                    <?php echo "<div class=\"eroare\"><img src=\"pics/cross1.png\"/>Nu sunt useri activi</div>";  ?>    
                                        </td>
                                    </tr>
                                
                                	<?php 
										}
									?>	
                                </table>
                             
                             
                             </div> 
                             <br>
                             
                             
                                                         
                             <div id="adauga">
                             
                             <div class="text">User nou</div>
                             <br>
                             
                                 <?php 					
							if((isset($_POST['trimis_user'])) && ($_POST['trimis_user'] == "ok")){
										
									if((!empty($_POST['username'])) && (!ctype_space($_POST['username']))){
											
											$data['username'] = $_POST['username'];											
											}
											else
											{
											$errors[] = "Nu ați ales un username";	
											}
									if((!empty($_POST['parola1'])) && (!ctype_space($_POST['parola1']))){
											
											if($_POST['parola1'] == $_POST['parola2']){
												
												$data['parola'] = sha1($_POST['parola1']);
												
												}
												else
												{
												$errors[] = "Parolele nu sunt identice";	
												}											
											
											}
											else
											{
											$errors[] = "Nu ați completat parola";	
											}
									 if(!empty($_POST['tabel'])){
										 
										 $tabel = $_POST['tabel'];
										 }
										 else
										 {
										  $errors[] = "Nu ați selectat tabelul pentru care creați user-ul";	 
										 }
									 
									if((isset($_POST['acces'])) && ($_POST['acces'] == "admin")){
											
											$data['acces'] = 1;
											}
											else
											{
											$data['acces'] = 0;
											}
											
											if(!isset($errors)){
												
												$data['data_inregistrarii'] = date('Y-m-d G:i:s');
												$data['uniq_id'] = uniqid();
												
												if(is_array($data)){
													
													$campuri = '';
													$valori = '';
														foreach($data as $key=>$valoare){
									
														$campuri .= $key . ", ";	
														$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'" . $valoare . "', ";	
														}
										
													$campuri = substr($campuri,0,-2);
													$valori = substr($valori,0,-2);
													$query = "Insert Into " . $tabel . "(" . $campuri . ") Values (" . $valori . ")";
									
													$result = $db->execute($query);
													
													
													
													if($result){															
													header("Location: admin_area.php");
													}
												}
												
												
												}
											
											
										// sfarsit procesare date user nou
										}
										
										// afisarea erorilor
										if(isset($errors)){
											echo "<div class=\"eroare\">";
											foreach($errors as $eroare){
											echo "<img src=\"pics/cross1.png\"/> {$eroare} <br>";	
												}
											echo "</div>";	
											}
							 ?>  
                             
							
                                <table id="adauga_user" cellpadding="2px" cellspacing="2px">
                                	<form action="<?php $_SERVER['PHP_SELF'] ; ?>" method="post">
                                    	<tr>
                                        	<td><label for="username">User</label></td>
                                            <td><input type="text" name="username" required /></td>
                                        <tr>
                                        <tr>
                                        	<td><label for="pass1">Pass</label></td>
                                            <td><input type="text" name="parola1" required /></td>
                                        <tr>
                                        <tr>
                                        	<td><label for="pass2">Retype pass</label></td>
                                            <td><input type="text" name="parola2" required /></td>
                                        <tr>
                                        <tr>
                                        	<td><label for="tabel">Selectează unde salvezi user-ul</label></td>
                                            <td>
                                            	<select name="tabel" required>
                                                	<option value="">Selectează tabelul</option>
                                                    <option value="login">Login administrare</option>
                                                    <option value="login_magazin">Login magazin</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td><label for="acces">Setează accesul user/admin</label></td>
                                            <td><input type="checkbox" name="acces" value="admin" /></td>
                                        <tr>
                                        <tr>
                                        	
                                            <td colspan="2" style="text-align:center"><input type="submit" name="submit" value="Adaugă user" class="modifica" />
                                            <input type="hidden" name="trimis_user" value="ok" />
                                            </td>
                                        <tr>
                                    
                                    </form>                                
                                </table>
                                <br><br>
                             </div>                          
                   
                   
                   
                   
                   
                   </div>            
                    
                    
                    	

				</div>
                
                
                
                
                
			</div>


</body>
</html>
<?php
 ob_flush();
?>