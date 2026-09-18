<?php
ob_start();
session_start();
require("includes/Database.php");
require("includes/constante.php");
$db = new Database();
error_reporting(0);

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
<title>Adauga participant in baza de date</title>
<link rel="stylesheet" type="text/css" media="all" href="css/adauga_participant_baza.css" />
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
                 
                 <!-- meniul navigare stanga -->
                 <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center"><a href="baza_date.php"><div class="text">Home</div></a></td>
                        </tr>
                    	<tr>
                    		<td style="background-color:#0F87FF"><a href="adauga_participant_baza.php">Adaugă participant</a></td>
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
				// procesare date primite de la tabel
				
				if((isset($_POST['trimis'])) and ($_POST['trimis'] == "true")){
						
						// verificare introducere nume
						if(!empty($_POST['nume']) and (!ctype_space($_POST['nume']))){
								$data['nume'] = $_POST['nume'];							
							}
							else
							{
								$errors[] = "Completati numele";	
							}
						// verificare introducere prenume	
						if(!empty($_POST['prenume']) and (!ctype_space($_POST['prenume']))){
								$data['prenume'] = $_POST['prenume'];								
							
							}	
							else
							{
								$errors[] = "Completati prenumele";
							}
					   // verificare sex		
						if(!empty($_POST['sex']) and ($_POST['sex'] == true )){
															
								$data['sex'] = $_POST['sex'];							
							
							}	
							else
							{
								$errors[] = "Bifati sexul";
							}
						
						// verificare data nasterii
							
						if(($_POST['ziua'] <> "") && ($_POST['luna'] <> "") && ($_POST['anul'] <> "")){
								// generez data de nastere
								
								$data['data_nasterii'] = $_POST['anul']."/".$_POST['luna']."/".$_POST['ziua'];
								$data_nasterii = $_POST['ziua']."/".$_POST['luna']."/".$_POST['anul'];
														
							}
							else
							{
								$errors[] = "Completati data nasterii";
							}
							
										
					// se verifica nr de telefon introdus	
					if(!empty($_POST['telefon']) && (is_numeric($_POST['telefon'])) && (strlen($_POST['telefon']) >= 10)){
						
							$data['telefon'] = $_POST['telefon'];
						}
						else
						{
							$errors[] = "Numar de telefon invalid";
						}
						
					// verific adresa de email
					
					if((!empty($_POST['email'])) && (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) && (!ctype_space($_POST['email'])))){
						
							$data['email'] = $_POST['email'];
						
						}	
						else
						{
							$errors[] = "Adresa de email invalida";
						}
						
						
							
					// introducerea orasului nu este obligatorie
					if(!empty($_POST['oras']) && (!ctype_space($_POST['oras']))){
						
							$data['oras'] = $_POST['oras'];
						}
						else
						{
							$data['oras'] = "";							
						}
					
							
					// introducerea clubului afiliat nu este obligatorie					
					if(!empty($_POST['club']) && (!ctype_space($_POST['club']))){
						
							$data['club'] = $_POST['club'];
						}
						else
						{
							$data['club'] = "";
						}
						
					// introducerea descrierii nu este obligatorie
									
					if(!empty($_POST['descriere']) && (!ctype_space($_POST['descriere']))){
						
							$data['descriere'] = $_POST['descriere'];
						}
						else
						{
							$data['descriere'] = "";
						}		 
							
					$data['ip'] = $_SERVER['REMOTE_ADDR'];
					$data['data_inscriere'] = date('Y-m-d');
					// generarea si verificarea codului
					include("php/functii.php");
				    $data['cod_unic'] = uniCode($data['nume'],$data['prenume'],$data['data_nasterii']);
					if($data['cod_unic']){
					$query = "SELECT `cod_unic` FROM `inscrisi_teamexpert` WHERE `cod_unic` = '".$data['cod_unic']."'";
						  $result = $db->execute($query);	
			  			  $num_rows = mysqli_num_rows($result);
								if($num_rows == 1){
									$errors[] = "Cod duplicat, te rog să reiei inscrierea!";
								}
								else
								{
									$data['cod_unic'] = $data['cod_unic'];	
								}
					}
					else
					{
						$errors[] = "Eroare generare cod, completați câmpurile obligatorii";
					}
					
					
					if(isset($data['cod_unic'])){
			    	$mail_cod = $data['cod_unic'];	// codul ce va fi trimis participantului
					}
					$data['varsta']	= date('Y') - $_POST['anul'];
					$data['categorie_varsta'] = catVarsta($data['varsta']);		
					$data['activ'] = 1;
					$data['news_activ'] = 1;
					
							//mesaj de succes
							if(!isset($errors)){							
								    
									
									// verificare daca exista duplicat a email-ului
									$query = "Select `email` FROM `inscrisi_teamexpert` WHERE email = '".$data['email']."'";
									$result = $db->execute($query);
																		
									$nr_randuri = mysqli_num_rows($result);
									
									
									if($nr_randuri == 1 ){
										
										$errors[] = "Adresa de email este deja în uz, încearcă înca o dată cu o adresa de mail nouă!";
										
										}
										else
										
										{
									
								
									// introducerea datelor in baza de date
									if(is_array($data)){
									$tabel = "inscrisi_teamexpert";
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
									}
									
									// trimitem mail cu codul generat celui care a facut inregistrarea
									$cod_unic = base64_encode($data['cod_unic']);
									$to = $data['email'];
									$from = "office@teamexpert.ro";
									$numeSender = "TeamXpert";
									$subiect = "Acest mesaj contine codul tau de inregistrare TeamXpert";
									
									// variabile ce se gasesc in textul mail-ului din tabelul din db
									
												
									$arrData = array(
														
										'cod'=>$mail_cod,										
										'numele'=>$data['nume']." ".$data['prenume'],										
										'data_nasterii'=>$data_nasterii,
										'cod_unic'=>$cod_unic
									);
												
									function replace($i) {
													
  												GLOBAL $arrData;
  												return $arrData[$i[1]];
												return $arrData[$i[2]];
												return $arrData[$i[3]];
												return $arrData[$i[4]];
												return $arrData[$i[5]];
																							
																							
									};
									
									$query = "SELECT `continut_email` FROM `mesaje_email` WHERE `flag_competitie` LIKE '%Mesaj confirmare inscriere%'";
									$insert = "SET NAMES 'utf8'";
									$db->execute($insert);
									$result = $db->execute($query);
									$mail = $db->getAssoc($result);
												
									// variabila ce contine mail-ul din baza de date
									$mesaj = base64_decode($mail['continut_email']);
												
									// inlocuirea variabilelor din email-ul extras din baza de date
												
									$mesaj = preg_replace_callback('/{\$([^}]+)}/',"replace", $mesaj);
									$headers = "FROM: $numeSender {$from} \r\n";
									$headers.= "Reply-To:{$from} \r\n";
									$headers.= "CC: {$to} \r\n";
									$headers.= "Bcc: {$to} \r\n";
									$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
									$headers.= "MIME-Version: 1.0\r\n";
									$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
									$headers.= "Content-Transfer-Encoding: 8bit\n"; 
									
									$result=mail($to,$subiect,$mesaj,$headers);
								
								echo "<div class=\"succes\"><p>Un concurent a fost adăugat cu succes în baza de date</p></div>";
								}						
								
							}
							
						}
						
						// validam erorile
						if(isset($errors)){
								echo "<div class=\"eroare\">";
							foreach($errors as $eroare){
								echo "<img src=\"pics/cross1.png\"/> {$eroare} <br>";
								
								}
								echo "</div>";
							}
				
				?>
                <!-- tabel introducere date -->
                
               <div class="tabel_date">
               <div id="admin">
                <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
				?>
               </div>
               <br>
               <div id="text">
                    <?php echo "Adaugă participant în baza de date "; ?>
               </div>
               <br>
                  <table id="tabel_date_inscris" >
                	<form action="<?php $_SERVER['PHP_SELF'] ; ?>" method="post">
                		<tr>
                    		<td class="celule">
                        		<label for="nume">Numele*</label>
                       		</td>
                       		<td class="input">
                        		<input type="text" name="nume" value="<?php if(isset($data['nume']))echo $data['nume']; ?>" required />
                        	</td>
                    	</tr>
                        <tr>
                        	<td class="celule">
                        		<label for="prenume">Prenumele*</label>
                       		</td>
                       		 <td class="input">
                        		<input type="text" name="prenume" value="<?php if(isset($data['prenume']))echo $data['prenume']; ?>" required />
                        	</td>                        
                        </tr>
                         <tr>
                        	<td class="celule">
                        		<label for="sex">Sexul*</label>
                       		</td>
                       		 <td class="input">
                        		<input type="radio" name="sex" value="masculin" <?php if((isset($data['sex']))&&(($data['sex']) == "masculin")){echo "checked" ;}else{}?> required/>Masculin
                                <input type="radio" name="sex" value="feminin" <?php if((isset($data['sex']))&&(($data['sex']) == "feminin")){echo "checked" ;}else{}?> required/>Feminin
                        	</td>                        
                        </tr>
                         
                         <tr>
                        	<td class="celule">
                        		<label for="data">Data nașterii*</label>
                       		</td>
                          
                       		 <td class="input">
                             	<select name="ziua" style="color:#394CA0; font-weight:bold" class="select" required>
                                	<option value="" >Ziua</option>
                                	<?php
										$zile = array('01'=>1,'02'=>2,'03'=>3,'04'=>4,'05'=>5,'06'=>6,'07'=>7,'08'=>8,'09'=>9,'10'=>10,'11'=>11,'12'=>12,'13'=>13,'14'=>14,'15'=>15,'16'=>16,'17'=>17,'18'=>18,'19'=>19,'20'=>20,'21'=>21,'22'=>22,'23'=>23,'24'=>24,'25'=>25,'26'=>26,'27'=>27,'28'=>28,'29'=>29,'30'=>30,'31'=>31);
										
										foreach($zile as $nr_zi=>$ziua){
											
											echo "<option value=\"$nr_zi\">$ziua</option>";
											
											}
									?>
                                </select>
                                
                                <select name="luna" style="color:#394CA0; font-weight:bold"  class="select" required>
                                	<option value="">Luna</option>
                                	<?php
										$luni = array('01'=>'Ianuarie','02'=>'Februarie','03'=>'Martie','04'=>'Aprilie','05'=>'Mai','06'=>'Iunie','07'=>'Iulie','08'=>'August','09'=>'Septembrie','10'=>'Octombrie','11'=>'Noiembrie','12'=>'Decembrie');
										foreach($luni as $number=>$luna ){
										echo "<option value=\"$number\">$luna</option>";
										}
									?>
                                    
                                </select>
                                
                        		<select name="anul" style="color:#394CA0; font-weight:bold" class="select" required>
                                	<option value="" >Anul</option>
                                	<?php
										$i= date('Y') - 2 ;
										$an_trecut = $i - 80 ;
										 for($i; $i >= $an_trecut; $i = $i-1){
											 
											echo "<option value=\"$i\">$i</option>";
										}
									?>
                                </select>
                                
                             </td>                        
                        </tr>
                       
                         <tr>
                        	<td class="celule">
                        		<label for="telefon">Telefon*</label>
                       		</td>
                       		 <td class="input">
                        		<input type="number" name="telefon" value="<?php if(isset($data['telefon']))echo $data['telefon']; ?>" pattern="^(?:0)[1-79](?:[\.\-\s]?\d\d){4}$" required/>
                        	</td>                        
                        </tr>
                        <tr>
                        	<td class="celule">
                        		<label for="email">Email*</label>
                       		</td>
                       		 <td class="input">
                        		<input type="email" name="email" value="<?php if(isset($data['email']))echo $data['email']; ?>" required />
                        	</td>                        
                        </tr>
                       
                        <tr>
                        	<td class="celule">
                        		<label for="oras">Orașul de reședință</label>
                       		</td>
                       		 <td class="input">
                        		<input type="text" name="oras" value="<?php if(isset($data['oras']))echo $data['oras']; ?>"/>
                        	</td>                        
                        </tr>
                        <tr>
                        	<td class="celule">
                        		<label for="club">Clubul la care sunteți afiliat</label>
                       		</td>
                       		 <td class="input">
                        		<input type="text" name="club" value="<?php if(isset($data['club']))echo $data['club']; ?>" />
                        	</td>                        
                        </tr>
                        <tr>
                        	<td class="celule">
                        		<label for="descriere">Scurtă descriere despre dumneavoastră<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sau aspirațiile dumneavoastră</label>
                       		</td>
                       		 <td class="input">
                        		<textarea cols="14" rows="5" name="descriere" ><?php if(isset($data['descriere']))echo $data['descriere']; ?></textarea>
                        	</td>                        
                        </tr>
                        
                        <tr>
                        	
                            <td valign="middle" id="submit" colspan="2" class="input_buton">
                            	<input type="reset" value="Resetează informațiile" class="modifica"/>&nbsp;&nbsp;
                            	<input type="submit" name="submit" value="Aplică" class="modifica"/>
                                <input type="hidden" name="trimis" value="true" />                                
                            </td>
                         </tr>
                         
                     </form>
	   
                </table>
            	<br>
                 
                </div>
                
                
           </div>
                 
</body>
</html>
<?php
ob_flush();
?>