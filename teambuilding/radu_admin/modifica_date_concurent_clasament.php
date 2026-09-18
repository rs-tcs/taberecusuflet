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
<title>Modificare tabel competitie</title>
<link rel="stylesheet" type="text/css" media="all" href="css/modificare_date_concurent.css" />
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
                        	<li><a href="clasament_general.php">Clasament general </a>&nbsp;&nbsp;>></li>
                            <li><a href="detalii_clasament_general.php?tabel=<?php echo $_GET['tabel'];?>&gen=<?php echo $_GET['gen'];?>">Detalii tabel clasament</a>&nbsp;&nbsp;>></li>
                            <li class="active">Modifică date concurent</li>
                        </ul>                    
                   </div>
                    <br>
                    <?php
						if(isset($_GET['eroare'])){
							$mesaj = unserialize(base64_decode($_GET['eroare']));
							
							echo "<div class=\"eroare\">";						
							foreach($mesaj as $eroare){
								
								echo "<img src=\"pics/cross1.png\" />&nbsp;".$eroare."<br>";
								}
							echo "</div><br />";				
						}
					?>
                    <table id="tabel_competitie" cellpadding="2px" cellspacing="2px" >
                    	
                    		<tr class="cap_tabel">
                            <td colspan="2" style="font-size:14px">Modifică detalii concurent competiție "<?php echo $_GET['tabel'];?>"</td>
                    		</tr>
                    		
                    		<form action="update.php" method="post" > 
                            <?php
								if((isset($_GET['id'])) && (isset($_GET['tabel']))){
									
									$tabel = $_GET['tabel'];
									$concurent = $_GET['id'];
									
									$query = "SELECT * FROM  `".$tabel."`  WHERE `id` = '".$concurent."'";
									
									$select = "SET NAMES 'utf8'";
									$db->execute($select);
									$result = $db->execute($query);
									if($result){
										
										while($row = $db->getObject($result)){
											
										 ?>
                                         
                                     <tr >
                            			<td class="cap_tabel">Id</td>
                                        <td class="celule"><input type="text" name="id" 
                                        value="<?php echo $row['id'];?>" readonly/>
                                        </td>
                                     </tr>
                                     <tr>
                                       <td class="cap_tabel">Cod Unic</td>
                                       <td class="celule"><input type="text" name="cod_unic" 
                                        value="<?php echo $row['cod_unic'];?>" readonly />
                                       </td>
                                     </tr>
                               		 <tr>
                                     	<td class="cap_tabel">Nume</td>
                                     	<td class="celule"><input type="text" name="numele" 
                                        value="<?php echo $row['numele'] ;?>" required />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Prenume</td>
                                     	<td class="celule"><input type="text" name="prenumele"
                                         value="<?php echo $row['prenumele'];?>" required />
                                        </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Categorie vârstă</td>
                                     	 <td class="celule">
                                         <select name="cat_varsta" required>
                                         	<option value="">Selectați categoria</option>
                                         <?php						
																				
                                         	$query = "SELECT `categorii` FROM `cat_varsta` WHERE 1";
											$result = $db->execute($query);										
											while($cat = mysqli_fetch_array($result)){												
												
											?>
												
											<option value="<?php echo $cat['categorii'] ;?>" 
											<?php if($row['categoria'] == $cat['categorii']) { ?> selected="selected" <?php }?>>
											<?php echo $cat['categorii'] ;?>
                                            </option>											
											<?php 											
											
											} //sfarsit while
                                         
                                         ?>
                                         
                                         </select>           
                                                         
                                       
                                        </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Sex</td>
                                     	<td class="celule">
                                        <select name="sex" required >
                                        	<option value="">Alege sexul</option>
                                        	<?php
											$query = "SELECT `sexul` FROM `sex` WHERE 1";
											$result = $db->execute($query);
											while($cat = mysqli_fetch_array($result)){ ?>
												
											<option value="<?php echo $cat['sexul']; ?>" 
											<?php if($row['sex'] == $cat['sexul']){?> selected="selected"<?php } ?>>
                                            <?php echo $cat['sexul']; ?>
                                            </option>	
												
											<?php	}
                                        
                                        	?>
                                        </select>
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Locul</td>
                                        <td class="celule"><input type="text" name="locul" 
                                        value="<?php echo $row['locul'];?>"  required/>
                                        </td>
                                     
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Rezultat</td>
                                        <td class="celule"><input type="text" name="rezultat" 
                                        value="<?php echo $row['rezultat'];?>"  required/>
                                        </td>
                                     
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Competiții</td>
                                        <td class="celule"><textarea name="competitii" required><?php echo $row['competitii'];?></textarea>                                       
                                        </td>
                                     
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Participari</td>
                                        <td class="celule"><input type="text" name="participari" 
                                        value="<?php echo $row['participari'];?>"  required/>
                                        </td>
                                     
                                     </tr>           
                                   
											<?php 	}// sfarsit while										
										
										} // sfarsit $result
										
									}// sfarsit isset							
							?>
                            		<tr class="celule">
                                			<td colspan="2">
                                        <input type="submit" name="submit" value="Modifică date concurent" />
                                    	<input type="hidden" name="modifica_date_clasament" value="ok" />
                                        <input type="hidden" name="numeTabel" value="<?php echo $tabel; ?>" />
                                        <input type="hidden" name="idConcurent" value="<?php  echo $concurent; ?>" />
                                        <input type="hidden" name="gen" value="<?php  echo $_GET['gen']; ?>" />      
                                   			 </td>
                               		 </tr>
                                     
                    			</form>				
                            	
                    </table>
                   
                  </div>
                  
           </div>  
		
</body>
</html>
<?php
ob_flush();
?>