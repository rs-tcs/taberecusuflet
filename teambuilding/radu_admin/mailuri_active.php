<?php
if(!$_SESSION['username']){
		
		header("Location: index.php");
		}
	else
	{
		$user = $_SESSION['username'];
	}

	$select = "SELECT `email` FROM `inscrisi_teamexpert` WHERE `activ` = 1";
	$result = $db->execute($select);
	$emails = array();
	while($row = $db->getObject($result)){
		
		array_push($emails, $row['email']);
		
		}
		
	
?>

	<img id="close_div" src="pics/cross_close.png" onClick="animOut();" />
    
	<table cellpadding="2px" cellspacing="2px" id="trimite_mail">
    
    	<tr>
             <td colspan="2">Adresele de email a participanților care nu au făcut unsubscribe </td>
        </tr>
    
    	<tr>
        	<td class="celula">Email-uri</td>
            <td class="celula"><textarea><?php if(!empty($emails)){echo implode(",", $emails);} else{echo "Nu ai adrese de email active";} ?></textarea></td>
        </tr>
        
    </table>
		
