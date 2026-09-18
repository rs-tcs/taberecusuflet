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
<title>Newsletter</title>
<link rel="stylesheet" type="text/css" media="all" href="css/trimite_newsletter.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/floating-1.12.js" ></script>



<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>



			<div id="page">
        		<div id="text_top">
                	<p>Zona de administrare <b style="color:darkblue">Team</b><b style="color: #F00000">Xpert</b> Race Club</p
                
                ></div>
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
   					 var originalelpos=el.offset().top; // take it where it originally is on the page

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
                    		<td style="text-align:center;"><a href="baza_date.php">Home</a></td>
                        </tr>
                    	<tr>
                    		<td><a href="adauga_participant_baza.php">Adaugă participant</a></td>
                        </tr>
                        <tr>
                        	<td style="background-color:#0F87FF"><a href="creeaza_newsletter1.php" >Creează newsletter</a></td>
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
                
                <!-- vizualizare informatii tabel newsletter -->
               
                
                <?php
                if((isset($_POST['trimis_date'])) && ($_POST['trimis_date'] == "ok")){ ?>
                
                	<div class="vizualizare">
                    	
					<?php	
					
							if((!empty($_POST['nume_folder'])) && (!ctype_space($_POST['nume_folder']))){
								
								$data['nume_folder'] = $_POST['nume_folder'];
								}
								else
								{
								$errors[] = "Nu ai completat numele folderului";
								}
					
							if((!empty($_POST['nume_sablon'])) && (!ctype_space($_POST['nume_sablon']))){
								
								$data['nume_sablon'] = $_POST['nume_sablon'];
								}
								else
								{
								$errors[] = "Nu ai completat numele șablonului";
								}
															
							if((!empty($_POST['editor'])) && (!ctype_space($_POST['editor']))){
								
								$data['editor'] = base64_encode(stripslashes($_POST['editor']));
								
								}
								else
								{
								$errors[] = "Nu ai completat codul newsletter-ului";
								}
								
								
								$data['activ'] = 1;	
								
							//executarea insertului in baza de date	
							if(!isset($errors)){
								
								
								// se seteaza neactive newsletter-ele existente din sitem
								
								$select = "UPDATE `sabloane_newsletter` SET `activ` = 0 WHERE  `activ` = 1";
								$result = $db->execute($select);
								
								
								// creez directorul si pagina newsletter-ului in directorul newsletter
								
									
								$root_folder = "../newsletter/";
								$dir_newsletter = mkdir($root_folder.$_POST['nume_folder']."/", 0777, true);
									
									
								$file_news = $root_folder.$_POST['nume_folder']."/".$_POST['nume_sablon'].".php";
								$file = fopen($file_news, 'w') or die("Nu pot scrie în pagina creată");
								fwrite($file,$_POST['editor']);
								fclose($file);
								
								
								
								
								// introducerea datelor in baza de date
									$data['uniq_id'] = uniqid();
								
									if(is_array($data)){
									$tabel = "sabloane_newsletter";
									$campuri = '';
									$valori = '';
									foreach($data as $key=>$valoare){
									
									$campuri .= $key . ", ";	
									$valori .= (is_numeric($valoare)) ? $valoare . ", " : "'" . $valoare . "', ";	
										}
										
									$campuri = substr($campuri,0,-2);
									$valori = substr($valori,0,-2);
									$query = "Insert Into   " .  $tabel . "(" . $campuri . ") Values (" . $valori . ")";
									
									$result = $db->execute($query);							
									
									
									
									if($result){  	
                                                  
                                        
                                       
										header("Location: editeaza_newsletter.php");
										
										
                                    
									 }
									 else
									 {
										 					
                                        
                                        
										$errors[] = "Nu am putut salva newsletter-ul" ;
                                        ?>	
                                        
                                        
										</div>
                                        <?php
									  }
									
									
									
									}
								
								
								
								
								
							}
								
							// generarea erorilor	
							if(isset($errors)){
								
								echo "<div class=\"eroare\">";
							foreach($errors as $eroare){
								echo "<img src=\"pics/cross1.png\"/> {$eroare} <br>";
								
								}
								echo "</div>";
								echo "<input type=\"button\" value=\"Mergi înapoi\"  onclick=\"history.go(-1);\" style=\" margin:auto; position:relative; left:450px\" />";
								
							} ?>
                            							
					</div>			
				<?php }
				 
                ?>
               <!-- sfarsit functie verificare informatii newsletter -->         		
				
               
               	
                       
                
                
                                
                 
                
                 <!-- afisare tabel de introducere a textului newsletter-ului --> 
               <?php 
                if(!isset($_POST['trimis_date'])){ ?>
				
                <div id="date_newsletter">
                <div id="admin">
                 	<?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                </div>
                <br>
                	<p>Introdu datele pentru newsletter-ul tău!</p>
                	
						
                	<table id="tabel_date_newsletter" cellpadding="2px" cellspacing="3px">
                    
                    	<form action="<?php $_SERVER['PHP_SELF'] ; ?>" method="post" >
                        	<tr>
                            	<td class="celula1"><label for="nume_folder">Nume folder</label> </td>
                                <td class="celula2"><input type="text" name="nume_folder" required /></td>
                            </tr>
                        	<tr>
                            	<td class="celula1"><label for="nume_sablon">Nume șablon</label> </td>
                                <td class="celula2"><input type="text" name="nume_sablon" required /></td>
                            </tr>
                        	
                            <tr>
                            	<td class="celula1" colspan="2"><label for="text">Introdu aici Codul Newsletter-ului</label> </td>
                            </tr>
                            <tr>
                            	<td class="celula3" colspan="2"><textarea name="editor" class="editor" required ></textarea></td>					 
                            </tr>
                            
                             <tr>
                            	<td colspan="2">
                                	<input type="submit" name="vizualizeaza" value="Salvează informațiile" />
                                	<input type="reset" name="reset" value="Resetează"/>
                                    <input type="hidden" name="trimis_date" value="ok" />
                                    
                                </td>
                                
                            </tr>
                        
                    	</form>
                        			
                       </table> 
                       <br>          
                	                
                </div>
                          
               <?php
				}
				?>
                
                
               
           </div>                 	 
                      
</body>
</html>
<?php
ob_flush();
?>