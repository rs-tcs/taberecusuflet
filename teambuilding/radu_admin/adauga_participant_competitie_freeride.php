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
<title>Adauga participant in competitie</title>
<link rel="stylesheet" type="text/css" media="all" href="css/adauga_participant_competitie.css" />
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
                 
                  <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
					</div>
                    <br>
                    <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="tabele_competitii.php">Tabele competiții </a>&nbsp;&nbsp;>></li>
                            <li><a href="detalii_tabel_competitie_freeride.php?tabelid=<?php echo $_GET['tbl_name'];?>&flag=<?php echo $_GET['flag']; ?>">Detalii Tabel Competiție</a>&nbsp;&nbsp;>></li>
                            <li class="active">Adaugă concurent</li>
                        </ul>                    
                    </div>
                    <br>
                    <?php
						if(isset($_GET['mesaj'])){
							
							$mesaj = unserialize(base64_decode($_GET['mesaj']));
							echo "<div class=\"eroare\">";						
							foreach($mesaj as $eroare){
								
								echo "<img src=\"pics/cross1.png\" />&nbsp;".$eroare."<br>";
								}
							echo "</div>";							
							}
					
					
					?>
                    <br>
                    <table id="tabel_participant" cellpadding="2px" cellspacing="2px">
                   
                    		<form action="update.php" method="post" >
                            	<tr>
                                       <td class="cap_tabel">Cod Unic</td>
                                       <td class="celule"><input type="text" name="cod_unic"/></td>                               
                                       
                                </tr>
                    			<tr>
                                       <td class="cap_tabel">Nume *</td>
                                       <td class="celule"><input type="text" name="numele" /></td>                               
                                       
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Prenume *</td>
                                       <td class="celule"><input type="text" name="prenumele" /></td>                               
                                       
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Vârsta *</td>
                                       <td class="celule"><input type="number" name="varsta" /></td>                               
                                       
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Cat. Vârstă *</td>
                                       <td class="celule"><select name="cat_varsta">
                                       					  <option value="">Alege categoria vârstă</option>
                                    <?php
									$query = "SELECT `categorii` FROM `cat_varsta` WHERE 1";
									$categorie = $db->execute($query);
									if(mysqli_num_rows($categorie) > 1){
										while($row = mysqli_fetch_array($categorie)){
									
											echo "<option value='{$row['categorii']}'>{$row['categorii']}</option>"; 
										}
									}
									?>
                                       					  </select>                                       
                                       </td>                               
                                       
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Sex *</td>
                                       <td class="celule"><select name="sex" />
                                       
                                       						<option value="">Alege sexul</option>
                                                            <option value="masculin">masculin</option>
                                       						<option value="feminin">feminin</option>
                                       					  </select>
                                       </td>                               
                                       
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Categorie concurs *</td>
                                       <td class="celule">
                                       <select name="categoria">
                                       		<option value="">Alege categoria concurs</option>                               
                                       		<option value="schi">Schi</option>			  
                                    		<option value="snowboard">Snowboard</option>									
                                       </select>
                                       </td>
                                </tr>
                                <tr>
                                		<td class="cap_tabel"><label for="experienta">Aveți experiență freeride ?*</label></td>
                                        <td class="celule"><textarea name="experienta" style="resize:none"></textarea></td>
                                </tr>
                                <tr>
                                		<td class="cap_tabel"><label for="adresa">Persoana de contact *</label> </td>
                                        <td class="celule"><textarea name="adresa" style="resize:none"></textarea></td>
                                </tr>                                
                                <tr>
                                       <td class="cap_tabel">Pachet vip</td>
                                       <td class="celule"><input type="checkbox" name="vip" value="vip"/></td>                               
                                       
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Mărime tricou</td>
                                       <td class="celule"><select name="tricou">
                                       					 <option value="">Fară tricou</option>	
                                      	<?php
															
															
										$query = "SELECT `marime` FROM `marime_tricou` WHERE 1";
									    $tricou = $db->execute($query);
									    if(mysqli_num_rows($tricou) > 1){
										  while($row = mysqli_fetch_array($tricou)){
									
											echo "<option value='{$row['marime']}'>{$row['marime']}</option>"; 
										}
									}
																				
										?>
                                       					  </select>
                                       </td>                               
                                       
                                </tr>
                                <tr>
                                		<td class="cap_tabel">Print Vip</td>
                                        <td class="celule"><input type="text" name="print_vip" /></td>
                                </tr>                                
                                <tr>
                                       <td class="cap_tabel">Număr concurs</td>
                                       <td class="celule"><input type="number" name="numar" /></td>                               
                                       
                                </tr>
                                <tr>
                                	   <td class="cap_tabel">Email</td>
                                       <td class="celule"><input type="email" name="email" /></td> 
                                </tr>
                                <tr>
                                       <td class="cap_tabel">Taxa</td>
                                       <td class="celule"><select name="taxa">
                                       					  <option value="">Alege starea plății</option>
                                         <?php
														
														
										$query = "SELECT `stare_plata` FROM `taxa` WHERE 1";
									    $taxa = $db->execute($query);
									    if(mysqli_num_rows($taxa) > 1){
										   while($row = mysqli_fetch_array($taxa)){
									
											echo "<option value='{$row['stare_plata']}'>{$row['stare_plata']}</option>"; 
										}
									}
																
										?>  
                                                         </select>
                                       </td>                               
                                       
                                </tr>
                                <tr>
                                	<td colspan="2">
                                    <input type="submit" name="submit" value="Adaugă participant" />
                                    <input type="hidden" name="tabel_competitie_freeride" value="<?php echo $_GET['tbl_name']; ?>" />
                                    <input type="hidden" name="flag" value="<?php echo $_GET['flag']; ?>" />
                                    </td>
                                </tr>
                    		</form>
                    </table>                   
                    <br><br>
                    
                  </div>
                    
                    
                    
                    
             </div>
             
   
   
   
</body>
</html> 
<?php
ob_flush();
?>      