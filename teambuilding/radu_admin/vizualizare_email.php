<?php
ob_start();
if(!$_SESSION['username']){
		
		header("Location: index.php");
		}
	else
	{
		$user = $_SESSION['username'];
	}
	
error_reporting(0);

			$id = $_GET['id'];
			$query = "SELECT * FROM `mesaje_email` WHERE `id` = {$id}";
			$insert = "SET NAMES 'utf8'";
			$db->execute($insert);
			$result = $db->execute($query);
?>


			<img id="close_div" src="pics/cross_close.png" onClick="animOut();" />
            
            <table cellpadding="2px" cellspacing="2px" id="vizualizare_mail">
            		<?php
						if($db->getCount($result) > 0){
							
							$row = $db->getAssoc($result);
							
							?>
                            <tr>
                            	<td style="font-weight:bold; font-size:24px">Vizualizare email confirmare <?php echo $row['flag_competitie']; ?></td>
                            </tr>
                            <tr>
                            	<td style="text-align:left"><?php echo base64_decode(htmlspecialchars($row['continut_email'])); ?></td>
                            </tr>
							<?php
							}
					?>
            
            </table>

