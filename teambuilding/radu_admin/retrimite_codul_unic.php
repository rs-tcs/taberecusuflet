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
<title>Retrimite codul unic</title>
<link rel="stylesheet" type="text/css" media="all" href="css/retrimite_codul_unic.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
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
                        	<td  style="background-color:#0F87FF"><a href="retrimite_codul_unic.php">Retrimite codul</a></td>
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
                 
                  <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
					</div>	
                    <br>
                    
                    
                    <p> Caută cod unic </p>
                    
                    <div id="cautare_formular">
                    	<table id="cautare_tabel" cellpadding="3px" cellspacing="3px" style="border-collapse:separate; border-spacing: 5px;">
                        <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
                        	 <tr>
                             	<td class="celula2">    
                                    <input type="text" name="nume" value="Nume" id="nume" onBlur="blurNume();" onFocus="focusNume();"/>
                                </td>
                                <td class="celula2">    
                                    <input type="text" name="prenume" value="Prenume" id="prenume" onBlur="blurPrenume();" onFocus="focusPrenume();"/>
                                </td>
                             </tr> 
                             <tr>
                             	<td class="celula2">    
                                    <input type="text" name="data_nasterii" value="yyyy-mm-dd" id="data_nasterii" onBlur="blurDataNasterii();" onFocus="focusDataNasterii();"/>
                                </td>
                                <td class="celula2">    
                                    <input type="text" name="varsta" value="Varsta" id="varstaConcurent" onBlur="blurVarstaConcurent();" onFocus="focusVarstaConcurent();"/>
                                </td>
                             </tr>                                 	
                           
                            
                            <tr>
                             	
                                <td class="celule" colspan="4" style="text-align:center">
                                	<input type="submit" name="submit" value="Caută"/>
                                    <input type="hidden" name="trimis" value="ok"/>
                                </td>
                                
                                    
                            </tr>
                            
                        </form>
                        </table>
                        <br>
                    </div>
                    
                    <?php
					// validarea trimiterii prin post a datelor din tabelul de cautare
					if((isset($_POST['trimis'])) && ($_POST['trimis'] == "ok")){
						
						if((!empty($_POST['nume'])) && (!ctype_space($_POST['nume']))){
							
								$nume = str_replace(" ","",$_POST['nume']);
							}
							else
							{
								$nume = '';
							}
						if((!empty($_POST['prenume'])) && (!ctype_space($_POST['prenume']))){
							
								$prenume = str_replace(" ","",$_POST['prenume']);
							}
							else
							{
								$prenume = '';
							}
						if((!empty($_POST['data_nasterii'])) && (!ctype_space($_POST['data_nasterii']))){
							
								$data_nasterii = str_replace(" ","",$_POST['data_nasterii']);
							}
							else
							{
								$data_nasterii = '';
							}
						if((!empty($_POST['varsta'])) && (!ctype_space($_POST['varsta']))){
							
								$varsta = str_replace(" ","",$_POST['varsta']);
							}
							else
							{
								$varsta = '';
							}
						
						
						$query = "SELECT * FROM `inscrisi_teamexpert` WHERE  ";
						$query .= " `nume` = '".$nume."' AND `prenume` = '".$prenume."'";
						$query .= " AND `data_nasterii` = '".$data_nasterii."' AND `varsta` = '".$varsta."'";
						$query .= " LIMIT 0, 1";
							
							$rezultat = $db->execute($query);
						
							if($db->getCount($rezultat) == 0){
								
									echo "<div id=\"rezultat_nul\">Căutarea nu a găsit niciun rezultat!</div>";
									
								}
								else
								
								{ ?>
								
					<div class="continut">	      
                		<table id="main_content_data" >
                        	<form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
                    		
                            
                            	<?php
								while($value = $db->getObject($rezultat)){ ?>
                                
                              <tr class="cap_tabel">
                            	<td><label for="cod">Codul unic</label></td>                              
                              </tr> 
                              <tr class="rezultat">  
                            	<td><input type="text" name="cod_unic" value ="<?php echo $value['cod_unic']; ?>" readonly/> </td>
                                
							  </tr>
                              <tr class="cap_tabel">
                              	<td><label for="apartine">Codul aparține</label></td>
                              </tr>	
                              <tr class="rezultat">
                              	<td ><input type="text" name="numele" value="<?php echo  $value['nume']. ' ' .$value['prenume']; ?>" readonly /></td>
                              </tr>	
                              <tr class="cap_tabel">
                              	<td><label for="email">Email</label></td>
                              </tr>	
                              <tr class="rezultat">
                              	<td ><input type="text" name="email" value="<?php echo $value['email'];?>" readonly /></td>
                              </tr>	
                              <tr class="rezultat">
                                <td ><input type="submit" name="submit" value="Trimite Codul" />
                                    <input type="hidden" name="cod_trimis" value="ok" />
                                    <input type="hidden" name="cod" value="<?php  echo $value['cod_unic'];?>" />
                                    <input type="hidden" name="email" value="<?php echo $value['email'];?>"/>
                                    <input type="hidden" name="dataNasterii" value="<?php echo $value['data_nasterii'];?>"/>
                                    <input type="hidden" name="numele" value="<?php echo $value['nume']." ".$value['prenume'];?>"/>
                                </td>
                              </tr>
                              
								<?php }
                                 ?>                             
                            	
                                
                                
                            </form>
                        </table>
                      </div>        
							<?php	}
								
						
						}
					
					?>
                    
                
                
                <?php
					if((isset($_POST['cod_trimis'])) && ($_POST['cod_trimis'] == "ok")){
						
						$mail_cod = $_POST['cod']; 
						$numele = $_POST['numele'];
						$dataNasterii = $_POST['dataNasterii'];
						$email = $_POST['email'];
							
							// trimitem mail cu codul generat celui care a facut inregistrarea
									$cod_unic = base64_encode($mail_cod);
									$to = $email;
									$numeSender = "TeamXpert";
									$from = "office@teamexpert.ro";									
									$subiect = "Acest mesaj contine codul tau de inregistrare TeamXpert";
									
									
									// variabile ce se gasesc in textul mail-ului din tabelul din db
									
												
									$arrData = array(
														
										'cod'=>$mail_cod,										
										'numele'=>$numele,										
										'data_nasterii'=>$dataNasterii,
										'cod_unic'=>base64_encode($mail_cod)
									);
												
									function replace($i) {
													
  												GLOBAL $arrData;
												
  												return $arrData[$i[1]];
												return $arrData[$i[2]];
												return $arrData[$i[3]];
												return $arrData[$i[4]];											
																							
																							
									};
									
									$query = "SELECT `continut_email` FROM `mesaje_email` WHERE `flag_competitie` LIKE '%Mesaj confirmare competitie%'";
									$insert = "SET NAMES 'utf8'";
									$db->execute($insert);
									$result = $db->execute($query);
									$mail = $db->getAssoc($result);
												
									// variabila ce contine mail-ul din baza de date
									$mesaj = base64_decode($mail['continut_email']);
												
									// inlocuirea variabilelor din email-ul extras din baza de date
												
									$mesaj = preg_replace_callback('/{\$([^}]+)}/',"replace", $mesaj);
												
									
									$headers = "FROM: $numeSender <\"$from\"> \r\n";
									$headers.= "Reply-To:{$from} \r\n";
									$headers.= "CC: {$to} \r\n";
									$headers.= "Bcc: {$to} \r\n";
									$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
									$headers.= "MIME-Version: 1.0\r\n";
									$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
									$headers.= "Content-Transfer-Encoding: 8bit\n"; 
									
									$result=mail($to,$subiect,$mesaj,$headers);
								
								echo "<div class=\"succes\"><p>Codul a fost retrimis cu succes lui \"{$_POST['numele']}\"</p></div>";
							
							
							
							
						
						}
				
				?>
                
                    
                    
                    
                    
                    </div>
                    
				</div>
                
                
                
                
          

</body>
</html>
<?php
ob_flush();
?>