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
<title>Detalii tabel competitie</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/detalii_tabel_competitie.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/functii.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    
	$(window).load(function(){
		
		$('#animation').fadeOut(800);
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
                        	<li><a href="tabele_competitii.php">Tabele competiții </a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii Tabel Competiție</li>
                        </ul>                    
                 </div>
                 <br>
                 
                 <div id="text">
                    <?php echo "Tabel competiție<br>".ucfirst(str_replace("_"," ",$_GET['tabelid'])); ?>
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
                
                    <?php     
                    // paginatia paginii
						
						$afisari_pagina = 75;
		  				$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE 1";
		   				$result = $db->execute($query);										 				 
		  				$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  				$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  				$start = ($pagina - 1) * $afisari_pagina;
					?>
                    
                    
               
                
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
                                    <input type="text" name="categoria" id="categoria" value="Categoria" onBlur="blurCateg();" onFocus="focusCateg();"/>
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="cod" id="cod" value="Cod unic" onBlur="blurCod();" onFocus="focusCod();"/>
                                </td>
                            </tr>
                            <tr>

                            	
                                <td class="celule">    
                                    <input type="text" name="taxa" id="taxa" value="Taxa" onBlur="blurTaxa();" onFocus="focusTaxa();"/>
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="cat_varsta" id="varsta" value="Categoria de vârstă" onBlur="blurVarsta();" onFocus="focusVarsta();" />
                                </td>
                             <tr>
                             	
                                <td class="celule" colspan="2" style="text-align:center">
                                	<input type="submit" name="submit" value="Caută"/>
                                    <input type="hidden" name="trimis_cautare" value="ok"/>
                                    <input type="hidden" name="nume_tabel" value="<?php  echo $_GET['tabelid']; ?>"/>
                                    <input type="hidden" name="flag" value="<?php echo $_GET['flag']; ?>" />
                                </td>
                                
                                    
                            </tr>
                            
                        </form>
                        </table>
                        <br>
                     </div>                
                   </div> 
                  
                   <!-- adaugare participant si vizualizare printare-->
                   
                   <div id="adauga_participant">
                	<a href="adauga_participant_competitie_freeride.php?tbl_name=<?php echo $_GET['tabelid']; ?>&flag=<?php echo $_GET['flag']; ?>"><button>Adaugă participant</button></a>
                    &nbsp;
                    <?php
					// butonul print
					if(isset($_POST['categoria'])){
					?>
                    <a href="print_personalizat.php?tabel=<?php echo $_POST['nume_tabel'];?>&categoria=<?php echo $_POST['categoria']; ?>&flag=<?php echo $_GET['flag']; ?>" ><button>Print personalizat</button></a>
                    <?php
					}
					else
					{
					?>
                    <a href="print_tabel_freeride.php?tbl_name=<?php echo $_GET['tabelid'];?><?php if(isset($_GET['coloana'])){ echo "&coloana={$_GET['coloana']}";} ?>&flag=<?php echo $_GET['flag']; ?>"><button>Print</button></a>
                    <?php
					}
					?>
                    &nbsp;
                    <?php
                    if(isset($_POST['categoria']) &&  ($_POST['categoria']) !== 'Categoria' ){
							
					?>
                    <a href="exporta_tabel_competitie.php?numeTabel=<?php echo $_GET['tabelid']; ?>&categoria=<?php echo $_POST['categoria'];?>&flag=<?php echo $_GET['flag'];?>" target="_blank"><button>Exportă Categorie </button></a>
                    <?php								
					}
					else if(isset($_POST['taxa']) && ($_POST['taxa']) == 'achitata' && ($_POST['categoria']) == 'Categoria'){
					?>
					<a href="exporta_tabel_competitie.php?numeTabel=<?php echo $_GET['tabelid']; ?>&taxa=<?php echo $_POST['taxa'];?>&flag=<?php echo $_GET['flag'];?>" target="_blank"><button>Exportă achitați </button></a>
                    <?php
					}
					else
					{
					?>
                    <a href="exporta_tabel_competitie.php?numeTabel=<?php echo $_GET['tabelid']; ?>&flag=<?php echo $_GET['flag'];?>" target="_blank"><button>Exportă neachitați</button></a>
                    <?php	
					}
					?>
                    &nbsp;
                    <!-- Butonul de export date de contact a celor achitati -->
                    <a href="export_dateContact_competitie.php?numeTabel=<?php echo $_GET['tabelid']; ?>&flag=<?php echo $_GET['flag']; ?>" target="_blank"><button>Date Contact CSV</button></a>
                     <!-- Butonul ce trimite mesaje concurentilor din competitie -->
                    &nbsp;
                    <button onClick="animIn(); displayGray();">Trimite email</button> 
                    
                     <!-- Butonul de update informatii post competitie -->
                    &nbsp;
                    <button onClick="updateIn(); displayGray();">Update competiție</button>  
                    
                     <?php
						$query = "SELECT sum(suma) FROM `".$_GET['tabelid']."` WHERE `suma` <> '' ";
						$result = $db->execute($query);
						$total = 0;
						while($row = mysqli_fetch_array($result)){
							$total = $row['sum(suma)'];
							
						}
						$total;
						echo "<button style=\"float:right; margin-right:10px;\">Total încasări&nbsp;<font style=\"color:red; font-weight:700\">{$total}</font>&nbsp;&nbsp;lei</button>";
					?>                                         
               		</div> 
                	
                  
                   <!-- date despre competitie si participanti -->
                  
                  	<div id="date_competitie">
                    
                    <br>
                    <table id="traseu" cellpadding="2px" cellspacing="2px">
                    		<tr>
                        	<th>Trasee Competiție</th>
                            <th>Schi</th>
                            <th>Snowboard</th>                            
                            <th>Participanți Vip</th>
                            <th>Plătitori</th>
                            <th>Total participanți</th>                                                      
                    	</tr>
                        <tr>
                        	<td>Locuri ocupate</td>
                            <td>
                            	<?php
                    // easy ride 
                	$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `categoria` = \"schi\"";
					$result = $db->execute($query);
					$easy_ride = mysqli_num_rows($result);
					echo $easy_ride;
								?>
                            </td>
                            <td>
                            <?php
							// hobby ride
					$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `categoria` = \"snowboard\"";
					$result = $db->execute($query);
					$hobby_ride = mysqli_num_rows($result);
					echo $hobby_ride;					
                            ?>
                            </td>                            
                            <td >
                            	<?php
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `pachet_vip` = 'vip'";
								$result = $db->execute($query);
								$nr_vip = mysqli_num_rows($result);
								echo $nr_vip;
								?>
                            
                            </td>
                            <td >
                            	<?php
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `taxa` = 'achitata'";
								$result = $db->execute($query);
								$taxa_achitata = mysqli_num_rows($result);
								echo $taxa_achitata;
								
                            	?>
                            </td>
                            <td style="color:red; font-weight:900; font-size:14px;">
                            <?php
					// total participanti
					$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE 1";
					$result = $db->execute($query);
					$participanti = mysqli_num_rows($result);
					echo $participanti ;
					
                      ?>      
                            </td>
                        </tr>
                        
                    </table>
                    <br>
					<table id="cat_varsta" cellpadding="2px" cellspacing="2px">
                    	<tr>
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
								// pitici
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"pitici\"";
								$result = $db->execute($query);
								$pitici = $db->getCount($result);
								echo $pitici;			
                            	?>
                            </td>
                            	
                            <td>
                            	<?php
								// copii
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"copii\"";
								$result = $db->execute($query);
								$copii = $db->getCount($result);
								echo $copii;			
                            	?>
                            </td>
                            <td>
                            	<?php
								// spiridusi
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"spiridusi\"";
								$result = $db->execute($query);
								$spiridusi = $db->getCount($result);
								echo $spiridusi;			
                            	?>
                            </td>
                            <td>
                            	<?php
								// uriasi
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"uriasi\"";
								$result = $db->execute($query);
								$uriasi = $db->getCount($result);
								echo $uriasi;			
                            	?>                            
                            </td>
                            <td>
                            	<?php
								// tineri
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"tineri\"";
								$result = $db->execute($query);
								$tineri = $db->getCount($result);
								echo $tineri;			
                            	?>                
                            
                            </td>
                            <td>
                            	<?php
								// adulti
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"adulti\"";
								$result = $db->execute($query);
								$adulti = $db->getCount($result);
								echo $adulti;			
                            	?>            
                            
                            </td>
                            <td>
                            	<?php
								// seniori
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"seniori\"";
								$result = $db->execute($query);
								$seniori = $db->getCount($result);
								echo $seniori;			
                            	?>            
                            </td>
                            <td>
                            	<?php
								// forever_young
								$query = "SELECT `id` FROM `".$_GET['tabelid']."` WHERE `cat_varsta` = \"forever_young\"";
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
						
						
					if((isset($_GET['tabelid'])) && (!isset($_POST['trimis_cautare'])) && (!isset($_GET['coloana']))){
							
						$comp = $_GET['tabelid']; 
				 
				 
				 ?> 
                 <!-- afisarea tabelului in prima apelare a paginii fara criterii de ordonare -->	
                  	<div id="animation"><img src="pics/ajax-loader.gif" /><br /> Se proceseaza informația</div>
                    <table id="date_tabel_competitie" cellpadding="2px" cellpadding="2px">
                    		
                            <tr class="cap_tabel">
                            	<td colspan="20" style="font-size:14px">Modifică detalii concurenți "<?php echo $_GET['tabelid'];?>"</td>
                    		</tr>
                    		<tr class="cap_tabel">
                            	<td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=id&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Id</a></td>
                                <td>Cod Unic</td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=numele&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Nume</a></td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=prenumele&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Prenume</a></td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=varsta&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Varsta</a></td>
                                <td>Cat. vârstă</td>
                                <td>Sex</td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=categoria&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Categoria</a></td>
                                <td>Exp. freeride</td>
                                <td>Adresa</td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=pachet_vip&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Pachet Vip</a></td>
                                <td>Tricou</td>
                                <td>Print Vip</td>                                                             
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=numar_concurs&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Număr</a></td>
                                <td>Mod plată</td>
                                <td>Suma</td>
                                <td>Confirmat</td>
                                <td>Taxa</td>
                                <td><a href="modificare_tabel_competitie_freeride.php?tbl_name=<?php echo $_GET['tabelid']?>&coloana=&pagina=<?php if(isset($_GET['pagina'])){echo $_GET['pagina'];}else{echo 1;}?>&flag=<?php echo $_GET['flag']; ?>"><button>Editează</button></a></td>
                                <td>Elimină</td>
                            
                            </tr>
                	        
                   			 <?php
							
							$query = "SELECT * FROM   `". $comp."` WHERE 1 ORDER BY `id` desc LIMIT $start, $afisari_pagina";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$result = $db->execute($query);
							
							if($result){
							if($db->getCount($result) <> 0){
							while($row = mysqli_fetch_array($result)){ ?>
                            
							<tr class="celule">
                            	<td><?php echo $row['id'] ;?></td>
                                <td><?php echo $row['cod_unic_participant'] ;?></td>
                                <td><?php echo $row['numele'] ;?></td>
                                <td><?php echo $row['prenumele'] ;?></td>
                                <td><?php echo $row['varsta'] ;?></td>
                                <td><?php echo $row['cat_varsta'] ;?></td>
                                <td><?php echo $row['sex'] ;?></td>
                                <td><?php echo $row['categoria'] ;?></td>
                                <td><?php echo substr($row['experienta'],0,20) ;?></td>
                                <td><?php echo substr($row['adresa'],0,20) ;?></td>
                                <td><?php echo $row['pachet_vip'] ;?></td>
                                <td><?php echo $row['tricou'] ;?></td>
                                <td><?php echo $row['print_vip'] ;?></td>                                
                                <td><?php echo $row['numar_concurs'] ;?></td>
                                <td><?php echo $row['modalitate_plata'] ;?></td>
                                <td><?php echo $row['suma'] ;?></td>
                                <td><?php echo $row['confirmare'] ;?></td>
                                <td><?php echo $row['taxa'] ;?></td>
                                <td><a href="modificare_date_concurent_freeride.php?comp_id=<?php echo $row['id'];?>&table_name=<?php echo $_GET['tabelid'] ;?>&flag=<?php echo $_GET['flag']; ?>"><button>Detalii</button></a></td>                               
                            	<td>
                                	<form action="update.php" method="post">
                                    	<input class="button" type="submit" name="sterge_concurent" value="Șterge" onClick="return confirmPost();" />
                                        <input type="hidden" name="id_concurent" value="<?php echo $row['id'] ;?>" />
                                        <input type="hidden" name="tabel" value="<?php echo $_GET['tabelid'] ?>" />
                                        <input type="hidden" name="flag" value="<?php echo $_GET['flag'];?>" />
                                    </form>
                                </td> 
                            	
								
								
							<?php } // sfarsit while ?>	
							
							</tr>
												
							<?php } // sfarsit num_rows
							else
							{
							echo "<tr class=\"celule\">";
							echo "<td colspan=\"20\" style=\"color:red; font-size:20px\">Nu există concurenți în acest tabel</td>";
							echo "</tr>";	
							}
							
							
							} // sfarsit result ?>
							
							<tr class="celule">
                            	<td colspan="20">
                	<!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ ?>
               
               			<div class="page_number">	
				    		<?php
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina='.$x.'&tabelid='.$_GET['tabelid'].'&flag='.$_GET['flag'].'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina='.$x.'&tabelid='.$_GET['tabelid'].'&flag='.$_GET['flag'].'"  >'.$x.'</a> '
						
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
						if((!empty($_POST['categoria'])) && (!ctype_space($_POST['categoria'])) && ($_POST['categoria'] <> "Categoria")){
							
								$categoria = $_POST['categoria'];
							}
							else
							{
								$categoria = '';
							}
							
						if((!empty($_POST['cod'])) && (!ctype_space($_POST['cod'])) && ($_POST['cod'] <> "Cod unic")){
							
								$cod = $_POST['cod'];
							}
							else
							{
								$cod = '';
							}
						if((!empty($_POST['taxa'])) && (!ctype_space($_POST['taxa'])) && ($_POST['taxa'] <> "Taxa")){
							
								$taxa = $_POST['taxa'];
							}
							else
							{
								$taxa = '';
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
$query .= " OR `categoria` = '".$_POST['categoria']."' OR `cod_unic_participant` = '".$cod."' OR `cat_varsta` = '".$cat_varsta."'";
$query .= " OR `taxa` = '".$taxa."' ";
							
													
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
                            	<td colspan="20" style="font-size:14px">Modifică detalii concurenți "<?php echo $_POST['nume_tabel'];?>"</td>
                    		</tr>
                            
                    		<tr class="cap_tabel">
                            	<td>Id</td>
                                <td>Cod Unic</td>
                                <td>Nume</td>
                                <td>Prenume</td>
                                <td>Vârsta</td>
                                <td>Cat. vârstă</td>
                                <td>Sex</td>
                                <td>Categoria</td>
                                <td>Exp. freeride</td>
                                <td>Adresa</td>
                                <td>Pachet Vip</td>
                                <td>Tricou</td>
                                <td>Print Vip</td>                                
                                <td>Număr</td>
                                <td>Mod plată</td>
                                <td>Suma</td>
                                <td>Confirmat</td>
                                <td>Taxa</td>
                                <td>Editează</td>
                            
                            </tr>
                            <?php
                	        while($row = mysqli_fetch_array($rezultat)){ ?>
                            
							<tr class="celule">
                            	
                            	<td><?php echo $row['id'] ;?></td>
                                <td><?php echo $row['cod_unic_participant'] ;?></td>
                                <td><?php echo $row['numele'] ;?></td>
                                <td><?php echo $row['prenumele'] ;?></td>
                                <td><?php echo $row['varsta'] ;?></td>
                                <td><?php echo $row['cat_varsta'] ;?></td>
                                <td><?php echo $row['sex'] ;?></td>
                                <td><?php echo $row['categoria'] ;?></td>
                                <td><?php echo substr($row['experienta'],0,20) ;?></td>
                                <td><?php echo substr($row['adresa'],0,20) ;?></td>
                                <td><?php echo $row['pachet_vip'] ;?></td>
                                <td><?php echo $row['tricou'] ;?></td>
                                <td><?php echo $row['print_vip'] ;?></td>                                
                                <td><?php echo $row['numar_concurs'] ;?></td>
                                <td><?php echo $row['modalitate_plata'] ;?></td>
                                <td><?php echo $row['suma'] ;?></td>
                                <td><?php echo $row['confirmare'] ;?></td>
                                <td><?php echo $row['taxa'] ;?></td>
                                <td><a href="modificare_date_concurent_freeride.php?comp_id=<?php echo $row['id'];?>&table_name=<?php echo $_POST['nume_tabel'] ;?>&flag=<?php echo $_GET['flag']; ?>"><button>Detalii</button></a></td>                               
                            
                            	
								
								
							<?php } // sfarsit while ?>	  
                                
                                
							</tr>
							<tr>
                                <td colspan="20" style="text-align:center;"><a href="detalii_tabel_competitie_freeride.php?tabelid=<?php echo $_POST['nume_tabel'] ?>&flag=<?php echo $_GET['flag']; ?>"><button>Reseteaza căutarea</button></a></td>
                            <tr>
							<?php	}
								
								
							} // sfarsit isset
							
							?>
                				
                		</table>

                  		<!-- afisarea tabelului in functie de elementul de ordonare -->
                        
                         <?php
				 
				 								
						if((isset($_GET['coloana'])) && (!isset($_POST['trimis_cautare']))) { 
							
							$comp = $_GET['tabelid'];
							$coloana = $_GET['coloana'];
								
						?>
                        <div id="animation"><img src="pics/ajax-loader.gif" /><br /> Se proceseaza informația</div>
                		<table id="date_tabel_competitie" cellpadding="2px" cellpadding="2px">
                    		
                            <tr class="cap_tabel">
                            	<td colspan="20" style="font-size:14px">Modifică detalii concurenți "<?php echo $_GET['tabelid'];?>"</td>
                    		</tr>
                    		<tr class="cap_tabel">
                            	<td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=id&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Id</a></td>
                                <td>Cod Unic</td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=numele&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Nume</a></td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=prenumele&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Prenume</a></td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=varsta&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Varsta</a></td>
                                <td>Cat. vârstă</td>
                                <td>Sex</td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=categoria&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Categoria</a></td>
                                <td>Exp. freeride</td>
                                <td>Adresa</td>
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=pachet_vip&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Pachet Vip</a></td>
                                <td>Tricou</td>
                                <td>Print Vip</td>                                                             
                                <td><a href="<?php $_SERVER['PHP_SELF'];?>?coloana=numar_concurs&tabelid=<?php echo $comp ?>&flag=<?php echo $_GET['flag']; ?>">Număr</a></td>
                                <td>Mod plată</td>
                                <td>Suma</td>
                                <td>Confirmat</td>
                                <td>Taxa</td>
                                <td><a href="modificare_tabel_competitie_freeride.php?tbl_name=<?php echo $_GET['tabelid']?>&coloana=<?php if(isset($_GET['coloana'])){echo $_GET['coloana'];}else{echo "";}?>&pagina=<?php if(isset($_GET['pagina'])){echo $_GET['pagina'];}else{echo 1;}?>&flag=<?php echo $_GET['flag']; ?>"><button>Editează</button></a></td>
                                <td>Elimină</td>
                            
                            </tr>
                	        
                   			 <?php
						
							$query = "SELECT * FROM `".$_GET['tabelid']."` WHERE 1 ORDER BY {$_GET['coloana']} desc LIMIT $start, $afisari_pagina";
							
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$result = $db->execute($query);
							if($result){
							if($db->getCount($result) <> 0){
							while($row = mysqli_fetch_array($result)){ ?>
                            
							<tr class="celule">
                            	<td><?php echo $row['id'] ;?></td>
                                <td><?php echo $row['cod_unic_participant'] ;?></td>
                                <td><?php echo $row['numele'] ;?></td>
                                <td><?php echo $row['prenumele'] ;?></td>
                                <td><?php echo $row['varsta'] ;?></td>
                                <td><?php echo $row['cat_varsta'] ;?></td>
                                <td><?php echo $row['sex'] ;?></td>
                                <td><?php echo $row['categoria'] ;?></td>
                                <td><?php echo substr($row['experienta'],0,20) ;?></td>
                                <td><?php echo substr($row['adresa'],0,20) ;?></td>
                                <td><?php echo $row['pachet_vip'] ;?></td>
                                <td><?php echo $row['tricou'] ;?></td>
                                <td><?php echo $row['print_vip'] ;?></td>                                
                                <td><?php echo $row['numar_concurs'] ;?></td>
                                <td><?php echo $row['modalitate_plata'] ;?></td>
                                <td><?php echo $row['suma'] ;?></td>
                                <td><?php echo $row['confirmare'] ;?></td>
                                <td><?php echo $row['taxa'] ;?></td>
                                <td><a href="modificare_date_concurent_freeride.php?comp_id=<?php echo $row['id'];?>&table_name=<?php echo $_GET['tabelid'] ;?>&flag=<?php echo $_GET['flag']; ?>"><button>Detalii</button></a></td>                               
                            	<td>
                                	<form action="update.php" method="post">
                                    	<input class="button" type="submit" name="sterge_concurent" value="Șterge" onClick="return confirmPost();" />
                                        <input  type="hidden" name="id_concurent" value="<?php echo $row['id'] ;?>" />
                                        <input  type="hidden" name="tabel" value="<?php echo $_GET['tabelid'] ?>" />
                                        <input type="hidden" name="flag" value="<?php echo $_GET['flag'];?>" />
                                    </form>
                                </td> 
                            	
								
								
							<?php } // sfarsit while ?>	
							
							</tr>
												
							<?php } // sfarsit num_rows
							else
							{
							echo "<tr class=\"celule\">";
							echo "<td colspan=\"20\" style=\"color:red; font-size:20px\">Nu există concurenți în acest tabel</td>";
							echo "</tr>";	
							}
							
							
							} // sfarsit result
                            
							?>
							<tr class="celule">
                            	<td colspan="20">
                	<!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ ?>
               
               			<div class="page_number">	
				    		<?php
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina='.$x.'&tabelid='.$_GET['tabelid'].'&coloana='.$coloana.'&flag='.$_GET['flag'].'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina='.$x.'&tabelid='.$_GET['tabelid'].'&coloana='.$coloana.'&flag='.$_GET['flag'].'"  >'.$x.'</a> '
						
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
							
						<?php		
                            
                        } // sfarsit isset
						
						?>
					  </table>		
                  
                </div> 
                 <div id="messageDiv">
                 
					<?php include('trimite_mesaj_concurentilor.php') ?>
            	</div>
                
                <!-- afisarea ferestrei update tabel --> 
                <div id="updateCompetitie">
                	<img id="close_div" src="pics/cross_close.png" onClick="animOutUpdate();" />
                    
                	<table id="tabelUpdateCompetitie" cellpadding="2" cellspacing="2" border="0">
                    	<form action="update.php" method="post" enctype="multipart/form-data">
                    		<tr>
                            	<th>Nume competiție</th>
                                <td>
                                	<input type="text" name="numeCompetitie" value="<?php echo $_GET['tabelid']?>" readonly/>
                                </td>
                            </tr>
                    		<tr>
                            	<th>Flag competiție</th>
                                <td><input type="text" name="flagCompetitie" value="<?php echo $_GET['flag']?>" readonly/></td>
                            </tr>
                            <th>Fisier upload</th>
                            <td><input type="file" name="fisierUpload"  value="Incarca fisier upload" required/></td>
                            <tr>
                            	<td colspan="2">
                                	<input type="submit" name="uploadTabel" value="Update Tabel" />                                   
                                </td>
                            </tr>
                    	</form>
                    </table>
                	
                </div>                            
                              
             </div>
			 <div id="gray"></div>             


            
</body>
</html>
<?php
ob_flush();
?>