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
<title>Detalii clasament general</title>
<link rel="stylesheet" type="text/css" media="all" href="css/detalii_tabel_competitie.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/functii.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(e) {   
	
		$(window).load(function(){
		
			$('#animation').fadeOut(500);
		});	
	});	
	
	
		
</script>
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
                    
               
                 <!-- meniul navigare stanga -->
                 <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center"><a href="baza_date.php"><div class="text">Home</div></a></td>
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
                
                
                
                
                
                
                <!-- continutul  paginii, popularea cu informatiile din pagina -->  
                <div id="continut">
                <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
				</div>
                <br>	
                <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="clasament_general.php">Clasament general</a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii clasament general</li>
                        </ul>                    
                 </div>
                 <br>
                 
                 <div id="text">
                    <?php echo "Tabel clasament general<br>".ucfirst(str_replace("_"," ",$_GET['tabel'])); ?>
                 </div>
                 <br>
               
                
                <!-- scriptul pentru slide-ul meniului stanga la scroll-ul paginii -->
                
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
                
                <!-- scriptul pentru validarea comenzii delete -->
                <script type="text/javascript">
					function confirmPost(){
				
						var agree = confirm("Ești sigur că vrei să ștergi acest participant?");
						if(agree){
							return true;
							}
							else
							{
							return false;	
							}
					}
				</script>
               
                
                <!-- modulul cu cautarea avansata-->
                
                <div id="cautare_avansata">             
                    <div id="cautare_formular">
                    	<input type="button" id="buton" value="Căutare avansată" onClick="vizibilitate();" />
                   		 <br><br>
                         
                    	<table id="cautare_tabel" cellpadding="2px" cellspacing="2px" style="border-collapse:separate; border-spacing: 5px;">
                        <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
                        	<tr>
                            	<td class="celule">    
                                   <input type="text" name="nume" id="nume" value="Nume" onBlur="blurNume();" onFocus="focusNume();"/>
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="prenume" id="prenume" value="Prenume" onBlur="blurPrenume();" onFocus="focusPrenume();"/>
                                </td>
                            </tr>
                            <tr>
                            	<td class="celule">    
                                   <input type="text" name="sex" id="sex" value="Sex" onBlur="blurSex();" onFocus="focusSex();"/>
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="punctaj" id="punctaj" value="Punctaj" onBlur="blurPunctaj();" onFocus="focusPunctaj();"/>
                                </td>
                            </tr>
                            <tr>
                            	<td class="celule">
                                    <input type="text" name="cat_varsta" id="varsta" value="Categoria de vârstă" onBlur="blurVarsta();" onFocus="focusVarsta();" />
                                </td>                                
                                
                                <td class="celule">
                                    <input type="text" name="cod" id="cod" value="Cod unic" onBlur="blurCod();" onFocus="focusCod();"/>
                                </td>                             
                             </tr>                               
                             <tr>
                             	
                                <td class="celule" colspan="2" style="text-align:center">
                                	<input type="submit" name="submit" value="Caută"/>
                                    <input type="hidden" name="trimis_cautare" value="ok"/>
                                    <input type="hidden" name="nume_tabel" value="<?php  echo $_GET['tabel']; ?>"/>
                                </td>
                                
                                    
                            </tr>
                            
                        </form>
                        </table>
                        <br>
                     </div>                
                   </div> 
                  
                   
                   
                   <div id="adauga_participant">               	
                    
										
                    <a href="export_clasament_general.php?numeTabel=<?php echo $_GET['tabel']; ?>" target="_blank"><button>Exporta Tabel Clasament CSV</button></a>                   
                    
                    &nbsp;
                    <script type="text/javascript">
						function curentUrl(){				
                        var url = window.location.href;
						return url;
						}
                    	
					</script>
                    <?php
					// afisarea butonului 'Update clasament'
					
					if(isset($_GET['gen']) && ($_GET['gen'] !== 'general')){
						if (isset($_GET['update']) && ($_GET['update'] == 'update')){
					?>
                    <button value="update" disabled>Clasament updatat</button>
                    
                    <?php
						}
						else
						{
					?>
                    <button id="update" value="update" onClick="updateClasament(this.value,  curentUrl());">Update Clasament</button> 
                    <?php	
						}
					}
					?>
					
                    <?php
					
					if(isset($_GET['update']) && ($_GET['update'] == 'update')){
						
						// se construieste ordinea clasamentului
							$Y = date('Y');
							$select = "SELECT `cod_unic` FROM `General$Y` WHERE `sex` = '".$_GET['gen']."' ORDER BY `rezultat` desc, `locul` asc";
							$result = $db->execute($select);
	
							$clasGeneral = array();
								if($result){
									while($row = $db->getObject($result)){
			
										array_push($clasGeneral, $row['cod_unic']);
									}	
		
								}
							
							// se sterg toate datele din tabel pentru a reintroduce informatiile actualizate
							$checkTable = "SHOW TABLES LIKE 'General$Y{$_GET['gen']}'";
							
							if($db->execute($checkTable)){
								
							$truncate = "TRUNCATE TABLE `General$Y{$_GET['gen']}`";
							$db->execute($truncate);
													
							}
							
							// se introduc informatiile cu noile id-uri
							for($i=0; $i < count($clasGeneral); $i++){
										
								$select = "SELECT `cod_unic` FROM `General$Y{$_GET['gen']}` WHERE `cod_unic` = '".$clasGeneral[$i]."'";
								$resSelect = $db->execute($select);
									if($db->getCount($resSelect) == 0){
		
								    	 $insert = "INSERT INTO `General$Y{$_GET['gen']}` (`id`,`cod_unic`)
										 VALUES ('','".$clasGeneral[$i]."')";			 
										 $result = $db->execute($insert);
										 
										$update = "UPDATE `General$Y{$_GET['gen']}` LEFT JOIN `General$Y` ON `General$Y{$_GET['gen']}`.cod_unic = `General$Y`.cod_unic SET `General$Y{$_GET['gen']}`.locul = `General$Y`.locul WHERE `General$Y{$_GET['gen']}`.cod_unic = `General$Y`.cod_unic";
										 $db->execute($update);	
										 
										 
												 
										 
									}

							}
									
							// se updateaza tabelul cu restul informatiilor din tabelul `clasament general`
			
					$query = "UPDATE `General$Y{$_GET['gen']}` LEFT JOIN `General$Y` ON					 																																																																		                    `General$Y{$_GET['gen']}`.cod_unic = `General$Y`.cod_unic SET 
					`General$Y{$_GET['gen']}`.numele = `General$Y`.numele, 
                    `General$Y{$_GET['gen']}`.prenumele = `General$Y`.prenumele,
                    `General$Y{$_GET['gen']}`.sex = `General$Y`.sex,
                    `General$Y{$_GET['gen']}`.categoria = `General$Y`.categoria,
                    `General$Y{$_GET['gen']}`.rezultat = `General$Y`.rezultat, 
                    `General$Y{$_GET['gen']}`.competitii = `General$Y`.competitii,
					`General$Y{$_GET['gen']}`.participari = `General$Y`.participari,
					`General$Y{$_GET['gen']}`.puncte_bonus = `General$Y`.puncte_bonus,
					`General$Y{$_GET['gen']}`.bonus = `General$Y`.bonus
                     WHERE 1";	
                     $result = $db->execute($query);	 			
						
					}
					
					?>                                          
               		</div> 
                    
                	<?php     
                    // paginatia paginii
						
						if(!isset($_GET['sex'])){
							
						$afisari_pagina = 75;
		  				$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE 1";
		   				$result = $db->execute($query);										 				 
		  				$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  				$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  				$start = ($pagina - 1) * $afisari_pagina;
						
						}
						else
						{
							
						$afisari_pagina = 75;
		  				$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `sex` = '".$_GET['sex']."'";
		   				$result = $db->execute($query);										 				 
		  				$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  				$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  				$start = ($pagina - 1) * $afisari_pagina;
							
						}
					?>
                  
                   <!-- date despre competitie si participanti -->
                  
                  	<div id="date_competitie">                    
                   
                    <br>
					<table id="cat_varsta" cellpadding="2px" cellspacing="2px">
                    	<tr>
                        	<th>Piticoți</th>
                        	<th>Pitici</th>
                            <th>Copii</th>
                            <th>Spiriduși</th>
                            <th>Uriași</th>
                            <th>Tineri</th>
                            <th>Adulți</th>
                            <th>Seniori</th>
                            <th>Forever_Young</th>
                    	</tr>
                        <tr>
                        	<td>
                            	<?php
								// piticoti
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"piticoti\"";
								$result = $db->execute($query);
								$piticoti = $db->getCount($result);
								echo $piticoti;			
                            	?>
                            </td>
                        	<td>
                            	<?php
								// pitici
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"pitici\"";
								$result = $db->execute($query);
								$pitici = $db->getCount($result);
								echo $pitici;			
                            	?>
                            </td>
                            	
                            <td>
                            	<?php
								// copii
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"copii\"";
								$result = $db->execute($query);
								$copii = $db->getCount($result);
								echo $copii;			
                            	?>
                            </td>
                            <td>
                            	<?php
								// spiridusi
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"spiridusi\"";
								$result = $db->execute($query);
								$spiridusi = $db->getCount($result);
								echo $spiridusi;			
                            	?>
                            </td>
                            <td>
                            	<?php
								// uriasi
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"uriasi\"";
								$result = $db->execute($query);
								$uriasi = $db->getCount($result);
								echo $uriasi;			
                            	?>                            
                            </td>
                            <td>
                            	<?php
								// tineri
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"tineri\"";
								$result = $db->execute($query);
								$tineri = $db->getCount($result);
								echo $tineri;			
                            	?>                
                            
                            </td>
                            <td>
                            	<?php
								// adulti
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"adulti\"";
								$result = $db->execute($query);
								$adulti = $db->getCount($result);
								echo $adulti;			
                            	?>            
                            
                            </td>
                            <td>
                            	<?php
								// seniori
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"seniori\"";
								$result = $db->execute($query);
								$seniori = $db->getCount($result);
								echo $seniori;			
                            	?>            
                            </td>
                            <td>
                            	<?php
								// forever_young
								$query = "SELECT `id` FROM `".$_GET['tabel']."` WHERE `categoria` = \"forever_young\"";
								$result = $db->execute($query);
								$forever_young = $db->getCount($result);
								echo $forever_young;			
                            	?>            
                            </td>
                        </tr>
                    </table>
					
					
                	
                    
                    
                 </div>
                 <br>
                 
                 <!-- afisarea mesajelor venite prin $_GET -->
                 <?php
				 		if(isset($_GET['succes'])){
						
						$mesaj = base64_decode($_GET['succes']);
						
						echo "<div class=\"succes\"><img src=\"pics/tick1.png\" />&nbsp;{$mesaj}</div>";
						
						}
						
						if(isset($_GET['eroare'])){
						
						$mesaj = base64_decode($_GET['eroare']);
						
						echo "<div class=\"eroare\"><img src=\"pics/cross1.png\" />&nbsp;{$mesaj}</div>";
						
						}
						
						
					if((isset($_GET['tabel'])) && (!isset($_POST['trimis_cautare']))){
							
						$comp = $_GET['tabel']; 
				 		$Y = date('Y');
				 
				 ?> 
                 	<!-- afisarea animatiei de incarcare a paginii -->	
                  	<div id="animation"><img src="pics/ajax-loader.gif" /><br /> Se proceseaza informația</div>
                    
                    <!-- afisarea tabelului in prima apelare a paginii fara criterii de ordonare -->	
                    <table id="date_tabel_competitie" cellpadding="2px" cellpadding="2px">
                    		
                            <tr class="cap_tabel">
                            	<td colspan="14" style="font-size:14px">Modifică detalii concurenți "<?php echo $_GET['tabel'];?>"</td>
                    		</tr>
                    		<tr class="cap_tabel">
                            	<td>Id</td>
                                <td>Cod Unic</td>
                                <td>Nume</td>
                                <td>Prenume</td>
                                <td>Sex</td>
                                <td>Categorie vârstă</td>
                                <td>Locul</td>                              
                                <td>Puncte</td>                                
                                <td style="max-width:400px;">Competiții</td> 
                                <td>Participări</td>
                                <td>Puncte bonus</td> 
                                <td>Bonus</td> 
                                <?php
								if($_GET['gen'] == 'general'){
								?>                              
                                <td><a href="modificare_tabel_clasament_general.php?tabel=<?php echo $_GET['tabel']?><?php if(isset($_GET['pagina'])){echo "&pagina=".$_GET['pagina'];}?>&gen=<?php echo $_GET['gen'];?>"><button <?php echo ($_GET['gen'] !==  'general')? 'disabled' : '' ;?>>Editează</button></a></td>                                
                                <td>Elimină</td>
                               <?php
								}
								?>
                            </tr>
                	        
                   			 <?php
							
							if(!isset($_GET['sex'])){
							$query = "SELECT * FROM   `".$comp."` WHERE 1 ORDER BY `id` asc LIMIT $start, $afisari_pagina";
							}
							else
							{
							
							$query = "SELECT * FROM   `".$comp."` WHERE 1 ORDER BY `locul` asc LIMIT $start, $afisari_pagina";	
							}
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$result = $db->execute($query);
							
							if($result){
							if($db->getCount($result) <> 0){
							while($row = mysqli_fetch_array($result)){ ?>
                            
							<tr class="celule">
                            	<td>
                                <?php
								
								echo $row['id'] ;                              
								
                                ?>

                                </td>
                                <td><?php echo $row['cod_unic'] ;?></td>
                                <td><?php echo $row['numele'] ;?></td>
                                <td><?php echo $row['prenumele'] ;?></td>
                                <td><?php echo $row['sex'] ;?></td>
                                <td><?php echo $row['categoria'] ;?></td>
                                <td><?php echo $row['locul'] ;?></td>
                                <td><?php echo $row['rezultat'] ;?></td>                                
                                <td style="text-align:left; max-width:350px"><?php echo $row['competitii'] ;?></td> 
                                <td><?php echo $row['participari'] ;?></td> 
                                <td><?php echo $row['puncte_bonus'] ;?></td> 
                                <td><?php echo $row['bonus'] ;?></td>
                                <?php
								if($_GET['gen'] == 'general'){
								?>                              
                                <td><a href="modifica_date_concurent_clasament.php?id=<?php echo $row['id'];?>&tabel=<?php echo $_GET['tabel'] ;?>&gen=<?php echo $_GET['gen'];?>"><button <?php echo ($_GET['gen'] !==  'general')? 'disabled' : '' ;?>>Detalii</button></a></td>                               
                            	<td>
                                	<form action="update.php" method="post">
                                    	<input class="button" type="submit" name="sterge_concurent" value="Șterge" onClick="return confirmPost();" />
                                        <input class="button" type="hidden" name="id_concurent" value="<?php echo $row['id'] ;?>" />
                                        <input class="button" type="hidden" name="tabel" value="<?php echo $_GET['tabel'] ?>" />
                                        <input class="button" type="hidden" name="gen" value="<?php echo $_GET['gen'] ?>" />
                                    </form>
                                </td> 
                            	<?php
								}
								?>
								
								
							<?php } // sfarsit while ?>	
							
							</tr>
												
							<?php } // sfarsit num_rows
							else
							{
							echo "<tr class=\"celule\">";
							echo "<td colspan=\"14\" style=\"color:red; font-size:20px\">Nu există concurenți în acest tabel</td>";
							echo "</tr>";	
							}
							
							
							} // sfarsit result ?>
							
							<tr class="celule">
                            	<td colspan="14">
                	<!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ ?>
               
               			<div class="page_number">	
				    		<?php
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina='.$x.'&tabel='.$_GET['tabel'].'&gen='.$_GET['gen'].'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina='.$x.'&tabel='.$_GET['tabel'].'&gen='.$_GET['gen'].'"  >'.$x.'</a> '
						
							?>
				   		</div>
						<?php 
						  }
			   	
			 			  }
				
						  ?>
                          
                        </div>

      				 </div>	
                     		  </td>
                          </tr>   
                     <br>		
							
					<?php	}// sfarsit isset
					?>
                    
                    		
                	</table>
                	
                    
                    <!-- procesare informatiilor cautarii din modulul avansat-->
                    
                    <?php
					// procesarea informatiilor venite prin post din tabelul cautare
					if((isset($_POST['trimis_cautare'])) && ($_POST['trimis_cautare'] == "ok"))  {
						
						if((!empty($_POST['nume'])) && (!ctype_space($_POST['nume'])) && ($_POST['nume'] <> "Nume")){
							
								$nume = $_POST['nume'];
							}
							else
							{
								$nume = '';
							}
						if((!empty($_POST['prenume'])) && (!ctype_space($_POST['prenume'])) && ($_POST['prenume'] <> "Prenume")){
							
								$prenume = $_POST['prenume'];
							}
							else
							{
								$prenume = '';
							}
						if((!empty($_POST['sex'])) && (!ctype_space($_POST['sex'])) && ($_POST['sex'] <> "Sex")){
							
								$sex = $_POST['sex'];
							}
							else
							{
								$sex = '';
							}
							
						if((!empty($_POST['cod'])) && (!ctype_space($_POST['cod'])) && ($_POST['cod'] <> "Cod unic")){
							
								$cod = $_POST['cod'];
							}
							else
							{
								$cod = '';
							}
						if((!empty($_POST['punctaj'])) && (!ctype_space($_POST['punctaj']))){
							
								$punctaj = $_POST['punctaj'];
							}
							else
							{
								$punctaj = '';
							}
						if((!empty($_POST['cat_varsta'])) && (!ctype_space($_POST['cat_varsta'])) && ($_POST['cat_varsta'] <> "Categoria de vârstă")){
							
								$cat_varsta = $_POST['cat_varsta'];
							}
							else
							{
								$cat_varsta = '';
							}
							
						    // crearea cautarii
							
							$query = "SELECT * FROM `".$_POST['nume_tabel']."` WHERE `numele` = '".$nume."' OR `prenumele` = '".$prenume."'";
							$query .= " OR `cod_unic` = '".$cod."' OR `categoria` = '".$cat_varsta."'";
							$query .= " OR `sex` = '".$sex."' OR `rezultat` = '".$punctaj."' ORDER BY `rezultat` desc";
							
													
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$rezultat = $db->execute($query);
							if(mysqli_num_rows($rezultat) < 1){
								
								echo "<table id=\"date_tabel_competitie\" cellpadding=\"2px\" cellpadding=\"2px\">";
								echo "<tr>";
								echo "<td class=\"rezultat_nul\">Căutarea nu a găsit niciun rezultat!</td>";
								echo "</tr>";								
								echo "<br>"	;
								echo "<tr>";
								echo "<td class=\"rezultat_nul\">";
								echo "<button onClick=\"history.go(-1)\">Înapoi la tabel</button>";
								echo "</td>";
								echo "</tr>";
								echo "</table>";
								
								
								}
								else
								
								{ ?>
                                
                          <!--- afisarea tabelului cu rezultatele cautarii -----> 
                          <div id="animation"><img src="pics/ajax-loader.gif" /><br /> Se proceseaza informația</div>
                          
                         <table id="date_tabel_competitie" cellpadding="2px" cellpadding="2px">
                    			
                            <tr class="cap_tabel">
                            	<td colspan="14" style="font-size:14px">Modifică detalii concurenți "<?php echo $_POST['nume_tabel'];?>"</td>
                    		</tr>
                            
                    		<tr class="cap_tabel">
                            	<td>Id</td>
                                <td>Cod Unic</td>
                                <td>Nume</td>
                                <td>Prenume</td>
                                <td>Sex</td>
                                <td>Categorie vârstă</td>
                                <td>Locul</td>
                                <td>Puncte</td>
                                <td style="max-width:350px;">Competiții</td>
                                <td>Participări</td> 
                                <td>Bonus</td>
                                <td>Puncte bonus</td>                                     
                                <td>Editează</td>
                            	<td><a href="modificare_tabel_clasament_general.php?tabel=<?php echo $_GET['tabel']?><?php if(isset($_GET['pagina'])){echo "&pagina=".$_GET['pagina'];}?>&gen=<?php echo $_GET['gen'];?>&sex=<?php echo $_POST['sex']?>"><button <?php echo ($_GET['gen'] !==  'general')? 'disabled' : '' ;?>>Editează</button></a></td>
                            </tr>
                            <?php
                	        while($row = $db->getObject($rezultat)){ ?>
                            
							<tr class="celule">
                            	
                            	<td><?php echo $row['id'] ;?></td>
                                <td><?php echo $row['cod_unic'] ;?></td>
                                <td><?php echo $row['numele'] ;?></td>
                                <td><?php echo $row['prenumele'] ;?></td>
                                <td><?php echo $row['sex'] ;?></td>
                                <td><?php echo $row['categoria'] ;?></td>
                                <td><?php echo $row['locul'] ;?></td>
                                <td><?php echo $row['rezultat'] ;?></td>
                                <td style="text-align:left; max-width:350px"><?php echo $row['competitii'] ;?></td>
                                <td><?php echo $row['participari'] ;?></td>
                                <td><?php echo $row['puncte_bonus'] ;?></td> 
                                <td><?php echo $row['bonus'] ;?></td>                                
                                <td><a href="modificare_date_clasament_general.php?comp_id=<?php echo $row['id'];?>&tabel=<?php echo $_POST['nume_tabel'] ;?>"><button>Detalii</button></a></td>                               
                            
                            	
							</tr>	
								
							<?php } // sfarsit while 
								
							?>	  
                                
                                
							
							<tr>
                                <td colspan="14" style="text-align:center;"><a href="detalii_clasament_general.php?tabel=<?php echo $_POST['nume_tabel'] ?>&gen=<?php echo $_GET['gen']; ?>"><button>Reseteaza căutarea</button></a></td>
                            <tr>
								
							<?php	
							} 
							}// sfarsit isset
							?>
                				
                		</table>

                  		
                  
                </div>
                       
             </div>
</body>
</html>
<?php
ob_flush();
?>