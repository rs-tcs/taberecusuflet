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
<title>Tabele Competitii</title>
<link rel="stylesheet" type="text/css" media="all" href="css/tabele_competitii.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/functii.js" ></script>
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
                    		<td style="background-color:#0F87FF"><a href="tabele_competitii.php">Tabele competiții</a></td>
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
                    <div id="text">
                    <?php echo "Tabel competiții "; ?>
             	    </div>
              		<br>
                    <script type="text/javascript">
					function confirmPost(){
					
						var agree = confirm("Ești sigur că vrei să ștergi această competiție?");
						
						if(agree){
						
						return true ;
						}
						else
						{
						return false ;
						}
						}
					</script>
                 	<?php
						if((isset($_GET['mesaj']))  && (isset($_GET['tip']))){
							
							
							if($_GET['tip'] == 1)
							
							echo "<div class=\"mesaj\" style=\"color:green\"><img src=\"pics/tick1.png\" />&nbsp;&nbsp;{$_GET['mesaj']}</div>";
							
							
							if($_GET['tip'] == 0)
							
							echo "<div class=\"mesaj\" style=\"color:red\"><img src=\"pics/cross1.png\" />&nbsp;&nbsp;{$_GET['mesaj']}</div>";
								
							}
					
					?>
                    	<br />
                        <!-- selectul care sorteaza anii competitionali -->
                        <form method="post" id="selAnComp" action="<?php $_SERVER['PHP_SELF'];?>">
                        	<select id="anComp" onChange="location.href=selAnComp.anComp.options[selectedIndex].value;">
                            		<option value="?">Selectați anul competițional</option>
                            	<?php
									$anCurent = date('Y');
									for($i=2014; $i <= $anCurent; $i++){
									?>
                                   <option value="?anComp=<?php echo $i ;?><?php if(isset($_GET['pagina'])){echo "&pagina=".$_GET['pagina'];}else{} ?>" <?php echo (isset($_GET['anComp']) == $i)? "selected" : "";?>><?php echo $i; ?></option>
                                    <?php	
									}
								?> 
                        	</select>
                        </form>
                        <br />
                 		 <table id="main_content_data" cellpadding="2px" cellspacing="2px">
                         		                         
                         	<tr class="cap_tabel">
                            	
                                <td>Nume competiție</td>
                                <td>Tipul competiției</td>
                                <td>Flag-ul competiției</td>
                                <td>Data desfășurării competiției</td>
                                <td>Data adăugării competiției</td>
                                <td>Termen Limita Personalizare</td>
                                <td>Activ</td>
                                <td>Vizualizare</td>
                                <td>Șterge competiție</td>
                            </tr>
                            
                            
                            <?php
							
							// paginatie
							
							
							$afisari_pagina = 50;
		  					$query = "SELECT `id` FROM `tabel_competitii` WHERE 1 ";
		   					$result = $db->execute($query);						 				 
		  					$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  					$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  					$start = ($pagina - 1) * $afisari_pagina;
							
							if(isset($_GET['anComp'])){
							$query = "SELECT * FROM `tabel_competitii` WHERE 
							DATE_FORMAT(`data_competitiei`, '%Y') = '".$_GET['anComp']."' 
							ORDER BY `data_competitiei` desc LIMIT $start, $afisari_pagina";	
								
							}
							else
							{
							$an = date('Y');	
							$query = "SELECT * FROM `tabel_competitii` WHERE DATE_FORMAT(`data_competitiei`, '%Y') = '".$an."' ORDER BY `data_competitiei` desc LIMIT $start, $afisari_pagina";
							}
							
							$rezultat = $db->execute($query);
							
							if($db->getCount($rezultat) >= 1){
								
							
							while($row = mysqli_fetch_array($rezultat)){
							
							if($row['activ'] == 1){
							?> 
                            
                            <tr class="celule">                            
                            	
                                <td><?php echo $row['nume_competitie'] ; ?></td>
                                <td><?php echo $row['tip_competitie'] ; ?></td>
                                <td><?php echo $row['flag'] ; ?></td>
                                <td>
                                	<form method="post" action="update.php">
                                	  <input type="text" name ="data_competitiei" value="<?php echo $row['data_competitiei'] ; ?>" style="width:75%; text-align:center" /><br />
                                       <input type="submit" name="dataCompetitiei" value="Modifică" style="font-size:9px; text-align:center" />  
                                       <input type="hidden" name="id_tabel" value="<?php echo $row['id'] ; ?>"/>                             		   <input type="hidden" name="nume_competitie" value="<?php echo $row['nume_competitie']; ?>" />
                                	</form>                 
                                
                                </td>
                                <td><?php echo $row['data_adaugarii_competitiei'] ; ?></td>
                                <td>
                                	<form method="post" action="update.php">
                                	  <input type="text" name ="dataLimitaPersonalizare" value="<?php echo $row['dataLimitaPersonalizare'] ; ?>" style="width:75%; text-align:center" /><br />
                                       <input type="submit" name="limita_personalizare" value="Modifică" style="font-size:9px;" />  
                                       <input type="hidden" name="id_tabel" value="<?php echo $row['id'] ; ?>"/>                             		   <input type="hidden" name="nume_competitie" value="<?php echo $row['nume_competitie']; ?>" />
                                	</form>                         
                                
                                </td>
                                <!-- butonul de update stare tabel -->
                                <td >
									<form method="post" action="update.php">
                                	  <input type="text" name ="number" value="<?php echo $row['activ'] ; ?>" style="width:13px; text-align:center" />
                                       <input type="submit" name="schimba_starea" value="Modifică" style="font-size:9px"/>  
                                       <input type="hidden" name="id_tabel" value="<?php echo $row['id'] ; ?>"/>                             		   <input type="hidden" name="nume_competitie" value="<?php echo $row['nume_competitie']; ?>" />
                                       <input type="hidden" name="flag" value="<?php echo $row['flag']; ?>" />
                                	</form>
                                </td>
                                
                                <?php
								// afisarea dinamica a butonului vizualizare tabel
								
									switch ($row['flag']){
										
										case "Comana Bike Fest":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";     									
										break;
										
										case "On Top of the World":
										
	echo "<td><a href=\"detalii_tabel_competitie_sinaia.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Tabere cu Suflet":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Malinului":
										
	echo "<td><a href=\"detalii_tabel_competitie_freeride.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Maraton Tabere cu Suflet":
										
	echo "<td><a href=\"detalii_tabel_competitie_maraton.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Tabere cu Suflet Ski":
										
	echo "<td><a href=\"detalii_tabel_competitie_tabere_ski.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa 1 Iunie":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Duatlon Tabere cu Suflet":
										
	echo "<td><a href=\"detalii_tabel_competitie_duatlon.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Veseliei":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
                               
										}
								?>
                                
                                <!-- butonul de stergere tabel -->
                               <td>
                               <form method="post" action="update.php" > 
                                <input type="submit" name="submit" onClick="return confirmPost();" value="Șterge tabel" />
                                <input type="hidden" name="tbl_id" value="<?php echo $row['id'] ;?>" />
                                <input type="hidden" name="tabel" value="<?php echo $row['nume_competitie'] ;?>" />
                                <input type="hidden" name="flag" value="<?php echo $row['flag']; ?>" />
                                </form>
                                
                                </td>
                            
                            </tr>
                          	
                            <?php
							}
							else
							
							// afisarea randului in culoare diferita
							{
							?>
								
								<tr class="dark">                            
                            	
                                <td><?php echo $row['nume_competitie'] ; ?></td>
                                <td><?php echo $row['tip_competitie'] ; ?></td>
                                <td><?php echo $row['flag'] ; ?></td>
                                <td><?php echo $row['data_competitiei'] ; ?></td>
                                <td><?php echo $row['data_adaugarii_competitiei'] ; ?></td>
                                <td ><?php echo $row['dataLimitaPersonalizare'] ; ?></td>
                                <!-- butonul de update stare tabel -->
                                <td>
									<form method="post" action="update.php">
                                	  <input type="text" name ="number" value="<?php echo $row['activ'] ; ?>" style="width:13px; text-align:center" />
                                       <input type="submit" name="schimba_starea" value="Modifică"  style="font-size:9px"/>  
                                       <input type="hidden" name="id_tabel" value="<?php echo $row['id'] ; ?>"/>                             		   <input type="hidden" name="nume_competitie" value="<?php echo $row['nume_competitie']; ?>" />
                                	</form>
                                </td>
                                
                                <?php
								// afisarea dinamica a butonului vizualizare tabel
									switch ($row['flag']){
										
										case "Comana Bike Fest":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";     									
										break;
										
										case "On Top of the World":
										
	echo "<td><a href=\"detalii_tabel_competitie_sinaia.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Tabere cu Suflet":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Malinului":
										
	echo "<td><a href=\"detalii_tabel_competitie_freeride.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Maraton Tabere cu Suflet":
										
	echo "<td><a href=\"detalii_tabel_competitie_maraton.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Tabere cu Suflet Ski":
										
	echo "<td><a href=\"detalii_tabel_competitie_tabere_ski.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa 1 Iunie":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";
										break;
										
										case "Duatlon Tabere cu Suflet":
										
	echo "<td><a href=\"detalii_tabel_competitie_duatlon.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
										case "Cupa Veseliei":
										
	echo "<td><a href=\"detalii_tabel_competitie_bikefest.php?tabelid={$row['nume_competitie']}&flag={$row['flag']}\"><button>Vizualizare</button></a></td>";		
										break;
										
                               
										}
								?>
                              	<!-- butonul de stergere tabel -->
                               <td>
                               <form method="post" action="update.php" > 
                                <input type="submit" name="submit" onClick="return confirmPost();" value="Șterge tabel" disabled/>
                                <input type="hidden" name="tbl_id" value="<?php echo $row['id'] ;?>" />
                                <input type="hidden" name="tabel" value="<?php echo $row['nume_competitie'] ;?>" />
                                </form>
                                
                                </td>
                            
                            </tr>
								
							<?php	
							}
							}
							}
							else
							{ ?>
                            	<tr>
                                	<td colspan="9">
                                    <?php echo "<div class=\"eroare\" style=\"margin:auto\"><img src=\"pics/cross1.png\"/>Nu ai nicio competiție în derulare</div>";  ?>
                                    </td>
                                </tr>
							<?php }
                        	?>
                         </table> 
                 		 <br><br>
                         
                         
                         <!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ 
								 ?>
               
               			<div class="page_number">	
				    		<?php
							
							if(isset($_GET['anComp'])){
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina='.$x.'&anComp='.$_GET['anComp'].'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina= '.$x.'&anComp='.$_GET['anComp'].'" >'.$x.'</a> ';
							}
							else
							{
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina= '.$x.'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina= '.$x.'"  >'.$x.'</a> ';
							}
							?>
				   		</div>
						<?php 
						  }
			   	
			 			  }
				
						  ?>
                          
                        </div>

      				 </div>	
                     
                     <br><br>            
                         
                 </div>
                
                
                
                
                
         </div>

				
</body>
</html>

<?php
ob_flush();
?>