<?php
ob_start();
session_start();
include("includes/Database.php");
include("includes/constante.php");
$db = new Database();	
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" media="all" href="css/main.css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
				<div id="page_content">
                
                		<div id="text_top">
                        	<p>Zona de administrare <b style="color:darkblue">Team</b><b style="color: #F00000">Xpert</b> Sport Events</p>
                        </div>
                        
                        <?php
								if((isset($_POST['trimis'])) and ($_POST['trimis'] == "ok")){
									
									if(!empty($_POST['username']) and (!ctype_space($_POST['username']))){
										
											$username = $db->escape($_POST['username']);
										}
										else
										{
											$error[] = "Introduceti username";
										}
										
									if((!empty($_POST['parola'])) and (!ctype_space($_POST['parola']))){
										
											$parola = $db->escape(sha1($_POST['parola']));
										}
										else
										{
											$error[] = "Introduceti parola";	
										}
										
										
										
										if(!isset($error)){
											
												
																							
											
					$select = "SELECT `username`, `parola` FROM `login` WHERE username = '".$username."' AND parola = '".$parola."'";
								
					$rezultat = $db->execute($select);
												
								if($db->getCount($rezultat) == 1){
													
								 $query = "SELECT `uniq_id` FROM `login` WHERE `parola` = '".$parola."' AND parola = '".$parola."'";
										$result = $db->execute($query);
										$uniq_id = $db->getAssoc($result);													
										
										$_SESSION['username'] = $username;
										$_SESSION['uniq_id'] = $uniq_id['uniq_id'];
										header("Location: baza_date.php");									
										}
										else
										{
										$error[] = "User sau parola gresite";	
										}	
												
																							
										}
										
									}
									
									
									if(isset($error)){
												echo "<div class=\"eroare\">";
													foreach($error as $eroare){
												echo "<img src=\"pics/cross1.png\"/> {$eroare} <br>";
								
													}
													echo "</div>";
										}
						?>
                        
                        <div id="login">
                        	<div id="login_text">
                            Te rog să introduci datele de login
                            </div>
                            
                            <div id="tabel_login">
                            
                            	<table cellspacing="2px" cellpadding="4px">
                                	<form action="<?php $_SERVER['PHP_SELF'] ; ?>" method="post" >
                                    <tr>
                                    	<td class="celule_tabel"><label for="username">Username</label></td>
                                        <td class="celule_tabel"><input type="text" name="username" /></td>
                                    </tr>
                                    <tr>
                                    	<td class="celule_tabel"><label for="parola">Parola</label></td>
                                        <td class="celule_tabel"><input type="password" name="parola" /></td>
                                    </tr>
                                    <tr>
                                    	<td class="celule_tabel"></td>
                                        <td class="celule_tabel"><input type="submit" name="submit" value="Log in" /></td>
                                        <td><input type="hidden" name="trimis" value="ok"/></td>
                                    </tr>
                                    
                                    </form>                                
                                </table>
                            
                            </div>                          
                            
                        
                        </div>
                        
                        
                		<div id="logo">
                            	<img src="pics/logo_teamxpert_sportevents_mail.png" alt="TeamXpert" />
                        </div>
                
                </div>



</body>
</html>
<?php
ob_flush();
?>
