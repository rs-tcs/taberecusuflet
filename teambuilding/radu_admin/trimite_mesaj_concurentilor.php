<?php
if(!$_SESSION['username']){
		
		header("Location: index.php");
		}
	else
	{
		$user = $_SESSION['username'];
	}
	
error_reporting(0);
		
			$tabel = $_GET['tabelid'];
			$select = "SELECT `flag` FROM `tabel_competitii` WHERE `nume_competitie` = '".$tabel."'";
			$res = $db->execute($select);
			$row = mysqli_fetch_assoc($res);
			$flag = $row['flag'];
?>
        	<img id="close_div" src="pics/cross_close.png" onClick="animOut();" />
        	<table cellpadding="2px" cellspacing="2px" id="trimite_mail">
            	<?php
					if(isset($_GET['message'])){
						
						echo "<tr><td colspan=\"2\">{$_GET['message']}</td></tr>";
						
						}
				?>
            	<form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
             	
                <tr>
                	<td colspan="2">Trimite mesaj participanților din competiția <?php echo $tabel; ?> </td>
                </tr>
                <tr>
                	 <td class="spacer" style="height:20px"></td>
                </tr>
                <tr>
                	<td class="celula" style="text-align:left">TO:</td>
                    <td class="celula">
                    	<?php
							$select = "SELECT `email` FROM `".$tabel."` Where email <> ''";
		
							$result = $db->execute($select);
							$email = array();
		
								if($result){		
			
									while($row = mysqli_fetch_array($result)){
				
									array_push($email, $row['email']);
				
									}		
									}
									else
									{
									echo "Nu ai adrese de email";	
									}
                    	?>
                        <input type="email" name="to" value="<?php if(!empty($email)){echo $email[0];}else{echo "Nu ai adrese de email";}?>" required />
                    </td>
                </tr>
                	 <td class="celula" style="text-align:left">BCC: </td>
                     <td class="celula">
                     <?php unset($email[0]); $email = implode(", ", $email);?>
                     <textarea name="bcc" rows="10"><?php if(!empty($email)){echo $email;}else{echo "Nu ai adrese de email";}?></textarea>
                     </td>
                <tr>
                	 <td class="celula" style="text-align:left">Subiect</td>
                     <td class="celula"><input type="text" name="subject" required /></td>
                </tr>
                <tr>
                	 <td class="celula" style="text-align:left">Mesaj</td>
                     <td class="celula"><textarea name="mesaj" rows="5" required></textarea></td>
                </tr>
                <tr>
                	 <td colspan="2" style="text-align:center">
                     <input type="submit" name="mail" value="Trimite mesaj" />
                     <input type="hidden" name="flag" value="<?php echo $_GET['flag'];?>" />
                     </td>
                </tr>
                <tr>
                	 <td class="spacer" style="height:20px"></td>
                </tr>
                
            	</form>
            </table>
            
		<?php
		
			if(isset($_POST['mail'])){
				
				$to = $_POST['to'];
				$bcc = $_POST['bcc'];
				$subject = $_POST['subject'];
				$body_message = $_POST['mesaj'];
				$numele = "TeamXpert Sport Events";
				$from = "office@teamexpert.ro";
				
				$headers = "FROM: $numele {$from} \r\n";
				$headers.= "Reply-To:{$from} \r\n";
				$headers.= "CC: \r\n";
				$headers.= "Bcc: ".$bcc." \r\n";
				$headers.= "X-Mailer: PHP/".phpversion()."\r\n";
				$headers.= "MIME-Version: 1.0\r\n";
				$headers.= "Content-Type: text/html; charset=UTF-8 \r\n";
				$headers.= "Content-Transfer-Encoding: 8bit\n"; 
				
				$result=mail($to,$subject,$body_message,$headers);
				
				
				
				if($result){
					
					switch ($flag){
						
						case "Cupa Malinului":
						$mesaj = base64_encode("Mesajul a fost trimis cu succes participanților");
						header('Location: detalii_tabel_competitie_freeride.php?succes='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);
						break;
						
						case "Comana Bike Fest":
						case "Cupa Veseliei":
						case "Cupa 1 Iunie":
						case "Cupa Tabere cu Suflet":
						$mesaj = base64_encode("Mesajul a fost trimis cu succes participanților");
						header('Location: detalii_tabel_competitie_bikefest.php?succes='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);
						break;
						
						case "Maraton Tabere cu Suflet":
						$mesaj = base64_encode("Mesajul a fost trimis cu succes participanților");
						header('Location: detalii_tabel_competitie_maraton.php?succes='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);
						break;
						
						case "On Top of the World":	
						$mesaj = base64_encode("Mesajul a fost trimis cu succes participanților");
						header('Location: detalii_tabel_competitie_sinaia.php?succes='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);
						break;
						
						// de continuat
						
					}
					
				}
				else						
				{
					switch ($flag){
							
						case "Cupa Malinului":	
						$mesaj = base64_encode("Eroare de transmitere, problema cu serverul de mail");
						header('Location: detalii_tabel_competitie_freeride.php?eroare='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);
						break;
						
						case "Comana Bike Fest":
						case "Cupa Veseliei":
						case "Cupa 1 Iunie":
						case "Cupa Tabere cu Suflet":
						$mesaj = base64_encode("Eroare de transmitere, problema cu serverul de mail");
						header('Location: detalii_tabel_competitie_bikefest.php?eroare='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);	
						break;
						
						case "Maraton Tabere cu Suflet":
						$mesaj = base64_encode("Eroare de transmitere, problema cu serverul de mail");
						header('Location: detalii_tabel_competitie_maraton.php?eroare='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);	
						break;
						
						case "On Top of the World":
						$mesaj = base64_encode("Eroare de transmitere, problema cu serverul de mail");
						header('Location: detalii_tabel_competitie_sinaia.php?eroare='.$mesaj.'&tabelid='.$tabel.'&flag='.$_POST['flag']);	
						break;
						
						
						// de continuat	
						}
					
				}
					
					
			} // sfarsit isset trimite mesaj
				
		
		
			
?>